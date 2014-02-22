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
		//bookmarked
		$query_sort_courses = sprintf("SELECT * FROM (SELECT c_name,uploaded_date, filename ,resource.c_id, resource.avg_rating AS res_avg_rating, resource.download_status, r_id  FROM `resource` JOIN `course` ON course.c_id = resource.c_id WHERE resource.approve_status =1 AND course.approve_status =1 ) AS `temp` JOIN `enroll_course` ON temp.c_id = c_enroll_id JOIN `user_resource` ON temp.r_id=user_resource_id WHERE enroll_course.u_id=%s AND bookmarked=1",GetSQLValueString($_SESSION['MM_UserID'], "int"));
	}else if($_GET['sortType']==2) {
		//sort by most popular
		$query_sort_courses = sprintf("SELECT * FROM (SELECT c_name,uploaded_date, filename ,resource.c_id, resource.avg_rating AS res_avg_rating, resource.download_status, r_id  FROM `resource` JOIN `course` ON course.c_id = resource.c_id WHERE resource.approve_status =1 AND course.approve_status =1 ) AS `temp` JOIN `enroll_course` ON temp.c_id = c_enroll_id WHERE enroll_course.u_id=%s ORDER BY temp.res_avg_rating DESC",GetSQLValueString($_SESSION['MM_UserID'], "int"));
	}else if($_GET['sortType']==3) {
		//sort by latest
		$query_sort_courses = sprintf("SELECT * FROM (SELECT resource.uploaded_date, c_name, r_id, filename,resource.c_id,resource.avg_rating AS res_avg_rating, resource.download_status  FROM `resource` JOIN `course` ON course.c_id = resource.c_id WHERE resource.approve_status =1 AND course.approve_status =1 ) AS `temp` JOIN `enroll_course` ON temp.c_id = c_enroll_id WHERE enroll_course.u_id=%s ORDER BY temp.uploaded_date DESC",GetSQLValueString($_SESSION['MM_UserID'], "int"));
	}
	$sort = mysql_query($query_sort_courses, $conn) or die(mysql_error());
	$row_sort = mysql_fetch_assoc($sort);
	$totalRows_sort = mysql_num_rows($sort);
	if($totalRows_sort>0){
		do {
			echo "<li>";
			echo '<span><b><a href="course_details_stud.php?c_id='.$row_sort['c_id'].'&show_res='.$row_sort['r_id'].'#resources">'.$row_sort['filename'].'</a></b> in <i>'.$row_sort['c_name'].'</i></span><br>';
			echo '<p class="dates">Uploaded on: '.$row_sort['uploaded_date'].'</p>';
			echo '<p ><b> Average Rating: </b><i>'.$row_sort['res_avg_rating'].'</i></p>';
			if (($row_sort['download_status']==1)) {
				//enrolled for course already
				echo '<p class="enrolled"><a href="download_res.php?id='.$row_sort['r_id'].'">Download</a></p>';
			}else {
				//not yet enrolled for course
			/*	echo '<a id="'.$row_sort['c_id'].'" class="enroll" onClick="enrollCourse(this); return false;">Enroll Course Now!</a>';*/
			}
			echo "</li>";
		}while($row_sort = mysql_fetch_assoc($sort));
	} else { 
		echo "No resources satisfy this condition at present";}
	}
else if (isset($_GET['searchKey']) && isset($_GET['searchType']) ) {
	//search course according to keyword
	$u_id=GetSQLValueString($_SESSION['MM_UserID'], "int");
	if($_GET['searchType']==2) {
		//search by course name
		$query_search_courses = "SELECT * FROM (SELECT resource.uploaded_date, c_name, r_id, filename,resource.c_id,resource.avg_rating AS rating, resource.download_status  FROM `resource` JOIN `course` ON course.c_id = resource.c_id WHERE resource.approve_status =1 AND course.approve_status =1 AND c_name LIKE '%".$_GET['searchKey']."%') AS `temp` JOIN `enroll_course` ON temp.c_id = c_enroll_id WHERE enroll_course.u_id=".$u_id." ORDER BY temp.uploaded_date DESC";
		
	}else if ($_GET['searchType']==1) {
		//search by filename
		$query_search_courses = "SELECT * FROM (SELECT resource.uploaded_date, c_name, r_id, filename,resource.c_id,resource.avg_rating AS rating, resource.download_status  FROM `resource` JOIN `course` ON course.c_id = resource.c_id WHERE resource.approve_status =1 AND course.approve_status =1 ) AS `temp` JOIN `enroll_course` ON temp.c_id = c_enroll_id WHERE enroll_course.u_id=".$u_id." AND filename LIKE '%".$_GET['searchKey']."%' ORDER BY temp.uploaded_date DESC";
		
	}
	$search = mysql_query($query_search_courses, $conn) or die(mysql_error());
	$row_search = mysql_fetch_assoc($search);
	$totalRows_search = mysql_num_rows($search);
	if($totalRows_search>0){
		do {
			echo "<li>";
			echo '<span><b>'.$row_search['filename'].'</b> in <i>'.$row_search['c_name'].'</i></span><br>';
			echo '<p class="dates">Uploaded on: '.$row_search['uploaded_date'].'</p>';
			echo '<p ><b> Votes : </b><i>'.$row_search['rating'].'</i></p>';
			if (($row_search['download_status']==1)) {
				//enrolled for course already
				echo '<p class="enrolled"><a href="download_res.php?id='.$row_search['r_id'].'">Download</p>';
			}else {
				//not yet enrolled for course
			/*	echo '<a id="'.$row_sort['c_id'].'" class="enroll" onClick="enrollCourse(this); return false;">Enroll Course Now!</a>';*/
			}
			echo "</li>";
		}while($row_search = mysql_fetch_assoc($search));
	} else { 
		echo "No resources satisfy this condition at present";}
	}

?>
