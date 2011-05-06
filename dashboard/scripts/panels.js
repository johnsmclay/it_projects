function loadPanel(val)
// Sends an XHR to load a dashboard tab.
{
	var panel="";
	switch(val)
	// Add a case for each tab in the dashboard.
	{
		case 0:
			panel="home";
			break;
		case 1:
			panel="recflush";
			break;
		case 2:
			panel="coursepush";
			break;
		case 3:
			panel="ssoinfo";
			break;
		case 4:
			panel="user";
			break;
		case 5:
			panel="gradeflush";
			break;
		case 6:
			panel="actbreport";
			break;
		case 7:
			panel="actflreport";
			break;
		default:
		// Default case should call a basic tab.
			panel="home";
			break;
	};
	sendReq("POST", true, "./scripts/panels.php", "pname="+panel, setPanel);
}

function setPanel(responseJSON)
// Displays the tab loaded on the dashboard.
{
	var panelTitle=$("contentH1");
	var panelBody=$("contentBody")
	var response=JSON.decode(responseJSON);
	panelTitle.set({"html": response["title"]});
        panelBody.set({"html": response["body"]});
}
