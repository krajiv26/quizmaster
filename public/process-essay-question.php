<?php require_once("../includes/sessions.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php require_once("../includes/validation-functions.php"); ?>
<?php require_once("../includes/db-connection.php"); ?>

<?php
 
    if(isset($_POST['submit-essay-question'])) {

    // The Edit Multiple Choice Question Form was submitted
    

    // Validate Edit Multiple Choice Question Form inputs
    $fields_required = array("question_text", "category");
    foreach($fields_required as $field) {
      $value = trim($_POST[$field]);
      if(!has_presence($value)) {
        $error_messages[$field] = ucfirst($field) . " is required.";
      }
    }


    // If there were errors, redirect back to the form.
    if(!empty($error_messages)) {
      $_SESSION["errors"] = $error_messages;

      $form_values = array("question_text" => $_POST['question_text'],
                           "answer"   => $_POST['answer'],
                           "category" => $_POST['category']);
      $_SESSION["form_history"] = $form_values;

      redirect_to($redirect_url);
    }
    // If inputs were valid begin update.
    $_POST = array_map('addslashes',$_POST);
    $_POST = array_map('htmlentities',$_POST);

    $question_text    = $_POST['question_text'];
    $answer          = $_POST['answer'];
    $category         = $_POST['category'];
    $is_answered = (isset($answer) && $answer != "")? 1 : 0;
    $created_at = date("Y-m-d H:i:s");
    
    $query  = "INSERT INTO essay_question (";
    $query .= "  question_text, category, answer, is_answered, created_at ";
    $query .= ") VALUES (";
    $query .= "  '{$question_text}', ";
    $query .= "  {$category}, '{$answer}', {$is_answered}, '{$created_at}'";
    $query .= ")";
    $result = mysqli_query($db, $query);

    if ( false === $result ) {
      // Query failed. Print out information.
      printf("error: %s\n", mysqli_error($db));
      $_SESSION["error_message"] = "Database insertion failure";
      redirect_to("new-essay-question.php");

    } 
    // Success
    $_SESSION["message"] = "Successfully added essay question!";
    redirect_to("manage-essay-questions.php");
  } 
 
?>

<?php require_once("../includes/db-connection-close.php"); ?>