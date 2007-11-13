/**
 * VCD-db - a web based VCD/DVD Catalog system
 * Copyright (C) 2003-2006 Konni - konni.com
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or (at
 * your option) any later version.
 * 
 * @author  Hákon Birgisson <konni@konni.com>
 * @package Installer
 * @version $Id$
 */


function Installer()  {

	this.step = 0;

	this.dbtype = '';
	this.dbhost = '';
	this.dbusername = '';
	this.dbpassword = '';
	this.dbname = '';
	
	this.supportedDBMSKeys = Array('mysql', 'postgres', 'mssql', 'db2', 'sqlite','oci8');
	this.supportedDBMSValues = Array('MySQL', 'Postgre SQL', 'Microsoft SQL', 'IBM DB2', 'SQLite', 'Oracle');
	this.availableDBMS = Array();
	this.checklist = Array('phpversion', 'gd', 'simplexml', 'mbstring', 'session', 'urlfopen', 'fileupload', 'permissions', 'config', 'database');
	this.checklistErrorMessages = Array(
			'VCD-db needs at least PHP version 5.0 or later, please upgrade your PHP binaries.<br/><br/>',
			'GD libraries need to be enabled for image resizing and graph displays<br/>More details about the GD libs are available at <a href="http://php.net/gd" target="_new">php.net/gd</a><br/><br/>',
			'By default SimpleXML is loaded, without SimpleXML localization, import/export would not work.  More details about SimpleXML are available at <a href="http://php.net/simplexml" target="_new">php.net/simplexml</a><br/><br/>',
			'Multibyte String support is needed for UTF-8.  Enable the mbstring module with your php setup.  More details about mbstring are available at <a href="http://php.net/mbstring" target="_new">php.net/mbstring</a><br/><br/>',
			'Session support should always be enabled with every PHP installation, if not enabled check out <a href="http://php.net/session" target="_new">php.net/session</a> for further details.<br/><br/>',
			'The php.ini setting "allow_url_fopen" must be set to 1, without allow_url_fopen enabled VCD-db cannot fetch data from remote sites!<br/><br/>',
			'File upload needs to be enabled to import covers, thumbnails and XML processings.  For details to enable file uploads read <a href="http://php.net/manual/en/ini.php" target="_new">php.net/manual/en/ini.php</a><br/><br/>',
			'Correct folder permissions are crucial for VCD-db to work with fetched content, caching and other things, please chmod your folders correctly.<br/><br/>',
			'You need to manually create the file "config.php" in your VCD-db root directory.  The file needs to be writeable for the installer.  Please chmod the file correctly (If you are on Unix based system, you can run <i>"chmod 666 config.php"</i> to make it writeable).<br/><br/>',
			'Without any database module loaded on the web-server VCD-db cannot function.  Please enable some of the optional database modules so you can run VCD-db.<br/><br/>'
			
		);
	this.checkcount = 0;
	this.callcount = 0;
	this.failedIndex = Array();
	this.checkCompleted = false;
	this.tablesCreated = false;
	this.dataPopulated = false;
	this.configSaved = false;
	this.adminSaved = false;
	this.processbar = null;
	
	this.Continue = function() {
		
		objButton = document.getElementById('BtnContinue');
		objButton.disabled = true;
		
		if (this.handleStep(this.step)) {
			
			objButton.disabled = false;
			this.nextMenu(this.step);	
			this.step++	
			
			// Should installer click for user ?
			this.stepForward(this.step);
		} 
	};


	// Called to excute the page function upon load
	this.stepForward = function(iStep) {
		if (iStep == 1 || iStep == 3 || iStep == 4) {
			this.Continue();
		}
		
				
	};
	
	this.handleStep = function(iStep) {
		
		switch(iStep) {
			
			case 0: 
				return true;
				break;
			case 1:
				// System check
				if (this.checkCompleted) {return true;}
			
				this.toggleCursor();
				
				// Check if this is a retry ..
				if (this.failedIndex.length > 0) {
					document.getElementById('checkerrors').innerHTML = '';
					this.hideLayer('checkerrors');
					
					for (j=0;j<this.failedIndex.length;j++) {
						statusId = 't'+this.failedIndex[j];
						resultId = 'r'+this.failedIndex[j];
						statusCell = document.getElementById(statusId);
						resultCell = document.getElementById(resultId);
						statusCell.innerHTML = '[pending]';
						resultCell.innerHTML = '';
					}
					// Reset the failed array
					this.failedIndex = Array();
				}
				
				for (i=0;i<this.checklist.length;i++) {
					setTimeout('Installer.doCheck('+i+')', 1200*i);
				}
				setTimeout('Installer.evalCheckresults(0)', 1000);
				return false;
				break;
				
			case 2:
				// After Successful database connection entry
				return true;
				break;
				
				
			case 3:
				if (this.tablesCreated) {return true;}
			
				// Create database tables
				this.hideLayer('tableerror1');
				this.hideLayer('tableerror2');
				document.getElementById('tableservererror').innerHTML = '';
				this.hideLayer('tableservererror');
				this.toggleCursor();
				dbSettings = Array(this.dbhost, this.dbusername, this.dbpassword, this.dbname, this.dbtype);
				x_Installer.executeCheck('createtables', dbSettings, this.handleCheckResults);
							
				return false;
				break;
				
			case 4:
				// Populate VCD-db default data
				if (this.dataPopulated) {return true;}
								
				this.toggleCursor();
				this.hideLayer('populateError');
				document.getElementById('populateservererror').innerHTML = '';
				dbSettings = Array(this.dbhost, this.dbusername, this.dbpassword, this.dbname, this.dbtype);
				
				// Start the progressbar call as well
				x_Installer.executeCheck('recordcount', dbSettings, this.handleCheckResults);
				
				x_Installer.executeCheck('populatedata', dbSettings, this.handleCheckResults);
				
				
				
				return false;
				break;
				
			case 5:
				objButton = document.getElementById('BtnContinue');
				objButton.value = 'Continue >>';	
			
				if (this.configSaved) {return true;}
				
				this.hideLayer('configservererror');
				document.getElementById('configservererror').innerHTML = '';
			
				//Collect configuration settings
				textFields = Array('s_title', 's_email', 's_smtphost', 's_smtpusername', 's_smtppassword', 
					's_smtprealm', 's_proxyhost', 's_proxyport', 's_ldaphost', 's_ldapdn', 's_ldapad');
				selectFields = Array('s_register', 's_covers', 's_category', 's_session', 's_adult');
				radioFields = Array('useproxy', 'useldap', 'ldapad');
				
				var fieldValues = new Object();
				// populate the data
				for (i=0;i<textFields.length;i++) {
					fieldValues[textFields[i]] = document.getElementById(textFields[i]).value;	
				}
				for (i=0;i<selectFields.length;i++) {
					fieldValues[selectFields[i]] = document.getElementById(selectFields[i]).options[document.getElementById(selectFields[i]).selectedIndex].value;
				}
				for (i=0;i<radioFields.length;i++) {
					var list = document.getElementsByName(radioFields[i]);
					for (j=0;j<list.length;j++) {
						if (list[j].checked) {
							fieldValues[radioFields[i]] = list[j].value;
							break;
						}
					}
				}			
				
				// Validate the config ..
				if (this.validateConfig(fieldValues)) {
					 dbSettings = Array(this.dbhost, this.dbusername, this.dbpassword, this.dbname, this.dbtype);
					 x_Installer.executeCheck('saveconfig', dbSettings, fieldValues, this.handleCheckResults);
					 return false;
					
				} else {
					
					objButton.value = 'Recheck >>';	
					objButton.disabled = false;
					this.toggleCursor();
					return false;
				}
				
				
				break;
				
			case 6:
				objButton = document.getElementById('BtnContinue');
				objButton.value = 'Continue >>';	
			
				// after config save attempt
				if (this.configSaved) {
					return true;
				} else {
					
					this.hideLayer('configsavesuccess');
					document.getElementById('configsaveerror').innerHTML = 'Could not save config file.<br/>Please check write permissions.';
					this.hideLayer('configsaveerror');
					objButton = document.getElementById('BtnContinue');
					objButton.value = 'Recheck >>';	
					objButton.disabled = false;
					this.toggleCursor();
					return false;
				}
				
				break;
				
			case 7:
				objButton = document.getElementById('BtnContinue');
				objButton.value = 'Continue >>';	
			
				// Create admin account
				if (this.adminSaved) {return true;}
				
				this.toggleCursor();
				textFields = Array('vcd_fullname', 'vcd_username', 'vcd_password', 'vcd_password2', 'vcd_email');
				var fieldValues = new Object();
				for (i=0;i<textFields.length;i++) {
					fieldValues[textFields[i]] = document.getElementById(textFields[i]).value;	
				}
				
				objButton = document.getElementById('BtnContinue');
				
				if (this.validateAdmin(fieldValues)) {
					// Delegate to Installer.php
					x_Installer.executeCheck('createadmin', dbSettings, fieldValues, this.handleCheckResults);
					return false;
				} else {
					this.toggleCursor();
					objButton.value = 'Recheck >>';	
					objButton.disabled = false;
					return false;
				}
				
				break;
				
			case 8:
				// After succsessfull install ...
				alert('Congratulations on successful install.\nNow taking you to your VCD-db website.');
				window.location.href='../?';
				break;
			
				
				
			default:
				alert('Error: handle action out of range');
				return false;
				break;
			
			
		}
		
	}
	
	this.validateAdmin = function(objConfig) {
		if (objConfig['vcd_fullname'] == '') {
			alert('Please type in your full name.');
			this.doFocus('vcd_fullname');
			return false;
		}
		if (objConfig['vcd_username'] == '') {
			alert('Please type in your username.');
			this.doFocus('vcd_username');
			return false;
		}
		
		if (objConfig['vcd_password'].length < 5) {
			alert('Your password needs to be at least 5 characters.');
			this.doFocus('vcd_password');
			return false;
		}
		
		if (objConfig['vcd_password'] != objConfig['vcd_password2']) {
			alert('Your passwords do not match.');
			this.doFocus('vcd_password2');
			return false;
		}
		
		if (objConfig['vcd_email'] == '') {
			alert('Please type in your email address.');
			this.doFocus('vcd_email');
			return false;
		}
		
		return true;
	};
		
	
	this.validateConfig = function(objConfig) {
		
		// Check proxy settings
		if (objConfig['useproxy'] == 1) {
			if (objConfig['s_proxyhost'] == '') {
				alert('Please specify the host name of the proxy server.');
				this.doFocus('s_proxyhost');
				return false;
			}
			if (objConfig['s_proxyport'] == '') {
				alert('Please specify the port of the proxy server.');
				this.doFocus('s_proxyport');
				return false;
			}
		}
		
		// Check the LDAP settings
		if (objConfig['useldap'] == 1) {
			if (objConfig['s_ldaphost'] == '') {
				alert('Please specify the host name of the LDAP server.');
				this.doFocus('s_ldaphost');
				return false;
			}
			
			if (objConfig['s_ldapdn'] == '') {
				alert('Please specify the Base DN on the LDAP server.');
				this.doFocus('s_ldapdn');
				return false;
			}
			
			
			if (objConfig['ldapad'] == 1 && objConfig['s_ldapad'] == '') {
				alert('Please specify the Active Directory domain name.');
				this.doFocus('s_ldapad');
				return false;
			}
		}
		
		return true;
		
		
		
	};
	
	this.evalCheckresults = function(retryCount) {
		if (this.callcount == this.checklist.length) {
			
			objButton = document.getElementById('BtnContinue');
			
			if (this.checkcount == this.checklist.length) {
				// All checks ok ..
				objButton.value = 'Continue >>';
				this.showLayer('checksuccess');
				this.checkCompleted = true;
				
			} else {
				// Some checks failed
				objButton.value = 'Recheck >>';	
				this.checkcount = 0;
				this.callcount = 0;
			}			
			
			objButton.disabled = false;
			this.toggleCursor();
			
			
		} else {
			retryCount++;
			setTimeout('Installer.evalCheckresults('+retryCount+')', 2000);
		}
	};
	
	this.setProgressBar = function(obj) {
		this.processbar = obj;
	};
	
	this.doCheck = function(iCheckNum) {
		var results = x_Installer.executeCheck(this.checklist[iCheckNum], this.handleCheckResults);
		
	};
	
	/* This function is out of scope when executed .. bare that in mind  */
	this.handleCheckResults = function(results) {
		var objResults = new Object(results);
		objButton = document.getElementById('BtnContinue');
		
		switch (objResults.check) {
			
			
			case 'createadmin':
				objButton.disabled = false;
				if (objResults.status == 1) {
					Installer.adminSaved = true;
					Installer.toggleCursor();
					Installer.Continue();
				} else {
					objButton.value = 'Recheck >>';
					Installer.toggleCursor();
					alert('Error: ' + objResults.results);
				}
			
			
				break;
			
			case 'saveconfig':
				objButton.disabled = false;
				if (objResults.status == 1) {
					Installer.configSaved = true;
					Installer.showLayer('configsavesuccess');
					Installer.Continue();
					
				} else {
					objButton.value = 'Recheck >>';
					Installer.toggleCursor();
					document.getElementById('configservererror').innerHTML = 'Error from server: ' + objResults.results;
					Installer.showLayer('configservererror');
				}				
			
				break;
				
			case 'recordcount':
				updateInterval = 3000;
				if (!Installer.dataPopulated && Installer.processbar != null && objResults.status == 1) {
					barvalue = roundNumber(objResults.results, 2);
				  	Installer.processbar.setBar(barvalue);
				  	setBarColor(Installer.processbar, barvalue);
				  	// Recall in the given interval ..
				  	dbSettings = Array(Installer.dbhost, Installer.dbusername, Installer.dbpassword, Installer.dbname, Installer.dbtype);
				  	x_Installer.executeCheck('recordcount', dbSettings, Installer.handleCheckResults);
				} else if (Installer.dataPopulated) {
					Installer.processbar.setBar(100);
				  	setBarColor(Installer.processbar, 100);
				}
		
			
				break;
				
			
			case 'populatedata':
				objButton.disabled = false;
				if (objResults.status == 1) {
					Installer.toggleCursor();	
					Installer.showLayer('populatesuccess');
					Installer.dataPopulated = true;
					
				} else {
					objButton.value = 'Recheck >>';
					Installer.toggleCursor();
					errorObj = document.getElementById('populateservererror');
					errorObj.innerHTML = 'Error from server: ' + objResults.results;
					Installer.showLayer('populateError');
					Installer.showLayer('populateservererror');
					
					
				}
			
				break;
			
			case 'createtables':
				Installer.toggleCursor();
				
				objButton.disabled = false;
				
				if (objResults.status == 1) {
					// Success
					Installer.showLayer('tablesuccess');
					objButton.value = 'Continue >>';
					Installer.tablesCreated = true;	
					
				} else if (objResults.status == 2) {
					// Created with errors
					document.getElementById('tableservererror').innerHTML = 'Error from server: ' + objResults.results;
					Installer.showLayer('tableerror1');
					Installer.showLayer('tableservererror');
					objButton.value = 'Recheck >>';
					
				} else {
					// Total failure
					errorMsg = document.getElementById('tableerror2').innerHTML;
					errorMsg = errorMsg.replace('[user]', Installer.dbusername);
					errorMsg = errorMsg.replace('[database]', Installer.dbname);
					
					document.getElementById('tableservererror').innerHTML = 'Error from server: ' + objResults.results;
					
					document.getElementById('tableerror2').innerHTML = errorMsg;
					Installer.showLayer('tableerror2');
					Installer.showLayer('tableservererror');
					objButton.value = 'Recheck >>';
				}
				
				
				break;
			
			case 'testConnection':
				if (objResults.status == 1) {
					Installer.showLayer('dbsuccess');
					Installer.toggleContinue();
				} else {
					str = 'Database connection failed with errormessage:<br/>';
					str += objResults.results;
					document.getElementById('dberror').innerHTML = str;
					Installer.showLayer('dberror');
				}
			
				
				Installer.toggleCursor();
				break;
			
			case Installer.checklist[0]:
				Installer.writeCheckResults(0,objResults);
				break;
				
			case Installer.checklist[1]:
				Installer.writeCheckResults(1,objResults);
				break;
				
			case Installer.checklist[2]:
				Installer.writeCheckResults(2,objResults);
				break;
				
			case Installer.checklist[3]:
				Installer.writeCheckResults(3,objResults);
				break;
				
			case Installer.checklist[4]:
				Installer.writeCheckResults(4,objResults);
				break;
				
			case Installer.checklist[5]:
				Installer.writeCheckResults(5,objResults);
				break;
				
			case Installer.checklist[6]:
				Installer.writeCheckResults(6,objResults);
				break;
				
			case Installer.checklist[7]:
				Installer.writeCheckResults(7,objResults);
				break;
				
			case Installer.checklist[8]:
				Installer.writeCheckResults(8,objResults);
				break;
				
			case Installer.checklist[9]:
				Installer.availableDBMS = objResults.keys;
				// Populate the available DBMS option list
				dblist = document.getElementById('dbtype');
				optionCount = 0;
				for (i=0; i<Installer.availableDBMS.length;i++) {
					if (Installer.availableDBMS[i] == 1) {
						dblist.options[optionCount] = new Option(Installer.supportedDBMSValues[i], Installer.supportedDBMSKeys[i]);
						optionCount++;
					}
					
					
				}
				
				Installer.writeCheckResults(9,objResults);
				break;
			
		}
		
		
	};
	
	this.writeCheckResults = function(iCheckNum, objResults) {
		statusId = 't'+iCheckNum;
		resultId = 'r'+iCheckNum;
		statusCell = document.getElementById(statusId);
		resultCell = document.getElementById(resultId);
		
		resultCell.innerHTML = objResults.results;
		
		if (objResults.status == 1) {
			statusCell.innerHTML = 'Success';
			statusCell.className = 'success';
			Installer.checkcount++;
		} else {
			Installer.failedIndex.push(iCheckNum);
			Installer.showLayer('checkerrors');
			document.getElementById('checkerrors').innerHTML += Installer.checklistErrorMessages[iCheckNum];
			statusCell.innerHTML = 'Failed';
			statusCell.className = 'failure';
		}
		
		Installer.callcount++;
		
	};
	
	
	this.showLayer = function(id) {
		document.getElementById(id).style.display = "block";
		document.getElementById(id).style.visibility = "visible";
	};
	
	this.hideLayer = function(id) {
		document.getElementById(id).style.display = "none";
		document.getElementById(id).style.visibility = "hidden";
	};
	
	this.showPage = function(iPage) {
		id = 'page'+iPage;
		document.getElementById(id).className = '';
		document.getElementById(id).style.display = "block";
		document.getElementById(id).style.visibility = "visible";
	};
	
	this.hidePage = function(iPage) {
		id = 'page'+iPage;
		document.getElementById(id).style.visibility = "hidden";
		document.getElementById(id).style.display = "none";
	};
	
	this.nextMenu = function(iPage) {
				
		var container = document.getElementById('menu');
		var lis = container.getElementsByTagName('LI');
		var topicObj = document.getElementById('message');
				
		if (iPage >= (lis.length-1)) {
			return;
		}
		
		if (iPage == 0) {
			lis[0].className = 'finished';
			lis[1].className = 'active';
			topicObj.innerHTML = lis[1].innerHTML;
			this.hidePage(iPage);
			this.showPage(iPage+1);
			return;
		}
		
				
		this.hidePage(iPage);
		this.showPage(iPage+1);
		
		
		lis[iPage].className = 'finished';
		topicObj.innerHTML = lis[iPage+1].innerHTML;
		lis[iPage+1].className = 'active';
		
		
		
		if (iPage == 1) {
			objButton = document.getElementById('BtnContinue');
			objButton.disabled = true;
			this.doFocus('dbhost');
		}
		
		if (iPage == 6) {
			this.doFocus('vcd_fullname');
		}
		
		
		
	}
	
	this.toggleContinue = function() {
		objButton = document.getElementById('BtnContinue');
		if (objButton.disabled) {
			objButton.disabled = false;
		} else {
			objButton.disabled = true;
		}
		
	};
	
	this.toggleCursor = function() {
		imgObj = document.getElementById('process');
		if (document.body.style.cursor=='wait') {
			document.body.style.cursor='default';
			imgObj.style.visibility='hidden';
		} else {
			document.body.style.cursor='wait';
			imgObj.style.visibility='visible';
		}
	};
	
	this.dbChange = function(selectObj) {
		selectedItem = selectObj.selectedIndex;
		selectedText = selectObj.options[selectedItem].text;
		selectedValue = selectObj.options[selectedItem].value;
		if (selectedValue == 'sqlite') {
			document.getElementById('dbusername').value = 'N/A';
			document.getElementById('dbpassword').value = '';
			document.getElementById('dbname').value = 'vcddb.db';
		}
		
	};

	this.doFocus = function(id) {
		try {
			document.getElementById(id).focus();
		} catch (ex) {}
	};
	
	this.verifyConnection = function(buttonObj) {
		this.dbhost = document.getElementById('dbhost').value;
		this.dbusername = document.getElementById('dbusername').value;
		this.dbpassword = document.getElementById('dbpassword').value;
		this.dbname = document.getElementById('dbname').value;
		selectObj = document.getElementById('dbtype');
		this.dbtype = selectObj.options[selectObj.selectedIndex].value;
		
		if (this.dbhost == '' && this.dbtype != 'oci8') {
			alert('Please type in database host.');
			document.getElementById('dbhost').focus();
			return;
		}
		
		if (this.dbusername == '') {
			alert('Please type in database username.');
			document.getElementById('dbusername').focus();
			return;
		}
		
		if (this.dbname == '') {
			alert('Please type in database name.');
			document.getElementById('dbname').focus();
			return;
		}
		
		this.toggleCursor();
		this.hideLayer('dberror');
		document.getElementById('dberror').innerHTML = '';
		dbSettings = Array(this.dbhost, this.dbusername, this.dbpassword, this.dbname, this.dbtype);
		x_Installer.executeCheck('testConnection', dbSettings, this.handleCheckResults);
		
		
		
	};
	
	
}