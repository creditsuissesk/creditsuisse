function addQuestion() {
	var question = document.getElementById("ques").value;
	var opt1 = document.getElementById("opt1").value;
	var opt2 = document.getElementById("opt2").value;
	var opt3 = document.getElementById("opt3").value;
	var opt4 = document.getElementById("opt4").value;
	var correct=0;
	var cId = document.getElementById("c_id").value;
	if(document.getElementById("r1").checked) {correct=1;}
	else if(document.getElementById("r2").checked) {correct=2;}
	else if(document.getElementById("r3").checked) {correct=3;}
	else if(document.getElementById("r4").checked) {correct=4;}
	if (question!="" && opt1!="" && opt2!="" && opt3!="" && opt4!="" && correct>0) {
		//all fields are filled
		if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
	  		xmlhttp=new XMLHttpRequest();
	  	} else {// code for IE6, IE5
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange=function() {
			if (xmlhttp.readyState==4 && xmlhttp.status==200) {
		    	//document.getElementById("courselist").innerHTML=xmlhttp.responseText;
				alert("Question added successfully!");
				showquestions();
			}
		}
		xmlhttp.open("GET","course_eval.php?c_id="+cId+"&ques="+question+"&opt1="+opt1+"&opt2="+opt2+"&opt3="+opt3+"&opt4="+opt4+"&correct="+correct,true);
		xmlhttp.send();
	} else {
		alert("Please fill all the fields");
	}
}

function showquestions() {
	var cId = document.getElementById("c_id").value;
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
    document.getElementById("questionslist").innerHTML=xmlhttp.responseText;
    }
  }
xmlhttp.open("GET","course_eval.php?show_questions="+cId,true);
xmlhttp.send();
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
	}else if (type=="presentation") {
		height_set=520;
		width_set=920;
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

//function to show user float
	function showUser(id,name) {
	light = new LightFace.IFrame({
		height:400,
		width:500,
		url: 'show_user_float.php?u_id='+id,
		title: name
		}).addButton('Close', function() { light.close(); },true).open();
	}

function updateResource(id,r_name,co_name) {
	light = new LightFace.IFrame({
		height:160,
		width:350,
		url: 'update.php?id='+id+"&co_name="+co_name+"&r_name="+r_name,
		title: r_name
		}).addButton('Close', function() { light.close(); },true).open();
}