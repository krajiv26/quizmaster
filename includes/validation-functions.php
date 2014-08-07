<?php

  $error_messages = array();

  // Tests for non empty value.
  function has_presence($value) {
    return ((isset($value)) && ($value !== ""));
  }

  // Tests if value is >= $min AND <= $max.
  function value_within_range($value, $min, $max) {
    return ((strlen($value) >= $min) && (strlen($value) <= $max));
  }

  // Tests for presence of value in supplied $array parameter.
  function value_is_in_set($value, $array) {
    return in_array($value, $array);
  }

  // Tests for valid email address format.
  function email_is_valid($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
  }

  function display_form_errors($errors = array()) {
    $output = "";
    if(!empty($errors)) {
    	$output  = "<div class=\"alert alert-dismissable alert-danger col-lg-4 col-lg-offset-1 pull-left\">";
    	$output .= "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">×</button>";
    	$output .= "<ul>";
    	foreach($errors as $key => $error) {
    		$output .= "<li>{$error}</li>";
    	}
    	$output .= "</ul>";
    	$output .= "</div>";
      }

      return $output; 
  }

?>
