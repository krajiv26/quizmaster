<?php
error_reporting(E_ALL);
ini_set("display_errors",1);
if(!(isset($_SESSION["authenticate"]) && $_SESSION["authenticate"] == "Yes" && isset($_SESSION["user"])))
{
	$_SESSION["error_message"] = "Please fill in the form to login.";
	header("Location: index.php");
}
$teacherPage = array('dashboard.php','take-quizzes.php','quiz.php','manage-questions.php','manage-document.php');
$studentPage = array('dashboard.php','take-quizzes.php','quiz.php');

$p = basename($_SERVER['SCRIPT_FILENAME']);
if($_SESSION["user"]["user_type"] == 'teacher'){
  if(!in_array($p,$teacherPage)){
       $_SESSION["error_message"] = "You are not authicated for this page.";
        header("Location: dashboard.php");
  }   
}
if($_SESSION["user"]["user_type"] == 'student'){
  if(!in_array($p,$studentPage)){
       $_SESSION["error_message"] = "You are not authicated for this page.";
        header("Location: dashboard.php");
  }   
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

