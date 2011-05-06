function searchBuildActivities()
{
	$("detailsBox").erase("html");
	$("queryActivitiesResult").erase("html");
	$("loadingStatus").erase("html");
	var checkboxes=document.getElementsByName("tags");
	var isChecked=$("isChecked").checked;
	var tags="starttags";
	var i=0;
	for(i=0; i<checkboxes.length; i++)
	{
		if(checkboxes[i].checked)
		{
			if(tags=="starttags")
			{
				tags=checkboxes[i].value;
			}
			else
			{
				tags+="_xmltag_"+checkboxes[i].value;
			};
		};
	};
	if(tags!="starttags")
	{
		sendReq("POST", true, "./scripts/actbreport.php", "isChecked="+isChecked+"&tags="+tags, searchBuildActivitiesDisplay);
	}
	else
	{
		$("loadingStatus").set({"html": "Choose at least one tag!"});
	};
}

function searchBuildActivitiesDisplay(responseJSON)
{
	var response=JSON.decode(responseJSON);
	var output=$("queryActivitiesResult");
	output.erase("html");
	var i=0;
	for(i=0; i<response.length; i++)
	{
		var course=new Element("li", {"html": "<a href=\"Javascript:searchBuildActivitiesExpand('course"+response[i]["id"]+"')\">"+response[i]["course"]+"</a>"});
		course.inject(output);
		var courseContent=new Element("ul", {"class": "hidden", "id": "course"+response[i]["id"]});
		courseContent.inject(output);
		var courseActMatches=0;
		var j=0;
		for(j=0; j<response[i]["units"].length; j++)
		{
			var unit=new Element("li", {"html": "<a href=\"Javascript:searchBuildActivitiesExpand('unit"+response[i]["units"][j]["id"]+"')\">"+response[i]["units"][j]["unit"]+"</a>"});
			unit.inject(courseContent);
			var unitContent=new Element("ul", {"class": "hidden", "id": "unit"+response[i]["units"][j]["id"]});
			unitContent.inject(courseContent);
			var unitActMatches=0;
			var k=0;
			for(k=0; k<response[i]["units"][j]["lessons"].length; k++)
			{
				var lesson=new Element("li", {"html": "<a href=\"Javascript:searchBuildActivitiesExpand('lesson"+response[i]["units"][j]["lessons"][k]["id"]+"')\">"+response[i]["units"][j]["lessons"][k]["lesson"]+"</a>"});
				lesson.inject(unitContent);
				var lessonContent=new Element("ul", {"class": "hidden", "id": "lesson"+response[i]["units"][j]["lessons"][k]["id"]});
				lessonContent.inject(unitContent);
				var lessonActMatches=0;
				var l=0;
				for(l=0; l<response[i]["units"][j]["lessons"][k]["chains"].length; l++)
				{
					var chain=new Element("li", {"html": "<a href=\"Javascript:searchBuildActivitiesExpand('chain"+response[i]["units"][j]["lessons"][k]["chains"][l]["id"]+"')\">"+response[i]["units"][j]["lessons"][k]["chains"][l]["chain"]+"</a>"});
					chain.inject(lessonContent);
					var chainContent=new Element("ul", {"class": "hidden", "id": "chain"+response[i]["units"][j]["lessons"][k]["chains"][l]["id"]});
					chainContent.inject(lessonContent);
					var m=0;
					for(m=0; m<response[i]["units"][j]["lessons"][k]["chains"][l]["activities"].length; m++)
					{
						var activity=new Element("li", {"html": "<a href=\"Javascript:activityBuildDetails("+response[i]["units"][j]["lessons"][k]["chains"][l]["activities"][m]["id"]+")\">"+response[i]["units"][j]["lessons"][k]["chains"][l]["activities"][m]["activity"]+"</a>"});
						activity.inject(chainContent);
					};
					var chainActMatches=response[i]["units"][j]["lessons"][k]["chains"][l]["activities"].length;
					chain.appendText(" ("+chainActMatches+" matche(s) on "+response[i]["units"][j]["lessons"][k]["chains"][l]["num"]+" activities)");
					if(chainActMatches==0)
					{
						chain.set({"class": "hidden"});
					};
					lessonActMatches+=chainActMatches;
				};
				lesson.appendText(" ("+lessonActMatches+" matche(s) on "+response[i]["units"][j]["lessons"][k]["num"]+" activities)");
				if(lessonActMatches==0)
				{
					lesson.set({"class": "hidden"});
				};
				unitActMatches+=lessonActMatches;
			};
			unit.appendText(" ("+unitActMatches+" matche(s) on "+response[i]["units"][j]["num"]+" activities)");
			if(unitActMatches==0)
			{
				unit.set({"class": "hidden"});
			};
			courseActMatches+=unitActMatches;
		};
		course.appendText(" ("+courseActMatches+" matche(s) on "+response[i]["num"]+" activities)");
		if(courseActMatches==0)
		{
			course.set({"class": "hidden"});
		};
	};
}

function searchBuildActivitiesExpand(id)
{
	var ul=$(id);
	ul.toggleClass("hidden");
}

function activityBuildDetails(id)
{
	$("detailsBox").erase("html");
	sendReq("POST", true, "./scripts/actbreport.php", "actid="+id, activityBuildDetailsDisplay);
	window.scrollTo(0,0);
}

function activityBuildDetailsDisplay(responseJSON)
{
	var response=JSON.decode(responseJSON);
	var box=$("detailsBox");
	box.erase("html");
	box.set({"html": "<h3>"+response["title"]+"</h3><ul></ul>"});
	var i=0;
	for(i=0; i<response["tags"].length; i++)
	{
		var tag=new Element("li", {"html": response["tags"][i]});
		tag.inject($$("#detailsBox ul")[0]);
	};
}
