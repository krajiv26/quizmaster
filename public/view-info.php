<?php require_once("../includes/sessions.php"); ?>
<?php require_once("../includes/db-connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php require_once("../includes/validation-functions.php"); ?>
<?php $page = "view-info.php"; ?>

<?php include('../includes/layouts/header.php'); ?>
<link href="css/custom/dataTables.bootstrap.css" rel="stylesheet">
<?php
  // If user does not exist, redirect back to Manage Users Page
  $id = isset($_GET["info_id"]) ? $_GET["info_id"] : "";
  $user = get_classified_by_id($id);
  if(!$user) { redirect_to("manage-classified-info.php"); }
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
        <h1><?php echo $user["title"]; ?></h1>
        <ol class="breadcrumb">
          <li><a href="dashboard.php"><i class="icon-dashboard"></i> Dashboard</a></li>
          <li class="active"><i class="icon-file-alt"></i> Edit Classified Info</li>
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
      
      <!-- content -->
      <?php echo html_entity_decode($user["description"]); ?>
       
  
  </div><!-- /.col-lg-6 -->

  </div><!-- /#page-wrapper -->

<?php include('../includes/layouts/footer.php'); ?>

<script src="js/custom/jquery.dataTables.min.js"></script>
<script src="js/custom/dataTables.bootstrap.js"></script>
<script>
$(document).ready(function() {
    $('#example').dataTable();
} );
</script>
<?php require_once("../includes/db-connection-close.php"); ?>
