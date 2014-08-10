<?php require_once("../includes/sessions.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php require_once("../includes/validation-functions.php"); ?>
<?php require_once("../includes/db-connection.php"); ?>

<?php
  

  if(isset($_POST['submit-new-user'])) {
    // The New User Form was submitted

    $fields_with_max_lengths = array("first_name" => 30, "last_name" => 30,
                                      "username" => 20, "email" => 30, "password" => 16);
    foreach($fields_with_max_lengths as $field => $max) {
      $value = trim($_POST[$field]);
      if(!value_within_range($value, 1, $max)) {
        $error_messages[$field] = ucfirst($field) . " is too long.";
      }
    }

    // Validate New User Form inputs
    $fields_required = array("first_name", "last_name", "username",
                              "email", "password");
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

    // If there were errors, redirect back to the form.
    if(!empty($error_messages)) {
      $_SESSION["errors"] = $error_messages;

      $form_values = array("first_name" => $_POST['first_name'],
                           "last_name" => $_POST['last_name'],
                           "username" => $_POST['username'],
                           "email" => $_POST['email']);
      $_SESSION["form_history"] = $form_values;

      redirect_to("new-user.php");
    }

    // If inputs were valid begin insertion.
    $_POST = array_map('addslashes',$_POST);
    $_POST = array_map('htmlentities',$_POST);

    $first_name   = $_POST['first_name'];
    $last_name    = $_POST['last_name'];
    $username     = $_POST['username'];
    $email        = $_POST['email'];
    $password     = $_POST['password'];
    $user_type    = $_POST['user_type'];
    

    $query  = "INSERT INTO user (";
    $query .= "  first_name, last_name, username, email, password, user_type";
    $query .= ") VALUES (";
    $query .= "  '{$first_name}', '{$last_name}', '{$username}',";
    $query .= "  '{$email}', '{$password}', '{$user_type}'";
    $query .= ")";

    $result = mysqli_query($db, $query);

    if($result) {
      // Success
      $_SESSION["message"] = "Successfully created new user: {$username}!";
      redirect_to("manage-users.php");
      
    } else {
      // Failure
      $_SESSION["error_message"] = "Database insertion failure";
      redirect_to("new-user.php");
      
    }

  } else {
    // This is probably a get request
    $_SESSION["message"] = "Please fill in the form to create a new user.";
    redirect_to("new-user.php");
    
  } 
?>

<?php require_once("../includes/db-connection-close.php"); ?>
