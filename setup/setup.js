function testConnection(form) {
	
	
	var host = form.db_host.value;
	var user = form.db_username.value;
	var pass = form.db_password.value;
	var dbname = form.db_name.value;
	var dbtype = form.db_type.value;
	
	if (host == "") {
    	alert('Enter hostname');
    	form.db_host.focus(); 
    	return false;
	}
	
	if (user == "") {
    	alert('Enter username');
    	form.db_username.focus(); 
    	return false;
	}
	
	
	if (dbname == "") {
    	alert('Enter database name');
    	form.db_name.focus(); 
    	return false;
	}
	
	
	if(confirm('This test will try to connect to the datebase specified\nwith current connection settings.\n\nIf results are a blank window it means that \nyou do not have the necessary driver installed.\n\nPress OK to open test in new window.'))
	{
		url = 'dbtest.php?h='+host+'&u='+user+'&p='+pass+'&db='+dbname+'&type='+dbtype+'';		
		window.open(url, 'DBTEST', 'toolbar=0,location=0,directories=no,status=no,menubar=no,scrollbars=no,resizable=no,width=350,height=250');	
		
		
	}
}



function validate(step, form) {

	if (step == 2) {
		var host = form.db_host.value;
		var user = form.db_username.value;
		var pass = form.db_password.value;
		var dbname = form.db_name.value;
		var dbtype = form.db_type.value;
		
		if (host == "") {
	    	alert('Enter hostname');
	    	form.db_host.focus(); 
	    	return false;
		}
		
		if (user == "") {
	    	alert('Enter username');
	    	form.db_username.focus(); 
	    	return false;
		}
		
			
		if (dbname == "") {
	    	alert('Enter database name');
	    	form.db_name.focus(); 
	    	return false;
		}
	}
	
	
	if (step == 4) {
		var fullname = form.vcd_fullname.value;
		var username = form.vcd_username.value;
		var password = form.vcd_password.value;
		var password2 = form.vcd_password2.value;
		var email = form.vcd_email.value;
		
		if (fullname == "") {
	    	alert('Enter your full name');
	    	form.vcd_fullname.focus(); 
	    	return false;
		}
		
		if (username == "") {
	    	alert('Enter your username');
	    	form.vcd_username.focus(); 
	    	return false;
		}
		
		if (!checkPasses(password,password2)) {
			form.vcd_password.focus(); 
	    	return false;
		}
		
		
		if (email == "") {
	    	alert('Enter your email');
	    	form.vcd_email.focus(); 
	    	return false;
		}
	
	}
	
	
	if (step == 5) {
		if (!IsNumeric(form.PAGE_COUNT.value) || form.PAGE_COUNT.value == "") {
			alert('Page records must be numeric');
	    	form.PAGE_COUNT.focus(); 
	    	return false;
		}
		
		if (!IsNumeric(form.SESSION_LIFETIME.value) || form.SESSION_LIFETIME.value == "") {
			alert('Session lifetime must be numeric');
	    	form.SESSION_LIFETIME.focus(); 
	    	return false;
		}
		
		if (form.SITE_HOME.value == "") {
	    	alert('Enter the base url of the web');
	    	form.SITE_HOME.focus(); 
	    	return false;
		}
		
		if (form.SITE_NAME.value == "") {
	    	alert('Enter the name of your website');
	    	form.SITE_NAME.focus(); 
	    	return false;
		}
		
		if (form.SITE_ROOT.value == "") {
	    	alert('Enter the path to the document root');
	    	form.SITE_ROOT.focus(); 
	    	return false;
		}
	
	}
	
	
	return true;
	
}

function checkSQLite(form) {
	try {
		
		var dbtype = form.db_type.value;
		if (dbtype == 'sqlite') {
			form.db_host.value = 'localhost';
			form.db_username.value = 'n/a';
			form.db_password.value = 'n/a';
			form.db_name.value = 'vcddb.sqlite';
			form.conntester.disabled = true;
		} else {
			form.conntester.disabled = false;
		}
		
		
	} catch (Exception) {}
	
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