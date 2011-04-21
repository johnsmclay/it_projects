function searchCourse()
{
	var sCFrom=$("searchCourseDB").value;
	if(sCFrom!=0)
	{
		if($("defaultFrom"))
		{
			$("defaultFrom").dispose();
		};
		sendReq("POST", true, "./scripts/coursepush.php", "sCFrom="+sCFrom, searchCourseDisplay);
	};
}

function searchCourseDisplay(responseJSON)
{
	var response=JSON.decode(responseJSON);
	$("searchCourseDBResult").erase("html");
	$("searchCourseResult").erase("html");
	if(response.length!=0)
	{
		var i=0;
		for(i=0; i<response["courses"].length; i++)
		{
			var courseOption=new Element("option", {"html": response["courses"][i]["id"]+" - "+response["courses"][i]["uid"]+" - "+response["courses"][i]["name"], "value": response["courses"][i]["id"]});
			courseOption.inject($("searchCourseResult"));
		};
		i=0;
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
	var pCId=$("searchCourseResult").value;
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
