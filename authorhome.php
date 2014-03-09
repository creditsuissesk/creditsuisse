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


mysql_select_db($database_conn, $conn);
$query_all_courses = sprintf("SELECT c_id,c_name,c_stream,start_date,end_date,avg_rating,approve_status FROM course WHERE u_id=%s ",GetSQLValueString($_SESSION['MM_UserID'], "int"));
$all_courses = mysql_query($query_all_courses, $conn) or die(mysql_error());
$row_all_courses = mysql_fetch_assoc($all_courses);
$totalRows_all_courses = mysql_num_rows($all_courses);

$query_resource = sprintf("SELECT c_id,c_name,c_stream,start_date,end_date,avg_rating FROM course where approve_status=1 and u_id=%s",GetSQLValueString($_SESSION['MM_UserID'], "int"));
$new_resource = mysql_query($query_resource, $conn) or die(mysql_error());
$row_new_resource = mysql_fetch_assoc($new_resource);
$totalRows_new_resource = mysql_num_rows($new_resource);



$query_resource_type = sprintf("SELECT * FROM resource_type");
$resource_type = mysql_query($query_resource_type, $conn) or die(mysql_error());
$row_resource_type = mysql_fetch_assoc($resource_type);
$totalRows_resource_type = mysql_num_rows($resource_type);


mysql_select_db($database_conn, $conn);
$query_current_courses = sprintf("SELECT c_id,c_name,c_stream,start_date,end_date,avg_rating FROM course WHERE u_id=%s AND start_date<=DATE(NOW()) AND end_date>=DATE(NOW()) AND approve_status=1 ORDER BY start_date ASC",GetSQLValueString($_SESSION['MM_UserID'], "int"));
$current_courses = mysql_query($query_current_courses, $conn) or die(mysql_error());
$row_current_courses = mysql_fetch_assoc($current_courses);
$totalRows_current_courses = mysql_num_rows($current_courses);


$query_approved_resources = sprintf("SELECT l_name,f_name,r_id,filename,file_type,file_size,uploaded_date,c_name,r.avg_rating as r_avg_rating, r.download_status,file_location
FROM resource AS r
JOIN course AS c ON r.c_id = c.c_id JOIN user as u on r.uploaded_by = u.u_id
WHERE (r.approve_status =1 and r.flag_status=0) and c.approve_status=1 and r.uploaded_by=%s",GetSQLValueString($_SESSION['MM_UserID'], "int"));
$approved_resources = mysql_query($query_approved_resources, $conn) or die(mysql_error());
$row_approved_resources = mysql_fetch_assoc($approved_resources);
$totalRows_approved_resources = mysql_num_rows($approved_resources);


$query_all_resources = sprintf("SELECT l_name,f_name,r_id,filename,file_type,file_size,uploaded_date,c_name,r.avg_rating as r_avg_rating, r.download_status,file_location,r.approve_status,r.flag_status
FROM resource AS r
JOIN course AS c ON r.c_id = c.c_id JOIN user as u on r.uploaded_by = u.u_id
WHERE   c.approve_status=1 and r.uploaded_by=%s",GetSQLValueString($_SESSION['MM_UserID'], "int"));
$all_resources = mysql_query($query_all_resources, $conn) or die(mysql_error());
$row_all_resources = mysql_fetch_assoc($all_resources);
$totalRows_all_resources = mysql_num_rows($all_resources);

mysql_select_db($database_conn, $conn);
$query_update = sprintf("SELECT u,c_id,c_name,f_name,l_name,user_score,a_stat,s_stream FROM( select distinct *,user.stream as s_stream,user.u_id as u from `user` natural join `enroll_course`)as a JOIN `course` as c ON a.c_enroll_id=c.c_id WHERE a.a_stat=0 AND c.u_id=%s ORDER BY a.u_id ASC ",GetSQLValueString($_SESSION['MM_UserID'], "int"));
$update = mysql_query($query_update, $conn) or die(mysql_error());
$row_update = mysql_fetch_assoc($update);
$totalRows_update = mysql_num_rows($update);

mysql_select_db($database_conn, $conn);
$query_all_students = sprintf("SELECT u,c_name,f_name,l_name,user_score,a_stat,s_stream,c_id FROM( select distinct *,user.stream as s_stream,user.u_id as u from `user` natural join `enroll_course`)as a JOIN `course` as c ON a.c_enroll_id=c.c_id WHERE a.a_stat between 1 and 3 AND c.u_id=%s ORDER BY a.u ASC ",GetSQLValueString($_SESSION['MM_UserID'], "int"));
$all_students = mysql_query($query_all_students, $conn) or die(mysql_error());
$row_all_students = mysql_fetch_assoc($all_students);
$totalRows_all_students = mysql_num_rows($all_students);


?>
<?php 
//start of php code for permitting new student
$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form10")) {
  $updateSQL = sprintf("UPDATE `enroll_course` SET a_stat=%s WHERE u_id=%s AND c_enroll_id=%s",
                       GetSQLValueString($_POST['app_id'], "int"),
                       GetSQLValueString($_POST['update_q'], "int"),
					   GetSQLValueString($_POST['update_c'], "int"));

  mysql_select_db($database_conn, $conn);
  $Result1 = mysql_query($updateSQL, $conn) or die(mysql_error());

  $updateGoTo = "authorhome.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

//end of php code for permitting new user

/* permitted students ka query*/
$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_change"])) && ($_POST["MM_change"] == "form9")) {

  $updateSQL = sprintf("UPDATE `enroll_course` SET a_stat=%s WHERE u_id=%s AND c_enroll_id=%s",
                       GetSQLValueString($_POST['approve_id'], "int"),
                       GetSQLValueString($_POST['change_q'], "int"),
					   GetSQLValueString($_POST['change_c'], "int"));

  mysql_select_db($database_conn, $conn);
  $Result1 = mysql_query($updateSQL, $conn) or die(mysql_error());

  $updateGoTo = "authorhome.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));

}
/*end of permitted ka  query*/

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Author's Home</title>
<script src="SpryAssets/SpryTabbedPanels.js" type="text/javascript"></script>
<link href="SpryAssets/SpryTabbedPanels.css" rel="stylesheet" type="text/css" />
<link href="css/templatemo_style.css?12" type="text/css" rel="stylesheet" />
<link href="css/table.css" type="text/css" rel="stylesheet" />
<link rel="stylesheet" type="text/css" media="screen" href="css/nav_bar.css" /> 
<!---
script for js/list.js tables
--->
<script src="js/list.js"></script>
<script src="js/jquery.min.js"></script>

<!--- script for resource viewing --->
<style>
	@import "css/LightFace.css";
</style>
<link rel="stylesheet" href="css/lightface.css" />
<script src="js/mootools.js"></script>
<script src="js/LightFace.js"></script>
<script src="js/LightFace.js"></script>
<script src="js/LightFace.IFrame.js"></script>
<script src="js/LightFace.Image.js"></script>
<script src="js/LightFace.Request.js"></script>

<!--
script for calendar
--->
<link rel="stylesheet" type="text/css" media="all" href="css/jsDatePick_ltr.min.css" />
<link href="SpryAssets/SpryValidationPassword.css" rel="stylesheet" type="text/css" />
<link href="SpryAssets/SpryValidationConfirm.css" rel="stylesheet" type="text/css" />
<style type="text/css">
body,td,th {
	color: #000000;
	font-size: 14px;
}
</style>
<script type="text/javascript" src="js/jsDatePick.min.1.3.js"></script>
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

function showResource(id,type,name) {
	//show dimensions based on content type
	var height_set,width_set;
	if(type=="pdf") {
		height_set=520;
		width_set=920;
	}else if (type=="image") {
		height_set=320;
		width_set=620;
	}
	light = new LightFace.IFrame({
		height:height_set,
		width:width_set,
		url: 'show_resource_float.php?actiontype=loadResource&r_id='+id,
		title: 'Resource : '+name
		}).addButton('Close', function() { light.close(); },true).open();		
}
</script>
</head>

<body onLoad="javascript:TabbedPanels1.showPanel(<?php echo $_COOKIE['index'];?>)" alink="#D6D6D6">

<nav id="headerbar">
	<ul id="headerbar">
		<li id="headerbar"><a href="authorhome.php">Home</a></li>
		<li id="headerbar"><a href="forum_new.php?mode=showmain">Forums</a></li>
		<li id="headerbar"><a href="#"><?php echo $_SESSION['MM_Username'];?></a>
			<ul id="headerbar">
				<li id="headerbar"><a href="authorhome.php">Profile</a></li>
				<li id="headerbar"><a href="<?php echo $logoutAction ?>">Log Out</a>
				</li>
			</ul>
		</li>
	</ul>
</nav>
<br/>


<h1>Hello, <?php echo $_SESSION['MM_f_name'];?></h1>
<div id="TabbedPanels1" class="TabbedPanels">
  <ul class="TabbedPanelsTabGroup">
    <li class="TabbedPanelsTab" tabindex="0">Create Course</li>
    <li class="TabbedPanelsTab" tabindex="0">Current Approved Courses</li>
    <li class="TabbedPanelsTab" tabindex="0">All Courses</li>
     <li class="TabbedPanelsTab" tabindex="0">Current Approved resources</li>
    <li class="TabbedPanelsTab" tabindex="0">All resources</li>
    <li class="TabbedPanelsTab" tabindex="0">Upload Resource</li>
    <li class="TabbedPanelsTab" tabindex="0">Permit Students</li>
      <li class="TabbedPanelsTab" tabindex="0">Permitted Students</li>
  </ul>
  <div class="TabbedPanelsContentGroup">
    <div class="TabbedPanelsContent">
      <p>Please enter the course details : </p>
      <form id="form1" name="new_course" method="POST" enctype="multipart/form-data" action="author_home_data.php">
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
        <p> <label for="file">Course Picture:</label>
<input type="file" name="file" id="file">
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
    
    <!-- start of second tab -->
    <div class="TabbedPanelsContent">
    <div id="curr_courses">
    <div class="datagrid">
     <table>
    <thead>
      <tr>
        <th class="sort" data-sort="currentname">Course Name</th>
        <th class="sort" data-sort="currentstream">Stream</th>
        <th class="sort" data-sort="currentstart">Start Date</th>
        <th class="sort" data-sort="currentend">End Date</th>
        <th class="sort" data-sort="currentrating">Average Rating</th>
        <th colspan="2">
          <input type="text" class="search" placeholder="Search course" />
        </th>
      </tr>
    </thead>
    <tbody class="list">
    <?php do { ?>
    <tr>
      <td class="currentname"><a href="course_detail.php?c_id=<?php echo $row_current_courses['c_id']; ?>"><?php echo $row_current_courses['c_name']; ?></a></td>
      <td class="currentstream"><?php echo $row_current_courses['c_stream']; ?></td>
      <td class="currentstart"><?php echo $row_current_courses['start_date']; ?></td>
      <td class="currentend"><?php echo $row_current_courses['end_date']; ?></td>
      <td class="currentrating"><?php echo $row_current_courses['avg_rating']; ?></td>
        </tr>
    <?php } while ($row_current_courses = mysql_fetch_assoc($current_courses)); ?>
      </tbody>
      </table>
    </div>
    </div>
    <script>
var currOptions = {
  valueNames: [ 'currentname', 'currentstream','currentstart','currentend','currentrating']
};

// Init list
var currList = new List('curr_courses', currOptions);
</script>
    </div> <!---end of second tab--->
    
    <div class="TabbedPanelsContent">
    <div id="all_courses">
    <div class="datagrid">
    <table>
    <thead>
      <tr>
        <th class="sort" data-sort="allname">Course Name</th>
        <th class="sort" data-sort="allstream">Stream</th>
        <th class="sort" data-sort="allstart">Start Date</th>
        <th class="sort" data-sort="allend">End Date</th>
        <th class="sort" data-sort="allrating">Average Rating</th>
        <th class="sort" data-sort="app_status">Approved Status</th>
        <th colspan="2">
          <input type="text" class="search" placeholder="Search course" />
        </th>
      </tr>
    </thead>
    <tbody class="list">
     <?php do { ?>
              <tr>
      <td class="allname"><a href="course_detail.php?c_id=<?php echo $row_all_courses['c_id']; ?>"><?php echo $row_all_courses['c_name']; ?></a></td>
      <td class="allstream"><?php echo $row_all_courses['c_stream']; ?></td>
      <td class="allstart"><?php echo $row_all_courses['start_date']; ?></td>
      <td class="allend"><?php echo $row_all_courses['end_date']; ?></td>
      <td class="allrating"><?php echo $row_all_courses['avg_rating']; ?></td>
      <td class="app_status"><?php if ($row_all_courses['approve_status']==0) echo "Course waiting for approval"; else if ($row_all_courses['approve_status']==1) echo "Course Approved"; else if ($row_all_courses['approve_status']==2) echo "Course is rejected by the Administrator"; ?>
          </tr>
    <?php } while ($row_all_courses = mysql_fetch_assoc($all_courses)); ?>
      </tbody>
      </table>
      </div> 
    </div>

    <script>
var allOptions = {
  valueNames: [ 'allname', 'allstream','allstart','allend','allrating','app_status' ]
};

// Init list
var allList = new List('all_courses', allOptions);
</script>
    </div>
    <!-- End of all courses tab -->
    
    <!-- start of approved resource tab -->
    <div class="TabbedPanelsContent">
     <?php if($totalRows_approved_resources>0){?>
    <div id="approved_res">
    <div class="datagrid">
   
     <table>
    <thead>
      <tr>
        <th class="sort" data-sort="r_name">Resource Name</th>
        <th class="sort" data-sort="c_name">Course Name</th>
        <th class="sort" data-sort="r_size">Size in Mb</th>
        <th class="sort" data-sort="r_type">Type</th>
        <th class="sort" data-sort="author">Uploaded By</th>
        <th class="sort" data-sort="date">Uploaded Date </th>
        <th class="sort" data-sort="rate">Average rating</th>
        <th></th>
        <th colspan="2">
          <input type="text" class="search" placeholder="Search Resource" />
        </th>
      </tr>
    </thead>
    <tbody class="list">
    <?php do { ?>
    <tr>
      <td class="r_name"><a href='<?php echo $row_approved_resources["file_location"]?>'><?php echo $row_approved_resources['filename']; ?></a></td>
      <td class="c_name"><?php echo $row_approved_resources['c_name']; ?></td>
      <td class="r_size"><?php echo $row_approved_resources['file_size']; ?></td>
      <td class="r_type"><?php echo $row_approved_resources['file_type']; ?></td>
      <td class="author"><?php echo $row_approved_resources['f_name']." ".$row_approved_resources['l_name']; ?></td>
            <td class="date"><?php echo $row_approved_resources['uploaded_date']; ?></td>
                  <td class="rate"><?php echo $row_approved_resources['r_avg_rating']; ?></td>
                  <?php if($row_approved_resources['download_status']==1){?>
        <form  id="form1" name="form1" method="POST" action="download_res.php">    
        
        <td> 
        <input name="change" id="change" value="Download" type="submit" >        
        </input>
        <input type="hidden" name="id" id="id" value="<?php echo $row_approved_resources['r_id'];?>"  />
        
        <input type="hidden" name="MM_change" value="form1" />
        </td>
        </form>
        <?php }else echo "<td> </td>";?>
    <?php } while ($row_approved_resources = mysql_fetch_assoc($approved_resources));?>
      </tr>
      </tbody>
      </table>
      
    </div>
    </div>
    <?php }else echo"No approved resources"; ?>
    <script>
var arOptions = {
  valueNames: [ 'r_name', 'c_name','r_size','r_type','author','date','rate']
};

// Init list
var arList = new List('approved_res', arOptions);
</script>
    </div> <!---end of approved resource tab--->


<!-- start of approved resource tab -->
    <div class="TabbedPanelsContent">
     <?php if($totalRows_all_resources>0){?>
    <div id="all_res">
    <div class="datagrid">
   
     <table>
    <thead>
      <tr>
        <th class="sort" data-sort="r_name">Resource Name</th>
        <th class="sort" data-sort="c_name">Course Name</th>
        <th class="sort" data-sort="r_size">Size in Mb</th>
        <th class="sort" data-sort="r_type">Type</th>
        <th class="sort" data-sort="author">Uploaded By</th>
        <th class="sort" data-sort="date">Uploaded Date </th>
        <th class="sort" data-sort="rate">Average rating</th>
        <th class="sort" data-sort="status">Current Status</th>
        <th></th>
        <th colspan="2">
          <input type="text" class="search" placeholder="Search Resource" />
        </th>
      </tr>
    </thead>
    <tbody class="list">
    <?php do { ?>
    <tr>
      <td class="r_name"><?php echo '<div id="'.$row_all_resources['r_id'].'" style="cursor:pointer;" onclick="showResource('.$row_all_resources['r_id'].',\'';
					if(strpos($row_all_resources['file_type'],"application/pdf")!==false) {
						echo "pdf";
					}else if (strpos($row_all_resources['file_type'],"image")!==false) {
						echo "image";
					}
					echo '\',\''.$row_all_resources['filename'].'\');">' ?>
      <?php echo $row_all_resources['filename'].'</div>'; ?></td>
      <td class="c_name"><?php echo $row_all_resources['c_name']; ?></td>
      <td class="r_size"><?php echo $row_all_resources['file_size']; ?></td>
      <td class="r_type"><?php echo $row_all_resources['file_type']; ?></td>
      <td class="author"><?php echo $row_all_resources['f_name']." ".$row_all_resources['l_name']; ?></td>
            <td class="date"><?php echo $row_all_resources['uploaded_date']; ?></td>
                  <td class="rate"><?php echo $row_all_resources['r_avg_rating']; ?></td>
                  <td class="status"><?php if($row_all_resources['flag_status']==1) { echo "Flagged resource";}
				  else if($row_all_resources['approve_status']==0){echo "Resource yet to be approved";}
				  else if($row_all_resources['approve_status']==1){echo "Resource is approved";}
				  else if($row_all_resources['approve_status']==2){echo "Resource is rejected";} ?></td>
                  <?php if($row_all_resources['download_status']==1){?>
        <form  id="form1" name="form1" method="POST" action="download_res.php">    
        
        <td> 
        <input name="change" id="change" value="Download" type="submit" >        
        </input>
        <input type="hidden" name="id" id="id" value="<?php echo $row_all_resources['r_id'];?>"  />
        
        <input type="hidden" name="MM_change" value="form1" />
        </td>
        </form>
        <?php }else echo "<td> </td>";?>
    <?php } while ($row_all_resources = mysql_fetch_assoc($all_resources));?>
      </tr>
      </tbody>
      </table>
      
    </div>
    </div>
    <?php }else echo"No resources uploaded by you"; ?>
    <script>
var arOptions = {
  valueNames: [ 'r_name', 'c_name','r_size','r_type','author','date','rate']
};

// Init list
var arList = new List('all_res', arOptions);
</script>
    </div> <!---end of all resource tab--->

    
    <div class="TabbedPanelsContent">
     <!--start of tab upload resource-->
     <div id="New Resource">
      <p>Please enter the Resource details : </p>
      <form id="new_resource" method="POST" action="upload_res.php" enctype="multipart/form-data">
       <p>
          <label for="co_name">Course Name* :</label>
          <select id= "co_name" name="co_name">
<?php 
		 do { 
		
				echo '<option value="'.$row_new_resource['c_id'].'"';
            echo '>'. $row_new_resource['c_name'] . '</option>'."\n";
		} while ($row_new_resource= mysql_fetch_assoc( $new_resource));
?></select>
       </p>
       <p>
          <label for="r_name">Resource Name* :</label>
          <input type="text" name="r_name" id="r_name" />
       </p> 
       <p>
          <label for="r_type">Resource Type* :</label>
          <select id= "r_type" name="r_type">
<?php 
		 do { 
		
				echo '<option value="'.$row_resource_type['type_id'].'"';
            echo '>'. $row_resource_type['r_type'] . '</option>'."\n";
		} while ($row_resource_type= mysql_fetch_assoc( $resource_type));
?></select>
       </p> 
       <p>
       <label for="download">Download Status* :</label>
       <select id= "download" name="download">
       <option value="1">Allow Download</option>
       <option value="0">Deny Download</option>
       </select>
       </p>
       <br/>
       <p> 
       <label for="file">File* :</label>
<input type="file" name="file" id="file">
		</p>  
      	   <input name="submit" type="submit" id="submit" onclick="MM_validateForm('co_name','','R','r_name','','R','r_type','','R');return document.MM_returnValue" value="Upload" action="upload_res.php"/>
      <input type="hidden" name="MM_insert" value="form" />
      </form>
      	</div><!--end of tab upload resource-->
      </div>
      
       <div class="TabbedPanelsContent">
    <!--- if no new students to be permitted then skip whole table--->
    
        <div id="new_students">
    <div class="datagrid">
    <?php if ($totalRows_update>0 ) { ?>
    <table>
    <thead>
    
  <tr>
  	 
    <th class="sort" data-sort="first_name">First Name</th>
    <th class="sort" data-sort="last_name">Last Name</th>
    <th class="sort" data-sort="stream">Student Stream</th>
    <th class="sort" data-sort="course_m">Course Name</th>
    <th class="sort" data-sort="score">Reputation</th>
    <th>Final Status</th>
    <th>    </th>
    <th colspan="2">
          <input type="text" class="search" placeholder="Search Student" />
        </th>
  </tr>
  </thead>
  <tbody class="list">
  <?php do { ?>
    
  
    <tr>
      
      <td class="first_name"><?php echo $row_update['f_name']; ?></td>
      <td class="last_name"> <?php echo $row_update['l_name']; ?></td>
      <td class="stream"><?php echo $row_update['s_stream']; ?></td>
      <td class="course_m"><a href="course_detail.php?c_id=<?php echo $row_update['c_id']; ?>"><?php echo $row_update['c_name']; ?></a></td>
      <td class="score"><?php echo $row_update['user_score']; ?></td>

      
<form  id="form10" name="form10" method="POST" action="<?php echo $editFormAction; ?>">      
<td><select name="app_id" id="app_id">
        <option value="0">  </option>
        <option value="1">Approved</option>
        <option value="2">Blocked</option>
        <option value="3">Rejected</option>
      </select></td>
      <td> 
      <input name="update" id="update" value="update" type="submit" ></input> 
        <input type="hidden" name="MM_update" value="form10" />
        <input type="hidden" id="update_q" name="update_q" value="<?php echo $row_update['u']?>" />
		<input type="hidden" id="update_c" name="update_c" value="<?php echo $row_update['c_id']?>" />

        </td></form>
    </tr>
    <?php } while ($row_update = mysql_fetch_assoc($update)); ?>
    </tbody>
    </table>
    </div></div>
    <?php } else {
		echo "No new students";
	} ?>
    <script>
var currOptions = {
  valueNames: [ 'first_name','last_name','stream','course_m','score']
};

// Init list
var currList = new List('new_students', currOptions);
</script>
    </div>
    <!--start of tab content--><div class="TabbedPanelsContent">
    <?php if ($totalRows_all_students>0 ) { ?>
        <div id="existing_students">
    <div class="datagrid">
    <table>
    <thead>
  <tr>
  
  	 <th class="sort" data-sort="ex_f_name">First Name</th>
    <th class="sort" data-sort="ex_l_name">Last Name</th>
    <th class="sort" data-sort="ex_stream">Stream</th>
    <th class="sort" data-sort="ex_course">Course</th>
    <th class="sort" data-sort="ex_score">Reputation</th>
    <th class="sort" data-sort="ex_status">Current Status</th>
    <th>    </th>
    <th colspan="2">
          <input type="text" class="search" placeholder="Search Student" />
        </th>
  </tr>
  </thead>
  <tbody class="list">
  <?php do { ?>
    
  
    <tr>
      <td class="ex_f_name"><?php echo $row_all_students['f_name']; ?></td>
      <td class="ex_l_name"><?php echo $row_all_students['l_name']; ?></td>
      <td class="ex_stream"><?php echo $row_all_students['s_stream']; ?></td>
      <td class="ex_course"><a href="course_detail.php?c_id=<?php echo $row_all_students['c_id']; ?>"><?php echo $row_all_students['c_name']; ?></a></td>
      <td class="ex_score"><?php echo $row_all_students['user_score']; ?></td>
      <td class="ex_status"><?php if($row_all_students['a_stat']==1) echo "Approved"; else echo "Rejected"; ?></td>
<form  id="form9" name="form9" method="POST" action="<?php echo $editFormAction; ?>">      
      <td> 
  <input name="change" id="change" value="<?php if($row_all_students['a_stat']==1){echo "Reject";}else {echo "Approve";}?>" type="submit" ></input> 
        <input type="hidden" name="MM_change" value="form9" />
        <input type="hidden" id="change_q" name="change_q" value="<?php echo $row_all_students['u']?>" />
        <input type="hidden" id="change_q" name="change_c" value="<?php echo $row_all_students['c_id']?>" />
        <input type="hidden" id="approve_id" name="approve_id" value="<?php if($row_all_students['a_stat']==1){echo "2";}else {echo "1";}?>"/>
        </td></form>
    </tr>
    <?php } while ($row_all_students = mysql_fetch_assoc($all_students)); ?>
    </tbody>
    </table>
    </div></div>
    <?php } else {
		echo "No Permitted students";
	} ?>
    <script>
var ex_Options = {
  valueNames: [ 'ex_f_name','ex_l_name','ex_stream','ex_course','ex_score','ex_status']
};

// Init list
var ex_List = new List('existing_students', ex_Options);
</script>
    </div><!--end of existing permitted students tab content-->
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
mysql_free_result($new_resource);
mysql_free_result($resource_type);
?>
