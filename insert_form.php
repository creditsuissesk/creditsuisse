<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>



<body>

<script>
function update(){
var f = document.createElement("form");
f.setAttribute('method',"post");
f.setAttribute('action',"submit.php");

var i = document.createElement("input"); //input element, text
i.setAttribute('type',"text");
i.setAttribute('name',"username");

var s = document.createElement("input"); //input element, Submit button
s.setAttribute('type',"submit");
s.setAttribute('value',"Submit");

f.appendChild(i);
f.appendChild(s);

//and some more input elements here
//and dont forget to add a submit button
document.getElementsByTagName('body')[0].appendChild(f);
}
</script>

<h1>Registration : </h1>
<form id="form1" name="form1" method="post" action="insert_test.php">
  <p>
    <label for="email">E-Mail</label>
    <input type="text" name="email" id="email" />
  </p>
  <p>
    <label for="f_name">First Name :</label>
    <input type="text" name="f_name" id="f_name" />
  </p>
  <p>
    <label for="l_name">Last Name : </label>
    <input type="text" name="l_name" id="l_name" />
  </p>
  <p>
    <label for="password">Password</label>
    <input type="password" name="password" id="password" />
  </p>
  <p>
    <label for="stream2">Stream : </label>
    <input type="text" name="stream" id="stream2" />
  </p>
  <p>
    <label for="category">Category :</label>
    <select name="category" size="1" id="category" onchange="update()">
      <option>--Select--</option>
      <option>Student</option>
      <option>Author</option>
    </select>
  </p>
  <p>
    <input type="submit" name="submit" id="submit" value="Submit" />
  </p>
</form> 
</body>
</html>