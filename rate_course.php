<?php
//this file is required to rate a course from course_details_stud.php page. Is called from function rateCourse from course_details_stud.js
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

$MM_restrictGoTo = "index.php#login";
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
?>
<?php
mysql_select_db($database_conn, $conn);
if (isset($_GET['c_id']) && isset($_GET['rate_value']) && $_GET['rate_value']>=0 &&  $_GET['rate_value']<=5) {
	//check if the user is indeed enrolled in course
	$query_check_enroll=sprintf("SELECT * FROM `enroll_course` WHERE u_id=%s AND c_enroll_id=%s",GetSQLValueString($_SESSION['MM_UserID'], "int"),GetSQLValueString($_GET['c_id'], "int"));
	$check_enroll = mysql_query($query_check_enroll, $conn) or die(mysql_error());
	$row_check_enroll = mysql_fetch_assoc($check_enroll);
	$totalRows_check_enroll= mysql_num_rows($check_enroll);
	if($totalRows_check_enroll==1) {
		//indeed enrolled, set rating.
		$query_set_rating=sprintf("UPDATE `enroll_course` SET rating=%s WHERE u_id=%s AND c_enroll_id=%s",GetSQLValueString($_GET['rate_value'], "double"),GetSQLValueString($_SESSION['MM_UserID'], "int"),GetSQLValueString($_GET['c_id'], "int"));
		$set_rating = mysql_query($query_set_rating, $conn) or die(mysql_error());
		//setting avg_rating after change
	mysql_select_db($database_conn, $conn);
	$query_old_rating=sprintf("((SELECT avg_rating * (SELECT count( * ) FROM enroll_course WHERE c_enroll_id =%s ) as old_count FROM course WHERE c_id =%s))",GetSQLValueString($_GET['c_id'], "int"),GetSQLValueString($_GET['c_id'], "int"));
	$old_rating = mysql_query($query_old_rating, $conn) or die(mysql_error());
	$row_old_rating= mysql_fetch_assoc($old_rating);
	
	$query_set_avg_rating=sprintf("update `course` set `avg_rating`= (select avg(rating) from `enroll_course` where c_enroll_id=%s AND rating>0 )where c_id=%s",GetSQLValueString($_GET['c_id'], "int"),GetSQLValueString($_GET['c_id'], "int"));
	$set_avg_rating = mysql_query($query_set_avg_rating, $conn) or die(mysql_error());
	
	$query_set_author_score=sprintf("UPDATE `user` set user_score=(user_score-%s+((SELECT avg_rating * (SELECT count( * ) FROM enroll_course WHERE c_enroll_id =%s ) as old_count FROM course WHERE c_id =%s))) where user.u_id=(select course.u_id from `course` where course.c_id=%s)",GetSQLValueString($row_old_rating['old_count'], "int"),GetSQLValueString($_GET['c_id'], "int"),GetSQLValueString($_GET['c_id'], "int"),GetSQLValueString($_GET['c_id'], "int"));
	$set_author_score = mysql_query($query_set_author_score, $conn) or die(mysql_error());
	
	}
}


?>