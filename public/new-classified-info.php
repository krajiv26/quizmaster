<?php require_once("../includes/sessions.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php require_once("../includes/validation-functions.php"); ?>
<?php require_once("../includes/db-connection.php"); ?>
<?php include('../includes/layouts/header.php'); ?>

<?php $page = "new-classified-info.php"; 
$cat = get_all_category();

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
        <h1>New Classified Info <small>Control Panel</small></h1>
        <ol class="breadcrumb">
          <li><a href="dashboard.php"><i class="icon-dashboard"></i> Dashboard</a></li>
          <li class="active"><i class="icon-file-alt"></i> New Classified Info</li>
        </ol>
        <hr />
      </div><!-- /.col-lg-12 -->
    </div><!-- /.row -->

    <!-- Display conditional messages and/or errors -->
    <?php $error_messages = errors(); ?>
    <?php $form_values = form_history() ?>
    <?php echo display_form_errors($error_messages); ?>
    <?php echo user_form_info_msg(); ?>
    <?php echo user_form_failure_msg(); ?>
      
      
      <div class="clearfix"></div>
      <div class="col-lg-offset-1 col-lg-8">
        
        <form class="form-horizontal" action="process-classified-info.php" method="post">

          <div class="form-group">
            <label for="title">Title</label>
              <input class="form-control" id="title" name="title" value="<?php echo isset($form_values['title']) ? $form_values['title'] : '' ?>" type="text" placeholder="Enter Title" class="input-xlarge">
          </div>
          <div class="form-group">
            <label for="category control-label">Category</label>
            <select name="category">
                <?php foreach($cat as $k=>$v)
                {
                        echo '<option value="'.$k.'">'.$v.'</option>';
                }?>
            </select>
	 </div>  
          <div class="form-group">

            <label for="description">Description</label>
            <textarea class="form-control" rows="15" id="description" name="description"  placeholder="Ex. Table structure" class="input-xlarge"><?php echo isset($form_values['description']) ? $form_values['description'] : '' ?></textarea>

          </div>

          

          <!-- Button (Double) -->
          <div class="form-group pull-right">
            <label for="submit-new-user"></label>
              <button type ="submit" id="submit-new-info" name="submit-new-info" value="submit-new-info" class="btn btn-primary">Create New Classified Info</button>
              <button id="reset-new-user" name="reset-new-info" onclick="resetNewUserForm()" class="btn btn-default">Reset</button>
          </div>
        </form>
      </div><!-- /.col-lg-6 -->
    </div><!-- /.row -->
  </div><!-- /#page-wrapper -->

<script>
  function resetNewUserForm() {
    location.reload();
  }
</script>
<?php include('../includes/layouts/footer.php'); ?>
<?php require_once("../includes/db-connection-close.php"); ?>