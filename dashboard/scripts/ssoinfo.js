function getSchoolsList()
{
	$("searchSSOResult").erase("html");
	$("numSchools").set({"html": "0"});
	if($("searchSSOResult").getParent().hasClass("hidden")==true)
	{
		toggleDetails();
	};
	sendReq("POST", true, "./scripts/ssoinfo.php", "getSchools=1", getSchoolsListDisplay);
}

function getSchoolsListDisplay(responseJSON)
{
	var response=JSON.decode(responseJSON);
	var i=0;
	$("numSchools").set({"html": response.length});
	for(i=0; i<response.length; i++)
	{
		if(response[i]["color"]!="#552200"&&response[i]["color"]!="green")
		{
			response[i]["sso"]+=" (<a href=\"Javascript:getSchoolDetails("+response[i]["id"]+")\">details</a>)";
		};
		var resultRow=new Element("tr", {"html": "<td class=\"idTh\">"+response[i]["id"]+"</td><td class=\"idTh\">"+response[i]["sname"]+"</td><td>"+response[i]["name"]+"</td><td class=\"idTh\">"+response[i]["parentid"]+"</td><td style=\"color: "+response[i]["color"]+";\">"+response[i]["sso"]+"</td>"});
		if(i%2==1)
		{
			resultRow.set({"class": "altRow"});
		};
		resultRow.inject($("searchSSOResult"));
	};
}

function getSchoolDetails(id)
{
	$("schoolDetailsTitle").erase("html");
	$("schoolDetails").erase("html");
	sendReq("POST", true, "./scripts/ssoinfo.php", "schoolId="+id, getSchoolDetailsDisplay);
}

function getSchoolDetailsDisplay(responseJSON)
{
	toggleDetails();
	var response=JSON.decode(responseJSON);
	$("schoolDetailsTitle").set({"html": response["school"]+" users: (<a href=\"Javascript:toggleDetails()\">back to schools list</a>)"});
	var i=0;
	for(i=0; i<response["users"].length; i++)
	{
		var userLi=new Element("li", {"html": response["users"][i]["id"]+" - "+response["users"][i]["username"]+" - "+response["users"][i]["fname"]+" "+response["users"][i]["lname"]+" - "+response["users"][i]["email"]});
		userLi.inject($("schoolDetails"));
	};
}

function toggleDetails()
{
	$("searchSSOResult").getParent().toggleClass("hidden");
	$("schoolDetails").toggleClass("hidden");
	$("schoolDetailsTitle").toggleClass("hidden");
}
