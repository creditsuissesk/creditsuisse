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
$MM_authorizedUsers = "cm,author";
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
$query_course_students = sprintf("SELECT * FROM `user` NATURAL JOIN `enroll_course` WHERE c_enroll_id=%s AND a_stat=1",GetSQLValueString($_GET['c_id'], "int"));
$course_students = mysql_query($query_course_students, $conn) or die(mysql_error());
$row_course_students = mysql_fetch_assoc($course_students);
$totalRows_course_students = mysql_num_rows($course_students);


mysql_select_db($database_conn, $conn);
$query_resource = sprintf("SELECT r_id,c_id,uploaded_date,file_size,r_type,type_id,file_type,filename,download_status FROM `resource`NATURAL JOIN `resource_type` WHERE c_id=%s ",GetSQLValueString($_GET['c_id'], "int"));
$resource = mysql_query($query_resource, $conn) or die(mysql_error());
$row_resource = mysql_fetch_assoc($resource);
$totalRows_resource = mysql_num_rows($resource);

mysql_select_db($database_conn, $conn);
$query_author_details = sprintf("SELECT * FROM user JOIN course ON course.u_id=user.u_id WHERE c_id = %s", GetSQLValueString($colname_course_details, "int"));
$author_details = mysql_query($query_author_details, $conn) or die(mysql_error());
$row_author_details = mysql_fetch_assoc($author_details);
$totalRows_author_details = mysql_num_rows($author_details);


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $row_course_details['c_name'];?></title>
<script src="SpryAssets/SpryTabbedPanels.js" type="text/javascript"></script>
<script src="js/list.js"></script><meta charset=utf-8 />
<link href="SpryAssets/SpryTabbedPanels.css" rel="stylesheet" type="text/css" />
<script src="js/jquery.min.js"></script>
<link href="css/templatemo_style.css?123" type="text/css" rel="stylesheet" /> 
<link rel="stylesheet" type="text/css" media="screen" href="css/course_list.css" /> 
<link rel="stylesheet" type="text/css" media="screen" href="css/nav_bar.css" />
<link href="css/table.css" type="text/css" rel="stylesheet" /> 
<link href="css/course_detail.css" type="text/css" rel="stylesheet" /> 
<script src="js/course_detail.js"></script>

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
</head>

<body onLoad="javascript:TabbedPanels1.showPanel(<?php echo $_COOKIE['index'];?>)">
<nav id="headerbar">
	<ul id="headerbar" style="margin:0px;">
		<li id="headerbar"><a href="<?php if ($_SESSION['MM_UserGroup']=='author') {
			echo "authorhome.php";
		}else if ($_SESSION['MM_UserGroup']=='cm') {
			echo "cmhome.php";
		}
		?>">Home</a></li>
		<li id="headerbar"><a href="forum_new.php?mode=showmain">Forums</a></li>
		<li id="headerbar" style="color: rgb(0, 0, 0);font-size: 14px;"><a href="#"><?php echo $_SESSION['MM_Username'];?></a>
			<ul id="headerbar" style="margin: 0px;">
				<li id="headerbar"><a href="<?php if ($_SESSION['MM_UserGroup']=='author') {
					echo "authorhome.php";
				}else if ($_SESSION['MM_UserGroup']=='cm') {
					echo "cmhome.php";
				}
				?>">Profile</a></li>
				<li id="headerbar"><a href="<?php echo $logoutAction ?>">Log Out</a>
				</li>
			</ul>
		</li>
	</ul>
</nav>
<br/>

<style>
body {
  font-family:sans-serif;
}
table td, table th {
  padding:5px;`
}
</style>
<div id="coursename_title"><?php echo $row_course_details['c_name']?></div>
<div id="TabbedPanels1" class="TabbedPanels">
  <ul class="TabbedPanelsTabGroup">
    <li class="TabbedPanelsTab" tabindex="0">Description</li>
    <li class="TabbedPanelsTab" tabindex="0">Student List</li>
    <li class="TabbedPanelsTab" tabindex="0">Files</li>
    <li class="TabbedPanelsTab" tabindex="0">Course Evaluation</li>
     <li class="TabbedPanelsTab" tabindex="0">Author Details</li>
  </ul>
  <div class="TabbedPanelsContentGroup">
    <div class="TabbedPanelsContent">
    <div class="tab-header">Course Description</div>
    	<div class="tab-form-box" style="background-color:#FFF;border-radius:5px;">
      	<p><?php echo $row_course_details['description']; ?></p>
      	</div>
    </div>
    <div class="TabbedPanelsContent">
    <?php if($totalRows_course_students>0) {?>
    <div id="users">
    <div class="datagrid">
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
        <td class="name"><a onclick="showUser(<?php echo $row_course_students['u_id'].",'".$row_course_students['f_name']." ".$row_course_students['l_name'];?>');return false;" style="cursor:pointer;"><?php echo $row_course_students['f_name'];?> <?php echo $row_course_students['l_name'];?></a></td>
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
  </div>
   <script>
var options = {
  valueNames: [ 'name', 'contact','email','dob','institute','stream' ]
};

// Init list
var userList = new List('users', options);
</script>
 <?php }
		else {
			echo "No students have enrolled for this course yet!";
		}
		?>
</div>
		 <!--start of file tab-->
         <div class="TabbedPanelsContent">
           <?php if($totalRows_resource>0) {?>
           <div id="resource">
             <div class="datagrid">
               <table>
                 <thead>
                   <tr>
                     <th class="sort" data-sort="name">Resource Name</th>
                     <th class="sort" data-sort="size">Resource Size</th>
                     <th class="sort" data-sort="type">Resource Type</th>
                     <th class="sort" data-sort="date">Resource Uploaded Date</th>
                     <th class="sort" data-sort="f_type">File Type</th>
                     <th></th>
                     <th></th>
                     <th colspan="2"> <input type="text" class="search" placeholder="Search Resource" />
                     </th>
                   </tr>
                 </thead>
                 <tbody class="list">
                   <?php do { ?>
                   <tr>
                     <td class="name"><a><?php echo '<div id="'.$row_resource['r_id'].'" style="cursor:pointer;" onclick="showResource('.$row_resource['r_id'].',\'';
					if(strpos($row_resource['file_type'],"application/pdf")!==false) {
						echo "pdf";
					}else if (strpos($row_resource['file_type'],"image")!==false) {
						echo "image";
					}else if (strpos($row_resource['file_type'],"video")!==false) {
						echo "video";
					}
					echo '\',\''.$row_resource['filename'].'\');">' ?>
					 <?php echo $row_resource['filename'];?></a>
                     </td>
                     <td class="size"><?php echo $row_resource['file_size'];?></td>
                     <td class="type"><?php echo $row_resource['r_type'];?></td>
                     <td class="date"><?php echo $row_resource['uploaded_date'];?></td>
                     <td class="f_type"><?php echo $row_resource['file_type'];?></td>
                     <?php if($row_resource['download_status']==1){?>
                     <form  id="form1" name="form1" method="POST" action="download_res.php">
                       <td><input name="change" id="change" value="Download" type="submit" />
                         <input type="hidden" name="id" id="id" value="<?php echo $row_resource['r_id'];?>"  />
                         <input type="hidden" name="MM_change" value="form1" /></td>
                     </form>
                     <?php }else echo "<td> </td>";?>
                       <td><input name="change" onclick="updateResource(<?php echo $row_resource['r_id'];?>,'<?php echo $row_resource['filename'];?>');" id="change" value="Update" type="button" />
                       </td>
                   </tr>
                 </tbody>
                 <?php } while ($row_resource = mysql_fetch_assoc($resource)); ?>
               </table>
             </div>
           </div>
           <?php }
		else {
			echo "No resources have been uploaded for this course yet!";
		}
		?>
           <script>
var resOptions = {
  valueNames: ['name','size','type','date','f_type' ]
};
// Init list
var resList = new List('resource', resOptions);
           </script>
         </div>
         <!--end of file tab-->
         <div class="TabbedPanelsContent">
			<div class="container">
				<main class="course-content">
					<div id="course"><div><div class="evaluation">
					<h2>Course Evaluation</h2>
                    <ul>
						<li>
                            <form id="mcqform">
                            <h4 style="color:#93CDF5;float:left;">New Question:</h4><br /><br />
                            Please select the button against correct option.<br />
                            
                            <table><tr><td></td>
                            <td><input id="ques" size="100" type="text" placeholder="Enter your question here" /></td></tr>
                            <tr><td>
                            <input type="radio" id="r1" name="correct" value="1"/></td>                          
                            <td><input id="opt1" size="100" type="text" placeholder="Enter option 1"/></td></tr>
                            <tr><td>
                            <input type="radio" id="r2" name="correct" value="2"/></td>
                            <td><input id="opt2" size="100" type="text" placeholder="Enter option 2"/></td></tr>
                            <tr><td>
                            <input type="radio" id="r3" name="correct" value="3"/></td>
                            <td><input id="opt3" size="100" type="text" placeholder="Enter option 3"/></td></tr>
                            <tr><td>
                            <input type="radio" id="r4" name="correct" value="4"/></td>
                            <td><input id="opt4" size="100" type="text" placeholder="Enter option 4"/></td></tr>
                            </table>
                            <input type="hidden" id="c_id" value="<?php echo $_GET['c_id']?>"/>
                            <input id="submit" type="button" class="buttom" value="Submit" onclick="addQuestion()" />
                            </form>
						</li>
                     </ul>
                     <!--- this ul will contain existing questions --->
                     <ul id="questionslist">
                     </ul>
                    </div></div></div>
				</main><!-- .content -->
			</div><!--- .container-->
			<script> $(document).ready(function(){showquestions();});</script>
         </div> <!--- end of evaluation tab--->
         <!-- start of author details tab -->
         <div class="TabbedPanelsContent">
         <div class="tab-header"> Course Author's Profile</div>
         	<div class="tab-form-box" style="background-color:#FFF;border-radius:5px;">
	<div id="pagewidth" >
	<div id="wrapper" class="clearfix">
		<div id="twocols"> 
			<div id="rightcol">
			<p><b>Name : </b><i><?php echo $row_author_details['f_name']." ".$row_author_details['l_name'];?></i></p>
			<p><b>Degree of specialization : </b><i><?php echo $row_author_details['degree'];?></i></p>
			<p><b>Institute of Specialization :</b><i> <?php echo $row_author_details['institute'];?></i></p>
			<p><b>Contact me at : </b><i><?php echo $row_author_details['u_name'];?></i></p>
			<p><b> About myself:</b> <p style="font-style:italic"><?php echo $row_author_details['about'];?></p></p>            
            </div>
        </div>
		<div id="leftcol">
    	<img src="<?php echo $row_author_details['photo'];?>" alt="" height="300" width="200" />    
        </div>

            </div></div></div>
    
							
    </div>
         
         <!-- end of author details tab -->
   </div>
</div>
<script type="text/javascript">
var TabbedPanels1 = new Spry.Widget.TabbedPanels("TabbedPanels1");
</script>
</body>
</html>
<?php
mysql_free_result($course_details);

mysql_free_result($course_students);
mysql_free_result($resource);
?>
