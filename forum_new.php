<?php require_once('Connections/conn.php'); ?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "admin,student,author,cm";
$MM_donotCheckaccess = "false";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = False; 

  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings into arrays. 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    // Or, you may restrict access to only certain users based on their username. 
    if (in_array($UserGroup, $arrGroups)) { 
      $isValid = true; 
    } 
    if (($strUsers == "") && false) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "userhome.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($_SERVER['QUERY_STRING']) && strlen($_SERVER['QUERY_STRING']) > 0) 
  $MM_referrer .= "?" . $_SERVER['QUERY_STRING'];
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}
?>
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
<title>Forums</title>
<script src="SpryAssets/SpryTabbedPanels.js" type="text/javascript"></script>
<link href="SpryAssets/SpryTabbedPanels.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" media="screen" href="css/templatemo_style.css" />
<link rel="stylesheet" type="text/css" media="screen" href="css/forum_new.css?1234" />
<link rel="stylesheet" type="text/css" media="screen" href="css/nav_bar.css" />
</head>

<body >
<!--- decide the tab number to show--->
<?php if ( isset($_GET['showTab'])) {
	if($_GET['showTab']=="discussions") {
		$tabToShow=1;
	}
}else {
	$tabToShow=0;
}
?>

<nav id="headerbar">
	<ul id="headerbar">
		<li id="headerbar"><a href="userhome.php">Home</a></li>
		<li id="headerbar"><a href="forum_new.php">Forums</a></li>
		<li id="headerbar"><a href="#"><?php echo $_SESSION['MM_Username'];?></a>
			<ul id="headerbar">
				<li id="headerbar"><a href="userhome.php?userTabToDisplay=5">Profile</a></li>
				<li id="headerbar"><a href="<?php echo $logoutAction ?>">Log Out</a>
				</li>
			</ul>
		</li>
	</ul>
</nav>
<br/>

<div>
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
<script language="javascript">
    						function changeImage() {
						        if (document.getElementById("voteup0").src == "images/arrow-up.png") {
						            document.getElementById("voteup0").src = "images/arrow-up-voted.png";
						        } else {
						            document.getElementById("voteup0").src = "images/arrow-up.png";
						        }
						    }
						</script>

                <?php if (isset($_GET['discussionid'])) { ?>
                	<?php $tabToShow=1;?>
                    <a href="forum_new.php?showTab=discussions"> Back to discussions </a><br />
					<!--- viewing a particular discussion--->
                    <?php
					mysql_select_db($database_conn, $conn);
					$query_disc = sprintf("SELECT * FROM `discussion` JOIN `user` ON insert_uid=u_id WHERE discussion_id =%s",GetSQLValueString($_GET['discussionid'], "int"));
					$disc = mysql_query($query_disc, $conn) or die(mysql_error());
					$row_disc = mysql_fetch_assoc($disc);
					$totalRows_disc = mysql_num_rows($disc);
					?>
                    <disc-title><?php echo $row_disc['name'];?> </disc-title>
                    <!--- show discussion title block--->
					 <div class="middle">
						<div class="container">
							<main class="content">
				                <dd><?php echo $row_disc['disc_body'];?></dd>
							</main><!-- .content -->
						</div><!-- .container-->

						<aside class="left-sidebar">
                        <img src="<?php echo $row_disc['photo'];?>" width="100" height="100" alt="Profile picture"/><br />
							<dt><?php echo $row_disc['f_name']." ".$row_disc['l_name'];?></dt>
						</aside><!-- .left-sidebar -->

						<aside class="right-sidebar">
							<dt><?php echo $row_disc['date_inserted_d'];?></dt>
						</aside><!-- .right-sidebar -->

					</div><!-- .middle-->

					<?php
					mysql_select_db($database_conn, $conn);
					$query_comments = sprintf("SELECT * FROM `discussion` JOIN `comment` ON discussion.discussion_id = comment.discussion_id JOIN `user` ON comment.insert_uid = user.u_id WHERE discussion.discussion_id =%s ORDER BY date_inserted_c",GetSQLValueString($_GET['discussionid'], "int"));
					$comments = mysql_query($query_comments, $conn) or die(mysql_error());
					$row_comments = mysql_fetch_assoc($comments);
					$totalRows_comments = mysql_num_rows($comments);
					?>
                    <!--- viewing comments if there are any --->
                    <?php $commentNumber=0; ?>
                    <?php if($totalRows_comments>0) {?>
	                    <?php do { ?>
                    <div class="middle">
						<div class="container">
							<main class="content">
				                <dt><dd><?php echo $row_comments['comment_body'];?></dd></dt>
							</main><!-- .content -->
						</div><!-- .container-->

						<aside class="left-sidebar">
                        	<img src="<?php echo $row_comments['photo'];?>" width="100" height="100" alt="Profile picture"/><br />
							<dt><?php echo $row_comments['f_name']." ".$row_comments['l_name'];?></dt>
						</aside><!-- .left-sidebar -->

						<aside class="right-sidebar">
							<dt><?php echo $row_comments['date_inserted_c'];?></dt>
                            <br />
                            <!--- vote up-down to be inserted here--->
                            <img id="voteup<?php echo $commentNumber++;?>" src="images/arrow-up.png" width="33" height="33" onclick="changeImage()" />
                            <br />
                            <score> 0 </dt> </score> <br />
                            <img id="votedown" src="images/arrow-down.png" width="33" height="33" />
                            
						</aside><!-- .right-sidebar -->
					</div><!-- .middle-->
                    	<?php }while ($row_comments = mysql_fetch_assoc($comments)) ;?>
                        
                   	<?php } ?>                   
                <?php	
				} else {
				?>
                <!--- discussionid not set, so get each category in while loop--->
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
					    <a href="forum_new.php?discussionid=<?php echo $row_discussions['discussion_id'];?> "> <?php echo $row_discussions['name'];?> </a>
				    </dt>
                    <datetime><?php echo "By ".$row_discussions['f_name']." ".$row_discussions['l_name']." on ".$row_discussions['date_inserted_d'];?></datetime>
                    <dd>
					    <?php echo $row_discussions['disc_body'];?>
                    </dd>
				    
                    <?php } while($row_discussions=mysql_fetch_assoc($discussions));?>
                    </dl>
                <?php }while ($row_categories = mysql_fetch_assoc($categories)); ?>
                <?php
						mysql_free_result($categories);
						mysql_free_result($discussions);
				?>
                <?php }?>
                <!---end of main if --->
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
var TabbedPanels1 = new Spry.Widget.TabbedPanels("TabbedPanels1",{defaultTab:<?php echo ($tabToShow);?>});
</script>
</div>
</body>
</html>