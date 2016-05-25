<?php require_once("../includes/sessions.php"); ?>
<?php require_once("../includes/db-connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php require_once("../includes/validation-functions.php"); ?>

<?php $page = "edit-user.php"; ?>

<?php include('../includes/layouts/header.php'); ?>

<?php
  // If user does not exist, redirect back to Manage Users Page
  $id = isset($_GET["user_id"]) ? $_GET["user_id"] : "";
  $user = get_user_by_id($id);
  if(!$user) { redirect_to("manage-users.php"); }
?>

<?php
  
  if(isset($_POST['submit-edit-user'])) {

    // The Edit User Form was submitted

    // Validate Edit User Form inputs
    $fields_with_max_lengths = array("first_name" => 30, "last_name" => 30,
                                      "username" => 20, "email" => 30);
    foreach($fields_with_max_lengths as $field => $max) {
      $value = trim($_POST[$field]);

      if(!value_within_range($value, 1, $max)) {
        $error_messages[$field] = ucfirst($field) . " is too long.";
      }
    }

    $fields_required = array("first_name", "last_name", "username",
                              "email");
    foreach($fields_required as $field) {
      $value = trim($_POST[$field]);
      if(!has_presence($value)) {
        $error_messages[$field] = ucfirst($field) . " is required.";
      }
    }

    $email = trim($_POST["email"]);
    if(!email_is_valid($email)) {
      $error_messages["email"] = "Please enter a valid email address.";
    }

    // If there are no errors, proceed with the update.
    if(empty($error_messages)) {

     $_POST = array_map('addslashes',$_POST);
     $_POST = array_map('htmlentities',$_POST);

      $user_id      = $id;
      $first_name   = $_POST['first_name'];
      $last_name    = $_POST['last_name'];
      $username     = $_POST['username'];
      $email        = $_POST['email'];
      $password     = md5($_POST['password']);
      $user_type    = $_POST['user_type'];
      
	  if(isset($_POST['password']) && $_POST['password'] != "") {
		  $query  = "UPDATE user SET ";
		  $query .= "first_name = '{$first_name}', ";
		  $query .= "last_name = '{$last_name}', ";
		  $query .= "username = '{$username}', ";
		  $query .= "email = '{$email}', ";
		  $query .= "password = '{$password}', ";
		  $query .= "user_type = '{$user_type}' ";
		  $query .= "WHERE user_id = {$user_id} ";
		  $query .= "LIMIT 1";
      }
      else {
		  $query  = "UPDATE user SET ";
		  $query .= "first_name = '{$first_name}', ";
		  $query .= "last_name = '{$last_name}', ";
		  $query .= "username = '{$username}', ";
		  $query .= "email = '{$email}', ";
		  $query .= "user_type = '{$user_type}' ";
		  $query .= "WHERE user_id = {$user_id} ";
		  $query .= "LIMIT 1";
	  }
      $result = mysqli_query($db, $query);

      if($result && mysqli_affected_rows($db) == 1) {
        // Success
        $_SESSION["message"] = "Successfully updated user: {$username}!";
        redirect_to("manage-users.php");
      } else {
        // Failure
        $message = "Database insertion failure";
        
      }
    }

  } else {
    // Do nothing. Re-display the Edit User Form.
  } 
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
        <h1>Edit User: <small><?php echo $user["username"]; ?> </small></h1>
        <ol class="breadcrumb">
          <li><a href="dashboard.php"><i class="icon-dashboard"></i> Dashboard</a></li>
          <li class="active"><i class="icon-file-alt"></i> Edit User</li>
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
      <div class="col-lg-offset-1 col-lg-4">
        
        <form class="form-horizontal" action="edit-user.php?user_id=<?php echo $user["user_id"]; ?>" method="post">
          <!-- Text input-->
          <div class="form-group">
            <label for="first_name">First Name</label>
              <input class="form-control" id="first_name" name="first_name" value="<?php echo $user["first_name"]; ?>" type="text" placeholder="Enter First Name" class="input-xlarge">
          </div>

          <!-- Text input-->
          <div class="form-group">
            <label for="last_name">Last Name</label>
              <input class="form-control" id="last_name" name="last_name" value="<?php echo $user["last_name"]; ?>" type="text" placeholder="Enter Last Name" class="input-xlarge">
          </div>

          <!-- Text input-->
          <div class="form-group">
            <label for="email">Email</label>
              <input class="form-control" id="email" name="email" value="<?php echo $user["email"]; ?>" type="text" placeholder="Enter Email Address" class="input-xlarge">
          </div>

          <!-- Text input-->
          <div class="form-group">
            <label for="username">Username</label>
              <input class="form-control" id="username" name="username" value="<?php echo $user["username"]; ?>" type="text" placeholder="Create a Username" class="input-xlarge">
          </div>

          <!-- Password input-->
          <div class="form-group">
            <label for="password">Password</label>
              <input class="form-control" id="password" name="password" type="password" value="" placeholder="Create a Password" class="input-xlarge">
          </div>

          <!-- Select Basic -->
          <div class="form-group">
            <label for="user_type">Select User Type</label>
              <select class="form-control" id="user_type" name="user_type" class="input-xlarge" value="<?php echo $user["user_type"]; ?>">
                <option value="admin" <?php if($user["user_type"] == "admin") { echo "selected"; } ?> >Administrator</option>
                <option value="teacher" <?php if($user["user_type"] == "teacher") { echo "selected"; } ?> >Teacher</option>
                <option value="student" <?php if($user["user_type"] == "student") { echo "selected"; } ?> >Student</option>
              </select>
          </div>

          <!-- Button (Double) -->
          <div class="form-group pull-right">
            <label for="submit-edit-user"></label>
              <button type ="submit" id="submit-edit-user" name="submit-edit-user" value="submit-edit-user" class="btn btn-primary">Update User</button>
              <a href="manage-users.php" id="cancel-edit-user" name="cancel-edit-user" class="btn btn-default">Cancel</a>
              <!-- Button trigger modal -->
              <a class="btn btn-danger" data-toggle="modal" data-target="#confirm-delete-modal">Delete User</a>   
          </div>
        </form>

        <!-- Confirm Deletion Modal -->
        <div class="modal fade" id="confirm-delete-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="confirm-delate-label">Warning!</h4>
              </div>
              <div class="modal-body">
                You are about to delete a user. This action will be irreversible.<br />
                Do you wish to proceed?
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                <a href="delete-user.php?user_id=<?php echo $user["user_id"]; ?>" class="btn btn-primary">Yes</a>
              </div>
            </div>
          </div>
        </div><!-- /.modal fade -->

      </div><!-- /.col-lg-6 -->

    </div><!-- /.row -->

  </div><!-- /#page-wrapper -->

<?php include('../includes/layouts/footer.php'); ?>
<?php require_once("../includes/db-connection-close.php"); ?>
