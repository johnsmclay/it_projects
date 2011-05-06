function getTotalRecords()
{
	getRecords(0, displayTotalRecords);
}

function getPeriodRecords()
{
	if($("periodRec").value==parseInt($("periodRec").value))
	{
		getRecords(parseInt($("periodRec").value), displayPeriodRecords);
	};
}

function getRecords(days, callback)
{
	sendReq("POST", true, "./scripts/recflush.php", "days="+days, callback);
}

function displayTotalRecords(responseText)
{
	var response=responseText;
	$("totalRecResult").set({"html": response});
};

function displayPeriodRecords(responseText)
{
	var response=responseText;
	$("periodRecResult").set({"html": response});
};

function flushRecords()
{

}

function flushAll()
{

}
