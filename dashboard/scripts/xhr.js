// AJAX XML HTTP request object creation script
// W3C valid (XML HTTP Request 1.0)
//
// by Jeremy Conan
//
// -------------------- //

// XML HTTP Request object.
var xhr=getXMLHttpRequest();

// XHR object constructor.
function getXMLHttpRequest()
{
	// XHR object reset.
	var xhr=null;
	// Browser XHR support checking.
	if((window.XMLHttpRequest)||(window.ActiveXObject))
	{
		// Microsoft Internet Explorer ActiveX object testing.
		if (window.ActiveXObject)
		{
			// Ms IE ActiveX objects versions.
			var names =
			[
				"Msxml2.XMLHTTP.6.0",
				"Msxml2.XMLHTTP.3.0",
				"Msxml2.XMLHTTP",
				"Microsoft.XMLHTTP"
			];
			for(var i in names)
			{
				try
				{
					// Assigns the ActiveX object on Ms IE.
					xhr = new ActiveXObject(names[i]);
				}
				catch(e)
				{
					// This ActiveX object doesn't work.
				}
			}
		}
		else
		{
			// Assigns the W3C standard XHR object.
			xhr=new XMLHttpRequest(); 
		};
	}
	else
	{
		// You should change your browser!
		alert("Boom!");
		return null;
	};
	return xhr;
}

function handler(callback)
// Checks the XML request state. When this request is finished, runs the displaying function.
//
// The response should be text or JSON and the callback should be able to decode JSON.
{
	xhr.onreadystatechange=function()
	{
		if((xhr.readyState==4)&&(xhr.status==200))
		{
			clearMessages();
			// Function to run when XML request is done. Named by this function parameter.
			callback(xhr.responseText);
		}
		else
		{
			// Callback function while data is loading.
			loading();
			if(xhr.status>=400)
			{
				// Unable to reach the file requested.
				error(xhr.status);
			}
		};
	};
}

function clearMessages()
{
	var loadDiv;
	if(loadDiv=$("loadingStatus"))
	{
		loadDiv.erase("html");
	};
}

function loading()
{
	var loadDiv;
	if(loadDiv=$("loadingStatus"))
	{
		var ldHtml;
		switch(xhr.readyState)
		{
			case 0:
				ldHtml="Sending request&hellip;";
				break;
			case 1:
				ldHtml="Request sent, waiting for response headers&hellip;";
				break;
			case 2:
				ldHtml="Response headers received, waiting for response body&hellip;";
				break;
			case 3:
				ldHtml="Receiving response body&hellip;";
				break;
			default:
				ldHtml="Running request&hellip;";
				break;
		};
		loadDiv.set({"html": ldHtml});
	};
}

function error(httpStatus)
{
	var loadDiv;
	if(loadDiv=$("loadingStatus"))
	{
		loadDiv.set({"html": "HTTP error "+httpStatus});
	};
}

function sendReq(httpReq, asynchronous, url, args, callback)
// Sends a new XHR depending on its type and arguments.
//
// httpReq: the request type, GET or POST.
// asynchronous: whether the request is asynchronous or not. Should usually be set to true.
// url: the requested file URL. Can be absolute or relative.
// args: all the arguments used by the requested file. Should be written like arg1=value1&arg2=value2&arg3...
// callback: the function to call when the distant data is loaded and ready for use.
{
	// Stops any currently running request to avoid server DDoS.
	if(xhr&&xhr.readyState!=0)
	{
		xhr.abort();
	};
	// POST request creation
	if(httpReq=="POST")
	{
		xhr.open(httpReq, url, asynchronous);
		// The XHR object send the arguments like if they were sent from an HTML form.
		xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		xhr.send(args);
	};
	// GET request creation
	if(httpReq=="GET")
	{
		// Checks arguments to create a valid URL.
		if(args!=""&&args!=null)
		{
			xhr.open(httpReq, url+"?"+args, asynchronous);
		}
		else
		{
			xhr.open(httpReq, url, asynchronous);
		};
		// The XHR object send the arguments via the file URL.
		xhr.send(null);
	};
	// Starts the XHR state listener.
	handler(callback);
}
