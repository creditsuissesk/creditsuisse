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
$MM_authorizedUsers = "admin";
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

$MM_restrictGoTo = "userhome.php";
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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE `user` SET approve_id=%s WHERE u_id=%s",
                       GetSQLValueString($_POST['app_id'], "int"),
                       GetSQLValueString($_POST['update_q'], "int"));

  mysql_select_db($database_conn, $conn);
  $Result1 = mysql_query($updateSQL, $conn) or die(mysql_error());

  $updateGoTo = "admin_home.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}
$colname_update = "-1";
if (isset($_SERVER['0'])) {
  $colname_update = $_SERVER['0'];
}
mysql_select_db($database_conn, $conn);
$query_update = sprintf("SELECT * FROM `user` natural join `approve_user` WHERE approve_id =0 ORDER BY u_id ASC");
$update = mysql_query($query_update, $conn) or die(mysql_error());
$row_update = mysql_fetch_assoc($update);
$totalRows_update = mysql_num_rows($update);

 /* Existing users ka query*/
$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_change"])) && ($_POST["MM_change"] == "form2")) {

  $updateSQL = sprintf("UPDATE `user` SET approve_id=%s WHERE u_id=%s",
                       GetSQLValueString($_POST['approve_id'], "int"),
                       GetSQLValueString($_POST['change_q'], "int"));

  mysql_select_db($database_conn, $conn);
  $Result1 = mysql_query($updateSQL, $conn) or die(mysql_error());

  $updateGoTo = "admin_home.php?showTab=1";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));

}
mysql_select_db($database_conn, $conn);
$query_all_users = "SELECT * FROM `user` natural join `approve_user` WHERE approve_id between 1 and 3 ORDER BY u_id ASC ";
$all_users = mysql_query($query_all_users, $conn) or die(mysql_error());
$row_all_users = mysql_fetch_assoc($all_users);
$totalRows_all_users = mysql_num_rows($all_users);






/*Existing users ka end of query*/


?>




<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Admin Home</title>
<script src="SpryAssets/SpryTabbedPanels.js" type="text/javascript"></script>
<link href="SpryAssets/SpryTabbedPanels.css" rel="stylesheet" type="text/css">
<link href="css/templatemo_style.css" type="text/css" rel="stylesheet" /> 
<script type="text/JavaScript" src="js/slimbox2.js"></script>
<script language="javascript" type="text/javascript">
function clearText(field)
{
    if (field.defaultValue == field.value) field.value = '';
    else if (field.value == '') field.value = field.defaultValue;
}
</script>

<script type="text/javascript" src="js/jquery.min.js"></script> 
<script type="text/javascript" src="js/jquery.scrollTo-min.js"></script> 
<script type="text/javascript" src="js/jquery.localscroll-min.js"></script> 
<script type="text/javascript" src="js/init.js"></script>  
<link rel="stylesheet" href="css/slimbox2.css" type="text/css" media="screen" />
<style type="text/css">
body,td,th {
	color: #000000;
	font-size: 14px;
}
</style>
<link href="SpryAssets/SpryRating.css" rel="stylesheet" type="text/css">
<link href="css/table.css" rel="stylesheet" type="text/css" />
<script src="SpryAssets/SpryRating.js" type="text/javascript"></script>
 

<body>
<?php 
if (isset($_GET['showTab'])) {
	if($_GET['showTab']<2) {
		$tabToShow=$_GET['showTab'];
	}
}
else {
	$tabToShow=0;
}
?>
<p>User's home </p>
<div id="TabbedPanels1" class="TabbedPanels">
  <ul class="TabbedPanelsTabGroup">
    <li class="TabbedPanelsTab" tabindex="0">New Users</li>
    <li class="TabbedPanelsTab" tabindex="0">Existing Users</li>
  </ul>
  <div class="TabbedPanelsContentGroup">
    <div class="TabbedPanelsContent">
    <!--- if no new users then skip whole table--->
    <?php if ($totalRows_update>0 ) { ?>
        <div id="curr_courses">
    <div class="datagrid">
    <table>
    <thead>
  <tr>
    <td>User Id</td>
    <td>User Name</td>
    <td>Password</td>
    <td>First Name</td>
    <td>Last Name</td>
    <td>Contact No</td>
    <td>Date of Birth</td>
    <td>Institute</td>
    <td>Stream</td>
    <td>Role</td>
    <td>Final Status</td>
    <td>    </td>
  </tr>
  </thead>
    <a href="<?php echo $logoutAction ?>">
  </a>
  <?php do { ?>
    
  
    <tr>
      <td><?php echo $row_update['u_id']; ?></td>
      <td><?php echo $row_update['u_name']; ?></td>
      <td><?php echo $row_update['password']; ?></td>
      <td><?php echo $row_update['f_name']; ?></td>
      <td><?php echo $row_update['l_name']; ?></td>
      <td><?php echo $row_update['contact_no']; ?></td>
      <td><?php echo $row_update['dob']; ?></td>
      <td><?php echo $row_update['institute']; ?></td>
      <td><?php echo $row_update['stream']; ?></td>
      <td><?php echo $row_update['role']; ?></td>
<form  id="form1" name="form1" method="POST" action="<?php echo $editFormAction; ?>">      <td><select name="app_id" id="app_id">
        <option value="0">  </option>
        <option value="1">Approved</option>
        <option value="2">Blocked</option>
        <option value="3">Rejected</option>
      </select></td>
      <td> 
      <input name="update" id="update" value="update" type="submit" ></input> 
        <input type="hidden" name="MM_update" value="form1" />
        <input type="hidden" id="update_q" name="update_q" value="<?php echo $row_update['u_id']?>" />
        </td></form>
    </tr>
    <?php } while ($row_update = mysql_fetch_assoc($update)); ?>
    </table>
    </div></div>
    <?php } else {
		echo "No new users";
	} ?>
    </div>
    <!--start of existing users' tab content--><div class="TabbedPanelsContent">
    <?php if ($totalRows_all_users>0 ) { ?>
        <div id="existing_users">
    <div class="datagrid">
    <table>
    <thead>
  <tr>
    <td>User Id</td>
    <td>User Name</td>
    <td>Password</td>
    <td>First Name</td>
    <td>Last Name</td>
    <td>Contact No</td>
    <td>Date of Birth</td>
    <td>Institute</td>
    <td>Stream</td>
    <td>Role</td>
    <td>Current Status</td>
    <td>Final Status</td>
    <td>    </td>
  </tr>
  </thead>
  <a href="<?php echo $logoutAction ?>">
  </a>
  <?php do { ?>
    
  
    <tr>
      <td><?php echo $row_all_users['u_id']; ?></td>
      <td><?php echo $row_all_users['u_name']; ?></td>
      <td><?php echo $row_all_users['password']; ?></td>
      <td><?php echo $row_all_users['f_name']; ?></td>
      <td><?php echo $row_all_users['l_name']; ?></td>
      <td><?php echo $row_all_users['contact_no']; ?></td>
      <td><?php echo $row_all_users['dob']; ?></td>
      <td><?php echo $row_all_users['institute']; ?></td>
      <td><?php echo $row_all_users['stream']; ?></td>
      <td><?php echo $row_all_users['role']; ?></td>
      <td><?php echo $row_all_users['app_stat']; ?></td>
<form  id="form2" name="form2" method="POST" action="<?php echo $editFormAction; ?>">      <td><select name="approve_id" id="approve_id">
        <option value="0">  </option>
        <option value="1">Approved</option>
        <option value="2">Blocked</option>
      </select></td>
      <td> 
  <input name="change" id="change" value="Change" type="submit" ></input> 
        <input type="hidden" name="MM_change" value="form2" />
        <input type="hidden" id="change_q" name="change_q" value="<?php echo $row_all_users['u_id']?>" />
        </td></form>
    </tr>
    <?php } while ($row_all_users = mysql_fetch_assoc($all_users)); ?>
    </table>
    </div></div>
    <?php } else {
		echo "No Existing users";
	} ?>
    
    </div><!--end of existing users' tab content-->
  </div>
</div>
<a href="<?php echo $logoutAction ?>">Log out</a>
<script type="text/javascript">
var TabbedPanels1 = new Spry.Widget.TabbedPanels("TabbedPanels1",{defaultTab:<?php echo $tabToShow;?>});
</script>
</body>
</html>
<?php
mysql_free_result($all_users);

mysql_free_result($update);
?>