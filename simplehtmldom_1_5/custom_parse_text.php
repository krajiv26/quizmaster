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
	$ansArray = array("1" =>"A","2" =>"B","3" => "C", "4" => "D");
	$str = trim($str);
	if(preg_match("/\((.*)\)/",$str,$matches) == 1)
		return array_search(trim($matches[1]),$ansArray);
	return "";
}

// Retrieve the DOM from a given URL
//$html = file_get_html('file:///var/www/html/quizmaster/upload/uk.html');
$html = nl2br2(file_get_contents('/var/www/html/quizmaster/upload/tobeuploaded/UPHJS Pre 2014 | Uttar Pradesh Higher Judicial Service (Preliminary) Examination- 2014 text.html'));
//echo $html;
$htmlArray = explode("<br />",$html);

$out = fopen('/var/www/html/quizmaster/upload/new.csv', 'w');
fputcsv($out, array('question','option_1','option_2','option_3','option_4','correct_answer','category','explanation','multi_answer'));
// Find all "span" tags and print their HREFs
$cnt = count($htmlArray);
$i = 0;
while ($i < $cnt)
{
    $i++;
    $data = array();
    $data['question'] = process_question($htmlArray[$i++]);
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

/*

1. Among the following cities, which one is nearest to the Topic of Cancer.
(A) Delhi
(B) Kolkata
(C) Jodhpur
(D) Nagpur

Ans. (B)

2. Which one of the following Articles of the Indian Constitution provides that it shall be the duty of the Union to protect every state against external aggression and internal disturbance?
(A) Article 215
(B) Article 275
(C) Article 325
(D) Article 353

Ans. (C)

* */

  
?>
