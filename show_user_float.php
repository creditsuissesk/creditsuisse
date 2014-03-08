<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="css/user_float.css?123" type="text/css" rel="stylesheet" />
</head>
<?php
//this page generates user floating layout.
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

$MM_restrictGoTo = "index.php#home";
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
if(isset($_GET['u_id'])) {
	$query_get_user= sprintf("SELECT * FROM `user` WHERE u_id=%s",GetSQLValueString($_GET['u_id'], "int"));
	$get_user = mysql_query($query_get_user, $conn) or die(mysql_error());
	$row_get_user = mysql_fetch_assoc($get_user);
	$totalRows_get_user= mysql_num_rows($get_user);
	if($totalRows_get_user==1) {
?>
<div id="templatemo_header_wrapper">
	<div id="templatemo_header">
    	<div id="site_title"><?php echo $row_get_user['f_name']." ".$row_get_user['l_name']; ?></div>
        <a class="templatemo_header_bg" href="" title="Creative commons beelden"  target="_blank"><img src="images/header.png" alt="Creative commons beelden" title="Creative commons beelden" /></a>
    </div>
</div>
<div class="role"><?php echo $row_get_user['role'];?> </div>
<div id="templatemo_main_wrapper">
	<div id="templatemo_main">
		<div id="content"> 
            <div class="section">
            <div class="half left">
				<div class="img_border img_user"> <img src="<?php echo $row_get_user['photo'] ?>" width="120" height="150" alt="No author image available" /></div>   
            </div>
            <div class="half right">
            <div class="info">
			Stream : <em> <?php echo $row_get_user['stream']; ?></em><br /><br />
            Degree : <em> <?php echo $row_get_user['degree']; ?></em><br /><br />
            Institute : <em> <?php echo $row_get_user['institute']; ?></em><br /><br />
            Score : <em> <?php echo $row_get_user['user_score']; ?></em><br /><br />
            </div>
            </div>
            </div>
        </div>
    </div>
</div>
<div class="about">
About :  <em> <?php echo $row_get_user['about']; ?></em>
</div>

<?php	
	}
	else {
		echo "Sorry, this user doesn't exist.";
	}
}
?>
</body>
</html>