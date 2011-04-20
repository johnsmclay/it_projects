function getSchoolsList()
{
	sendReq("GET", true, "./scripts/ssoinfo.php", null, getSchoolsListDisplay);
}

function getSchoolsListDisplay(responseJSON)
{
	var response=JSON.decode(responseJSON);
	var i=0;
	$("searchSSOResult").erase("html");
	$("numSchools").set({"html": response.length});
	for(i=0; i<response.length; i++)
	{
		var resultRow=new Element("tr", {"html": "<td class=\"idTh\">"+response[i]["id"]+"</td><td class=\"idTh\">"+response[i]["sname"]+"</td><td>"+response[i]["name"]+"</td><td class=\"idTh\">"+response[i]["parentid"]+"</td><td style=\"color: "+response[i]["color"]+";\">"+response[i]["sso"]+"</td>"});
		if(i%2==1)
		resultRow.set({"class": "altRow"});
		resultRow.inject($("searchSSOResult"));
	};
}
