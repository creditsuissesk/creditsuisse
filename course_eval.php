<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "admin,student,author,cm";
$MM_donotCheckaccess = "false";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = False; 

  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings into arrays. 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    // Or, you may restrict access to only certain users based on their username. 
    if (in_array($UserGroup, $arrGroups)) { 
      $isValid = true; 
    } 
    if (($strUsers == "") && false) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "index.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($_SERVER['QUERY_STRING']) && strlen($_SERVER['QUERY_STRING']) > 0) 
  $MM_referrer .= "?" . $_SERVER['QUERY_STRING'];
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}
?>
<?php require_once('Connections/conn.php'); ?>
<?php
	if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

mysql_select_db($database_conn, $conn);
if(isset($_GET['c_id']) && isset($_GET['ques']) && isset($_GET['opt1']) && isset($_GET['opt2']) && isset($_GET['opt3']) && isset($_GET['opt4']) && isset($_GET['correct'])) {
	//check if c_id is actually created by current session owner, because it is sent from a hidden field which can be tampered.
	$query_check_course = sprintf("SELECT * FROM `course` WHERE c_id=%s",GetSQLValueString($_GET['c_id'], "int"));
	$check_course = mysql_query($query_check_course, $conn) or die(mysql_error());
	$row_check_course = mysql_fetch_assoc($check_course);
	$totalRows_check_course = mysql_num_rows($check_course);
	//if course creator is indeed session owner, then only proceed
	if($row_check_course['u_id']==$_SESSION['MM_UserID']) {
		//get current number of questions existing for the course so as to give next question number to this question
		$query_count_ques = sprintf("SELECT * FROM `course_eval` WHERE c_id_eval=%s",GetSQLValueString($_GET['c_id'], "int"));
		$count_ques = mysql_query($query_count_ques, $conn) or die(mysql_error());
		$row_count_ques = mysql_fetch_assoc($count_ques);
		$totalRows_count_ques = mysql_num_rows($count_ques);
		
		$this_ques_no=$totalRows_count_ques+1;
		//now insert the question into table;
		$query_insert_ques = sprintf("INSERT INTO `course_eval`(c_id_eval,q_no,ques,opt1,opt2,opt3,opt4,answer) VALUES (%s,%s,%s,%s,%s,%s,%s,%s)",GetSQLValueString($_GET['c_id'], "int"),GetSQLValueString($this_ques_no, "int"),GetSQLValueString($_GET['ques'], "text"),GetSQLValueString($_GET['opt1'], "text"),GetSQLValueString($_GET['opt2'], "text"),GetSQLValueString($_GET['opt3'], "text"),GetSQLValueString($_GET['opt4'], "text"),GetSQLValueString($_GET['correct'], "text"));
		$insert_ques = mysql_query($query_insert_ques, $conn) or die(mysql_error());
	}
}else if (isset($_GET['show_questions'])) {
	//questions need to be showed. check course author's authenticity.
	$query_check_course = sprintf("SELECT * FROM `course` WHERE c_id=%s",GetSQLValueString($_GET['show_questions'], "int"));
	$check_course = mysql_query($query_check_course, $conn) or die(mysql_error());
	$row_check_course = mysql_fetch_assoc($check_course);
	$totalRows_check_course = mysql_num_rows($check_course);
	//if course creator is indeed session owner, then only proceed
	if($row_check_course['u_id']==$_SESSION['MM_UserID']) {

		//get current questions
		$query_count_ques = sprintf("SELECT * FROM `course_eval` WHERE c_id_eval=%s ORDER BY q_no",GetSQLValueString($_GET['show_questions'], "int"));
		$count_ques = mysql_query($query_count_ques, $conn) or die(mysql_error());
		$row_count_ques = mysql_fetch_assoc($count_ques);
		$totalRows_count_ques = mysql_num_rows($count_ques);
		if ($totalRows_count_ques>0) {
		do {
			echo '<li>';
			echo '<h4 style="color:#93CDF5;float:left;">'.$row_count_ques['ques'].'</h4><br /><br />';
			echo '<table>';
			echo '<tr><td>';  
			if($row_count_ques['answer']==1) {echo '<img src="images/mark-right.jpg" width="15" height="15"/>';}
			else {echo '<img src="images/mark-wrong.jpg" width="15" height="15"/>';}
			echo '</td>';
			echo '<td>'.$row_count_ques['opt1'].'</td></tr>';
			echo '<tr><td>';  
			if($row_count_ques['answer']==2) {echo '<img src="images/mark-right.jpg" width="15" height="15"/>';}
			else {echo '<img src="images/mark-wrong.jpg" width="15" height="15" />';}
			echo '</td>';
			echo '<td>'.$row_count_ques['opt2'].'</td></tr>';
			echo '<tr><td>';  
			if($row_count_ques['answer']==3) {echo '<img src="images/mark-right.jpg" width="15" height="15"/>';}
			else {echo '<img src="images/mark-wrong.jpg" width="15" height="15"/>';}
			echo '</td>';
			echo '<td>'.$row_count_ques['opt3'].'</td></tr>';
			echo '<tr><td>';  
			if($row_count_ques['answer']==4) {echo '<img src="images/mark-right.jpg" width="15" height="15"/>';}
			else {echo '<img src="images/mark-wrong.jpg" width="15" height="15"/>';}
			echo '</td>';
			echo '<td>'.$row_count_ques['opt4'].'</td></tr>';
			echo '</table>';
			echo '</li>';
		}while($row_count_ques = mysql_fetch_assoc($count_ques));
		}else {
			echo "No questions have been entered for this course yet.";
		}
	}
}else if (isset($_GET['takeTest'])) {
	//load the test MCQs
	//check whether the test taker is indeed enrolled in this course
	$query_check_student = sprintf("SELECT * FROM `enroll_course` WHERE c_enroll_id=%s AND u_id=%s",GetSQLValueString($_GET['takeTest'], "int"),GetSQLValueString($_SESSION['MM_UserID'], "int"));
	$check_student = mysql_query($query_check_student, $conn) or die(mysql_error());
	$row_check_student = mysql_fetch_assoc($check_student);
	$totalRows_check_student = mysql_num_rows($check_student);
	
	if ($totalRows_check_student==1) {
		//request is authentic. proceed loading questions.
		//load the test.
		$query_load_test = sprintf("SELECT * FROM `course_eval` WHERE c_id_eval=%s ORDER BY q_no ASC",GetSQLValueString($_GET['takeTest'], "int"));
	$load_test = mysql_query($query_load_test, $conn) or die(mysql_error());
	$row_load_test = mysql_fetch_assoc($load_test);
	$totalRows_load_test = mysql_num_rows($load_test);
		
		echo '<div class="container">';
		echo '<main class="course-content-exam" style="background-color:#FFF;border-radius:5px;">';
		echo '<div id="course" style="height:300px;position:relative;padding:0px;">';
		echo '<div style="max-height:100%;overflow:auto;"><div class="evaluation">';
		echo '<ul id="questionslist" style="overflow:auto;">';
		echo '<form name="evalform" id="evalform">';
		do {
			echo '<li>';
			echo '<div id="question'.$row_load_test['q_no'].'" style="color:#000;font-size:14px;">';
			echo 'Q. '.$row_load_test['q_no'].' : '.$row_load_test['ques'];
			echo '</div>';
			echo '<table style="color:#000;">';
			echo '<tr><td><input type="radio" id="r'.$row_load_test['q_no'].'-1" name="answer'.$row_load_test['q_no'].'" value="'.$row_load_test['q_no'].'-1"/></td><td>'.$row_load_test['opt1'].'</td></tr>';
			echo '<tr><td><input type="radio" id="r'.$row_load_test['q_no'].'-2" name="answer'.$row_load_test['q_no'].'" value="'.$row_load_test['q_no'].'-2"/></td><td>'.$row_load_test['opt2'].'</td></tr>';			
			echo '<tr><td><input type="radio" id="r'.$row_load_test['q_no'].'-3" name="answer'.$row_load_test['q_no'].'" value="'.$row_load_test['q_no'].'-3"/></td><td>'.$row_load_test['opt3'].'</td></tr>';
			echo '<tr><td><input type="radio" id="r'.$row_load_test['q_no'].'-4" name="answer'.$row_load_test['q_no'].'" value="'.$row_load_test['q_no'].'-4"/></td><td>'.$row_load_test['opt4'].'</td></tr>';
			echo '</table>';
			echo '</li>';
		}while($row_load_test = mysql_fetch_assoc($load_test));
		echo '<input id="submit'.$_GET['takeTest'].'" type="button" class="buttom" value="Submit Answers" onclick="submitTest('.$totalRows_load_test.')" />';
		echo '<input id="hiddenId" type="hidden" value="'.$_GET['takeTest'].'" />';
		echo '</form>';
		echo '</ul>';
		echo '</div></div></div>';
		echo '</main><!-- .content -->';
		echo '</div><!---container --->';
	}
}else if (isset($_GET['evalTest'])) {
	//get the course id to be evaluated
	$c_id=$_GET['evalTest'];
	//check whether the test taker is indeed enrolled in this course
	$query_check_student = sprintf("SELECT * FROM `enroll_course` WHERE c_enroll_id=%s AND u_id=%s",GetSQLValueString($_GET['evalTest'], "int"),GetSQLValueString($_SESSION['MM_UserID'], "int"));
	$check_student = mysql_query($query_check_student, $conn) or die(mysql_error());
	$row_check_student = mysql_fetch_assoc($check_student);
	$totalRows_check_student = mysql_num_rows($check_student);
	if ($totalRows_check_student==1) {
		//request is authentic. proceed loading questions.
		
		//get all the questions and answers for the course test.
		$query_load_test = sprintf("SELECT * FROM `course_eval` WHERE c_id_eval=%s ORDER BY q_no ASC",GetSQLValueString($_GET['evalTest'], "int"));
		$load_test = mysql_query($query_load_test, $conn) or die(mysql_error());
		$row_load_test = mysql_fetch_assoc($load_test);
		$totalRows_load_test = mysql_num_rows($load_test);
		
		$condition="";
		$q_no=1;	
		foreach($_GET as $key => $value)
		{
			if(is_numeric($key)) {//only the GET parameters which are questions are kept numeric, so this conditions filters whether a GET parameter is (question,answer) pair or not.
			   //echo 'Question = ' . $key . '<br />';
			   //echo 'Answer= ' . $value;
			   if($q_no==1) {
				   $condition.="(q_no=".$key." AND answer=".$value.")";
				   
			   }else {
					$condition.=" OR (q_no=".$key." AND answer=".$value.")";
			   }
			   $q_no+=2;
			}
		}
		$query_eval_test=sprintf("SELECT * FROM `course_eval` WHERE c_id_eval=%s AND (",GetSQLValueString($_GET['evalTest'], "int"));
		$query_eval_test.=$condition.")";
		$eval_test = mysql_query($query_eval_test, $conn) or die(mysql_error());
		$row_eval_test = mysql_fetch_assoc($eval_test);
		$totalRows_eval_test = mysql_num_rows($eval_test);
		
		//$totalRows_eval_test contains number of answers correctly given. Save score= this * 10 into database as candidates score.
		$query_save_score=sprintf("UPDATE `enroll_course` SET marks=%s,completion_stat=1 WHERE u_id=%s AND c_enroll_id=%s",GetSQLValueString($totalRows_eval_test*10, "int"),GetSQLValueString($_SESSION['MM_UserID'], "int"),GetSQLValueString($_GET['evalTest'], "int"));
		$save_score = mysql_query($query_save_score, $conn) or die(mysql_error());
		
		echo "Score recorded successfully! You scored ".($totalRows_eval_test*10)." marks out of ".($totalRows_load_test*10).".";
		
	}
}
?>