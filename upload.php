<?php require_once('Connections/conn.php'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Display</title>
</head>

<body>

<?php
// if something was posted, start the process...
if(isset($_POST['upload']))
{

// define the posted file into variables
$name = $_FILES['picture']['name'];
$tmp_name = $_FILES['picture']['tmp_name'];
$type = $_FILES['picture']['type'];
$size = $_FILES['picture']['size'];

// get the width & height of the file (we don't need the other stuff)
list($width, $height, $typeb, $attr) = getimagesize($tmp_name);
    
// if width is over 600 px or height is over 500 px, kill it    
if($width>600 || $height>500)
{
echo $name."'s dimensions exceed the 600x500 pixel limit.";?>
<a href="form.html">Click here</a> to try again.
<?php die();
}

// if the mime type is anything other than what we specify below, kill it    
if(!(
$type=='image/jpeg' ||
$type=='image/png' ||
$type=='image/gif'
)) {
echo $type .  " is not an acceptable format.";?>
<div> <a href="form.html">Click here</a> to try again. </div>
<?php
}

// if the file size is larger than 350 KB, kill it
if($size>'350000') {
echo $name . " is over 350KB. Please make it smaller.";
?> <a href="form.html">Click here</a> to try again. <?php 
die();
}
// if your server has magic quotes turned off, add slashes manually
if(!get_magic_quotes_gpc()){
$name = addslashes($name);
}

// open up the file and extract the data/content from it
$extract = fopen($tmp_name, 'r');
$content = fread($extract, $size);
$content = addslashes($content);
fclose($extract);  
// the query that will add this to the database
$addfile = "INSERT INTO resource (c_id,type_id,filename, file_type,file_size, file_content,uploaded_by ) ".
           "VALUES ('18','7','$name','$type',  '$size','$content','2')";
mysql_select_db($database_conn, $conn);
mysql_query($addfile,$conn) or die(mysql_error());

// get the last inserted ID if we're going to display this image next
$inserted_fid = mysql_insert_id();

mysql_close();
header( "Location: form.html");

// we still have to close the original IF statement. If there was nothing posted, kill the page.
}else{die("No uploaded file present");
}
?></body>
</html>