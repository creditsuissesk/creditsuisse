<?php require_once('Connections/conn.php'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>download file</title>
</head>

<body>
<?php

//select database
$db = mysql_select_db($database_conn, $conn);
$query = "SELECT r_id, filename FROM resource";
$result = mysql_query($query) or die('Error, query failed');
if(mysql_num_rows($result) == 0)
{
echo "Database is empty <br>";
} 
else
{
	while(list($id, $name) = mysql_fetch_array($result)){
            echo "<a href=\"download.php?id=$id\">$name</a><br>";
			}
}
mysql_close();
?>
</body>
</html>