<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>
<body>
<?php

 # Init the MySQL Connection
  $db = mysql_connect( 'localhost' , 'root' , '' );
  mysql_select_db( 'creditsuisse' );

 # Prepare the SELECT Query

 # Execute the SELECT Query
  if( !( $selectRes = mysql_query( 'SELECT * FROM student') ) ){
    echo 'Retrieval of data from Database Failed - #'.mysql_errno().': '.mysql_error();
  }else{
    ?>
<table border="2">
  <thead>
    <tr>
      <th>email</th>
      <th>f_name</th>
      <th>l_name</th>
      <th>password</th>
      <th>stream</th>
    </tr>
  </thead>
  <tbody>
    <?php
      if( mysql_num_rows( $selectRes )==0 ){
        echo '<tr><td colspan="4">No Rows Returned</td></tr>';
      }else{
        while( $row = mysql_fetch_assoc( $selectRes ) ){
          echo "<tr><td>{$row['email']}</td><td>{$row['f_name']}</td><td>{$row['l_name']}</td><td>{$row['password']}</td><td>{$row['stream']}</td></tr>\n";
        }
      }
  }
    ?>
  </tbody>
</table>

</body>
</html>