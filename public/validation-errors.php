<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>validation Errors</title>
    <link rel="stylesheet" href="style.css">
    <script src="script.js"></script>
  </head>
  <body>
    <?php

    // Array to hold all form errors
    $errors = array();

    // * presence
    $value = "";
    if(!isset($value) || empty($value)) {
    	$errors['value'] = "Value can not be blank.";
    }

    // * string length
    // min length
    $value = "abcde";
    $min = 3;
    if(strlen($value) < $min) {
      echo "Validation failed.<br />";
    }

    // max length
    $max = 6;
    if(strlen($value) > $max) {
      echo "Validation failed.<br />";
    }

    // * type
    $value = "1";
    if(!is_string($value)) {
      echo "Validation failed.<br />";
    }

    // * inclusion in a set
    $value = "4";
    $set = array("1", "2", "3", "4");
    if(!in_array($value, $set)) {
      echo "Validation failed.<br />";
    }

    // * uniqueness



    // * format


    // * email
    $email = "rledbetter@mail.com";
    $bogus_email = "bogus";

    if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      echo "Please enter a valid email address.";
    }

    print_r($errors);

    ?>
  </body>
</html>