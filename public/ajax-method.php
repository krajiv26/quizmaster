<?php require_once("../includes/sessions.php"); ?>
<?php require_once("../includes/db-connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php require_once("../includes/validation-functions.php"); ?>

<?php $page = "ajax-method.php"; ?>

<?php

if($_REQUEST["method"] == 'discard'){
	
	discard();
	
}



function discard(){
	
	$qid = (isset($_REQUEST["qid"]) && $_REQUEST["qid"] !="")?$_REQUEST["qid"]:0;
	if($qid > 0 ){
		discard_question($qid);
	}

}
?>


<?php require_once("../includes/db-connection-close.php"); ?>
