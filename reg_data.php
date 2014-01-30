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
// Define a destination
$targetFolder = 'images/profiles'; // Relative to the root

$verifyToken = md5('unique_salt' . $_POST['timestamp']);

if (!empty($_FILES) && $_POST['token'] == $verifyToken) {
	$tempFile = $_FILES['Filedata']['tmp_name'];
	$targetPath = $_SERVER['DOCUMENT_ROOT'] . $targetFolder;
	$targetFile = rtrim($targetPath,'/') . '/' . $_FILES['Filedata']['name'];
	
	// Validate the file type
	$fileTypes = array('jpg','jpeg','gif','png'); // File extensions
	$fileParts = pathinfo($_FILES['Filedata']['name']);
	
	if (in_array($fileParts['extension'],$fileTypes)) {
		move_uploaded_file($tempFile,$targetFile);
		echo '1';
	} else {
		echo 'Invalid file type.';
	}
}
	  
	  
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form")) {
		$insertSQL = sprintf("INSERT INTO `user` (u_name, password, f_name, l_name, contact_no, dob, institute, stream,role) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s)",
							 GetSQLValueString($_POST['u_name'], "text"),
							 GetSQLValueString($_POST['pass'], "text"),
							 GetSQLValueString($_POST['f_name'], "text"),
							 GetSQLValueString($_POST['l_name'], "text"),
							 GetSQLValueString($_POST['contact'], "int"),
							 GetSQLValueString($_POST['dob'], "date"),
							 GetSQLValueString($_POST['inst_name'], "text"),
							 GetSQLValueString($_POST['stream'], "text"),
							 GetSQLValueString($_POST['role'], "text"));
	  
		mysql_select_db($database_conn, $conn);
		$Result1 = mysql_query($insertSQL, $conn) or die(mysql_error());
	  
		$insertGoTo = "index.php";
		if (isset($_SERVER['QUERY_STRING'])) {
		  $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
		  $insertGoTo .= $_SERVER['QUERY_STRING'];
		}
		 /*header(sprintf("Location: %s", $insertGoTo)); */
	  }
?>
















<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
</body>
</html>