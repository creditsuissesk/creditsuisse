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
<html>
<head>
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
$query_incomplete_courses = sprintf("SELECT * FROM course JOIN enroll_course  ON course.c_id=enroll_course.c_id where enroll_course.u_id=%s AND completion_stat=0 AND DATE(NOW()) BETWEEN start_date AND end_date",GetSQLValueString($_SESSION['MM_UserID'], "int"));
$incomplete_courses = mysql_query($query_incomplete_courses, $conn) or die(mysql_error());
$row_incomplete_courses = mysql_fetch_assoc($incomplete_courses);
$totalRows_incomplete_courses = mysql_num_rows($incomplete_courses);


mysql_select_db($database_conn, $conn);
$query_completed_courses = sprintf("SELECT * FROM course JOIN enroll_course ON course.c_id=enroll_course.c_id WHERE enroll_course.u_id=%s AND completion_stat=0 AND DATE(NOW())> end_date",GetSQLValueString($_SESSION['MM_UserID'], "int"));
$completed_courses = mysql_query($query_completed_courses, $conn) or die(mysql_error());
$row_completed_courses = mysql_fetch_assoc($completed_courses);
$totalRows_completed_courses = mysql_num_rows($completed_courses);



mysql_select_db($database_conn, $conn);
$query_get_user_details = sprintf("SELECT * FROM `user` where u_id=%s",GetSQLValueString($_SESSION['MM_UserID'], "int"));
$get_user_details = mysql_query($query_get_user_details, $conn) or die(mysql_error());
$row_get_user_details = mysql_fetch_assoc($get_user_details);
$totalRows_get_user_details = mysql_num_rows($get_user_details);
?>

<title><?php echo $row_get_user_details['f_name'];?></title>
<script src="SpryAssets/SpryTabbedPanels.js" type="text/javascript"></script>
<link href="SpryAssets/SpryTabbedPanels.css" rel="stylesheet" type="text/css">
<link href="css/templatemo_style.css" type="text/css" rel="stylesheet" /> 
<link rel="stylesheet" type="text/css" media="screen" href="css/nav_bar.css" />
<link rel="stylesheet" type="text/css" media="screen" href="css/course_list.css" />

<script type="text/javascript" src="js/jquery.min.js"></script> 
<script type="text/javascript" src="js/jquery.scrollTo-min.js"></script> 
<script type="text/javascript" src="js/jquery.localscroll-min.js"></script> 
<script type="text/javascript" src="js/init.js"></script>  
<link rel="stylesheet" href="css/slimbox2.css" type="text/css" media="screen" />
<style type="text/css">
body,td,th {
	color: #000000;
	font-size: 14px;
}
</style>
<link href="SpryAssets/SpryRating.css" rel="stylesheet" type="text/css">
<script type="text/JavaScript" src="js/slimbox2.js"></script>
<script src="SpryAssets/SpryRating.js" type="text/javascript"></script>
<script language="javascript" type="text/javascript">
function clearText(field)
{
    if (field.defaultValue == field.value) field.value = '';
    else if (field.value == '') field.value = field.defaultValue;
}
</script>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<body>
<?php 
if (isset($_GET['userTabToDisplay'])) {
	if ($_GET['userTabToDisplay']<6) {
		$userTabToDisplay=$_GET['userTabToDisplay'];
	}else {
		$userTabToDisplay=0;
	}
}
else {
	$userTabToDisplay=0;
}
?>

<nav id="headerbar">
	<ul id="headerbar">
		<li id="headerbar"><a href="userhome.php">Home</a></li>
		<li id="headerbar"><a href="forum_new.php?mode=showmain">Forums</a></li>
		<li id="headerbar"><a href="#"><?php echo $_SESSION['MM_Username'];?></a>
			<ul id="headerbar">
				<li id="headerbar"><a href="userhome.php?userTabToDisplay=5">Profile</a></li>
				<li id="headerbar"><a href="<?php echo $logoutAction ?>">Log Out</a>
				</li>
			</ul>
		</li>
	</ul>
</nav>
<br/>
            
<h1><?php echo $row_get_user_details['f_name'];?>'s home </h1>
<div id="TabbedPanels1" class="TabbedPanels">
  <ul class="TabbedPanelsTabGroup">
    <li class="TabbedPanelsTab" tabindex="0">Recent Activity</li>
        <li class="TabbedPanelsTab" tabindex="0">Browse Courses</li>
    <li class="TabbedPanelsTab" tabindex="0">Your Courses</li>
    <li class="TabbedPanelsTab" tabindex="0">Completed Courses</li>
    <li class="TabbedPanelsTab" tabindex="0">Recommended</li>
    <li class="TabbedPanelsTab" tabindex="0">Enrollmarks</li>
    <li class="TabbedPanelsTab" tabindex="0">Profile</li>
  </ul>
  <div class="TabbedPanelsContentGroup">
    <div class="TabbedPanelsContent">Content 1</div>
	<div class="TabbedPanelsContent">
    <!--- browse course tab --->
    <div class="course-wrapper">
	    	<div class="course-content-wrapper">
			    <div class="course-content">
				<div class="middle">
						<div class="container">
							<main class="content">
                            <div id="course">
			<div>	
				<div class="first">
					<h2>Courses</h2>
					<ul>
						<li>
							<a href="index.php"><img src="images/gallery/01.jpg" alt=""/></a>
							<span><a href="index.php">Donec Nisl Justo</a></span><br>
							<p>7 Days &amp; 3 Days at Aliquam iaculis velit</p>
							<a href="index.php" class="details">See Details</a>
							<a href="index.php" class="Enroll">Enroll Now!</a>
						</li>
						<li>
							<a href="index.php"><img src="images/gallery/05.jpg" alt=""/></a>
							<span><a href="index.php">Pellentesque</a></span>
							<p>Maecenas gravida lacus mauris, at interdum ligula</p>
							<a href="index.php" class="details">See Details</a>							
							<a href="index.php" class="Enroll">Enroll Now!</a>
						</li>
						<li>
							<a href="index.php"><img src="images/gallery/04.jpg" alt=""/></a>
							<span><a href="index.php">QUISQUE</a></span>
							<p>Pellentesque molestie arcu vitae lectus</p>
							<a href="index.php" class="details">See Details</a>							
							<a href="index.php" class="Enroll">Enroll Now!</a>
						</li>
						<li>
							<a href="index.php"><img src="images/gallery/03.jpg" alt=""/></a>
							<span><a href="index.php">ODIOLOREM</a></span>
							<p>Nullam viverra nisi et elit pretium venenatis</p>
							<a href="index.php" class="details">See Details</a>							
							<a href="index.php" class="Enroll">Enroll Now!</a>
						</li>
					</ul>
					<a href="index.php">View all</a>
				</div></div></div>
							</main><!-- .content -->
						</div><!-- .container-->

						<aside class="left-sidebar">
                        sort by and this is some long message to test the width
						</aside><!-- .left-sidebar -->
                </div>
    </div></div></div> <!--- course divs closing --->
    
    </div> <!--- this div ends browse courses tab --->
    <div class="TabbedPanelsContent">
        <?php if ($totalRows_incomplete_courses>0) {?>
   		<?php $var=0; ?>
		<div id="templatemo_main_wrapper">
			<div id="templatemo_main"> 
		    	<div id="content"> 
				    <?php do { ?>
	          		<?php echo  "<div class='section section_with_padding' id='a".$var."'>";?>
	                <h1><?php echo $row_incomplete_courses['c_name']?></h1> 
	                <div class="half right">
                    <div class="img_border img_temp"> <img src="<?php echo $row_incomplete_courses['course_image']; ?>" alt="image 1" /></div>
	                	<p><em><?php echo $row_incomplete_courses['description']?></em></p>            
                    <?php //list all resources of that course    
					$query_resources = sprintf("SELECT * FROM `resource` WHERE c_id=%s AND approve_status=1",GetSQLValueString($row_incomplete_courses['c_id'], "int"));
		$resource = mysql_query($query_resources, $GLOBALS['conn']) or die(mysql_error());
		$row_resource = mysql_fetch_assoc($resource);
					?>
                    </div>
	    			<div class="half left">
					<?php 
						echo "<table>";
						do {
							
							echo "<tr><td><a href='view_resource.php'>".$row_resource['filename']."</a></td>";
							
							if($row_resource['download_status']==1){?>
                            <td>
        <form  id="form1" name="form1" method="POST" action="download_res.php">    
        
        <input name="change" id="change" value="Download" type="submit" >        
        </input>
        <input type="hidden" name="id" id="id" value="<?php echo $row_resource['r_id'];?>"  />
        
        <input type="hidden" name="MM_change" value="form1" />
        </form> </td>
        <?php }else echo "";
							echo "</tr>";
						}while ($row_resource= mysql_fetch_assoc($resource));
					?></table>
	                    </div>
                     
    		            <?php 
							if ($var ==0) {
								//echo "<a href='#a".$row_courses['c_id']."' class='page_nav_btn previous'>Previous</a>";
			                }else {
								$temp=$var-1;
								echo "<a href='#a".$temp."' class='page_nav_btn previous'>Previous</a>";
			                }
							if ($var == $totalRows_incomplete_courses-1) {
							}else {
								$temp=$var+1;
								echo "<a href='#a".$temp."' class='page_nav_btn next'>Next</a> ";
							}
		                ?>
        	       	<!---</div> ---> <!---END of  half right --->
	            <?php echo "</div>"; ?> <!-- END of Services -->
    	        <?php $var=$var+1; ?>
			    <?php } while ($row_incomplete_courses = mysql_fetch_assoc($incomplete_courses)); ?>     
                </div>
			</div>
	    </div>
        <?php }
		else {
			echo "You haven't enrolled for any courses yet!";
		}
		?>
    </div>
    <div class="TabbedPanelsContent">
    <?php if ($totalRows_completed_courses>0) {?>
    <?php $var=0; ?>
		<div id="templatemo_main_wrapper">
			<div id="templatemo_main"> 
		    	<div id="content">
                
				    <?php do { ?>
	          		<?php echo  "<div class='section section_with_padding' id='a".$row_completed_courses['c_id']."'>";?>
	                <h1><?php echo $row_completed_courses['c_name']?></h1> 
	                <div class="half left">
	                	<p><em><?php echo $row_completed_courses['description']?></em></p>
                        
	                </div>
	    			<div class="half right">
	                	<div class="img_border img_nom"> <a href="#gallery"><img src="images/templatemo_image_01.jpg" alt="image 1" /></a>	
	                    </div>
                     
    		            <?php 
							if ($var ==0) {
								//echo "<a href='#a".$row_courses['c_id']."' class='page_nav_btn previous'>Previous</a>";
			                }else {
								$temp=$row_completed_courses['c_id']-1;
								echo "<a href='#a".$temp."' class='page_nav_btn previous'>Previous</a>";
			                }
							if ($var == $totalRows_completed_courses-1) {
							}else {
								$temp=$row_completed_courses['c_id']+1;
								echo "<a href='#a".$temp."' class='page_nav_btn next'>Next</a> ";
							}
		                ?>
        	       	</div> <!---END of  half right --->
	            <?php echo "</div>"; ?> <!-- END of Services -->
    	        <?php $var=$var+1; ?>
			    <?php } while ($row_completed_courses = mysql_fetch_assoc($completed_courses)); ?> 
                    
                </div>
			</div>
	    </div>
        <?php }
		else {
			echo "You haven't completed any courses yet!";
		}
		?>
        
    </div>
    <div class="TabbedPanelsContent">Content 4</div>
    <div class="TabbedPanelsContent">Content 5</div>
    <div class="TabbedPanelsContent">Content 6</div>
  </div>
</div>
<p><br />
  
<script type="text/javascript">
var TabbedPanels1 = new Spry.Widget.TabbedPanels("TabbedPanels1",{defaultTab:<?php echo ($userTabToDisplay);?>});
</script>
</body>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "student";
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

$MM_restrictGoTo = "login.php";
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
</html>
<?php
mysql_free_result($incomplete_courses);

mysql_free_result($get_user_details);
?>
