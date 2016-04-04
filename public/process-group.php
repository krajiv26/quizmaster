<?php require_once("../includes/sessions.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php require_once("../includes/validation-functions.php"); ?>
<?php require_once("../includes/db-connection.php"); ?>

<?php
  

  if(isset($_POST['submit-edit-group'])) {
    // The New User Form was submitted

    // If inputs were valid begin insertion.
    $_POST = array_map('addslashes',$_POST);
    $_POST = array_map('htmlentities',$_POST);

    $addGroups  = $_POST['add-group-members-textarea'];
    $grpId  = $_POST['grp_id'];
    
    $query  = "Update groups Set ";
    $query  .= " grp_emails = '{$addGroups}'";
    $query .= " where grp_id = {$grpId}";
    
    $result = mysqli_query($db, $query);

    if($result) {
      // Success
      $_SESSION["message"] = "Successfully updated group!";
      redirect_to("manage-groups.php");
      
    } else {
      // Failure
      $_SESSION["error_message"] = "Database insertion failure";
      redirect_to("edit-group.php?user_id={$grpId}");
      
    }

  } else {
    // This is probably a get request
    $_SESSION["message"] = "Please fill in the form to edit groups.";
    redirect_to("edit-group.php?user_id={$grpId}");
    
  } 
?>

<?php require_once("../includes/db-connection-close.php"); ?>
