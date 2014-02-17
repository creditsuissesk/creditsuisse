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


function viewResource() {
	alert("function");
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
