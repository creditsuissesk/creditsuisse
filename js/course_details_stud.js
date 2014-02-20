function takeTest() {
	var cId = document.getElementById("hiddenId").value;
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
    document.getElementById("content-holder").innerHTML=xmlhttp.responseText;
    }
  }
xmlhttp.open("GET","course_eval.php?takeTest="+cId,true);
xmlhttp.send();
}

function submitTest(noOfQuestions) {
	 var inputs = document.getElementById("evalform").elements;
	 var radios = [];
	 var query = ""; 
	 var solved=0;
	 for (var i = 0; i < inputs.length; ++i) {
        if (inputs[i].type == 'radio') {
            radios.push(inputs[i]);
        }
    }
	var firstFlag=0;
	for (var i = 0; i < radios.length; i++) {
        if (radios[i].checked) {
			var pos=radios[i].value.toString().indexOf("-");
			query+="&"+radios[i].value.toString().substring(0,pos)+"="+radios[i].value.toString().substring(pos+1);
			solved++;
        }
    }
	if (solved==noOfQuestions) {
		var cId = document.getElementById("hiddenId").value;
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
		    document.getElementById("content-holder").innerHTML=xmlhttp.responseText;
		    }
		  }
		xmlhttp.open("GET","course_eval.php?evalTest="+cId+query,true);
		xmlhttp.send();
	}else {
		alert("You must attempt all questions in order to get evaluated!");
	}
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
	}
	light = new LightFace.IFrame({
		height:height_set,
		width:width_set,
		url: 'show_resource_float.php?actiontype=loadResource&r_id='+id,
		title: 'Resource : '+name
		}).addButton('Close', function() { light.close(); },true).open();		
}

function showRating(id,name) {
	light = new LightFace.IFrame({
		height:150,
		width:200,
		url: 'show_resource_float.php?actiontype=loadRating&r_id='+id,
		title: name
		}).addButton('Close', function() { light.close(); },true).open();
}

function showQR(id,name) {
	light = new LightFace.IFrame({
		height:170,
		width:170,
		url: 'show_resource_float.php?actiontype=loadQR&r_id='+id,
		title: name
		}).addButton('Close', function() { light.close(); },true).open();
}

function enrollCourse(cId) {
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
				//var e = document.getElementById("sortdropdown");
				//var strUser = e.options[e.selectedIndex].value;
				//sortCourses(strUser,0);
				window.location.reload();
			}
			else {
				//ele.innerHTML='<a id="'+ele.id+'" class="enroll">Enrolling...</a>';
			}
		}
		xmlhttp.open("GET","show_courses.php?enrollId="+cId,true);
		xmlhttp.send();
	}
}

function recoCourse(cId) {
	light = new LightFace.IFrame({
		height:320,
		width:620,
		url: 'course_reco.php?c_id='+cId,
		title: "Select student to refer course"
		}).addButton('Close', function() { light.close(); },true).open();
}

