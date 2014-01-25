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
if($_GLOBALS['r_id']==4)
$id    = $_GLOBALS['id'];
else
$id =3;
$query = sprintf("SELECT filename, file_type, file_size, file_content FROM resource WHERE r_id = %s",GetSQLValueString($id, "int"));
$result = mysql_query($query) or die('Error, query failed');
$row_result = mysql_fetch_assoc($result);
$name=$row_result['filename'];
$size=$row_result['file_size'];
$type=$row_result['file_type'];
$content=$row_result['file_content'];
header("Content-length: $size");
header("Content-type: $type");
header("Content-Disposition: attachment; filename=$name");
ob_clean();
flush();
echo $content;
mysql_close();
exit;
?>
