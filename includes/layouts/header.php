<?php
if(!(isset($_SESSION["authenticate"]) && $_SESSION["authenticate"] == "Yes" && isset($_SESSION["user"])))
{
	$_SESSION["error_message"] = "Please fill in the form to login.";
	header("Location: index.php");
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Randy Michael Ledbetter">

    <title>Test Builder Pro</title>
    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.css" rel="stylesheet">

    <!-- Add custom CSS here -->
    
    <link rel="stylesheet" href="font-awesome/css/font-awesome.min.css">
    <link href="css/sb-admin.css" rel="stylesheet">
    <link href="css/main.css" rel="stylesheet">
    <link href="css/bootstrap-wysihtml5.css" rel="stylesheet">
    
  </head>

