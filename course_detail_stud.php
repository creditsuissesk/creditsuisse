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
$query_resource = sprintf("SELECT r_id,c_id,uploaded_date,file_size,r_type,type_id,file_type,filename,download_status FROM `resource`NATURAL JOIN `resource_type` WHERE c_id=%s ",GetSQLValueString($_GET['c_id'], "int"));
$resource = mysql_query($query_resource, $conn) or die(mysql_error());
$row_resource = mysql_fetch_assoc($resource);
$totalRows_resource = mysql_num_rows($resource);





?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Course Detailes</title>
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
						<h2>Welcome to Course Name</h2>
						<p><em> This is the course description.</em></p>
						<img src="images/course_picture/Data Struct.jpg" alt="" class="image_fl" height=200 width =200 />
						<div class="col_half float_r">
							<p>here author of course will be mentioned and his details </p>
							<h3>Other courses by him</h3>
							<ul class="templatemo_list">
								<li>course A</li>
								<li>Course B</li>
								<li>Course C</li>
							</ul>
						</div>
					</div>
				</li>
			
				<li id="about"><span class="header"></span>
					<div class="inner">
						<h2>About</h2>
						<img src="images/templatemo_image_02.jpg" alt="Image 02" class="image_fr" />
						<p><em>Ut tincidunt risus porta ipsum tristique sodales. Cras et urna erat. Integer laoreet turpis id arcu congue scelerisque.</em></p>
						<p>Aenean facilisis bibendum neque, adipiscing dapibus felis elementum id. Vestibulum ante ipsum primis in <a href="#">faucibus orci</a> luctus et ultrices posuere cubilia Curae; Phasellus id metus leo, vitae posuere ligula. Aliquam volutpat congue facilisis. Nunc dui mauris, eleifend id facilisis vitae, interdum sit amet ipsum. Praesent congue placerat imperdiet. Nulla facilisi. Morbi sollicitudin congue. Validate <a href="http://validator.w3.org/check?uri=referer" rel="nofollow"><strong>XHTML</strong></a> &amp; <a href="http://jigsaw.w3.org/css-validator/check/referer" rel="nofollow"><strong>CSS</strong></a>.</p>
						<p>Nulla quam felis, gravida et eleifend sed, sollicitudin quis purus. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec est purus, fermentum in vulputate nec, laoreet sed dui. Duis in metus eu augue aliquet vestibulum. Nam laoreet, augue eu imperdiet tristique, elit <a href="#">orci rutrum elit</a>, bibendum lobortis arcu magna sed nunc. Suspendisse eros diam, dictum in elementum at, pharetra eget massa. Phasellus odio risus, pharetra vel rutrum quis, iaculis ac purus. Nam et mi non urna congue ornare. Aenean et dolor diam.</p>
					</div>
				</li>
			
				<li id="social"><span class="header"></span>
					<div class="inner">
						<h2>Social</h2>
						<p><em>Maecenas consectetur adipiscing neque, eget vestibulum orci suscipit eu. Nam adipiscing, nisi eu porta porttitor, tellus sem sollicitudin orci, sed pharetra lorem augue vel nibh. Donec id tortor ipsum, vitae tempor turpis. In hac habitasse platea dictumst.</em></p>
						<ul class="social_links">
							<li><a href="http://www.facebook.com/templatemo" class="facebook">Facebook</a></li>
							<li><a href="#" class="flickr">Flickr</a></li>
							<li><a href="#" class="linkedin">Linkedin</a></li>
							<li><a href="#" class="twitter">Twitter</a></li>
							<li><a href="#" class="yahoo">Yahoo</a></li>
							<li><a href="#" class="youtube">Youtube</a></li>
						</ul>
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