<?php
/*
 * smtp.php
 *
 * @(#) $Header$
 *
 */

class smtp_class
{
	public $user="";
	public $realm="";
	public $password="";
	public $host_name="";
	public $host_port=25;
	public $localhost="";
	public $timeout=0;
	public $data_timeout=0;
	public $direct_delivery=0;
	public $error="";
	public $debug=0;
	public $html_debug=0;
	public $esmtp=1;
	public $esmtp_host="";
	public $esmtp_extensions=array();
	public $maximum_piped_recipients=100;
	public $exclude_address="";
	public $getmxrr="GetMXRR";

	/* private variables - DO NOT ACCESS */

	private $state="Disconnected";
	private $connection=0;
	private $pending_recipients=0;
	private $next_token="";
	private $direct_sender="";
	private $connected_domain="";

	/* Private methods - DO NOT CALL */

	Function Tokenize($string,$separator="")
	{
		if(!strcmp($separator,""))
		{
			$separator=$string;
			$string=$this->next_token;
		}
		for($character=0;$character<strlen($separator);$character++)
		{
			if(GetType($position=strpos($string,$separator[$character]))=="integer")
				$found=(IsSet($found) ? min($found,$position) : $position);
		}
		if(IsSet($found))
		{
			$this->next_token=substr($string,$found+1);
			return(substr($string,0,$found));
		}
		else
		{
			$this->next_token="";
			return($string);
		}
	}

	Function OutputDebug($message)
	{
		$message.="\n";
		if($this->html_debug)
			$message=str_replace("\n","<br />\n",HtmlEntities($message));
		echo $message;
		flush();
	}

	Function SetDataAccessError($error)
	{
		$this->error=$error;
		if(function_exists("socket_get_status"))
		{
			$status=socket_get_status($this->connection);
			if($status["timed_out"])
				$this->error.=": data access time out";
			elseif($status["eof"])
				$this->error.=": the server disconnected";
		}
	}

	Function GetLine()
	{
		for($line="";;)
		{
			if(feof($this->connection))
			{
				$this->error="reached the end of data while reading from the SMTP server conection";
				return("");
			}
			if(GetType($data=fgets($this->connection,100))!="string"
			|| strlen($data)==0)
			{
				$this->SetDataAccessError("it was not possible to read line from the SMTP server");
				return("");
			}
			$line.=$data;
			$length=strlen($line);
			if($length>=2
			&& substr($line,$length-2,2)=="\r\n")
			{
				$line=substr($line,0,$length-2);
				if($this->debug)
					$this->OutputDebug("S $line");
				return($line);
			}
		}
	}

	Function PutLine($line)
	{
		if($this->debug)
			$this->OutputDebug("C $line");
		if(!fputs($this->connection,"$line\r\n"))
		{
			$this->SetDataAccessError("it was not possible to send a line to the SMTP server");
			return(0);
		}
		return(1);
	}

	Function PutData(&$data)
	{
		if(strlen($data))
		{
			if($this->debug)
				$this->OutputDebug("C $data");
			if(!fputs($this->connection,$data))
			{
				$this->SetDataAccessError("it was not possible to send data to the SMTP server");
				return(0);
			}
		}
		return(1);
	}

	Function VerifyResultLines($code,&$responses)
	{
		$responses=array();
		Unset($match_code);
		while(strlen($line=$this->GetLine($this->connection)))
		{
			if(IsSet($match_code))
			{
				if(strcmp($this->Tokenize($line," -"),$match_code))
				{
					$this->error=$line;
					return(0);
				}
			}
			else
			{
				$match_code=$this->Tokenize($line," -");
				if(GetType($code)=="array")
				{
					for($codes=0;$codes<count($code) && strcmp($match_code,$code[$codes]);$codes++);
					if($codes>=count($code))
					{
						$this->error=$line;
						return(0);
					}
				}
				else
				{
					if(strcmp($match_code,$code))
					{
						$this->error=$line;
						return(0);
					}
				}
			}
			$responses[]=$this->Tokenize("");
			if(!strcmp($match_code,$this->Tokenize($line," ")))
				return(1);
		}
		return(-1);
	}

	Function FlushRecipients()
	{
		if($this->pending_sender)
		{
			if($this->VerifyResultLines("250",$responses)<=0)
				return(0);
			$this->pending_sender=0;
		}
		for(;$this->pending_recipients;$this->pending_recipients--)
		{
			if($this->VerifyResultLines(array("250","251"),$responses)<=0)
				return(0);
		}
		return(1);
	}

	/*
	 * Method:		TryAuthPlain
	 * Parameters:	$s_data	the data to use for authentication
	 * Returns:		int		1 on success, 0 on failure
	 * Author:		Russell Robinson, 25 May 2003, http://www.tectite.com/
	 * Purpose:
	 *	Try an AUTH PLAIN authentication method.
	 */
	Function TryAuthPlain($s_data)
	{
		return ($this->PutLine("AUTH PLAIN ".base64_encode($s_data)) &&
			($this->VerifyResultLines("235",$responses) > 0));
	}

	/*
	 * Method:		TryAllAuthPlain
	 * Parameters:	void
	 * Returns:		int		1 on success, 0 on failure
	 * Author:		Russell Robinson, 25 May 2003, http://www.tectite.com/
	 * Purpose:
	 *	Try various AUTH PLAIN authentication methods.
	 */
	Function TryAllAuthPlain()
	{
			//
			// this one is defined by sendmail here:
			// according to: http://www.sendmail.org/~ca/email/authrealms.html#authpwcheck_method
			//
		$s_data = $this->user.chr(0).$this->user.(strlen($this->realm) ? "@".$this->realm : "").chr(0).$this->password;
		if ($this->TryAuthPlain($s_data))
			return (1);

			//
			// Also according to the above document, some sendmails won't accept
			// the realm, so try again without it
			//
		$s_data = $this->user.chr(0).$this->user.chr(0).$this->password;
		if ($this->TryAuthPlain($s_data))
			return (1);

			//
			// I've seen an EXIM configuration like this:
			//		user^password^unused
			// though: http://exim.work.de/exim-html-3.20/doc/html/spec_36.html
			// specifies
			//		^user^password
			// we'll try both
			//
		$s_data = $this->user.chr(0).$this->password.chr(0);
		if ($this->TryAuthPlain($s_data))
			return (1);
		$s_data = chr(0).$this->user.chr(0).$this->password;
		if ($this->TryAuthPlain($s_data))
			return (1);

		return (0);
	}

	/* Public methods */

	Function Connect($domain="")
	{
		$this->error=$error="";
		$this->esmtp_host="";
		$this->esmtp_extensions=array();
		$hosts=array();
		if($this->direct_delivery)
		{
			if(strlen($domain)==0)
				return(1);
			$hosts=$weights=$mxhosts=array();
			$getmxrr=$this->getmxrr;
			if(function_exists($getmxrr)
			&& $getmxrr($domain,$hosts,$weights))
			{
				for($host=0;$host<count($hosts);$host++)
					$mxhosts[$weights[$host]]=$hosts[$host];
				KSort($mxhosts);
				for(Reset($mxhosts),$host=0;$host<count($mxhosts);Next($mxhosts),$host++)
					$hosts[$host]=$mxhosts[Key($mxhosts)];
			}
			else
			{
				if(strcmp(@gethostbyname($domain),$domain)!=0)
					$hosts[]=$domain;
			}
		}
		else
		{
			if(strlen($this->host_name))
				$hosts[]=$this->host_name;
		}
		if(count($hosts)==0)
		{
			$this->error="could not determine the SMTP to connect";
			return(0);
		}
		if(strcmp($this->state,"Disconnected"))
		{
			$this->error="connection is already established";
			return(0);
		}
		for($host=0;;$host++)
		{
			$domain=$hosts[$host];
			if(ereg('^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}$',$domain))
				$ip=$domain;
			else
			{
				if($this->debug)
					$this->OutputDebug("Resolving SMTP server domain \"$domain\"...");
				if(!strcmp($ip=@gethostbyname($domain),$domain))
					$ip="";
			}
			if(strlen($ip)==0
			|| (strlen($this->exclude_address)
			&& !strcmp(@gethostbyname($this->exclude_address),$ip)))
			{
				if($host==count($hosts)-1)
				{
					$this->error="could not resolve the host domain \"".$domain."\"";
					return(0);
				}
				continue;
			}
			if($this->debug)
				$this->OutputDebug("Connecting to SMTP server ip \"$ip\"...");
			if(($this->connection=($this->timeout ? @fsockopen($ip,$this->host_port,$errno,$error,$this->timeout) : @fsockopen($ip,$this->host_port))))
				break;
			if($host==count($hosts)-1)
			{
				switch($this->timeout ? strval($error) : "??")
				{
					case "-3":
						$this->error="-3 socket could not be created";
						break;
					case "-4":
						$this->error="-4 dns lookup on hostname \"".$domain."\" failed";
						break;
					case "-5":
						$this->error="-5 connection refused or timed out";
						break;
					case "-6":
						$this->error="-6 fdopen() call failed";
						break;
					case "-7":
						$this->error="-7 setvbuf() call failed";
						break;
					default:
						$this->error="could not connect to the host \"".$domain."\": ".$error;
						break;
				}
				return(0);
			}
		}
		$timeout=($this->data_timeout ? $this->data_timeout : $this->timeout);
		if($timeout
		&& function_exists("socket_set_timeout"))
			socket_set_timeout($this->connection,$timeout,0);
		if($this->debug)
			$this->OutputDebug("Connected to SMTP server ip \"$ip\".");
		if(!strcmp($localhost=$this->localhost,"")
		&& !strcmp($localhost=getenv("SERVER_NAME"),"")
		&& !strcmp($localhost=getenv("HOST"),""))
			$localhost="localhost";
		$success=0;
		if($this->VerifyResultLines("220",$responses)>0)
		{
			$fallback=1;
			if($this->esmtp
			|| strlen($this->user))
			{
				if($this->PutLine("EHLO $localhost"))
				{
					if(($success_code=$this->VerifyResultLines("250",$responses))>0)
					{
						$this->esmtp_host=$this->Tokenize($responses[0]," ");
						for($response=1;$response<count($responses);$response++)
						{
							$extension=strtoupper($this->Tokenize($responses[$response]," "));
							$this->esmtp_extensions[$extension]=$this->Tokenize("");
						}
						$success=1;
						$fallback=0;
					}
					else
					{
						if($success_code==0)
						{
							$code=$this->Tokenize($this->error," -");
							switch($code)
							{
								case "421":
									$fallback=0;
									break;
							}
						}
					}
				}
				else
					$fallback=0;
			}
			if($fallback)
			{
				if($this->PutLine("HELO $localhost")
				&& $this->VerifyResultLines("250",$responses)>0)
					$success=1;
			}
			if($success
			&& strlen($this->user))
			{
				if(!IsSet($this->esmtp_extensions["AUTH"]))
				{
					$this->error="server does not require authentication";
					$success=0;
				}
				else
				{
					for($authentication=$this->Tokenize($this->esmtp_extensions["AUTH"]," ");strlen($authentication);$authentication=$this->Tokenize($remaining_authentication_types," "))
					{
						$remaining_authentication_types=$this->Tokenize("");
						switch($authentication)
						{
							case "PLAIN":
								$success=$this->TryAllAuthPlain();
								break;
							case "LOGIN":
								$success=($this->PutLine("AUTH LOGIN")
								&& $this->VerifyResultLines("334",$responses)
								&& $this->PutLine(base64_encode($this->user.(strlen($this->realm) ? "@".$this->realm : "")))
								&& $this->VerifyResultLines("334",$responses)
								&& $this->PutLine(base64_encode($this->password))
								&& $this->VerifyResultLines("235",$responses));
								break;
						}
						if($success)
							break;
					}
					if($success
					&& strlen($authentication)==0)
					{
						$this->error="the server does not require a supported authentication method";
						$success=0;
					}
				}
			}
		}
		if($success)
		{
			$this->state="Connected";
			$this->connected_domain=$domain;
		}
		else
		{
			fclose($this->connection);
			$this->connection=0;
		}
		return($success);
	}

	Function MailFrom($sender)
	{
		if($this->direct_delivery)
		{
			switch($this->state)
			{
				case "Disconnected":
					$this->direct_sender=$sender;
					return(1);
				case "Connected":
					$sender=$this->direct_sender;
					break;
				default:
					$this->error="direct delivery connection is already established and sender is already set";
					return(0);
			}
		}
		else
		{
			if(strcmp($this->state,"Connected"))
			{
				$this->error="connection is not in the initial state";
				return(0);
			}
		}
		$this->error="";
		if(!$this->PutLine("MAIL FROM:<$sender>"))
			return(0);
		if(!IsSet($this->esmtp_extensions["PIPELINING"])
		&& $this->VerifyResultLines("250",$responses)<=0)
			return(0);
		$this->state="SenderSet";
		if(IsSet($this->esmtp_extensions["PIPELINING"]))
			$this->pending_sender=1;
		$this->pending_recipients=0;
		return(1);
	}

	Function SetRecipient($recipient)
	{
		if($this->direct_delivery)
		{
			if(GetType($at=strrpos($recipient,"@"))!="integer")
				return("it was not specified a valid direct recipient");
			$domain=substr($recipient,$at+1);
			switch($this->state)
			{
				case "Disconnected":
					if(!$this->Connect($domain))
						return(0);
					if(!$this->MailFrom(""))
					{
						$error=$this->error;
						$this->Disconnect();
						$this->error=$error;
						return(0);
					}
					break;
				case "SenderSet":
				case "RecipientSet":
					if(strcmp($this->connected_domain,$domain))
					{
						$this->error="it is not possible to deliver directly to recipients of different domains";
						return(0);
					}
					break;
				default:
					$this->error="connection is already established and the recipient is already set";
					return(0);
			}
		}
		else
		{
			switch($this->state)
			{
				case "SenderSet":
				case "RecipientSet":
					break;
				default:
					$this->error="connection is not in the recipient setting state";
					return(0);
			}
		}
		$this->error="";
		if(!$this->PutLine("RCPT TO:<$recipient>"))
			return(0);
		if(IsSet($this->esmtp_extensions["PIPELINING"]))
		{
			$this->pending_recipients++;
			if($this->pending_recipients>=$this->maximum_piped_recipients)
			{
				if(!$this->FlushRecipients())
					return(0);
			}
		}
		else
		{
			if($this->VerifyResultLines(array("250","251"),$responses)<=0)
				return(0);
		}
		$this->state="RecipientSet";
		return(1);
	}

	Function StartData()
	{
		if(strcmp($this->state,"RecipientSet"))
		{
			$this->error="connection is not in the start sending data state";
			return(0);
		}
		$this->error="";
		if(!$this->PutLine("DATA"))
			return(0);
		if($this->pending_recipients)
		{
			if(!$this->FlushRecipients())
				return(0);
		}
		if($this->VerifyResultLines("354",$responses)<=0)
			return(0);
		$this->state="SendingData";
		return(1);
	}

	Function PrepareData(&$data,&$output,$preg=1)
	{
		if($preg
		&& function_exists("preg_replace"))
			$output=preg_replace(array("/\n\n|\r\r/","/(^|[^\r])\n/","/\r([^\n]|\$)/D","/(^|\n)\\./"),array("\r\n\r\n","\\1\r\n","\r\n\\1","\\1.."),$data);
		else
			$output=ereg_replace("(^|\n)\\.","\\1..",ereg_replace("\r([^\n]|\$)","\r\n\\1",ereg_replace("(^|[^\r])\n","\\1\r\n",ereg_replace("\n\n|\r\r","\r\n\r\n",$data))));
	}

	Function SendData($data)
	{
		if(strcmp($this->state,"SendingData"))
		{
			$this->error="connection is not in the sending data state";
			return(0);
		}
		$this->error="";
		return($this->PutData($data));
	}

	Function EndSendingData()
	{
		if(strcmp($this->state,"SendingData"))
		{
			$this->error="connection is not in the sending data state";
			return(0);
		}
		$this->error="";
		if(!$this->PutLine("\r\n.")
		|| $this->VerifyResultLines("250",$responses)<=0)
			return(0);
		$this->state="Connected";
		return(1);
	}

	Function ResetConnection()
	{
		switch($this->state)
		{
			case "Connected":
				return(1);
			case "SendingData":
				$this->error="can not reset the connection while sending data";
				return(0);
			case "Disconnected":
				$this->error="can not reset the connection before it is established";
				return(0);
		}
		$this->error="";
		if(!$this->PutLine("RSET")
		|| $this->VerifyResultLines("250",$responses)<=0)
			return(0);
		$this->state="Connected";
		return(1);
	}

	Function Disconnect($quit=1)
	{
		if(!strcmp($this->state,"Disconnected"))
		{
			$this->error="it was not previously established a SMTP connection";
			return(0);
		}
		$this->error="";
		if(!strcmp($this->state,"Connected")
		&& $quit
		&& (!$this->PutLine("QUIT")
		|| $this->VerifyResultLines("221",$responses)<=0))
			return(0);
		fclose($this->connection);
		$this->connection=0;
		$this->state="Disconnected";
		if($this->debug)
			$this->OutputDebug("Disconnected.");
		return(1);
	}

	Function SendMessage($sender,$recipients,$headers,$body)
	{
		if(($success=$this->Connect()))
		{
			if(($success=$this->MailFrom($sender)))
			{
				for($recipient=0;$recipient<count($recipients);$recipient++)
				{
					if(!($success=$this->SetRecipient($recipients[$recipient])))
						break;
				}
				if($success
				&& ($success=$this->StartData()))
				{
					for($header_data="",$header=0;$header<count($headers);$header++)
						$header_data.=$headers[$header]."\r\n";
					if(($success=$this->SendData($header_data."\r\n")))
					{
						$this->PrepareData($body,$body_data);
						$success=$this->SendData($body_data);
					}
					if($success)
						$success=$this->EndSendingData();
				}
			}
			$error=$this->error;
			$disconnect_success=$this->Disconnect($success);
			if($success)
				$success=$disconnect_success;
			else
				$this->error=$error;
		}
		return($success);
	}

};

?>