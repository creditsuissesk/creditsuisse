<?php
function deleteComment($commentId,$post_uid) {
		//get comment's details for score etc
		$query_comment = sprintf("SELECT * FROM `comment` WHERE comment_id=%s",GetSQLValueString($commentId, "int"));
		$comment = mysql_query($query_comment, $GLOBALS['conn']) or die(mysql_error());
		$row_comment = mysql_fetch_assoc($comment);
		$totalRows_comment = mysql_num_rows($comment);
		
		//if user is actually comment poster then only proceed
		//if($_SESSION['MM_UserID']==$row_comment['insert_uid']) {
			//delete comment
			$delete_comment = sprintf("DELETE FROM `comment` WHERE comment_id=%s",GetSQLValueString($commentId, "int"));
			$comment_delete = mysql_query($delete_comment, $GLOBALS['conn']) or die(mysql_error());
			
				//comment deleted successfully now decrement poster's comment count and score
				$update_user = sprintf("UPDATE `user` SET created_comments=created_comments-1, user_score=user_score-%s WHERE u_id=%s",GetSQLValueString($row_comment['comment_score'], "int"),GetSQLValueString($post_uid, "int"));
				$result_update_user = mysql_query($update_user, $GLOBALS['conn']) or die(mysql_error());
				
				//now decrement discussion's comment count
				$update_disc = sprintf("UPDATE `discussion` SET count_comments=count_comments-1 WHERE discussion_id=%s",GetSQLValueString($row_comment['discussion_id'], "int"));
				$result_update_user = mysql_query($update_disc, $GLOBALS['conn']) or die(mysql_error());
		//}
		$insertGoTo = "index.php";
		if (isset($_SERVER['QUERY_STRING'])) {
		  $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
		  $insertGoTo .= $_SERVER['QUERY_STRING'];		 
		}
	}
?>