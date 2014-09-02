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
function nl2br2($string) {
$string = str_replace(array("\r\n", "\r", "\n"), "<br />", $string);
return $string;
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

function process_answer_option($str)
{
	$ansArray = array("1" =>"a","2" =>"b","3" => "c", "4" => "d");
	$str = trim($str);
	return array_search(substr($str,-1),$ansArray);
}

// Retrieve the DOM from a given URL
//$html = file_get_html('file:///var/www/html/quizmaster/upload/uk.html');
$html = nl2br2(file_get_contents('/var/www/html/quizmaster/upload/madyapradesh civil judge class 2 2007Text.html'));
//echo $html;
$htmlArray = explode("<br />",$html);

$out = fopen('/var/www/html/quizmaster/upload/new.csv', 'w');
fputcsv($out, array('question','option_1','option_2','option_3','option_4','correct_answer','category','explanation','multi_answer'));
// Find all "span" tags and print their HREFs
$cnt = count($htmlArray);
$i = 0;
while ($i < $cnt)
{
    $i = $i +3;
    $data = array();
    $data['question'] = $htmlArray[$i++];
    $data['option_1'] = process_answer($htmlArray[$i++]);
    $data['option_2'] = process_answer($htmlArray[$i++]);
    $data['option_3'] = process_answer($htmlArray[$i++]);
    $data['option_4'] = process_answer($htmlArray[$i++]);
    $i++;
    $data['correct_answer'] = process_answer_option($htmlArray[$i++]);
    $data['category'] = "6";
    $data['explanation'] = "";
    $data['multi_answer'] = "";
    pr($data);
   //question,option_1,option_2,option_3,option_4,correct_answer,category,explanation,multi_answer
   
	fputcsv($out, array($data['question'],$data['option_1'],$data['option_2'],$data['option_3'],$data['option_4'],$data['correct_answer'],$data['category'],'',''));

 echo "---------------------<br>";      

}
fclose($out);
exit;



  
?>
