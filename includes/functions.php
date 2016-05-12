<?php
error_reporting(E_ALL);
ini_set("display_errors",1);
	function pr($data,$ex = "0"){
		echo "<pre>";
		print_r($data);
		echo "</pre>";
		if($ex == 1) exit; 
	}

	function pagination($targetpage,$total,$limit,$page){

		/* Setup page vars for display. */
		if ($page == 0) $page = 1; //if no page var is given, default to 1.
		$prev = $page - 1; //previous page is current page - 1
		$next = $page + 1; //next page is current page + 1
		$lastpage = ceil($total/$limit); //lastpage.
		$lpm1 = $lastpage - 1; //last page minus 1
		$adjacents = 3;
		$pagination = "";
		/* <!--<ul class="pagination">
		  <li><a href="#">&laquo;</a></li>
		  <li class="active"><a href="#">1</a></li>
		  <li class="disabled"><a href="#">2</a></li>
		  <li><a href="#">3</a></li>
		  <li><a href="#">4</a></li>
		  <li><a href="#">5</a></li>
		  <li><a href="#">&raquo;</a></li>
		</ul>-->*/
		if($lastpage > 1)
		{ 
			$pagination .= "<ul class='pagination'>";
			
			if ($page > 1)
				$pagination.= "<li><a href=\"$targetpage&page=$prev\">&laquo;</a></li>";
			else
				$pagination.= "<li class='disabled'><a onclick='return false;'>&laquo;</a></li>";


			if ($lastpage < 7 + ($adjacents * 2)) 
			{ 
				for ($counter = 1; $counter <= $lastpage; $counter++)
				{
					if ($counter == $page)
					$pagination.= "<li class='active'><a onclick='return false;' >$counter</a></li>";
					else
					$pagination.= "<li><a href=\"$targetpage&page=$counter\">$counter</a></li>"; 
				}
			}
			elseif($lastpage > 5 + ($adjacents * 2)) //enough pages to hide some
			{
				//close to beginning; only hide later pages
				if($page < 1 + ($adjacents * 2)) 
				{
					for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
					{
						if ($counter == $page)
						$pagination.= "<li class='active'><a onclick='return false;' >$counter</a></li>";
						else
						$pagination.= "<li><a href=\"$targetpage&page=$counter\">$counter</a></li>"; 
					}
					$pagination.= "<li class='disabled'><a onclick='return false;'>......</li>";
					$pagination.= "<li><a href=\"$targetpage&page=$lpm1\">$lpm1</a></li>";
					$pagination.= "<li><a href=\"$targetpage&page=$lastpage\">$lastpage</a></li>"; 
				}
				//in middle; hide some front and some back
				elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
				{
					$pagination.= "<li><a href=\"$targetpage&page=1\">1</a></li>";
					$pagination.= "<li><a href=\"$targetpage&page=2\">2</a></li>";
					$pagination.= "<li class='disabled'><a onclick='return false;'>......</li>";
					for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
					{
						if ($counter == $page)
						$pagination.= "<li class='active'><a href='#' >$counter</a></li>";
						else
						$pagination.= "<li><a href=\"$targetpage&page=$counter\">$counter</a></li>"; 
					}
					$pagination.= "<li class='disabled'><a onclick='return false;'>......</li>";
					$pagination.= "<li><a href=\"$targetpage&page=$lpm1\">$lpm1</a></li>";
					$pagination.= "<li><a href=\"$targetpage&page=$lastpage\">$lastpage</a></li>"; 
				}
				//close to end; only hide early pages
				else
				{
					$pagination.= "<li><a href=\"$targetpage&page=1\">1</a></li>";
					$pagination.= "<li><a href=\"$targetpage&page=2\">2</a></li>";
					$pagination.= "<li class='disabled'><a onclick='return false;'>......</li>";
					for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; 
					$counter++)
					{
						if ($counter == $page)
						$pagination.= "<li class='active'><a onclick='return false;' >$counter</a></li>";
						else
						$pagination.= "<li><a href=\"$targetpage&page=$counter\">$counter</a></li>"; 
					}
				}
			}

			//next button
			if ($page < $counter - 1) 
				$pagination.= "<li><a href=\"$targetpage&page=$next\">&raquo;</a></li>";
			else
				$pagination.= "<li class='disabled'><a onclick='return false;'>&raquo;</a></li>";
			$pagination.= "</ul>\n"; 
		}
		return $pagination;
	}  //end function pagination




	function redirect_to($new_location) {
		header("Location: " . $new_location);
		exit;
	}

	function confirm_query($result_set) {
		if(!$result_set) {
			die("The Database Query Failed.");
		}
	}

	function is_question_exists($question){
		global $db;
		$query 	= "SELECT * ";
		$query .= "FROM question ";
		$query .= "WHERE question_text like '%{$question}%' ";
		$query .= "LIMIT 1";
		$result = mysqli_query($db, $query);
		if($result && $result->num_rows == 1)
                {
                    $_SESSION["error_message"] .= "\nDuplicate question '".$question."'";
                    return true;  //means question is going to duplicate
                }	
		else
			return false;
	}
	function import_one_question($data){
		global $db;
                $correctAnwser = explode(",",$data['correct_answer']);
                unset($data['correct_answer']);
		$data = array_map('addslashes',$data);
		$data = array_map('htmlentities',$data);
		
		if(is_question_exists($data['question'])) return false;
		$question_text    = $data['question'];
		$answer1          = $data['option_1'];
		$answer2          = $data['option_2'];
		$answer3          = $data['option_3'];
		$answer4          = $data['option_4'];
		$category         = $data['category'];
		$explanation      = $data['explanation'];
                $created_at = date("Y-m-d H:i:s");
		$multi_answer = (isset($data['multi_answer']) && $data['multi_answer'] == 1)? 1 : 0;
                if($multi_answer == 0){
                    $is_answered = (isset($correctAnwser[0]) && intval($correctAnwser[0]) > 0)? 1 : 0;
                }
                else
                {  //multi answer
                    $is_answered = 1;
                }
		
		// Insert new question into question table
		$query  = "INSERT INTO question (";
		$query .= "  question_text, category, explanation, multi_answer, is_answered, created_at ";
		$query .= ") VALUES (";
		$query .= "  '{$question_text}', ";
		$query .= "  {$category}, '{$explanation}', {$multi_answer}, {$is_answered}, '{$created_at}'";
		$query .= ")";
		
		$result = mysqli_query($db, $query);

		if ( false === $result ) {
		  // Query failed. Print out information.
		  printf("error: %s\n", mysqli_error($db));
		  $_SESSION["error_message"][] = "Database insertion failure";
		  return false;
		} 

		$inserted_question_id = mysqli_insert_id($db);

		// Insert new question's answers into answer table
		$ischecked = false;
		  
		// Answer 1
		if(in_array(1,$correctAnwser)) { $ischecked = true; }

		$query1  = "INSERT INTO answer (";
		$query1 .= "  answer_text, question_id, is_correct";
		$query1 .= ") VALUES (";
		$query1 .= "  '{$answer1}', '{$inserted_question_id}', '{$ischecked}'";
		$query1 .= ")";

		$result1 = mysqli_query($db, $query1);

		if ( false === $result1 ) {
		  // Query failed. Print out information.
		  printf("error: %s\n", mysqli_error($db));
		  $_SESSION["error_message"][] = "Database insertion failure";
		  return false;
		}

		$ischecked = false; 

		// Answer 2
		if(in_array(2,$correctAnwser)) { $ischecked = true; }

		$query2  = "INSERT INTO answer (";
		$query2 .= "  answer_text, question_id, is_correct";
		$query2 .= ") VALUES (";
		$query2 .= "  '{$answer2}', '{$inserted_question_id}', '{$ischecked}'";
		$query2 .= ")";

		$result2 = mysqli_query($db, $query2);

		if ( false === $result2 ) {
		  // Query failed. Print out information.
		  printf("error: %s\n", mysqli_error($db));
		  $_SESSION["error_message"][] = "Database insertion failure";
		  return false;
		}

		$ischecked = false;

		// Answer 3
		if(in_array(3,$correctAnwser)) { $ischecked = true; }

		$query3  = "INSERT INTO answer (";
		$query3 .= "  answer_text, question_id, is_correct";
		$query3 .= ") VALUES (";
		$query3 .= "  '{$answer3}', '{$inserted_question_id}', '{$ischecked}'";
		$query3 .= ")";

		$result3 = mysqli_query($db, $query3);

		if ( false === $result3 ) {
		  // Query failed. Print out information.
		  printf("error: %s\n", mysqli_error($db));
		  $_SESSION["error_message"][] = "Database insertion failure";
		  return false;
		}

		$ischecked = false;

		// Answer 4
		if(in_array(4,$correctAnwser)) { $ischecked = true; }

		$query4  = "INSERT INTO answer (";
		$query4 .= "  answer_text, question_id, is_correct";
		$query4 .= ") VALUES (";
		$query4 .= "  '{$answer4}', '{$inserted_question_id}', '{$ischecked}'";
		$query4 .= ")";

		$result4 = mysqli_query($db, $query4);

		if ( false === $result4 ) {
		  // Query failed. Print out information.
		  printf("error: %s\n", mysqli_error($db));
		  $_SESSION["error_message"][] = "Database insertion failure";
		  return false;
		}  
		// Success
		return true;
	}

	function get_all_groups() {
		global $db;

	  	$query  = "SELECT * ";
	  	$query .= "FROM groups";

	  	$result = mysqli_query($db, $query);
	  	confirm_query($result);

	  	return $result;
	}
	
	

	function get_groups($id) {
		global $db;

		// Sanitize input parameter prior to making query
		$safe_user_id = mysqli_real_escape_string($db, $id);

		$query 	= "SELECT * ";
		$query .= "FROM groups ";
		$query .= "WHERE grp_id = {$id} ";
		$query .= "LIMIT 1";
		$user_set = mysqli_query($db, $query);
		confirm_query($user_set);
		if($user = mysqli_fetch_assoc($user_set)) {
			return $user;
		} else {
			return null;
		}
	}
	function get_all_users() {
		global $db;

	  	$query  = "SELECT * ";
	  	$query .= "FROM user";

	  	$result = mysqli_query($db, $query);
	  	confirm_query($result);

	  	return $result;
	}
        
        function get_all_classified($where = "") {
		global $db;
                $where = ($where == "")?' 1 = 1':$where;
	  	$query  = "SELECT * ";
	  	$query .= "FROM classified_info WHERE {$where}";

	  	$result = mysqli_query($db, $query);
	  	confirm_query($result);

	  	return $result;
	}
        
     function get_quiz_score_details($quiz_id) {
		global $db;

	  	$query  = "SELECT * ";
	  	$query .= "FROM `quiz_score` where quiz_id = {$quiz_id} ORDER BY `score` DESC";

	  	$result = mysqli_query($db, $query);
	  	confirm_query($result);

	  	return $result;
	}   
        
        
     function get_all_docs($where = "") {
		global $db;
	  	$query  = "SELECT * ";
                $query .= "FROM document WHERE is_deleted = 0 {$where}";  
	  	$result = mysqli_query($db, $query);
	  	confirm_query($result);

	  	return $result;
	}
	
	function get_all_category() {
		global $db;

	  	$query  = "SELECT * ";
	  	$query .= "FROM category";

	  	$result = mysqli_query($db, $query);
	  	confirm_query($result);
		$cat = array();
		while ($row = mysqli_fetch_assoc($result)) {
		  $cat[$row["id"]] = $row["name"];
		}
	  	return $cat;
	}



	function get_quiz_score($qs_id) {
		global $db;

		// Sanitize input parameter prior to making query
		$safe_user_id = mysqli_real_escape_string($db, $qs_id);

		$query 	= "SELECT * ";
		$query .= "FROM quiz_score ";
		$query .= "WHERE qs_id = {$qs_id} ";
		$query .= "LIMIT 1";
		$user_set = mysqli_query($db, $query);
		confirm_query($user_set);
		if($user = mysqli_fetch_assoc($user_set)) {
			return $user;
		} else {
			return null;
		}
	}
	
	function get_user_by_id($user_id) {
		global $db;

		// Sanitize input parameter prior to making query
		$safe_user_id = mysqli_real_escape_string($db, $user_id);

		$query 	= "SELECT * ";
		$query .= "FROM user ";
		$query .= "WHERE user_id = {$safe_user_id} ";
		$query .= "LIMIT 1";
		$user_set = mysqli_query($db, $query);
		confirm_query($user_set);
		if($user = mysqli_fetch_assoc($user_set)) {
			return $user;
		} else {
			return null;
		}
	}
        
        function get_classified_by_id($info_id) {
		global $db;

		// Sanitize input parameter prior to making query
		$safe_info_id = mysqli_real_escape_string($db, $info_id);

		$query 	= "SELECT * ";
		$query .= "FROM classified_info ";
		$query .= "WHERE info_id = {$safe_info_id} ";
		$query .= "LIMIT 1";
               
		$user_set = mysqli_query($db, $query);
		confirm_query($user_set);
		if($user = mysqli_fetch_assoc($user_set)) {
			return $user;
		} else {
			return null;
		}
	}
        function get_doc_by_id($doc_id) {
            
		global $db;

		// Sanitize input parameter prior to making query
		$safe_doc_id = mysqli_real_escape_string($db, $doc_id);

		$query 	= "SELECT * ";
		$query .= "FROM document ";
		$query .= "WHERE doc_id = {$safe_doc_id} AND is_deleted = 0 ";
		$query .= "LIMIT 1";
		$user_set = mysqli_query($db, $query);
		confirm_query($user_set);
		if($user = mysqli_fetch_assoc($user_set)) {
			return $user;
		} else {
			return null;
		}
	}
	
	

	function get_user_by_username($username) {
		global $db;

		// Sanitize input parameter prior to making query
		$safe_username = mysqli_real_escape_string($db, $username);

		$query 	= "SELECT * ";
		$query .= "FROM user ";
		$query .= "WHERE username = {$safe_username} ";
		$query .= "LIMIT 1";
		$user_set = mysqli_query($db, $query);
		confirm_query($user_set);
		if($user = mysqli_fetch_assoc($user_set)) {
			return $user;
		} else {
			return null;
		}
	}

	function get_all_quizzes() {
		global $db;

	  	$query  = "SELECT *";
	  	$query .= "FROM quiz WHERE is_deleted = 0";
                
	  	$result = mysqli_query($db, $query);
	  	confirm_query($result);

	  	return $result;
	}
        function get_quiz_question_count($id) {
		global $db;
	  	$query  = "SELECT a.quiz_id  FROM quiz as a inner join quiz_has_question as b on a.quiz_id = b.quiz_id where a.quiz_id = {$id}";
                $result = mysqli_query($db, $query);
	  	confirm_query($result);    
	  	return $result->num_rows;
	}

	function get_quiz_by_id($quiz_id) {
		global $db;

		// Sanitize input parameter prior to making query
		$safe_quiz_id = mysqli_real_escape_string($db, $quiz_id);

		$query 	= "SELECT * ";
		$query .= "FROM quiz ";
		$query .= "WHERE quiz_id = {$safe_quiz_id} ";
		$query .= "LIMIT 1";
		$quiz_set = mysqli_query($db, $query);
		confirm_query($quiz_set);
		if($quiz = mysqli_fetch_assoc($quiz_set)) {
			return $quiz;
		} else {
			return null;
		}
	}

	function get_all_questions($where = ' 1 = 1') {
		global $db;
		$where = ($where == "")?' 1 = 1':$where;
	  	$query  = "SELECT * ";
	  	$query .= "FROM question WHERE {$where}";
	  	//echo $query; exit;
	  	$result = mysqli_query($db, $query);
	  	confirm_query($result);

	  	return $result;
	}
	
	
	
	function get_all_questions_limit($start,$limit,$where = ' 1 = 1') {
		global $db;
		$where = ($where == "")?' 1 = 1':$where;
	  	$query  = "SELECT * ";
	  	$query .= "FROM question WHERE {$where} LIMIT {$start} ,{$limit}";
	  	$result = mysqli_query($db, $query);
	  	confirm_query($result);

	  	return $result;
	}
	
	function get_all_questions_discarded($where = ' is_discarded = 1') {
		global $db;
		$where = ($where == "")?' is_discarded = 1':$where;
	  	$query  = "SELECT * ";
	  	$query .= "FROM question WHERE {$where}";
	  	//echo $query; exit;
	  	$result = mysqli_query($db, $query);
	  	confirm_query($result);

	  	return $result;
	}
	
	function get_all_questions_limit_discarded($start,$limit,$where = ' is_discarded = 1') {
		global $db;
		$where = ($where == "")?' is_discarded = 1':$where;
	  	$query  = "SELECT * ";
	  	$query .= "FROM question WHERE {$where} LIMIT {$start} ,{$limit}";
	  	$result = mysqli_query($db, $query);
	  	confirm_query($result);

	  	return $result;
	}
	
	function remove_discarded_question($qs_list){
		$new_qa_list = array();
		if(is_array($qs_list)){
			foreach($qs_list as $qs){
				if(isDiscarded($qs) == 0) $new_qa_list[] = $qs;
			}
		}
		return $new_qa_list;
	}
	
	function isDiscarded($qid) {
		global $db;
		$query1 = "SELECT is_discarded FROM question WHERE question_id = {$qid}";
		$result = mysqli_query($db, $query1);
		confirm_query($result);
		if($user = mysqli_fetch_assoc($result)) {
			return $user['is_discarded'];
		} else {
			return null;
		}
	}
	
        function get_all_essay_questions($where = ' 1 = 1') {
		global $db;
		$where = ($where == "")?' 1 = 1':$where;
	  	$query  = "SELECT * ";
	  	$query .= "FROM essay_question WHERE {$where}";
	  	//echo $query; exit;
	  	$result = mysqli_query($db, $query);
	  	confirm_query($result);

	  	return $result;
	}
	
	function get_all_essay_questions_limit($start,$limit,$where = ' 1 = 1') {
		global $db;
		$where = ($where == "")?' 1 = 1':$where;
	  	$query  = "SELECT * ";
	  	$query .= "FROM essay_question WHERE {$where} LIMIT {$start} ,{$limit}";
	  	$result = mysqli_query($db, $query);
	  	confirm_query($result);

	  	return $result;
	}
	
	

	function get_question_by_id($question_id) {
		global $db;

		// Sanitize input parameter prior to making query
		$safe_question_id = mysqli_real_escape_string($db, $question_id);

		$query 	= "SELECT * ";
		$query .= "FROM question ";
		$query .= "WHERE question_id = {$safe_question_id} ";
		$query .= "LIMIT 1";
		$question_set = mysqli_query($db, $query);
		confirm_query($question_set);
		if($question = mysqli_fetch_assoc($question_set)) {
			return $question;
		} else {
			return null;
		}
	}
        
        function get_essay_question_by_id($question_id) {
		global $db;

		// Sanitize input parameter prior to making query
		$safe_question_id = mysqli_real_escape_string($db, $question_id);

		$query 	= "SELECT * ";
		$query .= "FROM essay_question ";
		$query .= "WHERE eq_id = {$safe_question_id} ";
		$query .= "LIMIT 1";
		$question_set = mysqli_query($db, $query);
		confirm_query($question_set);
		if($question = mysqli_fetch_assoc($question_set)) {
			return $question;
		} else {
			return null;
		}
	}

	
	function get_quiz_questions_limit($quiz_id,$start,$limit) {
		
		global $db;
		$where = "question_id IN (SELECT question_id FROM quiz_has_question WHERE quiz_id={$quiz_id})";
	  	$query  = "SELECT * ";
	  	$query .= "FROM question WHERE {$where} LIMIT {$start} ,{$limit}";
	  	//echo $query; exit;
	  	$result = mysqli_query($db, $query);
	  	confirm_query($result);

	  	return $result;
	}
	
	
	function get_quiz_questions($quiz_id) {
		
		global $db;
		$where = "question_id IN (SELECT question_id FROM quiz_has_question WHERE quiz_id={$quiz_id})";
	  	$query  = "SELECT * ";
	  	$query .= "FROM question WHERE {$where}";
	  	//echo $query; exit;
	  	$result = mysqli_query($db, $query);
	  	confirm_query($result);

	  	return $result;
	}
	
	
	
	function get_quiz_questions_id($quiz_id) {
		
		global $db;
		$where = "question_id IN (SELECT question_id FROM quiz_has_question WHERE quiz_id={$quiz_id})";
	  	$query  = "SELECT question_id ";
	  	$query .= "FROM question WHERE {$where}";
	  	//echo $query; exit;
	  	$result = mysqli_query($db, $query);
	  	confirm_query($result);
		$q_ids= array();
		while ($row = mysqli_fetch_assoc($result)) {
		  $q_ids[$row["question_id"]] = "";
		}
	  	return $q_ids;
	  	
	}
	
	function get_allowed_time($quiz_id) {
		
		global $db;
		$where = " quiz_id={$quiz_id}";
	  	$query  = "SELECT allowed_time ";
	  	$query .= "FROM quiz WHERE {$where} LIMIT 1";
	  	//echo $query; exit;
	  	
		$user_set = mysqli_query($db, $query);
		confirm_query($user_set);
		if($user = mysqli_fetch_assoc($user_set)) {
			return $user['allowed_time'];
		} else {
			return null;
		}
	  	
	}

	function get_question_answers($question_id) {
		global $db;

		// Sanitize input parameter prior to making query
		$safe_question_id = mysqli_real_escape_string($db, $question_id);

		$query 	= "SELECT * ";
		$query .= "FROM answer ";
		$query .= "WHERE question_id = {$safe_question_id}";
		$result = mysqli_query($db, $query);
		confirm_query($result);
		$answer_set = array();
		$id = 0;
		while ($row = mysqli_fetch_assoc($result)) {
		  $answer_set[$id++] = $row; 
		}
			
		return $answer_set;
	}
	
	function get_question_answers_html($question_id) {
		global $db;

		// Sanitize input parameter prior to making query
		$safe_question_id = mysqli_real_escape_string($db, $question_id);

		$query 	= "SELECT * ";
		$query .= "FROM answer ";
		$query .= "WHERE question_id = {$safe_question_id}";
		$result = mysqli_query($db, $query);
		confirm_query($result);
		$answer_set = array();
		$id = 0;
		$ansHtml = '<div style="padding-left:50px;">';
		while ($row = mysqli_fetch_assoc($result)) {
		  $answer_set[$id++] = $row; 
		  $fontClass = ($row['is_correct'] == 1)?"correct":"";
                  //if($question_id != 573 )
		  //$ansHtml .= $id.'. <span class="'.$fontClass.'">'.html_entity_decode($row['answer_text']).'</span><br>';
                  //else
                  $ansHtml .= $id.'. <span class="'.$fontClass.'">'.checkAnsFormat($row['answer_text']).'</span><br>';
		}
		$ansHtml .= '</div>';	
		return $ansHtml;
	}
        
	function checkAnsFormat($ansHtml)
        {
            $html = "";
            //echo $ansHtml;
           if(preg_match("/(.*?)\<pre>(.*?)\<\/pre\>/is", html_entity_decode($ansHtml), $matches) == 1)
           {
              $html .= $matches[1];
              $html .= "<pre><code>";
              $html .= htmlentities($matches[2]);
              $html .= "</code></pre>";
              $html .= substr(html_entity_decode($ansHtml),strrpos(html_entity_decode($ansHtml),'</pre>'));
           }
            else
                $html .= html_entity_decode($ansHtml);
            return $html;
        }
        
	function get_correct_answers($question_data) {
		global $db;
		
		//pr($question_data,1);
		$answer_data = array();
		foreach($question_data as $k=>$v)
		{
			$query = "SELECT answer_id FROM answer WHERE is_correct = 1 AND question_id = {$k}";
			$result = mysqli_query($db, $query);
			confirm_query($result);
			if($ans = mysqli_fetch_assoc($result)) {
				$answer_data[$k]=$ans['answer_id'];
			}
			else
			{
				$answer_data[$k] = "";
			}
		}
		return $answer_data;
	}
	
	function save_score($qs_id,$score) {
		global $db;
		$query1 = "UPDATE quiz_score SET score = {$score} WHERE qs_id = {$qs_id}";
		$result = mysqli_query($db, $query1);
		confirm_query($result);
	}
	
	function discard_question($qid) {
		global $db;
		$query1 = "UPDATE question SET is_discarded = 1 WHERE question_id = {$qid}";

		$result = mysqli_query($db, $query1);
		confirm_query($result);
	}
	
  function get_score($question_data,$answer_data)
  {
	  $score = 0;
	  foreach($question_data as $k=>$v)
	  {
		  if($answer_data[$k] == $v) $score++;
	  }
	 return $score;
  }
  
  function get_score_analysis($answer_sl,$quiz_id)
  {
	  
	   $data = @unserialize($answer_sl);
		//pr($data,1);
		$question_data = array();
		foreach($data as $k => $v)
		{
			$key = substr($k,9);
			if(intval($key) == 0) continue;  //other than quesiton_* will be removed
			$question_data[substr($k,9)] = $v;
		}
		$answer_data = get_correct_answers($question_data);
		
		  $all_q_id = get_quiz_questions_id($quiz_id);
		  
		  $final_result = array();
		  foreach($all_q_id as $k => $v){
			  $final_result[$k] = (@$answer_data[$k] == @$question_data[$k]) ? 1: 0;
		  }
		 return $final_result;
  }
  
  function get_ques_difficulty_level($answer_sl,$quiz_id)
  {
	  $quesDlevel = array();
	   $data = @unserialize($answer_sl);
		//pr($data,1);
		$question_data = array();
		foreach($data as $k => $v)
		{
			$key = substr($k,9);
			if(intval($key) == 0) continue;  //other than quesiton_* will be removed
			$question_data[substr($k,9)] = $v;
		}
		  $all_q_id = get_quiz_questions_id($quiz_id);
		  $final_result = array();
		  foreach($all_q_id as $k => $v){
				$q_ans =get_question_answers($k);
				$ans_id = @$question_data[$k];
				foreach($q_ans as $key => $val){
					$quesDlevel[$k][$key]= ($val['answer_id'] == $ans_id )?1:0;
					if($key == 3) {
						$quesDlevel[$k][4] = (array_sum($quesDlevel[$k]) == 0)?1:0;
					} 
				}
		  }
		 return $quesDlevel;
  }
  
  function get_questionwise_difficulty_index($questionwise_array,$userCnt){
	  $groupCnt = intval($userCnt*0.27);
	   $final_array = array();
	   $j = 0;
		foreach($questionwise_array as $v){
			for($i=0; $i<$groupCnt; $i++){
			@$final_array[$j]['c_u_grp'] += $v[$i];
		    }
		    for($i=($userCnt - $groupCnt); $i< $userCnt; $i++){
			@$final_array[$j]['c_l_grp'] += $v[$i];
		    }
		    
		    $final_array[$j]['difficulty'] = ($final_array[$j]['c_u_grp'] + $final_array[$j]['c_l_grp'])/($groupCnt*2);
		    $final_array[$j]['dicrimination'] = ($final_array[$j]['c_u_grp'] - $final_array[$j]['c_l_grp'])/($groupCnt);
		    $j++;
		}
	return $final_array;
	  
  }
  
  function range_difficulty($value){
	   $text = "";
		if($value >= 0.81)  $text = "Easy";
		else if($value < 0.81 && $value >= 0.20) $text = "Right Difficulty";
		else $text = "Difficult";
		return $text;
	  }
  function range_discrimination($value){
	   $text = "";
		if($value >= 0.46)  $text = "Discriminating item";
		else if($value < 0.46 && $value >= -0.55) $text = "Non-discriminating";
		else $text = "Can discriminate but item is questionable";
		return $text;
	  }
  function mystats_standard_deviation(array $a, $sample = false) {
        $n = count($a);
        if ($n === 0) {
            trigger_error("The array has zero elements", E_USER_WARNING);
            return false;
        }
        if ($sample && $n === 1) {
            trigger_error("The array has only 1 element", E_USER_WARNING);
            return false;
        }
        $mean = array_sum($a) / $n;
        $carry = 0.0;
        foreach ($a as $val) {
            $d = ((double) $val) - $mean;
            $carry += $d * $d;
        };
        if ($sample) {
           --$n;
        }
        return sqrt($carry / $n);
    }
  
  
  
  function get_percentage_score($final_result){
	  $score = 0;
	  foreach($final_result as $k=>$v){
		  $score += $v;
	  }
	 $percent = ($score*100)/count($final_result);
	  return number_format($percent,2);
  }
  function checkUserType()
  {
      if(isset($_SESSION["user"]["user_type"]))
          return $_SESSION["user"]["user_type"];
      return false;
      
  }
?>
