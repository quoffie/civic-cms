var FRAMEWORK = {};

/* A handy function for doing pop-up confirmations when deleting something */
FRAMEWORK.deleteConfirmation = function (url)
{
	if (confirm("Are you really sure you want to delete this?\n\nOnce deleted it will be gone forever."))
	{
		document.location.href = url;
		return true;
	}
	else { return false; }
};

/**
 * Used for navigating between multiple forms that contain information
 * for one object, and are displayed as tabs.  Each of the forms must have
 * a hidden field for the tab and action, each with the appropriate id.
 * Also each of the forms must use the same id in the form tag
 * @param id form The id of the form tag
 * @param string tab The name of the next tab to display
 * @param string action The function to call in the controller for the forms
 */
FRAMEWORK.processTabbedForm = function (form,tab,action)
{
	if (action === 'save') { document.getElementById('continue').value = 'false'; }
	document.getElementById('tab').value = tab;
	document.getElementById('action').value = action;
	document.getElementById(form).submit();
};

/* The following function creates an XMLHttpRequest object useful for doing AJAX stuff */
FRAMEWORK.getXMLHttpRequestObject = function ()
{
	var request;
	var browser = navigator.appName;

	if (browser === "Microsoft Internet Explorer") { request = new ActiveXObject("Microsoft.XMLHTTP"); }
	else { request = new XMLHttpRequest(); }

	return request;
};

FRAMEWORK.getFormValues = function (form)
{
	var params = "";

	for(var i=0; i<form.elements.length; i++)
	{
		switch(form.elements[i].type)
		{
			case "text":
				params += form.elements[i].name + "=" + escape(form.elements[i].value) + ";";
			break;

			case "select-one":
				params += form.elements[i].name + "=" + form.elements[i].options[form.elements[i].selectedIndex].value + ";";
			break;
		}
	}

	//Remove the trailing semicolon before returning
	return params.substr(0,(params.length - 1));
};

/**
 * Limits a given form field to a given number of characters
 * @param field The DOM element for the field to limit
 * @param maxNumChars The maximum number of characters you want to allow
 */
FRAMEWORK.limit = function (field,maxNumChars)
{
	if (field.value.length > maxNumChars)
	{
		field.value = field.value.substring(0,maxNumChars);
	}
};


FRAMEWORK.popupTooltip = function (text,className)
{
	function newelement(newid)
	{
		var el = document.createElement('div');
		el.id = newid;
		el.style.display = 'none';
		el.style.position = 'absolute';
		document.body.appendChild(el);
	}
	function getmouseposition(e)
	{
		if (!e) { e = window.event; }
		var offsetx = 17;
		var offsety = 13;
		var tooltip = document.getElementById('tooltip');
		tooltip.style.left = (e.clientX+offsetx) + 'px';
		tooltip.style.top = (e.clientY+offsety) + 'px';
	}
    if(!document.getElementById('tooltip')) newelement('tooltip');
    var tooltip = document.getElementById('tooltip');
    if (className) { tooltip.className = className; }

    while(tooltip.childNodes.length>=1) { tooltip.removeChild(tooltip.firstChild); }
    tip = document.createTextNode(text);
    tooltip.appendChild(tip);

    tooltip.style.display = 'block';
    document.onmousemove = getmouseposition;
};
FRAMEWORK.hideTooltip = function ()
{
    document.getElementById('tooltip').style.display = 'none';
};

/**
 * A Date Picker built off the YUI Calendar
 * This required the YUI Toolkit
 * @param element The form input to put the chosen date
 */
FRAMEWORK.calendarInit = true;
FRAMEWORK.popupCalendar = function (element)
{
	if (!document.getElementById("popupDatePicker"))
	{
		var div = document.createElement("div");
		div.setAttribute("id","popupDatePicker");
		div.setAttribute("class","yui-skin-sam");
		element.form.appendChild(div);
	}
		
	FRAMEWORK.dateField = element;
	
	if (FRAMEWORK.calendarInit)
	{
		FRAMEWORK.calendarInit = false;
		FRAMEWORK.popupDatePicker = new YAHOO.widget.Calendar("popupDatePicker",{"close":true});
		FRAMEWORK.popupDatePicker.selectEvent.subscribe(FRAMEWORK.dateSelectionHandler,FRAMEWORK.popupDatePicker,true);
	}
	else
	{
		if (element.value != "")
		{
			FRAMEWORK.popupDatePicker.select(element.value);
			dates = FRAMEWORK.popupDatePicker.getSelectedDates();
			if (dates.length > 0)
			{
				date = dates[0];
				FRAMEWORK.popupDatePicker.cfg.setProperty("pagedate",(date.getMonth()+1 + "/" + date.getFullYear()));
			}
			else { alert("Invalid date"); }
		}
	}
	
	var xy = YAHOO.util.Dom.getXY(element);
	xy[0] += 20;
	xy[1] += 20;
	YAHOO.util.Dom.setXY ("popupDatePicker", xy, false);
	
	
	FRAMEWORK.popupDatePicker.render();
	FRAMEWORK.popupDatePicker.show();
}

FRAMEWORK.dateSelectionHandler = function (type,args,obj)
{
	FRAMEWORK.dateField.value = args[0][0][1] + "/" + args[0][0][2] + "/" + args[0][0][0];
	FRAMEWORK.popupDatePicker.hide();
}
