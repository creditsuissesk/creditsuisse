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
if (isset($_GET['r_id']) &&  isset($_GET['rate_value'])){
		//checking to prevent self-voting
		$query_check_voter = sprintf("SELECT * FROM `resource` WHERE r_id=%s",GetSQLValueString($_GET['r_id'], "int"));
		$check_voter = mysql_query($query_check_voter, $conn) or die(mysql_error());
		$row_check_voter=mysql_fetch_assoc($check_voter);
		if ($row_check_voter['uploaded_by']==$_SESSION['MM_UserID']) {
			//self voting, return 0 as failure
			echo 0;
		}else {
			//fair voting, update the resource rating.
			//$query_update_score = sprintf("UPDATE `resource` SET avg_rating=%s WHERE r_id =%s",GetSQLValueString($_POST['count'], "int"),GetSQLValueString($_POST['id'], "int"));
			//$update_score = mysql_query($query_update_score, $conn) or die(mysql_error());

			//update user-vote given to resource in user_resource table
			/*$new_score=$_POST['count'];
			$difference=$new_score-$prev_score;
			
			if($_POST['upstatus']=="true" || $_POST['upstatus']=="1") {
				$vote_value=1;
			}else if ($_POST['downstatus']=="true" || $_POST['downstatus']=="1") {
				$vote_value=-1;	
			}else if (empty($_POST['upstatus']) and empty($_POST['downstatus'])) {
				$vote_value=0;
			}*/

			$query_update_userres=sprintf("INSERT INTO `user_resource` (u_id,user_resource_id,rating,bookmarked,date_last_viewed) VALUES ('%s','%s','%s','0',now()) ON DUPLICATE KEY UPDATE date_last_viewed=now(),rating=%s;",GetSQLValueString($_SESSION['MM_UserID'], "int"),GetSQLValueString($_GET['r_id'], "int"),GetSQLValueString($_GET['rate_value'], "double"),GetSQLValueString($_GET['rate_value'], "double"));
			$update_userres=mysql_query($query_update_userres, $conn) or die(mysql_error());
			
			mysql_select_db($database_conn, $conn);
			$query_set_avg_rating=sprintf("update `resource` set `avg_rating`= (select avg(rating) from `user_resource` where user_resource_id=%s )where r_id=%s AND rating>0",GetSQLValueString($_GET['r_id'], "int"),GetSQLValueString($_GET['r_id'], "int"));
		$set_avg_rating = mysql_query($query_set_avg_rating, $conn) or die(mysql_error());
		
			//update the score of user to whom vote was given
			//$query_update_author = sprintf("UPDATE `resource` JOIN user ON uploaded_by=u_id SET user_score=user_score+%s WHERE r_id=%s",GetSQLValueString($difference, "int"),GetSQLValueString($_POST['id'], "int"));
			//$update_author = mysql_query($query_update_author, $conn) or die(mysql_error());
			

			echo 1;
		}		
}
if(isset($_POST['r_id']) && isset($_POST['action']) && isset($_POST['value'])) {
	if($_POST['action']=="bookmark") {
		//regardless of whoever is user, anyone can bookmark resource.
			if($_POST['value']=="1" ){
					$bookmarked=1;
			}else if ($_POST['value']=="0"){
					$bookmarked=0;
			}
			
			$query_update_userbookmark=sprintf("INSERT INTO `user_resource` (u_id,user_resource_id,rating,bookmarked,date_last_viewed) VALUES ('%s','%s','0.0','%s',now()) ON DUPLICATE KEY UPDATE date_last_viewed=now(),bookmarked=%s;",GetSQLValueString($_SESSION['MM_UserID'], "int"),GetSQLValueString($_POST['r_id'], "int"),GetSQLValueString($bookmarked, "int"),GetSQLValueString($bookmarked, "int"));
			$update_userbookmark=mysql_query($query_update_userbookmark, $conn) or die(mysql_error());
			
			echo 1;
	}
}
if
(isset($_POST['action']) && $_POST['action']=='flag') {
	//check if user is indeed enrolled in the course if is flagging resource of.
	$query_check_enroll=sprintf("SELECT * FROM `enroll_course` WHERE u_id=%s AND c_enroll_id=(SELECT c_id FROM `resource` WHERE r_id=%s)",GetSQLValueString($_SESSION['MM_UserID'], "int"),GetSQLValueString($_POST['id'], "int"));
	$check_enroll = mysql_query($query_check_enroll, $conn) or die(mysql_error());
	$row_check_enroll=mysql_fetch_assoc($check_enroll);
	$totalRows_check_enroll= mysql_num_rows($check_enroll);
	if($totalRows_check_enroll==1) {
		//user actually enrolled. now flag resource.
		$query_flag_res = sprintf("UPDATE `resource` SET flag_status=1 WHERE r_id=%s",GetSQLValueString($_POST['id'], "int"));
		$flag_res = mysql_query($query_flag_res, $conn) or die(mysql_error());
		echo "1"; //echoeing success to change flag icon 
	}
}
?>