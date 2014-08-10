<?php require_once("../includes/sessions.php"); ?>
<?php require_once("../includes/db-connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php require_once("../includes/validation-functions.php"); ?>

<?php $page = "edit-document.php"; ?>

<?php include('../includes/layouts/header.php'); ?>

<?php
$cat = get_all_category();
  // If user does not exist, redirect back to Manage Users Page
  $id = isset($_GET["doc_id"]) ? $_GET["doc_id"] : "";
  $doc = get_doc_by_id($id);
  if(!$doc) { redirect_to("manage-document.php"); }
?>

<?php
  
  if(isset($_POST['submit-edit-document'])) {

    // The Edit User Form was submitted

    // Validate Edit User Form inputs
    $fields_with_max_lengths = array("title" => 250, "filename" => 250 );
    foreach($fields_with_max_lengths as $field => $max) {
      $value = trim($_POST[$field]);
      if(!value_within_range($value, 1, $max)) {
        $error_messages[$field] = ucfirst($field) . " is too long.";
      }
    }

    $fields_required = array("title", "filename", "file_type");
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

      $doc_id       = $id;
      $title        = $_POST['title'];
      $description  = $_POST['description'];
      $filename     = $_POST['filename'];
      $category     = $_POST['category'];
      $file_type    = $_POST['file_type'];
      

      $query  = "UPDATE document SET ";
      $query .= "title = '{$title}', ";
      $query .= "description = '{$description}', ";
      $query .= "filename = '{$filename}', ";
      $query .= "category = '{$category}', ";
      $query .= "file_type = '{$file_type}' ";
      $query .= "WHERE doc_id = {$doc_id} ";
      $query .= "LIMIT 1";

      $result = mysqli_query($db, $query);

      if($result && mysqli_affected_rows($db) == 1) {
        // Success
        $_SESSION["message"] = "Successfully updated document!";
        redirect_to("manage-document.php");
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
        <ol class="breadcrumb">
          <li><a href="dashboard.php"><i class="icon-dashboard"></i> Dashboard</a></li>
          <li class="active"><i class="icon-file-alt"></i> Edit Document</li>
        </ol>
        <hr />
      </div><!-- /.col-lg-12 -->
    </div><!-- /.row -->

    <!-- Display conditional error message -->
    <?php 
      if(!empty($message)) { 
    
        echo "<div class=\"alert alert-dismissable alert-danger col-lg-4 col-lg-offset-1 pull-left\">";
        echo "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">×</button>";
        echo "<strong><?php echo $message; ?></strong>";
        echo "</div>";
      }
    ?>
    <?php echo display_form_errors($error_messages); ?>
  
      <div class="clearfix"></div>
      <div class="col-lg-offset-1 col-lg-4">
        
        <form class="form-horizontal" action="edit-document.php?doc_id=<?php echo $doc["doc_id"]; ?>" method="post">
          <!-- Text input-->
          <div class="form-group">
            <label for="title">Title</label>
              <input class="form-control" id="title" name="title" value="<?php echo $doc["title"]; ?>" type="text" placeholder="Enter Title" class="input-xlarge">
          </div>

          <!-- Text input-->
          <div class="form-group">
            <label for="description">Description</label>
              <input class="form-control" id="description" name="description" value="<?php echo $doc["description"]; ?>" type="text" placeholder="Enter Description" class="input-xlarge">
          </div>

          <!-- Text input-->
          <div class="form-group">
            <label for="filename">Filename</label>
              <input class="form-control" id="filename" name="filename" value="<?php echo $doc["filename"]; ?>" type="text" placeholder="Enter Filename" class="input-xlarge">
          </div>

          <!-- Text input-->
          <div class="form-group">
                <label for="category control-label">Category</label>
                <select class="form-control" name="category">
                    <?php foreach($cat as $k=>$v)
                    {
                            $sel = ($k == $doc["category"])? 'selected="selected"':'';
                            echo '<option value="'.$k.'" '.$sel.'>'.$v.'</option>';
                    }?>
                </select>
                        
            </div>

          <!-- Password input-->
          <div class="form-group">
            <label for="file_type">File Type</label>
              <input class="form-control" id="file_type" name="file_type" type="text" value="<?php echo $doc["file_type"]; ?>" class="input-xlarge">
          </div>

          <!-- Button (Double) -->
          <div class="form-group pull-right">
            <label for="submit-edit-document"></label>
              <button type ="submit" id="submit-edit-user" name="submit-edit-document" value="submit-edit-document" class="btn btn-primary">Update Document</button>
              <a href="manage-document.php" id="cancel-edit-user" name="cancel-edit-user" class="btn btn-default">Cancel</a>
              <!-- Button trigger modal -->
              <!--<a class="btn btn-danger" data-toggle="modal" data-target="#confirm-delete-modal">Delete User</a>   -->
          </div>
        </form>

        <!-- Confirm Deletion Modal -->
        <div class="modal fade" id="confirm-delete-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="confirm-delate-label">Warning!</h4>
              </div>
              <div class="modal-body">
                You are about to delete a user. This action will be irreversible.<br />
                Do you wish to proceed?
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                <a href="delete-document.php?doc_id=<?php echo $user["doc_id"]; ?>" class="btn btn-primary">Yes</a>
              </div>
            </div>
          </div>
        </div><!-- /.modal fade -->

      </div><!-- /.col-lg-6 -->

    </div><!-- /.row -->

  </div><!-- /#page-wrapper -->

<?php include('../includes/layouts/footer.php'); ?>
<?php require_once("../includes/db-connection-close.php"); ?>
