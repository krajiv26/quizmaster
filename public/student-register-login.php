<?php require_once("../includes/sessions.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php require_once("../includes/validation-functions.php"); ?>
<?php require_once("../includes/db-connection.php"); ?>

<?php
  

  if(isset($_POST['submit-login'])) {
    // The Login Form was submitted
    

     $fields_with_max_lengths = array("first_name" => 30, "last_name" => 30,
                                      "idnumber" => 20);
    foreach($fields_with_max_lengths as $field => $max) {
      $value = trim($_POST[$field]);
      if(!value_within_range($value, 1, $max)) {
        $error_messages[$field] = ucfirst($field) . " is too long.";
      }
    }

    // Validate New User Form inputs
    $fields_required = array("first_name", "last_name", "idnumber");
    foreach($fields_required as $field) {
      $value = trim($_POST[$field]);
      if(!has_presence($value)) {
        $error_messages[$field] = ucfirst($field) . " is required.";
      }
    }
    
    // If there were errors, redirect back to the form.
    if(!empty($error_messages)) {
      $_SESSION["errors"] = $error_messages;

      $form_values = array("first_name" => $_POST['first_name'],
                           "last_name" => $_POST['last_name'],
                           "idnumber" => $_POST['idnumber'],
                           "middle_name" => $_POST['middle_name'],
                           "examcode" => $_POST['examcode']);
      $_SESSION["form_history"] = $form_values;
    
      redirect_to("student-register.php");
    }
    
    // If inputs were valid begin insertion.
    $_POST = array_map('addslashes',$_POST);
    $_POST = array_map('htmlentities',$_POST);

    $first_name   = $_POST['first_name'];
    $last_name    = $_POST['last_name'];
    $middle_name  = $_POST['middle_name'];
    $username     = $_POST['idnumber'];
    $examcode     = $_POST['examcode'];
    $email        = "student@gmail.com";
    $password     = "";
    $user_type    = 'student';
    
	if(!isUserExists($username)){
		$query  = "INSERT INTO user (";
		$query .= "  first_name, middle_name, last_name, username, email, password, user_type";
		$query .= ") VALUES (";
		$query .= "  '{$first_name}', '{$middle_name}', '{$last_name}', '{$username}',";
		$query .= "  '{$email}', '{$password}', '{$user_type}'";
		$query .= ")";
		$result = mysqli_query($db, $query);
		if($result) {
		  // Success
		  $_SESSION["message"] = "Successfully created new user: {$username}!";
		  
		  if(logging_student($username)){
			  $_SESSION["examcode"] = $examcode;
			  redirect_to("dashboard.php");
		  }
		  else{
			  redirect_to("student-register.php");
		  }
		} else {
		  // Failure
		  $_SESSION["error_message"] = "Database insertion failure";
		  redirect_to("student-register.php");
		}
		
	}
	else {
		if(logging_student($username)){
			  $_SESSION["examcode"] = $examcode;	
			  redirect_to("dashboard.php");
		  }
		  else{
			  redirect_to("student-register.php");
		  }
		
	}
	
	
    



  } else {
    // This is probably a get request
    $_SESSION["message"] = "Please fill in the form to login.";
    redirect_to("student-register.php");
    
  } 
  
  function logging_student($idnumber){
	  global $db;
	  
	  $query  = "SELECT * FROM `user` WHERE `username` = '{$idnumber}' AND `user_type` = 'student'";
	  $result = mysqli_query($db, $query);
	  if($result && $result->num_rows == 1) {
		if($user = mysqli_fetch_assoc($result)) {
			$_SESSION["authenticate"] = "Yes";
			$_SESSION["user"] = $user;
			$_SESSION["logouturl"] = "student-register.php";
			  // Success
			  $_SESSION["message"] = "You are logged in successfully!";
			 return true;
         }
		 else
		 {
			$_SESSION["error_message"] = "Id number is wrong!";
			return false;
		 }
    } else {
      // Failure
      $_SESSION["error_message"] = "Id number is wrong!";
      return false;
    }
	  
	  
  }
  
  
?>

<?php require_once("../includes/db-connection-close.php"); ?>
