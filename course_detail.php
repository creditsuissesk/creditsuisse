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

$colname_course_details = "-1";
if (isset($_GET['c_id'])) {
  $colname_course_details = $_GET['c_id'];
}
mysql_select_db($database_conn, $conn);
$query_course_details = sprintf("SELECT * FROM course WHERE c_id = %s", GetSQLValueString($colname_course_details, "int"));
$course_details = mysql_query($query_course_details, $conn) or die(mysql_error());
$row_course_details = mysql_fetch_assoc($course_details);
$totalRows_course_details = mysql_num_rows($course_details);

mysql_select_db($database_conn, $conn);
$query_course_students = sprintf("SELECT * FROM `user` NATURAL JOIN `enroll_course` WHERE c_id=%s",GetSQLValueString($_GET['c_id'], "int"));
$course_students = mysql_query($query_course_students, $conn) or die(mysql_error());
$row_course_students = mysql_fetch_assoc($course_students);
$totalRows_course_students = mysql_num_rows($course_students);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $row_course_details['c_name'];?></title>
<script src="SpryAssets/SpryTabbedPanels.js" type="text/javascript"></script>
<script src="list.js"></script><meta charset=utf-8 />
<link href="SpryAssets/SpryTabbedPanels.css" rel="stylesheet" type="text/css" />
<script src="jquery.min.js"></script>
</head>

<body>
<style>
body {
  font-family:sans-serif;
}
table td, table th {
  padding:5px;`
}
</style>
<h1><?php echo $row_course_details['c_name']?></h1>
<div id="TabbedPanels1" class="TabbedPanels">
  <ul class="TabbedPanelsTabGroup">
    <li class="TabbedPanelsTab" tabindex="0">Description</li>
    <li class="TabbedPanelsTab" tabindex="0">Student List</li>
    <li class="TabbedPanelsTab" tabindex="0">Files</li>
</ul>
  <div class="TabbedPanelsContentGroup">
    <div class="TabbedPanelsContent">
      <p>&nbsp;<?php echo $row_course_details['description']; ?></p>
    </div>
    <div class="TabbedPanelsContent">
    <div id="users">
  <table>
    <thead>
      <tr>
        <th class="sort" data-sort="name">Name</th>
        <th class="sort" data-sort="contact">Contact</th>
        <th class="sort" data-sort="email">E-mail</th>
        <th class="sort" data-sort="dob">Date of Birth</th>
        <th class="sort" data-sort="institute">Institute</th>
        <th class="sort" data-sort="stream">Stream</th>
        <th colspan="2">
          <input type="text" class="search" placeholder="Search student" />
        </th>
      </tr>
    </thead>
    <tbody class="list">
    <?php do { ?>
      <tr>
        <td class="name"><?php echo $row_course_students['f_name'];?> <?php echo $row_course_students['l_name'];?></td>
        <td class="contact"><?php echo $row_course_students['contact_no'];?></td>
        <td class="email"><?php echo $row_course_students['u_name'];?></td>
        <td class="dob"><?php echo $row_course_students['dob'];?></td>
        <td class="institute"><?php echo $row_course_students['institute'];?></td>
                <td class="stream"><?php echo $row_course_students['stream'];?></td>
      </tr>
      <?php } while ($row_course_students = mysql_fetch_assoc($course_students)); ?>
      </tbody>
      </table>
  </div>
   <script>
var options = {
  valueNames: [ 'name', 'contact','email','dob','institute','stream' ]
};

// Init list
var userList = new List('users', options);
</script>
</div>
      <p>&nbsp;</p>
   </div>
</div>
<p><a href="authorhome.php">Back to Home</a></p>
<script type="text/javascript">
var TabbedPanels1 = new Spry.Widget.TabbedPanels("TabbedPanels1");
</script>
</body>
</html>
<?php
mysql_free_result($course_details);

mysql_free_result($course_students);
?>
