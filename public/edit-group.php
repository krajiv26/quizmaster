<?php require_once("../includes/sessions.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php require_once("../includes/validation-functions.php"); ?>
<?php require_once("../includes/db-connection.php"); ?>
<?php include('../includes/layouts/header.php'); ?>
<?php $page = "edit-group.php"; ?>

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
  <?php
  $groups = get_groups($_GET['user_id']);
 ?>
  <div id="page-wrapper">

    <div class="row">
      <div class="col-lg-12">
        <h1>Edit Group: <small>Control Panel</small></h1>
        <ol class="breadcrumb">
          <li><a href="dashboard.php"><i class="icon-dashboard"></i> Dashboard</a></li>
          <li class="active"><i class="icon-file-alt"></i> Edit Group</li>
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
            <h3 class="panel-title"><strong>Group Name</strong></h3>
          </div>
          <div class="panel-body">
             <form class="form-horizontal" role="form" action="process-group.php" method="post">
              <div class="form-group">
                <label for="new-quiz-name-field" class="col-lg-2 control-label">Add Members to this Group</label>
                <div class="col-lg-10">
                  <textarea class="form-control" rows="10" id="add-group-members-textarea" name="add-group-members-textarea" value=""
                          placeholder='jdoe@bingmail.com;janedoe@example.com;demostudent39@someuniversity.edu' class="input-xlarge"><?php echo isset($groups['grp_emails']) ? $groups['grp_emails'] : '' ?></textarea>
                          <p class="help-block">Assign individuals to this group by the email address they used to create their Test Builder Pro account.</p>
                          <p class="help-block">Separate each email address with a semi-colon.</p>
                </div>
              </div>
              <div class="form-group">
                <div class="col-lg-12">
                  <div class="pull-right">
                  <input type="hidden" name="grp_id" value="<?php echo $_GET['user_id']?>">
                    <button type ="submit" id="submit-edit-group" name="submit-edit-group" value="submit-edit-group" class="btn btn-primary">Update Group</button>
                    <a href="manage-groups.php" id="cancel-edit-group" name="cancel-edit-group" class="btn btn-default">Cancel</a>
                    <!-- Button trigger modal -->
                    <a class="btn btn-danger" data-toggle="modal" data-target="#confirm-delete-modal">Delete Group</a> 
                  </div>
                </div>
              </div>
            </form>
          </div>
      </div>
        
       

  </div><!-- /#page-wrapper -->

<?php include('../includes/layouts/footer.php'); ?>
