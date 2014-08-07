<?php require_once("../includes/sessions.php"); ?>
<?php require_once("../includes/db-connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>

<?php

  $quiz = get_quiz_by_id($_GET["quiz_id"]); 
  if(!$quiz) {
    // Quiz id was missing or invalid or
    // could not be found in the database.
    $_SESSION["error_message"] = "Failed to delete quiz. Quiz does not exist.";
    redirect_to("manage-quizzes.php");
  }

  $id = $quiz["quiz_id"];
  $quiz_name = $quiz["quiz_name"];
  $query = "UPDATE quiz SET is_deleted = 1 WHERE quiz_id = {$id} LIMIT 1";
  $result = mysqli_query($db, $query);

  if($result && mysqli_affected_rows($db) == 1) {
      // Success
        $_SESSION["message"] = "Successfully deleted quiz: {$quiz_name}.";
        redirect_to("manage-quizzes.php");
  } else {
    // Failure
    redirect_to("manage-quizzes.php");
    
  }

?>
