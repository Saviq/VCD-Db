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

function openAdminConsole() {
	url = getBase()+'admin';
	window.open(url, 'Console', 'toolbar=0,location=0,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=800,height=600');
}

function loadManager(id) {
	var page = getBase()+'?page=manager&vcd_id='+id;
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

	if (form.id_list.value == '') {
		alert(Trans.late('loanlist'));
		return false;
	} else if (selectedValue == '') {
		alert(Trans.late('loanborrower'));
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
	var url = getBase()+'?page=borrower';
	window.open(url, 'borrower','height=100,width=200,top=50,left=25');
}

function changeBorrower() {
	var selectionObj = $('borrowers');
	var selectedItem = selectionObj.selectedIndex;
	var selectedValue = selectionObj.options[selectedItem].value;

	if (selectedValue != '') {
		url = getBase()+'?page=settings&action=editborrower&bid='+selectedValue;
		location.href = url;
	}
}

function deleteBorrower() {
	var selectionObj = $('borrowers');
	var selectedItem = selectionObj.selectedIndex;
	var selectedValue = selectionObj.options[selectedItem].value;
	var message = Trans.late('deleteborrower');

	if (selectedValue != '') {
		if (confirm(message)) {
			url = getBase()+'?page=settings&action=delborrower&bid='+selectedValue;
			location.href = url;
		}
	}
}

function checkBorrower(form){
	if(form.borrower_name.value == '') {
		alert(Trans.late('invalidname'));
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

	if (form.title.value == '') {
	    alert(Trans.late('notitle'));
	    form.title.focus();
	    return false;
	}

	if (form.year.value == '') {
	    alert(Trans.late('noyear'));
	    form.year.focus();
	    return false;
	}

	if (!IsNumeric(form.year.value)) {
		alert(Trans.late('invalid'));
	    form.year.focus();
	    return false;
	}


	var mtyp = form.mediatype.options[form.mediatype.selectedIndex].value;
	var mcat = form.category.options[form.category.selectedIndex].value;
	var mcds = form.cds.options[form.cds.selectedIndex].value;

	if (mtyp == '') {
		alert(Trans.late('nomediatype'));
		return false;
	}

	if (mcat == '') {
		alert(Trans.late('nocategory'));
		return false;
	}

	if (mcds == '') {
		alert(Trans.late('nocdcount'));
		return false;
	}

	return true;
}




function val_IMDB(form) {

	if (form.title.value == '') {
	    alert(Trans.late('notitle'));
	    form.title.focus();
	    return false;
	}

	if (form.year.value == '') {
	    alert(Trans.late('noyear'));
	    form.year.focus();
	    return false;
	}

	if (!IsNumeric(form.year.value)) {
		alert(Trans.late('invalid'));
	    form.year.focus();
	    return false;
	}


	var mtyp = form.mediatype.options[form.mediatype.selectedIndex].value;
	var mcat = form.category.options[form.category.selectedIndex].value;
	var mcds = form.cds.options[form.cds.selectedIndex].value;

	if (mtyp == '') {
		alert(Trans.late('nomediatype'));
		return false;
	}

	if (mcat == '') {
		alert(Trans.late('nocategory'));
		return false;
	}

	if (mcds == '') {
		alert(Trans.late('nocdcount'));
		return false;
	}

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
	url = getBase()+'?page=cd&vcd_id='+val;
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
	var page = getBase()+'?page=jump&pornstar='+pornstarname+'&web='+web;
	window.open(page,'starsearch');
}

function addActors(id) {
	var url = getBase()+'?page=addpornstars&vcd_id='+id;
	window.open(url, 'addactors', 'height=300,width=420,top=200,left=250');
}

function addScreenshots(id) {
	var url = getBase()+'?page=addscreens&vcd_id='+id;
	window.open(url, 'addscreens', 'height=300,width=420,top=200,left=250');
}

function changePornstar(pornstar_id) {
	var url = getBase()+'?page=pornstarmanager&pornstar_id='+pornstar_id;
	window.open(url, 'PornStarManager', 'toolbar=0,location=0,directories=no,status=no,menubar=no,scrollbars=no,resizable=no,width=365,height=270');
}

function fetchstarimage(star_id) {
	var promptext = Trans.late('url');
	linktext = prompt(promptext,'');
	if (linktext != null) {
		url = getBase()+'?page=pornstarmanager&action=fetchimage&pornstar_id='+star_id+'&path='+linktext;
		location.href = url;
	}
}

function deletePornstarImage(pornstar_id) {
	if (confirm(Trans.late('delete'))) {
		url = getBase()+'?page=pornstarmanager&action=deleteimage&pornstar_id='+pornstar_id;
		location.href = url;
	}
}

function removeActor(pornstar_id, movie_id) {
	if (confirm(Trans.late('removeactor'))) {
		url = getBase()+'?page=manager&vcd_id='+movie_id+'&action=removeactor&actor_id='+pornstar_id;
		location.href = url;
	}
}

function addFeed(type) {
	if (type == 'vcddb') {
		var url = getBase()+'?page=addrss&type=vcddb';
		window.open(url, 'Rss', 'toolbar=0,location=0,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,height=250,width=350,top=200,left=250');	
	} else if (type == 'site') {
		var url = getBase()+'?page=addrss&type=site';
		window.open(url, 'Rss', 'toolbar=0,location=0,directories=no,status=no,menubar=no,scrollbars=no,resizable=no,height=80,width=300,top=200,left=250');
	} else {
		return false;
	}
}

function rssCheck(form) {
	var count=0;
	for (i=0; i<form.elements.length ;i++)
	if (form.elements[i].type=='checkbox' && form.elements[i].checked) {
	 	count+=1
	}
	if (count == 0) {
		alert(Trans.late('norss'));
		return false;
	}
	return true;
}

function deleteFeed(id) {
	if (confirm(Trans.late('delete'))) {
		url = getBase()+'?page=settings&action=delrss&rss_id='+id;
		location.href = url;
	}
}

function deleteMetaType(id) {
	var msg = Trans.late('deletemeta');
	if (confirm(msg)) {
		url = getBase()+'?page=settings&action=delmetatype&meta_id='+id;
		location.href=url;
	}
}

function deleteComment(item_id,id) {
	url = getBase()+'?page=cd&vcd_id='+item_id+'&action=delComment&cid='+id;
	location.href = url;
}

function addtowishlist(id) {
	var url = getBase()+'?page=cd&vcd_id='+id+'&action=addtowishlist';
	location.href = url;
}

function deleteFromWishlist(id) {
	var url = getBase()+'?page=wishlist&action=delete&vcd_id='+id;
	location.href = url;
}

function deleteCover(cover_id, vcd_id) {
	var url = getBase()+'?page=manager&vcd_id='+vcd_id+'&action=deletecover&cover_id='+cover_id;
	location.href = url;
}

function showUserStatus() {
	url = getBase()+'?page=overview';
	window.open(url, 'overview', 'scrollbars=yes,resizable=yes,height=600,width=830,top=50,left=25');
}

function showUserStatusDetailed() {
	url = getBase()+'pages/user_status_detail.php';
	window.open(url, 'popup2', 'scrollbars=yes,resizable=yes,height=800,width=830,top=50,left=25');
}

function showAllMoviesDetailed() {
	url = getBase()+'pages/all_movies_details.php';
	window.open(url, 'popup3', 'scrollbars=yes,resizable=yes,height=800,width=830,top=50,left=25');
}

function checkupload(formvalue) {
	if (formvalue == '') {
		alert(Trans.late('noupload'));
		return false;
	} else {
		return true;
	}
}

function clearXML() {
	var url = getBase()+'?page=add&source=xml&action=cleanup';
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
		alert(Trans.late('noxmlthumbs'));
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
		alert(Trans.late('nocriteria'));
		return false;
	}

	return true;
}

function checkManually(form) {
	var cds = form.cds.options[form.cds.selectedIndex].value;
	var mediatype = form.mediatype.options[form.mediatype.selectedIndex].value;
	var category = form.category.options[form.category.selectedIndex].value;

	if (form.title.value == '') {
		alert(Trans.late('notitle'));
		form.title.focus();
		return false;
	}

	if (mediatype == '') {
		alert(Trans.late('nomediatype'));
		return false;
	}

	if (category == '') {
		alert(Trans.late('nocategory'));
		return false;
	}

	if (cds == '') {
		alert(Trans.late('nocdcount'));
		return false;
	}

	return true;
}

function deleteCopy(usercopies, totalcopies, id, media_id) {
	if (totalcopies == 1) {
		var message = Trans.late('lastcopy');
		if (confirm(message)) {
			url = getBase()+'?page=manager&action=deletecopy&vcd_id='+id+'&media_id='+media_id+'&mode=full';
			location.href = url;
		}
	} else {
		var message = Trans.late('delete');
		if (confirm(message)) {
			url = getBase()+'?page=manager&action=deletecopy&vcd_id='+id+'&media_id='+media_id+'&mode=single';
			location.href = url;
		}
	}
}

function checkListed(form) {
	checkFieldsRaw(form,'choiceBox', 'id_list');
	if (form.id_list.value == '') {
		alert(Trans.late('noselection'));
		return false;
	} else {
		return true;
	}
}

function confirmListed(form) {
    for (i=0; i<form.elements.length ;i++)  {
	if (form.elements[i].type=='select-one') {
    	val = form.elements[i].options[form.elements[i].selectedIndex].value;
        if (val == '') {
        	alert(Trans.late('nomediaselection'));
            return false;
         }
      }
 	}
 	return true;
}

function printView(type) {
	url = getBase()+'?page=printview&mode=' + type;
	window.open(url, 'printview', 'scrollbars=yes, menubar=yes, resizable=yes,height=600,width=830,top=50,left=25');
}

function markSeen(movie_id, flag) {
	url = getBase()+'?page=cd&vcd_id='+movie_id+'&action=seenlist&flag='+flag;
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
	url = getBase()+'?page=category&category_id='+cat_id+'&action=onlymine';
	location.href = url; 	 
}

function checkReg(form) {
	if (form.name.value == "") {
		alert(Trans.late('reqname'));
		form.name.focus();
		return false;
	}

	if (form.username.value == "") {
		alert(Trans.late('requsername'));
		form.username.focus();
		return false;
	}

	if (form.username.value.length < 3) {
		alert(Trans.late('requsername3'));
		form.username.focus();
		return false;
	}

	if (!emailCheck(form.email.value)) {
		form.email.focus();
		return false;
	}

	if (form.password.value.length < 5) {
		alert(Trans.late('reqpassword'));
		form.password.focus();
		return false;
	}

	if (form.password.value != form.password2.value) {
		alert(Trans.late('reqpassnomatch'));
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

function filebrowse(param, destField) {
	try {
		if (destField == null) {
			var url = getBase()+'?page=playerbrowse&from='+param;
		} else {
			var url = getBase()+'?page=playerbrowse&from='+param+'&field='+destField;
		}
		window.open(url, 'FileBrowser', 'height=60,width=380,top=200,left=250');
	} catch (Ex) {}
}

function addFileLocation(img,rowIndex,cellIndex,rowCount,mediaId,metaTypeId) {
	var tbl = $('tblmetadata')
	
	var rowIdx = (((rowCount+1)*rowIndex)+cellIndex)+1;
	var tr = tbl.insertRow(rowIdx);
	var newTd1 = tr.insertCell(-1);
	var newTd2 = tr.insertCell(-1);
	
	newTd1.setAttribute('nowrap','nowrap');
	var newInput = document.createElement('input');
	
	newInput.setAttribute('type','text');
	newInput.setAttribute('class','input');
	newInput.setAttribute('size','40');
	var inputId = 'meta_:filelocation:'+metaTypeId+':'+mediaId;
	newInput.setAttribute('id',inputId);
	newInput.setAttribute('name',inputId);
	
	var jsImg = document.createElement('img');
	jsImg.setAttribute('src', replace(img.src,'add','folder_go'));
	jsImg.setAttribute('border','0');
	jsImg.setAttribute('hspace','4');
	jsImg.setAttribute('title','Browse for file')
	jsImg.style.verticalAlign = 'middle';
	jsImg.onclick = function() {filebrowse('file', inputId);}
	
	newTd1.style.padding = '0px 0px 0px 15px';
	newTd1.appendChild(document.createTextNode('filelocation'))
	newTd2.appendChild(newInput);
	newTd2.appendChild(jsImg);
}

function getFileName(form, fieldname) {
	try {
		filename = form.filename.value;
		var obj = opener.document.getElementById(fieldname);
		obj.value = filename;
		self.close();
		return false;
  } catch (ex) {}
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

function switchTemplate(template) {
	url = getBase()+'?page=settings&action=templates&template='+template;
	location.href = url;
}

function managerSubmit(form, action) {
	try {
		checkFieldsRaw(form,'audioChoices','audio_list');
		checkFieldsRaw(form,'langChoices','sub_list');
		checkFieldsRaw(form,'spokenChoices','spoken_list');
	} catch (ex) {}
	try {
		checkFieldsRaw(form,'adultCategoriesUsed', 'id_list');		
	} catch (ex) {}
}

function setManagerMediaType(obj, movieid) {
	var id = obj.options[obj.selectedIndex].value;
	var url = getBase()+'?page=manager&vcd_id='+movieid+'&dvd='+id;
	location.href = url;
}

function showDVD(id) {
	try {
		var obj = $(id);
		if (obj != null) {
			return obj.innerHTML;
		} else {
			return '';
		}
	} catch (ex) {
		alert(ex.Message);
	}
}

function deleteMeta(metadata_id, itemId) {
	try {
		if (metadata_id > 0) {
			url = getBase()+'?page=manager&vcd_id='+itemId+'&action=deletemeta&meta_id='+metadata_id;
			location.href = url;
		}
	} catch (ex) {}
}

/* Ajax based form functions */

var currCountryName;
var currCountryKey;

function updateFlags(response, fieldId, hiddenFieldId) {
	obj = new Object(response);
  	var urlbase = replace(jxBase,'index.php','');
  	var img = new Image();
  	img.src = urlbase+obj;

  	var html = '<ul class="flags">';
  	var htmlfield = $(hiddenFieldId);
  	if (htmlfield.value.length==0) {
  		htmlfield.value += currCountryKey;
  	} else {
  		htmlfield.value += '#'+currCountryKey;
  	}

  	var subtitles = $(fieldId);
    var lis = subtitles.getElementsByTagName('LI');
    var classNames = Array();
    for (i=0;i<Math.ceil(lis.length/3);i++) {
    	classNames.push('x','y','z');
    }
    
    var j = 0;
	for (i=0; i < lis.length; i++) {
		lid = lis[i].id;
		if (lid != currCountryKey && lis[i].className!='clr') {
			html += '<li id='+lid+' class='+classNames[j]+'>' + lis[i].innerHTML + '</li>';
			if (classNames[j]=='z' || i==lis.length) {
				html += '<li class="clr"><br class="clr"/></li>';
			}
			j++;
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

	var type = 0;
	if (fieldId=='subtitles') {
		type = 1;
	} else if (fieldId=='langspoken') {
		type = 2;
	}
	
	html += "<li id="+currCountryKey+"><img src='"+img.src+"' vspace='2' hspace='2' height='12' border='0' ondblclick=\"removeFlag('"+currCountryKey+"',"+type+")\" title=\""+currCountryName+"\" align='absmiddle'>"+lang+"</li>";
	html += "</ul>";

  	subtitles.innerHTML = html;
}

function updateSubtitles(response)   {
	updateFlags(response,'subtitles','dvdsubs');
}

function updateLanguages(response) {
	updateFlags(response,'langspoken','dvdlang');
}

function removeFlag(key,type) {
	var div;
	var htmlfield;
	if (type == 1) {
		div = $('subtitles');
		htmlfield = $('dvdsubs');
	} else if(type==2) {
		div = $('langspoken');
		htmlfield = $('dvdlang');
	} 
		
	htmlfield.value = '';

    var lis = div.getElementsByTagName('LI');
    var lid = '';
    var classNames = Array();
    for (i=0;i<Math.ceil(lis.length/3);i++) {classNames.push('x','y','z');}
    var j = 0;
    var html = '<ul class="flags">';
	for (i=0; i < lis.length; i++) {
		lid = lis[i].id;
		if (lid != key && lis[i].className!='clr') {
			html += '<li id='+lid+' class='+classNames[j]+'>' + lis[i].innerHTML + '</li>';
			if (classNames[j]=='z' || i==lis.length) {
				html += '<li class="clr"><br class="clr"/></li>';
			}
			if (htmlfield.value == '') {
				htmlfield.value = lid ;
			} else {
				htmlfield.value += '#' + lid ;
			}
			j++;
		}
	}
	html += "</ul>";
	div.innerHTML = html;
}

function addFlag(form,source,callerId) {
	var objList = $(source);
	var selectedItem = objList.selectedIndex;
	if (selectedItem < 0) { return; }
	var selectedText = objList.options[selectedItem].text;
	var selectedValue = objList.options[selectedItem].value;

	currCountryName = selectedText;
	currCountryKey = selectedValue;

	obj = new vcddbAjax('getCountryFlag');
	if (callerId == 'subs') {
		obj.invoke(selectedValue,updateSubtitles);
	} else if (callerId == 'langs') {
		obj.invoke(selectedValue,updateLanguages);
	}
}

function addAudio(form, source) {
	var objList = $(source);
	var selectedItem = objList.selectedIndex;
	if (selectedItem < 0) { return; }
	var selectedText = objList.options[selectedItem].text;
	var selectedValue = objList.options[selectedItem].value;

	var audio = $('audio');
  	var htmlfield = $('dvdaudio');
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
	var audios = $('audio');
	var htmlfield = $('dvdaudio');
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
	button = $('confirmButton');
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
	div = $('rss'+id);
	div.innerHTML = '';
	div.appendChild(img);
		
	obj = new vcddbAjax('getRss');
	obj.invoke(id,renderRss);
}

function renderRss(response) {
	obj = new Object(response);
	items = obj.items;
	ul = $('rss'+obj.id);
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
		try { title = $('mTitle').innerHTML;} 
			catch (ex) { try { title = $('m'+id).innerHTML;} catch(ex) {}
		}
		try {
			oldDiv = document.getElementById('dSlider');
			oldDiv.innerHTML = '';
		} catch (ex) {}
		
		base = getBase()+'upload/screenshots/albums/'+id+'/';
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
		
		var el = $('startslide');
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
	
		show('suggestion',true);
		hide('noresults');
		
		img = new Image();
		img.src = getBase()+'images/processing.gif'; 
		img.setAttribute('border',0);
		img.setAttribute('title','Loading ...');
		img.setAttribute('vspace',60);
		div = $('cover');
		div.innerHTML = '';
		div.appendChild(img);
		
		obj = new vcddbAjax('getRandomMovie');
		obj.invoke(category,seen,doShowSuggestion);

	} catch (Exception) {}
}

function doShowSuggestion(response) {
	try {
		if (response == null) {
			show('noresults',true);
			hide('suggestion');
			return;
		}
		
		obj = new Object(response);
		if (obj.title != 'undefined') {
			dCover = $('cover');
			dTitle = $('title');
			dCategory = $('cat');
			dYear = $('year');	
			dLink = $('link');
			
			dTitle.innerHTML = obj.title;
			dCategory.innerHTML = obj.category;
			dYear.innerHTML = obj.year;
			dLink.href = getBase()+'?page=cd&vcd_id='+obj.id;
			
			dCover.innerHTML = '';
			var cover = new Image();
			cover.src = getBase()+'?page=file&cover_id='+obj.cover_id;
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
	return TagToTip(layerid,SHADOWWIDTH,1,ABOVE,true,LEFT,false,FADEIN,150,FADEOUT,150,
		WIDTH,280,BGCOLOR, '#ffffff',BORDERCOLOR,'#cfcfcf',SHADOW,true,SHADOWWIDTH,1,PADDING,0);
}

function $() {
	var elements = new Array();
	for (var i = 0; i < arguments.length; i++) {
		var element = arguments[i];
		if (typeof element == 'string')
			element = document.getElementById(element);
		if (arguments.length == 1)
			return element;
		elements.push(element);
	}
	return elements;
}

function getBase() {
	return replace(jxBase,'index.php','');
}