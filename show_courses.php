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
		$query_sort_courses = sprintf("SELECT * FROM `course` LEFT OUTER JOIN (SELECT * FROM `enroll_course` WHERE u_id =%s ) AS `temp` ON course.c_id = temp.c_enroll_id WHERE approve_status =1 ORDER BY avg_rating",GetSQLValueString($_SESSION['MM_UserID'], "int"));
	}else if($_GET['sortType']==2) {
		//sort by latest
		$query_sort_courses = sprintf("SELECT * FROM `course` LEFT OUTER JOIN (SELECT * FROM `enroll_course` WHERE u_id=%s) AS `temp` ON course.c_id = temp.c_enroll_id WHERE approve_status=1 ORDER BY inserted_on DESC",GetSQLValueString($_SESSION['MM_UserID'], "int"));

	}else if($_GET['sortType']==3) {
		//sort by starting soon
		$query_sort_courses = sprintf("SELECT * FROM `course` LEFT OUTER JOIN (SELECT * FROM `enroll_course` WHERE u_id=%s) AS `temp` ON course.c_id = temp.c_enroll_id WHERE approve_status=1 AND start_date>=now() ORDER BY start_date ASC",GetSQLValueString($_SESSION['MM_UserID'], "int"));

	}else if($_GET['sortType']==4) {
		//sort by running course
		$query_sort_courses = sprintf("SELECT * FROM `course` LEFT OUTER JOIN (SELECT * FROM `enroll_course` WHERE u_id=%s) AS `temp` ON course.c_id = temp.c_enroll_id WHERE approve_status=1 AND start_date<=now() AND end_date>now() ORDER BY start_date ASC",GetSQLValueString($_SESSION['MM_UserID'], "int"));
	}
	$sort = mysql_query($query_sort_courses, $conn) or die(mysql_error());
	$row_sort = mysql_fetch_assoc($sort);
	$totalRows_sort = mysql_num_rows($sort);
	if($totalRows_sort>0){
		do {
			echo "<li>";
			echo '<a href="course_details_stud.php?c_id='.$row_sort['c_id'].'"><img src="'.$row_sort['course_image'].'" alt=""/></a>';
			echo '<span><a href="course_details_stud.php?c_id='.$row_sort['c_id'].'">'.$row_sort['c_name'].'</a> in '.$row_sort['c_stream'].'</span><br>';
			echo '<p>'.$row_sort['description'].'</p>';
			echo '<p class="dates">Duration : '.$row_sort['start_date'].' - '.$row_sort['end_date'].'</p>';
			echo '<a href="course_details_stud.php?c_id='.$row_sort['c_id'].'" class="details">See Details</a>';
			if (!empty($row_sort['u_id'])) {
				//enrolled for course already
				echo '<p class="enrolled">Enrolled!</p>';
			}else {
				//not yet enrolled for course
				echo '<a id="'.$row_sort['c_id'].'" class="enroll" onClick="enrollCourse(this); return false;">Enroll Now!</a>';
			}
			echo "</li>";
		}while($row_sort = mysql_fetch_assoc($sort));
	} else { 
		echo "No courses satisfy this condition at present";}
	}
else if (isset($_GET['enrollId'])) {
	//for enrolling courses
	$enroll_query=sprintf("INSERT INTO `enroll_course`(u_id,c_enroll_id,completion_stat) VALUES (%s,%s,0)",GetSQLValueString($_SESSION['MM_UserID'], "int"),GetSQLValueString($_GET['enrollId'], "int"));
	$enroll = mysql_query($enroll_query, $conn) or die(mysql_error());
}else if (isset($_GET['showCourses'])) {
	//for showing courses currently going on
	if($_GET['showCourses']==1) {
		$query_incomplete_courses = sprintf("SELECT * FROM course JOIN enroll_course  ON course.c_id=enroll_course.c_enroll_id where enroll_course.u_id=%s AND approve_status=1 AND completion_stat=0 AND DATE(NOW()) BETWEEN start_date AND end_date",GetSQLValueString($_SESSION['MM_UserID'], "int"));
		$incomplete_courses = mysql_query($query_incomplete_courses, $conn) or die(mysql_error());
		$row_incomplete_courses = mysql_fetch_assoc($incomplete_courses);
		$totalRows_incomplete_courses = mysql_num_rows($incomplete_courses);
		
		if($totalRows_incomplete_courses>0){
			$var=0;
			do {
				echo "<div class='section section_with_padding' id='a".$var."'>";
	            echo "<a href='course_details_stud.php?c_id=".$row_incomplete_courses['c_id']."' ><h1>".$row_incomplete_courses['c_name']."</h1></a>";
	            echo "<div class='half right'>";
                echo '<div class="img_border img_temp"> <a href="course_details_stud.php?c_id='.$row_incomplete_courses['c_id'].'" ><img src="'.$row_incomplete_courses['course_image'].'" alt="image 1" width="200" height="120"/></a></div>';
	            echo '<p><em>'.$row_incomplete_courses['description'].'</em></p>';
				echo '</div>';
				//list all resources of that course
				    
				$query_resources = sprintf("SELECT * FROM `resource` WHERE c_id=%s AND approve_status=1",GetSQLValueString($row_incomplete_courses['c_id'], "int"));
				$resource = mysql_query($query_resources, $GLOBALS['conn']) or die(mysql_error());
				$row_resource = mysql_fetch_assoc($resource);
	    		echo '<div class="half left">';
				echo "<table>";
				do {		
					echo "<tr><td><a href='view_resource.php'>".$row_resource['filename']."</a></td>";
					if($row_resource['download_status']==1){
						echo "<td>";
        				echo '<form  id="form1" name="form1" method="POST" action="download_res.php">';
				        echo '<input name="change" id="change" value="Download" type="submit" />';
						echo '<input type="hidden" name="id" id="id" value="'.$row_resource['r_id'].'"  />';
						echo '<input type="hidden" name="MM_change" value="form1" />';
						echo '</form> </td>';
					}else {
						echo "";
					}
					echo "</tr>";
				}while ($row_resource= mysql_fetch_assoc($resource));
				echo '</table>';
				echo '</div>';
			  
				if ($var ==0) {
					//this is first course, no need to show previous button
				}else {
					$temp=$var-1;
					echo "<a href='#a".$temp."' class='page_nav_btn previous'>Previous</a>";
				}
				if ($var == $totalRows_incomplete_courses-1) {
					//this is last course, no need to show next button	
				}else {
					$temp=$var+1;
					echo "<a href='#a".$temp."' class='page_nav_btn next'>Next</a> ";
				}
				echo "</div>"; //END of  half right
				//echo "</div>"; //END of Services
				$var=$var+1;
			}while($row_incomplete_courses = mysql_fetch_assoc($incomplete_courses));
		} else { 
			echo "No courses satisfy this condition at present";
		}	
	}
}else if (isset($_GET['searchKey']) && isset($_GET['searchType']) ) {
	//search course according to keyword
	if($_GET['searchType']==1) {
		//search by course name
		//$query_search_courses = sprintf("SELECT * FROM `course` LEFT OUTER JOIN (SELECT * FROM `enroll_course` WHERE u_id=%s) AS `temp` ON course.c_id = temp.c_enroll_id WHERE approve_status=1 AND c_name LIKE '%%s%' ORDER BY start_date ASC",GetSQLValueString($_SESSION['MM_UserID'], "int"),GetSQLValueString($_GET['searchKey'], "int"));
		$query_search_courses = "SELECT * FROM `course` LEFT OUTER JOIN (SELECT * FROM `enroll_course` WHERE u_id=".$_SESSION['MM_UserID'].") AS `temp` ON course.c_id = temp.c_enroll_id WHERE approve_status=1 AND c_name LIKE '%".$_GET['searchKey']."%' ORDER BY start_date ASC";
		
	}else if ($_GET['searchType']==2) {
		//search by course stream
		//$query_search_courses = sprintf("SELECT * FROM `course` LEFT OUTER JOIN (SELECT * FROM `enroll_course` WHERE u_id=%s) AS `temp` ON course.c_id = temp.c_enroll_id WHERE approve_status=1 AND c_stream LIKE '%%s%' ORDER BY start_date ASC",GetSQLValueString($_SESSION['MM_UserID'], "int"),GetSQLValueString($_GET['searchKey'], "int"));
		$query_search_courses = "SELECT * FROM `course` LEFT OUTER JOIN (SELECT * FROM `enroll_course` WHERE u_id=".$_SESSION['MM_UserID'].") AS `temp` ON course.c_id = temp.c_enroll_id WHERE approve_status=1 AND c_stream LIKE '%".$_GET['searchKey']."%' ORDER BY start_date ASC";
		
	}
	$search = mysql_query($query_search_courses, $conn) or die(mysql_error());
	$row_search = mysql_fetch_assoc($search);
	$totalRows_search = mysql_num_rows($search);
	if($totalRows_search>0){
		do {
			echo "<li>";
			echo '<a href="course_details_stud.php?c_id='.$row_search['c_id'].'"><img src="'.$row_search['course_image'].'" alt=""/></a>';
			echo '<span><a href="course_details_stud.php?c_id='.$row_search['c_id'].'">'.$row_search['c_name'].'</a> in '.$row_search['c_stream'].'</span><br>';
			echo '<p>'.$row_search['description'].'</p>';
			echo '<p class="dates">Duration : '.$row_search['start_date'].' - '.$row_search['end_date'].'</p>';
			echo '<a href="course_details_stud.php?c_id'.$row_search['c_id'].'" class="details">See Details</a>';
			if (!empty($row_search['u_id'])) {
				//enrolled for course already
				echo '<p class="enrolled">Enrolled!</p>';
			}else {
				//not yet enrolled for course
				echo '<a id="'.$row_search['c_id'].'" class="enroll" onClick="enrollCourse(this); return false;">Enroll Now!</a>';
			}
			echo "</li>";
		}while($row_search = mysql_fetch_assoc($search));
	} else { 
		echo "No courses satisfy this condition at present";
	}
}
?>