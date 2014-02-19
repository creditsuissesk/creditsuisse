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
<?php require_once('delete_comment.php'); ?>
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
if(isset($_POST['actiontype']) && $_POST['actiontype']=="delete") {
	if(isset($_POST['comment_id']) && isset($_POST['redirect_disc_id']) ) {
	
		$query_comment = sprintf("SELECT * FROM `comment` WHERE comment_id=%s",GetSQLValueString($_POST['comment_id'], "int"));
		$comment = mysql_query($query_comment, $GLOBALS['conn']) or die(mysql_error());
		$row_comment = mysql_fetch_assoc($comment);
		$totalRows_comment = mysql_num_rows($comment);
		if($_SESSION['MM_UserID']==$row_comment['insert_uid']) {
			deleteComment($_POST['comment_id'],$_SESSION['MM_UserID']);	
		}
		else
		//case for admin moderation 
			if($_SESSION['MM_UserGroup']=='admin')
			{
				deleteComment($_POST['comment_id'],$_POST['insert_uid']);
			}
		}
	}//end of two post variables if case
	else{ if (isset($_POST['actiontype']) && $_POST['actiontype']=="flag") {
		//flag comment
				$flag_comment = sprintf("UPDATE `comment` SET flag=1 WHERE comment_id=%s",GetSQLValueString($_POST['comment_id'], "int"));
				$result_flag_comment = mysql_query($flag_comment, $conn) or die(mysql_error());

	}
	else {
		if(isset($_POST['actiontype']) && $_POST['actiontype']=="unflag")
	{
		//unflag comment
		$unflag_comment = sprintf("UPDATE `comment` SET flag=0 WHERE comment_id=%s",GetSQLValueString($_POST['comment_id'], "int"));
				$unresult_flag_comment = mysql_query($unflag_comment, $conn) or die(mysql_error());
	}
	}
		header("Location: forum_new.php?showTab=discussions&mode=disc&discussionid=".$_POST['redirect_disc_id']);
}
	
?>