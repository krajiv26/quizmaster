<?php require_once("../includes/sessions.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php require_once("../includes/validation-functions.php"); ?>
<?php require_once("../includes/db-connection.php"); ?>

<?php
  

  if(isset($_POST['submit-login'])) {
    // The Login Form was submitted

    $fields_with_max_lengths = array("username" => 40, "password" => 40);
    foreach($fields_with_max_lengths as $field => $max) {
      $value = trim($_POST[$field]);
      if(!value_within_range($value, 1, $max)) {
        $error_messages[$field] = ucfirst($field) . " is too long.";
      }
    }

    // Validate New User Form inputs
    $fields_required = array("username", "password");
    foreach($fields_required as $field) {
      $value = trim($_POST[$field]);
      if(!has_presence($value)) {
        $error_messages[$field] = ucfirst($field) . " is required.";
      }
    }

    

   /*$email = trim($_POST["email"]);
    if(!email_is_valid($email)) {
      $error_messages["email"] = "Please enter a valid email address.";
    }*/

    // If there were errors, redirect back to the form.
    if(!empty($error_messages)) {
      $_SESSION["errors"] = $error_messages;

      $form_values = array("username" => $_POST['username']);
      $_SESSION["form_history"] = $form_values;

      redirect_to("index.php");
    }

    // If inputs were valid begin insertion.
    $_POST = array_map('addslashes',$_POST);
    $_POST = array_map('htmlentities',$_POST);
    
    $username        = $_POST['username'];
    $password     = md5($_POST['password']);

	$query  = "SELECT * FROM `user` WHERE `username` = '{$username}' AND `password` = '{$password}'";
	
    $result = mysqli_query($db, $query);
    if($result && $result->num_rows == 1) {
		if($user = mysqli_fetch_assoc($result)) {
			$_SESSION["authenticate"] = "Yes";
			unset($user["password"]);
			$_SESSION["user"] = $user;
			  // Success
			  $_SESSION["message"] = "You are logged in successfully!";
			  redirect_to("dashboard.php");
         }
		 else
		 {
			$_SESSION["error_message"] = "username or password is wrong!";
			redirect_to("index.php");
		 }
    } else {
      // Failure
      $_SESSION["error_message"] = "username or password is wrong!";
      redirect_to("index.php");
    }

  } else {
    // This is probably a get request
    $_SESSION["message"] = "Please fill in the form to login.";
    redirect_to("index.php");
    
  } 
?>

<?php require_once("../includes/db-connection-close.php"); ?>
