<?php

	session_start();

	ini_set('session.bug_compat_warn', 0);
	ini_set('session.bug_compat_42', 0);

	function errors() {
		if(isset($_SESSION["errors"])) {
			$output = $_SESSION["errors"];

			// Clear errors after use.
			$_SESSION["errors"] = null;

			return $output;
		}
	}

	function form_history() {
		if(isset($_SESSION["form_history"])) {
			$output = $_SESSION["form_history"];

			// Clear errors after use.
			$_SESSION["form_history"] = null;

			return $output;
		}
	}

	function user_form_info_msg() {
		if(isset($_SESSION["message"])) {
			$message  = "<div class=\"alert alert-dismissable alert-info col-lg-4 col-lg-offset-1 pull-left\">";
			$message .= "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">×</button>";
			$message .= "<strong>" . htmlentities($_SESSION["message"]) . "</strong></div>";

			$_SESSION["message"] = null;

			return $message;
		} 
	}

	function user_form_failure_msg() {
		if(isset($_SESSION["error_message"])) {
			$message  = "<div class=\"alert alert-dismissable alert-danger col-lg-4 col-lg-offset-1 pull-left\">";
			$message .= "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">×</button>";
			$message .= "<strong>" . htmlentities($_SESSION["error_message"]) . "</strong></div>";

			$_SESSION["error_message"] = null;

			return $message;
		} 
	}

	function user_form_success_msg() {
		if(isset($_SESSION["message"])) {
			$message  = "<div class=\"alert alert-dismissable alert-success col-lg-12 pull-left\">";
			$message .= "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">×</button>";
			$message .= "<strong>" . htmlentities($_SESSION["message"]) . "</strong></div>";

			$_SESSION["message"] = null;

			return $message;
		} 
	}

	function delete_failure_msg() {
		if(isset($_SESSION["error_message"])) {
			$message  = "<div class=\"alert alert-dismissable alert-danger col-lg-12 pull-left\">";
			$message .= "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">×</button>";
			$message .= "<strong>" . htmlentities($_SESSION["error_message"]) . "</strong></div>";

			$_SESSION["error_message"] = null;

			return $message;
		} 
	}
	
?>