<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Resource</title>

<!--- files for rating--->
<script type="text/javascript" src="js/jquery.min.js"></script> 
<script src="./js/jquery.rateit.js" type="text/javascript"></script>
<script type="text/javascript" src="js/course_details_stud.js"></script>
<script type="text/javascript" src="js/flowplayer-3.2.11.min.js"></script>
<script type="text/javascript" src="js/resource_float.js"></script>
<link href="./css/rateit.css" rel="stylesheet" type="text/css">
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
	}else if (strpos($row_get_resource['file_type'],"image")!==false) {
		echo '<embed height="300" width="600" src="'.$row_get_resource['view_location'].'">';
	}else if (strpos($row_get_resource['file_type'],"video")!==false) {
		echo '<div id="holder"></div>';
		echo '<script>$(document).ready(function(){load('.$row_get_resource['r_id'].');});</script>';	
	}else if (strpos($row_get_resource['file_type'],"powerpoint")!==false || strpos($row_get_resource['file_type'],"presentation")!==false) {
		echo '<embed height="500" width="900" src="'.$row_get_resource['view_location'].'">';
	}
}

if(isset($_GET['r_id']) && (isset($_GET['actiontype']) && $_GET['actiontype']=="loadRating")) {
	//load the bookmarking and flagging float.
	$query_get_resource= sprintf("SELECT * FROM `resource` LEFT OUTER JOIN `user_resource` ON resource.r_id=user_resource.user_resource_id WHERE r_id=%s",GetSQLValueString($_GET['r_id'], "int"));
	$get_resource = mysql_query($query_get_resource, $conn) or die(mysql_error());
	$row_get_resource = mysql_fetch_assoc($get_resource);
	$totalRows_get_resource= mysql_num_rows($get_resource);
	echo '<table style="margin-left:10px;"><tr><td>';
	echo '<div class= "rateit bigstars" data-rateit-starwidth="32" data-rateit-starheight="32" id="rateit" data-rateit-value="'.$row_get_resource['rating'].'" data-rateit-ispreset="true">';
	echo '</div>';
	echo '<div>';
	echo '<span style="margin-left:15px;" id="hover"></span>';
	echo '</div>';
	echo '</td></tr><tr><td>';
	if($row_get_resource['bookmarked']==1) {
		//already bookmarked
		echo "<img id='book_y' style='cursor:pointer;' src='images/bookmark_y.png' width='30' height='30' title='You have bookmarked this resource. Click to remove bookmark' onclick='bookmarkResource(this,".$row_get_resource['r_id'].");'/>";
	}else {
		echo "<img id='book_n' style='cursor:pointer;' src='images/bookmark_n.png' width='30' height='30' title='Click to bookmark this resource' onclick='bookmarkResource(this,".$row_get_resource['r_id'].");'/>";
		//not already bookmarked
	}
	
	if($row_get_resource['flag_status']==1) {
		//already flagged
		echo "<img id='flagged' src='images/red_flag.png' width='30' height='30' title='This resource has been flagged'/>";
	}else {
		//not flagged
		echo "<img id='unflagged' src='images/flag.png' style='cursor:pointer;' width='30' height='30' onclick='flag(".$row_get_resource['r_id'].");'/>";
	}
	
	echo '</td></tr></table>';
	?>
    <script type="text/javascript">
		var ratingType=['Poor','Fair','Good','Very Good','Excellent!'];
		$('#rateit').bind('over', function (event,value) { 
			$(this).attr('title', value);
			if(value==null) {
				$('#hover').text("");
			}else {
				$('#hover').text(ratingType[value-1]);
			}
		});
		$('#rateit').on('beforerated', function (e, value) {
		if (!confirm('Are you sure you want to rate this resourse '+ ratingType[Math.floor(value)-1]+ "?")) {
			e.preventDefault();
		}
		});
		$('#rateit').on('beforereset', function (e) {
			if (!confirm('Are you sure you want to reset the rating?')) {
				e.preventDefault();
			}
		});
		$("#rateit").bind('rated', function (event, value) { 
			var rateReturn=rateResource(<?php echo $row_get_resource['r_id'];?>,value);
		});
		$("#rateit").bind('reset', function () { rateResource(<?php echo $row_get_resource['r_id'];?>,0); });
	</script>
	<?php echo '</script>';
}

if(isset($_GET['r_id']) && (isset($_GET['actiontype']) && $_GET['actiontype']=="loadQR")) {
	//get resource file details.
	$query_get_resource= sprintf("SELECT * FROM `resource` LEFT OUTER JOIN `user_resource` ON resource.r_id=user_resource.user_resource_id WHERE r_id=%s",GetSQLValueString($_GET['r_id'], "int"));
	$get_resource = mysql_query($query_get_resource, $conn) or die(mysql_error());
	$row_get_resource = mysql_fetch_assoc($get_resource);
	$totalRows_get_resource= mysql_num_rows($get_resource);
	//redirect to QR API page to generate QR code
	//get current directory in $dir
	$url = $_SERVER['REQUEST_URI']; //returns the current URL
	$parts = explode('/',$url);
	$dir = $_SERVER['SERVER_NAME'];
	for ($i = 0; $i < count($parts) - 1; $i++) {
		 $dir .= $parts[$i] . "/";
	}
	$redirect=urlencode('http://'.$dir.'index.php?mode=qr&viewId='.$row_get_resource['r_id']);
	 //header( 'Location: https://api.qrserver.com/v1/create-qr-code/?size=150x150&data='.$redirect) ;
	echo '<a href="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data='.$redirect.'">';
	echo '<img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data='.$redirect.'"/>';
	echo '</a>';
}
?>
</body>
</html>