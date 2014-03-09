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
$MM_authorizedUsers = "cm";
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

mysql_select_db($database_conn, $conn);
$query_pending_courses = sprintf("SELECT c_id,c_name,c_stream,start_date,end_date,f_name,l_name,u_name,degree,user_score FROM course JOIN user ON course.u_id=user.u_id where approve_status=0");
$pending_courses = mysql_query($query_pending_courses, $conn) or die(mysql_error());
$row_pending_courses = mysql_fetch_assoc($pending_courses);
$totalRows_pending_courses = mysql_num_rows($pending_courses);

$query_resource = sprintf("SELECT c_id,c_name,c_stream,start_date,end_date,avg_rating FROM course ");
$new_resource = mysql_query($query_resource, $conn) or die(mysql_error());
$row_new_resource = mysql_fetch_assoc($new_resource);
$totalRows_new_resource = mysql_num_rows($new_resource);



$query_resource_type = sprintf("SELECT * FROM resource_type");
$resource_type = mysql_query($query_resource_type, $conn) or die(mysql_error());
$row_resource_type = mysql_fetch_assoc($resource_type);
$totalRows_resource_type = mysql_num_rows($resource_type);

mysql_select_db($database_conn, $conn);
$query_approved_courses = sprintf("SELECT c_id,c_name,c_stream,start_date,end_date,avg_rating FROM course WHERE approve_status=1");
$approved_courses = mysql_query($query_approved_courses, $conn) or die(mysql_error());
$row_approved_courses = mysql_fetch_assoc($approved_courses);
$totalRows_approved_courses = mysql_num_rows($approved_courses);


mysql_select_db($database_conn, $conn);
$query_pending_resources = sprintf("SELECT l_name,f_name,r_id,filename,file_type,file_size,uploaded_date,c_name,r.approve_status as r_status,file_location
FROM resource AS r
JOIN course AS c ON r.c_id = c.c_id JOIN user as u on r.uploaded_by = u.u_id
WHERE (r.approve_status =0 or r.flag_status=1) and c.approve_status=1 ");
$pending_resources = mysql_query($query_pending_resources, $conn) or die(mysql_error());
$row_pending_resources = mysql_fetch_assoc($pending_resources);
$totalRows_pending_resources = mysql_num_rows($pending_resources);


$query_approved_resources = sprintf("SELECT l_name,f_name,r_id,filename,file_type,file_size,uploaded_date,c_name,r.avg_rating as r_avg_rating, r.download_status,file_location
FROM resource AS r
JOIN course AS c ON r.c_id = c.c_id JOIN user as u on r.uploaded_by = u.u_id
WHERE (r.approve_status =1 and r.flag_status=0) and c.approve_status=1 ");
$approved_resources = mysql_query($query_approved_resources, $conn) or die(mysql_error());
$row_approved_resources = mysql_fetch_assoc($approved_resources);
$totalRows_approved_resources = mysql_num_rows($approved_resources);

mysql_select_db($database_conn, $conn);
$query_update = sprintf("SELECT u,c_id,c_name,f_name,l_name,user_score,a_stat,s_stream FROM( select distinct *,user.stream as s_stream,user.u_id as u from `user` natural join `enroll_course`)as a JOIN `course` as c ON a.c_enroll_id=c.c_id WHERE a.a_stat=0 ORDER BY a.u_id ASC ");
$update = mysql_query($query_update, $conn) or die(mysql_error());
$row_update = mysql_fetch_assoc($update);
$totalRows_update = mysql_num_rows($update);

mysql_select_db($database_conn, $conn);
$query_all_students = "SELECT u,c_name,f_name,l_name,user_score,a_stat,s_stream,c_id FROM( select distinct *,user.stream as s_stream,user.u_id as u from `user` natural join `enroll_course`)as a JOIN `course` as c ON a.c_enroll_id=c.c_id WHERE a.a_stat between 1 and 3 ORDER BY a.u_id ASC ";
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

  $updateGoTo = "cmhome.php";
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

  $updateGoTo = "cmhome.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));

}
/*end of permitted ka  query*/


//php code to update status of course
$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE `course` SET approve_status =%s WHERE c_id=%s",
                       GetSQLValueString($_POST['app_id'], "int"),
                       GetSQLValueString($_POST['update_q'], "int"));

  mysql_select_db($database_conn, $conn);
  $Result1 = mysql_query($updateSQL, $conn) or die(mysql_error());
  if($_POST['app_id']==1)
  	{ 
		$updateSQL=sprintf("INSERT INTO discussion_category (category_name) VALUES (%s)",
		GetSQLValueString($_POST['update_n'], "text"));
		mysql_select_db($database_conn, $conn);
  		$Result1 = mysql_query($updateSQL, $conn) or die(mysql_error());
	}
  $updateGoTo = "cmhome.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}
//php code to update resource status
if ((isset($_POST["MM_update_r"])) && ($_POST["MM_update_r"] == "form2")) {
		if($_POST['r_stat']==1)
		$flag=0;
		else
		$flag=1;

  $updateSQL_r = sprintf("UPDATE `resource` SET approve_status =%s,flag_status=%s WHERE r_id=%s",
                       GetSQLValueString($_POST['r_stat'], "int"),
					   GetSQLValueString($flag, "int"),
                       GetSQLValueString($_POST['rid'], "int"));

  mysql_select_db($database_conn, $conn);
  $Result2 = mysql_query($updateSQL_r, $conn) or die(mysql_error());

  $updateGoTo = "cmhome.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Content Manager Home</title>
<script src="SpryAssets/SpryTabbedPanels.js" type="text/javascript"></script>
<link href="SpryAssets/SpryTabbedPanels.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" media="screen" href="css/nav_bar.css" />
<link href="css/templatemo_style.css" type="text/css" rel="stylesheet" />
<link href="css/table.css" type="text/css" rel="stylesheet" /> 
<!---
script for js/list.js tables
--->
<script src="js/list.js"></script>
<script src="js/jquery.min.js"></script>
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

//function to show user float
	function showUser(id,name) {
	light = new LightFace.IFrame({
		height:400,
		width:500,
		url: 'show_user_float.php?u_id='+id,
		title: name
		}).addButton('Close', function() { light.close(); },true).open();
	}
</script>
</head>

<body onLoad="javascript:TabbedPanels1.showPanel(<?php echo $_COOKIE['index'];?>)">

<nav id="headerbar">
	<ul id="headerbar">
		<li id="headerbar"><a href="cmhome.php">Home</a></li>
		<li id="headerbar"><a href="forum_new.php?mode=showmain">Forums</a></li>
		<li id="headerbar"><a href="#"><?php echo $_SESSION['MM_Username'];?></a>
			<ul id="headerbar">
				<li id="headerbar"><a href="cmhome.php">Profile</a></li>
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
    <li class="TabbedPanelsTab" tabindex="0">Approved Courses</li>
    <li class="TabbedPanelsTab" tabindex="0">Pending Courses</li>
    <li class="TabbedPanelsTab" tabindex="0">Upload Resource</li>
     <li class="TabbedPanelsTab" tabindex="0">Approved Resource</li>
      <li class="TabbedPanelsTab" tabindex="0">Pending/Flagged Resource</li>
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
        <div class="TabbedPanelsContent">
    <div id="curr_courses">
    <div class="datagrid">
    <?php if($totalRows_approved_courses>0){?>
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
      <td class="currentname"><a href="course_detail.php?c_id=<?php echo $row_approved_courses['c_id']; ?>"><?php echo $row_approved_courses['c_name']; ?></a></td>
      <td class="currentstream"><?php echo $row_approved_courses['c_stream']; ?></td>
      <td class="currentstart"><?php echo $row_approved_courses['start_date']; ?></td>
      <td class="currentend"><?php echo $row_approved_courses['end_date']; ?></td>
      <td class="currentrating"><?php echo $row_approved_courses['avg_rating']; ?></td>
    <?php } while ($row_approved_courses = mysql_fetch_assoc($approved_courses));?>
      </tr>
      </tbody>
      </table>
      <?php }else echo"NO Approved Courses"; ?>
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
    <div id="pending_courses">
    <div class="datagrid">
     <?php if($totalRows_pending_courses>0){?>
    <table>
    <thead>
      <tr>
        <th class="sort" data-sort="allname">Course Name</th>
        <th class="sort" data-sort="allstream">Stream</th>
        <th class="sort" data-sort="allstart">Start Date</th>
        <th class="sort" data-sort="allend">End Date</th>
        <th class="sort" data-sort="name">Author Name</th>
        <th class="sort" data-sort="degree">Degree of Author</th>
        <th class="sort" data-sort="score">Author's Score</th>
        <th></th>
        <th></th>
        <th colspan="2">
          <input type="text" class="search" placeholder="Search course" />
        </th>
      </tr>
    </thead>
    <tbody class="list">
     <?php do { ?>
              <tr>
      <td class="allname"><a href="course_detail.php?c_id=<?php echo $row_pending_courses['c_id']; ?>"><?php echo $row_pending_courses['c_name']; ?></a></td>
      <td class="allstream"><?php echo $row_pending_courses['c_stream']; ?></td>
      <td class="allstart"><?php echo $row_pending_courses['start_date']; ?></td>
      <td class="allend"><?php echo $row_pending_courses['end_date']; ?></td>
      <td class="name"><?php echo $row_pending_courses['f_name']." ".$row_pending_courses['l_name']; ?></td>
      <td class="degree"><?php echo $row_pending_courses['degree']; ?></td>
      <td class="score"><?php echo $row_pending_courses['user_score']; ?></td>
      <form  id="form1" name="form1" method="POST" action="<?php echo $editFormAction; ?>">      <td><select name="app_id" id="app_id">
        <option value="0">  </option>
        <option value="1">Approved</option>
        <option value="2">Rejected</option>
      </select></td>
      <td> 
      <input name="update" id="update" value="update" type="submit" ></input> 
        <input type="hidden" name="MM_update" value="form1" />
        <input type="hidden" id="update_q" name="update_q" value="<?php echo $row_pending_courses['c_id']?>" />
        <input type="hidden" id="update_n" name="update_n" value="<?php echo $row_pending_courses['c_name']?>" />
        </td></form>
          </tr>
    <?php } while ($row_pending_courses = mysql_fetch_assoc($pending_courses)); ?>
      </tbody>
      </table>
      <?php }else echo"No Pending Courses"; ?>
      </div> 
    </div>

    <script>
var allOptions = {
  valueNames: [ 'allname', 'allstream','allstart','allend','allrating' ]
};

// Init list
var allList = new List('pending_courses', allOptions);
</script>
    </div>
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
      <!-- start of approved resource tab-->
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
      <td class="r_name"><a>
      <?php echo '<div id="'.$row_approved_resources['r_id'].'" style="cursor:pointer;" onclick="showResource('.$row_approved_resources['r_id'].',\'';
					if(strpos($row_approved_resources['file_type'],"application/pdf")!==false) {
						echo "pdf";
					}else if (strpos($row_approved_resources['file_type'],"image")!==false) {
						echo "image";
					}
					echo '\',\''.$row_approved_resources['filename'].'\');">' ?>
	  <?php echo $row_approved_resources['filename'].'</div>'; ?></a></td>
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
    </div> <!---end of fifth tab--->
    
    <div class="TabbedPanelsContent">
    <?php if($totalRows_pending_resources>0){?>
    <div id="pending_resources">
    <div class="datagrid">
     
    <table>
    <thead>
      <tr>
        <th class="sort" data-sort="pr_name">Resource Name</th>
        <th class="sort" data-sort="pc_name">Course Name</th>
        <th class="sort" data-sort="pr_size">Size in Mb</th>
        <th class="sort" data-sort="pr_type">Type</th>
        <th class="sort" data-sort="pauthor">Uploaded By</th>
        <th class="sort" data-sort="pdate">Uploaded Date </th>
        <th></th>
        <th></th>
        <th colspan="2">
          <input type="text" class="search" placeholder="Search resource" />
        </th>
      </tr>
    </thead>
    <tbody class="list">
     <?php do { ?>
              <tr>
      <td class="pr_name"><a>
      <?php echo '<div id="'.$row_pending_resources['r_id'].'" style="cursor:pointer;" onclick="showResource('.$row_pending_resources['r_id'].',\'';
					if(strpos($row_pending_resources['file_type'],"application/pdf")!==false) {
						echo "pdf";
					}else if (strpos($row_pending_resources['file_type'],"image")!==false) {
						echo "image";
					}
					echo '\',\''.$row_pending_resources['filename'].'\');">' ?>
	  <?php echo $row_pending_resources['filename'].'</div>'; ?></a></td>
      <td class="pc_name"><?php echo $row_pending_resources['c_name']; ?></td>
      <td class="pr_size"><?php echo $row_pending_resources['file_size']; ?></td>
      <td class="pr_type"><?php echo $row_pending_resources['file_type']; ?></td>
      <td class="pauthor"><?php echo $row_pending_resources['f_name']." ".$row_pending_resources['l_name']; ?></td>
            <td class="pdate"><?php echo $row_pending_resources['uploaded_date']; ?></td>

      <form  id="form2" name="form2" method="POST" action="<?php echo $editFormAction; ?>">      <td><select name="r_stat" id="r_stat">
        <option value="0">  </option>
        <option value="1">Approved</option>
        <option value="2">Rejected</option>
      </select></td>
      <td> 
      <input name="update" id="update" value="update" type="submit" ></input> 
        <input type="hidden" name="MM_update_r" value="form2" />
        <input type="hidden" id="" name="rid" value="<?php echo $row_pending_resources['r_id']?>" />
        </td></form>
          </tr>
    <?php } while ($row_pending_resources = mysql_fetch_assoc($pending_resources)); ?>
      </tbody>
      </table>
      
      </div> 
    </div>
<?php }else echo"No Pending Resources"; ?>
   <script>
var prOptions = {
  valueNames: [ 'pr_name', 'pc_name','pr_size','pr_type','pauthor','pdate','prate']
};

// Init list
var prList = new List('pending_resources', prOptions);
</script>
    </div><!--- end of pending resource tab-->
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
      
      <td class="first_name"><a onclick="showUser(<?php echo $row_update['u'].",'".$row_update['f_name']." ".$row_update['l_name'];?>');return false;" style="cursor:pointer;"><?php echo $row_update['f_name']; ?></a></td>
      <td class="last_name"><a onclick="showUser(<?php echo $row_update['u'].",'".$row_update['f_name']." ".$row_update['l_name'];?>');return false;" style="cursor:pointer;"><?php echo $row_update['l_name']; ?></a></td>
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
      <td class="ex_f_name"><a onclick="showUser(<?php echo $row_all_students['u'].",'".$row_all_students['f_name']." ".$row_all_students['l_name'];?>');return false;" style="cursor:pointer;"><?php echo $row_all_students['f_name']; ?></a></td>
      <td class="ex_l_name"><a onclick="showUser(<?php echo $row_all_students['u'].",'".$row_all_students['f_name']." ".$row_all_students['l_name'];?>');return false;" style="cursor:pointer;"><?php echo $row_all_students['l_name']; ?></a></td>
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
<script type="text/javascript">
var TabbedPanels1 = new Spry.Widget.TabbedPanels("TabbedPanels1");
</script>
</body>
</html>