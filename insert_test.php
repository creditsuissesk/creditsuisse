<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>
<body>
<?php
# Init the MySQL Connection
  $db = mysql_connect( 'localhost' , 'root' , '' );
  mysql_select_db( 'credit_suisse' );
  $email=$_POST["u_name"];
  $f_name=$_POST["f_name"];
  $l_name=$_POST["l_name"];
  $password=$_POST["pass"];
  $dob=$_POST["dob"];
  $contact=$_POST["contact"];
  $institute=$_POST["inst_name"];
  $stream=$_POST["stream"];
  $role=$_POST["role"];
  $insertTPL = "INSERT INTO user(u_name,password,f_name,l_name,contact_no,dob,institute,stream,role) VALUES ('$email','$password','$f_name','$l_name','$contact','$dob','$institute','$stream','$role')";
 if( !( $insertRes = mysql_query( $insertTPL ) ) ){
    echo '<p>Insert of Row into Database Failed - #'.mysql_errno().': '.mysql_error().'</p>';
  }else{
    echo '<p>Person\'s Information Inserted</p>';
  }
?>
</body>
</html>