var ns4 = (document.layers);
var ie4 = (document.all && !document.getElementById);
var ie5 = (document.all && document.getElementById);
var ns6 = (!document.all && document.getElementById);

function show(id){

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

		if (document.getElementById(id).style.visibility == "visible") {
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

function openAdminConsole() {
	url = 'admin/';
	window.open(url, 'Console', 'toolbar=0,location=0,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=800,height=600');
}

function showcover(image, db, id) {
	var url = "pages/cover.php?pic="+image+"&db="+db+"&id="+id+"";
	window.open(url, 'popup', 'height=200,width=200,top=50,left=25');
}


function loadManager(cd_id) {
	var page = "pages/manager.php?cd_id="+cd_id+"";
	window.open(page,'Manager','toolbar=0,location=0,directories=no,status=no,menubar=no,scrollbars=no,resizable=no,width=500,height=300');
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
	var objChoices = document.getElementById(boxChoices);
	var objSave = document.getElementById(boxSave);

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
	if(window.event.keyCode == 46)
		clr();
}

function selectKeyPress() {
	// Notes:
	//	1) previous keys are cleared onBlur/onFocus and with Delete key
	//	2) if the search doesn't find a match, this returns to normal 1 key search
	//		setting returnValue = false below for ALL cases will prevent
	//		default behavior

	//TODO:
	//	1) add Netscape handling


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
}

function clr()
{
	document.all["keys"].value = "";
}

function createBorrower() {
	var url = "pages/borrower.php";
	window.open(url, 'popup', 'height=100,width=200,top=50,left=25');
}


function changeBorrower(form) {
	var selectedItem = form.borrowers.selectedIndex;
	var selectedValue = form.borrowers.options[selectedItem].value;

	if (selectedValue == "null") {
		alert('Select name to edit');
		return false;
	} else {
		url = "?page=private&o=settings&edit=borrower&bid="+selectedValue+"";
		location.href = url;
	}

}

function deleteBorrower(form) {
	var selectedItem = document.borrowForm.borrowers.selectedIndex;
	var selectedValue =document.borrowForm.borrowers.options[selectedItem].value;
	var message = "Delete borrower and all his loan records?";

	if (selectedValue != "null") {
		if (confirm(message)) {
			url = "exec_query.php?action=delete_borrower&bid="+selectedValue;
			location.href = url;
		}
	}
}

function val_borrower(form){
	  if(form.borrower_name.value == "")
	    {
	    alert("Please enter a name");
	    form.borrower_name.focus();
	    return false;
	  }

	  if (!emailCheck(form.borrower_email.value))
	  {
	  	form.borrower_email.focus();
	  	return false;
	  }

	form.vista.disabled = false;

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


function returnloan(loan_id) {
	if (confirm('Return movie ?')) {
		url = './exec_query.php?action=returnloan&loan_id='+loan_id;
		location.href = url;
	}
}

function goSimilar(form) {

	val = form.similar.options[form.similar.selectedIndex].value;
	url = './?page=cd&vcd_id='+val;
	location.href = url;
}

function Valmynd(box, bOpenWindow) {
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


function openWin(url){
		vindu = window.open(url);
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
	var page = "../pages/jump.php?pornstar="+pornstarname+"&web="+web+"";
	window.open(page,'starsearch');
}

function addActors(cd_id) {
	var url = './editactors.php?&id='+cd_id+'';
	window.open(url, 'addactors', 'height=300,width=420,top=200,left=250');
}

function changePornstar(pornstar_id) {
	var url = './pmanager.php?pornstar_id='+pornstar_id+'';
	window.open(url, 'PornStarManager', 'toolbar=0,location=0,directories=no,status=no,menubar=no,scrollbars=no,resizable=no,width=350,height=270');
}

function fetchstarimage(star_id) {
	var promptext = 'Enter exact image url';
	linktext = prompt(promptext,'');
	if (linktext != null) {
		url = '../exec_query.php?action=fetchimage&star_id='+star_id+'&path='+linktext;
		location.href = url;
	}
}

function delpornstarImage(pornstar_id) {
	if (confirm('Sure you want to delete ?')) {
		url = '../exec_query.php?action=delimage&star_id='+pornstar_id;
		location.href = url;
	}
}

function del_actor(pornstar_id, movie_id) {
	if (confirm('Delete actor from movie?')) {
		url = '../exec_query.php?action=delactor&actor_id='+pornstar_id+'&movie_id='+movie_id;
		location.href = url;
	}
}


function addFeed() {
	var url = './pages/addRssFeed.php';
	window.open(url, 'RSSFEED', 'toolbar=0,location=0,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,height=250,width=350,top=200,left=250');
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
		url = 'exec_query.php?action=delrss&rss_id='+id;
		location.href = url;
	}
}

function deleteMetaType(id) {
	var msg = 'Delete metadata type and all data entered within it?';
	if (confirm(msg)) {
		url = 'exec_query.php?action=delmetatype&meta_id='+id;
		location.href=url;
	}
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
	var url = 'exec_query.php?action=addtowishlist&vcd_id='+id;
	location.href = url;
}

function deleteFromWishlist(id) {
	var url = 'exec_query.php?action=deletefromwishlist&vcd_id='+id;
	location.href = url;
}

function deleteCover(cover_id, vcd_id) {
	var url = '../exec_query.php?action=deletecover&cover_id='+cover_id+'&vcd_id='+vcd_id;
	location.href = url;
}

function showUserStatus() {
	url = 'pages/user_status.php';
	window.open(url, 'popup', 'scrollbars=yes,resizable=yes,height=600,width=830,top=50,left=25');
}

function checkupload(formvalue) {
	if (formvalue == "") {
		alert('Select file to upload');
		return false;
	} else {
		return true;
	}
}

function clearXML(filename) {
	var url = 'exec_query.php?action=cleanxml&filename='+filename;
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

function deleteCopy(usercopies, totalcopies, cd_id, media_id) {
	if (totalcopies == 1) {
		var message = "This is the only copy in the database\nAll movie information will be deleted\nContinue and delete?";
		if (confirm(message)) {
			url = '../exec_query.php?action=deletecopy&cd_id='+cd_id+'&media_id='+media_id+'&mode=full';
			location.href = url;
			return;
		}
	}

	var message = "Delete this copy ?";
	if (confirm(message)) {
		url = '../exec_query.php?action=deletecopy&cd_id='+cd_id+'&media_id='+media_id+'&mode=single';
		location.href = url;
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
	url = 'pages/printView.php?mode=' + type;
	window.open(url, 'popup', 'scrollbars=yes, menubar=yes, resizable=yes,height=600,width=830,top=50,left=25');
}


function markSeen(movie_id, flag) {
	url = 'exec_query.php?action=seenlist&vcd_id='+movie_id+'&flag='+flag;
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


function showSuggestion(form) {
	try {

		var seen = 0;
		if (form.onlynotseen != null) {
			if (form.onlynotseen.checked) {
				seen = 1;
			}
		}

		var category = form.category.options[form.category.selectedIndex].value;
		var url = 'pages/user_suggestion.php?do=suggest&cat='+category+'&seen='+seen;
		window.frames.suggestion.location.href = url;

	} catch (Exception) {}
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


function showonlymine(cat_id) {
	url = 'exec_query.php?action=onlymine&cat_id='+cat_id;
	location.href = url;
}

function adjustPlayer() {
	var url = './pages/player.php';
	window.open(url, 'PLAYER', 'height=250,width=400,top=200,left=250');
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
			var url = 'filebrowse.php?from='+param;
		} else {
			var url = 'filebrowse.php?from='+param+'&field='+destField;
		}

		window.open(url, 'FILEBROWSE', 'height=60,width=380,top=200,left=250');

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



function playFile(command) {
	try {




		if (!document.all) {
		 	alert ("Sorry but this feature is only\navailable with Internet Explorer.");
  			return;
		}

		var ws = new ActiveXObject("WScript.Shell");
		if (OS == 'Windows') {
			command = replace(command, "#", "\\");
			command = replace(command, "|", "\"");
		}
 		ws.Exec(command);

	} catch (Exception) {
		alert('In order for this to work, you must add\nthis website\'s address to your \"Trusted Sites\"\nunder \"Tools > Internet options > Security\"');
	}
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
	url = 'exec_query.php?action=templates&name='+template;
	location.href = url;
}

function doManagerSubmit(form) {
	try {
		var dvdSelectedMediaID = form.options[form.selectedIndex].value;
		var dvdBox = document.getElementById('selected_dvd');
		dvdBox.value = dvdSelectedMediaID;
		var updateButton = document.getElementById('update');
		updateButton.click();

	} catch (ex) {
		alert(ex.Message);
	}
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

function deleteNFO(metadata_id, cd_id) {
	try {
		if (metadata_id > 0) {
			url = '../exec_query.php?action=deleteNFO&meta_id='+metadata_id+'&rid='+cd_id;
			location.href = url;
		}
	} catch (ex) {}
}

function deleteMeta(metadata_id, cd_id) {
	try {
		if (metadata_id > 0) {
			url = '../exec_query.php?action=deletemeta&meta_id='+metadata_id+'&rid='+cd_id;
			location.href = url;
		}
	} catch (ex) {}
}


/* Ajax based form functions */ 

var currCountryName;
var currCountryKey;

function updateSubtitles( response )   { 
  	obj = new Object(response);
  	var img = new Image();
  	img.src = obj;
  	  	
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
			htmlfield.value += lid + '#';
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
	
	x_dvdObj.getCountryFlag(selectedValue, updateSubtitles);
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
  		htmlfield.value += selectedValue + '#';	  		
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
			htmlfield.value += lid + '#';
		}
	}
	html += "</ul>";
	audios.innerHTML = html;
}