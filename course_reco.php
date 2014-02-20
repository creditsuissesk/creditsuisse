<?php
//this page generates resource floating layout.
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

$MM_restrictGoTo = "index.php#login";
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

<script type="text/javascript" src="js/jquery.min.js"></script> 	
<script type="text/javascript">
function searchStudent() {
	var query=document.getElementById('student_query').value;
	var cId=document.getElementById('c_id').value;
	if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
		  xmlhttp=new XMLHttpRequest();
		} else {// code for IE6, IE5
		  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  		}
		xmlhttp.onreadystatechange=function()
		{
		  if (xmlhttp.readyState==4 && xmlhttp.status==200) {
				document.getElementById("tableholder").innerHTML=xmlhttp.responseText;
			}
		}
		xmlhttp.open("GET","course_reco.php?search="+query+"&course_id="+cId,true);
		xmlhttp.send();
}

function recommendStudent(e) {
	var r=confirm("Are you sure you want to proceed with recommendation?");
	if (r==true) {
	var cId=document.getElementById('c_id').value;
	if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
		  xmlhttp=new XMLHttpRequest();
		} else {// code for IE6, IE5
		  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  		}
		xmlhttp.onreadystatechange=function()
		{
		  if (xmlhttp.readyState==4 && xmlhttp.status==200) {
				document.getElementById("tableholder").innerHTML=xmlhttp.responseText;
			}
		}
		xmlhttp.open("GET","course_reco.php?recommendTo="+e.id+"&course="+cId,true);
		xmlhttp.send();
	}
}



</script>


<?php
mysql_select_db($database_conn, $conn);
if(isset($_GET['c_id'])) {
	//first check if the user is indeed enrolled in the course he is recommending.
	$query_check_enroll=sprintf("SELECT * from `enroll_course` WHERE u_id=%s AND c_enroll_id=%s",GetSQLValueString($_SESSION['MM_UserID'], "int"),GetSQLValueString($_GET['c_id'], "int"));
	$check_enroll=mysql_query($query_check_enroll, $conn) or die(mysql_error());
	$row_check_enroll = mysql_fetch_assoc($check_enroll);
	$totalRows_check_enroll = mysql_num_rows($check_enroll);
	if($totalRows_check_enroll==1) { ?>
		<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
		<html xmlns="http://www.w3.org/1999/xhtml">
		<head>        
		<style>
		td {
			color:#333;
		}
		</style>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>Recommend Course</title>    
		</head>
		<body>
    	<form>
        <input id="student_query" type="text" placeholder="Enter student name to search" size="30"/>
        <input id="submit_button" type="button" value="Search"  onclick="searchStudent(); return false;"/>
        <input id="c_id" type="hidden" value="<?php echo $_GET['c_id'];?>" />
        </form>
        <div id="tableholder">
        </div>
        <script> $(document).ready(function() {
			searchStudent();
        });
        </script>
        </body>
		</html>
	<?php
	}else {
		//echo "You can not recommend the course without enrolling in it.";
	}
}
if (isset($_GET['search']) && isset($_GET['course_id']) ) {
	//after receiving the search query, show the table of users, who are not already sent recommendation by same user and who are not already enrolled in this course.
	$query_search_student="SELECT *,user.u_id AS show_user_id from `user` LEFT OUTER JOIN (SELECT * FROM `course_reco` WHERE c_reco_id=".$_GET['course_id']." AND from_u_id=".$_SESSION['MM_UserID'].")AS temp ON user.u_id=temp.to_u_id LEFT OUTER JOIN `enroll_course` ON user.u_id=enroll_course.u_id AND enroll_course.c_enroll_id=".$_GET['course_id']." WHERE ((u_name LIKE '%".$_GET['search']."%') OR (f_name LIKE '%".$_GET['search']."%') OR (l_name LIKE '%".$_GET['search']."%')) AND role='student' AND (NOT user.u_id=".$_SESSION['MM_UserID'].") AND user.approve_id=1";
	$search_student=mysql_query($query_search_student, $conn) or die(mysql_error());
	$row_search_student = mysql_fetch_assoc($search_student);
	$totalRows_search_student = mysql_num_rows($search_student);

	$omitted_entries=0;
	if($totalRows_search_student>0)  {
		//divs for scrolling
		echo '<div style="height:250px;position:relative;padding:0px;">';
		echo '<div style="max-height:100%;overflow:auto;"><div style="overflow:auto;">';
		echo '<table class="datagrid" style="css/table.css">';
		do {
			echo '<tr>';
			if(empty($row_search_student['c_reco_id']) && empty($row_search_student['marks'])) {	
			//no recommendation has been already made and the user has not enrolled
				echo '<td> <a title="Click to recommend this course" onclick="recommendStudent(this)" id="'.$row_search_student['show_user_id'].'" style="cursor:pointer;">'.$row_search_student['f_name'].' '.$row_search_student['l_name'].'</a></td>';
			}else if(!empty($row_search_student['c_reco_id']) && empty($row_search_student['marks'])){
				//recommendation has been already made. show a tick.
				echo '<td> '.$row_search_student['f_name'].' '.$row_search_student['l_name'].'</td><td><img src="images/tick.png" width="15" height="15" title="You have already recommended this course to this user" /></td>';
			}
			if(!empty($row_search_student['marks'])) {
				$omitted_entries++;
			}
			echo '</tr>';
		}while($row_search_student=mysql_fetch_assoc($search_student));
		echo "</table>";
		echo "</div></div></div>";
	}
	//if a query result contains a student who has already enrolled, he is not shown in results. but if he is the only one, then no matching result message needs to be shown. hence omitted entries variable.
	if($totalRows_search_student-$omitted_entries==0) {	
		echo "Sorry, no students match your query.";
	}
}

if(isset($_GET['recommendTo']) && isset($_GET['course'])) {
	//file_put_contents("test.txt",$_SESSION['MM_UserID']." recommended course ".$_GET['course']." to ".$_GET['recommendTo']);
	$query_add_reco=sprintf("INSERT INTO `course_reco`(c_reco_id,from_u_id,to_u_id) VALUES (%s,%s,%s) ON DUPLICATE KEY UPDATE c_reco_id=c_reco_id",GetSQLValueString($_GET['course'], "int"),GetSQLValueString($_SESSION['MM_UserID'], "int"),GetSQLValueString($_GET['recommendTo'], "int"));
	$add_reco=mysql_query($query_add_reco, $conn) or die(mysql_error());
	echo "Your recommendation added successfully";
}
?>
