var ns4 = (document.layers);
var ie4 = (document.all && !document.getElementById);
var ie5 = (document.all && document.getElementById);
var ns6 = (!document.all && document.getElementById);

function show(id,keepstatus){

	try {

	// Netscape 4
	if(ns4){
		document.layers[id].visibility = "show";
	}
	// Explorer 4
	else if(ie4){
		document.all[id].style.visibility = "visible";
	}
	// W3C - Explorer 5+ and Netscape 6+
	else if(ie5 || ns6){

		if (!keepstatus && document.getElementById(id).style.visibility == "visible") {
			hide(id);
			return;
		}

		document.getElementById(id).style.display = "block";
		document.getElementById(id).style.visibility = "visible";
	}

	} catch (Exception) {}
}

function hide(id){
	// Netscape 4

	try {

	if(ns4){
		document.layers[id].visibility = "hide";
	}
	// Explorer 4
	else if(ie4){
		document.all[id].style.visibility = "hidden";
	}
	// W3C - Explorer 5+ and Netscape 6+
	else if(ie5 || ns6){
		document.getElementById(id).style.visibility = "hidden";
		document.getElementById(id).style.display = "none";

	}

	} catch (Exception) {}
}

function openAdminConsole(link) {
	url = replace(link.href,'#','admin');
	window.open(url, 'Console', 'toolbar=0,location=0,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=800,height=600');
}

function loadManager(id) {
	var page = '?page=manager&vcd_id='+id;
	window.open(page,'Manager','toolbar=0,location=0,directories=no,status=no,menubar=no,scrollbars=no,resizable=no,width=500,height=300');
}

function copyFiles(form) {
	
	try {
	
		for (key in form.elements) {
			el = form.elements[key];
			uri = el.value;
			if(el.getAttribute("clear"))
				el.type = "text";
			else if (el.type == 'file' && el.value != '') {
				node = document.createElement("input");
				node.type = "hidden";
				node.name = el.name+"_uri";
				node.value = el.value;
				form.appendChild(node);
			}
		}
	} catch (Exception) {}
}

function moveOver(form, boxAvailable, boxChoices)  {
try {

	var objAvailable = document.getElementById(boxAvailable);
	var objChoices = document.getElementById(boxChoices);

	var boxLength = objChoices.length;
	var selectedItem = objAvailable.selectedIndex;

	if (selectedItem < 0) { return; }

	var selectedText = objAvailable.options[selectedItem].text;
	var selectedValue = objAvailable.options[selectedItem].value;
	var i;
	var isNew = true;
	if (boxLength != 0) {
	for (i = 0; i < boxLength; i++) {
		thisitem = objChoices.options[i].text;
		if (thisitem == selectedText) {
			isNew = false;
			break;
      	}
   	  }
	}

	if (isNew) {
		newoption = new Option(selectedText, selectedValue, false, false);
		objChoices.options[boxLength] = newoption;
	}
	objAvailable.options[objAvailable.selectedIndex] = null;
	objAvailable.selectedIndex=-1;

	// Sort the selected
    sortSelect(form[boxChoices]);


	} catch (ex) {
		alert('Could not locate input fields.');
  }
}


function moveBack(form, selected_id, boxAvailable, boxChoices) {

	var objAvailable = document.getElementById(boxAvailable);
	var objChoices = document.getElementById(boxChoices);

	var boxLength = objAvailable.length;
	var selectedItem = objChoices.selectedIndex;

	if (selectedItem < 0) {
		return;
	}

	var selectedText = objChoices.options[selectedItem].text;
	var selectedValue = objChoices.options[selectedItem].value;
	var i;
	var isNew = true;
	if (boxLength != 0) {
	for (i = 0; i < boxLength; i++) {
		thisitem = objAvailable.options[i].text;
			if (thisitem == selectedText) {
			isNew = false;
			break;
      		}
   		}
    }
	if (isNew) {
		newoption = new Option(selectedText, selectedValue, false, false);
		objAvailable.options[boxLength] = newoption;
	}
	objChoices.selectedIndex=-1;

	// Sort the available
	if (boxLength < 1000)
    	sortSelect(form[boxAvailable]);

}


function removeMe(form, boxAvailable, boxChoices) {
	var objAvailable = document.getElementById(boxAvailable);
	var objChoices = document.getElementById(boxChoices);

	var boxLength = objChoices.length;
	arrSelected = new Array();
	var count = 0;
	var selected_index;

	for (i = 0; i < boxLength; i++) {
		if (objChoices.options[i].selected) {
			arrSelected[count] = objChoices.options[i].value;
			selected_index = objChoices.options[i].value;
		}
		count++;
	}

	moveBack(form,selected_index, boxAvailable, boxChoices);

	var x;
	for (i = 0; i < boxLength; i++) {
		for (x = 0; x < arrSelected.length; x++) {
			if (objChoices.options[i].value == arrSelected[x]) {
				objChoices.options[i] = null;
		   }
		}
		boxLength = objChoices.length;
   }
}



function saveMe() {
	var strValues = "";
	var boxLength = document.choiceForm.choiceBox.length;
	var count = 0;
	if (boxLength != 0) {
	for (i = 0; i < boxLength; i++) {
		if (count == 0) {
		strValues = document.choiceForm.choiceBox.options[i].value;
		document.choiceForm.size.value = strValues;
	}
	else {
	strValues = strValues + "," + document.choiceForm.choiceBox.options[i].value;
	document.choiceForm.size.value = strValues;
	}
	count++;
	   }
	}
}

function checkFieldsRaw(form, boxChoices, boxSave) {
	var objChoices = $(boxChoices);
	var objSave = $(boxSave);

	if (objChoices == null) {return;}
	
	var delimiter = "#";
	teString = "";
	tempSt = "";

	for(var i=0; i<objChoices.options.length; i++)  {
		teString = objChoices.options[i].value;
		if (i < objChoices.options.length - 1){
			tempSt = tempSt + teString + delimiter;
		} else {
			tempSt = tempSt + teString;
		}
	}
	objSave.value = tempSt;
}



function checkFields(form) {
	var delimiter = "#";
	teString = "";
	tempSt = "";

	for(var i=0; i<form.choiceBox.options.length; i++)  {
		teString = form.choiceBox.options[i].value;
		if (i < form.choiceBox.options.length - 1){
			tempSt = tempSt + teString + delimiter;
		} else {
			tempSt = tempSt + teString;
		}
	}

	form.id_list.value = tempSt;
	var selectedItem = form.borrowers.selectedIndex;
	var selectedValue = form.borrowers.options[selectedItem].value;

	if (form.id_list.value == "") {
		alert('No movies have been added to loan');
		return false;
	} else if (selectedValue == "null") {
		alert('Select somneone to lend the movies');
		return false;
	} else {
		return true;
	}
}



function onSelectKeyDown()
{
	try {
		if(window.event.keyCode == 46)clr();
	} catch (Exception) {}
}

function selectKeyPress() {
	// Notes:
	//	1) previous keys are cleared onBlur/onFocus and with Delete key
	//	2) if the search doesn't find a match, this returns to normal 1 key search
	//		setting returnValue = false below for ALL cases will prevent
	//		default behavior

	//TODO:
	//	1) add Netscape handling

	try {
		
		var sndr = window.event.srcElement;
		var pre = this.document.all["keys"].value;
		var key = window.event.keyCode;
		var charx = String.fromCharCode(key);
		
			var re = new RegExp("^" + pre + charx, "i"); // "i" -> ignoreCase
			for(var i=0; i<sndr.options.length; i++)
			{
				if(re.test(sndr.options[i].text))
				{
					sndr.options[i].selected=true;
					document.all["keys"].value += charx;
					window.event.returnValue = false;
					break;
				}
			}
	} catch (Exception) {}
}

function clr() {
	try {
		document.all["keys"].value = "";
	} catch (Exception) {}
}

function createBorrower() {
	var url = "?page=borrower";
	window.open(url, 'borrower','height=100,width=200,top=50,left=25');
}


function changeBorrower() {
	var selectionObj = document.getElementById('borrowers');
	var selectedItem = selectionObj.selectedIndex;
	var selectedValue = selectionObj.options[selectedItem].value;

	if (selectedValue != '') {
		var urlbase = replace(jxBase,'index.php','');
		url = urlbase+'?page=settings&action=editborrower&bid='+selectedValue;
		location.href = url;
	}
}

function deleteBorrower() {
	var selectionObj = document.getElementById('borrowers');
	var selectedItem = selectionObj.selectedIndex;
	var selectedValue = selectionObj.options[selectedItem].value;
	var message = "Delete borrower and all his loan records?";

	if (selectedValue != '') {
		if (confirm(message)) {
			url = "?page=settings&action=delborrower&bid="+selectedValue;
			location.href = url;
		}
	}
}

function checkBorrower(form){
	if(form.borrower_name.value == '') {
		alert('Please enter a name');
	    form.borrower_name.focus();
	    return false;
	}
	if (!emailCheck(form.borrower_email.value)) {
		form.borrower_email.focus();
		return false;
	}
	
	form.saveBorrower.disabled = false;
	return true;
}

function val_Empire(form) {

	checkFieldsRaw(form, 'choiceBox', 'id_list');

	if (form.title.value == "") {
	    alert('CD title can\'t be empty');
	    form.title.focus();
	    return false;
	}

	if (form.year.value == "") {
	    alert('Year can\'t be empty');
	    form.year.focus();
	    return false;
	}

	if (!IsNumeric(form.year.value)) {
		alert('Year must be numeric');
	    form.year.focus();
	    return false;
	}


	var mtyp = form.mediatype.options[form.mediatype.selectedIndex].value;
	var mcat = form.category.options[form.category.selectedIndex].value;
	var mcds = form.cds.options[form.cds.selectedIndex].value;

	if (mtyp == "null") {
		alert('Select media type on your copy');
		return false;
	}

	if (mcat == "null") {
		alert('Select main category');
		return false;
	}

	if (mcds == "null") {
		alert('Select number of cd\'s on your copy');
		return false;
	}

	return true;
}




function val_IMDB(form) {

	if (form.title.value == "") {
	    alert('CD title can\'t be empty');
	    form.title.focus();
	    return false;
	}

	if (form.year.value == "") {
	    alert('Year can\'t be empty');
	    form.year.focus();
	    return false;
	}

	if (!IsNumeric(form.year.value)) {
		alert('Year must be numeric');
	    form.year.focus();
	    return false;
	}


	var mtyp = form.mediatype.options[form.mediatype.selectedIndex].value;
	var mcat = form.category.options[form.category.selectedIndex].value;
	var mcds = form.cds.options[form.cds.selectedIndex].value;

	if (mtyp == "null") {
		alert('Select media type on your copy');
		return false;
	}

	if (mcat == "null") {
		alert('Select main category');
		return false;
	}

	if (mcds == "null") {
		alert('Select number of cd\'s on your copy');
		return false;
	}

	return true;
}


function submitBorrower(form) {
	form.vista.disabled=true;
	return true;
}


function emailCheck (emailStr) {

var emailPat=/^(.+)@(.+)$/
var specialChars="\\(\\)<>@,;:\\\\\\\"\\.\\[\\]"
var validChars="\[^\\s" + specialChars + "\]"
var quotedUser="(\"[^\"]*\")"
var ipDomainPat=/^\[(\d{1,3})\.(\d{1,3})\.(\d{1,3})\.(\d{1,3})\]$/
var atom=validChars + '+'
var word="(" + atom + "|" + quotedUser + ")"
var userPat=new RegExp("^" + word + "(\\." + word + ")*$")
var domainPat=new RegExp("^" + atom + "(\\." + atom +")*$")


var matchArray=emailStr.match(emailPat)
if (matchArray==null) {

	alert("Invalid email (check @ signs and dots)")
	return false
}
var user=matchArray[1]
var domain=matchArray[2]

if (user.match(userPat)==null) {
    alert("invalid email username.")
    return false
}

var IPArray=domain.match(ipDomainPat)
if (IPArray!=null) {
	  for (var i=1;i<=4;i++) {
	    if (IPArray[i]>255) {
	        alert("IP not valid")
		return false
	    }
    }
    return true
}

var domainArray=domain.match(domainPat)
if (domainArray==null) {
	alert("Invalid domain name in email.")
    return false
}

var atomPat=new RegExp(atom,"g")
var domArr=domain.match(atomPat)
var len=domArr.length
if (domArr[domArr.length-1].length<2 ||
    domArr[domArr.length-1].length>3) {
   alert("Wrong email extension.")
   return false
}

if (len<2) {
   var errStr="Email domain name missing."
   alert(errStr)
   return false
}

return true;
}

function goSimilar(form) {

	val = form.similar.options[form.similar.selectedIndex].value;
	url = './?page=cd&vcd_id='+val;
	location.href = url;
}


function IsNumeric(strString)   {
   var strValidChars = "0123456789";
   var strChar;
   var blnResult = true;

   if (strString.length == 0) return false;

   //  test strString consists of valid characters listed above
   for (i = 0; i < strString.length && blnResult == true; i++)  {
      strChar = strString.charAt(i);
      if (strValidChars.indexOf(strChar) == -1) {
         blnResult = false;
         }
    }
   return blnResult;
}


function jumpTo(pornstarname, web) {
	var page = "?page=jump&pornstar="+pornstarname+"&web="+web+"";
	window.open(page,'starsearch');
}

function addActors(id) {
	var url = '?page=addpornstars&vcd_id='+id;
	window.open(url, 'addactors', 'height=300,width=420,top=200,left=250');
}

function addScreenshots(id) {
	var url = '?page=addscreens&vcd_id='+id;
	window.open(url, 'addscreens', 'height=300,width=420,top=200,left=250');
}

function changePornstar(pornstar_id) {
	var url = '?page=pornstarmanager&pornstar_id='+pornstar_id;
	window.open(url, 'PornStarManager', 'toolbar=0,location=0,directories=no,status=no,menubar=no,scrollbars=no,resizable=no,width=365,height=270');
}

function fetchstarimage(star_id) {
	var promptext = 'Enter exact image url';
	linktext = prompt(promptext,'');
	if (linktext != null) {
		url = '?page=pornstarmanager&action=fetchimage&pornstar_id='+star_id+'&path='+linktext;
		location.href = url;
	}
}

function deletePornstarImage(pornstar_id) {
	if (confirm('Sure you want to delete ?')) {
		url = '?page=pornstarmanager&action=deleteimage&pornstar_id='+pornstar_id;
		location.href = url;
	}
}

function removeActor(pornstar_id, movie_id) {
	if (confirm('Delete actor from movie?')) {
		url = '?page=manager&vcd_id='+movie_id+'&action=removeactor&actor_id='+pornstar_id;
		location.href = url;
	}
}


function addFeed(type) {
	if (type == 'vcddb') {
		var url = '?page=addrss&type=vcddb';
		window.open(url, 'Rss', 'toolbar=0,location=0,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,height=250,width=350,top=200,left=250');	
	} else if (type == 'site') {
		var url = '?page=addrss&type=site';
		window.open(url, 'Rss', 'toolbar=0,location=0,directories=no,status=no,menubar=no,scrollbars=no,resizable=no,height=80,width=300,top=200,left=250');
	} else {
		return false;	
	}
			
	
	
}


function rssCheck(form) {
	count=0

	for (i=0; i<form.elements.length ;i++)
	if (form.elements[i].type=='checkbox' && form.elements[i].checked){
	 	count+=1
	}
	if (count == 0) {
		alert('Select at least one feed to continue');
		return false;
	}

	return true;
}

function deleteFeed(id) {
	if (confirm('Sure you want to delete ?')) {
		url = '?page=settings&action=delrss&rss_id='+id;
		location.href = url;
	}
}

function deleteMetaType(id) {
	var msg = 'Delete metadata type and all data entered within it?';
	if (confirm(msg)) {
		url = '?page=settings&action=delmetatype&meta_id='+id;
		location.href=url;
	}
}

function deleteComment(item_id,id) {
	url = '?page=cd&vcd_id='+item_id+'&action=delComment&cid='+id;
	location.href = url;
}


function doTooltip(e, num) {
	
  if ( typeof Tooltip == "undefined" || !Tooltip.ready ) return;
  var cntnt = wrapTipContent(num);
  var tip = document.getElementById( Tooltip.tipID );
  if ( messages[num][4] ) tip.style.width = messages[num][4] + "px";
  Tooltip.show(e, cntnt);
}

function hideTip() {
  if ( typeof Tooltip == "undefined" || !Tooltip.ready ) return;
  Tooltip.hide();
}


function wrapTipContent(num) {
  var cntnt = '<div class="img"><img src="' + messages[num][0] + '" width="' +
    messages[num][1] + '" height="' + messages[num][2] + '" border="0"></div>';
  if ( messages[num][3] ) cntnt += '<div class="msg">' + messages[num][3] + '</div>';
	return cntnt;
}

function addtowishlist(id) {
	var url = '?page=cd&vcd_id='+id+'&action=addtowishlist';
	location.href = url;
}

function deleteFromWishlist(id) {
	var url = '?page=wishlist&action=delete&vcd_id='+id;
	location.href = url;
}

function deleteCover(cover_id, vcd_id) {
	var url = '?page=manager&vcd_id='+vcd_id+'&action=deletecover&cover_id='+cover_id;
	location.href = url;
}

function showUserStatus() {
	url = '?page=overview';
	window.open(url, 'overview', 'scrollbars=yes,resizable=yes,height=600,width=830,top=50,left=25');
}

function showUserStatusDetailed() {
	url = 'pages/user_status_detail.php';
	window.open(url, 'popup2', 'scrollbars=yes,resizable=yes,height=800,width=830,top=50,left=25');
}

function showAllMoviesDetailed() {
	url = 'pages/all_movies_details.php';
	window.open(url, 'popup3', 'scrollbars=yes,resizable=yes,height=800,width=830,top=50,left=25');
}

function checkupload(formvalue) {
	if (formvalue == "") {
		alert('Select file to upload');
		return false;
	} else {
		return true;
	}
}

function clearXML() {
	var url = '?page=add&source=xml&action=cleanup';
	location.href = url;
}

function showupload(form, layername) {
	if (form.xmlthumbs.checked) {
		show(layername);
	} else {
		hide(layername);

	}
}

function checkXMLConfirm(form) {
	if (form.xmlthumbs.checked && form.xmlthumbfile.value == "") {
		alert('Select the XML file for the thumbnails\nor uncheck the thumbnails upload checkbox!');
		return false;
	} else {
		return true;
	}
}

function checkAdvanced(form) {

	var category = form.category.options[form.category.selectedIndex].value;
	var year = form.year.options[form.year.selectedIndex].value;
	var mediatype = form.mediatype.options[form.mediatype.selectedIndex].value;
	var owner = form.owner.options[form.owner.selectedIndex].value;
	var rating = form.grade.options[form.grade.selectedIndex].value;



	if (form.title.value == '' && category == 'null' && year == 'null' && mediatype == 'null' && owner == 'null' && rating == 'null')
	{
		alert('Please define the search criteria');
		return false;
	}

	return true;
}

function checkManually(form) {
	var cds = form.cds.options[form.cds.selectedIndex].value;
	var mediatype = form.mediatype.options[form.mediatype.selectedIndex].value;
	var category = form.category.options[form.category.selectedIndex].value;

	if (form.title.value == "") {
		alert('Select title on your movie');
		form.title.focus();
		return false;
	}

	if (mediatype == 'null') {
		alert('Select media type on your movie');
		return false;
	}

	if (category == 'null') {
		alert('Select category on your movie');
		return false;
	}

	if (cds == 'null') {
		alert('Select CD count on your movie');
		return false;
	}

	return true;
}

function deleteCopy(usercopies, totalcopies, id, media_id) {
	if (totalcopies == 1) {
		var message = "This is the only copy in the database\nAll movie information will be deleted\nContinue and delete?";
		if (confirm(message)) {
			url = '?page=manager&action=deletecopy&vcd_id='+id+'&media_id='+media_id+'&mode=full';
			location.href = url;
		}
	} else {

		var message = "Delete this copy ?";
		if (confirm(message)) {
			url = '?page=manager&action=deletecopy&vcd_id='+id+'&media_id='+media_id+'&mode=single';
			location.href = url;
		}
	}
}

function checkListed(form) {

	checkFieldsRaw(form,'choiceBox', 'id_list');
	if (form.id_list.value == "") {
		alert('Select at least one movie to proceed');
		return false;
	} else {
		return true;
	}
}

function confirmListed(form) {

    for (i=0; i<form.elements.length ;i++)  {
	if (form.elements[i].type=='select-one') {
    	val = form.elements[i].options[form.elements[i].selectedIndex].value;
        if (val == 'null') {
        	alert('Select media type for all the titles');
            return false;
         }
      }
 	}

 	return true;
}


function printView(type) {
	url = '?page=printview&mode=' + type;
	window.open(url, 'printview', 'scrollbars=yes, menubar=yes, resizable=yes,height=600,width=830,top=50,left=25');
}


function markSeen(movie_id, flag) {
	url = '?page=cd&vcd_id='+movie_id+'&action=seenlist&flag='+flag;
	location.href = url;
}

function showPage(box, bOpenWindow) {
    val = box.options[box.selectedIndex].value;
    box.selectedIndex=0;

    if ((-1 < box.selectedIndex) && (val.lastIndexOf('nil') > -1 )) {
       return true
    }

    if (bOpenWindow)
        openWin(val);
    else
        location.href = val;
    return true;
}


function showonlymine(cat_id) { 	 
	url = '?page=category&category_id='+cat_id+'&action=onlymine';
	location.href = url; 	 
}


function checkReg(form) {

	if (form.name.value == "") {
		alert('Please type in your full name.');
		form.name.focus();
		return false;
	}

	if (form.username.value == "") {
		alert('Please select username.');
		form.username.focus();
		return false;
	}

	if (form.username.value.length < 3) {
		alert('Username must be at least 3 characters.');
		form.username.focus();
		return false;
	}

	if (!emailCheck(form.email.value)) {
		form.email.focus();
		return false;
	}

	if (form.password.value.length < 5) {
		alert('Password needs to be at least 5 characters!');
		form.password.focus();
		return false;
	}

	if (form.password.value != form.password2.value) {
		alert('Passwords do not match!');
		form.password.focus();
		return false;
	}


	return true;


}


// sort function - ascending (case-insensitive)
function sortFuncAsc(record1, record2) {
    var value1 = record1.optText.toLowerCase();
    var value2 = record2.optText.toLowerCase();
    if (value1 > value2) return(1);
    if (value1 < value2) return(-1);
    return(0);
}

// sort function - descending (case-insensitive)
function sortFuncDesc(record1, record2) {
    var value1 = record1.optText.toLowerCase();
    var value2 = record2.optText.toLowerCase();
    if (value1 > value2) return(-1);
    if (value1 < value2) return(1);
    return(0);
}

function sortSelect(selectToSort, ascendingOrder) {
    if (arguments.length == 1) ascendingOrder = true;    // default to ascending sort

    // copy options into an array
    var myOptions = [];
    for (var loop=0; loop<selectToSort.options.length; loop++) {
        myOptions[loop] = { optText:selectToSort.options[loop].text, optValue:selectToSort.options[loop].value };
    }

    // sort array
    if (ascendingOrder) {
        myOptions.sort(sortFuncAsc);
    } else {
        myOptions.sort(sortFuncDesc);
    }

    // copy sorted options from array back to select box
    selectToSort.options.length = 0;
    for (var loop=0; loop<myOptions.length; loop++) {
        var optObj = document.createElement('option');
        optObj.text = myOptions[loop].optText;
        optObj.value = myOptions[loop].optValue;
        selectToSort.options.add(optObj);
    }
}


var detect = navigator.userAgent.toLowerCase();
var OS,browser,version,total,thestring;

if (checkIt('konqueror'))
{
	browser = "Konqueror";
	OS = "Linux";
}
else if (checkIt('safari')) browser = "Safari"
else if (checkIt('omniweb')) browser = "OmniWeb"
else if (checkIt('opera')) browser = "Opera"
else if (checkIt('webtv')) browser = "WebTV";
else if (checkIt('icab')) browser = "iCab"
else if (checkIt('msie')) browser = "Internet Explorer"
else if (!checkIt('compatible'))
{
	browser = "Netscape Navigator"
	version = detect.charAt(8);
}
else browser = "An unknown browser";

if (!version) version = detect.charAt(place + thestring.length);

if (!OS)
{
	if (checkIt('linux')) OS = "Linux";
	else if (checkIt('x11')) OS = "Unix";
	else if (checkIt('mac')) OS = "Mac"
	else if (checkIt('win')) OS = "Windows"
	else OS = "an unknown operating system";
}

function checkIt(string)
{
	place = detect.indexOf(string) + 1;
	thestring = string;
	return place;
}


function filebrowse(param, destField) {
	try {
		if (destField == null) {
			var url = '?page=playerbrowse&from='+param;
		} else {
			var url = '?page=playerbrowse&from='+param+'&field='+destField;
		}

		window.open(url, 'FileBrowser', 'height=60,width=380,top=200,left=250');

	} catch (Ex) {}
}



function getFileName(form, fieldname) {
	try {
		filename = form.filename.value;
		var obj = opener.document.getElementById(fieldname);
		obj.value = filename;
		self.close();
		return false;

  } catch (ex) {
  }
}

function getPlayerFileName(form) {
	try {
		filename = form.filename.value;
		var targetField = opener.document.player.player;
		targetField.value = filename;
		self.close();
		return false;
  } catch (Exception) {}
}



function replace(s, t, u) {
  /*
  **  Replace a token in a string
  **    s  string to be processed
  **    t  token to be found and removed
  **    u  token to be inserted
  **  returns new String
  */
  i = s.indexOf(t);
  r = "";
  if (i == -1) return s;
  r += s.substring(0,i) + u;
  if ( i + t.length < s.length)
    r += replace(s.substring(i + t.length, s.length), t, u);
  return r;
  }


function viewMode(category_id, viewmode, batch) {
	url = "./?action=viewmode&category_id="+category_id+"&batch="+batch+"&mode="+viewmode;
	location.href = url;
}

function switchTemplate(template) {
	url = '?page=settings&action=templates&template='+template;
	location.href = url;
}

function managerSubmit(form, action) {
	checkFieldsRaw(form,'audioChoices','audio_list');
	checkFieldsRaw(form,'langChoices','sub_list');
	try {
		checkFieldsRaw(form,'adultCategoriesUsed', 'id_list');		
	} catch (ex) {}
}

function setManagerMediaType(obj, movieid) {
	var id = obj.options[obj.selectedIndex].value;
	var url = '?page=manager&vcd_id='+movieid+'&dvd='+id;
	location.href = url;
}


function showDVD(id) {
	try {
		var obj = document.getElementById(id);
		if (obj != null) {
			return obj.innerHTML;
		} else {
			return "";
		}
	} catch (ex) {
		alert(ex.Message);
	}
}


function deleteMeta(metadata_id, itemId) {
	try {
		if (metadata_id > 0) {
			url = '?page=manager&vcd_id='+itemId+'&action=deletemeta&meta_id='+metadata_id;
			location.href = url;
		}
	} catch (ex) {}
}


/* Ajax based form functions */

var currCountryName;
var currCountryKey;

function updateSubtitles( response )   {
  	obj = new Object(response);
  	var urlbase = replace(jxBase,'index.php','');
  	var img = new Image();
  	img.src = urlbase+obj;

  	var html = '<ul>';

  	var htmlfield = document.getElementById('dvdsubs');
  	if (htmlfield.value.length==0) {
  		htmlfield.value += currCountryKey;
  	} else {
  		htmlfield.value += '#'+currCountryKey;
  	}



  	var subtitles = document.getElementById('subtitles');
    var lis = subtitles.getElementsByTagName('LI');
	for (i=0; i < lis.length; i++) {
		lid = lis[i].id;
		if (lid != currCountryKey) {
			html += '<li id='+lid+'>' + lis[i].innerHTML + '</li>';
		}

	}

	var iMaxlen = 10;
	var lang = new String(currCountryName);

	if (lang.length > iMaxlen) {
		var firstBracket = lang.indexOf('(');
		var lastBracket = lang.indexOf(')');
		if (firstBracket != -1 && lastBracket != -1) {
			lang = lang.substring(0, firstBracket);
		} else {
			lang = lang.substring(0, iMaxlen) + '..';
		}
	}

	
	
		
	html += "<li id="+currCountryKey+"><img src='"+img.src+"' vspace='2' hspace='2' height='12' border='0' ondblclick=\"removeSub('"+currCountryKey+"')\" title=\""+currCountryName+"\" align='absmiddle'>"+lang+"</li>";
	html += "</ul>";

  	subtitles.innerHTML = html;
}

function removeSub(key) {
	var subtitles = document.getElementById('subtitles');
	var htmlfield = document.getElementById('dvdsubs');
	htmlfield.value = '';

    var lis = subtitles.getElementsByTagName('LI');
    var lid = "";
    var html = '<ul>';
	for (i=0; i < lis.length; i++) {
		lid = lis[i].id;
		if (lid != key) {
			html += '<li id=\"'+lid+'\">' + lis[i].innerHTML + '</li>';
			if (htmlfield.value == '') {
				htmlfield.value = lid ;
			} else {
				htmlfield.value += '#' + lid ;
			}

		}
	}
	html += "</ul>";
	subtitles.innerHTML = html;
}

function addSubtitle(form, source) {
	var objList = document.getElementById(source);
	var selectedItem = objList.selectedIndex;
	if (selectedItem < 0) { return; }
	var selectedText = objList.options[selectedItem].text;
	var selectedValue = objList.options[selectedItem].value;

	currCountryName = selectedText;
	currCountryKey = selectedValue;

	obj = new vcddbAjax('getCountryFlag');
	obj.invoke(selectedValue,updateSubtitles);
}

function addAudio(form, source) {
	var objList = document.getElementById(source);
	var selectedItem = objList.selectedIndex;
	if (selectedItem < 0) { return; }
	var selectedText = objList.options[selectedItem].text;
	var selectedValue = objList.options[selectedItem].value;

	var audio = document.getElementById('audio');

  	var htmlfield = document.getElementById('dvdaudio');
  	if (htmlfield.value.length==0) {
  		htmlfield.value += selectedValue;
  	} else {
  		htmlfield.value += '#'+selectedValue;
  	}


  	var html = '<ul>';
    var lis = audio.getElementsByTagName('LI');
	for (i=0; i < lis.length; i++) {
		lid = lis[i].id;
		if (lid != selectedValue) {
			html += '<li class=audio id='+lid+' ondblclick=\"removeAudio(\''+lid+'\')\">' + lis[i].innerHTML + '</li>';
		}
	}

	html += '<li id='+selectedValue+' ondblclick=\"removeAudio(\''+selectedValue+'\')\">' +selectedText + '</li>';
	html += "</ul>";

	audio.innerHTML = html;
}

function removeAudio(key) {
	var audios = document.getElementById('audio');
	var htmlfield = document.getElementById('dvdaudio');
	htmlfield.value = '';

    var lis = audios.getElementsByTagName('LI');
    var lid = "";
    var html = '<ul>';
	for (i=0; i < lis.length; i++) {
		lid = lis[i].id;
		if (lid != key) {
			html += '<li id=\"'+lid+'\" ondblclick=\"removeAudio(\''+lid+'\')\">' + lis[i].innerHTML + '</li>';
			if (htmlfield.value == '') {
				htmlfield.value = lid;
			} else {
				htmlfield.value += '#' + lid;
			}

		}
	}
	html += "</ul>";
	audios.innerHTML = html;
}


/* Functions when adding new movie and changing media type */

function doMediaTypeData(selectedValue) {
	processing(true);
	obj = new vcddbAjax('getDataForMediaType');
	obj.invoke('meta|cover|dvd', selectedValue, showForms);
}

function cutStr(str, maxlen) {
	return (str.length > maxlen)?str.substring(0,maxlen-3)+'...':str;
}

function getFieldHTML(data) {
	var fieldHTML = '';
	switch(data.type) {
		case 'file'		: fieldHTML += '<tr><td colspan="2">'+data.label+':<br/><input type="file" name="'+data.id+'" '+'id="'+data.id+'"'+((data.clear)?' clear="true"':'')+'/></td></tr>'; break;
		case 'text'		: fieldHTML += '<tr><td>'+data.label+':</td><td><input type="text" name="'+data.id+'" size="18"/></td></tr>'; break;
		case 'select'	: fieldHTML += '<tr><td>'+data.label+':</td><td><select name="'+data.id+'"'+((data.multi)?' multiple size="3"':'')+' class="input">';
		for (dataID in data.data)
		if (dataID != '______array') {
			dataObj = data.data[dataID];
			fieldHTML += '<option value="'+dataObj.value+'"'+((dataObj.selected)?' selected':'')+'>'+cutStr(dataObj.label, 20)+'</option>';
		}
		fieldHTML += '</select></td></tr>'
		break;
		default: fieldHTML = ""; break;
	}
	return fieldHTML;
}

function processing(start) {
	button = document.getElementById('confirmButton');
	button.disabled = start;
	if (start) {
		button.style.color='#cccccc';
		show('processIcon');
	} else {
		button.style.color='#000000';
		show('processIcon');
	}
}


function showForms(dataArrArr) {
	for (dataArrID in dataArrArr) {
		dataArr = dataArrArr[dataArrID];
		if (dataArr != '______array') {
			var html = '';
			if (dataArr.data) {
				html += '<table cellspacing="1" cellpadding="1" width="100%" class="plain">';
				html += '<tr><td class="header" colspan="2">'+dataArr.header+'</td></tr>';
				for (dataID in dataArr.data) {
					html += getFieldHTML(dataArr.data[dataID]);
				}
				html += '</table>';
			}
			try  { document.getElementById(dataArr.query+'Fields').innerHTML = html; } catch (ex) {}
			processing(false);
		}
	}
}

function l(type) {
	if (type==0) {
		hide('r-col');
	} else if(type==1) {
		show('r-col');
	}
	createCookie('rbar', type, 365);
}


function createCookie(name,value,days) {
	if (days) {
		var date = new Date();
		date.setTime(date.getTime()+(days*24*60*60*1000));
		var expires = "; expires="+date.toGMTString();
	}
	else var expires = "";
	document.cookie = name+"="+value+expires+"; path=/";
}

function readCookie(name) {
	var nameEQ = name + "=";
	var ca = document.cookie.split(';');
	for(var i=0;i < ca.length;i++) {
		var c = ca[i];
		while (c.charAt(0)==' ') c = c.substring(1,c.length);
		if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
	}
	return null;
}

function eraseCookie(name) {
	createCookie(name,"",-1);
}

function vcddbAjax(funcname) {
	var ajax = new Ajax("POST", jxBase, false, false);
	this.cls = 'VCDAjaxHelper';
	this.fnc = funcname;
	this.invoke = function() { ajax.callMethod(this.cls, this.fnc, this.invoke.arguments) }
}

function invokeRss(id) {
	
	img = new Image();
	img.src = 'images/processing.gif'; 
	img.setAttribute('border',0);
	img.setAttribute('hspace',140);
	img.setAttribute('vspace',40);
	img.setAttribute('title','Loading ...');
	div = document.getElementById('rss'+id);
	div.innerHTML = '';
	div.appendChild(img);
		
	obj = new vcddbAjax('getRss');
	obj.invoke(id,renderRss);
}

function renderRss(response) {
	obj = new Object(response);
	items = obj.items;
	ul = document.getElementById('rss'+obj.id);
	ul.innerHTML = '';
	hover = "this.T_SHADOWWIDTH=1;this.T_STICKY=1;this.T_OFFSETX=-70;this.T_WIDTH=250;return escape('#')";
	for (i=0;i<items.length;i++) {
		var li = document.createElement('li');
		var link = document.createElement('a');
		var text = document.createTextNode(items[i].title);
		link.setAttribute('href', items[i].link);
		link.setAttribute('onmouseover', hover.replace('#',items[i].hover));
		link.setAttribute('target', '_blank');
		link.appendChild(text);
		li.appendChild(link);
		ul.appendChild(li);
	}
}


function playMovie(id) {
	var doc = document.body;
	var frame = document.createElement('iframe');
	frame.setAttribute('src','?page=launcher&id='+id);
	frame.setAttribute('id','playframe');
	frame.setAttribute('height',0);
	frame.setAttribute('width',0);
	frame.setAttribute('style','visibility:hidden');
	doc.appendChild(frame);
}

function addLoadEvent(func) {
  var oldonload = window.onload;
  if (typeof window.onload != 'function') {
    window.onload = func;
  } else {
    window.onload = function() {
      oldonload();
      func();
    }
  }
}


function ShowScreenshots(movie_id) {
	try {
		obj = new vcddbAjax('getScreenshots');
		obj.invoke(movie_id, doShowScreenshots);
	} catch (ex) {
		alert(ex.Message);
	}
}

function doShowScreenshots(response) {
	try {
		obj = new Object(response);
		
		if (obj.errorCode>0) {
			alert(obj.errorMessage);
			return;
		}
		
		files = obj.files;
		id = obj.id;
		title = '';
		try { title = document.getElementById('mTitle').innerHTML;} 
			catch (ex) { try { title = document.getElementById('m'+id).innerHTML;} catch(ex) {}
		}
		try {
			oldDiv = document.getElementById('dSlider');
			oldDiv.innerHTML = '';
		} catch (ex) {}
		
		base = 'upload/screenshots/albums/'+id+'/';
		doc = document.body;
	
		// create container
		var c = document.createElement('div');
		c.setAttribute('id','dSlider');
		c.setAttribute('height',0);
		c.setAttribute('width',0);
		c.setAttribute('style','visibility:hidden;display:none;height:0;width:0');
		doc.appendChild(c);
		
		for (i=0;i<files.length;i++) {
			l = document.createElement('a');
			l.setAttribute('href',base+files[i]);
			l.setAttribute('rel','lyteshow[s]');
			l.setAttribute('title', title +' - Screenshot ' + (i+1));
			if (i==0) {
				l.setAttribute('id','startslide');
			}
			c.appendChild(l);
		}
		
		var el = document.getElementById('startslide');
	    myLytebox.updateLyteboxItems();
	  	myLytebox.start(el,true,false);
		
	} catch (e) {
		alert(e.message);
	}
}


function showSuggestion(form) {
	try {
		var seen = 0;
		if (form.onlynotseen != null && form.onlynotseen.checked) {
			seen = 1;
		}
		var category = form.category.options[form.category.selectedIndex].value;
		var urlbase = replace(jxBase,'index.php','');
		
		show('suggestion',true);
		hide('noresults');
		
		img = new Image();
		img.src = urlbase+'images/processing.gif'; 
		img.setAttribute('border',0);
		img.setAttribute('title','Loading ...');
		img.setAttribute('vspace',60);
		div = document.getElementById('cover');
		div.innerHTML = '';
		div.appendChild(img);
		
		obj = new vcddbAjax('getRandomMovie');
		obj.invoke(category,seen,doShowSuggestion);

	} catch (Exception) {}
}

function doShowSuggestion(response) {
	try {
		
		var urlbase = replace(jxBase,'index.php','');
		
		if (response == null) {
			show('noresults',true);
			hide('suggestion');
			return;
		}
		
		obj = new Object(response);
		if (obj.title != 'undefined') {
			dCover = document.getElementById('cover');
			dTitle = document.getElementById('title');
			dCategory = document.getElementById('cat');
			dYear = document.getElementById('year');	
			dLink = document.getElementById('link');
			
			dTitle.innerHTML = obj.title;
			dCategory.innerHTML = obj.category;
			dYear.innerHTML = obj.year;
			dLink.href = urlbase+'?page=cd&vcd_id='+obj.id;
			
			dCover.innerHTML = '';
			var cover = new Image();
			cover.src = urlbase+'?page=file&cover_id='+obj.cover_id;
			cover.setAttribute('border',0);
			cover.setAttribute('class','imgx');
			cover.setAttribute('width',120);
			dCover.appendChild(cover);
		} 
	} catch (ex) {
		alert(ex.message);
	}
}

function ImageTip(data) {
	var img = '<img src=\"'+data[0]+'\" border="0" width=\"'+data[1]+'\" height=\"'+data[2]+'\"/>';
	return Tip(doImageTip(data),BGCOLOR, '#ffffff',BORDERCOLOR,'#cfcfcf', WIDTH, data[1], PADDING,5,SHADOW,true)
}

function doImageTip(data) {
	return '<img src=\"'+data[0]+'\" border="0" width=\"'+data[1]+'\" height=\"'+data[2]+'\"/>';
}

function TextTip(data) {
	return Tip(doTextTip(data[0]), SHADOWWIDTH,1,STICKY,1,OFFSETX,-70,WIDTH,250);
}

function doTextTip(data) {
	return decodeURI('<div align="center">'+data+'</div>');
}

function DvdTip(layerid) {
	//this.T_SHADOWWIDTH=1;this.T_STICKY=1;this.T_ABOVE=true;this.T_LEFT=false; this.T_WIDTH=284;";
	return TagToTip(layerid,SHADOWWIDTH,1,ABOVE,true,LEFT,false,FADEIN,150,FADEOUT,150,
		WIDTH,280,BGCOLOR, '#ffffff',BORDERCOLOR,'#cfcfcf',SHADOW,true,SHADOWWIDTH,1,PADDING,0);
}

function $(id) {
	return document.getElementById(id);
}