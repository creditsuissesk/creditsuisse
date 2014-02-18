<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Resource</title>

<!--- files for voting--->
<script src="lib/jQuery.js" type="text/javascript"></script>
<script src="lib/jquery.upvote.js" type="text/javascript"></script>
<link href="lib/jquery.upvote.css" rel="stylesheet" type="text/css">

</head>
<?php
//this page generates resource floating layout.
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
?>
<body>
<?php
mysql_select_db($database_conn, $conn);
if(isset($_GET['r_id']) && (isset($_GET['actiontype']) && $_GET['actiontype']=="loadResource")) {
	//show resource in float
	$query_get_resource= sprintf("SELECT * FROM `resource` WHERE r_id=%s",GetSQLValueString($_GET['r_id'], "int"));
	$get_resource = mysql_query($query_get_resource, $conn) or die(mysql_error());
	$row_get_resource = mysql_fetch_assoc($get_resource);
	$totalRows_get_resource= mysql_num_rows($get_resource);
	//now we need to divide the page layout output according to type of resource, but that later.
	if($row_get_resource['file_type']=="application/pdf") {
		//outputting code to show pdf
		echo '<embed height="500" width="900" src="'.$row_get_resource['view_location'].'">';
	}else if ($row_get_resource['file_type']=="image/jpeg") {
		echo '<embed height="300" width="600" src="'.$row_get_resource['view_location'].'">';
	}
}if(isset($_GET['r_id']) && (isset($_GET['actiontype']) && $_GET['actiontype']=="loadRating")) {
	//load the bookmarking and flagging float.
	$query_get_resource= sprintf("SELECT * FROM `resource` LEFT OUTER JOIN `user_resource` ON resource.r_id=user_resource.user_resource_id WHERE r_id=%s",GetSQLValueString($_GET['r_id'], "int"));
	$get_resource = mysql_query($query_get_resource, $conn) or die(mysql_error());
	$row_get_resource = mysql_fetch_assoc($get_resource);
	$totalRows_get_resource= mysql_num_rows($get_resource);
	echo '<table style="margin-left:60px;"><tr><td>';
	echo '<div id="res'.$row_get_resource['r_id'].'" class="upvote">';
	echo '<a class="upvote"></a>';
	echo '<span class="count">0</span>';
	echo '<a class="downvote"></a>';
	echo '<a class="star"></a>';
	echo '</div>';
	echo '</td><td>';
	if($row_get_resource['flag_status']==1) {
		echo "<img id='flagged' src='images/red_flag.png' width='30' height='30'/>";
	}else {
		echo "<img id='unflagged' src='images/flag.png' style='cursor:pointer;' width='30' height='30' onclick='flag(".$row_get_resource['r_id'].");'/>";
	}
	echo '</td></tr></table>';
	echo '<script language="javascript">';
	
	echo "function flag(r_id) {
	var r=confirm(\"Are you sure you want to flag this discussion?\");
			if (r==true){
				$.ajax({
					url: 'voter_res.php',
					type: 'post',
					data: { id: r_id,action:'flag'},
					success: function (response) {
						if(response==1) {
							document.getElementById('unflagged').src= 'images/red_flag.png';
						}
					}
				});	
	  		} 
	};";
	
	echo "var res_callback = function(data) {";
	echo "	$.ajax({
		url: 'voter_res.php',
		type: 'post',
		data: { id: data.id, up: data.upvoted, down: data.downvoted, star: data.starred , count: $('#res'+ data.id).upvote('count'),upstatus:$('#res'+data.id).upvote('upvoted'),downstatus:$('#res'+data.id).upvote('downvoted')},
		});	
	};";
	echo "
	var res_callback2= function(data) {
		if($('#res'+data.id).upvote('upvoted')==true || $('#res'+data.id).upvote('downvoted')==true) { ";
	echo	'alert("You can\'t vote yourself");';
	echo "	}
		$.ajax({
			url: 'voter_res.php',
			type: 'post',
			data: {id: data.id, star:data.starred}
		});
	};";
	
	if($row_get_resource['uploaded_by']==$_SESSION['MM_UserID']){
			echo "$('#res".$row_get_resource['r_id']."').upvote({count:".$row_get_resource['avg_rating'].",id:".$row_get_resource['r_id'].", callback: res_callback2";
			if ($row_get_resource['bookmarked']==1) {
				echo ",starred:1";
			} 
			echo "});";
	} else {
			echo "$('#res".$row_get_resource['r_id']."').upvote({count:".$row_get_resource['avg_rating'].",id:".$row_get_resource['r_id'].", callback: res_callback";
			if ($row_get_resource['vote_status']==1) {
				echo ",upvoted:1";
			}else if ($row_get_resource['vote_status']==-1){
				echo ",downvoted:1";
			}
			if ($row_get_resource['bookmarked']==1) {
				echo ",starred:1";
			}
			echo "});";
	}
	echo '</script>';
}
?>
</body>
</html>