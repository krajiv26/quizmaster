<?php require_once("../includes/sessions.php"); ?>
<?php require_once("../includes/db-connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php require_once("../includes/validation-functions.php"); ?>

<?php $page = "edit-question.php"; 
//pr($_SESSION["get_query"],1);
if($_SESSION["get_query"] != "")
$redirect_url = "manage-questions.php?".$_SESSION["get_query"];
else
$redirect_url = "manage-questions.php";

?>

<?php include('../includes/layouts/header.php'); ?>
<?php
$cat = get_all_category();
 $id = isset($_REQUEST["question_id"]) ? $_REQUEST["question_id"] : ""; 
  
  $question = get_question_by_id($id);

  if(!$question) { redirect_to($redirect_url); }
  $answer_set = get_question_answers($id);
?>

<?php
  
  // Temporary hack to simulate logged in user

  if(isset($_POST['submit-edit-mc-question'])) {

    // The Edit Multiple Choice Question Form was submitted
    

    // Validate Edit Multiple Choice Question Form inputs
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
                           "answer4"  => $_POST['answer4']);
      $_SESSION["form_history"] = $form_values;

      redirect_to($redirect_url);
    }
    $isCheckedPost = $_POST['ischecked'];
    unset($_POST['ischecked']);
    // If inputs were valid begin update.
    $_POST = array_map('addslashes',$_POST);
    $_POST = array_map('htmlentities',$_POST);

    $question_text    = $_POST['question_text'];
    $answer1          = $_POST['answer1'];
    $answer2          = $_POST['answer2'];
    $answer3          = $_POST['answer3'];
    $answer4          = $_POST['answer4'];
    $category         = $_POST['category'];
    $explanation      = $_POST['explanation'];
    $multi_answer = (isset($_POST['multi_answer']) && $_POST['multi_answer'] == 1)? 1 : 0;
    if($multi_answer == 0){
        $is_answered = (isset($isCheckedPost[0]) && $isCheckedPost[0] != "")? 1 : 0;
    }
    else
    {  //multi answer
        $is_answered = 1;
     }
    // Update Multiple Choice Question
    $query  = "UPDATE question SET ";
    $query .= "question_text = '{$question_text}', ";
    $query .= "category = {$category}, ";
    $query .= "explanation = '{$explanation}',";
    $query .= "multi_answer = {$multi_answer}, ";
    $query .= "is_answered = {$is_answered} ";
    $query .= "WHERE question_id = {$id} ";
    $query .= "LIMIT 1";

    $result = mysqli_query($db, $query);

    if ( false === $result ) {
      // Query failed. Print out information.
      printf("error: %s\n", mysqli_error($db));
      $_SESSION["error_message"] = mysqli_error($db).". In edit-question.php @ query.";
      redirect_to($redirect_url);

    } 


    // Update question's answers into answer table
    $ischecked = false;
      
    // Answer 1
    if(in_array(1,$isCheckedPost)) { $ischecked = true; }

    $query1  = "UPDATE answer SET ";
    $query1 .= "answer_text = '{$answer1}', ";
    $query1 .= "question_id = '{$id}', ";
    $query1 .= "is_correct = '{$ischecked}' ";
    $query1 .= "WHERE answer_id = {$answer_set[0]['answer_id']} ";
    $query1 .= "LIMIT 1";

    $result1 = mysqli_query($db, $query1);

    if ( false === $result1 ) {
      // Query failed. Print out information.
      printf("error: %s\n", mysqli_error($db));
      $_SESSION["error_message"] = mysqli_error($db).". In edit-question.php @ query1.";
      redirect_to($redirect_url);
    }

    $ischecked = false; 

    // Answer 2
    if(in_array(2,$isCheckedPost)) { $ischecked = true; }

    $query2  = "UPDATE answer SET ";
    $query2 .= "answer_text = '{$answer2}', ";
    $query2 .= "question_id = '{$id}', ";
    $query2 .= "is_correct = '{$ischecked}' ";
    $query2 .= "WHERE answer_id = {$answer_set[1]['answer_id']} ";
    $query2 .= "LIMIT 1";

    $result2 = mysqli_query($db, $query2);

    if ( false === $result2 ) {
      // Query failed. Print out information.
      printf("error: %s\n", mysqli_error($db));
      $_SESSION["error_message"] = mysqli_error($db).". In edit-question.php @ query2.";
      redirect_to($redirect_url);
    }

    $ischecked = false;

    // Answer 3
    if(in_array(3,$isCheckedPost)) { $ischecked = true; }

    $query3  = "UPDATE answer SET ";
    $query3 .= "answer_text = '{$answer3}', ";
    $query3 .= "question_id = '{$id}', ";
    $query3 .= "is_correct = '{$ischecked}' ";
    $query3 .= "WHERE answer_id = {$answer_set[2]['answer_id']} ";
    $query3 .= "LIMIT 1";

    $result3 = mysqli_query($db, $query3);

    if ( false === $result3 ) {
      // Query failed. Print out information.
      printf("error: %s\n", mysqli_error($db));
      $_SESSION["error_message"] = mysqli_error($db).". In edit-question.php @ query3.";
      redirect_to($redirect_url);
    }

    $ischecked = false;

    // Answer 4
    if(in_array(4,$isCheckedPost)) { $ischecked = true; }

    $query4  = "UPDATE answer SET ";
    $query4 .= "answer_text = '{$answer4}', ";
    $query4 .= "question_id = '{$id}', ";
    $query4 .= "is_correct = '{$ischecked}' ";
    $query4 .= "WHERE answer_id = {$answer_set[3]['answer_id']} ";
    $query4 .= "LIMIT 1";

    $result4 = mysqli_query($db, $query4);

    if ( false === $result4 ) {
      // Query failed. Print out information.
      printf("error: %s\n", mysqli_error($db));
      $_SESSION["error_message"] = mysqli_error($db).". In edit-question.php @ query4.";
      redirect_to($redirect_url);
    }  



    // Success
    $_SESSION["message"] = "Successfully updated multiple choice question!";
    redirect_to($redirect_url);
    

  } else {
    // Do nothing
    
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
        <h1>Edit Question <small>Control Panel</small></h1>
        <ol class="breadcrumb">
          <li><a href="dashboard.php"><i class="icon-dashboard"></i> Dashboard</a></li>
          <li class="active"><i class="icon-file-alt"></i> Edit Question</li>
        </ol>
        <hr />
      </div><!-- /.col-lg-12 -->
    </div><!-- /.row -->


    <div class="clearfix"></div>
      <div class="row">
        <div class="col-lg-12">
          <h2 id="nav-tabs">Question Type</h2>
            <div class="bs-example">
              <ul class="nav nav-tabs" style="margin-bottom: 15px;">
                <li class="active"><a href="#edit-mc-question-form" data-toggle="tab">Multiple Choice</a></li>
                <li><a href="#new-essay-question-form" data-toggle="tab">Essay</a></li>
              </ul>
              <div id="myTabContent" class="tab-content">
                <div class="tab-pane fade active in col-lg-offset-1 col-lg-6" id="edit-mc-question-form">
                  <p>
                    <form class="form-horizontal" action="edit-question.php?question_id=<?php echo $_GET["question_id"]; ?>" method="post">
                      <div class="form-group">
                        <div class="col-sm-12">
                        <label for="first_name">Question Text</label>
                        <textarea class="form-control" rows="3" id="question_text" name="question_text"  placeholder="Ex. What color is an apple?" class="input-xlarge"><?php echo $question["question_text"] ?></textarea>
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-sm-12">
                        <label for="answer1 control-label">Answer 1</label>
                        <textarea class="form-control" rows="1" id="answer1" name="answer1" placeholder="Ex. Orange" class="input-xlarge"><?php echo $answer_set[0]["answer_text"]; ?></textarea>
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-sm-12">
                        <label for="answer1 control-label">Answer 2</label>
                        <textarea class="form-control" rows="1" id="answer2" name="answer2" placeholder="Ex. Red" class="input-xlarge"><?php echo $answer_set[1]["answer_text"]; ?></textarea>
                        </div>
                      </div>
                      
                      <div class="form-group">
                        <div class="col-sm-12">
                        <label for="answer1 control-label">Answer 3</label>
                        <textarea class="form-control" rows="1" id="answer3" name="answer3" placeholder="Ex. Green" class="input-xlarge"><?php echo $answer_set[2]["answer_text"]; ?></textarea>
                        </div>
                      </div>
                     
                      <div class="form-group">
                        <div class="col-sm-12">
                        <label for="answer1 control-label">Answer 4</label>
                        <textarea class="form-control" rows="1" id="answer4" name="answer4" placeholder="Ex. Red or Green" class="input-xlarge"><?php echo $answer_set[3]["answer_text"]; ?></textarea>
                        </div>
                      </div>
                      <div style="float:left;">
						<div class="form-group">
                        <div class="col-sm-12">
                        <input type="checkbox" value="1" id="multi_answer" name="multi_answer" <?php echo ($question["multi_answer"] == 1) ? 'checked' : ''; ?>>
						<label for="category control-label">Multi Answer</label>
                        </div>
						</div>
						<div class="form-group">
                        <div class="col-sm-12">
                        <label for="category control-label">Category</label>
                        <select name="category" class="form-control">
						<?php foreach($cat as $k=>$v)
						{
							$sel = ($k == $question["category"])? 'selected="selected"':'';
							echo '<option value="'.$k.'" '.$sel.'>'.$v.'</option>';
						}?>
						</select>
                        </div>
						</div>
					  </div>
					  <div style="float:right;">
						<div class="form-group">
                        <div class="col-sm-12">
                        <label for="category control-label">Answer</label>
                        <select name="ischecked[]" id="ischecked" multiple="true" size =5 class="form-control">
                            <option value="">None</option>
                            <option value="1" <?php echo ($answer_set[0]["is_correct"] == 1) ? 'selected="selected"' : ''; ?>>1 One</option>
                            <option value="2" <?php echo ($answer_set[1]["is_correct"] == 1) ? 'selected="selected"' : ''; ?>>2 Two</option>
                            <option value="3" <?php echo ($answer_set[2]["is_correct"] == 1) ? 'selected="selected"' : ''; ?>>3 Three</option>
                            <option value="4" <?php echo ($answer_set[3]["is_correct"] == 1) ? 'selected="selected"' : ''; ?>>4 Four</option>
                        </select>
                        </div>
						</div>
					  </div>
					  <div class="form-group">
                        <div class="col-sm-12">
                        <label for="answer4 control-label">Explanation</label>
                        <textarea class="form-control" rows="1"  id="explanation" name="explanation"  placeholder="Explanation-Comment" class="input-xlarge"class="input-xlarge"><?php echo $question["explanation"] ?></textarea>
						</div>
                      </div>

                      <div class="form-group pull-right">
                        <label for="submit-edit-mc-question"></label>
                          <button type ="submit" id="submit-edit-mc-question" name="submit-edit-mc-question" value="submit-edit-mc-question" onclick="return validateForm();" class="btn btn-primary">Update Question</button>
                          <a href="manage-questions.php" id="cancel-edit-question" name="cancel-edit-question" class="btn btn-default">Cancel</a>
                          <!-- Button trigger modal -->
                          <!--<a class="btn btn-danger" data-toggle="modal" data-target="#confirm-delete-modal">Delete Question</a> -->
                      </div>

                      <!-- Confirm Deletion Modal -->
                      <div class="modal fade" id="confirm-delete-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                          <div class="modal-content">
                            <div class="modal-header">
                              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                              <h4 class="modal-title" id="confirm-delate-label">Warning!</h4>
                            </div>
                            <div class="modal-body">
                              You are about to delete a question and ALL its associated answers. This action will be irreversible.<br />
                              Do you wish to proceed?
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                              <a href="delete-question.php?question_id=<?php echo $_GET["question_id"]; ?>" class="btn btn-primary">Yes</a>
                            </div>
                          </div>
                        </div>
                      </div><!-- /.modal fade -->

                    </form>
                  </p>
                </div>
                <div class="tab-pane fade col-lg-offset-1 col-lg-6" id="new-essay-question-form">
                  <p>
                    <form class="form-horizontal" action="process-new-user.php" method="post">
                      <div class="form-group">
                        <div class="col-sm-12">
                        <label for="first_name">Question Text</label>
                        <textarea class="form-control" rows="4" id="question-text" name="question-text" value="<?php echo isset($form_values['question_text']) ? $form_values['question_text'] : '' ?>"
                          placeholder='Ex. If a six-string guitar is tuned in Standard E, what notes would each string be tuned to? List them from the lowest pitch to the highest.' class="input-xlarge"></textarea>
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-sm-12">
                        <label for="answer1 control-label">Answer 1</label>
                        <textarea class="form-control" rows="10" id="answer1" name="answer1" value="<?php echo isset($form_values['answer_text']) ? $form_values['answer_text'] : '' ?>"
                          placeholder="Ex. E, A, D, G, B, E." class="input-xlarge"></textarea>
                        </div>
                      </div>

                      <div class="form-group pull-right">
                        <label for="submit-new-user"></label>
                        <button type ="submit" id="submit-edit-question" name="submit-edit-question" value="submit-edit-question" class="btn btn-primary">Update Question</button>
                        <a href="manage-questions.php" id="cancel-edit-question" name="cancel-edit-question" class="btn btn-default">Cancel</a>
                        <!-- Button trigger modal -->
                        <a class="btn btn-danger" data-toggle="modal" data-target="#confirm-delete-modal">Delete Question</a> 
                      </div>

                    </form>
                  </p>
                </div>
              </div>
            </div>
        </div>
      </div>
  </div><!-- /#page-wrapper -->

<?php include('../includes/layouts/footer.php'); ?>
<script type='text/javascript'>
  function validateForm(){
      var cnt = 0;
      if($('#multi_answer:checked').val() == 1){
        
        $('#ischecked > :selected').each(function() {
            if($(this).val() != "")
                cnt = cnt + 1;
        });
        if(cnt < 2){
         alert("You are selecting only 1 or none answer for MuitiSeclect Anwser");
         return false;   
        }
      }
  }    
$(document).ready(function() {
  if($('#multi_answer:checked').val() != 1){
      $('#ischecked').attr({
        'multiple': false,
        'size' : 0
        });
  }
  
  $('#multi_answer').change(function(event) {  //on click
    if(this.checked == true)
    {
        $('#ischecked').attr({
        'multiple': true,
        'size' : 5
        });
    }
    else
    {
        $('#ischecked').attr({
        'multiple': false,
        'size' : 0
        });
    }
    });
 });
</script>
<?php require_once("../includes/db-connection-close.php"); ?>
