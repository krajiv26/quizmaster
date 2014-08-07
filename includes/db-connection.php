<?php

  define("DB_SERVER", "localhost");
  define("DB_USER", "root");
  define("DB_PASS", "123456");
  define("DB_NAME", "quizmaster");

  // 1. Create a database connection
  $db = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);

  // Test if the database connection occurred
  if(mysqli_connect_errno()) {
    die("Database connection failed: " . mysqli_connect_error() .
        " (" . mysqli_connect_errno() . ")"
    );
  }
  
?>
