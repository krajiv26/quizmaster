<?php require_once("../includes/sessions.php"); ?>
<?php require_once("../includes/db-connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php require_once("../includes/validation-functions.php"); ?>

<?php $page = "edit-essay-question.php"; 
//pr($_SESSION["get_query"],1);
if($_SESSION["get_query"] != "")
$redirect_url = "manage-essay-questions.php?".$_SESSION["get_query"];
else
$redirect_url = "manage-essay-questions.php";

?>

<?php include('../includes/layouts/header.php'); ?>
<?php
$cat = get_all_category();
 $id = isset($_REQUEST["eq_id"]) ? $_REQUEST["eq_id"] : ""; 
  
  $question = get_essay_question_by_id($id);

  if(!$question) { redirect_to($redirect_url); }
  $answer_set = get_question_answers($id);
?>

<?php
  
  // Temporary hack to simulate logged in user

  if(isset($_POST['submit-edit-essay-question'])) {

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
    
    // Update Multiple Choice Question
    $query  = "UPDATE essay_question SET ";
    $query .= "question_text = '{$question_text}', ";
    $query .= "category = {$category}, ";
    $query .= "answer = '{$answer}',";
    $query .= "is_answered = {$is_answered} ";
    $query .= "WHERE eq_id = {$id} ";
    $query .= "LIMIT 1";
    
    $result = mysqli_query($db, $query);

    if ( false === $result ) {
      // Query failed. Print out information.
      printf("error: %s\n", mysqli_error($db));
      $_SESSION["error_message"] = mysqli_error($db).". In edit-question.php @ query.";
      redirect_to($redirect_url);

    } 

    // Success
    $_SESSION["message"] = "Successfully updated essay question!";
    redirect_to($redirect_url);
    

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
                <li class="active"><a href="#new-essay-question-form" data-toggle="tab">Essay</a></li>
              </ul>
              <div id="myTabContent" class="tab-content">
                <div class="tab-pane fade active in col-lg-offset-1 col-lg-6" id="new-essay-question-form">
                  <p>
                    <form class="form-horizontal" action="edit-essay-question.php?eq_id=<?php echo $_GET["eq_id"]; ?>" method="post">
                      <div class="form-group">
                        <div class="col-sm-12">
                        <label for="question_text">Question Text</label>
                        <textarea class="form-control" rows="4" id="question_text" name="question_text" 
                          placeholder='Ex. If a six-string guitar is tuned in Standard E, what notes would each string be tuned to? List them from the lowest pitch to the highest.' class="input-xlarge"><?php echo $question["question_text"] ?></textarea>
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-sm-12">
                        <label for="answer control-label">Answer</label>
                        <textarea class="form-control" rows="10" id="answer" name="answer" 
                          placeholder="Ex. E, A, D, G, B, E." class="input-xlarge"><?php echo $question["answer"] ?></textarea>
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
                      <div class="form-group pull-right">
                        <label for="submit-new-user"></label>
                        <button type ="submit" id="submit-edit-essay-question" name="submit-edit-essay-question" value="submit-edit-essay-question" class="btn btn-primary">Update Question</button>
                        <a href="manage-essay-questions.php" id="cancel-edit-question" name="cancel-edit-question" class="btn btn-default">Cancel</a>
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

<?php require_once("../includes/db-connection-close.php"); ?>
