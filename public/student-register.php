<?php require_once("../includes/sessions.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php require_once("../includes/validation-functions.php"); ?>
<?php require_once("../includes/db-connection.php"); ?>
<?php include('../includes/layouts/header-front.php'); ?>




<?php $page = "student-register.php"; ?>

<body>

<div id="wrapper">

  <div id="page-wrapper">
	<!-- Display conditional error message -->
    <!-- Display conditional messages -->
        <!-- Display conditional messages and/or errors -->
    <?php $error_messages = errors(); ?>
    <?php $form_values = form_history() ?>
    <?php echo display_form_errors($error_messages); ?>
    <?php echo user_form_info_msg(); ?>
    <?php echo user_form_failure_msg(); ?>
	<div class="clearfix"></div>
    <div class="container" style="width:50%">
		<strong>Available Exam Code</strong><br>
		<label style="width: 100%;height: 34px;padding: 6px 12px;font-size: 14px;line-height: 1.42857143;color: #555;background-color: #fff;background-image: none;border: 1px solid #ccc;border-radius: 4px;"><?php echo get_examcodes()?></label>
        
		<p></p>
        <form class="form-signin" method="post" action="student-register-login.php">
        <h2 class="form-signin-heading">Please input your details</h2>
		<br/><strong>First Name*</strong>
        <input type="text" name="first_name" class="form-control" placeholder="First Name" autofocus>
		<br/><strong>Middle Name</strong>
        <input type="text" name="middle_name" class="form-control" placeholder="Middle Name" >
        <br/><strong>Last Name*</strong>
        <input type="text" name="last_name" class="form-control" placeholder="Last Name" >
        <br/><strong>Id Number*</strong>
        <input type="text" name="idnumber" class="form-control" placeholder="Id Number" >
        <br/><strong>Exam Code*</strong>
        <input type="text" name="examcode" class="form-control" placeholder="Exam Code" >
        <br/>
        <button class="btn btn-lg btn-primary btn-block" type="submit" name="submit-login">Log in</button>
      </form>
    </div>

  </div><!-- /#page-wrapper -->

<?php include('../includes/layouts/footer.php'); ?>
