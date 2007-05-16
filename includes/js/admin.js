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


function checkDeletion(form) {
	if (form.owner.selectedIndex == 0) {
		alert('You must select the owner');
		form.owner.focus();
		return false;
	}
	return true;
}

function SaveModeEdit(file_id) {
	uri = "./?page=languages&mode=edit&recordID="+file_id+"&type=safe";
	location.href = uri;
}

function setBorder(obj) {
	
	obj.style.borderColor = "#0C2862";
	obj.style.borderWidth = "1px";
	obj.style.borderStyle = "solid";
	obj.style.padding = "1px";
	obj.style.margin = "1px";
	
}

function clearBorder(obj) {
	
	obj.style.borderColor = "#dadce0";
	obj.style.borderWidth = "1px";
	obj.style.borderStyle = "solid";
	obj.style.padding = "1px";
	obj.style.margin = "1px";
	
}

function setLogFilter() {
	var filterObj = document.getElementById('filter');
	var filter_id = filterObj.options[filterObj.selectedIndex].value;
	var url = "./?page=viewlog";
	if (filter_id > 0) {
		url += "&filter_id="+filter_id;
	}
	location.href = url;
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
}


function runTask(task_id) {
	if (confirm('Execute task?')) {
		url = "./?page=executeTask&task_id="+task_id;
		location.href=url;	
	}
}

function Updater() {

	this.totalTransactions = 0;
	this.transactionCount = 0;
	this.transactionCounter = 0;
	this.aIndex = Array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
	//this.aIndex = Array('A');
	this.currentIndex = 0;
	this.transactionsCompleted = true;
	this.proxy = x_PornstarProxy;
	
	this.startUpdate = function() {
		
		document.getElementById('btnStartCall').disabled = true;
		this.addLogEntry('Action', 'Message', 'header');
		
		this.addLogEntry('Notice', 'Asking for endpoint ...');
		this.proxy.doHandshake(this.handleServiceDiscovery);
		
		
	};
	
	this.handleServiceDiscovery = function(response) {
		
		Updater.addLogEntry('Notice', response);
		Updater.getListByLetter();
		
	};
	
	
	this.getListByLetter = function(retryCount) {
		try {
			
			if (this.currentIndex == this.aIndex.length) {
				this.endTransactions();
				return;
			}
			
			if (this.transactionsCompleted) {
				this.transactionCount = 0;
				this.transactionCounter = 0;
				this.addLogEntry('Call to Server', 'Getting list for pornstars starting with letter '+this.aIndex[this.currentIndex]);
				this.transactionsCompleted = false;
				this.totalTransactions++;
				this.proxy.getUpdateList(this.aIndex[this.currentIndex], this.handeListResponse);
				
			} else {
				retryCount++;
				this.addLogEntry('Waiting', 'Waiting for transactions to comeplete');
				setTimeout('Updater.getListByLetter('+retryCount+')', 2000);
			}
			
			
		} catch (Exception) {}
	};
	

	this.endTransactions = function() {
		this.addLogEntry('Notice', 'Update finished, total '+this.totalTransactions+' transactions');
	};
	
	this.runTransaction = function(response) {
		
		var totalEntries = response.entries;
		this.transactionCount = totalEntries;
		var Incoming = response.incoming;
		var Outgoing = response.outgoing;
		var ServerUpdate = response.supdate;
		var ClientUpdate = response.cupdate;
		var ClientServerUpdate = response.csupdate;
		
		this.addLogEntry('Notice', 'Incoming calls needed for letter '+response.letter+': ' + Incoming);
		this.addLogEntry('Notice', 'Outgoing calls needed for letter '+response.letter+': ' + Outgoing);
		this.addLogEntry('Notice', 'Sync calls needed for letter '+response.letter+': ' + ClientServerUpdate);
		this.addLogEntry('Notice', 'Server updates needed for letter '+response.letter+': ' + ServerUpdate);
		this.addLogEntry('Notice', 'Client updates needed for letter '+response.letter+': ' + ClientUpdate);
		
		for (var i=0;i<this.transactionCount;i++) {
			this.totalTransactions++;
			this.proxy.getUpdates(i, this.handleTransactionResponse);
		}
		
				
	};
	
	this.handleTransactionResponse = function(response) {
				
		try {
			Updater.addLogEntry(response.action + ' ' + '['+(Updater.transactionCounter+1)+']',response.message);	
		} catch (Exception) {
			Updater.addLogEntry('Bad response',response);
		}
		
		
		
		Updater.transactionCounter++;
		
		
		// Trigger Updates for next letter
		if (Updater.transactionCounter == Updater.transactionCount) {
			Updater.transactionsCompleted = true;
			Updater.getListByLetter();
		}
	}
	
	
	this.handeListResponse = function(response) {
		try {
		
			var entryCount = response.entries;
			
			Updater.addLogEntry('Response from Server', 'Total transactions required: ' + entryCount);

			// If no updates are required
			if (entryCount == 0) {
				Updater.transactionsCompleted = true;
				Updater.currentIndex++;
				Updater.getListByLetter();
				return;
			}
			
					
								
			if (Updater.currentIndex < (Updater.aIndex.length)) {
				if (entryCount == 0) {
					Updater.getListByLetter();
				} else  {
					Updater.runTransaction(response);
				}
			} 
			
			Updater.currentIndex++;
		
			
		} catch (Exception) {}
	};
	
	
	this.addLogEntry = function(action, message, className) {
	
		var table = document.getElementById('tblupdater');
		var tbody = table.getElementsByTagName("tbody")[0];
		
		if (table.rows.length == 0) {
			var row = table.insertRow(0);
		} else {
			var row = table.insertRow(1);
		}
		
		
		var cell_action  = document.createElement("TD");
		var cell_message = document.createElement("TD");
		
		cell_action.innerHTML = action;
		cell_message.innerHTML = message;
						
		if (className != 'undefined') {
			cell_action.className = className;
			cell_message.className = className;
			cell_action.width = '20%';
			cell_message.width = '80%';
		}
		
		row.appendChild(cell_action);
		row.appendChild(cell_message);
	};

}