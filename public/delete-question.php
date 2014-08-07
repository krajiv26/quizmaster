<?php require_once("../includes/sessions.php"); ?>
<?php require_once("../includes/db-connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>

<?php

  $question = get_question_by_id($_GET["question_id"]); 
  if(!$question) {
    // Question id was missing or invalid or
    // could not be found in the database.
    $_SESSION["error_message"] = "Failed to delete question. Question does not exist.";
    redirect_to("manage-questions.php");
  }

  $id = $question["question_id"];
  $query = "DELETE FROM question WHERE question_id = {$id} LIMIT 1";
  $result = mysqli_query($db, $query);

  if($result && mysqli_affected_rows($db) == 1) {
    $query2 = "DELETE FROM answer WHERE question_id = {$id}";
    $result2 = mysqli_query($db, $query2);
    if($result2) {
      $query3 = "DELETE FROM quiz_has_question WHERE question_id = {$id}";
      $result3 = mysqli_query($db, $query3);
      if($result3) {
        // Success
        $_SESSION["message"] = "Successfully deleted question with id: {$id}.";
        redirect_to("manage-questions.php");
      }
      
    } 
  } else {
    // Failure
    redirect_to("manage-questions.php");
    
  }

?>