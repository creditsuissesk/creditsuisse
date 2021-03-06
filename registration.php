<html xmlns="http://www.w3.org/1999/xhtml"></html>	  <?php require_once('Connections/conn.php'); ?>
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
	  
	  // *** Redirect if username exists
	  $MM_flag="MM_insert";
	  if (isset($_POST[$MM_flag])) {
		$MM_dupKeyRedirect="index.php";
		$loginUsername = $_POST['u_name'];
		$LoginRS__query = sprintf("SELECT u_name FROM `user` WHERE u_name=%s", GetSQLValueString($loginUsername, "text"));
		mysql_select_db($database_conn, $conn);
		$LoginRS=mysql_query($LoginRS__query, $conn) or die(mysql_error());
		$loginFoundUser = mysql_num_rows($LoginRS);
	  
		//if there is a row in the database, the username was found - can not add the requested username
		if($loginFoundUser){
		  $MM_qsChar = "?";
		  //append the username to the redirect page
		  if (substr_count($MM_dupKeyRedirect,"?") >=1) $MM_qsChar = "&";
		  $MM_dupKeyRedirect = $MM_dupKeyRedirect . $MM_qsChar ."requsername=".$loginUsername;
		  header ("Location: $MM_dupKeyRedirect");
		  exit;
		}
	  }
	  ?>
	  <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	  <html xmlns="http://www.w3.org/1999/xhtml">
	  <head>
	  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	  <title>Registration</title>
	  
	  <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7; IE=EmulateIE9" />
		  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no"/>
		  <script src="SpryAssets/SpryValidationPassword.js" type="text/javascript"></script>
	  <script src="SpryAssets/SpryValidationConfirm.js" type="text/javascript"></script>
	  <script type="text/javascript">
	  function MM_validateForm() { //v4.0
		if (document.getElementById){
		  var i,p,q,nm,test,num,min,max,errors='',args=MM_validateForm.arguments;
		  for (i=0; i<(args.length-2); i+=3) { test=args[i+2]; val=document.getElementById(args[i]);
			if (val) { nm=val.name; if ((val=val.value)!="") {
			  if (test.indexOf('isEmail')!=-1) { p=val.indexOf('@');
				if (p<1 || p==(val.length-1)) errors+='- '+nm+' must contain an e-mail address.\n';
			  } else if (test!='R') { num = parseFloat(val);
				if (isNaN(val)) errors+='- '+nm+' must contain a number.\n';
				if (test.indexOf('inRange') != -1) { p=test.indexOf(':');
				  min=test.substring(8,p); max=test.substring(p+1);
				  if (num<min || max<num) errors+='- '+nm+' must contain a number between '+min+' and '+max+'.\n';
			} } } else if (test.charAt(0) == 'R') errors += '- '+nm+' is required.\n'; }
		  } if (errors) alert('The following error(s) occurred:\n'+errors);
		  document.MM_returnValue = (errors == '');
	  } }
	  </script>
		  <link rel="stylesheet" type="text/css" href="css/reg_style.css" media="all" />
		  <link rel="stylesheet" type="text/css" href="css/registration.css" media="all"/>
		  <link rel="stylesheet" type="text/css" media="all" href="css/jsDatePick_ltr.min.css" />
	  <link href="SpryAssets/SpryValidationPassword.css" rel="stylesheet" type="text/css" />
	  <link href="SpryAssets/SpryValidationConfirm.css" rel="stylesheet" type="text/css" />
	  <script type="text/javascript" src="js/jsDatePick.min.1.3.js"></script>
	  <script type="text/javascript">
		  window.onload = function(){
			  new JsDatePick({
				  useMode:2,
				  target:"Date_of_Birth",
				  dateFormat:"%Y-%m-%d"
			  });
		  };
	  </script>
      </head>
	  
	  <body>
      
      <div class="freshdesignweb-top" >
      <div class="span.right">
      <a href="index.php#home" ><img src="images/home_btn.png" name="h_btn" id="h_btn" onClick="index.php#home" /></a>
	  </div>
      </div>
	  <div class="container">
     
	  <header>
					  <h1>Registration Form</h1>
				  </header>       
			<div  class="form">
				  <form id="contactform" action="reg_data.php" method="POST" enctype="multipart/form-data"> 
					  <p class="contact">
		  <label for="u_name">UserName (Email)*:</label></p> 
					  <input id="u_name" name="u_name" placeholder="example@domain.com" required  type="email" />
	   <p class="contact">
		<label for="pass">Password* : </label></p>
		  <p><span id="sprypassword1"> 
		<input type="password" name="password" id="password" required/><br> 
		<span class="passwordRequiredMsg">     A value is required.</span><span class="passwordInvalidStrengthMsg">     The password doesn't meet the specified strength.</span></span></p>
        </br>
		<p class="contact">
		  <label for="confirm_password">Confirm Password* :</label></p>
		 <p><span id="spryconfirm2"> 
		  <input type="password" name="confirm_password" id="confirm_password" required/>
          <br>
	  <span class="confirmRequiredMsg">     A value is required.</span><span class="confirmInvalidMsg">     The values don't match.</span></span></p></br>
		<p class="contact">
		  <label for="First_Name">First Name* : </label></p>
		  <input type="text" required name="First_Name" id="First_Name" placeholder="First Name"/>
		  
		<p class="contact">
		  <label for="Last_Name">Last Name* : </label></p>
		  <input type="text" required name="Last_Name" id="Last_Name" placeholder="Last Name"/>
	   <p class="contact">
		  <label for="Date of Birth">Date of Birth* : </label></p>
		  <input name="Date_of_Birth" required type="text" id="Date_of_Birth" readonly placeholder="Date of Birth"/>
				  
	   <p class="contact">
		  <label for="Contact_Number">Contact No.* : </label></p>
		  <input type="text" name="Contact_Number" required id="Contact_Number" placeholder="Contact No. of 10 Digits"/>
	   <p class="contact">
		  <label for="Institute_Name">Institute Name* : </label></p>
		  <input type="text" name="Institute_Name" id="Institute_Name" required placeholder="Institute of Specialization"/>
		<p class="contact">
		  <label for="Stream">Stream* : </label></p>
		  <input type="text" name="Stream" id="Stream" required placeholder="Field of Specialization"/>
          <p class="contact">
		  <label for="Qualification">Qualification* : </label></p>
		  <input type="text" required name="Qualification" id="Qualification" placeholder="Enter the Qualification of study eg. B.Tech"/>
		  <p class="contact">
		  <label for="About">About* : </label></p>
		  <textarea name="About" required id="About" placeholder="About Yourself" rows="5" cols="45"></textarea><br/>
          <p><label for="role">I am...* : </label></p>
          <p>
		   <select class="select-style gender" name="role" id="role">
				  <option value="student">Student</option>
				  <option value="author">Author</option>
				  <option value="cm">Content Manager</option>
				  </select><br /><br />
	  </p>  
      <p> <label for="File">Profile Picture:</label>
<input type="file" name="File" required id="File">
		</p>
        <?php
     require_once('recaptchalib.php');
     $publickey = "6Le6u-8SAAAAAAbbVmypQOuHJ1sxwzwHS3BBYi76";
     echo recaptcha_get_html($publickey);
   ?>
	<br /><br />
      <p ><input class="buttom" name="submit" id="submit" value="Sign me up!" type="submit" onClick="MM_validateForm('password','','R','First_Name','','R','Last_Name','','R','Contact_Number','','RisNum','Institute_Name','','R','Stream','','R','Qualification','','R','About','','R','File','','R');return document.MM_returnValue" 
	action="reg_data.php"	  />
		  <input class="buttom" type="reset" name="reset" id="reset" value="Reset" />
		</p>
		<input type="hidden" name="MM_insert" value="form" />
	  </form>
      </div>
      </div>
	  <script type="text/javascript">
	  var sprypassword1 = new Spry.Widget.ValidationPassword("sprypassword1", {minAlphaChars:6, validateOn:["change"]});
	  var spryconfirm2 = new Spry.Widget.ValidationConfirm("spryconfirm2", "password", {validateOn:["change"]});
	  </script>
	  </body>
	  </html>