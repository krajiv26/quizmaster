<?php require_once("../includes/sessions.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php require_once("../includes/validation-functions.php"); ?>
<?php require_once("../includes/db-connection.php"); ?>

<?php
  
  // Temporary hack to simulate logged in user
  $user_id = 1;

  if(isset($_POST['submit-new-mc-question'])) {

    // The New Multiple Choice Form was submitted

    // Validate New User Form inputs
    $fields_required = array("question_text", "answer1", "answer2",
                              "answer3", "answer4");
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
                           "answer1"  => $_POST['answer1'],
                           "answer2"  => $_POST['answer2'],
                           "answer3"  => $_POST['answer3'],
                           "answer4"  => $_POST['answer4'],
                           "category"   => $_POST['category']);
      $_SESSION["form_history"] = $form_values;

      redirect_to("new-question.php");
    }

    // If inputs were valid begin insertion.
    $isCheckedPost = $_POST['ischecked'];
    unset($_POST['ischecked']);
    $_POST = array_map('addslashes',$_POST);
    $_POST = array_map('htmlentities',$_POST);

    $question_text    = $_POST['question_text'];
    $answer1          = $_POST['answer1'];
    $answer2          = $_POST['answer2'];
    $answer3          = $_POST['answer3'];
    $answer4          = $_POST['answer4'];
    $category         = $_POST['category'];
    $explanation      = $_POST['explanation'];
    $created_at = date("Y-m-d H:i:s");
    $multi_answer = (isset($_POST['multi_answer']) && $_POST['multi_answer'] == 1)? 1 : 0;
    if($multi_answer == 0){
        $is_answered = (isset($isCheckedPost[0]) && $isCheckedPost[0] != "")? 1 : 0;
    }
    else
    {  //multi answer
        $is_answered = 1;
     }
        // Insert new question into question table
        $query  = "INSERT INTO question (";
        $query .= "  question_text, category, explanation, multi_answer, is_answered, created_at ";
        $query .= ") VALUES (";
        $query .= "  '{$question_text}', ";
        $query .= "  {$category}, '{$explanation}', {$multi_answer}, {$is_answered}, '{$created_at}'";
        $query .= ")";
    //echo $query; exit;
        $result = mysqli_query($db, $query);

        if ( false === $result ) {
          // Query failed. Print out information.
          printf("error: %s\n", mysqli_error($db));
          $_SESSION["error_message"] = "Database insertion failure";
          redirect_to("new-question.php");

        } 

        $inserted_question_id = mysqli_insert_id($db);
        
        $ischecked = false;

        // Answer 1
        if(in_array(1,$isCheckedPost)) { $ischecked = true; }

        $query1  = "INSERT INTO answer (";
        $query1 .= "  answer_text, question_id, is_correct";
        $query1 .= ") VALUES (";
        $query1 .= "  '{$answer1}', '{$inserted_question_id}', '{$ischecked}'";
        $query1 .= ")";

        $result1 = mysqli_query($db, $query1);

        if ( false === $result1 ) {
          // Query failed. Print out information.
          printf("error: %s\n", mysqli_error($db));
          $_SESSION["error_message"] = "Database insertion failure";
          redirect_to("new-question.php");
        }

        $ischecked = false; 

        // Answer 2
        if(in_array(2,$isCheckedPost)) { $ischecked = true; }

        $query2  = "INSERT INTO answer (";
        $query2 .= "  answer_text, question_id, is_correct";
        $query2 .= ") VALUES (";
        $query2 .= "  '{$answer2}', '{$inserted_question_id}', '{$ischecked}'";
        $query2 .= ")";

        $result2 = mysqli_query($db, $query2);

        if ( false === $result2 ) {
          // Query failed. Print out information.
          printf("error: %s\n", mysqli_error($db));
          $_SESSION["error_message"] = "Database insertion failure";
          redirect_to("new-question.php");
        }

        $ischecked = false;

        // Answer 3
        if(in_array(3,$isCheckedPost)) { $ischecked = true; }

        $query3  = "INSERT INTO answer (";
        $query3 .= "  answer_text, question_id, is_correct";
        $query3 .= ") VALUES (";
        $query3 .= "  '{$answer3}', '{$inserted_question_id}', '{$ischecked}'";
        $query3 .= ")";

        $result3 = mysqli_query($db, $query3);

        if ( false === $result3 ) {
          // Query failed. Print out information.
          printf("error: %s\n", mysqli_error($db));
          $_SESSION["error_message"] = "Database insertion failure";
          redirect_to("new-question.php");
        }

        $ischecked = false;

        // Answer 4
        if(in_array(4,$isCheckedPost)) { $ischecked = true; }

        $query4  = "INSERT INTO answer (";
        $query4 .= "  answer_text, question_id, is_correct";
        $query4 .= ") VALUES (";
        $query4 .= "  '{$answer4}', '{$inserted_question_id}', '{$ischecked}'";
        $query4 .= ")";

        $result4 = mysqli_query($db, $query4);

        if ( false === $result4 ) {
          // Query failed. Print out information.
          printf("error: %s\n", mysqli_error($db));
          $_SESSION["error_message"] = "Database insertion failure";
          redirect_to("new-question.php");
        }  



        // Success
        $_SESSION["message"] = "Successfully created new multiple choice question!";
        redirect_to("manage-questions.php");
        
  } else {
    // This is probably a get request
    $_SESSION["message"] = "Please fill in the form to create a new question.";
    redirect_to("new-question.php");
    
  } 
?>

<?php require_once("../includes/db-connection-close.php"); ?>