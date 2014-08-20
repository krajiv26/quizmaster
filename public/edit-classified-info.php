<?php require_once("../includes/sessions.php"); ?>
<?php require_once("../includes/db-connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php require_once("../includes/validation-functions.php"); ?>

<?php $page = "edit-classified-info.php"; ?>

<?php include('../includes/layouts/header.php'); ?>

<?php
 $cat = get_all_category();
  // If user does not exist, redirect back to Manage Users Page
  $id = isset($_GET["info_id"]) ? $_GET["info_id"] : "";
  $user = get_classified_by_id($id);
  if(!$user) { redirect_to("manage-classified-info.php"); }
?>

<?php
  
  if(isset($_POST['submit-edit-info'])) {


    $fields_with_max_lengths = array("title" => 250);
    foreach($fields_with_max_lengths as $field => $max) {
      $value = trim($_POST[$field]);
      if(!value_within_range($value, 1, $max)) {
        $error_messages[$field] = ucfirst($field) . " is too long.";
      }
    }

    $fields_required = array("title", "description");
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

      $info_id      = $id;
      $title   = $_POST['title'];
      $description    = $_POST['description'];
      $category    = $_POST['category'];
      

      $query  = "UPDATE classified_info SET ";
      $query .= "title = '{$title}', ";
      $query .= "description = '{$description}', ";
      $query .= "category = {$category} ";
      $query .= "WHERE info_id = {$info_id} ";
      $query .= "LIMIT 1";

      $result = mysqli_query($db, $query);

      if($result && mysqli_affected_rows($db) == 1) {
        // Success
        $_SESSION["message"] = "Successfully updated new classified info";
        redirect_to("manage-classified-info.php");
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
        <h1>Edit Classified Info </h1>
        <ol class="breadcrumb">
          <li><a href="dashboard.php"><i class="icon-dashboard"></i> Dashboard</a></li>
          <li class="active"><i class="icon-file-alt"></i> Edit Classified Info</li>
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
      <div class="col-lg-offset-1 col-lg-8">
        
        <form class="form-horizontal" action="edit-classified-info.php?info_id=<?php echo $user["info_id"]; ?>" method="post">
          <!-- Text input-->
          <div class="form-group">
            <label for="title">Title</label>
              <input class="form-control" id="title" name="title" value="<?php echo $user["title"]; ?>" type="text" placeholder="Enter First Name" class="input-xlarge">
          </div>
          <div class="form-group">
            <label for="category control-label">Category</label>
            <select name="category">
                <?php foreach($cat as $k=>$v)
                {
                        $sel = ($k == $user["category"])? 'selected="selected"':'';
                        echo '<option value="'.$k.'" '.$sel.'>'.$v.'</option>';
                }?>
            </select>
	 </div>  
          <div class="form-group">

            <label for="description">Description</label>
            <textarea class="form-control" rows="15" id="description" name="description"  placeholder="Ex. Table structure" class="input-xlarge"><?php echo $user["description"]; ?></textarea>

          </div>
          

          <!-- Button (Double) -->
          <div class="form-group pull-right">
            <label for="submit-edit-user"></label>
              <button type ="submit" id="submit-edit-user" name="submit-edit-info" value="submit-edit-info" class="btn btn-primary">Update Info</button>
              <a href="manage-classified-info.php" id="cancel-edit-user" name="cancel-edit-user" class="btn btn-default">Cancel</a>
              <!-- Button trigger modal -->
          </div>
        </form> 

      </div><!-- /.col-lg-6 -->

    </div><!-- /.row -->

  </div><!-- /#page-wrapper -->

<?php include('../includes/layouts/footer.php'); ?>
<?php require_once("../includes/db-connection-close.php"); ?>
