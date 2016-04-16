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

</style>
      
   <div class="col-lg-12">

      <div>
      <table  >
	  <tr><th  rowspan="2" style="width:100px;">Name</th><th  rowspan="2" style="width:100px;">Total<br>Score (%)</th><th colspan="<?php echo $quiz_q_count;?>">Question</th></tr>
	  <tr>
	  <?php for($i=1; $i <= $quiz_q_count;$i++){
			echo "<th style='width:45px;'>".$i."</th>";
			}
		?>
	  
	  </tr>
	  <?php while($row = mysqli_fetch_assoc($quiz_score)) { 
		   
		  $sc_analysis = get_score_analysis($row['answer_sl'],$row['quiz_id']);
		  $percent = get_percentage_score($sc_analysis);
		  ?>
		<tr><td><?php echo $row['user_id']; ?></td><td><?php echo $percent; ?></td>
		<?php foreach($sc_analysis as $k => $v){
			echo "<td>".$v."</td>";
			}
		?>
		
		</tr>
	
	<?php 	}	  ?>
		  
      
      </table>
      
      </div>
        
       

  </div><!-- /#page-wrapper -->

<?php include('../includes/layouts/footer.php'); ?>

<?php require_once("../includes/db-connection-close.php"); ?>
