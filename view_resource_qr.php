<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Resource</title>
</head>

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
<body>
<?php
mysql_select_db($database_conn, $conn);
if(isset($_GET['r_id']) && (isset($_GET['actiontype']) && $_GET['actiontype']=="loadResource")) {
	//show resource in float
	$query_get_resource= sprintf("SELECT * FROM `resource` WHERE r_id=%s",GetSQLValueString($_GET['r_id'], "int"));
	$get_resource = mysql_query($query_get_resource, $conn) or die(mysql_error());
	$row_get_resource = mysql_fetch_assoc($get_resource);
	$totalRows_get_resource= mysql_num_rows($get_resource);
	//now we need to divide the page layout output according to type of resource, but that later.
	if($row_get_resource['file_type']=="application/pdf") {
		//outputting code to show pdf
		echo '<embed height="500" width="900" src="'.$row_get_resource['view_location'].'">';
	}else if ($row_get_resource['file_type']=="image/jpeg") {
		echo '<embed height="300" width="600" src="'.$row_get_resource['view_location'].'">';
	}else if (strpos($row_get_resource['file_type'],"video")!==false) {
		echo "video";
	}else if (strpos($row_get_resource['file_type'],"powerpoint")!==false || strpos($row_get_resource['file_type'],"presentation")!==false) {
		echo "presentation";
	}
}
?>
</body>
</html>