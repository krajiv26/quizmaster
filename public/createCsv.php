<?php require_once("../includes/sessions.php"); ?>
<?php require_once("../includes/db-connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php require_once("../includes/validation-functions.php"); ?>

<?php $page = "import-question.php"; ?>

<?php include('../includes/layouts/header.php'); ?>


<?php
  if(isset($_POST['submit-import-mc-question'])) {

		if (isset($_FILES["file"])) {

            //if there was an error uploading the file
        if ($_FILES["file"]["error"] > 0) {
            $_SESSION["error_message"] = "Return Code: " . $_FILES["file"]["error"] . "<br />";

        }
        else {
		
            /* echo "Upload: " . $_FILES["file"]["name"] . "<br />";
             echo "Type: " . $_FILES["file"]["type"] . "<br />";
             echo "Size: " . ($_FILES["file"]["size"] / 1024) . " Kb<br />";
             echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br />";*/
			if( $_FILES["file"]["type"] != 'text/csv'){
				$_SESSION["error_message"] = " Please upload only csv file. This is not valid file type ";
			}
			else {
			

			$upload_dir = $_SERVER['DOCUMENT_ROOT'] . "/quizmaster/upload/";
			
			if (file_exists($upload_dir) && is_writable($upload_dir)) {
				// do upload logic here
				   //if file already exists
				 if (file_exists($upload_dir . $_FILES["file"]["name"])) {
					$_SESSION["error_message"] = $_FILES["file"]["name"] . " already exists. ";
				 }
				 else {
						//Store file in directory "upload" with the name of "uploaded_file.txt"
					$storagename = basename($_FILES["file"]["name"])."_".time().".csv";
					if(move_uploaded_file($_FILES["file"]["tmp_name"], $upload_dir . $storagename)){
						$_SESSION["message"] =  "Stored in: " . "upload/" . $_FILES["file"]["name"];
						$f = fopen($upload_dir.$storagename,"r");
						$cnt = 0;
						while(! feof($f))
						  {
							$cnt++;
							if($cnt == 1) {
							$header = fgetcsv($f);
							}
							else
							{
								$data = array();
								foreach(fgetcsv($f) as $k=>$v)
								{
									$data[$header[$k]] = $v;
								}
								// Go for db insertion:
								if(trim($data['question']) != "")
									import_one_question($data);
							}
						  }
						fclose($f);
					}
					
				}
				
			}
			else {
				$_SESSION["error_message"] = 'Upload directory is not writable, or does not exist.';
			}	 
			}	 
				 
              
        }
     } else {
             $_SESSION["error_message"] = "No file selected <br />";
     }
    
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
        <h1>Import Question <small>Control Panel</small></h1>
        <ol class="breadcrumb">
          <li><a href="dashboard.php"><i class="icon-dashboard"></i> Dashboard</a></li>
          <li class="active"><i class="icon-file-alt"></i> Upload Question</li>
        </ol>
        <hr />
      </div><!-- /.col-lg-12 -->
    </div><!-- /.row -->
	NOTE: This upload functionality is currently for 4 option question with 1 correct Answer.
    <?php echo user_form_info_msg(); ?>
    <?php echo user_form_failure_msg(); ?>
    <div class="clearfix"></div>
      <div class="row">
        <div class="col-lg-12">
          <h2 id="nav-tabs">Question Upload in CSV format</h2>
            <div class="bs-example">
              
              <div id="myTabContent" class="tab-content">
                <div class="tab-pane fade active in col-lg-offset-1 col-lg-6" id="edit-mc-question-form">
                  <p>
                    <form class="form-horizontal" action="import-question.php" method="post" enctype="multipart/form-data">
                      <div class="form-group">
                        <div class="col-sm-12">
                        <label for="first_name">Upload Csv File:</label>
                        <input type="file" name="file" id="file" />
						</div>
                      </div>
                      

                      <div class="form-group pull-right">
                        <label for="submit-import-mc-question"></label>
                          <button type ="submit" id="submit-import-mc-question" name="submit-import-mc-question" value="submit-import-mc-question" class="btn btn-primary">Upload Question</button>
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
<?php require_once("../includes/db-connection-close.php"); ?>
