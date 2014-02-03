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
}?>
<!--- new discussion post script starts --->
<?php

$newDiscAction = $_SERVER['PHP_SELF'];
	  if (isset($_SERVER['QUERY_STRING'])) {
		$newDiscAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
	  }
	  ?>
<!---new discussion script ends --->
<?php
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
<link rel="stylesheet" type="text/css" href="css/forum_disc_form.css" media="all" />
<!---<link rel="stylesheet" type="text/css" href="css/registration.css" media="all"/> --->

<!--- files for voting--->
<script src="lib/jQuery.js" type="text/javascript"></script>
<script src="lib/jquery.upvote.js" type="text/javascript"></script>
<link href="lib/jquery.upvote.css" rel="stylesheet" type="text/css">

<!--- new discussion form validation--->
<script type="text/javascript">
	  function MM_validateForm() { //v4.0
		if (document.getElementById){
		  var i,p,q,nm,test,num,min,max,errors='',args=MM_validateForm.arguments;
		  for (i=0; i<(args.length-2); i+=3) { test=args[i+2]; val=document.getElementById(args[i]);
			if (val) { nm=val.name; if ((val=val.value)!="") {
			  if (test.indexOf('isEmail')!=-1) { p=val.indexOf('@');
				if (p<1 || p==(val.length-1)) errors+='- '+nm+' must contain an e-mail address.\n';
			  } else if (test!='R') { num = parseFloat(val);
				if (isNaN(val)) errors+='- '+nm+' must contain a number.\n';
				if (test.indexOf('inRange') != -1) { p=test.indexOf(':');
				  min=test.substring(8,p); max=test.substring(p+1);
				  if (num<min || max<num) errors+='- '+nm+' must contain a number between '+min+' and '+max+'.\n';
			} } } else if (test.charAt(0) == 'R') errors += '- '+nm+' is required.\n'; }
		  } if (errors) alert('The following error(s) occurred:\n'+errors);
		  document.MM_returnValue = (errors == '');
	  } }
</script>
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
		<li id="headerbar"><a href="forum_new.php?mode=showmain">Forums</a></li>
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

                <?php if (isset($_GET['mode']) && $_GET['mode']=="disc") { ?>
                	<?php $tabToShow=1;?>
                    <a href="forum_new.php?showTab=discussions&mode=showmain"> Back to discussions </a><br />
					<!--- viewing a particular discussion--->
                    <?php
					mysql_select_db($database_conn, $conn);
					$query_disc = sprintf("SELECT * FROM `discussion` JOIN `user` ON insert_uid=u_id LEFT OUTER JOIN user_discussion ON discussion.discussion_id=user_discussion.user_discussion_id AND user_discussion.u_id=%s WHERE discussion.discussion_id =%s",GetSQLValueString($_SESSION['MM_UserID'], "int"),GetSQLValueString($_GET['discussionid'], "int"));
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
                            <div id="disc<?php echo $row_disc['discussion_id']; ?>" class="upvote">
							    <a class="upvote"></a>
							    <span class="count">0</span>
							    <a class="downvote"></a>
							    <a class="star"></a>
						    </div>
						</aside><!-- .right-sidebar -->

					</div><!-- .middle-->
					
					<?php
					mysql_select_db($database_conn, $conn);
					$query_comments = sprintf("SELECT * FROM `discussion` JOIN `comment` ON discussion.discussion_id = comment.discussion_id JOIN `user` ON comment.insert_uid = user.u_id LEFT OUTER JOIN `user_comment` ON comment.comment_id = user_comment.user_comment_id AND user_comment.user_id=%s WHERE discussion.discussion_id =%s ORDER BY date_inserted_c",GetSQLValueString($_SESSION['MM_UserID'], "int"),GetSQLValueString($_GET['discussionid'], "int"));
					$comments = mysql_query($query_comments, $conn) or die(mysql_error());
					$row_comments = mysql_fetch_assoc($comments);
					$totalRows_comments = mysql_num_rows($comments);
					?>
                    <!---discussion header loaded. Update in user_discussion --->
                    <?php 
					$query_update_disc = sprintf("INSERT INTO user_discussion(u_id,user_discussion_id,seen_comments,date_last_viewed,bookmarked) VALUES ('%s','%s','%s',now(),'0') ON DUPLICATE KEY UPDATE date_last_viewed=now(),seen_comments=%s;",GetSQLValueString($_SESSION['MM_UserID'], "int"),GetSQLValueString($_GET['discussionid'], "int"),GetSQLValueString($totalRows_comments, "int"),GetSQLValueString($totalRows_comments, "int"));
					$update_disc = mysql_query($query_update_disc, $conn) or die(mysql_error());
                    ?>
                    <!--- viewing comments if there are any --->
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
                            <div id="comment<?php echo $row_comments['comment_id']; ?>" class="upvote">
							    <a class="upvote"></a>
							    <span class="count">0</span>
							    <a class="downvote"></a>
							    <!---<a class="star"></a> --->
						    </div>
                                                       
						</aside><!-- .right-sidebar -->
					</div><!-- .middle-->
                    <!--- comment is displayed. now update this info in user_comment --->
                    <?php 
					$query_update_comment = sprintf("INSERT INTO user_comment(user_comment_id,user_id,vote_status,bookmarked,date_last_viewed) VALUES ('%s','%s','0','0',now()) ON DUPLICATE KEY UPDATE date_last_viewed=now();",GetSQLValueString($row_comments['comment_id'], "int"),GetSQLValueString($_SESSION['MM_UserID'], "int"));
					$update_comment = mysql_query($query_update_comment, $conn) or die(mysql_error());
					?>
                    	<?php }while ($row_comments = mysql_fetch_assoc($comments)) ;?>
                        
                        <!--- user comment was here--->
                        <!--- javascript for all voting --->
                        <script language="javascript">
							var callback = function(data) {
							$.ajax({
						        url: 'voter.php',
						        type: 'post',
						        data: { id: data.id, up: data.upvoted, down: data.downvoted, count: $('#comment'+ data.id).upvote('count'),upstatus:$('#comment'+data.id).upvote('upvoted'),downstatus:$('#comment'+data.id).upvote('downvoted')},
						    	});	
							};
							var callback2= function(data) {
								alert("You can't vote yourself");
								$('#comment'+data.id).upvote();
							};
							
							var disc_callback = function(data) {
							$.ajax({
						        url: 'voter_disc.php',
						        type: 'post',
						        data: { id: data.id, up: data.upvoted, down: data.downvoted, star: data.starred , count: $('#disc'+ data.id).upvote('count'),upstatus:$('#disc'+data.id).upvote('upvoted'),downstatus:$('#disc'+data.id).upvote('downvoted')},
						    	});	
							};
							var disc_callback2= function(data) {
								alert("You can't vote yourself");
								$('#disc'+data.id).upvote();
							};
							<!--- script for discussion vote widget --->
							<?php if($row_disc['insert_uid']==$_SESSION['MM_UserID']){?>
								$('#disc<?php echo $row_disc['discussion_id'];?>').upvote({count: <?php echo $row_disc['rating'];?>,id: <?php echo $row_disc['discussion_id'];?>, callback: disc_callback2});
							<?php } else { ?>
									$('#disc<?php echo $row_disc['discussion_id'];?>').upvote({count: <?php echo$row_disc['rating'];?>,id: <?php echo $row_disc['discussion_id'];?>, callback: disc_callback
									<?php if ($row_disc['vote_status']==1) {
										echo ",upvoted:1";
									}else if ($row_disc['vote_status']==-1){
										echo ",downvoted:1";
									}
									if ($row_disc['bookmarked']==1) {
										echo ",starred:1";
									}
									?>
									});
							<?php }?>
							
							<!--- script for comment vote widgets --->
							<?php $comments = mysql_query($query_comments, $conn) or die(mysql_error());?>
							<?php
								while ($row_comments = mysql_fetch_assoc($comments)) {?>
									<?php if($row_comments['insert_uid']==$_SESSION['MM_UserID']) {?>
											$('#comment<?php echo $row_comments['comment_id'];?>').upvote({count: <?php echo $row_comments['comment_score'];?>,id: <?php echo $row_comments['comment_id'];?>, callback: callback2});
									<?php }else {?>
									$('#comment<?php echo $row_comments['comment_id'];?>').upvote({count: <?php echo $row_comments['comment_score'];?>,id: <?php echo $row_comments['comment_id'];?>, callback: callback
									<?php if ($row_comments['vote_status']==1) {
										echo ",upvoted:1";
									}else if ($row_comments['vote_status']==-1){
										echo ",downvoted:1";
									}
									?>
									});
							<?php } 
							}?>
						</script>
                   	<?php } ?>
                    <!---show new comment form--->
                        <?php
                        $query_own_comment = sprintf("SELECT * from `user` WHERE u_id=%s;",GetSQLValueString($_SESSION['MM_UserID'], "int"));
					$own_comment = mysql_query($query_own_comment, $conn) or die(mysql_error());
					$row_own_comment = mysql_fetch_assoc($own_comment);
					?>
                    <div class="middle">
                        <div class="container">
                        <main class="content">
                    <form action="new_comment.php" id="new_comment" name="new_comment" method="POST">
                    <p><label for="comment_body">Enter your comment :</label><br /></p>
					<textarea type="comment_body" id="comment_body" name="comment_body" form="new_comment"> </textarea>
                    <br /><br />
                    <p>
                      <input name="submit" id="submit" value="Post Comment" type="submit" class="buttom" onClick="MM_validateForm('comment_body','','R');return document.MM_returnValue" action="new_comment.php"/></p>
                      <input type="hidden" name="disc_id" value="<?php echo $_GET['discussionid'];?>"/>
                    </form>
                    	</main>
	                    </div>
                    <aside class="left-sidebar">
                        	<img src="<?php echo $row_own_comment['photo'];?>" width="100" height="100" alt="Profile picture"/><br />
							<dt><?php echo $row_own_comment['f_name']." ".$row_own_comment['l_name'];?></dt>
					</aside><!-- .left-sidebar -->
                    </div> 
                    <!---end of discussion case --->                  
                <?php	
				} else if (isset($_GET['mode']) && $_GET['mode']=="showmain") {
				?>
                <!--- discussionid not set, so get each category in while loop--->
                <a href="forum_new.php?showTab=discussions&mode=newDisc"><input name="new_disc" type="button" value="New Discussion" style="float:right;margin-right: 35px;" class="buttom" /></a>
                <br />
                <!--- script and php for discussion voting in list view--->
                <script>
                var disc_callback = function(data) {
							$.ajax({
						        url: 'voter_disc.php',
						        type: 'post',
						        data: { id: data.id, up: data.upvoted, down: data.downvoted, star: data.starred , count: $('#disc'+ data.id).upvote('count'),upstatus:$('#disc'+data.id).upvote('upvoted'),downstatus:$('#disc'+data.id).upvote('downvoted')},
						    	});	
							};
							var disc_callback2= function(data) {
								alert("You can't vote yourself");
								$('#disc'+data.id).upvote();
							};
				</script>   
                <!--- end of script and php for discussion voting in list view --->
                <?php do { ?>
                <forum-h4> <?php echo $row_categories['category_name']?></forum-h4>
                <!--- now get all discussions of that category and show them in a loop--->
                <?php
				mysql_select_db($database_conn, $conn);
$query_discussions = sprintf("SELECT * FROM discussion JOIN user ON discussion.insert_uid=user.u_id LEFT OUTER JOIN `user_discussion` ON discussion.discussion_id=user_discussion.user_discussion_id AND user_discussion.u_id=%s WHERE discussion.category_id=%s",GetSQLValueString($_SESSION['MM_UserID'], "int"),GetSQLValueString($row_categories['category_id'], "int"));
$discussions = mysql_query($query_discussions, $conn) or die(mysql_error());
$row_discussions = mysql_fetch_assoc($discussions);
$totalRows_discussions = mysql_num_rows($discussions);
				?>
                <!--- make list of all the discussions under that category--->
                <!---<dl> --->
                
                <?php do { ?>
                	<div class="middle">
                    	<div class="container">
                    	<main class="content">
                        <dt>
                        <a href="forum_new.php?showTab=discussions&mode=disc&discussionid=<?php echo $row_discussions['discussion_id'];?> "> <?php echo $row_discussions['name'];?> </a> </dt>
                        <datetime><?php echo "By ".$row_discussions['f_name']." ".$row_discussions['l_name']." on ".$row_discussions['date_inserted_d'];?></datetime> <br />
                        <dd>
                        <?php if (strlen($row_discussions['disc_body'])>400) {
							echo substr($row_discussions['disc_body'],0,400)."....";
							}else {
								echo $row_discussions['disc_body'];
							}
						?>
                        </dd>
                        </main>
                    	</div>
                        <aside class="left-sidebar">
                        <table border="0">
                        <tr>
                        <th><dd>Comments </dd> </th>
                        </tr>
                        <tr>
                        <td><dd><?php echo $row_discussions['count_comments']; ?> </dd></td>
                        </tr>
                        </table>
                        </aside>
                        <aside class="right-sidebar">
                        	<div id="disc<?php echo $row_discussions['discussion_id']; ?>" class="upvote">
							    <a class="upvote"></a>
							    <span class="count">0</span>
							    <a class="downvote"></a>
							    <a class="star"></a>
						    </div>
                        </aside>
                    </div>
                    <!--- script for discussion vote widget --->
                    <script>
							<?php if($row_discussions['insert_uid']==$_SESSION['MM_UserID']){?>
								$('#disc<?php echo $row_discussions['discussion_id'];?>').upvote({count: <?php echo $row_discussions['rating'];?>,id: <?php echo $row_discussions['discussion_id'];?>, callback: disc_callback2});
							<?php } else { ?>
									$('#disc<?php echo $row_discussions['discussion_id'];?>').upvote({count: <?php echo $row_discussions['rating'];?>,id: <?php echo $row_discussions['discussion_id'];?>, callback: disc_callback
									<?php if ($row_discussions['vote_status']==1) {
										echo ",upvoted:1";
									}else if ($row_discussions['vote_status']==-1){
										echo ",downvoted:1";
									}
									if ($row_discussions['bookmarked']==1) {
										echo ",starred:1";
									}
									?>
									});
							<?php }?>
                </script>
                    
                    <?php } while($row_discussions=mysql_fetch_assoc($discussions));?>
                <?php }while ($row_categories = mysql_fetch_assoc($categories)); ?>
                <?php
						mysql_free_result($categories);
						mysql_free_result($discussions);
				?>
                <!--- end of discussion list menu--->
                <?php }else if (isset($_GET['mode']) && $_GET['mode']=="newDisc"){?>
                	<!--- new discussion --->
                    <a href="forum_new.php?showTab=discussions&mode=showmain"> Back to discussions </a><br />
                	<disc-title> New Discussion </disc-title>
                    <div class="form">
                    <form action="new_discussion.php" name="new_disc_form" id="new_disc_form" method="POST">
                    <p><label for="disc_name">Discussion Name :</label> 
					  <input type="disc_name" id="disc_name" name="disc_name" placeholder="Discussion title here..." /> </p>
                      <p>
                    <label for="category">Discussion Category :</label>
		  	 	  <select class="select-style gender" name="category" id="category" placeholder="I am...">
					<?php
						mysql_select_db($database_conn, $conn);
						$query_disc_cat = sprintf("SELECT * FROM discussion_category");
						$disc_cat = mysql_query($query_disc_cat, $conn) or die(mysql_error());
						$row_disc_cat = mysql_fetch_assoc($disc_cat);
						$totalRows_disc_cat = mysql_num_rows($disc_cat);
						do {?>
                        <option value="<?php echo $row_disc_cat['category_id'];?>"><?php echo $row_disc_cat['category_name'];?></option>
						<?php }while($row_disc_cat = mysql_fetch_assoc($disc_cat));?>
				  </select><br /><br />
				  </p>
                    <p><label for="disc_body">Discussion Body :</label><br /></p>
					<textarea type="disc_body" id="disc_body" name="disc_body" form="new_disc_form"> </textarea>
                    <br /><br />
                    <p>
                      <input name="submit" id="submit" value="Create Discussion" type="submit" class="buttom" onClick="MM_validateForm('disc_name','','R','disc_body','','R');return document.MM_returnValue" action="new_discussion.php"/></p>
                    </form>
                    </div>
                    
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