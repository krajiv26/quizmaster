<?php require_once("../includes/sessions.php"); ?>
<?php require_once("../includes/db-connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>

<?php
error_reporting(E_ALL);
ini_set("display_errors",1);
  $qid = (isset($_GET["question_id"]))? $_GET["question_id"]:"";
  $quiz_id = (isset($_GET["quiz_id"]))? $_GET["quiz_id"]:"";
  
  $query = "DELETE FROM quiz_has_question WHERE question_id = {$qid} AND quiz_id = {$quiz_id} ";
  
  $result = mysqli_query($db, $query);
  if($result && mysqli_affected_rows($db) > 0) {
    
        // Success
        $_SESSION["message"] = "Successfully deleted question with id: {$qid}.";
        redirect_to("edit-quiz.php?quiz_id=$quiz_id");
    } 
   else {
    // Failure
    redirect_to("edit-quiz.php?quiz_id=$quiz_id");
    
  }
  

?>
