<?php require_once("../includes/sessions.php"); ?>
<?php require_once("../includes/db-connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php
$doc_id = (isset($_GET['doc_id']) && intval($_GET['doc_id']) > 0)?$_GET['doc_id']:"";

$fileContentType = array('pdf' => 'application/pdf', 'doc' => 'application/msword', 'xls' => 'application/vnd.ms-excel');

$docRow = get_doc_by_id($doc_id);

$path = "../document/";
$file = $docRow['filename'];
$title = $docRow['title'];


header('Content-type: '.$fileContentType[$docRow['file_type']]);
header('Content-Disposition: inline; filename="' . $title . '"');
header('Content-Transfer-Encoding: binary');
header('Content-Length: ' . filesize($path.$file));
header('Accept-Ranges: bytes');

@readfile($path.$file);
?>
