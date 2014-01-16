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
</head>

<body>
<style>
.list {
  font-family:sans-serif;
  margin:0;
  padding:20px 0 0;
}
.list > li {
  display:block;
  background-color: #eee;
  padding:10px;
  box-shadow: inset 0 1px 0 #fff;
}
.avatar {
  max-width: 150px;
}
img {
  max-width: 100%;
}
h3 {
  font-size: 16px;
  margin:0 0 0.3rem;
  font-weight: normal;
  font-weight:bold;
}
p {
  margin:0;
}

input {
  border:solid 1px #ccc;
  border-radius: 5px;
  padding:7px 14px;
  margin-bottom:10px
}
input:focus {
  outline:none;
  border-color:#aaa;
}
.sort {
  padding:8px 30px;
  border-radius: 6px;
  border:none;
  display:inline-block;
  color:#fff;
  text-decoration: none;
  background-color: #28a8e0;
  height:30px;
}
.sort:hover {
  text-decoration: none;
  background-color:#1b8aba;
}
.sort:focus {
  outline:none;
}
.sort:after {
  width: 0;
  height: 0;
  border-left: 5px solid transparent;
  border-right: 5px solid transparent;
  border-bottom: 5px solid transparent;
  content:"";
  position: relative;
  top:-10px;
  right:-5px;
}
.sort.asc:after {
  width: 0;
  height: 0;
  border-left: 5px solid transparent;
  border-right: 5px solid transparent;
  border-top: 5px solid #fff;
  content:"";
  position: relative;
  top:13px;
  right:-5px;
}
.sort.desc:after {
  width: 0;
  height: 0;
  border-left: 5px solid transparent;
  border-right: 5px solid transparent;
  border-bottom: 5px solid #fff;
  content:"";
  position: relative;
  top:-10px;
  right:-5px;
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
    <input class="search" placeholder="Search" />
    <button class="sort" data-sort="name">
      Sort by name
    </button>
  
    <ul class="list">
     <?php do { ?>
      <li>
        <h3 class="name"><?php echo $row_course_students['f_name'];?><?php echo $row_course_students['l_name'];?></h3>
        <p class="contact"><?php echo $row_course_students['contact_no'];?></p>
        <p class="email"><?php echo $row_course_students['u_name'];?></p>
        <p class="dob"><?php echo $row_course_students['dob'];?></p>
        <p class="institute"><?php echo $row_course_students['institute'];?></p>
        <p class="stream"><?php echo $row_course_students['stream'];?></p>
      </li>
          <?php } while ($row_course_students = mysql_fetch_assoc($course_students)); ?>
    </ul>
  </div>
   
      <script>
var options = {
  valueNames: [ 'name', 'contact','email','dob','institute','stream' ]
};

var userList = new List('users', options);
</script>
      <p>&nbsp;</p>
    </div>
    <div class="TabbedPanelsContent">
    <script type="text/javascript" src="upclick.js"></script>
      <form id="form1" name="form1" method="post" action="">
        <p>File Type :
</p>
        <p>
          <label>
            <input type="radio" name="resourse_group" value="radio" id="resourse_group_0" />
            Book</label>
          <br />
          <label>
            <input type="radio" name="resourse_group" value="radio" id="resourse_group_1" />
            Video Lecture</label>
          <br />
          <label>
            <input type="radio" name="resourse_group" value="radio" id="resourse_group_2" />
            Slides</label>
          <br />
          <label>
            <input type="radio" name="resourse_group" value="radio" id="resourse_group_3" />
            Research Paper</label>
          <br />
          <label>
            <input type="radio" name="resourse_group" value="radio" id="resourse_group_4" />
            Notes</label>
          <br />
          <label>
            <input type="radio" name="resourse_group" value="radio" id="resourse_group_5" />
            Others</label>
          <br />
        </p>
      </form>
      <p>
        <input type="button" id="uploader" value="Upload">
        <script type="text/javascript">

   var uploader = document.getElementById('uploader');

   upclick(
     {
      element: uploader,
      action: './uploader.php', 
      onstart:
        function(filename)
        {
          alert('Start upload: '+filename);
        },
      oncomplete:
        function(response_data) 
        {
          alert(response_data);
        }
     });

   </script>
        &nbsp;</p>
    </div>
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
