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

$MM_restrictGoTo = "/dreamweaver/index.php#login";
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
	  ?>
<?php

$colname_course_details = "-1";
if (isset($_GET['c_id'])) {
  $colname_course_details = $_GET['c_id'];
}
$value=GetSQLValueString($colname_course_details, "int");
mysql_select_db($database_conn, $conn);
$query_course_details = "SELECT a_stat,c_id,c_name,c_stream,inserted_on,course.u_id,approve_status,course_image,avg_rating,description,enroll_course.u_id as u_enroll_id,enroll_course.marks,enroll_course.rating,c_enroll_id,completion_stat,DATE_FORMAT(start_date,'%d-%m-%Y') AS date_start,DATE_FORMAT(end_date,'%d-%m-%Y') AS date_end FROM `course` LEFT OUTER JOIN `enroll_course` ON course.c_id=enroll_course.c_enroll_id AND enroll_course.u_id=".$_SESSION['MM_UserID']." WHERE c_id =".$value;
$course_details = mysql_query($query_course_details, $conn) or die(mysql_error());
$row_course_details = mysql_fetch_assoc($course_details);
$totalRows_course_details = mysql_num_rows($course_details);

mysql_select_db($database_conn, $conn);
$query_resource = sprintf("SELECT * FROM `resource` NATURAL JOIN `resource_type` WHERE c_id=%s ",GetSQLValueString($_GET['c_id'], "int"));
$resource = mysql_query($query_resource, $conn) or die(mysql_error());
$row_resource = mysql_fetch_assoc($resource);
$totalRows_resource = mysql_num_rows($resource);

mysql_select_db($database_conn, $conn);
$query_author_details = sprintf("SELECT * FROM user WHERE u_id = %s", GetSQLValueString($row_course_details['u_id'], "int"));
$author_details = mysql_query($query_author_details, $conn) or die(mysql_error());
$row_author_details = mysql_fetch_assoc($author_details);
$totalRows_author_details = mysql_num_rows($author_details);

$query_no_of_users = sprintf("SELECT * FROM `enroll_course` WHERE c_enroll_id=%s ",GetSQLValueString($_GET['c_id'], "int"));
$no_of_users = mysql_query($query_no_of_users, $conn) or die(mysql_error());
$row_no_of_users = mysql_fetch_assoc($no_of_users);
$totalRows_no_of_users = mysql_num_rows($no_of_users);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $row_course_details['c_name']; ?></title>
<meta name="keywords" content="tech layer, free template, one page layout" />
<meta name="description" content="Tech Layer is free one-page template layout by templatemo.com using colorful navigations and darkgray background." />
<link href="css/course_details_stud.css" type="text/css" rel="stylesheet" /> 
<script type="text/javascript" src="js/jquery.min.js"></script> 
<script type="text/javascript" src="js/jquery.scrollTo-min.js"></script> 
<script type="text/javascript" src="js/jquery.localscroll-min.js"></script> 
<script type="text/javascript" src="js/init.js"></script>  
<script type="text/javascript" src="js/course_details_stud.js"></script> 
<link rel="stylesheet" href="css/slimbox2.css" type="text/css" media="screen" /> 
<link rel="stylesheet" href="css/course_list.css" type="text/css" media="screen" /> 
<link rel="stylesheet" type="text/css" media="screen" href="css/nav_bar.css" />
<script type="text/JavaScript" src="js/slimbox2.js"></script> 

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
    
<script src="./js/jquery.rateit.js" type="text/javascript"></script>
<link href="./css/rateit.css" rel="stylesheet" type="text/css">
    
<script language="javascript" type="text/javascript">
function clearText(field)
{
    if (field.defaultValue == field.value) field.value = '';
    else if (field.value == '') field.value = field.defaultValue;
}

<?php if(!empty($row_course_details['u_enroll_id']) && isset($_GET['show_res'])){ 
	//if user is enrolled and showResource is set then open resource popup after loading page
	$query_get_resource= sprintf("SELECT * FROM `resource` WHERE r_id=%s",GetSQLValueString($_GET['show_res'], "int"));
	$get_resource = mysql_query($query_get_resource, $conn) or die(mysql_error());
	$row_get_resource = mysql_fetch_assoc($get_resource);
	$filetype="";
	if(strpos($row_get_resource['file_type'],"application/pdf")!==false) {
		$filetype="pdf";
	}else if (strpos($row_get_resource['file_type'],"image")!==false) {
		$filetype="image";
	}else if (strpos($row_get_resource['file_type'],"video")!==false) {
		$filetype="video";
	}else if (strpos($row_get_resource['file_type'],"powerpoint")!==false || strpos($row_get_resource['file_type'],"presentation")!==false) {
						echo "presentation";
	}
	?>
	$(document).ready(function(){showResource(<?php echo $_GET['show_res'];?>,'<?php echo $filetype;?>','<?php echo $row_get_resource['filename'];?>')});
	
<?php }?>

</script>
</head> 
<body> 
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

<div id="templatemo_header_wrapper">
	<div id="templatemo_header">
    	<div id="site_title"><?php echo $row_course_details['c_name']; ?></div>
        <a class="templatemo_header_bg" href=""><img src="images/header.png"/></a>
    </div>
</div>

<div id="templatemo_main_wrapper">
	<div id="templatemo_main">
		<div id="content"> 
            <div id="home" class="section">
                <div class="home_box left">
                	<div class="row1 box box1">
                    	<div class="box_with_padding">
                        	<h2><a href="#about">About</a></h2>
                         	<?php if (strlen($row_course_details['description'])>105) {
							echo substr($row_course_details['description'],0,105)."....";
							}else {
								echo $row_course_details['description'];
							}?>
						</div>
                    </div>
                    <div class="row1 box2">
                    	<div class="box_with_padding">
                        	<?php if(!empty($row_course_details['u_enroll_id'])&&$row_course_details['a_stat']==1){
								//user has enrolled and approved, show him link to resources
								echo '<h2><a href="#resources">Resources</a></h2>';
							}else if(!empty($row_course_details['u_enroll_id'])&&$row_course_details['a_stat']==0){
								//user has enrolled but yet to be approved
								echo '<h2 style="cursor:pointer;"><a onclick="alert(\'You have enrolled for the course.But you are yet to be approved to access resources and evaluation!\'); return false;">Resources</a></h2>';
								}
							else
							 if(!empty($row_course_details['u_enroll_id'])&&$row_course_details['a_stat']==0){
								//user has enrolled but rejected
								echo '<h2 style="cursor:pointer;"><a onclick="alert(\'You have enrolled for the course.But you have been rejected to take the course!\'); return false;">Resources</a></h2>';
								}
							else
							{
								//user has not enrolled, disable the link
								echo '<h2 style="cursor:pointer;"><a onclick="alert(\'You have not enrolled for the cource. Please enroll to access resources and evaluation!\'); return false;">Resources</a></h2>';
							}
							?>
                            Browse through the resources of this course and learn!
						</div>
                    </div>
                    <div class="row1 box3">
                    	<div class="box_with_padding">
                        	<?php if(!empty($row_course_details['u_enroll_id'])&&$row_course_details['a_stat']==1){
								//user has enrolled and approved, show him link to resources
								echo '<h2><a href="#evaluation">Evaluation</a></h2>';
							}else if(!empty($row_course_details['u_enroll_id'])&&$row_course_details['a_stat']==0){
								//user has enrolled but yet to be approved
								echo '<h2 style="cursor:pointer;"><a onclick="alert(\'You have enrolled for the course.But you are yet to be approved to access resources and evaluation!\'); return false;">Resources</a></h2>';
								}
							else
							 if(!empty($row_course_details['u_enroll_id'])&&$row_course_details['a_stat']==0){
								//user has enrolled but rejected
								echo '<h2 style="cursor:pointer;"><a onclick="alert(\'You have enrolled for the course.But you have been rejected to take the course!\'); return false;">Evaluation</a></h2>';
								}
							else
							{
								//user has not enrolled, disable the link
								echo '<h2 style="cursor:pointer;"><a onclick="alert(\'You have not enrolled for the cource. Please enroll to access resources and evaluation!\'); return false;">Evaluation</a></h2>';
							}
							?>
                             Prepared to get evaluated? Take the test now!
						</div>
                    </div>
                    <div class="row1 box4">
                    	<div class="box_with_padding">
                        	<h2><a href="#contact">Contact</a></h2>
                            Got any queries? Contact the faculty!
						</div>
                    </div>
                </div>
 				<div class="home_box right">
                	<div class="row1 box5">
                    	<div class="box_with_padding">
                        	<h5>Course Overview</h5>
                            Stream : <?php echo $row_course_details['c_stream']; ?><br />
                            Duration : <?php echo $row_course_details['date_start']." to ".$row_course_details['date_end'];?>
						</div>
                    </div>
                    <div class="row2" id="home_gallery">
                    	<img src="<?php echo $row_course_details['course_image'];?>" alt="image 1" width="400" height="240"/>
					</div>
                    <div class="row1 box6">
                        <div class="box_with_padding">
                        	<h5>Course statistics</h5>
                            There are <?php echo $totalRows_no_of_users;?> users enrolled!
                            <div class="clear"></div>
                        </div>
                        
                    </div>
                </div>
            </div> <!-- END of Home -->
            
            <div class="section section_with_padding" id="about"> 
                <h1>About</h1>
                <div class="half left">
                	<h4>The course</h4>
                    <div class="img_border img_course"> <a href="#gallery"><img src="<?php echo $row_course_details['course_image'] ?>" width="200" height="120" alt="No course image available" /></a>	
                  </div>
                    <p><em><?php echo $row_course_details['description'];?></em></p>
                    <?php if (empty($row_course_details['u_enroll_id'])) {
                    	echo '<form id="enrollform">';
                    	echo '<input id="submit" type="button" class="buttom" value="Enroll!" onclick="enrollCourse('.$row_course_details['c_id'].')" />';
                    	echo '</form>';
					}else {
						echo '<form id="recoform">';
                    	echo '<input id="submit" type="button" class="buttom" value="Recommend to others" onclick="recoCourse('.$row_course_details['c_id'].')" />';
                    	echo '</form>';
					}if($row_course_details['marks']!=-1 and $row_course_details['a_stat']==1){
						//student has taken the test and to be shown rating widget.
						?>
						<br>Rate this course : 
						<div class= "rateit bigstars" data-rateit-starwidth="32" data-rateit-starheight="32" id="rateit"data-rateit-value="<?php echo $row_course_details['rating'];?>" data-rateit-ispreset="true">
						</div>
						<div>
						<span style="margin-left:150px;" id="hover"></span>
						</div>
						<script type="text/javascript">
						var ratingType=['Poor','Fair','Good','Very Good','Excellent!'];
						$('#rateit').bind('over', function (event,value) { 
							$(this).attr('title', value);
							if(value==null) {
								$('#hover').text("");
							}else {
								$('#hover').text(ratingType[value-1]);
							}
						});
						$('#rateit').on('beforerated', function (e, value) {
         					if (!confirm('Are you sure you want to rate this course '+ ratingType[Math.floor(value)-1]+ "?")) {
				              e.preventDefault();
							}
						});
						$('#rateit').on('beforereset', function (e) {
							if (!confirm('Are you sure you want to reset the rating?')) {
								e.preventDefault();
							}
						});
						$("#rateit").bind('rated', function (event, value) { 
							var rateReturn=rateCourse(<?php echo $row_course_details['c_id'];?>,value);
						});
						$("#rateit").bind('reset', function () { rateCourse(<?php echo $row_course_details['c_id'];?>,0); });
						</script>
					<?php 
					}
					?>
				</div>
                <div class="half right">
                	<h4>The author</h4>
					<div class="img_border img_author"> <a href="#gallery"><img src="<?php echo $row_author_details['photo'] ?>" width="120" height="150" alt="No author image available" /></a></div>
                    <p class="inline_details">Name : <em><?php echo $row_author_details['f_name']." ".$row_author_details['l_name'];?></em></p>
                    <p class="inline_details">Degree of Specialization : <em><?php echo $row_author_details['degree'];?></em></p>
                    <p class="inline_details">Institute of Specialization : <em><?php echo $row_author_details['institute'];?></em></p>
                    <p class="inline_details">Contact : <em><?php echo $row_author_details['contact_no'];?></em></p>
				</div>
                <a href="#home" class="home_btn">home</a> 
                <a href="#home" class="page_nav_btn previous">Previous</a>
                <?php //if user is enrolled then go next to resources else go next to contact
				if (!empty($row_course_details['u_enroll_id'])&&$row_course_details['a_stat']==1) { 
					echo '<a href="#resources" class="page_nav_btn next">Next</a>';
				}else {
					echo '<a href="#contact" class="page_nav_btn next">Next</a>';
				}  ?>
            </div> <!-- END of About -->
            
            <?php if (!empty($row_course_details['u_enroll_id'])&&$row_course_details['a_stat']==1) { 
				//show the two tabs only if user has enrolled
			?>
            <div class="section section_with_padding" id="resources"> 
                <h1>Resources</h1>
                <?php //get all resources of the course and show container only if resources are available 
					$query_get_resources=sprintf("SELECT * FROM `resource` WHERE c_id=%s AND approve_status=1",GetSQLValueString($_GET['c_id'],"int"));
					$get_resources = mysql_query($query_get_resources, $conn) or die(mysql_error());
					$row_get_resources = mysql_fetch_assoc($get_resources);
					$totalRows_get_resources = mysql_num_rows($get_resources);
					//show container only if resources are uploaded
					if($totalRows_get_resources>0) {
						
				?>
				
                <div class="container">
				<main class="course-content-exam" style="background-color:#FFF;border-radius:5px;">
				<div id="course" style="height:300px;position:relative;padding:0px;">
				<div style="max-height:100%;overflow:auto;"><div class="evaluation">
				<ul id="resourcelist">
                <?php 
                do {
					echo '<li><table><tr><td><b><div id="'.$row_get_resources['r_id'].'" onclick="showResource('.$row_get_resources['r_id'].',\'';
					if(strpos($row_get_resources['file_type'],"application/pdf")!==false) {
						echo "pdf";
					}else if (strpos($row_get_resources['file_type'],"image")!==false) {
						echo "image";
					}else if (strpos($row_get_resources['file_type'],"video")!==false) {
						echo "video";
					}else if (strpos($row_get_resources['file_type'],"powerpoint")!==false || strpos($row_get_resources['file_type'],"presentation")!==false) {
						echo "presentation";
					}
					echo '\',\''.$row_get_resources['filename'].'\');"><div id="rname" title="View this resource" style="cursor:pointer;">'.$row_get_resources['filename'].'</div></div></b></td>';
					if($row_get_resources['download_status']==1) {
						echo '<td><a href="download_res.php?id='.$row_get_resources['r_id'].'"><img src="images/Download-icon.png" title="Download this resource" style="cursor:pointer;width:35px;height:35px;margin:0px;"/></a></td>';
					}
					//show voting icon
					echo '<td><img src="images/rating-icon.png" title="Rate/Bookmark this resource"style="cursor:pointer;" width="25px" height="25px" onclick="showResourceRating('.$row_get_resources['r_id'].',\''.$row_get_resources['filename'].'\');"/></td>';
					
					//show QR code icon
					echo '<td><img src="images/qr.png" title="Get QR Code of this resource" style="cursor:pointer;" width="25px" height="25px" onclick="showQR('.$row_get_resources['r_id'].',\''.$row_get_resources['filename'].'\');"/></td>';
					echo '</tr></table></li>';
                }while($row_get_resources = mysql_fetch_assoc($get_resources));
				?>
                
                </ul>
				</div></div></div>
				</main><!-- .content -->
				</div><!---container --->
                <?php } else { //no resource exists. Notify so.
					echo "No resources have been uploaded by author. Please check again later.";
				}?>
                <a href="#home" class="home_btn">home</a> 
                <a href="#about" class="page_nav_btn previous">Previous</a>
                <a href="#evaluation" class="page_nav_btn next">Next</a> 
            </div> <!-- END of resources -->
            

            <div class="section section_with_padding" id="evaluation"> 
               	<h1>Evaluation</h1>
                <div id="content-holder">
				<?php 
				//check if there are any available tests. Show options accordingly.
				$query_test_exists= sprintf("SELECT * FROM `course_eval` WHERE c_id_eval=%s",GetSQLValueString($_GET['c_id'], "int"));
				$check_test_exists = mysql_query($query_test_exists, $conn) or die(mysql_error());
				$row_test_exists = mysql_fetch_assoc($check_test_exists);
				$totalRows_test_exists = mysql_num_rows($check_test_exists);
				if($totalRows_test_exists>0){
					//test is available. check if candidate has already taken the test.
					$query_test_check=sprintf("SELECT * FROM `enroll_course` WHERE u_id=%s AND c_enroll_id=%s AND a_stat=1",GetSQLValueString($_SESSION['MM_UserID'], "int"),GetSQLValueString($_GET['c_id'], "int"));
					$check_test=mysql_query($query_test_check,$conn) or die(mysql_error());
					$row_check_test=mysql_fetch_assoc($check_test);
					if($row_check_test['marks']==-1) {
					//Test has not been taken. Show options of taking test.
					echo "There is a test for this course available!<br>";
					echo '<form id="enrollform" style="padding-top:10px;">';
                    echo '<input id="submit" type="button" class="buttom" value="Take Test!" onclick="takeTest();" />';
					echo '<input id="hiddenId" type="hidden" value="'.$_GET['c_id'].'" />';
                    echo '</form>';
					}else {
						//test has already been taken. Show user the marks.
						echo "You have already taken test and have secured ".$row_check_test['marks']."/".$totalRows_test_exists*10;
					}
				}else {
					echo "Sorry, no test has been created for this course by author yet!";
				}
				?>
            	</div><!--- end of content-holder--->
                <a href="#home" class="home_btn">home</a> 
                <a href="#resources" class="page_nav_btn previous">Previous</a>
                <a href="#contact" class="page_nav_btn next">Next</a>
            </div> <!-- END of evaluation -->
            
            
            <?php } //end of the if of only-enrolled tabs ?>
            
            <div class="section section_with_padding" id="contact"> 
                <h1>Contact</h1> 
                
                <div class="half left">
                    <h4><?php echo $row_author_details['f_name']." ".$row_author_details['l_name'];?></h4>
                                     
                 	Email: <?php echo $row_author_details['u_name'];?> | Phone: <?php echo $row_author_details['contact_no'];?><br />

                    <div class="clear h20"></div>
                
                
                <a href="#home" class="home_btn">home</a> 
                <?php //if user is enrolled, previous page is evaluation, else is about
				if (!empty($row_course_details['u_enroll_id'])&&$row_course_details['a_stat']==1) {
					echo '<a href="#evaluation" class="page_nav_btn previous">Previous</a>';
				}else {
					echo '<a href="#about" class="page_nav_btn previous">Previous</a>';
				} ?>
                <a href="#home" class="page_nav_btn next">Next</a>
            	</div> <!-- END of Contact -->
                
                <div class="half right">
                    <h4>Quick Contact</h4>
                    <p>If you have any queries, write to the author.</p>
                    <div id="contact_form">
					<form>
					<label for="author">Name:</label> <input type="text" id="namefield" name="author" class="required input_field" />
					<div class="clear"></div>
					<label for="text">Message:</label> <textarea id="textfield" name="text" rows="0" cols="0" class="required"></textarea>
					<input type="button" onclick="mailer(); return false;" class="submit_btn float_l" value="Send" />
					</form>
					</div>
                </div>
        </div> 
    </div>
</div>

<div id="templatemo_footer_wrapper">
	<div id="templatemo_footer">
    	<p>Copyright © 2014 <a href="#">Shaarad and Kunal</a> | <a rel="nofollow" href="index.php">Virtual Library</a> by <a href="https://www.facebook.com/kunal.shah.96780" target="_blank" rel="nofollow">Kunal and Shaarad</a></p>
    </div>
</div>

</div>
</body> 
</html>
<?php 
mysql_free_result($author_details);
mysql_free_result($course_details);
mysql_free_result($resource);
?>