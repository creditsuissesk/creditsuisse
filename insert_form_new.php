<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
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

<!--
script for calendar
--->
<link rel="stylesheet" type="text/css" media="all" href="jsDatePick_ltr.min.css" />
<link href="SpryAssets/SpryValidationPassword.css" rel="stylesheet" type="text/css" />
<link href="SpryAssets/SpryValidationConfirm.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="jsDatePick.min.1.3.js"></script>
<script type="text/javascript">
	window.onload = function(){
		new JsDatePick({
			useMode:2,
			target:"dob",
			dateFormat:"%Y-%d-%m"
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
<form action="insert_test.php" method="post">
  <p>
    <label for="u_name">Username (Email)* : </label>
    <input type="text" name="u_name" id="u_name" />
  </p>
  <p><span id="sprypassword1">
  <label for="pass">Password* : </label>
  <input type="password" name="pass" id="pass" />
  <span class="passwordRequiredMsg">A value is required.</span><span class="passwordInvalidStrengthMsg">The password doesn't meet the specified strength.</span></span></p>
  <p><span id="spryconfirm2">
    <label for="confirm_pass">Confirm Password* :</label>
    <input type="password" name="confirm_pass" id="confirm_pass" />
  <span class="confirmRequiredMsg">A value is required.</span><span class="confirmInvalidMsg">The values don't match.</span></span></p>
  <p>
    <label for="f_name">First Name* : </label>
    <input type="text" name="f_name" id="f_name" />
	</p>
  <p>
    <label for="l_name">Last Name* : </label>
    <input type="text" name="l_name" id="l_name" />
  </p>
  <p>
    <label for="dob">Date of Birth* : </label>
    <input name="dob" type="text" id="dob" readonly="readonly" />
  </p>
  <p>
    <label for="contact">Contact No.* : </label>
    <input type="text" name="contact" id="contact" />
  </p>
  <p>
    <label for="inst_name">Institute Name* : </label>
    <input type="text" name="inst_name" id="inst_name" />
  </p>
  <p>
    <label for="stream">Stream* : </label>
    <input type="text" name="stream" id="stream" />
  </p>
  <p>
    <label for="role">I want to register as a*  : </label>
    <select name="role" id="role">
      <option value="student">Student</option>
      <option value="author">Author</option>
    </select>
  </p>
  <p>
    <input name="submit" type="submit" id="submit" onclick="MM_validateForm('u_name','','RisEmail','pass','','R','f_name','','R','l_name','','R','contact','','RisNum','inst_name','','R','stream','','R');return document.MM_returnValue" value="Submit" />
    <input type="submit" name="reset" id="reset" value="Reset" />
  </p>
</form>
<script type="text/javascript">
var sprypassword1 = new Spry.Widget.ValidationPassword("sprypassword1", {minAlphaChars:6, validateOn:["change"]});
var spryconfirm2 = new Spry.Widget.ValidationConfirm("spryconfirm2", "pass", {validateOn:["change"]});
</script>
</body>
</html>