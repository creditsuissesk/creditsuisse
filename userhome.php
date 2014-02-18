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
$query_completed_courses = sprintf("SELECT * FROM course JOIN enroll_course ON course.c_id=enroll_course.c_enroll_id WHERE enroll_course.u_id=%s AND completion_stat=1 AND DATE(NOW())> end_date",GetSQLValueString($_SESSION['MM_UserID'], "int"));
$completed_courses = mysql_query($query_completed_courses, $conn) or die(mysql_error());
$row_completed_courses = mysql_fetch_assoc($completed_courses);
$totalRows_completed_courses = mysql_num_rows($completed_courses);



/*mysql_select_db($database_conn, $conn);
$query_get_user_details = sprintf("SELECT * FROM `user` where u_id=%s",GetSQLValueString($_SESSION['MM_UserID'], "int"));
$get_user_details = mysql_query($query_get_user_details, $conn) or die(mysql_error());
$row_get_user_details = mysql_fetch_assoc($get_user_details);
$totalRows_get_user_details = mysql_num_rows($get_user_details);*/


$query_resource = sprintf("SELECT c_id,c_name,c_stream,start_date,end_date,avg_rating FROM course JOIN enroll_course ON c_id=c_enroll_id where course.approve_status=1 and enroll_course.u_id=%s",GetSQLValueString($_SESSION['MM_UserID'], "int"));
$new_resource = mysql_query($query_resource, $conn) or die(mysql_error());
$row_new_resource = mysql_fetch_assoc($new_resource);
$totalRows_new_resource = mysql_num_rows($new_resource);

mysql_select_db($database_conn, $conn);
$query_author_details = sprintf("SELECT * FROM user WHERE u_id = %s", GetSQLValueString($_SESSION['MM_UserID'], "int"));
$author_details = mysql_query($query_author_details, $conn) or die(mysql_error());
$row_author_details = mysql_fetch_assoc($author_details);
$totalRows_author_details = mysql_num_rows($author_details);
?>

<title><?php echo /*$row_get_user_details['f_name']*/$_SESSION['MM_f_name'];?></title>
<script src="SpryAssets/SpryTabbedPanels.js" type="text/javascript"></script>
<link href="SpryAssets/SpryTabbedPanels.css" rel="stylesheet" type="text/css">
<link href="css/templatemo_style.css" type="text/css" rel="stylesheet" /> 
<link rel="stylesheet" type="text/css" media="screen" href="css/nav_bar.css" />
<link rel="stylesheet" type="text/css" media="screen" href="css/course_list.css" />
<link rel="stylesheet" type="text/css" media="screen" href="css/resource_list.css" />

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

<script>
function sortCourses(str,refreshEnrolled)
{
if (str=="")
  {
  document.getElementById("txtHint").innerHTML="";
  return;
  }
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
    document.getElementById("courselist").innerHTML=xmlhttp.responseText;
		if(refreshEnrolled==1) {
			showEnrolledCourses();
		}
    }
  }
xmlhttp.open("GET","show_courses.php?sortType="+str,true);
xmlhttp.send();
}

function showEnrolledCourses()
{
/*if (str=="")
  {
  document.getElementById("txtHint").innerHTML="";
  return;
  }*/
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
    document.getElementById("content").innerHTML=xmlhttp.responseText;
    }
  }
xmlhttp.open("GET","show_courses.php?showCourses=1",true);
xmlhttp.send();
}

function enrollCourse(ele) {
	var r=confirm("Are you sure you want to enroll for this course?");
	if (r==true) {
		if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
		  xmlhttp=new XMLHttpRequest();
		} else {// code for IE6, IE5
		  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  		}
		xmlhttp.onreadystatechange=function()
		{
		  if (xmlhttp.readyState==4 && xmlhttp.status==200) {
		    //document.getElementById("courselist").innerHTML=xmlhttp.responseText;
				var e = document.getElementById("sortdropdown");
				var strUser = e.options[e.selectedIndex].value;
				sortCourses(strUser,0);
			}
			else {
				ele.innerHTML='<a id="'+ele.id+'" class="enroll">Enrolling...</a>';
			}
		}
		xmlhttp.open("GET","show_courses.php?enrollId="+ele.id,true);
		xmlhttp.send();
	}
}

function searchCourses() {
	var e = document.getElementById("searchdropdown");
	var searchtype = e.options[e.selectedIndex].value;
	var searchkey = document.getElementById("searchword").value;
	if (searchkey=="") {
		var e = document.getElementById("sortdropdown");
			var strUser = e.options[e.selectedIndex].value;
			sortCourses(strUser,1);
	} else {
		if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
	  		xmlhttp=new XMLHttpRequest();
	  	} else {// code for IE6, IE5
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange=function() {
			if (xmlhttp.readyState==4 && xmlhttp.status==200) {
		    	document.getElementById("courselist").innerHTML=xmlhttp.responseText;
			}
		}
		xmlhttp.open("GET","show_courses.php?searchType="+searchtype+"&searchKey="+searchkey,true);
		xmlhttp.send();
	}
}
</script>
<script>
function sortresources(str,refreshEnrolled)
{
	
if (str=="")
  {
  document.getElementById("txtHint").innerHTML="";
  return;
  }
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
    document.getElementById("resourcelist").innerHTML=xmlhttp.responseText;
		if(refreshEnrolled==1) {
			showEnrolledresources();
		}
    }
  }
xmlhttp.open("GET","show_resources.php?sortType="+str,true);
xmlhttp.send();

}
function searchresources() {
	var e = document.getElementById("searchdropdown1");
	var searchtype = e.options[e.selectedIndex].value;
	var searchkey = document.getElementById("searchword1").value;
	if (searchkey=="") {
		var e = document.getElementById("sortdropdown1");
			var strUser = e.options[e.selectedIndex].value;
			sortresources(strUser,1);
	} else {
		if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
	  		xmlhttp=new XMLHttpRequest();
	  	} else {// code for IE6, IE5
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange=function() {
			if (xmlhttp.readyState==4 && xmlhttp.status==200) {
		    	document.getElementById("resourcelist").innerHTML=xmlhttp.responseText;
			}
		}
		xmlhttp.open("GET","show_resources.php?searchType="+searchtype+"&searchKey="+searchkey,true);
		xmlhttp.send();
	}
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
            
<h1><?php echo /*$row_get_user_details['f_name']*/$_SESSION['MM_f_name'];?>'s home </h1>
<div id="TabbedPanels1" class="TabbedPanels">
  <ul class="TabbedPanelsTabGroup">
    <li class="TabbedPanelsTab" tabindex="0">Browse Resources</li>
    <li class="TabbedPanelsTab" tabindex="0">Browse Courses</li>
    <li class="TabbedPanelsTab" tabindex="0">Your Courses</li>
    <li class="TabbedPanelsTab" tabindex="0">Completed Courses</li>
    <li class="TabbedPanelsTab" tabindex="0">Recommended</li>
  <!--  <li class="TabbedPanelsTab" tabindex="0">Enroll marks</li> -->
    <li class="TabbedPanelsTab" tabindex="0">Profile</li>
    <li class="TabbedPanelsTab" tabindex="0">Upload Papers</li>
  </ul>
  <div class="TabbedPanelsContentGroup">
    <div class="TabbedPanelsContent">
     <div class="Resource-wrapper">
	    	<div class="resource-content-wrapper">
			    <div class="resource-content">
				<div class="middle">
						<div class="container">
							<main class="resource-content">
                            <div id="resource">
				<div>	
				<div class="first">
					<h2>Resources</h2>
					<ul id="resourcelist">
					<!--- Resources appear here dynamically --->	
					</ul>
					<!--<a href="index.php">View all</a>-->
				</div></div></div>
							</main><!-- .content -->
						</div><!-- .container-->

						<aside class="left-sidebar">
                        Sort by:
                        <form action=""> 
						<select id="sortdropdown1" name="use" onChange="sortresources(this.value,0)">
						<option value="1" selected>Your Bookmarks</option>
						<option value="2">Most Popular</option>
                        <option value="3">Latest</option>
						</select>
						</form>
                        <!---<script> $(document).ready(function(){sortresources(1,1);});</script> --->
                        
                        Search resource by:
                        <form action=""> 
						<select id="searchdropdown1" name="use" onChange="sortresources(this.value,0)">
						<option value="1" selected>File Name</option>
						<option value="2">Course Name</option> 
						</select> <br>
                        <input type="text" id="searchword1" style="margin-top:10px;" onKeyPress="searchresources();"> 
						</form>
                        
						</aside><!-- .left-sidebar -->
                </div>
    		</div>
    	</div> <!--- resource divs closing --->
    
    </div> <!--- this div ends browse resources tab --->
    
    </div>
	<div class="TabbedPanelsContent">
    <!--- browse course tab --->
    <div class="course-wrapper">
	    	<div class="course-content-wrapper">
			    <div class="course-content">
				<div class="middle">
						<div class="container">
							<main class="course-content">
                            <div id="course">
				<div>	
				<div class="first">
					<h2>Courses</h2>
					<ul id="courselist">
					<!--- courses appear here dynamically --->	
					</ul>
					<a href="index.php">View all</a>
				</div></div></div>
							</main><!-- .content -->
						</div><!-- .container-->

						<aside class="left-sidebar">
                        Sort by:
                        <form action=""> 
						<select id="sortdropdown" name="users" onChange="sortCourses(this.value,0)">
						<option value="1" selected>Most Popular</option>
						<option value="2">Latest</option>
                        <option value="3">Starting Soon</option>
                        <option value="4">Running Courses</option>
						</select>
						</form>
                        <script> $(document).ready(function(){sortCourses(1,1);sortresources(1,1);});</script>
                        
                        Search course by:
                        <form action=""> 
						<select id="searchdropdown" name="users" onChange="sortCourses(this.value,0)">
						<option value="1" selected>Name</option>
						<option value="2">Stream</option> 
						</select> <br>
                        <input type="text" id="searchword" style="margin-top:10px;" onKeyPress="searchCourses();"> 
						</form>
                        
						</aside><!-- .left-sidebar -->
                </div>
    </div></div></div> <!--- course divs closing --->
    
    </div> <!--- this div ends browse courses tab --->
    <div class="TabbedPanelsContent">
		<div id="templatemo_main_wrapper">
			<div id="templatemo_main"> 
		    	<div id="content"> 
		      	</div>
			</div>
		</div>
	</div>
    <div class="TabbedPanelsContent">
    <?php if ($totalRows_completed_courses>0) {?>
    <?php $var=0; ?>
		<div id="templatemo_main_wrapper">
			<div id="templatemo_main"> 
		    	<div class="content">
                
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
    <!-- start of profile tab-->
    <div class="TabbedPanelsContent">
    <img src="<?php echo $row_author_details['photo'];?>" alt="" height=200 width =300 />
							<p><b>Name : </b><i><?php echo $row_author_details['f_name']." ".$row_author_details['l_name'];?></i></p>
                            <p><b>Degree of specialization : </b><i><?php echo $row_author_details['degree'];?></i></p>
                            <p><b>Institute of Specialization :</b><i> <?php echo $row_author_details['institute'];?></i></p>
                            <p><b>Contact me at : </b><i><?php echo $row_author_details['u_name'];?></i></p>
                            <p><b> About myself:</b> <p style="font-style:italic"><?php echo $row_author_details['about'];?></p></p>
    </div>
    <!-- end of profile tab-->
    <div class="TabbedPanelsContent">
     <!--start of tab upload resource-->
     <div id="New Resource">
      <p>Please enter the Resource details : </p>
      <form id="new_resource" method="POST" action="upload_res.php" enctype="multipart/form-data">
       <p>
          <label for="co_name">Regarding Course* :</label>
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
          <input type="hidden" id= "r_type" name="r_type" value="5">
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
      	   <input name="submit" type="submit" id="submit" onClick="MM_validateForm('co_name','','R','r_name','','R','r_type','','R');return document.MM_returnValue" value="Upload" action="upload_res.php"/>
      <input type="hidden" name="MM_insert" value="form" />
      </form>
      	</div><!--end of tab upload resource-->
      </div>
  </div>
</div>
<p><br />
  
<script type="text/javascript">
var TabbedPanels1 = new Spry.Widget.TabbedPanels("TabbedPanels1",{defaultTab:<?php echo ($userTabToDisplay);?>});
</script>
</body>
</html>
<?php
//mysql_free_result($get_user_details);
?>
