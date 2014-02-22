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
<?php
// *** Validate request to login to this site.
if (!isset($_SESSION)) {
  session_start();
}

$loginFormAction = $_SERVER['PHP_SELF'];
if (isset($_GET['accesscheck'])) {
  $_SESSION['PrevUrl'] = $_GET['accesscheck'];
}

if (isset($_POST['username'])) {
  $loginUsername=$_POST['username'];
  $password=$_POST['pass'];
  $MM_fldUserAuthorization = "role";
  $MM_redirectLoginSuccessUser = "userhome.php";
  $MM_redirectLoginSuccessAuthor = "authorhome.php";
  $MM_redirectLoginSuccessRoot = "admin_home.php";
  $MM_redirectLoginSuccessCM = "cmhome.php";
  $MM_redirectLoginFailed = "index.php#login";
  $MM_redirecttoReferrer = false;
  mysql_select_db($database_conn, $conn);
  	
  $LoginRS__query=sprintf("SELECT u_id,f_name, u_name, password, role,stream FROM `user` WHERE u_name=%s AND password=%s AND approve_id=1",GetSQLValueString($loginUsername, "text"), GetSQLValueString($password, "text")); 
  $LoginRS = mysql_query($LoginRS__query, $conn) or die(mysql_error());
  $loginFoundUser = mysql_num_rows($LoginRS);
  if ($loginFoundUser) {
    
    $loginStrGroup  = mysql_result($LoginRS,0,'role');
    
	if (PHP_VERSION >= 5.1) {session_regenerate_id(true);} else {session_regenerate_id();}
    //declare two session variables and assign them
    $_SESSION['MM_Username'] = $loginUsername;
    $_SESSION['MM_UserGroup'] = $loginStrGroup;
	$_SESSION['MM_UserID'] = mysql_result($LoginRS,0,'u_id');
	$_SESSION['MM_f_name'] = mysql_result($LoginRS,0,'f_name');
	$_SESSION['MM_stream'] = mysql_result($LoginRS,0,'stream');
    if (isset($_SESSION['PrevUrl']) && false) {
      $MM_redirectLoginSuccess = $_SESSION['PrevUrl'];	
    }
	if ($_SESSION['MM_UserGroup'] == 'admin') {
	  header("Location: ".$MM_redirectLoginSuccessRoot );
	} elseif ($_SESSION['MM_UserGroup'] == 'student') {
	  header("Location: ".$MM_redirectLoginSuccessUser);
	} elseif ($_SESSION['MM_UserGroup'] == 'author') {
	  header("Location: ".$MM_redirectLoginSuccessAuthor)			;}elseif ($_SESSION['MM_UserGroup'] == 'cm') {
	  header("Location: ".$MM_redirectLoginSuccessCM);
	}
    //header("Location: " . $MM_redirectLoginSuccess );
  }
  else {
    header("Location: ". $MM_redirectLoginFailed );
  }
}
?>
<?php 
mysql_select_db($database_conn, $conn);
$query_no_of_user = sprintf("SELECT count(distinct u_id)as count_u FROM `user` WHERE approve_id =1");
$no_of_user = mysql_query($query_no_of_user, $conn) or die(mysql_error());
$row_no_of_user = mysql_fetch_assoc($no_of_user);
$totalRows_no_of_user = mysql_num_rows($no_of_user);

mysql_select_db($database_conn, $conn);
$query_no_of_course = sprintf("SELECT count(distinct c_id)as count_c FROM `course` WHERE approve_status =1");
$no_of_course = mysql_query($query_no_of_course, $conn) or die(mysql_error());
$row_no_of_course = mysql_fetch_assoc($no_of_course);
$totalRows_no_of_course = mysql_num_rows($no_of_course);


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Virtual Library</title>
<meta name="keywords" content="tech layer, free template, one page layout" />
<link href="css/home_page.css" type="text/css" rel="stylesheet" /> 
<script type="text/javascript" src="js/jquery.min.js"></script> 
<script type="text/javascript" src="js/jquery.scrollTo-min.js"></script> 
<script type="text/javascript" src="js/jquery.localscroll-min.js"></script> 
<script type="text/javascript" src="js/init.js"></script>  
<link rel="stylesheet" href="css/slimbox2.css" type="text/css" media="screen" /> 

<link rel="stylesheet" href="css/login.css" type="text/css" media="screen" /> 
<script type="text/JavaScript" src="js/slimbox2.js"></script> 
<script language="javascript" type="text/javascript">
function clearText(field)
{
    if (field.defaultValue == field.value) field.value = '';
    else if (field.value == '') field.value = field.defaultValue;
}
</script>

<script src="js/jquery.min.js"></script>
<script>
		$(function(){
		  var $form_inputs =   $('form input');
		  var $rainbow_and_border = $('.rain, .border');
		  /* Used to provide loping animations in fallback mode */
		  $form_inputs.bind('focus', function(){
		  	$rainbow_and_border.addClass('end').removeClass('unfocus start');
		  });
		  $form_inputs.bind('blur', function(){
		  	$rainbow_and_border.addClass('unfocus start').removeClass('end');
		  });
		  $form_inputs.first().delay(800).queue(function() {
			$(this).focus();
		  });
		});
</script>
<?php 
//code to pop-up a resource for viewing when redirected here by a QR code scan.
//URL will be of the type index.php?mode=qr&viewId=resourceIdHere
if(isset($_GET['mode']) && isset($_GET['viewId'])) {
	//query the database about resource details to show it in a float window.
	if($_GET['mode']=='qr') {
	mysql_select_db($database_conn, $conn);
	$query_get_resource=sprintf("SELECT * FROM `resource` WHERE r_id=%s",GetSQLValueString($_GET['viewId'],"int"));
	$get_resource = mysql_query($query_get_resource, $conn) or die(mysql_error());
	$row_get_resource = mysql_fetch_assoc($get_resource);
	$totalRows_get_resource = mysql_num_rows($get_resource);
?>
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
<script>
	function showResource(id,type,name) {
	//show dimensions based on content type
	var height_set,width_set;
	if(type=="pdf") {
		height_set=520;
		width_set=920;
	}else if (type=="image") {
		height_set=320;
		width_set=620;
	}
	light = new LightFace.IFrame({
		height:height_set,
		width:width_set,
		url: 'view_resource_qr.php?actiontype=loadResource&r_id='+id,
		title: 'Resource : '+name
		}).addButton('Close', function() { light.close(); },true).open();		
}

<?php echo "$(document).ready(function(){showResource(".$row_get_resource['r_id'].",'";
	if(strpos($row_get_resource['file_type'],"application/pdf")!==false) {
		echo "pdf";
	}else if (strpos($row_get_resource['file_type'],"image")!==false) {
		echo "image";
	}
	echo "','".$row_get_resource['filename']."');});";?>
</script>
<?php } //end of mode=qr if
}//end of resource popup if?>


</head> 
<body> 

<div id="templatemo_header_wrapper">
	<div id="templatemo_header">
    	<div id="site_title"><a rel="nofollow" href="">Virtual Library</a></div>
        <a class="templatemo_header_bg" href="" title="Creative commons beelden"  target="_blank"><img src="images/header.png" alt="Creative commons beelden" title="Creative commons beelden" /></a>
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
                         	 Know about how this is initiated and <b><i> What we are?</i></b>
						</div>
                    </div>
                    <div class="row1 box2">
                    	<div class="box_with_padding">
                        	<h2><a href="#services">Services</a></h2>
         Get to know Services provided by us
						</div>
                    </div>
                    <div class="row1 box3">
                    	<div class="box_with_padding">
                        	<h2><a href="#institution">Institutions</a></h2>
                            Name of Institutions from where we get author of our courses
						</div>
                    </div>
                    <div class="row1 box4">
                    	<div class="box_with_padding">
                        	<h2><a href="#contact">Contact</a></h2>
	Got queries? Contact us by mailing us
						</div>
                    </div>
                </div>
 				<div class="home_box right">
                	<div class="row1 box5">
                    	<div class="box_with_padding">
                        <a href="registration.php"	><h2>Sign Up</h2></a>
                         Just a click away from obtaining knowledge
						</div>
                    </div>
                    <!-- <div class="row2" id="home_gallery">
                    	<a href="images/gallery/01-l.jpg" rel="lightbox[home_gallery]" class="left"><img src="images/gallery/01.jpg" alt="image 1" /></a>
                        					</div> -->
                    <div class="row1 box8">
                    	<div class="box_with_padding">
                        <a href="#login"	><h2>Sign In</h2></a>
                         Go Grab knowledge from latest Courses
						</div>
                    </div>
                    <div class="row1 box7">
                    	<div class="box_with_padding">
                        <h2>Statistics</h2>
       <p>                 
                   <b>      Total Number of Users:</b><?php echo "<i>".$row_no_of_user['count_u']."</i>";?>
        <br/>            
                       <b>  Total Number of Courses:</b><?php echo "<i>".$row_no_of_course['count_c']."</i>";?>
        </p>
						</div>
                    </div>                        
                    <div class="row1 box6">
                        <div id="mini_contact_form">
                        	<h5>Quick Contact</h5>
                            <form method="post" name="contact" action="#">
                            	<div class="col_half left">
                               	  	<textarea id="text_small" name="text" rows="0" cols="0" 
                                  		onfocus="clearText(this)" onblur="clearText(this)">Message</textarea>
                                </div>                                
                            	<div class="col_half right">
                                	<input name="author" type="text" class="input_field" id="author_small" 
                                    	onfocus="clearText(this)" onblur="clearText(this)" value="Name" maxlength="40" />
                                    <input name="email" type="text" class="input_field" id="email_small" 
                                    	onfocus="clearText(this)" onblur="clearText(this)" value="Email" maxlength="40" />
                                  	<input type="submit" class="submit_btn float_l" name="submit" id="submit_small" value="Send" />
                              	</div>                                
                            </form>
                            <div class="clear"></div>
                        </div>
                        
                    </div>
                </div>
            </div> <!-- END of Home -->
            
            <div class="section section_with_padding" id="about"> 
                <h1>About</h1>
                <div class="half left">
                    <div class="img_border img_fl"> <a href="#gallery"><img src="images/templatemo_image_02.jpg" alt="image 2" /></a>	
                  </div>
                    write here fr about slide				</div>
                <div class="half right">
                     this is ryt of about
				</div>
                <a href="#home" class="home_btn">home</a> 
                <a href="#home" class="page_nav_btn previous">Previous</a>
                <a href="#services" class="page_nav_btn next">Next</a>
            </div> <!-- END of About -->
            
            <div class="section section_with_padding" id="services"> 
                <h1>Services</h1>
                <div class="half left">
                	this is left of services
                                    </div>
                <div class="half right">
                    <div class="img_border img_nom"> <a href="#gallery"><img src="images/templatemo_image_01.jpg" alt="image 1" /></a>	
                    </div>
                    
               	  this is right of services
                </div>
                <a href="#home" class="home_btn">home</a> 
                <a href="#about" class="page_nav_btn previous">Previous</a>
                <a href="#institution" class="page_nav_btn next">Next</a> 
            </div> <!-- END of Services -->
           
            
            <div class="section section_with_padding" id="institution"> 
               	<h1>institutions</h1>
              <div> this is institutions </div>
            
                <a href="#home" class="home_btn">home</a> 
                <a href="#services" class="page_nav_btn previous">Previous</a>
                <a href="#contact" class="page_nav_btn next">Next</a>
            </div> <!-- END of institution -->
            
            <div class="section section_with_padding" id="contact"> 
                <h1>Contact</h1> 
                
                <div class="half left">
                    <h4>Mailing Address</h4>
                    220-440 Nullam lacus diam,<br />
                	Pulvinar sit amet convallis eget, 10220<br />
                	Lorem ipsum dolor<br /><br />
                 
                 	Email: info[at]company.com | Phone: 020-010-0101<br />

                    <div class="clear h20"></div>
                <div class="img_nom img_border"><span></span>
                    <iframe width="320" height="160" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="http://maps.google.com/maps?f=q&amp;source=s_q&amp;hl=en&amp;geocode=&amp;q=new+york+central+park&amp;aq=&amp;sll=37.0625,-95.677068&amp;sspn=60.158465,135.263672&amp;vpsrc=6&amp;ie=UTF8&amp;hq=&amp;hnear=Central+Park,+New+York&amp;t=m&amp;ll=40.769606,-73.973372&amp;spn=0.014284,0.033023&amp;z=14&amp;output=embed"></iframe>
                </div>
                
                <a href="#home" class="home_btn">home</a> 
                <a href="#institution" class="page_nav_btn previous">Previous</a>
                <a href="#login" class="page_nav_btn next">Next</a>
                
            	</div> <!-- END of Contact -->
                
                <div class="half right">
                    <h4>Quick Contact</h4>
                    <p>Nullam a tortor est, congue fermentum nisi. Maecenas nulla nulla, eu volutpat euismod, scelerisque ut dui.</p>
                    <div id="contact_form">
                        <form method="post" name="contact" action="#contact">
                            <div class="left">
                                <label for="author">Name:</label> <input type="text" id="author" name="author" class="required input_field" />
                            </div>
                            <div class="right">                           
                                <label for="email">Email:</label> <input type="text" id="email" name="email" class="validate-email required input_field" />
                            </div>
                            <div class="clear"></div>
                            <label for="text">Message:</label> <textarea id="text" name="text" rows="0" cols="0" class="required"></textarea>
                            <input type="submit" class="submit_btn float_l" name="submit" id="submit" value="Send" />
                        </form>
                    </div>
                </div>
                
                
            
            
            
        </div> 
         <div class="section section_with_padding log" id="login"> 
          <div class="log">    
                <div id="home">
		<div class="rain">
			<div class="border start">
				<form ACTION="<?php echo $loginFormAction; ?>" id="form1" name="form1" method="POST">
					<label for="username">Email</label>
					<input name="username" type="text" placeholder="username" id="username"/>
					<label for="pass">Password</label>
					<input name="pass" type="password" placeholder="Password" id="pass"/>
                                        <input type="submit" value="LOG IN" id="submit" />
				</form>
			</div>
		</div>
                </div>
                </div>
                <a href="#home" class="home_btn">home</a> 
                <a href="#contact" class="page_nav_btn previous">Previous</a>
                <a href="#home" class="page_nav_btn next">Next</a>
            </div> <!-- END of Login -->
        
        
        
        
        
        
    </div>
</div>

<div id="templatemo_footer_wrapper">
	<div id="templatemo_footer">
    	<p>Copyright © 2014 <a href="#">Shaarad and Kunal</a> | <a rel="nofollow" href="index.php">Virtual Library</a> by <a href="https://www.facebook.com/kunal.shah.96780" target="_blank" rel="nofollow">Kunal and Shaarad</a></p>
    </div>
</div>

</div>

</body> 
<script type='text/javascript' src='js/logging.js'></script>
</html>