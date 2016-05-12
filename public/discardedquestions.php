<?php require_once("../includes/sessions.php"); ?>
<?php require_once("../includes/db-connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php $page = "discardedquestions.php"; 
error_reporting(E_ALL);
ini_set("display_errors",1);
$cat = get_all_category();
$_SESSION["get_query"] = "";
$_SESSION["get_query"] = $_SERVER["QUERY_STRING"];
?>
<style>
/*.correct{color:#008000;}*/
.correct{color:#ff00ff;font-weight:bold;}
.your-ans{border:1px solid; padding-right:20px;}
a:hover{text-decoration: none !important;}
</style>
<!-- Query database for all users -->
<?php 
//where script Start
$where = "";
$key = (isset($_GET['key']))? $_GET['key']:"";
if($key !="")
{
	if($where != "")
		$where .= " AND question_text like '%{$key}%'";
	else
		$where .= " question_text like '%{$key}%' AND is_discarded = 1";
}

$cat_id = (isset($_GET['category']))? $_GET['category']:"";
if($cat_id !="")
{
	if($where != "")
		$where .= " AND category = {$cat_id}";
	else
		$where .= " category = {$cat_id} AND is_discarded = 1";
}

$ans = (isset($_GET['ans']))? intval($_GET['ans']): 0;


if($ans > 0)
{
	
	$ans = ($ans == 2)? 0 : $ans;
	if($where != "")
		$where .= " AND is_answered = {$ans}";
	else
		$where .= " is_answered = {$ans} AND is_discarded = 1";
	$ans = ($ans === 0)? 2 : $ans;
}


//where script ends
$questions_list = get_all_questions_discarded($where); 
$total = $questions_list->num_rows;

parse_str($_SERVER["QUERY_STRING"], $output);
unset($output['page']);
$get_query = http_build_query($output, '', '&amp;');



$targetpage = "manage-questions.php?$get_query"; //your file name
$limit = 10; //how many items to show per page
$p = (isset($_GET['page']))? $_GET['page']:"";

if($p)
	$start = ($p - 1) * $limit; //first item to display on this page
else
	$start = 0;


$questions_list = get_all_questions_limit_discarded($start,$limit,$where);

$pagination_html = pagination($targetpage,$total,$limit,$p);
?>


<?php include('../includes/layouts/header.php'); ?>

<body>

<div id="wrapper">

  <!-- Sidebar -->
  <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <!-- Brand and toggle get grouped for better mobile display -->
    <?php include('../includes/layouts/admin-head.php'); ?>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse navbar-ex1-collapse">
      <?php include('../includes/layouts/admin-side-nav.php'); ?>

      <?php include('../includes/layouts/admin-profile.php'); ?>
    </div><!-- /.navbar-collapse -->
  </nav>
  <div id="page-wrapper">
    
    <div class="row">
      <div class="col-lg-12">
        <h1>Discarded Questions <small>Control Panel</small></h1>
        <ol class="breadcrumb">
          <li><a href="dashboard.php"><i class="icon-dashboard"></i> Dashboard</a></li>
          <li class="active"><i class="icon-file-alt"></i> Discarded Questions</li>
        </ol>

        <?php echo user_form_success_msg(); ?>
        <?php echo delete_failure_msg(); ?>

        <div class="clearfix"></div>

       <div class="clearfix"></div>
      <div class="col-lg-12">
        <div class="row">
        <div class="panel tbp-panel-inverse">
          <div class="panel-heading">
            <h3 class="panel-title"><strong>Filter Questions</strong></h3>
          </div>
          <div class="panel-body">
            <div class="row">
	 <form name="search" id="search" action="" method="get">
              <div class="col-lg-3">
                <label class="control-label">Display</label>
                <div>
                <select class="form-control" name="ans" id="ans">
                  <option value="" >All</option>
                  <option value="1" <?php if($ans == 1) echo 'selected="selected"';?>>Answered</option>
                  <option value="2" <?php if($ans == 2) echo 'selected="selected"';?>>Un-answered</option>
                </select>
                </div>
              </div>

              <div class="col-lg-3">
                <label class="control-label">Category</label>
                <div>
                <select name="category" class="form-control" id="category">
					<option value="">All</option>
					<?php foreach($cat as $k=>$v)
					{
						$sel = ($k == $cat_id)? 'selected="selected"':'';
						echo '<option value="'.$k.'" '.$sel.'>'.$v.'</option>';
					}?>
				</select>
                </div>
              </div>

              <div class="col-lg-3">
                  <label class="control-label">Search</label>
                  <div class="input-group">
                    <input type="text" class="form-control" placeholder="Search by Keyword" name="key" id="key" value="<?php echo $key;?>">
                    <span class="input-group-btn">
                      <button class="btn btn-default"  type="button" onclick="document.getElementById('search').submit();"><i class="fa fa-search"></i></button>
                    </span>
                  </div>
              </div>
             
              <div class="col-lg-3">
                  <label class="control-label">&nbsp;</label>
                  <div class="input-group" style="margin-left:50%;">
                    <button type ="reset" id="reset-search" name="reset-search" value="reset-search" class="btn btn-primary pull-right tbp-flush-right" onclick="window.location.href = 'manage-questions.php';">Reset Filter</button>
                  </div>
              </div>
               
	    </form>
            </div>
          
	</div>
		  
		  
        </div>
             
           
        <h2>Question List (<?php echo $total;?>) </h2>
        <?php echo $pagination_html;  ?>
	<form name="table_select" id="table_select" action="assign-question.php" method="post">
            <div style="float:left;clear:both;">
                <button type ="button"  id="show-answer" name="show-answer" value="show-answer" class="btn btn-primary pull-right tbp-flush-right">Hide All Answer</button>
            </div>
           
            <div class="table-responsive" style="clear:both;">
            <table class="table table-bordered table-hover table-striped tablesorter">
              <thead>
                <tr>
                  <?php if(checkUserType() == 'admin') { ?><th><input type="checkbox" id="selecctall"/></th><?php } ?>
                  <th>ID</i></th>
                  <th>Question Text <i class="fa fa-sort"></i></th>
                  <th>Category</th>
                  <th>Ans</th>
                  <?php if(checkUserType() == 'admin') { ?><th>Assign</th>
                  <th>Edit</th><?php } ?>
                  <!--<th>Delete</th>-->
                </tr>
              </thead>
              <tbody id="user-rows">     

              <?php
              $answered[0] = "<span style='color:#ff0000;'>No</span>";
              $answered[1] = "Yes";
	      $cnt = 0;
              while($row = mysqli_fetch_assoc($questions_list)) {
		$cnt++;
		
		      $discardStyle = ($row['is_discarded'] == 1)?"color:#ff0000 !important;":"";
              ?>

                  <tr>
                    <?php if(checkUserType() == 'admin') { ?><td><input class="checkbox1" type="checkbox" name="check[]" value="<?php echo $row['question_id']; ?>"></td><?php } ?>
                    <td style="<?php echo $discardStyle;?>"><?php echo $row['question_id']; ?></td>
                    <td  ><a style ="cursor:pointer;<?php echo $discardStyle;?>" <?php echo $discardStyle;?> onclick="$('#ans_<?php echo $row['question_id'];?>').toggle();"><?php echo checkAnsFormat($row['question_text']); ?></a></td>
                    <td><?php echo $cat[$row['category']]; ?></td>
                    <td><?php echo $answered[$row['is_answered']]; ?></td>
                    <?php if(checkUserType() == 'admin') { ?><td>
                        <a href="assign-question.php?question_id=<?php echo htmlentities($row['question_id']); ?>" ><i class="fa fa-user fa-2x"></i></a>                
                    </td>
                    <td>
                        <a href="edit-question.php?question_id=<?php echo htmlentities($row['question_id']); ?>" ><i class="fa fa-edit fa-2x"></i></a>                
                    </td><?php } ?>
                  </tr >
                    <tr id="ans_<?php echo $row['question_id'];?>" >
                    <td colspan="6"><?php echo get_question_answers_html($row['question_id']);?></td>
                    </tr>

                  <!-- Confirm Deletion Modal -->
                  <div class="modal fade" id="confirm-delete-modal<?php echo htmlentities($row['question_id']); ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                          <h4 class="modal-title" id="confirm-delate-label">Warning!</h4>
                        </div>
                        <div class="modal-body">
                          You are about to delete a question and ALL its associated answers. This action will be irreversible.<br />
                          Do you wish to proceed?
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                          <a href="delete-questions.php?question_id=<?php echo $row["question_id"]; ?>" class="btn btn-primary">Yes</a>
                        </div>
                      </div>
                    </div>
                  </div><!-- /.modal fade -->
              
              <?php
                }
              ?>
              <?php
                // Release the returned data
                mysqli_free_result($questions_list);
              ?>
        
              </tbody>
            </table>
          </form>
        <?php echo $pagination_html;  ?>
          </div><!-- /.table-responsive -->
      </div>
    </div><!-- /.row -->

  </div><!-- /#page-wrapper -->

<?php include('../includes/layouts/footer.php'); ?>
    <script>
  $(document).ready(function() {
    $('#selecctall').click(function(event) {  //on click
        if(this.checked) { // check select status
            $('.checkbox1').each(function() { //loop through each checkbox
                this.checked = true;  //select all checkboxes with class "checkbox1"              
            });
        }else{
            $('.checkbox1').each(function() { //loop through each checkbox
                this.checked = false; //deselect all checkboxes with class "checkbox1"                      
            });        
        }
    });
    
    
   $('#assign-question').click(function(event) {  //on click
       var selected = 0;
            $('.checkbox1').each(function() { //loop through each checkbox
               if(this.checked == true)
                   selected = selected + 1;
            });
        if(selected > 0)
            return true;
        alert("Please Select At least one question.");
        return false;
    });
   
   $('#show-answer').click(function(event) {  //on click
      if($(this).html() == "Hide All Answer"){
          $(this).html("Show All Answer");
          $( "tr[id^='ans_']" ).hide();
      }
      else {
          $(this).html("Hide All Answer");
          $( "tr[id^='ans_']" ).show();
      }
      
    });
});
  </script>
<?php require_once("../includes/db-connection-close.php"); ?>
