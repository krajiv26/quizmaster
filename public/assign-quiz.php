<?php require_once("../includes/sessions.php"); ?>
<?php require_once("../includes/db-connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php require_once("../includes/validation-functions.php"); ?>

<?php $page = "assign-quiz.php"; ?>

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
        <h1>Assign Quiz: <small>Control Panel</small></h1>
        <ol class="breadcrumb">
          <li><a href="dashboard.php"><i class="icon-dashboard"></i> Dashboard</a></li>
          <li class="active"><i class="icon-file-alt"></i> Assign Quiz</li>
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
            <h3 class="panel-title"><strong>Assign Quiz</strong></h3>
          </div>
          <div class="panel-body">
             <form class="form" role="form">
              <div class="row">
                <div class="col-lg-6">
                  <label class="control-label">Select a Group that you Would Like to Assign this Quiz to</label>
                  <div>
                  <select class="form-control">
                    <option>Group 1</option>
                    <option>Group 2</option>
                  </select>
                  </div>
                </div>
              </div>
              <br />
              <div class="col-lg-6">
              <div class="form-group pull-right">
                <a href="manage-questions.php" id="cancel-assign-question" name="cancel-assign-question" class="btn btn-default">Cancel</a>
                <button type="submit" class="btn btn-primary">Assign Quiz Now</button>
              </div>
            </div>
            </form>
          </div>
      </div>
        
       

  </div><!-- /#page-wrapper -->

<?php include('../includes/layouts/footer.php'); ?>
