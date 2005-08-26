/****
*
*   A set of javascript functions to do row filtering in a table given a 
*   form containing search parameters.
*
*  Compatibility : IE4+
*  	
*  Author  : Sidney Chong
*  Date    : 24/8/2001
*  Version : 1.0d
*
*
*  Features
*  ========
*  This script offers the following set of features:
*
*  #search criteria can be a text input, hidden input, single or multi-select list input.
*  #allows a combination of search criteria (as an AND operation).
*  #4 types of matching strategies are available:
*     1) substring1 - substring search (from 1st char) (default)
*        eg. man will match "manhood" and "man is evil" NOT "superman" and "he is a man"
*     2) substring - substring search (anywhere within)
*        eg. man will match "manhood" and "woman" and "superman" and "he is a man"
*     3) full - full string search
*        eg. man will match "man" NOT "manhood", "superman", "man is evil" and "he is a man"
*     4) item - search for a word/phrase in a comma seperated string
*        eg. man will match "man,woman,child" NOT "superman,superwoman,kid" and "boy,girl,dog"
*  #performs full string match (in substring1 mode) if last char in search string is a whitespace.
*  #allows search to be turned off/on.
*
*  Usage
*  =====
*  Give the table and search form a handle (using the attribute id or name).
*
*  In the cell elements of the table, include a custom atrribute called 
*  "TF_colKey" and give it a value to identify the column. Note that you only
*  need to do this for columns that will take part in the search.
*
*  In the form input elements, include also a custom attribute called 
*  "TF_colKey" whose value will reference the column on which this search 
*  parameter is applicable. Again, as in the table, you will only need to do this
*  if this input field should take part in the search. Also, an optional custom 
*  attribute called "TF_searchType" can be specified to use another search strategy 
*  for a particular field.
*
*  In an option tag for a select input, use the custom attribute "TF_not_used"
*  to exclude the particular option from the search. (Very useful in drop down
*  selection list box)
*
*  Call TF_filterTable passing in the handles to the table and form to 
*  perform the filtering.
*
*  NOTE that the value of TF_colKey & TF_searchType are case-insensitive.
*
*  ChangeLog cum Version History
*  =============================
*  24/8/2001 - version 1.0d release.
*  23/8/2001 - Added functions _TF_get_value and TF_concat_and_set.
*  23/8/2001 - hidden input can now be used as a search field.
*  22/8/2001 - implemented "substring" search mode.
*  22/8/2001 - improve robustness/flexibility: if a cell <td> in the table or input 
*              <input>/<select> in the search form does not take part in the search 
*              (hence do not have the attribute "TF_colKey"), the script will 
*              gracefully ignore it. Previously, it will generate a script error.
*  22/8/2001 - reintroduced "TF_not_used" custom attribute to the option element.
*              I've apparently managed to loose it in the v1.0 pre-release *DUH!*
*
*  16/8/2001 - version 1.0c release.
*  16/8/2001 - modified _TF_trimWhitespace to trim the front as well.
*  16/8/2001 - fixed bug in _TF_filterTable that cause AND search combinations
*              not to work properly.
*
*   9/8/2001 - version 1.0b release.
*   8/8/2001 - added _TF_showAll function.
*   8/8/2001 - modified _TF_filterTable to use _TF_shouldShow function.
*   8/8/2001 - added TF_searchType attribute to define a search type.
*   8/8/2001 - implemented "item" search.
*   8/8/2001 - added _TF_shouldShow function.
*
*  26/7/2001 - version 1.0a release.
*  26/7/2001 - added _TF_trimWhitespace function.
*  26/7/2001 - modified _TF_filterTable single condition search to include 
*              full pattern search if the last char of the search string
*              is a whitespace.
*
*  14/6/2001 - version 1.0 initial release.
*
****/

/** PRIVATE FUNCTIONS **/
function _TF_trimWhitespace(txt) {
	var strTmp = txt;
	//trimming from the front
	for (counter=0; counter<strTmp.length; counter++)
		if (strTmp.charAt(counter) != " ")
			break;
	//trimming from the back
	strTmp = strTmp.substring(counter,strTmp.length);
	counter = strTmp.length - 1;
	for (counter; counter>=0; counter--)
		if (strTmp.charAt(counter) != " ")
			break;
	return strTmp.substring(0, counter+1);
}

function _TF_showAll(tb) {
	for (i=0;i<tb.rows.length;i++)
	{
		tb.rows[i].style.display = "";
	}
}

function _TF_shouldShow(type, con, val) {
	var toshow=true;
	if (type != null) type = type.toLowerCase();
	switch (type)
	{
		case "item":
			var strarray = val.split(",");
			innershow = false;
			for (ss=0;ss<strarray.length;ss++){
				if (con==_TF_trimWhitespace(strarray[ss])){
					innershow=true;
					break;
				}
			}
			if (innershow == false)
				toshow=false;
		break
		case "full":
			if (val!=con)
				toshow = false;
		break
		case "substring":
			if (val.indexOf(con)<0)
				toshow = false;
		break
		default: //is "substring1" search
			if (val.indexOf(con)!=0) //pattern must start from 1st char
				toshow = false;
			if (con.charAt(con.length-1) == " ")
			{ //last char is a space, so lets do a full search as well
				if (_TF_trimWhitespace(con) != val)
					toshow = false;
				else
					toshow = true;
			}
		break
	}
	return toshow;
}

function _TF_filterTable(tb, conditions) {
	//given an array of conditions, lets search the table
	for (i=0;i<tb.rows.length;i++)
	{
		var show = true;
		var rw = tb.rows[i];
		for (j=0;j<rw.cells.length;j++)
		{
			var cl = rw.cells[j];
			for (k=0;k<conditions.length;k++)
			{
				var colKey = cl.getAttribute("TF_colKey");
				if (colKey == null) //attribute not found
					continue; //so lets not search on this cell.
				if (conditions[k].name.toUpperCase() == colKey.toUpperCase())
				{
					var tbVal = cl.innerText;
					var conVals = conditions[k].value;
					if (conditions[k].single) //single value
					{ 
						show = _TF_shouldShow(conditions[k].type, conditions[k].value, cl.innerText);
					} else { //multiple values
						for (l=0;l<conditions[k].value.length;l++)
						{
							innershow = _TF_shouldShow(conditions[k].type, conditions[k].value[l], cl.innerText);
							if (innershow == true) break;
						}
						if (innershow == false)
							show = false;
					}
				}
			}
			//if any condition has failed, then we stop the matching (due to AND behaviour)
			if (show == false)
				break;
		}
		if (show == true)
			tb.rows[i].style.display = "";
		else
			tb.rows[i].style.display = "none";
	}
}

/** PUBLIC FUNCTIONS **/
//main function
function TF_filterTable(tb, frm) {
	var conditions = new Array();
	if (frm.style.display == "none") //filtering is off
		return _TF_showAll(tb);

	//go thru each type of input elements to figure out the filter conditions
	var inputs = frm.tags("INPUT");
	for (i=0;i<inputs.length;i++)
	{ //looping thru all INPUT elements
		if (inputs[i].getAttribute("TF_colKey") == null) //attribute not found
			continue; //we assume that this input field is not for us
		switch (inputs[i].type)
		{
			case "text":
			case "hidden":
				if(inputs[i].value != "")
				{
					index = conditions.length;
					conditions[index] = new Object;
					conditions[index].name = inputs[i].getAttribute("TF_colKey");
					conditions[index].type = inputs[i].getAttribute("TF_searchType");
					conditions[index].value = inputs[i].value;
					conditions[index].single = true;
				}
			break
		}
	}
	var inputs = frm.tags("SELECT");
	//able to do multiple selection box
	for (i=0;i<inputs.length;i++)
	{ //looping thru all SELECT elements
		if (inputs[i].getAttribute("TF_colKey") == null) //attribute not found
			continue; //we assume that this input field is not for us
		var opts = inputs[i].options;
		var optsSelected = new Array();
		for (intLoop=0; intLoop<opts.length; intLoop++)
		{ //looping thru all OPTIONS elements
			if (opts[intLoop].selected && (opts[intLoop].getAttribute("TF_not_used") == null))
			{
				index = optsSelected.length;
				optsSelected[index] = opts[intLoop].value;
			}
		}
		if (optsSelected.length > 0) //has selected items
		{
			index = conditions.length;
			conditions[index] = new Object;
			conditions[index].name = inputs[i].getAttribute("TF_colKey");
			conditions[index].type = inputs[i].getAttribute("TF_searchType");
			conditions[index].value = optsSelected;
			conditions[index].single = false;
		}
	}
	//ok, now that we have all the conditions, lets do the filtering proper
	_TF_filterTable(tb, conditions);
}

function TF_enableFilter(tb, frm, val) {
	if (val.checked) //filtering is on
	{
		frm.style.display = "";
	} else { //filtering is off
		frm.style.display = "none";
	}
	//refresh the table
	TF_filterTable(tb, frm);
}

function _TF_get_value(input) {
	switch (input.type)
	{
		case "text":
			 return input.value;
		break
		case "select-one":
			if (input.selectedIndex > -1) //has value
				return input.options(input.selectedIndex).value;
			else
				return "";
		break;
	}
}

//util function that concat two input fields and set the result in the third
function TF_concat_and_set(salText, salSelect, salHidden) {
	var valLeft = _TF_get_value(salText);
	var valRight = _TF_get_value(salSelect);
	salHidden.value = valLeft + valRight;
}
