function sortCourses(str)
{
if (str=="")
  {
  document.getElementById("txtHint").innerHTML="";
  return;
  }
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
    document.getElementById("courselist").innerHTML=xmlhttp.responseText;
    }
  }
xmlhttp.open("GET","show_courses.php?sortType="+str,true);
xmlhttp.send();
}

function enrollCourse(ele) {
	var r=confirm("Are you sure you want to enroll for this course?");
	if (r==true) {
		if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
		  xmlhttp=new XMLHttpRequest();
		} else {// code for IE6, IE5
		  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  		}
		xmlhttp.onreadystatechange=function()
		{
		  if (xmlhttp.readyState==4 && xmlhttp.status==200) {
		    //document.getElementById("courselist").innerHTML=xmlhttp.responseText;
				var e = document.getElementById("sortdropdown");
				var strUser = e.options[e.selectedIndex].value;
				//sortCourses(strUser);
				window.location.reload();
			}
			else {
				ele.innerHTML='<a id="'+ele.id+'" class="enroll">Enrolling...</a>';
			}
		}
		xmlhttp.open("GET","show_courses.php?enrollId="+ele.id,true);
		xmlhttp.send();
	}
}

function searchCourses() {
	var e = document.getElementById("searchdropdown");
	var searchtype = e.options[e.selectedIndex].value;
	var searchkey = document.getElementById("searchword").value;
	if (searchkey=="") {
		var e = document.getElementById("sortdropdown");
			var strUser = e.options[e.selectedIndex].value;
			sortCourses(strUser);
	} else {
		if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
	  		xmlhttp=new XMLHttpRequest();
	  	} else {// code for IE6, IE5
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange=function() {
			if (xmlhttp.readyState==4 && xmlhttp.status==200) {
		    	document.getElementById("courselist").innerHTML=xmlhttp.responseText;
			}
		}
		xmlhttp.open("GET","show_courses.php?searchType="+searchtype+"&searchKey="+searchkey,true);
		xmlhttp.send();
	}
}

function sortresources(str)
{
	
if (str=="")
  {
  document.getElementById("txtHint").innerHTML="";
  return;
  }
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
    document.getElementById("resourcelist").innerHTML=xmlhttp.responseText;
	sortCourses(1);
    }
  }
xmlhttp.open("GET","show_resources.php?sortType="+str,true);
xmlhttp.send();

}
function searchresources() {
	var e = document.getElementById("searchdropdown1");
	var searchtype = e.options[e.selectedIndex].value;
	var searchkey = document.getElementById("searchword1").value;
	if (searchkey=="") {
		var e = document.getElementById("sortdropdown1");
			var strUser = e.options[e.selectedIndex].value;
			sortresources(strUser);
	} else {
		if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
	  		xmlhttp=new XMLHttpRequest();
	  	} else {// code for IE6, IE5
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange=function() {
			if (xmlhttp.readyState==4 && xmlhttp.status==200) {
		    	document.getElementById("resourcelist").innerHTML=xmlhttp.responseText;
			}
		}
		xmlhttp.open("GET","show_resources.php?searchType="+searchtype+"&searchKey="+searchkey,true);
		xmlhttp.send();
	}
}

//function to show user float
	function showUser(id,name) {
	light = new LightFace.IFrame({
		height:400,
		width:500,
		url: 'show_user_float.php?u_id='+id,
		title: name
		}).addButton('Close', function() { light.close(); },true).open();
	}
	
function showResource(id,type,name) {
	//show dimensions based on content type
	var height_set,width_set;
	if(type=="pdf") {
		height_set=520;
		width_set=920;
	}else if (type=="image") {
		height_set=320;
		width_set=620;
	}else if (type=="video") {
		height_set=336;
		width_set=635;
	}
	light = new LightFace.IFrame({
		height:height_set,
		width:width_set,
		url: 'show_resource_float.php?actiontype=loadResource&r_id='+id,
		title: 'Resource : '+name,
		onClose:function() {
			if(type=="video") {
				window.location.reload();
			}
		}
		}).addButton('Close', function() { light.close(); },true).open();		
}

function rateCourse(id,rate) {
	//rates course id with rate value. calls rate_course.php with c_id and rate_value
	if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
		  xmlhttp=new XMLHttpRequest();
		} else {// code for IE6, IE5
		  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  		}
		xmlhttp.onreadystatechange=function()
		{
		  if (xmlhttp.readyState==4 && xmlhttp.status==200) {
			}
			else {
			}
		}
		xmlhttp.open("GET","rate_course.php?c_id="+id+"&rate_value="+rate,true);
		xmlhttp.send();
}

function showResourceRating(id,name) {
	//loads the rating + flagging float.
	light = new LightFace.IFrame({
		height:120,
		width:250,
		url: 'show_resource_float.php?actiontype=loadRating&r_id='+id,
		title: name
		}).addButton('Close', function() { light.close(); },true).open();
}

