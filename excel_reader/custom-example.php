<?php
include 'excel_reader.php';     // include the class

// creates an object instance of the class, and read the excel file data
$excel = new PhpExcelReader;
$excel->read('../document/books.xls');

// Excel file data is stored in $sheets property, an Array of worksheets
/*
The data is stored in 'cells' and the meta-data is stored in an array called 'cellsInfo'

Example (firt_sheet - index 0, second_sheet - index 1, ...):

$sheets[0]  -->  'cells'  -->  row --> column --> Interpreted value
         -->  'cellsInfo' --> row --> column --> 'type' (Can be 'date', 'number', or 'unknown')
      
      
      
                                            --> 'raw' (The raw data that Excel stores for that data cell)
                                            * 
                                            * 
                                            * ALTER TABLE `classified_info` ADD `created_at` TIMESTAMP NULL ,
ADD `modified_at` TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00';
*/


// this function creates and returns a HTML table with excel rows and columns data
// Parameter - array with excel worksheet data
function sheetData($sheet) {
  $re = htmlentities('<table id="example" class="table table-striped table-bordered" cellspacing="0" width="100%">').'<br>';     // starts html table

  $x = 1;
  while($x <= $sheet['numRows']) {
	if($x == 1) $re .= "&nbsp;&nbsp;&nbsp;&nbsp;".htmlentities('<thead>')."<br>";
	if($x == 2) $re .= "&nbsp;&nbsp;&nbsp;&nbsp;".htmlentities('<tbody>')."<br>";
	   
    $re .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".htmlentities("<tr>")."<br>";
    $y = 1;
    while($y <= $sheet['numCols']) {
      $cell = isset($sheet['cells'][$x][$y]) ? $sheet['cells'][$x][$y] : '';
      if($x == 1)
      $re .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".htmlentities("<th>$cell</th>")."<br>";  
      else
      $re .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".htmlentities("<td>$cell</td>")."<br>";  
      $y++;
    }
     
    $re .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".htmlentities("</tr>")."<br>";
    if($x == 1) $re .= "&nbsp;&nbsp;&nbsp;&nbsp;".htmlentities('</thead>')."<br>"; 
    $x++;
  }
	$re .= "&nbsp;&nbsp;&nbsp;&nbsp;".htmlentities('</tbody>')."<br>";
	$re .= htmlentities('</table>')."<br>---------------------------------------<br>";
    return $re;
}

$nr_sheets = count($excel->sheets);       // gets the number of sheets
$excel_data = '';              // to store the the html tables with data of each sheet

// traverses the number of sheets and sets html table with each sheet data in $excel_data
for($i=0; $i<$nr_sheets; $i++) {
  $excel_data .= '<h4>Sheet '. ($i + 1) .' (<em>'. $excel->boundsheets[$i]['name'] .'</em>)</h4>'. sheetData($excel->sheets[$i]) .'<br/>';  
}
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8" />
<title>Example PHP Excel Reader</title>
<style type="text/css">
table {
 border-collapse: collapse;
}        
td {
 border: 1px solid black;
 padding: 0 0.5em;
}        
</style>
</head>
<body>

<?php
// displays tables with excel file data
echo $excel_data;
?>    

</body>
</html>
