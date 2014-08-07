<?php require_once("../includes/sessions.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php require_once("../includes/validation-functions.php"); ?>
<?php require_once("../includes/db-connection.php"); ?>
<?php include('../includes/layouts/header.php'); ?>

<?php $page = "new-user.php"; ?>

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
        <h1>New User <small>Control Panel</small></h1>
        <ol class="breadcrumb">
          <li><a href="dashboard.php"><i class="icon-dashboard"></i> Dashboard</a></li>
          <li class="active"><i class="icon-file-alt"></i> New User</li>
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
      <div class="col-lg-offset-1 col-lg-4">
        
        <form class="form-horizontal" action="process-new-user.php" method="post">
          <!-- Text input-->
          <div class="form-group">
            <label for="first_name">First Name</label>
              <input class="form-control" id="first_name" name="first_name" value="<?php echo isset($form_values['first_name']) ? $form_values['first_name'] : '' ?>" type="text" placeholder="Enter First Name" class="input-xlarge">
          </div>

          <!-- Text input-->
          <div class="form-group">
            <label for="last_name">Last Name</label>
              <input class="form-control" id="last_name" name="last_name" value="<?php echo isset($form_values['last_name']) ? $form_values['last_name'] : '' ?>" type="text" placeholder="Enter Last Name" class="input-xlarge">
          </div>

          <!-- Text input-->
          <div class="form-group">
            <label for="email">Email</label>
              <input class="form-control" id="email" name="email" value="<?php echo isset($form_values['email']) ? $form_values['email'] : '' ?>" type="text" placeholder="Enter Email Address" class="input-xlarge">
          </div>

          <!-- Text input-->
          <div class="form-group">
            <label for="username">Username</label>
              <input class="form-control" id="username" name="username" value="<?php echo isset($form_values['username']) ? $form_values['username'] : '' ?>" type="text" placeholder="Create a Username" class="input-xlarge">
          </div>

          <!-- Password input-->
          <div class="form-group">
            <label for="password">Password</label>
              <input class="form-control" id="password" name="password" type="password" placeholder="Create a Password" class="input-xlarge">
          </div>

          <!-- Select Basic -->
          <div class="form-group">
            <label for="user_type">Select User Type</label>
              <select class="form-control" id="user_type" name="user_type" class="input-xlarge" value="">
                <option value="admin">Administrator</option>
                <option value="teacher">Teacher</option>
                <option value="student">Student</option>
              </select>
          </div>

          <!-- Button (Double) -->
          <div class="form-group pull-right">
            <label for="submit-new-user"></label>
              <button type ="submit" id="submit-new-user" name="submit-new-user" value="submit-new-user" class="btn btn-primary">Create New User</button>
              <button id="reset-new-user" name="reset-new-user" onclick="resetNewUserForm()" class="btn btn-default">Reset</button>
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