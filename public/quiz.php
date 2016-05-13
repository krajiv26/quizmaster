<?php require_once("../includes/sessions.php"); ?>
<?php require_once("../includes/db-connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php $page = "quiz.php"; 
error_reporting(E_ALL);
ini_set("display_errors",1);
//pr($_SESSION,1);
$cat = get_all_category();
if(isset($_GET['quiz_id'])) $_SESSION['user']['quiz']['quiz_id'] = $_GET['quiz_id'];

if(isset($_SESSION['user']['quiz']['quiz_id']) && $_SESSION['user']['quiz']['quiz_id'] !="")
 $quiz_id = $_SESSION['user']['quiz']['quiz_id'];
else
	redirect_to("take-quizzes.php");
?>
<style>
.correct{color:#008000;}
.your-ans{border:1px solid; padding-right:20px;}

</style>
<!-- Query database for all users -->
<?php 

$questions_list = get_quiz_questions($quiz_id); 
$allowedTime = get_allowed_time($quiz_id); 
$total = $questions_list->num_rows;
/*
parse_str($_SERVER["QUERY_STRING"], $output);
unset($output['page']);
$get_query = http_build_query($output, '', '&amp;');



$targetpage = "quiz.php?$get_query"; //your file name
$limit = 10; //how many items to show per page
$p = (isset($_GET['page']))? $_GET['page']:"";

if($p)
	$start = ($p - 1) * $limit; //first item to display on this page
else
	$start = 0;


$questions_list = get_quiz_questions_limit($quiz_id,$start,$limit);

$pagination_html = pagination($targetpage,$total,$limit,$p);*/
$begin_time = time();
$active =1;


if(isset($_SESSION['user']['quiz']['qs_id']) && $_SESSION['user']['quiz']['qs_id'] != "")
{
	
	
	
	$quiz_score  = get_quiz_score($_SESSION['user']['quiz']['qs_id']);
    
    $begin_time = time() - $quiz_score['lapse_time'];
    
	$data = @unserialize($quiz_score['answer_sl']);
	$active = $quiz_score['active'];
	unset($data['active']);
	unset($data['begin_time']);
	//pr($data);
	//echo "<br>active".$active;
	$question_data = array();
	foreach($data as $k => $v)
	{
		$key = substr($k,9);
		if(intval($key) == 0) continue;  //other than quesiton_* will be removed
		$question_data[substr($k,9)] = $v;
	}
	if($active == 0)
	{
		$final_result['question_attempted'] = count($question_data);
		$fquestion_data = array();
		$q_list = get_quiz_questions_id($quiz_id);
		//pr($q_list,1);
		foreach($q_list as $k =>$v)
		{
			$fquestion_data[$k] = isset($question_data[$k])?$question_data[$k]:"";
		}
		$answer_data = get_correct_answers($fquestion_data);
		//pr($question_data);
		//pr($answer_data);
		$final_result['total_question'] = $total;
		$final_result['correct_answer'] = get_score($fquestion_data,$answer_data);
		$final_result['result_percentage'] = ($final_result['correct_answer']*100)/$total;
		//pr($final_result);
		save_score($_SESSION['user']['quiz']['qs_id'],$final_result['result_percentage']);

	}
}


//if(isset($_POST['submit-quiz-question'])) {
if(isset($_POST['begin_time'])) {
	
	$_POST = array_map('addslashes',$_POST);
    $_POST = array_map('htmlentities',$_POST);
	$lapse_time = time() - $_POST['begin_time'];
	$user_id = $_SESSION['user']['user_id'];
	$active = $_POST['active'];
	$answer_sl_array = $_POST;
	$answer_sl = serialize($_POST);
	$score ='0.00';
	//echo "printing post";
	//pr($_POST);
	if(isset($_SESSION['user']['quiz']['qs_id']) && $_SESSION['user']['quiz']['qs_id'] > 0)
	{
		echo $query1 = "UPDATE quiz_score SET lapse_time = {$lapse_time},answer_sl ='{$answer_sl}',active = {$active} WHERE qs_id = {$_SESSION['user']['quiz']['qs_id']}";
		//exit;
		$result1 = mysqli_query($db, $query1);
		if ( false === $result ) {
		  // Query failed. Print out information.
		  printf("error: %s\n", mysqli_error($db));
		  $_SESSION["message"] = mysqli_error($db).". submitting quiz.";
		  redirect_to("quiz.php");
		}
		else
		{
			$_SESSION["message"] = "Quiz saved successfully.";
			redirect_to("quiz.php");
		}
	}
	else
	{
		echo $query2 = "INSERT INTO quiz_score (user_id, quiz_id,score,lapse_time,answer_sl,active) VALUES ( {$user_id},{$quiz_id}, {$score},{$lapse_time},'{$answer_sl}','{$active}' )";
		//exit;
		
		$result2 = mysqli_query($db, $query2);
		if($result2 && mysqli_affected_rows($db) != -1) {
			  // Success
			  $current_qs_id = mysqli_insert_id($db);
			  $_SESSION['user']['quiz']['qs_id'] = $current_qs_id;
			  $_SESSION["message"] = "Successfully submitted!";
			  redirect_to("quiz.php");
		 } else {
			  $_SESSION["message"] = "Db faliure";
			  redirect_to("quiz.php");
		}
    }
}

?>


<?php include('../includes/layouts/header.php'); ?>

<body>

<div id="wrapper" style="padding-left:0px !important;">

  <div id="page-wrapper">
    
    <div class="row">
      <div class="col-lg-12">

       <div class="clearfix"></div>
      <div class="col-lg-12">
        <div class="row">
        <div class="panel tbp-panel-inverse" >
          <div class="panel-heading">
            <h3 class="panel-title"><strong>Instruction</strong><div style="float:right;"><span id="stopwatch">00:00:00</span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Total Count = <?php echo $total;?></div></h3>
          </div>
          <div class="panel-body">
            <div class="row">

            </div>
		  </div>
		  
		  
        </div>
        <?php //echo $pagination_html; ?>
		
          <div class="table-responsive">
            <form  action="" method="post" id="myform" name ="myform">
			<input type="hidden" value="<?php echo $begin_time;?>" name="begin_time" id ="begin_time" >
			<input type="hidden" value="<?php echo $active;?>" name="active" id ="active" >
            <table class="table table-bordered table-hover table-striped tablesorter">
              <thead>
                <tr>
                  
                  <th>ID</i></th>
                  <th>Question Text <i class="fa fa-sort"></i></th>
                  <th>Category</th>
                </tr>
              </thead>
              <tbody id="user-rows">     

              <?php
				$cnt = 0;
                while($row = mysqli_fetch_assoc($questions_list)) {
				
				$cnt++;
				$answer_set = get_question_answers($row['question_id']);
				$disabled = ($active == 0)?' disabled="disabled"':'';
				$ans_html = '<div style="padding-left:40px;">';
				$checked = (isset($question_data[$row['question_id']]) && $question_data[$row['question_id']] == $answer_set[0]['answer_id'])?" checked='checked'":"";
				$fontClass ="";
				$fontClass .= ($checked!= '')? "your-ans":"";
				
				$fontClass .= ($active == 0 && @$answer_data[$row['question_id']] == $answer_set[0]['answer_id'])?" correct":"";
				
				$ans_html .= '<input type="radio" name="question_'.$row['question_id'].'" value="'.$answer_set[0]['answer_id'].'" '.$checked.' '.$disabled.'> <span class="'.$fontClass.'">'.$answer_set[0]["answer_text"].'</span><br>' ;
				$checked = (isset($question_data[$row['question_id']]) && $question_data[$row['question_id']] == $answer_set[1]['answer_id'])?" checked='checked'":"";
				$fontClass ="";
				$fontClass .= ($checked!= '')? "your-ans":"";
				$fontClass .= ($active == 0 && @$answer_data[$row['question_id']] == $answer_set[1]['answer_id'])?" correct":"";
			
				$ans_html .= '<input type="radio" name="question_'.$row['question_id'].'" value="'.$answer_set[1]['answer_id'].'" '.$checked.''.$disabled.'> <span class="'.$fontClass.'">'.$answer_set[1]["answer_text"].'</span><br>' ;
				$checked = (isset($question_data[$row['question_id']]) && $question_data[$row['question_id']] == $answer_set[2]['answer_id'])?" checked='checked'":"";
				$fontClass ="";
				$fontClass .= ($checked!= '')? "your-ans":"";
				$fontClass .= ($active == 0 && @$answer_data[$row['question_id']] == $answer_set[2]['answer_id'])?" correct":"";
				
				$ans_html .= '<input type="radio" name="question_'.$row['question_id'].'" value="'.$answer_set[2]['answer_id'].'" '.$checked.''.$disabled.'> <span class="'.$fontClass.'">'.$answer_set[2]["answer_text"].'</span><br>' ;
				$checked = (isset($question_data[$row['question_id']]) && $question_data[$row['question_id']] == $answer_set[3]['answer_id'])?" checked='checked'":"";
				$fontClass ="";
				$fontClass .= ($checked!= '')? "your-ans":"";
				$fontClass .= ($active == 0 && @$answer_data[$row['question_id']] == $answer_set[3]['answer_id'])?" correct":"";
				;
				$ans_html .= '<input type="radio" name="question_'.$row['question_id'].'" value="'.$answer_set[3]['answer_id'].'" '.$checked.''.$disabled.'> <span class="'.$fontClass.'">'.$answer_set[3]["answer_text"].'</span><br>' ;
				$ans_html .= '</div>' ;
				
              ?>

                  <tr>
                    <td><?php echo $cnt; ?></td>
                    <td><?php echo $row['question_text']; ?><br><?php echo $ans_html;?></td>
                    <td><?php echo $cat[$row['category']]; ?></td>
                  </tr>
              
              <?php
                }
              ?>
              <?php
                // Release the returned data
                mysqli_free_result($questions_list);
              ?>
        
              </tbody>
            </table>
            <div class="form-group pull-right" >
             <label for="submit-edit-mc-question"></label>
             <?php if($active == 0) { ?>
				 <div style="float:left;margin-right:300px;">Total Question = <?php echo $final_result['total_question'];?><br>
				 Total Attempted = <?php echo $final_result['question_attempted'];?><br>
				 Total Correct = <?php echo $final_result['correct_answer'];?><br>
				 Score = <?php echo $final_result['result_percentage'];?>%</div><div style="float:left;"><a class="btn btn-danger" href="take-quizzes.php" >Back to Quiz List</a></div>
			<?php }
			else {
			 ?>
                <div style="float:left;margin-right:500px;"><a class="btn btn-danger" data-toggle="modal" data-target="#confirm-delete-modal" >Complete Quiz</a></div> 
                   
               
              <?php } ?>
             </div>
            </form>
          </div><!-- /.table-responsive -->
          
          
          <!-- Confirm Deletion Modal -->
        <div class="modal fade" id="confirm-delete-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="confirm-delate-label">Warning!</h4>
              </div>
              <div class="modal-body">
                You are about to complete this Quiz. This action will be irreversible.<br />
                Do you wish to proceed?
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                <a onclick="getFormSubmit();" class="btn btn-primary">Yes</a>
              </div>
            </div>
          </div>
        </div><!-- /.modal fade -->
          
          
          
      </div>
    </div><!-- /.row -->

  </div><!-- /#page-wrapper -->

<?php include('../includes/layouts/footer.php'); ?>

<script>

function getFormSubmit()
{
		document.getElementById('active').value = 0;
		document.getElementById('myform').submit();
		 
}	

$('#stopwatch').timer({
			duration: '40s',
			format: '%M : %S',
			countdown: true,
			callback: function() {
				
				$('#stopwatch').timer('pause');
				getFormSubmit();
			}
});



$(window).scroll(function(){
  //$("#tbp-panel").css({"margin-top": ($(window).scrollTop()) + "px", "margin-left":($(window).scrollLeft()) + "px"});
});
</script>
<?php require_once("../includes/db-connection-close.php"); ?>
<?php if($active == 0){ ?>
<script type='text/javascript'>
$( document ).ready(function() {
	$('#stopwatch').timer('remove');
});
</script>
<?php } ?>
