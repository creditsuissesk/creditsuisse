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

function showResourceRating(id,name) {
	//loads the rating + flagging float.
	light = new LightFace.IFrame({
		height:120,
		width:250,
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

function rateResource(id,rate) {
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
		xmlhttp.open("GET","voter_res.php?r_id="+id+"&rate_value="+rate,true);
		xmlhttp.send();	
}

function flag(r_id) {
	var r=confirm("Do you want to flag this resource ?");
		if (r==true){
			$.ajax({
				url: 'voter_res.php',
				type: 'post',
				data: { id: r_id,action:'flag'},
				success: function (response) {
				if(response==1) {
					document.getElementById('unflagged').src= 'images/red_flag.png';
				}
				}
			});	
	} 
}

function bookmarkResource(ele,id) {
	var bookmark=0;
	if(ele.id=="book_y"){
		//already bookmarked, remove bookmark
		bookmark=0;
	}else if (ele.id=="book_n"){
		//bookmark resource
		bookmark=1;
	}
	$.ajax({
		url: 'voter_res.php',
		type: 'post',
		data: { r_id: id,action:'bookmark',value:bookmark},
		success: function (response) {
		if(response==1 && bookmark==0) {
			document.getElementById(ele.id).src= 'images/bookmark_n.png';
			ele.title='Click to bookmark this resource';
			document.getElementById(ele.id).id= 'book_n';
		}else if(response==1 && bookmark==1){
			document.getElementById(ele.id).src= 'images/bookmark_y.png';
			ele.title='You have bookmarked this resource. Click to remove bookmark'
			document.getElementById(ele.id).id= 'book_y';
			
		}
		}
	});	
}