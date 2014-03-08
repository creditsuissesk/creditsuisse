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
if($_FILES["file"]["size"]==0 )
{
		echo '<script type="text/javascript">alert("Upload profile image to register"); window.location="registration.php"; </script>';

}
else
{
$allowedExts = array("gif", "jpeg", "jpg", "png");
$temp = explode(".", $_FILES["file"]["name"]);
$extension = end($temp);
if ((($_FILES["file"]["type"] == "image/gif")
|| ($_FILES["file"]["type"] == "image/jpeg")
|| ($_FILES["file"]["type"] == "image/jpg")
|| ($_FILES["file"]["type"] == "image/pjpeg")
|| ($_FILES["file"]["type"] == "image/x-png")
|| ($_FILES["file"]["type"] == "image/png"))
&& ($_FILES["file"]["size"] < 50000)
/*&& in_array($extension, $allowedExts)*/
)
  {
  if ($_FILES["file"]["error"] > 0)
    {
     echo '<script type="text/javascript">alert("File Error: '. $_FILES["file"]["error"] . ' ");window.location="http://localhost/dreamweaver/registration.php";</script>';
    }
  else
    {
   /* echo "Upload: " . $_FILES["file"]["name"] . "<br>";
    echo "Type: " . $_FILES["file"]["type"] . "<br>";
    echo "Size: " . ($_FILES["file"]["size"] / 1024) . " kB<br>";
    echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br>";
	*/
	$max_size=50000;
	$max_size_mb=(50000/1024)/1024;
	$filesize=$_FILES["file"]["size"]/1024/1024;
	$filename=$_POST['u_name'];
	$upload_add="images/profiles/" . $filename;
    if (file_exists("images/profiles/" . $filename))
      {
      echo  '<script type="text/javascript">alert("Username already exists."); window.location="index.php";</script>';
      }
    else
      {
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form")) {
		$insertSQL = sprintf("INSERT INTO `user` (u_name, password, f_name, l_name, contact_no, dob, institute, stream,role,photo,degree,about) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s,%s,%s,%s)",
							 GetSQLValueString($_POST['u_name'], "text"),
							 GetSQLValueString($_POST['pass'], "text"),
							 GetSQLValueString($_POST['f_name'], "text"),
							 GetSQLValueString($_POST['l_name'], "text"),
							 GetSQLValueString($_POST['contact'], "int"),
							 GetSQLValueString($_POST['dob'], "date"),
							 GetSQLValueString($_POST['inst_name'], "text"),
							 GetSQLValueString($_POST['stream'], "text"),
							 GetSQLValueString($_POST['role'], "text"),
							 GetSQLValueString( $upload_add,"text"),
							 GetSQLValueString($_POST['degree'], "text"),
							 GetSQLValueString($_POST['about'], "text"));
	  
		mysql_select_db($database_conn, $conn);
		$Result1 = mysql_query($insertSQL, $conn) or die(mysql_error());
		 move_uploaded_file($_FILES["file"]["tmp_name"],
      $upload_add);
	   	echo '<script type="text/javascript">alert("Registered Successfully. Waiting for the approval by Administrator"); window.location="index.php"; </script>';
	  }
	  }
    }
	    }
else
  {
	  if (!(($_FILES["file"]["type"] == "image/gif")
|| ($_FILES["file"]["type"] == "image/jpeg")
|| ($_FILES["file"]["type"] == "image/jpg")
|| ($_FILES["file"]["type"] == "image/pjpeg")
|| ($_FILES["file"]["type"] == "image/x-png")
|| ($_FILES["file"]["type"] == "image/png")))
  echo '<script type="text/javascript">alert("Invalid File Type. Please upload valid file.");  window.location="registration.php";</script>';
  else 
	   if(($_FILES["file"]["size"] > $max_size))
  echo '<script type="text/javascript">alert("File Size is '.$filesize.' mb which GREATER than the allowed size. Allowed Size is '.$max_size_mb.' mb.");
  window.location="registration.php";</script>';
  else
  echo '<script type="text/javascript">alert("Problem in uploading file.Please register again");window.location="registration.php";</script>';
  }
}
?>

<?php 
} //end of captcha validation if
?>