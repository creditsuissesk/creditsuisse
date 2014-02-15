<?php require_once('Connections/conn.php'); ?>
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

$MM_restrictGoTo = "/dreamweaver/login.php";
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
mysql_select_db($database_conn, $conn);
$query_course_details = sprintf("SELECT * FROM course WHERE c_id = %s", GetSQLValueString($colname_course_details, "int"));
$course_details = mysql_query($query_course_details, $conn) or die(mysql_error());
$row_course_details = mysql_fetch_assoc($course_details);
$totalRows_course_details = mysql_num_rows($course_details);

mysql_select_db($database_conn, $conn);
$query_resource = sprintf("SELECT * FROM `resource`NATURAL JOIN `resource_type` WHERE c_id=%s ",GetSQLValueString($_GET['c_id'], "int"));
$resource = mysql_query($query_resource, $conn) or die(mysql_error());
$row_resource = mysql_fetch_assoc($resource);
$totalRows_resource = mysql_num_rows($resource);

mysql_select_db($database_conn, $conn);
$query_author_details = sprintf("SELECT * FROM user WHERE u_id = %s", GetSQLValueString($row_course_details['u_id'], "int"));
$author_details = mysql_query($query_author_details, $conn) or die(mysql_error());
$row_author_details = mysql_fetch_assoc($author_details);
$totalRows_author_details = mysql_num_rows($author_details);

/*mysql_select_db($database_conn, $conn);
$query_other_course = sprintf("SELECT * FROM course WHERE u_id = %s", GetSQLValueString($row_course_details['u_id'], "int"));
$other_course = mysql_query($query_other_course, $conn) or die(mysql_error());
$row_other_course = mysql_fetch_assoc($other_course);
$totalRows_other_course = mysql_num_rows($other_course);
*/
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $row_course_details['c_name'];?></title>
<meta name="keywords" content="single, slider, free templates, website templates, CSS, HTML" />
<meta name="description" content="Single Slider is a free CSS template provided by templatemo.com" />
<link href="css/templatemo_style_co.css?1" rel="stylesheet" type="text/css" />

<script src="js/jquery-1.2.6.min.js" type="text/javascript"></script>
<script src="js/jquery.easing.1.3.js" type="text/javascript"></script>
<script src="js/jquery.kwicks-1.5.1.pack.js" type="text/javascript"></script>

<script type="text/javascript">
	$().ready(function() {  
		$('.kwicks').kwicks({  
			max : 710,  
			spacing : 0,  
			sticky: true,
		});  
	}); 
</script>

<script type="text/javascript" src="js/jquery-1-4-2.min.js"></script> 
<link rel="stylesheet" href="css/slimbox2_co.css" type="text/css" media="screen" /> 
<script type="text/JavaScript" src="js/slimbox2.js"></script> 

</head>

<body>

<div id="templatemo_wrapper">
	<div id="templatemo_main">
	
		<div id="site_title">
			<a href="" title=""  class="site_link" target="_blank"></a>
			<h1><a href=""rel=""></a></h1>			
	  </div>
		
		<div id="templatemo_content">
			<ul class="kwicks">
			
				<li id="home"><span class="header"></span>
					<div class="inner">
						<h2>Welcome to <?php echo $row_course_details['c_name'];?></h2>
						<p>
                        <img src="<?php echo $row_course_details['course_image'];?>" alt="" class="image_fl" height=200 width =300 />
                        </p>
                        <p><em> This course is included in stream <?php echo $row_course_details['c_stream'].". "; echo $row_course_details['description'];?></em></p>
						
						<div class="col_half float_r">
                        
						</div>
					</div>
				</li>
			
				<li id="about"><span class="header"></span>
					<div class="inner">
						<h2>About</h2>
						<img src="<?php echo $row_author_details['photo'];?>" alt="" height=50 width =50 />
							<p><b>Name : </b><i><?php echo $row_author_details['f_name']." ".$row_author_details['l_name'];?></i></p>
                            <p><b>Degree of specialization : </b><i><?php echo $row_author_details['degree'];?></i></p>
                            <p><b>Institute of Specialization :</b><i> <?php echo $row_author_details['institute'];?></i></p>
                            <p><b>Contact him at : </b><i><?php echo $row_author_details['u_name'];?></i></p>
                            <p><b> About himself:</b> <p style="font-style:italic"><?php echo $row_author_details['about'];?></p></p>
                           <!-- other courses ka part
						   <?php //if($totalRows_other_course>0){?>
							<h3>Other Courses by Author</h3>
							<ul class="templatemo_list">
                            <?php //do{?>
								<li><a href="<?php// echo 'course_detail_stud.php?c_id='.$row_other_course['c_id'];?>"><?php //echo $row_other_course['c_name'];?></a></li>
							<?php //}while ($row_other_course = mysql_fetch_assoc($other_course)); ?>	
							</ul>
                            <?php //}else{ echo "";} ?>  -->
					</div>
				</li>
			
				<li id="social"><span class="header"></span>
					<div class="inner">
						<h2>Resources</h2>
						<p><em>Following are the list of Resources for the course</em></p>
						<p style="font-style:italic" style="font-size:24px">
                         <?php if($totalRows_resource>0){?>
                        <ul class="templatemo_list">
                            
							<?php do{?>
								<li><table><tr><td><a href="<?php echo $row_resource['file_location'];?>"><?php echo $row_resource['filename'];?></a></td><?php if($row_resource['download_status']==1){ echo '<td><a href="download_res.php?id="'.$row_resource['r_id'].'"><img src="images/download-icon.png" alt="" height=24 width =24  /></a></td>';}?></tr></table></li>
							
							<?php }while ($row_resource = mysql_fetch_assoc($resource)); ?>	
							
                            </ul>
                            <?php }else{ echo "No Resources for this course have been uploaded. Please Wait for resources to be uploaded. Sorry for inconveniene caused.";} ?>
                        </p>
					</div>
				</li>
			
				<li id="portfolio"><span class="header"></span>
					<div class="inner">
						<h2>Portfolio</h2>
						<ul id="gallery">
							<li><a href="images/portfolio/01-l.jpg" rel="lightbox[portfolio]" title="Curabitur facilisis auctor risus, eget lacinia leo feugiat ac."><img src="images/portfolio/01.jpg" alt="Image 01" /></a></li>
							<li><a href="images/portfolio/02-l.jpg" rel="lightbox[portfolio]" title="Nulla varius laoreet tellus, non volutpat mi iaculis a."><img src="images/portfolio/02.jpg" alt="Image 02" /></a></li>
							<li><a href="images/portfolio/03-l.jpg" class="no_margin_right" rel="lightbox[portfolio]" title="Vestibulum euismod mi et massa volutpat lobortis."><img src="images/portfolio/03.jpg" alt="Image 03" /></a></li>
							<li><a href="images/portfolio/04-l.jpg" rel="lightbox[portfolio]" title="Quisque volutpat nunc a felis lobortis aliquet."><img src="images/portfolio/04.jpg" alt="Image 04" /></a></li>
							<li><a href="images/portfolio/05-l.jpg" rel="lightbox[portfolio]" title="Nullam dictum enim et quam posuere dapibus."><img src="images/portfolio/05.jpg" alt="Image 05" /></a></li>
							<li><a href="images/portfolio/06-l.jpg" class="no_margin_right" rel="lightbox[portfolio]" title="Phasellus condimentum nisi et mi feugiat dapibus."><img src="images/portfolio/06.jpg" alt="Image 06" /></a></li>
						</ul>
					</div>
				</li>
				
				<li id="contact"><span class="header"></span>
					<div class="inner">
						<h2>Contact Information</h2>
						<p>Nullam a tortor est, congue fermentum nisi. Maecenas nulla nulla, lobortis eu volutpat euismod, scelerisque ut dui. Integer luctus tellus ac mi malesuada dignissim.</p>
						<h4>Send us a message!</h4>
						<div id="contact_form"  class="col_w280 float_l">
							<form method="post" name="contact" action="#">
							
								<label for="author">Name:</label> <input type="text" id="author" name="author" class="required input_field" />
								<div class="cleaner h10"></div>
								<label for="email">Email:</label> <input type="text" id="email" name="email" class="validate-email required input_field" />
								<div class="cleaner h10"></div>
				
								<label for="text">Message:</label> <textarea id="text" name="text" rows="0" cols="0" class="required"></textarea>
								<div class="cleaner h10"></div>
								
								<input type="submit" value="Send" id="submit" name="submit" class="submit_btn float_l" />
								<input type="reset" value="Reset" id="reset" name="reset" class="submit_btn float_r" />
								
							</form>
						</div>
                        
                        <div class="col_w280 float_r">
                            <h4>Mailing Address</h4>
                            <h6><strong>Company Name</strong></h6>
                            240-480 Fusce nec ante at odio blandit, <br />
                            In vitae lacus in purus interdum, 18760<br />
                            Ullamcorper mattis magna non<br /><br />
                            
                            <strong>Phone:</strong> 010-050-1050<br />          
                            <strong>Email:</strong> <a href="mailto:info@yoursite.com">info@yoursite.com</a>
               			</div>
						
					</div>
				</li>
			
			</ul>
			
		</div> <!-- END of content -->
	</div> <!-- END of templatemo_main -->
    <div id="templatemo_footer">Copyright © 2048 Your Company Name | Designed by <a href="http://www.templatemo.com" rel="nofollow" target="_parent">Free CSS Templates</a></div>
</div> <!-- END of templatemo_wrapper -->


<script type='text/javascript' src='js/logging.js'></script>
</body>
</html>
<?php 
mysql_free_result($author_details);
mysql_free_result($course_details);
mysql_free_result($resource);
?>