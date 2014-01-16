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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "new_course")) {
  $insertSQL = sprintf("INSERT INTO course (c_name, c_stream, start_date, end_date,description) VALUES (%s, %s, %s, %s,%s)",
                       GetSQLValueString($_POST['c_name'], "text"),
                       GetSQLValueString($_POST['c_stream'], "text"),
                       GetSQLValueString($_POST['start_date'], "date"),
                       GetSQLValueString($_POST['end_date'], "date"),GetSQLValueString($_POST['desc'], "text"));

  mysql_select_db($database_conn, $conn);
  $Result1 = mysql_query($insertSQL, $conn) or die(mysql_error());

  $insertGoTo = "authorhome.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

mysql_select_db($database_conn, $conn);
$query_all_courses = sprintf("SELECT c_id,c_name,c_stream,start_date,end_date,avg_rating FROM course NATURAL JOIN create_course WHERE u_id=%s",GetSQLValueString($_SESSION['MM_UserID'], "int"));
$all_courses = mysql_query($query_all_courses, $conn) or die(mysql_error());
$row_all_courses = mysql_fetch_assoc($all_courses);
$totalRows_all_courses = mysql_num_rows($all_courses);

mysql_select_db($database_conn, $conn);
$query_current_courses = sprintf("SELECT c_id,c_name,c_stream,start_date,end_date,avg_rating FROM course NATURAL JOIN create_course WHERE u_id=%s AND start_date<=DATE(NOW()) AND end_date>=DATE(NOW()) ORDER BY start_date ASC",GetSQLValueString($_SESSION['MM_UserID'], "int"));
$current_courses = mysql_query($query_current_courses, $conn) or die(mysql_error());
$row_current_courses = mysql_fetch_assoc($current_courses);
$totalRows_current_courses = mysql_num_rows($current_courses);

$colname_get_cid = "c_name";
if (isset($_POST['c_name'])) {
  $colname_get_cid = $_POST['c_name'];
}
mysql_select_db($database_conn, $conn);
$query_get_cid = sprintf("SELECT c_id FROM course WHERE c_name = %s", GetSQLValueString($colname_get_cid, "text"));

$get_cid = mysql_query($query_get_cid, $conn) or die(mysql_error());
$row_get_cid = mysql_fetch_assoc($get_cid);
$totalRows_get_cid = mysql_num_rows($get_cid);

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "new_course")) {
  $insertSQL2 = sprintf("INSERT INTO create_course (u_id,c_id) VALUES (%s,%s)",GetSQLValueString($_SESSION['MM_UserID'], "int"),GetSQLValueString($row_get_cid['c_id'], "int")
                       );

  mysql_select_db($database_conn, $conn);
  $Result1 = mysql_query($insertSQL2, $conn) or die(mysql_error());
}



?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Author's Home</title>
<script src="SpryAssets/SpryTabbedPanels.js" type="text/javascript"></script>
<link href="SpryAssets/SpryTabbedPanels.css" rel="stylesheet" type="text/css" />

<!---
script for list.js tables
--->
<script src="list.js"></script>
<script src="jquery.min.js"></script>
<!--
script for calendar
--->
<link rel="stylesheet" type="text/css" media="all" href="jsDatePick_ltr.min.css" />
<link href="SpryAssets/SpryValidationPassword.css" rel="stylesheet" type="text/css" />
<link href="SpryAssets/SpryValidationConfirm.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="jsDatePick.min.1.3.js"></script>
<script type="text/javascript">
window.onload = function(){
		new JsDatePick({
			useMode:2,
			target:"start_date",
			dateFormat:"%Y-%m-%d"
		});
		new JsDatePick({
			useMode:2,
			target:"end_date",
			dateFormat:"%Y-%m-%d"
		});
	};
function MM_validateForm() { //v4.0
  if (document.getElementById){
    var i,p,q,nm,test,num,min,max,errors='',args=MM_validateForm.arguments;
    for (i=0; i<(args.length-2); i+=3) { test=args[i+2]; val=document.getElementById(args[i]);
      if (val) { nm=val.name; if ((val=val.value)!="") {
        if (test.indexOf('isEmail')!=-1) { p=val.indexOf('@');
          if (p<1 || p==(val.length-1)) errors+='- '+nm+' must contain an e-mail address.\n';
        } else if (test!='R') { num = parseFloat(val);
          if (isNaN(val)) errors+='- '+nm+' must contain a number.\n';
          if (test.indexOf('inRange') != -1) { p=test.indexOf(':');
            min=test.substring(8,p); max=test.substring(p+1);
            if (num<min || max<num) errors+='- '+nm+' must contain a number between '+min+' and '+max+'.\n';
      } } } else if (test.charAt(0) == 'R') errors += '- '+nm+' is required.\n'; }
    } if (errors) alert('The following error(s) occurred:\n'+errors);
    document.MM_returnValue = (errors == '');
} }
</script>
</head>

<body alink="#D6D6D6">
<p>Author's home</p>
<div id="TabbedPanels1" class="TabbedPanels">
  <ul class="TabbedPanelsTabGroup">
    <li class="TabbedPanelsTab" tabindex="0">Create Course</li>
    <li class="TabbedPanelsTab" tabindex="0">Current Courses</li>
    <li class="TabbedPanelsTab" tabindex="0">All Courses</li>
  </ul>
  <div class="TabbedPanelsContentGroup">
    <div class="TabbedPanelsContent">
      <p>Please enter the course details : </p>
      <form id="form1" name="new_course" method="POST" action="<?php echo $editFormAction; ?>">
        <p>
          <label for="c_name">Course Name* :</label>
          <input type="text" name="c_name" id="c_name" />
          <label for="start_date">Start Date* : </label>
          <input name="start_date" type="text" id="start_date" readonly="readonly" />
        </p>
        <p>
          <label for="c_stream">Course Stream* : </label>
          <input type="text" name="c_stream" id="c_stream" />
          <label for="end_date">End Date* : </label>
          <input name="end_date" type="text" id="end_date" readonly="readonly" />
        </p>
        <p>
          <label for="desc">Course Description* :</label>
          <textarea name="desc" id="desc" cols="45" rows="5"></textarea>
        </p>
        <p>
          <input name="submit" type="submit" id="submit" onclick="MM_validateForm('c_name','','R','start_date','','R','c_stream','','R','end_date','','R','desc','','R');return document.MM_returnValue" value="Submit" />
          <input type="reset" name="reset" id="reset" value="Reset" />
          <?php require_once('Connections/conn.php'); ?>

        </p>
        <p>&nbsp;</p>
        <p>&nbsp;</p>
        <p>&nbsp;</p>
        <p>
          <label for="uid"></label>
        </p>
        <p>&nbsp;</p>
        <p>
          <input type="hidden" name="MM_insert" value="new_course" />
        </p>
      </form>
    </div>	<!---ends tab 1--->
    <div class="TabbedPanelsContent">
    <table class="sortable" width="100%" border="1">
      <tr>
        <th scope="col">Course Name</th>
        <th scope="col">Stream</th>
        <th scope="col">Start Date</th>
        <th scope="col">End Date</th>
        <th scope="col">Average Rating</th>
      </tr>
     <?php do { ?>
    <tr>
      <td><a href="course_detail.php?c_id=<?php echo $row_current_courses['c_id']; ?>"><?php echo $row_current_courses['c_name']; ?></a></td>
      <td><?php echo $row_current_courses['c_stream']; ?></td>
      <td><?php echo $row_current_courses['start_date']; ?></td>
      <td><?php echo $row_current_courses['end_date']; ?></td>
      <td><?php echo $row_current_courses['avg_rating']; ?></td>
        </tr>
    <?php } while ($row_current_courses = mysql_fetch_assoc($current_courses)); ?>
    </table>
    </div> <!---end of second tab--->
    <div class="TabbedPanelsContent">
    <table width="100%" class="sortable" border="1">
      <tr>
        <th scope="col">Course Name</th>
        <th scope="col">Stream</th>
        <th scope="col">Start Date</th>
        <th scope="col">End Date</th>
        <th scope="col">Average Rating</th>
      </tr>

        <?php do { ?>
              <tr>
      <td><a href="course_detail.php?c_id=<?php echo $row_all_courses['c_id']; ?>"><?php echo $row_all_courses['c_name']; ?></a></td>
      <td><?php echo $row_all_courses['c_stream']; ?></td>
      <td><?php echo $row_all_courses['start_date']; ?></td>
      <td><?php echo $row_all_courses['end_date']; ?></td>
      <td><?php echo $row_all_courses['avg_rating']; ?></td>
          </tr>
    <?php } while ($row_all_courses = mysql_fetch_assoc($all_courses)); ?>
    </table>
    </div>

  </div>
</div>
<br />
<a href="<?php echo $logoutAction ?>">Log out</a>
<script type="text/javascript">
var TabbedPanels1 = new Spry.Widget.TabbedPanels("TabbedPanels1");
</script>
</body>
</html>
<?php
mysql_free_result($all_courses);

mysql_free_result($current_courses);

mysql_free_result($get_cid);
?>
