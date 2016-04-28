<?php require_once("../includes/sessions.php"); ?>
<?php require_once("../includes/db-connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php require_once("../includes/validation-functions.php"); ?>

<?php $page = "edit-quiz.php"; ?>

<?php include('../includes/layouts/header.php'); ?>





<?php
  // If quiz does not exist, redirect back to Manage Quizzes Page
  $id = isset($_GET["quiz_id"]) ? $_GET["quiz_id"] : "";
  $quiz = get_quiz_by_id($id);


  if(!$quiz) { redirect_to("manage-quizzes.php"); }
?>
 <?php $quiz_score = get_quiz_score_details($id);
 $quiz_score1 = get_quiz_score_details($id);
 
$quiz_q_count = get_quiz_question_count($id);
  ?>
<?php



  

?>

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
        <h1>Edit Quiz: <small><?php echo $quiz['quiz_name']; ?></small></h1>
        <ol class="breadcrumb">
          <li><a href="dashboard.php"><i class="icon-dashboard"></i> Dashboard</a></li>
          <li class="active"><i class="icon-file-alt"></i> Edit Quiz</li>
        </ol>
        <hr />
      </div><!-- /.col-lg-12 -->
    </div><!-- /.row -->

    <!-- Display conditional error message -->

    <?php echo display_form_errors($error_messages); ?>
<?php if(!empty($error_messages)) { print_r($error_messages); } ?>
<?php if(!empty($message)) { print_r($message); } ?>
<style>
table, th, td {   border: 1px solid black;} 
th {background-color:#B4A682;}
td{background-color:#E0E6CF;}
th, td{	text-align:center;}
.no_row td{background-color:#F5F5F5;}

</style>
      
   <div class="col-lg-12">

		  <h2>Split-Half Reliability KR-20</h2>
      <table  >
	  <tr><th  rowspan="2" style="width:100px;">Name</th><th  rowspan="2" style="width:100px;">Total<br>Score (%)</th><th colspan="<?php echo $quiz_q_count;?>">Question</th><th  rowspan="2" style="width:50px;">Total<br>Score</th></tr>
	  <tr>
	  <?php 
	  for($i=1; $i <= $quiz_q_count;$i++){
			echo "<th style='width:45px;'>".$i."</th>";
			}
		?>
	  
	  </tr>
	  <?php 
	  $questionwise_array = array();
	  $userCnt = 0;
	  $total_score = array();
	  while($row = mysqli_fetch_assoc($quiz_score)) { 
		   $userCnt++;
		  $sc_analysis = get_score_analysis($row['answer_sl'],$row['quiz_id']);
		  //pr($sc_analysis);
		  
		  foreach($sc_analysis as $key => $val){
		  $questionwise_array[$key][] = $val;
			}
			
		  $percent = get_percentage_score($sc_analysis);
		  ?>
		<tr><td><?php $user = get_user_by_id($row['user_id']); echo $user['username']; ?></td><td><?php echo intval($percent); ?></td>
		<?php foreach($sc_analysis as $k => $v){
			echo "<td>".$v."</td>";
			}
		?>
		<td style="background-color:#fff;"><?php echo $total_score[] = array_sum($sc_analysis); ?></td>
		</tr>
	
	<?php 	}	  ?>
		<tr class="no_row" ><td><strong>No of 1's</strong></td><td> </td>
		<?php foreach($questionwise_array as $k => $v){
			echo "<td>".array_sum($v)."</td>";
			}
		?>
		<td style="background-color:#fff;"></td>
		</tr>  
		<tr ><td >Proportion Passed (p)</td><td> </td>
		<?php foreach($questionwise_array as $k => $v){
			echo "<td>".(array_sum($v)/$userCnt)."</td>";
			}
		?>
		<td style="background-color:#fff;"></td>
		</tr>
		<tr class="no_row"><td >Proportion Failed (q)</td><td> </td>
		<?php foreach($questionwise_array as $k => $v){
			echo "<td>".(1 - (array_sum($v)/$userCnt))."</td>";
			}
		?>
		<td style="background-color:#fff;"></td>
		</tr>
        <tr ><td > p x q </td><td> </td>
		<?php 
		$sumPQ = 0;
		foreach($questionwise_array as $k => $v){
			$sumPQ += ((array_sum($v)/$userCnt)*(1 - (array_sum($v)/$userCnt)));
			echo "<td>".((array_sum($v)/$userCnt)*(1 - (array_sum($v)/$userCnt)))."</td>";
			}
			$st_dev = mystats_standard_deviation($total_score);
		?>
		<td style="background-color:#fff;"><strong><?php echo $sumPQ;?></strong></td>
		</tr>
		<tr><td colspan="20"><div style="text-align:left;margin-left:100px;"><strong>k = <?php echo $quiz_q_count; ?> i.e No. of Questions<br>
		 	&Sigma;pq = <?php echo $sumPQ; ?> i.e Summation of p x q<br>
		 	&sigma;<sup>2</sup> = <?php echo $st_dev*$st_dev; ?> i.e square of standard deviation of Total score for each student<br>
		 	r<sub>KR20</sub> = [ k/(k-1) ] * [ 1- (&Sigma;pq/&sigma;<sup>2</sup>) ]<br>
		 	r<sub>KR20</sub> = [ <?php echo $quiz_q_count; ?>/(<?php echo $quiz_q_count; ?>-1) ] * [ 1- (<?php echo $sumPQ; ?>/<?php echo $st_dev*$st_dev; ?>) ]<br>
		    r<sub>KR20</sub> = [ <?php echo ($quiz_q_count/($quiz_q_count -1));?> ] * [ <?php echo (1- ($sumPQ/($st_dev*$st_dev))); ?> ]<br><br>
		    r<sub>KR20</sub> = <?php echo ($quiz_q_count/($quiz_q_count -1)) * (1- ($sumPQ/($st_dev*$st_dev)));?><br><br>
		
		</strong></div></td></tr>
		
      </table>
      
      </div>
      <!--- -->  
       <div class="col-lg-12">

		  <h2>Difficulty Index and the Discrimination Index</h2>
      <table  >
	  <tr><th   style="width:100px;">Item</th><th  style="width:100px;"># Correct<br>(Upper group)</th>
	  <th ># Correct<br>(Lower group)</th><th >Difficulty<br>(p)</th><th >Discrimination<br>(D)</th></tr>
	  
	  <?php 
	  $difficultyIndex = get_questionwise_difficulty_index($questionwise_array,$userCnt);
		foreach($difficultyIndex as $k=>$di){
	  ?>
		 <tr><td>Question<br><?php echo ($k+1);?></td><td><?php echo $di['c_u_grp'];?></td><td><?php echo $di['c_l_grp'];?></td><td><?php echo $di['difficulty'];?></td><td><?php echo $di['dicrimination'];?></td></tr> 
      <?php }?>
      </table>
      
      </div>
     

  </div><!-- /#page-wrapper -->

<?php include('../includes/layouts/footer.php'); ?>

<?php require_once("../includes/db-connection-close.php"); ?>
