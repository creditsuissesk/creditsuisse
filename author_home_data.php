<?php require_once('Connections/conn.php'); ?>
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
$temp = explode(".", $_FILES["file"]["name"]);
$extension = end($temp);
if ((($_FILES["file"]["type"] == "image/gif")
|| ($_FILES["file"]["type"] == "image/jpeg")
|| ($_FILES["file"]["type"] == "image/jpg")
|| ($_FILES["file"]["type"] == "image/pjpeg")
|| ($_FILES["file"]["type"] == "image/x-png")
|| ($_FILES["file"]["type"] == "image/png"))
&& ($_FILES["file"]["size"] < 500000))
{
 if ($_FILES["file"]["error"] > 0)
    {
     echo '<script type="text/javascript">alert("File Error: '. $_FILES["file"]["error"] . ' ");window.location="http://localhost/dreamweaver/authorhome.php";</script>';
    }
  else
    {
   /* echo "Upload: " . $_FILES["file"]["name"] . "<br>";
    echo "Type: " . $_FILES["file"]["type"] . "<br>";
    echo "Size: " . ($_FILES["file"]["size"] / 1024) . " kB<br>";
    echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br>";
	*/
	$filename=$_POST['c_name'].'.'.$extension;
	$upload_add="images/course_picture/" . $filename;
    if (file_exists("images/course_picture/" . $filename))
      {
      echo  '<script type="text/javascript">alert("'. $filename . '  already exists. "); window.location="http://localhost/dreamweaver/authorhome.php";</script>';
      }
    else
      {
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "new_course")) {
  $insertSQL = sprintf("INSERT INTO course (c_name, c_stream, start_date, end_date,course_image,description) VALUES (%s, %s, %s, %s,%s,%s)",
                       GetSQLValueString($_POST['c_name'], "text"),
                       GetSQLValueString($_POST['c_stream'], "text"),
                       GetSQLValueString($_POST['start_date'], "date"),
                       GetSQLValueString($_POST['end_date'], "date"),
					   GetSQLValueString($upload_add, "text"),
					   GetSQLValueString($_POST['desc'], "text"));

  mysql_select_db($database_conn, $conn);
  $Result1 = mysql_query($insertSQL, $conn) or die(mysql_error());
}
$colname_get_cid = "c_name";
if (isset($_POST['c_name'])) {
  $colname_get_cid = $_POST['c_name'];
}
mysql_select_db($database_conn, $conn);
$query_get_cid = sprintf("SELECT c_id FROM course WHERE c_name = %s", GetSQLValueString($colname_get_cid, "text"));

$get_cid = mysql_query($query_get_cid, $conn) or die(mysql_error());
$row_get_cid = mysql_fetch_assoc($get_cid);
$totalRows_get_cid = mysql_num_rows($get_cid);

mysql_select_db($database_conn, $conn);
$query_get_auth_info = sprintf("SELECT * FROM user WHERE u_id = %s",GetSQLValueString($_SESSION['MM_UserID'],"int"));

/*$get_auth_info= mysql_query($query_get_auth_info, $conn) or die(mysql_error());
$row_get_auth_info = mysql_fetch_assoc($get_auth_info);
$totalRows_get_auth_info = mysql_num_rows($get_auth_info);*/

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "new_course")) {
  $insertSQL2 = sprintf("INSERT INTO create_course (u_id,c_id) VALUES (%s,%s)",GetSQLValueString($_SESSION['MM_UserID'], "int"),GetSQLValueString($row_get_cid['c_id'], "int")
                       );

  mysql_select_db($database_conn, $conn);
  $Result1 = mysql_query($insertSQL2, $conn) or die(mysql_error());
}
	   move_uploaded_file($_FILES["file"]["tmp_name"],
      $upload_add);
	   	echo '<script type="text/javascript">alert("File Succesfully Uploaded"); window.location="http://localhost/dreamweaver/authorhome.php"; </script>';
	  }
	}}
else
  {
  echo '<script type="text/javascript">alert("Invalid File. Please Create Course again");window.location="http://localhost/dreamweaver/authorhome.php";</script>';
  }
 mysql_free_result($get_cid);
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