function searchGrades()
{
	var sGTypeInputs=document.getElementsByName("searchGradeType");
	var sGType="";
	var i=0;
	for(i=0; i<sGTypeInputs.length; i++)
	{
		if(sGTypeInputs[i].checked==true)
		{
			sGType=sGTypeInputs[i].value;
		};
	};
	var sGValue=$("searchGradeValue").value;
	var sGServer=$("searchGradeService").value;
	sendReq("POST", true, "./scripts/gradeflush.php", "sGType="+sGType+"&sGValue="+sGValue+"&sGServer="+sGServer, searchGradesDisplay);
}

function searchGradesDisplay(responseJSON)
{
	var response=JSON.decode(responseJSON);
	$("queryTypeResult").set({"html": response["type"]});
	$("queryDbTypeResult").set({"html": response["dbtype"]});
	$("server").set({"html": response["server"]});
	$("queryFlushResult").erase("html");
	if(response["status"]=="nomatch")
	{
		$("numGrades").set({"html": "0"});
		$("flushButton").set({"disabled": "disabled"});
	};
	if(response["status"]=="success")
	{
		$("numGrades").set({"html": response["num"]});
		$("numGradesId").set({"html": response["id"]});
		$("flushButton").erase("disabled");
	};
	if(response["status"]=="failed")
	{
		$("numGrades").set({"html": "0"});
		$("flushButton").set({"disabled": "disabled"});
		$("queryFlushResult").set({"html": response["output"], "class": "failedText"});
	};
}

function flushGrades()
{
	var fGType=$("queryDbTypeResult").get("html");
	var fGValue=$("numGradesId").get("html");
	var fGServer=$("server").get("html");
	sendReq("POST", true, "./scripts/gradeflush.php", "fGType="+fGType+"&fGId="+fGValue+"&fGServer="+fGServer, flushGradesDisplay); 
}

function flushGradesDisplay(responseJSON)
{
	var response=JSON.decode(responseJSON);
	$("queryFlushResult").erase("html");
	if(response["status"]=="failed")
	{
		$("queryFlushResult").set({"html": response["output"]});
	}
	else
	{
		searchGrades();
	};
}
