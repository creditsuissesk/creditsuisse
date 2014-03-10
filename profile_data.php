<?php require_once('Connections/conn.php'); ?>
<?php /*php script for uploading pic */?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "cm,author,student,admin";
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
if($_SESSION['MM_UserGroup']=="cm")
{
	$redirect="cmhome.php";
}
else
{
	if($_SESSION['MM_UserGroup']=="author")
	$redirect="authorhome.php";
	else
	if($_SESSION['MM_UserGroup']=="admin")
	$redirect="admin_home.php";
	else
	$redirect="userhome.php";
}
//start profileform
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "profileform")) {
		$insertSQL = sprintf("UPDATE  `user` SET contact_no=%s,institute=%s,degree=%s,about=%s WHERE u_id=%s",
							 GetSQLValueString($_POST['contact'], "int"),
							 GetSQLValueString($_POST['inst_name'], "text"),
							 GetSQLValueString($_POST['degree'], "text"),
							 GetSQLValueString($_POST['about'], "text"),
							 GetSQLValueString($_POST["u_id"], "int"));
	  
		mysql_select_db($database_conn, $conn);
		$Result1 = mysql_query($insertSQL, $conn) or die(mysql_error());
		echo '<script type="text/javascript">alert("Profile Updated Successfully."); 	window.location="'.$redirect.'"; </script>';
}
else
{ 
//start of dp form
if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "dpform"))
{
if($_FILES["File"]["size"]==0 )
{
		echo '<script type="text/javascript">alert("Upload profile image"); window.location="'.$redirect.'"; </script>';

}
else
{
$allowedExts = array("gif", "jpeg", "jpg", "png");
$temp = explode(".", $_FILES["File"]["name"]);
$extension = end($temp);
if ((($_FILES["File"]["type"] == "image/gif")
|| ($_FILES["File"]["type"] == "image/jpeg")
|| ($_FILES["File"]["type"] == "image/jpg")
|| ($_FILES["File"]["type"] == "image/pjpeg")
|| ($_FILES["File"]["type"] == "image/x-png")
|| ($_FILES["File"]["type"] == "image/png"))
&& ($_FILES["File"]["size"] < 50000)
/*&& in_array($extension, $allowedExts)*/
)
  {
  if ($_FILES["File"]["error"] > 0)
    {
     echo '<script type="text/javascript">alert("File Error: '. $_FILES["File"]["error"] . ' ");window.location="'.$redirect.'";</script>';
    }
  else
    {
   /* echo "Upload: " . $_FILES["File"]["name"] . "<br>";
    echo "Type: " . $_FILES["File"]["type"] . "<br>";
    echo "Size: " . ($_FILES["File"]["size"] / 1024) . " kB<br>";
    echo "Temp File: " . $_FILES["File"]["tmp_name"] . "<br>";
	*/
	$max_size=50000;
	$max_size_mb=(50000/1024)/1024;
	$filesize=$_FILES["File"]["size"]/1024/1024;
	$filename=$_POST['u_id'].".".$extension;
	$upload_add="images/profiles/" . $filename;
    move_uploaded_file($_FILES["File"]["tmp_name"],$upload_add);
	echo '<script type="text/javascript">alert("Profile Picture Successfully."); 	window.location="'.$redirect.'"; </script>';
    }
	    }
else
  {
	  if (!(($_FILES["File"]["type"] == "image/gif")
|| ($_FILES["File"]["type"] == "image/jpeg")
|| ($_FILES["File"]["type"] == "image/jpg")
|| ($_FILES["File"]["type"] == "image/pjpeg")
|| ($_FILES["File"]["type"] == "image/x-png")
|| ($_FILES["File"]["type"] == "image/png")))
  echo '<script type="text/javascript">alert("Invalid File Type. Please upload valid file.");  window.location="'.$redirect.'";</script>';
  else 
	   if(($_FILES["File"]["size"] > $max_size))
  echo '<script type="text/javascript">alert("File Size is '.$filesize.' mb which GREATER than the allowed size. Allowed Size is '.$max_size_mb.' mb.");
  window.location="'.$redirect.'";</script>';
  else
  echo '<script type="text/javascript">alert("Problem in uploading file.Please upload again");window.location="'.$redirect.'";</script>';
  }
}
}
}
?>








<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Profile</title>
</head>

<body>
</body>
</html>