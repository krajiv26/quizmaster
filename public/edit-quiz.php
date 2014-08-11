<?php require_once("../includes/sessions.php"); ?>
<?php require_once("../includes/db-connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php require_once("../includes/validation-functions.php"); ?>

<?php $page = "edit-quiz.php"; ?>

<?php include('../includes/layouts/header.php'); ?>





<?php
  // If quiz does not exist, redirect back to Manage Quizzes Page
  $id = isset($_GET["quiz_id"]) ? $_GET["quiz_id"] : "";
  $quiz = get_quiz_by_id($id);


  if(!$quiz) { redirect_to("manage-quizzes.php"); }
?>

<?php

 
  
  if(isset($_POST['submit-edit-quiz'])) {

    // The Edit Quiz Form was submitted

    // Validate Edit Quiz Form inputs
    $fields_with_max_lengths = array("quiz_name" => 255, "category" => 255,
                                      "attempts" => 11);
    foreach($fields_with_max_lengths as $field => $max) {
      $value = trim($_POST[$field]);
      if(!value_within_range($value, 1, $max)) {
        $error_messages[$field] = ucfirst($field) . " is too long.";
      }
    }

    $fields_required = array("quiz_name", "category", "attempts");
    foreach($fields_required as $field) {
      $value = trim($_POST[$field]);
      if(!has_presence($value)) {
        $error_messages[$field] = ucfirst($field) . " is required.";
      }
    }


    // If there are no errors, proceed with the update.
    if(empty($error_messages)) {

      $_POST = array_map('addslashes',$_POST);
      $_POST = array_map('htmlentities',$_POST);

      $quiz_id      = $id;
      $quiz_name    = $_POST['quiz_name'];
      $category     = $_POST['category'];
      $deadline     = $_POST['deadline'];
      $attempts     = $_POST['attempts'];


      
      if(!has_presence($deadline)) {
        $query  = "UPDATE quiz SET ";
        $query .= "quiz_name = '{$quiz_name}', ";
        $query .= "category = '{$category}', ";
        $query .= "deadline = NULL, ";
        $query .= "attempts = '{$attempts}' ";
        $query .= "WHERE quiz_id = {$quiz_id} ";
        $query .= "LIMIT 1";
      } else {
        $query  = "UPDATE quiz SET ";
        $query .= "quiz_name = '{$quiz_name}', ";
        $query .= "category = '{$category}', ";
        $query .= "deadline = '{$deadline}', ";
        $query .= "attempts = '{$attempts}' ";
        $query .= "WHERE quiz_id = {$quiz_id} ";
        $query .= "LIMIT 1";
      }
      
      
      

      $result = mysqli_query($db, $query); 

      if($result && mysqli_affected_rows($db) != -1) {
        // Success
        $_SESSION["message"] = "Successfully updated quiz: {$quiz_name}!";
        redirect_to("manage-quizzes.php");
      } else {
        // Failure
        $message = "Database insertion failure";
        
      }
    }

  } else {
    // Do nothing. Re-display the Edit User Form.
  } 
?>

<body>

<div id="wrapper">

  <!-- Sidebar -->
  <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <!-- Brand and toggle get grouped for better mobile display -->
    <?php include('../includes/layouts/admin-head.php'); ?>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse navbar-ex1-collapse">
      <?php include('../includes/layouts/admin-side-nav.php'); ?>

      <?php include('../includes/layouts/admin-profile.php'); ?>
    </div><!-- /.navbar-collapse -->
  </nav>
  <div id="page-wrapper">

    <div class="row">
      <div class="col-lg-12">
        <h1>Edit Quiz: <small><?php echo $quiz['quiz_name']; ?></small></h1>
        <ol class="breadcrumb">
          <li><a href="dashboard.php"><i class="icon-dashboard"></i> Dashboard</a></li>
          <li class="active"><i class="icon-file-alt"></i> Edit Quiz</li>
        </ol>
        <hr />
      </div><!-- /.col-lg-12 -->
    </div><!-- /.row -->

    <!-- Display conditional error message -->

    <?php echo display_form_errors($error_messages); ?>

<?php if(!empty($_POST)) { print_r($_POST); } ?>
<?php if(!empty($error_messages)) { print_r($error_messages); } ?>
<?php if(!empty($message)) { print_r($message); } ?>



      
      <div class="col-lg-12">

      
        <div class="panel tbp-panel-inverse">
          <div class="panel-heading">
            <?php  $linked_questions = get_quiz_questions($id); ?>
            <h4><?php echo $quiz["quiz_name"]; ?><span> - There are <?php echo mysqli_num_rows($linked_questions); ?> Questions Linked to this Quiz.</span></h4>
          </div>
          <div class="panel-body">

              <form id="edit-quiz-form"  role="form" action="edit-quiz.php?quiz_id=<?php echo $quiz["quiz_id"]; ?>" method="post">
                <div class="form-group col-lg-3">
                  <label for="quiz_name" class="control-label">Quiz Name</label>
                  <input type="text" class="form-control" id="quiz_name" value="<?php echo $quiz["quiz_name"]; ?>" name="quiz_name" placeholder="Quiz Name">
                </div>
                <div class="form-group col-lg-3">
                  <label for="category" class="control-label">Quiz Category</label>
                  <input type="text" class="form-control" id="category" value="<?php echo $quiz["category"]; ?>" name="category" placeholder="Quiz Category (ex. CINS 370)">
                </div>
                <div class="form-group col-lg-3">
                  <label for="deadline" class="control-label">Quiz Deadline</label>
                  <input type="text" class="form-control" id="deadline" value="<?php echo $quiz["deadline"]; ?>" name="deadline" placeholder="2014-05-15 23:59:59">
                </div>
                <div class="form-group col-lg-3">
                  <label for="attempts" class="control-label">Attempts Allowed</label>
                    <input type="text" class="form-control" id="attempts" value="<?php echo $quiz["attempts"]; ?>" name="attempts" placeholder="1">
                </div>
                <!--<div class="form-group">
                  <button type="submit" class="btn btn-primary pull-right" id="submit-edit-quiz" name="submit-edit-quiz" value="submit-edit-quiz">Save Changes</button>
                </div>-->
                
                
                    <!-- Split button -->
                    <!--<div class="btn-group pull-right">
                      <button type="button" class="btn btn-primary">Add Questions</button>
                      <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                        <span class="caret"></span>
                        <span class="sr-only">Toggle Dropdown</span>
                      </button>
                      <ul class="dropdown-menu" role="menu">
                        <li><a href="#">New Question</a></li>
                        <li><a >From Existing Questions</a></li>
                     
                      </ul>
                    </div>-->
                    <button type="submit" class="btn btn-primary pull-right margin-right-10" id="submit-edit-quiz" name="submit-edit-quiz" value="submit-edit-quiz">Save Changes</button>
                    <a href="manage-quizzes.php" id="cancel-edit-quiz" name="cancel-edit-quiz" class="btn btn-default pull-right margin-right-10">Cancel</a>
              </form>
				
				
				
				
				
				
          </div>
          <div class="table-responsive">
            <table class="table table-bordered">
              <tbody>
                <?php
                $i = 1;
                while($row = mysqli_fetch_assoc($linked_questions)) {
                  $current_question = get_question_by_id($row['question_id']);
                  //pr($current_question);

                ?>
                    
                    <tr><td class="tbp-question-list-header">Question <?php echo $i++; ?> <div style="float:right;"><i class="fa fa-minus"></i> <a href="remove-question.php?quiz_id=<?php echo $quiz["quiz_id"]; ?>&question_id=<?php echo $current_question['question_id']; ?>" onclick="return confirm('Are you sure to remove this question');">Remove from Test</a></div><h4></td></tr>
                    <tr><td><h4 class="tbp-question-list-body"><?php echo $current_question['question_text']; ?></h4></td></tr>
                <?php } ?>
              </tbody>
            </table>
            
        </div>
           
          </div>
        </div>
        
       

  </div><!-- /#page-wrapper -->

<?php include('../includes/layouts/footer.php'); ?>

<?php require_once("../includes/db-connection-close.php"); ?>
