<?php require_once('Connections/conn.php'); ?>
<?php
//initialize the session
if (!isset($_SESSION)) {
  session_start();
}

// ** Logout the current user. **
$logoutAction = $_SERVER['PHP_SELF']."?doLogout=true";
if ((isset($_SERVER['QUERY_STRING'])) && ($_SERVER['QUERY_STRING'] != "")){
  $logoutAction .="&". htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_GET['doLogout'])) &&($_GET['doLogout']=="true")){
  //to fully log out a visitor we need to clear the session varialbles
  $_SESSION['MM_Username'] = NULL;
  $_SESSION['MM_UserGroup'] = NULL;
  $_SESSION['PrevUrl'] = NULL;
  unset($_SESSION['MM_Username']);
  unset($_SESSION['MM_UserGroup']);
  unset($_SESSION['PrevUrl']);
	
  $logoutGoTo = "index.php";
  if ($logoutGoTo) {
    header("Location: $logoutGoTo");
    exit;
  }
}
?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "author,cm";
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
if ((isset($_POST["MM_change"])) && ($_POST["MM_change"] == "form2")){
$query_resource = sprintf("SELECT * FROM resource where r_id=%s",GetSQLValueString($_POST['id'], "int"));
$new_resource = mysql_query($query_resource, $conn) or die(mysql_error());
$row_new_resource = mysql_fetch_assoc($new_resource);
$totalRows_new_resource = mysql_num_rows($new_resource);
}
?> 
<?php 
if($_SESSION['MM_UserGroup']=="cm")
{
	$approve_stat = 1;
	$redirect="cmhome.php";
}
else
{
	$approve_stat = 0;
	if($_SESSION['MM_UserGroup']=="author")
	$redirect="authorhome.php";
	else
	$redirect="userhome.php";
}
if($_FILES["file"]["size"]==0)
{
		echo '<script type="text/javascript">alert("Upload File to proceed further"); window.location="'.$redirect.'"; </script>';

}
else
{
$allowedExts = array("gif", "jpeg", "jpg", "png","pdf","mp4","doc","docx","pptx","ppt","txt");
$temp = explode(".", $_FILES["file"]["name"]);
$extension = end($temp);
$max_size=500000000;
$max_size_mb=(500000000/1024)/1024;
$filesize=$_FILES["file"]["size"]/1024/1024;
$filename=$_POST['r_name'] . "." . $extension;
if ((($_FILES["file"]["type"] == "image/gif")
|| ($_FILES["file"]["type"] == "image/jpeg")
|| ($_FILES["file"]["type"] == "image/jpg")
|| ($_FILES["file"]["type"] == "image/pjpeg")
|| ($_FILES["file"]["type"] == "image/x-png")
|| ($_FILES["file"]["type"] == "image/png")
|| ($_FILES["file"]["type"] == "application/pdf")
|| ($_FILES["file"]["type"] == "application/x-pdf")
||($_FILES["file"]["type"] == "video/mp4")
||($_FILES["file"]["type"] == "application/msword")
||($_FILES["file"]["type"] == "application/vnd.openxmlformats-officedocument.wordprocessingml.document")
||($_FILES["file"]["type"] == "application/vnd.ms-powerpoint")
||($_FILES["file"]["type"] == "application/vnd.openxmlformats-officedocument.presentationml.presentation")
||($_FILES["file"]["type"] == "text/plain"))
&& in_array($extension, $allowedExts)
&& 
($_FILES["file"]["size"] < $max_size)

)
  {
  if ($_FILES["file"]["error"] > 0)
    {
    echo '<script type="text/javascript">alert("File Error: '. $_FILES["file"]["error"] . ' ");window.location="'.$redirect.'";</script>';
    }
  else
    {
    /*echo "Upload: " . $_POST['r_name'] . "." . $extension . "<br>";
    echo "Type: " . $_FILES["file"]["type"] . "<br>";
    echo "Size: " . ($_FILES["file"]["size"] / 1024) . " kB<br>";
    echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br>";*/
	$upload_add="resource/".$_POST["co_name"]."/" . $filename;
	$path = "resource/".$_POST["co_name"];
	
if ( ! is_dir($path)) {
    mkdir($path,0777);
}
    
      
	  	  
if ((isset($_POST["MM_change"])) && ($_POST["MM_change"] == "form2")){
		if($totalRows_new_resource==1 && $row_new_resource['file_type']==$_FILES['file']['type'] ){
$insertSQL = sprintf("UPDATE resource SET file_size=%s, uploaded_by=%s, approve_status=%s, download_status=%s, uploaded_date=now() WHERE r_id=%s",
							 GetSQLValueString($filesize, "double"),
							 GetSQLValueString($_SESSION['MM_UserID'], "int"),
							 GetSQLValueString($approve_stat, "int"),
							 GetSQLValueString($_POST["download"], "int"),
							 GetSQLValueString($_POST["id"], "int")
							 );
	  
		mysql_select_db($database_conn, $conn);
		$Result1 = mysql_query($insertSQL, $conn) or die(mysql_error());
	  move_uploaded_file($_FILES["file"]["tmp_name"],$upload_add);
	  echo '<script type="text/javascript">alert("File Succesfully Uploaded"); window.location="'.$redirect.'"; </script>';
    
		}else
		echo '<script type="text/javascript">alert("Problem in the uploaded file."); window.location="'.$redirect.'"; </script>';
		}
else
  {
	  if (!(($_FILES["file"]["type"] == "image/gif")
|| ($_FILES["file"]["type"] == "image/jpeg")
|| ($_FILES["file"]["type"] == "image/jpg")
|| ($_FILES["file"]["type"] == "image/pjpeg")
|| ($_FILES["file"]["type"] == "image/x-png")
|| ($_FILES["file"]["type"] == "image/png")
|| ($_FILES["file"]["type"] == "application/pdf")
|| ($_FILES["file"]["type"] == "application/x-pdf")
||($_FILES["file"]["type"] == "video/mp4")
||($_FILES["file"]["type"] == "application/msword")
||($_FILES["file"]["type"] == "application/vnd.openxmlformats-officedocument.wordprocessingml.document")
||($_FILES["file"]["type"] == "application/vnd.ms-powerpoint")
||($_FILES["file"]["type"] == "application/vnd.openxmlformats-officedocument.presentationml.presentation")
||($_FILES["file"]["type"] == "text/plain")))
  echo '<script type="text/javascript">alert("Invalid File Type. Please upload valid file.");  window.location="'.$redirect.'";</script>';
  else 
  if(($_FILES["file"]["size"] > $max_size))
  echo '<script type="text/javascript">alert("File Size is '.$filesize.' mb which GREATER than the allowed size. Allowed Size is '.$max_size_mb.' mb.");
  window.location="'.$redirect.'";</script>';
  else
  echo '<script type="text/javascript">alert("Problem in uploading");window.location="'.$redirect.'";</script>';
  }
}
}}
/*$insertGoTo = "authorhome.php";
		if (isset($_SERVER['QUERY_STRING'])) {
		  $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
		  $insertGoTo .= $_SERVER['QUERY_STRING'];
		}
		 header(sprintf("Location: %s", $insertGoTo));*/
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