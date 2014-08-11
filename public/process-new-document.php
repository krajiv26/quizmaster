<?php require_once("../includes/sessions.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php require_once("../includes/validation-functions.php"); ?>
<?php require_once("../includes/db-connection.php"); ?>

<?php
  

  if(isset($_POST['submit-new-doc'])) {
    // The New User Form was submitted

    $fields_with_max_lengths = array("title" => 250, "filename" => 250 );
    foreach($fields_with_max_lengths as $field => $max) {
      $value = trim($_POST[$field]);
      if(!value_within_range($value, 1, $max)) {
        $error_messages[$field] = ucfirst($field) . " is too long.";
      }
    }

    // Validate New User Form inputs
    $fields_required = array("title", "filename", "file_type");
    foreach($fields_required as $field) {
      $value = trim($_POST[$field]);
      if(!has_presence($value)) {
        $error_messages[$field] = ucfirst($field) . " is required.";
      }
    }

    

    // If there were errors, redirect back to the form.
    if(!empty($error_messages)) {
      $_SESSION["errors"] = $error_messages;

      $form_values = array("title" => $_POST['title'],
                           "description" => $_POST['description'],
                           "category" => $_POST['category'],
                           "filename" => $_POST['filename']);
      $_SESSION["form_history"] = $form_values;

      redirect_to("new-document.php");
    }

    // If inputs were valid begin insertion.
    $_POST = array_map('addslashes',$_POST);
    $_POST = array_map('htmlentities',$_POST);

      $title        = $_POST['title'];
      $description  = $_POST['description'];
      $filename     = $_POST['filename'];
      $category     = $_POST['category'];
      $file_type    = $_POST['file_type'];
    

    $query  = "INSERT INTO document (";
    $query .= "  title, description, category, filename, file_type";
    $query .= ") VALUES (";
    $query .= "  '{$title}', '{$description}', {$category},";
    $query .= "  '{$filename}', '{$file_type}'";
    $query .= ")";

    $result = mysqli_query($db, $query);

    if($result) {
      // Success
      $_SESSION["message"] = "Successfully created new document!";
      redirect_to("manage-document.php");
      
    } else {
      // Failure
      $_SESSION["error_message"] = "Database insertion failure";
      redirect_to("new-document.php");
      
    }

  } else {
    // This is probably a get request
    $_SESSION["message"] = "Please fill in the form to create a new user.";
    redirect_to("new-document.php");
    
  } 
?>

<?php require_once("../includes/db-connection-close.php"); ?>
