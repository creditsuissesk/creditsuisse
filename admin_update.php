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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE `user` SET approve_id=%s WHERE u_id=%s",
                       GetSQLValueString($_POST['app_id'], "int"),
                       GetSQLValueString($_POST['u_id'], "int"));

  mysql_select_db($database_conn, $conn);
  $Result1 = mysql_query($updateSQL, $conn) or die(mysql_error());

  $updateGoTo = "userhome.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$colname_update = "-1";
if (isset($_SERVER['0'])) {
  $colname_update = $_SERVER['0'];
}
mysql_select_db($database_conn, $conn);
$query_update = sprintf("SELECT * FROM `user` natural join `approve_user` WHERE approve_id =0 ORDER BY u_id ASC");
$update = mysql_query($query_update, $conn) or die(mysql_error());
$row_update = mysql_fetch_assoc($update);
$totalRows_update = mysql_num_rows($update);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>admin_update</title>
</head>

<body>
<table border="1" cellpadding="1" cellspacing="1">
  <tr>
    <td>User Id</td>
    <td>User Name</td>
    <td>Password</td>
    <td>First Name</td>
    <td>Last Name</td>
    <td>Contact No</td>
    <td>Date of Birth</td>
    <td>Institute</td>
    <td>Stream</td>
    <td>Role</td>
    <td>Approved Status</td>
  </tr>
  <?php do { ?>
    <tr>
      <td><?php echo $row_update['u_id']; ?></td>
      <td><?php echo $row_update['u_name']; ?></td>
      <td><?php echo $row_update['password']; ?></td>
      <td><?php echo $row_update['f_name']; ?></td>
      <td><?php echo $row_update['l_name']; ?></td>
      <td><?php echo $row_update['contact_no']; ?></td>
      <td><?php echo $row_update['dob']; ?></td>
      <td><?php echo $row_update['institute']; ?></td>
      <td><?php echo $row_update['stream']; ?></td>
      <td><?php echo $row_update['role']; ?></td>
      <td><?php echo $row_update['app_stat']; ?></td>
    </tr>
    <?php } while ($row_update = mysql_fetch_assoc($update)); ?>
</table>
<form id="form1" name="form1" method="POST" action="<?php echo $editFormAction; ?>">
  <p>
    <label for="app_id">Final Status: </label>
    <select name="app_id" id="app_id">
      <option value="0">  </option>
      <option value="1">Approved</option>
      <option value="2">Blocked</option>
      <option value="3">Rejected</option>
    </select>
    <label for="u_id"><br />
    Enter User Id</label>
    <input type="text" name="u_id" id="u_id" />
  </p>
  <p>
    <input type="submit" name="update" id="update" value="Update" />
  </p>
  <input type="hidden" name="MM_update" value="form1" />
</form>
</body>
</html>
<?php
mysql_free_result($update);
?>