/*
	VCD admin site JS functions
*/


function toggle(theDiv) {
    
	 try {
	 	var elem = document.getElementById(theDiv);
     	elem.style.display = (elem.style.display == "none")?"":"none";
	 } catch (Exception) {
	 	alert('Option not available');
	 }
	
	 
}


function mailtest() {
	if (confirm('This will test your mailserver\nbased on current settings.\nTest mail will be sent to your email\nand status will be written to the page.\n\nPress ok to proceed')) {
		url = 'mailtest.php';
		window.open(url, 'MailTest', 'toolbar=0,location=0,directories=no,status=yes,menubar=no,scrollbars=yes,resizable=no,width=600,height=400');			
	}
	
}

function showLayer() {
	toggle('newObj');
}


function SaveModeEdit(file_id) {
	uri = "./?page=languages&mode=edit&recordID="+file_id+"&type=safe";
	location.href = uri;
}

function setBorder(obj) {
	
	id = obj.name;
	
	document.getElementById(id).style.borderColor = "#0C2862";
	document.getElementById(id).style.borderWidth = "1px";
	document.getElementById(id).style.borderStyle = "solid";
	document.getElementById(id).style.padding = "1px";
	document.getElementById(id).style.margin = "1px";
	
}

function clearBorder(obj) {
	id = obj.name;
	
	document.getElementById(id).style.borderColor = "#dadce0";
	document.getElementById(id).style.borderWidth = "1px";
	document.getElementById(id).style.borderStyle = "solid";
	document.getElementById(id).style.padding = "1px";
	document.getElementById(id).style.margin = "1px";
	
}

function trOn(obj) {
	obj.style.background = "#F6F6F6";
}

function trOff(obj) {
	obj.style.background = "#FFFFFF";
}

function deleteRecord(recordID, action, message) {
	if (confirm(message)) {
		url = "./?page=deleteRecord&recordType="+action+"&recordID="+recordID;	
		location.href=url;
	}
}

function deleteUser(recordID, action) {
	
	var message = "Delete user ?";
	
	if (confirm(message)) {
		
		var choose = "Press OK to delete all information regarding user\n(including movies, covers and comments)\n";
		choose += "or press cancel to only disable user account and keep user data."
		if (confirm(choose)) {
			url = "./?page=deleteRecord&recordType="+action+"&recordID="+recordID+"&mode=full";	
		} else {
			url = "./?page=deleteRecord&recordType="+action+"&recordID="+recordID;	
		}
		location.href=url;
	}
}

function editRecord(recordID, action) {
	url = "./?page="+action+"&mode=edit&recordID="+recordID;
	location.href=url;
}

function changePassword(recordID, action) {
	url = 'opener.php?mode='+action+'&recordID='+recordID+'';
	window.open(url, 'Changer', 'toolbar=0,location=0,directories=no,status=no,menubar=no,scrollbars=no,resizable=no,width=280,height=120');		
}

function changeRole(recordID, action) {
	url = 'opener.php?mode='+action+'&recordID='+recordID+'';
	window.open(url, 'Changer', 'toolbar=0,location=0,directories=no,status=no,menubar=no,scrollbars=no,resizable=no,width=240,height=10');	
}

function exportUser(recordID, action) {
	msg = "Export selected users movies as XML ?";
	if (confirm(msg)) {
		url = "./?page=exportUserXML&recordID="+recordID;	
		location.href = url;
	}
}

function setDefaultRole(recordID, action) {
	url = "./?page="+action+"&recordID="+recordID;
	location.href=url;
}

function checkPasses(pass1, pass2) {
	
	if (pass1.length < 5) {
		alert('Passwords need to be at least 5 characters!');
		return false;
	}
	
	if (pass1 != pass2) {
		alert('Passwords do not match!');
		return false;
	} 
	return true;
}


function viewFeed(id, action) {
	location.href = "./?page=xmlfeeds&view="+id;
}





function moveOver(form)  {
	var boxLength = form.choiceBox.length;
	var selectedItem = form.available.selectedIndex;
	
	if (selectedItem < 0) {
		return;
	}
	
	var selectedText = form.available.options[selectedItem].text;
	var selectedValue = form.available.options[selectedItem].value;
	var i;
	var isNew = true;
	if (boxLength != 0) {
	for (i = 0; i < boxLength; i++) {
		thisitem = form.choiceBox.options[i].text;
		if (thisitem == selectedText) {
		isNew = false;
		break;
      }
   }
} 
if (isNew) {
	newoption = new Option(selectedText, selectedValue, false, false);
	form.choiceBox.options[boxLength] = newoption;
	}
	
	/* Konni added for DKM */
	form.available.options[form.available.selectedIndex] = null;
	form.available.selectedIndex=-1;
}


function moveBack(form, selected_id) {
	/*
		available
		choiceBox
	*/

	var boxLength = form.available.length;
	var selectedItem = form.choiceBox.selectedIndex;
	
	if (selectedItem < 0) {
		return;
	}
	//alert(selected_id);
	
	var selectedText = form.choiceBox.options[selectedItem].text;
	var selectedValue = form.choiceBox.options[selectedItem].value;
	var i;
	var isNew = true;
	if (boxLength != 0) {
	for (i = 0; i < boxLength; i++) {
		thisitem = form.available.options[i].text;
		if (thisitem == selectedText) {
		isNew = false;
		break;
      }
   }
} 
if (isNew) {
	newoption = new Option(selectedText, selectedValue, false, false);
	form.available.options[boxLength] = newoption;
	}
	
	/* Konni added for DKM */
	form.choiceBox.selectedIndex=-1;

}


function removeMe(form) {
	var boxLength = form.choiceBox.length;
	arrSelected = new Array();
	var count = 0;
	var selected_index;
	
	for (i = 0; i < boxLength; i++) {
		if (form.choiceBox.options[i].selected) {
		arrSelected[count] = form.choiceBox.options[i].value;
		selected_index = form.choiceBox.options[i].value;
		}
	count++;
	}

moveBack(form,selected_index);

var x;
for (i = 0; i < boxLength; i++) {
	for (x = 0; x < arrSelected.length; x++) {
		if (form.choiceBox.options[i].value == arrSelected[x]) {
		form.choiceBox.options[i] = null;
		
	   }
	}
	boxLength = form.choiceBox.length;
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



function checkFields(form) {
var delimiter = "#";	// Hvernig viltu splitta strengnum ??
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
}