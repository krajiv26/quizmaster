<?php
error_reporting(E_ALL);
ini_set("display_errors",1);

include('simple_html_dom.php');
function pr($data,$ex = "0"){
		echo "<pre>";
		print_r($data);
		echo "</pre>";
		if($ex == 1) exit; 
	}

function process_question($str)
{
	$str_array= explode(".",$str);
	unset($str_array[0]);
	//return  trim(implode(".",$str_array));
	return html_entity_decode(trim(implode(".",$str_array)));
}
function process_answer($str)
{
	$str = trim($str);
	$f_str = preg_replace("/\([A-Da-d]\)/", "", substr($str,0,3)).substr($str,3);;
	return html_entity_decode(trim($f_str));
}
// Retrieve the DOM from a given URL
//$html = file_get_html('file:///var/www/html/quizmaster/upload/uk.html');
$html = file_get_html('/var/www/html/quizmaster/upload/uphjsc12.html');
//echo $html; exit;
$out = fopen('/var/www/html/quizmaster/upload/new.csv', 'w');
fputcsv($out, array('question','option_1','option_2','option_3','option_4','correct_answer','category','explanation','multi_answer'));
// Find all "span" tags and print their HREFs
foreach($html->find('span') as $element)
{
      // echo $element. '<br>'; 
   $keywords = explode("<br />", $element);
   //question,option_1,option_2,option_3,option_4,correct_answer,category,explanation,multi_answer
   $cnt = count($keywords);
	for($i = 3; $i < $cnt; $i=$i+6)
	{
	fputcsv($out, array(process_question($keywords[$i]),process_answer($keywords[$i+1]),process_answer($keywords[$i+2]),process_answer($keywords[$i+3]),process_answer($keywords[$i+4]),'','','',''));
	}

pr($keywords);
 echo "---------------------<br>";      

}
fclose($out);
exit;



  
?>
