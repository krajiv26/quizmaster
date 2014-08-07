<?php require_once("../includes/sessions.php"); ?>
<?php require_once("../includes/db-connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>

<?php

  $user = get_user_by_id($_GET["user_id"]); 
  if(!$user) {
    // User id was missing or invalid or
    // could not be found in the database.
    $_SESSION["error_message"] = "Failed to delete user. User does not exist.";
    redirect_to("manage-users.php");
  }

  $id = $user["user_id"];
  $username = $user["username"];
  $query = "DELETE FROM users WHERE user_id = {$id} LIMIT 1";
  $result = mysqli_query($db, $query);

  if($result && mysqli_affected_rows($db) == 1) {
    // Success
    $_SESSION["message"] = "Successfully deleted user: {$username}.";
    redirect_to("manage-users.php");
    
  } else {
    // Failure
    redirect_to("manage-users.php");
    
  }

?>