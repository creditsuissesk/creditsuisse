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
<?php
// *** Validate request to login to this site.
if (!isset($_SESSION)) {
  session_start();
}

$loginFormAction = $_SERVER['PHP_SELF'];
if (isset($_GET['accesscheck'])) {
  $_SESSION['PrevUrl'] = $_GET['accesscheck'];
}

if (isset($_POST['username'])) {
  $loginUsername=$_POST['username'];
  $password=$_POST['pass'];
  $MM_fldUserAuthorization = "role";
  $MM_redirectLoginSuccessUser = "userhome.php";
  $MM_redirectLoginSuccessAuthor = "authorhome.php";
  $MM_redirectLoginSuccessRoot = "admin_home.php";
  $MM_redirectLoginSuccessCM = "cmhome.php";
  $MM_redirectLoginFailed = "index.php#home";
  $MM_redirecttoReferrer = false;
  mysql_select_db($database_conn, $conn);
  	
  $LoginRS__query=sprintf("SELECT u_id,f_name, u_name, password, role,stream FROM `user` WHERE u_name=%s AND password=%s AND approve_id=1",GetSQLValueString($loginUsername, "text"), GetSQLValueString($password, "text")); 
  $LoginRS = mysql_query($LoginRS__query, $conn) or die(mysql_error());
  $loginFoundUser = mysql_num_rows($LoginRS);
  if ($loginFoundUser) {
    
    $loginStrGroup  = mysql_result($LoginRS,0,'role');
    
	if (PHP_VERSION >= 5.1) {session_regenerate_id(true);} else {session_regenerate_id();}
    //declare two session variables and assign them
    $_SESSION['MM_Username'] = $loginUsername;
    $_SESSION['MM_UserGroup'] = $loginStrGroup;
	$_SESSION['MM_UserID'] = mysql_result($LoginRS,0,'u_id');
	$_SESSION['MM_f_name'] = mysql_result($LoginRS,0,'f_name');
	$_SESSION['MM_stream'] = mysql_result($LoginRS,0,'stream');
    if (isset($_SESSION['PrevUrl']) && false) {
      $MM_redirectLoginSuccess = $_SESSION['PrevUrl'];	
    }
	if ($_SESSION['MM_UserGroup'] == 'admin') {
	  $redirectid=$MM_redirectLoginSuccessRoot;
	} elseif ($_SESSION['MM_UserGroup'] == 'student') {
	  $redirectid=$MM_redirectLoginSuccessUser;
	} elseif ($_SESSION['MM_UserGroup'] == 'author') {
	  $redirectid=$MM_redirectLoginSuccessAuthor;
	}elseif ($_SESSION['MM_UserGroup'] == 'cm') {
	  $redirectid=$MM_redirectLoginSuccessCM;
	}
	$flag=1;
    //header("Location: " . $MM_redirectLoginSuccess );
  }
  else {
	       $flag=0;
		   $redirectid=$MM_redirectLoginFailed;
  }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php 
mysql_select_db($database_conn, $conn);
  $error__query=sprintf("SELECT approve_id FROM `user` WHERE u_name=%s AND password=%s ",GetSQLValueString($loginUsername, "text"), GetSQLValueString($password, "text")); 
  $error = mysql_query($error__query, $conn) or die(mysql_error());
  $row_error = mysql_fetch_assoc($error);
	  if($row_error['approve_id']==2 && $flag==1)
	  		{ echo '<script type="text/javascript">alert("Administrator has rejected you to join the website"); window.location="'.$redirectid.'";</script>';}
else if($row_error['approve_id']==0 && $flag==1)
	  { echo '<script type="text/javascript">alert("Administrator is yet to approve you to join the website"); window.location="'.$redirectid.'";</script>';}
else  if($row_error['approve_id']==1&& $flag==1)
{echo '<script type="text/javascript">window.location="'.$redirectid.'";</script>';}
	else  { echo '<script type="text/javascript">alert("Either Username or Password is wrong if you are a approved user or Sign up for website"); window.location="'.$redirectid.'";</script>';}

?>
<title>Login</title>
</head>

<body>

</body>
</html>
