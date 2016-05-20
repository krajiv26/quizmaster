<?php require_once("../includes/sessions.php"); ?>
<?php require_once("../includes/db-connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php $page = "take-quizzes.php"; ?>

<!-- Query database for all users -->
<?php 
$quiz_list = get_all_quizzes();
if($_SESSION["user"]["user_type"] == "student"){
	if(isset($_SESSION["examcode"]) && $_SESSION["examcode"] != "")	$quiz_list = get_all_quizzes($_SESSION["examcode"]); 
}




if(isset($_SESSION['user']['quiz'])) unset($_SESSION['user']['quiz']);
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
        <h1>Manage Quizzes <small>Control Panel</small></h1>
        <ol class="breadcrumb">
          <li><a href="dashboard.php"><i class="icon-dashboard"></i> Dashboard</a></li>
          <li class="active"><i class="icon-file-alt"></i> Manage Quizzes</li>
        </ol>

        <!-- Display conditional messages -->
        <?php echo user_form_success_msg(); ?>
        <?php echo delete_failure_msg(); ?>

        <div class="clearfix"></div>

        <div class="row">
          <div class="col-lg-4 pull-right">
            <form role="form">
              <label>Find Quiz By Quiz Name</label>
              <div class="form-group input-group">
                <input type="text" class="form-control" placeholder="Enter Quiz Name">
                <span class="input-group-btn">
                  <button class="btn btn-default"  type="button"><i class="fa fa-search"></i></button>
                </span>
              </div>
            </form>
          </div>
        </div>

        <h2>Quiz List</h2>
        
          <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped tablesorter">
              <thead>
                <tr>
                  
                  <th>Quiz Name <i class="fa fa-sort"></i></th>
                  <th width="10%">Category</th>
                  <th width="10%">Code</th>
                  <th width="15%">Quiz Deadline</i></th>
                  <th>Q Cnt </th>
                  <th>Last Score</th>
                  <th>Last Attempt On</th>
                  <th>History</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody id="user-rows">     

              <?php
                while($row = mysqli_fetch_assoc($quiz_list)) {
              ?>

                  <tr>
                    <td><?php echo $row['quiz_name']; ?></td>
                    <td><?php echo $row['category']; ?></td>
                    <td><?php echo $row['quiz_code']; ?></td>
                    <td><?php if($row['deadline']) { echo $row['deadline']; } else { echo 'NO DEADLINE SET'; } ?></td>
                    <td><?php echo get_quiz_question_count($row['quiz_id']); ?></td>
                    <td>
                    </td>
                    <td>
                                       
                    </td>
                    <td>
                    </td>
                    <td>
						<a href="quiz.php?quiz_id=<?php echo $row['quiz_id']; ?>">Take Now</a>
                    </td>
                  </tr>

              <?php
				
                }
              ?>
              
              <?php
                // Release the returned data
                mysqli_free_result($quiz_list);
              ?>
        
              </tbody>
            </table>
          </div><!-- /.table-responsive -->
      </div>
    </div><!-- /.row -->

  </div><!-- /#page-wrapper -->

<?php include('../includes/layouts/footer.php'); ?>
<?php require_once("../includes/db-connection-close.php"); ?>
