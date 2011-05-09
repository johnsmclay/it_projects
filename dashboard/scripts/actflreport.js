function searchACTFLActivitiesSelectAll()
{
	var i=0;
	var checkboxes=document.getElementsByName("tags");
	for(i=0; i<checkboxes.length; i++)
	{
		checkboxes[i].checked=true;
	};
}

function searchACTFLActivitiesSelectNone()
{
	var i=0;
	var checkboxes=document.getElementsByName("tags");
	for(i=0; i<checkboxes.length; i++)
	{
		checkboxes[i].checked=false;
	};
}

function searchACTFLActivities()
{
	$("queryActivitiesResult").erase("html");
	$("loadingStatus").erase("html");
	var checkboxes=document.getElementsByName("tags");
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
		sendReq("POST", true, "./scripts/actflreport.php", "tags="+tags, searchACTFLActivitiesDisplay);
	}
	else
	{
		$("loadingStatus").set({"html": "Choose at least one tag!"});
	};
}

function searchACTFLActivitiesDisplay(responseJSON)
{
	var response=JSON.decode(responseJSON);
	var output=$("queryActivitiesResult");
	output.erase("html");
	var ul=new Element("ul");
	var i=0;
	for(i=0; i<response.length; i++)
	{
		var course=new Element("li", {"html": "<a href=\"Javascript:searchACTFLActivitiesExpand('course"+response[i]["id"]+"')\">"+response[i]["course"]+"</a>"});
		course.inject(ul);
		var courseContent=new Element("ul", {"class": "hidden", "id": "course"+response[i]["id"]});
		courseContent.inject(ul);
		var courseActMatches=0;
		var j=0;
		for(j=0; j<response[i]["units"].length; j++)
		{
			var unit=new Element("li", {"html": "<a href=\"Javascript:searchACTFLActivitiesExpand('unit"+response[i]["units"][j]["id"]+"')\">"+response[i]["units"][j]["unit"]+"</a>"});
			unit.inject(courseContent);
			var unitContent=new Element("ul", {"class": "hidden", "id": "unit"+response[i]["units"][j]["id"]});
			unitContent.inject(courseContent);
			var unitActMatches=0;
			var k=0;
			for(k=0; k<response[i]["units"][j]["lessons"].length; k++)
			{
				var lesson=new Element("li", {"html": "<a href=\"Javascript:searchACTFLActivitiesExpand('lesson"+response[i]["units"][j]["lessons"][k]["id"]+"')\">"+response[i]["units"][j]["lessons"][k]["lesson"]+"</a>"});
				lesson.inject(unitContent);
				var lessonContent=new Element("ul", {"class": "hidden", "id": "lesson"+response[i]["units"][j]["lessons"][k]["id"]});
				lessonContent.inject(unitContent);
				var lessonActMatches=0;
				var l=0;
				for(l=0; l<response[i]["units"][j]["lessons"][k]["chains"].length; l++)
				{
					var chain=new Element("li", {"html": "<a href=\"Javascript:searchACTFLActivitiesExpand('chain"+response[i]["units"][j]["lessons"][k]["chains"][l]["id"]+"')\">"+response[i]["units"][j]["lessons"][k]["chains"][l]["chain"]+"</a>"});
					chain.inject(lessonContent);
					var chainContent=new Element("ul", {"class": "hidden", "id": "chain"+response[i]["units"][j]["lessons"][k]["chains"][l]["id"]});
					chainContent.inject(lessonContent);
					var m=0;
					for(m=0; m<response[i]["units"][j]["lessons"][k]["chains"][l]["activities"].length; m++)
					{
						var activity=new Element("li", {"html": response[i]["units"][j]["lessons"][k]["chains"][l]["activities"][m]["activity"]});
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
	ul.inject(output);
}

function searchACTFLActivitiesExpand(id)
{
	var ul=$(id);
	ul.toggleClass("hidden");
}

function getACTFLCoursesList()
{
	sendReq("POST", true, "./scripts/actflreport.php", "getCourses=1", getACTFLCoursesListDisplay);
}

function getACTFLCoursesListDisplay(responseJSON)
{
	var response=JSON.decode(responseJSON);
	var select=$("courseId");
	select.set({"html": "<option value=\"0\">Choose a course&hellip;</option>"});
	var i=0;
	for(i=0; i<response.length; i++)
	{
		var option=new Element("option", {"html": response[i]["title"], "value": response[i]["id"]});
		option.inject(select);
	};
	select.erase("disabled");
	$("unitId").set({"disabled": "disabled"});
	$("lessonId").set({"disabled": "disabled"});
	$("chainId").set({"disabled": "disabled"});
}

function getACTFLList(type)
{
	var parents=new Array();
	parents["unit"]="course";
	parents["lesson"]="unit";
	parents["chain"]="lesson";
	if($(parents[type]+"Id").value!=0)
	{
		sendReq("POST", true, "./scripts/actflreport.php", "getListType="+type+"&parentId="+$(parents[type]+"Id").value, getACTFLListDisplay);
	}
	else
	{
		var i=0;
		var nextSelects=new Array();
		nextSelects["unit"]=["unit", "lesson", "chain"];
		nextSelects["lesson"]=["lesson", "chain"];
		nextSelects["chain"]=["chain"];
		for(i=0; i<nextSelects[type].length; i++)
		{
			$(nextSelects[type][i]+"Id").set({"html": "<option value=\"0\">Choose a "+nextSelects[type][i]+"&hellip;</option>", "disabled": "disabled"});
		};
	}
}

function getACTFLListDisplay(responseJSON)
{
	var response=JSON.decode(responseJSON);
	var select=$(response["type"]+"Id");
	select.set({"html": "<option value=\"0\">Choose a "+response["type"]+"&hellip;</option>"});
	var i=0;
	for(i=0; i<response["vals"].length; i++)
	{
		var option=new Element("option", {"html": response["vals"][i]["title"], "value": response["vals"][i]["id"]});
		option.inject(select);
	};
	select.erase("disabled");
	i=0;
	var nextSelects=new Array();
	nextSelects["unit"]=["lesson", "chain"];
	nextSelects["lesson"]=["chain"];
	nextSelects["chain"]=[];
	for(i=0; i<nextSelects[response["type"]].length; i++)
	{
		$(nextSelects[response["type"]][i]+"Id").set({"html": "<option value=\"0\">Choose a "+nextSelects[response["type"]][i]+"&hellip;</option>", "disabled": "disabled"});
	};
}

function getACTFLActivitiesList()
{
	$("queryActivitiesResult").erase("html");
	$("loadingStatus").erase("html");
	if($("chainId").value!=0)
	{
		sendReq("POST", true, "./scripts/actflreport.php", "actChainId="+$("chainId").value, getACTFLActivitiesListDisplay);
	};
}

function getACTFLActivitiesListDisplay(responseJSON)
{
	var response=JSON.decode(responseJSON);
	var output=$("queryActivitiesResult");
	output.erase("html");
	var table=new Element("table", {"html": "<thead><tr><th>Activity title</th><th class=\"idTh\">1.1</th><th class=\"idTh\">1.2</th><th class=\"idTh\">1.3</th><th class=\"idTh\">2.1</th><th class=\"idTh\">2.2</th><th class=\"idTh\">3.1</th><th class=\"idTh\">3.2</th><th class=\"idTh\">4.1</th><th class=\"idTh\">4.2</th><th class=\"idTh\">5.1</th><th class=\"idTh\">5.2</th></tr></thead><tbody></tbody>"});
	var i=0;
	for(i=0; i<response.length; i++)
	{
		var tr=new Element("tr");
		if(i%2==1)
		{
			tr.set({"class": "altRow"});
		};
		var j=0;
		for(j=0; j<response[i].length; j++)
		{
			var td=new Element("td", {"html": response[i][j]});
			if(j!=0)
			{
				td.set({"class": "idTh"});
				if(response[i][j]=="Yes")
				{
					td.set({"style": "color: green;"});
				};
			};
			td.inject(tr);
		};
		tr.inject(table.getElements("tbody")[0]);
	};
	table.inject(output);
}
