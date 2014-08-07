<?php require_once("../includes/sessions.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php require_once("../includes/validation-functions.php"); ?>
<?php require_once("../includes/db-connection.php"); ?>
<?php include('../includes/layouts/header.php'); ?>

<?php $page = "new-quiz.php"; ?>





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
        <h1>New Quiz: <small>Control Panel</small></h1>
        <ol class="breadcrumb">
          <li><a href="dashboard.php"><i class="icon-dashboard"></i> Dashboard</a></li>
          <li class="active"><i class="icon-file-alt"></i> New Quiz</li>
        </ol>
        <hr />
      </div><!-- /.col-lg-12 -->
    </div><!-- /.row -->

    <!-- Display conditional error message -->
    <?php 
      if(!empty($message)) { 
    
        echo "<div class=\"alert alert-dismissable alert-danger col-lg-4 col-lg-offset-1 pull-left\">";
        echo "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">×</button>";
        echo "<strong><?php echo $message; ?></strong>";
        echo "</div>";
      }
    ?>
    <?php echo display_form_errors($error_messages); ?>
  
      <div class="clearfix"></div>
        <div class="panel tbp-panel-inverse">
          <div class="panel-heading">
            <h3 class="panel-title"><strong>Quiz Name</strong></h3>
          </div>
          <div class="panel-body">
             <form class="form-horizontal" role="form" action="process-new-quiz.php" method="post">
              <div class="form-group">
                <label for="quiz_name" class="col-lg-2 control-label">Quiz Name</label>
                <div class="col-lg-10">
                  <input type="text" class="form-control" id="quiz_name" name="quiz_name" placeholder="Quiz Name">
                </div>
              </div>
              <div class="form-group">
                <label for="category" class="col-lg-2 control-label">Quiz Category</label>
                <div class="col-lg-10">
                  <input type="text" class="form-control" id="category" name="category" placeholder="Quiz Category (ex. CINS 370)">
                </div>
              </div>
              <div class="form-group">
                <label for="deadline" class="col-lg-2 control-label">Quiz Deadline</label>
                <div class="col-lg-10">
                  <input type="text" class="form-control" id="deadline" name="deadline" placeholder="2014-05-15 23:59:59">
                </div>
              </div>
              <div class="form-group">
                <label for="attempts" class="col-lg-2 control-label">Attempts Allowed</label>
                <div class="col-lg-10">
                  <input type="text" class="form-control" id="attempts" name="attempts" placeholder="1">
                </div>
              </div>
              <div class="form-group">
                <div class="col-lg-offset-10 col-lg-2">
                  <button type="submit" class="btn btn-lg btn-primary pull-right" id="submit-new-quiz" name="submit-new-quiz"
                           value="submit-new-quiz">Start Adding Questions</button>
                </div>
              </div>
            </form>
          </div>
      </div>
        
       

  </div><!-- /#page-wrapper -->

<?php include('../includes/layouts/footer.php'); ?>