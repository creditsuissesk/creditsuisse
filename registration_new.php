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
	  
	  $editFormAction = $_SERVER['PHP_SELF'];
	  if (isset($_SERVER['QUERY_STRING'])) {
		$editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
	  }
	  
	  if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form")) {
		$insertSQL = sprintf("INSERT INTO `user` (u_name, password, f_name, l_name, contact_no, dob, institute, stream, `role`) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s)",
							 GetSQLValueString($_POST['u_name'], "text"),
							 GetSQLValueString($_POST['pass'], "text"),
							 GetSQLValueString($_POST['f_name'], "text"),
							 GetSQLValueString($_POST['l_name'], "text"),
							 GetSQLValueString($_POST['contact'], "int"),
							 GetSQLValueString($_POST['dob'], "date"),
							 GetSQLValueString($_POST['inst_name'], "text"),
							 GetSQLValueString($_POST['stream'], "text"),
							 GetSQLValueString($_POST['role'], "text"));
	  
		mysql_select_db($database_conn, $conn);
		$Result1 = mysql_query($insertSQL, $conn) or die(mysql_error());
	  
		$insertGoTo = "index.php";
		if (isset($_SERVER['QUERY_STRING'])) {
		  $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
		  $insertGoTo .= $_SERVER['QUERY_STRING'];
		}
		header(sprintf("Location: %s", $insertGoTo));
	  }
	  ?>
	  <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	  <html xmlns="http://www.w3.org/1999/xhtml">
	  <head>
	  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	  <title>Registration</title>
	  
	  <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7; IE=EmulateIE9">
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
		  <link rel="stylesheet" type="text/css" href="style.css" media="all" />
		  <link rel="stylesheet" type="text/css" href="demo.css" media="all" />
		  <link rel="stylesheet" type="text/css" media="all" href="jsDatePick_ltr.min.css" />
	  <link href="SpryAssets/SpryValidationPassword.css" rel="stylesheet" type="text/css" />
	  <link href="SpryAssets/SpryValidationConfirm.css" rel="stylesheet" type="text/css" />
	  <script type="text/javascript" src="jsDatePick.min.1.3.js"></script>
	  <script type="text/javascript">
		  window.onload = function(){
			  new JsDatePick({
				  useMode:2,
				  target:"dob",
				  dateFormat:"%Y-%m-%d"
				  /*selectedDate:{				This is an example of what the full configuration offers.
					  day:5,						For full documentation about these settings please see the full version of the code.
					  month:9,
					  year:2006
				  },
				  yearsRange:[1978,2020],
				  limitToToday:false,
				  cellColorScheme:"beige",
				  dateFormat:"%m-%d-%Y",
				  imgPath:"img/",
				  weekStartDay:1*/
			  });
		  };
	  </script>
	  </head>
	  
	  <body>
	  <div class="container">
	  <header>
					  <h1>Registration Form</h1>
				  </header>       
			<div  class="form">
				  <form id="contactform" action="<?php echo $editFormAction; ?>" method="POST" > 
					  <p class="contact">
		  <label for="u_name">UserName (Email)*:</label></p> 
					  <input id="u_name" name="u_name" placeholder="example@domain.com" required=""  type="email">
	   <p class="contact">
		<label for="pass">Password* : </label></p>
		  <p><span id="sprypassword1"> 
		<input type="password" name="pass" id="pass" required=""/>
		<span class="passwordRequiredMsg">     A value is required.</span><span class="passwordInvalidStrengthMsg">     The password doesn't meet the specified strength.</span></span></p>
		<p class="contact">
		  <label for="confirm_pass">Confirm Password* :</label></p>
		 <p><span id="spryconfirm2"> 
		  <input type="password" name="confirm_pass" id="confirm_pass" />
	  <span class="confirmRequiredMsg">     A value is required.</span><span class="confirmInvalidMsg">     The values don't match.</span></span></p>
		<p class="contact">
		  <label for="f_name">First Name* : </label></p>
		  <input type="text" name="f_name" id="f_name" placeholder="First Name"/>
		  
		<p class="contact">
		  <label for="l_name">Last Name* : </label></p>
		  <input type="text" name="l_name" id="l_name" placeholder="Last Name"/>
	   <p class="contact">
		  <label for="dob">Date of Birth* : </label></p>
		  <input name="dob" type="text" id="dob" readonly="readonly" placeholder="Date of Birth"/>
				  
	   <p class="contact">
		  <label for="contact">Contact No.* : </label></p>
		  <input type="text" name="contact" id="contact" placeholder="Contact No. of 10 Digits"/>
	   <p class="contact">
		  <label for="inst_name">Institute Name* : </label></p>
		  <input type="text" name="inst_name" id="inst_name" placeholder="Institute Name"/>
		<p class="contact">
		  <label for="stream">Stream* : </label></p>
		  <input type="text" name="stream" id="stream" placeholder="Stream Name"/>
		  <p>
		   <select class="select-style gender" name="role" id="role">
				  <option value="select">i am..</option>
				  <option value="student">Student</option>
				  <option value="author">Author</option>
				  <option value="cm">Content Manager</option>
				  </select><br><br>
	  </p>  <p ><input class="buttom" name="submit" id="submit" value="Sign me up!" type="submit" onclick="MM_validateForm('u_name','','RisEmail','pass','','R','f_name','','R','l_name','','R','contact','','RisNum','inst_name','','R','stream','','R');return document.MM_returnValue" 
		  />
		  <input class="buttom" type="reset" name="reset" id="reset" value="Reset" />
		</p>
		<input type="hidden" name="MM_insert" value="form" />
	  </form>
	  <script type="text/javascript">
	  var sprypassword1 = new Spry.Widget.ValidationPassword("sprypassword1", {minAlphaChars:6, validateOn:["change"]});
	  var spryconfirm2 = new Spry.Widget.ValidationConfirm("spryconfirm2", "pass", {validateOn:["change"]});
	  </script>
	  </body>
	  </html>