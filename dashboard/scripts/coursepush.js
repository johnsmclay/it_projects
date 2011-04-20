function searchCourse()
{
	var sCValue=$("searchCourseValue").value;
	var sCFrom=$("searchCourseDB").value;
	if(sCValue!=""&&sCValue!=null)
	{
		sendReq("POST", true, "./scripts/coursepush.php", "sCValue="+sCValue+"&sCFrom="+sCFrom, searchCourseDisplay);
	};
}

function searchCourseDisplay(responseJSON)
{
	var response=JSON.decode(responseJSON);
	$("searchCourseDBResult").erase("html");
	if(response.length!=0)
	{
		$("searchCourseResult").set({"html": "<span id=\"courseId\">"+response["id"]+"</span> - "+response["uid"]+" - "+response["name"]});
		var i=0;
		for(i=0; i<response["services"].length; i++)
		{
			var serviceOption=new Element("option", {"html": response["services"][i][0], "value": response["services"][i][1]});
			serviceOption.inject($("searchCourseDBResult"));
		};
		$("pushCourseButton").erase("disabled");
	}
	else
	{
		$("searchCourseResult").set({"html": "None"});
		$("pushCourseButton").set({"disabled": "disabled"});
	};
}

function pushCourse()
{
	$("queryCourseResult").erase("html");
	var pCId=$("courseId").get("html");
	var pCFrom=$("searchCourseDB").value;
	var pCTo=$("searchCourseDBResult").value;
	sendReq("POST", true, "./scripts/coursepush.php", "pCId="+pCId+"&pCFrom="+pCFrom+"&pCTo="+pCTo, pushCourseDisplay);
}

function pushCourseDisplay(responseText)
{
	var response=responseText;
	$("queryCourseResult").set({"html": "Done", "class": "successText"});
	$$("option."+response)[0].selected=true;
	searchCourse();
}
