<?php require_once('Connections/conn.php'); ?>
<?php /*php script for uploading pic */?>
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

<?php //captcha validation
 require_once('recaptchalib.php');
 $privatekey = "6Le6u-8SAAAAABwYZOiltZWMoBFWdx7451JMEkTT";
 $resp = recaptcha_check_answer ($privatekey,$_SERVER["REMOTE_ADDR"],$_POST["recaptcha_challenge_field"],$_POST["recaptcha_response_field"]);
if (!$resp->is_valid) {
	//die ("The reCAPTCHA wasn't entered correctly. Go back and try it again." ."(reCAPTCHA said: " . $resp->error . ")");
	echo '<script type="text/javascript">alert("Please enter the captcha correctly"); window.history.back(); </script>';
}else {
?>

<?php 
if($_FILES["File"]["size"]==0 )
{
		echo '<script type="text/javascript">alert("Upload profile image to register"); window.location="registration.php"; </script>';

}
else
{
$allowedExts = array("gif", "jpeg", "jpg", "png");
$temp = explode(".", $_FILES["File"]["name"]);
$extension = end($temp);
$size=1024*1024;
if ((($_FILES["File"]["type"] == "image/gif")
|| ($_FILES["File"]["type"] == "image/jpeg")
|| ($_FILES["File"]["type"] == "image/jpg")
|| ($_FILES["File"]["type"] == "image/pjpeg")
|| ($_FILES["File"]["type"] == "image/x-png")
|| ($_FILES["File"]["type"] == "image/png"))
&& ($_FILES["File"]["size"] < $size)
/*&& in_array($extension, $allowedExts)*/
)
  {
  if ($_FILES["File"]["error"] > 0)
    {
     echo '<script type="text/javascript">alert("File Error: '. $_FILES["File"]["error"] . ' ");window.location="registration.php";</script>';
    }
  else
    {
   /* echo "Upload: " . $_FILES["File"]["name"] . "<br>";
    echo "Type: " . $_FILES["File"]["type"] . "<br>";
    echo "Size: " . ($_FILES["File"]["size"] / 1024) . " kB<br>";
    echo "Temp File: " . $_FILES["File"]["tmp_name"] . "<br>";
	*/
	$max_size=$size;
	$max_size_mb=($size/1024)/1024;
	$filesize=$_FILES["File"]["size"]/1024/1024;
	$filename=".".$extension;
    if (file_exists("images/profiles/" . $filename))
      {
      echo  '<script type="text/javascript">alert("Username already exists."); window.location="index.php";</script>';
      }
    else
      {
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form")) {
			$passhash=hash('sha256', $_POST['password']);
		$insertSQL = sprintf("INSERT INTO `user` (u_name, password, f_name, l_name, contact_no, dob, institute, stream,role,degree,about) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s,%s,%s)",
							 GetSQLValueString($_POST['u_name'], "text"),
							 GetSQLValueString($passhash, "text"),
							 GetSQLValueString($_POST['First_Name'], "text"),
							 GetSQLValueString($_POST['Last_Name'], "text"),
							 GetSQLValueString($_POST['Contact_Number'], "int"),
							 GetSQLValueString($_POST['Date_of_Birth'], "date"),
							 GetSQLValueString($_POST['Institute_Name'], "text"),
							 GetSQLValueString($_POST['Stream'], "text"),
							 GetSQLValueString($_POST['role'], "text"),
							 
							 GetSQLValueString($_POST['Qualification'], "text"),
							 GetSQLValueString($_POST['About'], "text"));
	  
		mysql_select_db($database_conn, $conn);
		$Result1 = mysql_query($insertSQL, $conn) or die(mysql_error());
	  $query_select=sprintf("SELECT u_id FROM user WHERE u_name=%s",GetSQLValueString($_POST['u_name'], "text"));
	  $select = mysql_query($query_select, $conn) or die(mysql_error());
$row_select = mysql_fetch_assoc($select);
$totalRows_all_students = mysql_num_rows($select);
if($totalRows_all_students==1)
{
	$filename=$row_select['u_id'].$filename;
	$upload_add="images/profiles/" . $filename;
	 $updateSQL = sprintf("UPDATE `user` SET photo=%s WHERE u_id=%s ",
                       GetSQLValueString( $upload_add,"text"),
                       GetSQLValueString($row_select['u_id'], "int"));

  mysql_select_db($database_conn, $conn);
  $Result1 = mysql_query($updateSQL, $conn) or die(mysql_error());
	move_uploaded_file($_FILES["File"]["tmp_name"],
      $upload_add);
}
else
{
	$updateSQL = sprintf("DELETE `user` WHERE u_id=%s ",GetSQLValueString($row_select['u_id'], "int"));
  mysql_select_db($database_conn, $conn);
  $Result1 = mysql_query($updateSQL, $conn) or die(mysql_error());
  echo  '<script type="text/javascript">alert("Username already exists."); window.location="index.php";</script>';
}
	   	echo '<script type="text/javascript">alert("Registered Successfully. Waiting for the approval by Administrator"); window.location="index.php"; </script>';
	  }
	  }
    }
	    }
else
  {
	  if (!(($_FILES["File"]["type"] == "image/gif")
|| ($_FILES["File"]["type"] == "image/jpeg")
|| ($_FILES["File"]["type"] == "image/jpg")
|| ($_FILES["File"]["type"] == "image/pjpeg")
|| ($_FILES["File"]["type"] == "image/x-png")
|| ($_FILES["File"]["type"] == "image/png")))
  echo '<script type="text/javascript">alert("Invalid File Type. Please upload valid file.");  window.location="registration.php";</script>';
  else 
	   if(($_FILES["File"]["size"] > $max_size))
  echo '<script type="text/javascript">alert("File Size is '.$filesize.' MB which GREATER than the allowed size. Allowed Size is '.$max_size_mb.' MB.");
  window.location="registration.php";</script>';
  else
  echo '<script type="text/javascript">alert("Problem in uploading file.Please register again");window.location="registration.php";</script>';
  }
}
?>

<?php 
} //end of captcha validation if
?>