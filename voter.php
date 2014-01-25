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
if (isset($_POST['up']) ||  isset($_POST['down'])){
		//checking to prevent self-voting
		$query_check_voter = sprintf("SELECT * FROM comment WHERE comment_id =%s",GetSQLValueString($_POST['id'], "int"));
		$check_voter = mysql_query($query_check_voter, $conn) or die(mysql_error());
		$row_check_voter=mysql_fetch_assoc($check_voter);
		$prev_score=$row_check_voter['comment_score'];
		if ($row_check_voter['insert_uid']==$_SESSION['MM_UserID']) {
			//self voting, return 0 as failure
			echo 0;
		}else {
			//fair voting, update the comment score.
			$query_update_score = sprintf("UPDATE comment SET comment_score=%s WHERE comment_id =%s",GetSQLValueString($_POST['count'], "int"),GetSQLValueString($_POST['id'], "int"));
			$update_score = mysql_query($query_update_score, $conn) or die(mysql_error());
			
			//update user-vote given to comment in user_comment table
			$new_score=$_POST['count'];
			$difference=$new_score-$prev_score;
			if($_POST['upstatus']==true && $difference==1) {
				$vote_value=1;
			//}else if ($_POST['upstatus']==false && $_POST['downstatus']==false) {
			}else if (empty($_POST['upstatus']) && empty($_POST['downstatus'])) {
				$vote_value=0;
			}else if ($_POST['downstatus']==true && $difference==-1) {
				$vote_value=-1;
			}
			$query_update_usercomment = sprintf("UPDATE user_comment SET vote_status=%s WHERE user_comment_id=%s AND user_id=%s",GetSQLValueString($vote_value, "int"),GetSQLValueString($_POST['id'], "int"),GetSQLValueString($_SESSION['MM_UserID'], "int"));
			$update_usercomment=mysql_query($query_update_usercomment, $conn) or die(mysql_error());
			echo 1;
		}
}
?>