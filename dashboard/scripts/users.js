// User accounts management dedicated Javascript
//
// This Javascript allows to search, add, edit or delete a user.
// Each application is divided in two functions. The first one send an XHR and the second one manage the XHR response.
// There may be a third function before sending the XHR that manages the presentation.

// User search
//
function searchUser()
{
	var sValue=$("searchValue").value;
	var sTypeInput=document.getElementsByName("searchType");
	var sProduct=$("searchProduct").value;
	var i=0;
	var sType=0;
	for(i=0;i<sTypeInput.length;i++)
	{
		if(sTypeInput[i].checked==true)
		{
			sType=sTypeInput[i].value;
		};
	};
	if(sValue.length>=0)
	{
		sendReq("POST", true, "./scripts/users.php", "sValue="+sValue+"&sType="+sType+"&sProduct="+sProduct, searchUserDisplay);
	};
}
function searchUserDisplay(responseJSON)
{
	var response=JSON.decode(responseJSON);
	var userTable=$("searchResult");
	userTable.erase("html");
	var i=0;
	if(response["status"])
	{
		$("queryUserResult").set({"html": response["output"], "class": "failedText"});
	}
	else
	{
		for(i=0; i<response.length; i++)
		{
			var userRow=new Element("tr", {"html": "<td class=\"idTh\">"+response[i]["id"]+"</td>\n<td>"+response[i]["username"]+"</td>\n<td>"+response[i]["email"]+"</td>\n<td class=\"productTh\"><span class=\""+response[i]["server"]+"\">"+response[i]["product"]+"</span></td>\n<td><a href=\"Javascript:editUserTooltip("+response[i]["id"]+", "+i+")\">Edit</a></td>\n<td><input type=\"checkbox\" name=\"deleteUserCheckbox\" class=\""+response[i]["server"]+"\" value=\""+response[i]["id"]+"\" /></td>\n"});
			if(i%2==1)
			{
				userRow.set({"class": "altRow"});
			};
			userRow.inject(userTable);
		};
	};
}

// User adding
//
function addUserSetFields()
{
	var regexProduct=new RegExp(".+DB.+DBspeakez", "g");
	if(regexProduct.test($("addUserProduct").value)==true)
	{
		$("addUserUsername").set({"value": "Not used", "disabled": "disabled"});
	}
	else
	{
		$("addUserUsername").set({"value": "Username"});
		$("addUserUsername").erase("disabled");
	};
}
function addUser()
{
	var aUsername=$("addUserUsername").value;
	var aEmail=$("addUserEmail").value;
	var aProduct=$("addUserProduct").value;
	sendReq("POST", true, "./scripts/users.php", "aUsername="+aUsername+"&aEmail="+aEmail+"&aProduct="+aProduct, addUserDisplay);
}
function addUserDisplay(responseJSON)
{
	var response=JSON.decode(responseJSON);
	var addUserResult=$("queryUserResult");
	addUserResult.erase("html");
	if(response["status"]=="failed")
	{
		addUserResult.set({"html": response["output"], "class": "failedText"});
	}
	else
	{
		addUserResult.set({"html": response["output"], "class": "successText"});
		$(response["server"]).selected=true;
		$("searchValue").value=response["id"];
		document.getElementsByName("searchType")[1].checked=true;
		searchUser();
	}
}

// User editing
//
function editUserTooltip(id, row)
{
	if($$(".editRow")[0])
	{
		$$(".editRow")[0].dispose();
	};
	var editRow=new Element("tr", {"html": "<td><input type=\"text\" id=\"editId\" value=\""+id+"\" class=\"idTh\" disabled=\"disabled\" /></td>\n<td><input type=\"text\" id=\"editUsername\" value=\""+$$("#searchResult tr")[row].getElements("td")[1].get("html")+"\" /></td>\n<td><input type=\"text\" id=\"editEmail\" value=\""+$$("#searchResult tr")[row].getElements("td")[2].get("html")+"\" /></td>\n<td class=\"productTh\"><span id=\"editProduct\" class=\""+$$("#searchResult tr")[row].getElements("td")[3].getElements("span")[0].get("class")+"\">"+$$("#searchResult tr")[row].getElements("td")[3].getElements("span")[0].get("html")+"</span></td>\n<td>&nbsp;</td>\n<td><input type=\"button\" value=\"Edit user\" onclick=\"Javascript:editUser()\" /></td>\n", "class": "editRow"});
	var regexProduct=new RegExp(".+DB.+DBspeakez", "g");
	if(regexProduct.test($$("#searchResult tr")[row].getElements("td")[3].getElements("span")[0].get("class"))==true)
	{
		editRow.getElements("input")[1].set({"disabled": "disabled", "value": "Not used"});
	};
	editRow.inject($$("#searchResult tr")[row], "after");
}
function editUser()
{
	var eUsername=$("editUsername").value;
	var eId=$("editId").value;
	var eEmail=$("editEmail").value;
	var eProduct=$("editProduct").get("class");

	sendReq("POST", true, "./scripts/users.php", "eId="+eId+"&eUsername="+eUsername+"&eEmail="+eEmail+"&eProduct="+eProduct, editUserDisplay);
}
function editUserDisplay(responseJSON)
{
	var response=JSON.decode(responseJSON);
	var editUserResult=$("queryUserResult");
	editUserResult.erase("html");
	if(response["status"]=="failed")
	{
		editUserResult.set({"html": response["output"], "class": "failedText"});
	}
	else
	{
		$("searchValue").value=response["id"];
		document.getElementsByName("searchType")[1].checked=true;
		searchUser();
	}
}

// User deleting
//
function deleteUser()
{
	if($("deleteUserConfirm").checked==true)
	{
		var dUsers=new Array();
		var dUsersCheckboxes=document.getElementsByName("deleteUserCheckbox");
		var i=0;
		var j=0;
		for(i=0; i<dUsersCheckboxes.length; i++)
		{
			if(dUsersCheckboxes[i].checked==true)
			{
				dUsers[j]=new Array();
				dUsers[j]["dId"]=dUsersCheckboxes[i].value;
				dUsers[j]["dProduct"]=dUsersCheckboxes[i].get("class");
				j++;
			};
		};
		j=null;
		i=0;
		for(i=0; i<dUsers.length; i++)
		{
			sendReq("POST", true, "./scripts/users.php", "dId="+dUsers[i]["dId"]+"&dProduct="+dUsers[i]["dProduct"], deleteUserDisplay);
		};
	};
}
function deleteUserDisplay(responseJSON)
{
	var response=JSON.decode(responseJSON);
	var deleteUserResult=$("queryUserResult");
	deleteUserResult.erase("html");
	$("deleteUserConfirm").checked=false;
	if(response["status"]=="failed")
	{
		deleteUserResult.set({"html": response["output"], "class": "failedText"})
	}
	else
	{
		searchUser();
	};
}
