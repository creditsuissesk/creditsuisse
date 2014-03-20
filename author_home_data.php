<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "author";
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

$MM_restrictGoTo = "/dreamweaver/userhome.php";
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

?>
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
if($_SESSION['MM_UserGroup']=='cm')
{
$redirect_id="http://localhost/dreamweaver/cmhome.php";
$ap_stat=1;
}
else if($_SESSION['MM_UserGroup']=='author')
{
	$redirect_id="http://localhost/dreamweaver/authorhome.php";
    $ap_stat=0;
}
$temp = explode(".", $_FILES["file"]["name"]);
$extension = end($temp);
$size=1024*1024;
if ((($_FILES["file"]["type"] == "image/gif")
|| ($_FILES["file"]["type"] == "image/jpeg")
|| ($_FILES["file"]["type"] == "image/jpg")
|| ($_FILES["file"]["type"] == "image/pjpeg")
|| ($_FILES["file"]["type"] == "image/x-png")
|| ($_FILES["file"]["type"] == "image/png"))
&& ($_FILES["file"]["size"] < $size))
{
 if ($_FILES["file"]["error"] > 0)
    {
     echo '<script type="text/javascript">alert("File Error: '. $_FILES["file"]["error"] . ' ");window.location="'.$redirect_id.'";</script>';
    }
  else
    {
	$filename=$_POST['c_name'].'.'.$extension;
   	$upload_add="images/course_picture/" . $filename;
    if (file_exists("images/course_picture/" . $filename))
      {
      echo  '<script type="text/javascript">alert("'. $filename . '  already exists. "); window.location="'.$redirect_id.'";</script>';
      }
    else
      {
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "new_course")) {
  $insertSQL = sprintf("INSERT INTO course (c_name, c_stream, start_date, end_date,course_image,description,approve_status,u_id) VALUES (%s, %s, %s, %s,%s,%s,%s,%s)",
                       GetSQLValueString($_POST['c_name'], "text"),
                       GetSQLValueString($_POST['c_stream'], "text"),
                       GetSQLValueString($_POST['start_date'], "date"),
                       GetSQLValueString($_POST['end_date'], "date"),
					   GetSQLValueString($upload_add, "text"),
					   GetSQLValueString($_POST['desc'], "text"),   GetSQLValueString($ap_stat, "int"),GetSQLValueString($_SESSION['MM_UserID'], "int"));

  mysql_select_db($database_conn, $conn);
  $Result1 = mysql_query($insertSQL, $conn) or die(mysql_error());
	   move_uploaded_file($_FILES["file"]["tmp_name"],
      $upload_add);
	   	echo '<script type="text/javascript">alert("Course Succesfully Created"); window.location="'.$redirect_id.'"; </script>';
	}}}}
else
  {
  echo '<script type="text/javascript">alert("Upload Another file.File size might be greater than the allowed size. Allowed size is 1 MB. Please Create Course again");window.location="'.$redirect_id.'";</script>';
  }
  
  
  
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
</head>

<body>
</body>
</html>