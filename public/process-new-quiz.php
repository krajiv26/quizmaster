<?php require_once("../includes/sessions.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php require_once("../includes/validation-functions.php"); ?>
<?php require_once("../includes/db-connection.php"); ?>

<?php
 
 // Temporary hack to supply user id for data insertion
 $user_id = 3; 

  if(isset($_POST['submit-new-quiz'])) {
    // The New User Form was submitted

    $fields_with_max_lengths = array("quiz_name" => 255, "category" => 255, "attempts" => 11);
    foreach($fields_with_max_lengths as $field => $max) {
      $value = trim($_POST[$field]);
      if(!value_within_range($value, 1, $max)) {
        $error_messages[$field] = ucfirst($field) . " is too long.";
      }
    }

    // Validate New User Form inputs
    $fields_required = array("quiz_name", "category", "attempts");
    foreach($fields_required as $field) {
      $value = trim($_POST[$field]);
      if(!has_presence($value)) {
        $error_messages[$field] = ucfirst($field) . " is required.";
      }
    }

    


    // If there were errors, redirect back to the form.
    if(!empty($error_messages)) {
      $_SESSION["errors"] = $error_messages;

      $form_values = array("quiz_name" => $_POST['quiz_name'],
                           "category" => $_POST['category'],
                           "deadline" => $_POST['deadline'],
                           "attempts" => $_POST['attempts']);
      $_SESSION["form_history"] = $form_values;

      redirect_to("new-quiz.php");
    }

    // If inputs were valid begin insertion.
    $_POST = array_map('addslashes',$_POST);
    $_POST = array_map('htmlentities',$_POST);

    $quiz_name   = $_POST['quiz_name'];
    $category = $_POST['category'];
    $deadline = $_POST['deadline'];
    $attempts = $_POST['attempts'];


    
    if($deadline != '') {
      $query  = "INSERT INTO quiz (";
      $query .= "  quiz_name, category, deadline, attempts";
      $query .= ") VALUES (";
      $query .= "  '{$quiz_name}', '{$category}', '{$deadline}', '{$attempts}'";
      $query .= ")";
    } else {
      $query  = "INSERT INTO quiz (";
      $query .= "  quiz_name, category, attempts";
      $query .= ") VALUES (";
      $query .= "  '{$quiz_name}', '{$category}', '{$attempts}'";
      $query .= ")";
    }
    

    $result = mysqli_query($db, $query);

    if($result) {
      // Success
      $_SESSION["message"] = "Successfully created new quiz: {$quiz_name}!";
      redirect_to("manage-quizzes.php");
      
    } else {
      // Failure
      $_SESSION["error_message"] = "Database insertion failure";
      redirect_to("new-quiz.php");
      
    }

  } else {
    // This is probably a get request
    $_SESSION["message"] = "Please fill in the form to create a new quiz.";
    redirect_to("new-quiz.php");
    
  } 
?>

<?php require_once("../includes/db-connection-close.php"); ?>
