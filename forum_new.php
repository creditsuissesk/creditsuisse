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

mysql_select_db($database_conn, $conn);
$query_categories = "SELECT * FROM discussion_category";
$categories = mysql_query($query_categories, $conn) or die(mysql_error());
$row_categories = mysql_fetch_assoc($categories);
$totalRows_categories = mysql_num_rows($categories);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<script src="SpryAssets/SpryTabbedPanels.js" type="text/javascript"></script>
<link href="SpryAssets/SpryTabbedPanels.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" media="screen" href="css/templatemo_style.css" />
<link rel="stylesheet" type="text/css" media="screen" href="css/forum_new.css?12" />
</head>

<body>
<h1> Forums</h1>
<div id="TabbedPanels1" class="TabbedPanels">
  <ul class="TabbedPanelsTabGroup">
    <li class="TabbedPanelsTab" tabindex="0">Recent Activity</li>
    <li class="TabbedPanelsTab" tabindex="0">Discussions</li>
    <li class="TabbedPanelsTab" tabindex="0">Bookmarks</li>
  </ul>
  <div class="TabbedPanelsContentGroup">
    <div class="TabbedPanelsContent">
    <!--- Recent Activity--->
    Content1
    
    </div>
    <div class="TabbedPanelsContent">
    	<!--- Discussions-->
        <div class="forum-wrapper">
	    	<div class="forum-content-wrapper">
			    <div class="forum-content">
                <!--- get each category in while loop--->
                <?php do { ?>
                <forum-h4> <?php echo $row_categories['category_name']?></forum-h4>
                <!--- now get all discussions of that category and show them in a loop--->
                <?php
				mysql_select_db($database_conn, $conn);
$query_discussions = sprintf("SELECT * FROM discussion JOIN user ON discussion.insert_uid=user.u_id WHERE discussion.category_id=%s",GetSQLValueString($row_categories['category_id'], "int"));
$discussions = mysql_query($query_discussions, $conn) or die(mysql_error());
$row_discussions = mysql_fetch_assoc($discussions);
$totalRows_discussions = mysql_num_rows($discussions); 
				?>
                <!--- make list of all the discussions under that category--->
                <dl>
                <?php do { ?>
                    <dt>
					    <?php echo $row_discussions['name'];?>
				    </dt>
                    <datetime><?php echo "By ".$row_discussions['f_name']." ".$row_discussions['l_name']." on ".$row_discussions['date_inserted'];?></datetime>
                    <dd>
					    <?php echo $row_discussions['body'];?>
                    </dd>
				    
                    <?php } while($row_discussions=mysql_fetch_assoc($discussions));?>
                    </dl>
                <?php }while ($row_categories = mysql_fetch_assoc($categories)); ?>
			    </div>
		    </div>
	    </div>
    </div>
    <div class="TabbedPanelsContent">Content 3
    <!--- Bookmarks--->
    </div>
  </div>
</div>
<script type="text/javascript">
var TabbedPanels1 = new Spry.Widget.TabbedPanels("TabbedPanels1");
</script>
</body>
</html>
<?php
mysql_free_result($categories);
?>
<?php
mysql_free_result($discussions);
?>