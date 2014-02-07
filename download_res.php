<?php require_once('Connections/conn.php'); ?>
<?php function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
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
?>
<?php
$db = mysql_select_db($database_conn, $conn);
if(isset($_POST['id']))
{
	$id    = $_POST['id'];
	$query = sprintf("SELECT filename, file_type,file_size, file_location FROM resource WHERE r_id = %s",GetSQLValueString($id, "int"));
	$result = mysql_query($query) or die('Error, query failed'); 
	list($name, $type, $size, $location) =  mysql_fetch_row($result);
/*	$extract = fopen($location, 'r');
	$content = fread($extract, $size);
	$content = addslashes($content);
	fclose($extract);*/
	$content = file_get_contents($location);
	 header("Content-Disposition: attachment; filename=\"$name\"");
        header("Content-type: $type");
        header("Content-length: $size");
        print $content;
		ob_clean();
		flush();
		echo $content;
		mysql_close();
		exit;
}
else
echo "Id not set";

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Download Resource</title>
</head>

<body>
</body>
</html>