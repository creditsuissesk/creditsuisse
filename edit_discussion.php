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
	if(isset($_POST['disc_id']) && isset($_POST['redirect_url']) ) {
		echo "all POST set";
		
		//get discussion's details for score etc
		$query_disc = sprintf("SELECT * FROM `discussion` WHERE discussion_id=%s",GetSQLValueString($_POST['disc_id'], "int"));
		$disc = mysql_query($query_disc, $conn) or die(mysql_error());
		$row_disc = mysql_fetch_assoc($disc);
		$totalRows_disc = mysql_num_rows($disc);
		
		//if user is actually discussion poster then only proceed
		if($_SESSION['MM_UserID']==$row_disc['insert_uid']) {
			//first, delete all comments in that discussion
				$get_comments=sprintf("SELECT comment_id,insert_uid FROM `comment` WHERE discussion_id=%s",GetSQLValueString($_POST['disc_id'], "int"));
				$comment=mysql_query($get_comments,$conn);
				//$row_comment_id=mysql_fetch_assoc($comment);
				
				/*do{
					deleteComment($row_comment_id['comment_id'],$row_comment_id['insert_uid']);
				}while($row_comment_id=mysql_fetch_assoc($comment));*/
				while($row_comment_id=mysql_fetch_assoc($comment)) {
					deleteComment($row_comment_id['comment_id'],$row_comment_id['insert_uid']);
				}
			
			//delete discussion
			$delete_disc = sprintf("DELETE FROM `discussion` WHERE discussion_id=%s",GetSQLValueString($_POST['disc_id'], "int"));
			$disc_delete = mysql_query($delete_disc, $conn) or die(mysql_error());
			
				//discussion deleted successfully now decrement poster's discussion count and score
				$update_user = sprintf("UPDATE `user` SET count_discussions=count_discussions-1, user_score=user_score-%s WHERE u_id=%s",GetSQLValueString($row_disc['rating'], "int"),GetSQLValueString($_SESSION['MM_UserID'], "int"));
				$result_update_user = mysql_query($update_user, $conn) or die(mysql_error());
				
				
				
		}
		$insertGoTo = "index.php";
		if (isset($_SERVER['QUERY_STRING'])) {
		  $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
		  $insertGoTo .= $_SERVER['QUERY_STRING'];		 
		}
		}//end of two post variables if case
	} else if (isset($_POST['actiontype']) && $_POST['actiontype']=="flag") {
		//flag discussion
				$flag_disc = sprintf("UPDATE `discussion` SET flag=1 WHERE discussion_id=%s",GetSQLValueString($_POST['disc_id'], "int"));
				$result_flag_disc= mysql_query($flag_disc, $conn) or die(mysql_error());
	} else if (isset($_POST['actiontype']) && $_POST['actiontype']=="unflag") {
		//check if session owner is root, only then proceed
		if($_SESSION['MM_UserGroup']=='admin') {
			//unflag discussion
			$unflag_disc = sprintf("UPDATE `discussion` SET flag=0 WHERE discussion_id=%s",GetSQLValueString($_POST['disc_id'], "int"));
			$unresult_flag_disc= mysql_query($unflag_disc, $conn) or die(mysql_error());
		}
	}
	header("Location: ".$_POST['redirect_url']);
?>