<?php
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

mysql_select_db($database_conn, $conn);
if(isset($_GET['sortType'])) {
	if($_GET['sortType']==1) {
		//sort by most popular
		$query_sort_courses = sprintf("SELECT * FROM `course` LEFT OUTER JOIN (SELECT * FROM `enroll_course` WHERE u_id =%s ) AS `temp` ON course.c_id = temp.c_id WHERE approve_status =1 ORDER BY avg_rating",GetSQLValueString($_SESSION['MM_UserID'], "int"));
	}else if($_GET['sortType']==2) {
		//sort by latest
		$query_sort_courses = sprintf("SELECT * FROM `course` LEFT OUTER JOIN (SELECT * FROM `enroll_course` WHERE u_id=%s) AS `temp` ON course.c_id = temp.c_id WHERE approve_status=1 ORDER BY inserted_on DESC",GetSQLValueString($_SESSION['MM_UserID'], "int"));

	}else if($_GET['sortType']==3) {
		//sort by starting soon
		$query_sort_courses = sprintf("SELECT * FROM `course` LEFT OUTER JOIN (SELECT * FROM `enroll_course` WHERE u_id=%s) AS `temp` ON course.c_id = temp.c_id WHERE approve_status=1 AND start_date>=now() ORDER BY start_date ASC",GetSQLValueString($_SESSION['MM_UserID'], "int"));

	}

	$sort = mysql_query($query_sort_courses, $conn) or die(mysql_error());
	$row_sort = mysql_fetch_assoc($sort);
	$totalRows_sort = mysql_num_rows($sort);
	
	do {
		echo "<li>";
		echo '<a href="index.php"><img src="'.$row_sort['course_image'].'" alt=""/></a>';
		echo '<span><a href="index.php">'.$row_sort['c_name'].'</a></span><br>';
		echo '<p>'.$row_sort['description'].'</p>';
		echo '<p class="dates">Duration : '.$row_sort['start_date'].' - '.$row_sort['end_date'].'</p>';
		echo '<a href="index.php" class="details">See Details</a>';
		if (!empty($row_sort['u_id'])) {
			//enrolled for course already
			echo '<p class="enrolled">Already enrolled!</p>';
		}else {
			//not yet enrolled for course
			echo '<a href="index.php" class="enroll">Enroll Now!</a>';
		}
		echo "</li>";
	}while($row_sort = mysql_fetch_assoc($sort));
}
?>