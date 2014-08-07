<?php require_once("../includes/sessions.php"); ?>
<?php include('../includes/layouts/header-front.php'); ?>
<?php $page = "index.php"; ?>

<body>

<div id="wrapper">

  <div id="page-wrapper">
	<!-- Display conditional error message -->
    <!-- Display conditional messages -->
        <?php echo user_form_success_msg(); ?>
        <?php echo delete_failure_msg(); ?>
	<div class="clearfix"></div>
    <div class="container" style="width:30%">
        <p></p>
        <form class="form-signin" method="post" action="process-login.php">
        <h2 class="form-signin-heading">Please log in</h2>
		<br/>
        <input type="text" name="username" class="form-control" placeholder="Username" autofocus>
		<br/>
        <input type="password" name="password" class="form-control" placeholder="Password">
        <br/>
        <button class="btn btn-lg btn-primary btn-block" type="submit" name="submit-login">Log in</button>
      </form>
    </div>

  </div><!-- /#page-wrapper -->

<?php include('../includes/layouts/footer.php'); ?>