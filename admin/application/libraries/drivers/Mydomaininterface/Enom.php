<?php
defined('SYSPATH') or die('No direct access allowed.');

class Mydomaininterface_Enom_Driver extends Mydomaininterface_Driver
{
	public $PostString;
	public $RawData;
	public $Values;
	
	private $username;
	private $password;
	
	private $err_msg = NULL;
	/**
	 * $UseSSL = 0;  //Set to 1 to use SSL to connect to enom - use 0 for none
	 * $Server = 1;  // 1 for testing, 0 forlive (case sensative);
	 */
	public $UseSSL = 1;
	public $Server = 0;

	public function __construct()
	{}

	/**
	 * set interface account
	 *
	 * @param $username account username
	 * @param $password account password
	 */
	public function account($username = NULL,$password = NULL)
	{
		$this->username = $username;
		$this->password = $password;
	}

	/**
	 * 设置服务器(1:测试服务器,0:正式服务器)
	 *
	 * @param <int> $type
	 */
	public function server($type = 1)
	{
		$this->Server = $type;
	}

	/**
	 * 设置是否启用SSL安全链接(1:启用,0:关闭)
	 *
	 * @param <int> $ssl
	 */
	public function ssl($ssl = 0)
	{
		$this->UseSSL = $ssl;
	}

	/**
	 * set domain hosts
	 */
	public function set_hosts($sld,$tld,$data)
	{
		$result_arr = array('done'=>false,'error'=>'');
		// Set account username and password
		$this->AddParam("uid",$this->username);
		$this->AddParam("pw",$this->password);
		
		// Set the domain name to check
		$this->AddParam("tld",$tld);
		$this->AddParam("sld",$sld);
		if(count($data))
		{
			$num = 1;
			foreach($data as $key=>$value)
			{
				$this->AddParam("HostName".$num,urldecode($value['hostname']));
				$this->AddParam("RecordType".$num,urldecode($value['record_type']));
				$this->AddParam("Address".$num,urldecode($value['address']));
				$num++;
			}
		}
		
		// Check the name
		$this->AddParam("command","SetHosts");
		$this->DoTransaction($this->Server,$this->UseSSL);
		
		$values = $this->Values;
		// Were there errors?_
		if($values["ErrCount"]!="0")
		{
			// Yes, get the first one
			$result_arr['error'] = $values["Err1"];
			return $result_arr;
		}
		else
		{
			// Check code from NSI (210 = name available)
			if($values["Done"])
			{
				$result_arr['done'] = true;
				return $result_arr;
			}
			else
			{
				$result_arr['error'] = $values["Err1"];
				return $result_arr;
			}
		}
	}

	/**
	 * set domain nameserver
	 */
	public function set_ns($sld,$tld,$data)
	{
		//return struct
		$result_arr = array('done'=>false,'error'=>'');
		// Set account username and password
		$this->AddParam("uid",$this->username);
		$this->AddParam("pw",$this->password);
		
		// Set the domain name to check
		$this->AddParam("tld",$tld);
		$this->AddParam("sld",$sld);
		if(isset($data['use_dns']) && ($data['use_dns']=='default'))
		{
			$this->AddParam("UseDNS",'default');
		}
		else
		{
			if(count($data))
			{
				$num = 1;
				$this->AddParam("ns".$num,$value['name']);
				$num++;
			}
		}
		
		// Check the name
		$this->AddParam("command","ModifyNS");
		$this->DoTransaction($this->Server,$this->UseSSL);
		
		$values = $this->Values;
		// Were there errors?
		if($values["ErrCount"]!="0")
		{
			// Yes, get the first one
			$result_arr['error'] = $values["Err1"];
			return $result_arr;
		}
		else
		{
			// Check code from NSI (200 = success)
			if($values["Done"])
			{
				$result_arr['done'] = true;
				return $result_arr;
			}
			else
			{
				$result_arr['error'] = $values["Err1"];
				return $result_arr;
			}
		}
	}

	/**
	 * get domain hosts info
	 */
	public function get_hosts($sld,$tld)
	{
		//return struct
		$result_arr = array('done'=>false);
		
		// Set account username and password
		$this->AddParam("uid",$this->username);
		$this->AddParam("pw",$this->password);
		
		// Set the domain name to check
		$this->AddParam("tld",$tld);
		$this->AddParam("sld",$sld);
		
		// Check the name
		$this->AddParam("command","GetHosts");
		$this->DoTransaction($this->Server,$this->UseSSL);
		
		$values = $this->Values;
		// Were there errors?_
		if($values["ErrCount"]!="0")
		{
			// Yes, get the first one
			$this->err_msg = $values["Err1"];
			return $result_arr;
		}
		else
		{
			// Check code from NSI (200 = success)
			if($values["Done"])
			{
				$host_count = $values['HostCount'];
				if($host_count>0)
				{
					$result_arr['done'] = true;
					for($i = 1;$i<=$host_count;$i++)
					{
						$result_arr['hosts'][$i]['hostname'] = $values['HostName'.$i];
						$result_arr['hosts'][$i]['address'] = $values['Address'.$i];
						$result_arr['hosts'][$i]['record_type'] = $values['RecordType'.$i];
						$result_arr['hosts'][$i]['mx_pref'] = $values['MXPref'.$i];
						$result_arr['hosts'][$i]['hostid'] = $values['hostid'.$i];
					}
					return $result_arr;
				}
				else
				{
					$this->err_msg = "域名无设置.";
					return $result_arr;
				}
			}
			else
			{
				$this->err_msg = $values["Err1"];
				return $result_arr;
			}
		}
	}

	/**
	 * get domain nameserver info
	 */
	public function get_dns($sld,$tld)
	{
		$result_arr = array('done'=>false,'dns1'=>NULL,'dns2'=>NULL,'dns3'=>NULL,'dns4'=>NULL);
		
		// Set account username and password
		$this->AddParam("uid",$this->username);
		$this->AddParam("pw",$this->password);
		
		// Set the domain name to check
		$this->AddParam("tld",$tld);
		$this->AddParam("sld",$sld);
		
		// Check the name
		$this->AddParam("command","GetDNS");
		$this->DoTransaction($this->Server,$this->UseSSL);
		
		$values = $this->Values;
		// Were there errors?_
		if($values["ErrCount"]!="0")
		{
			// Yes, get the first one
			$this->err_msg = $values["Err1"];
			return $result_arr;
		}
		else
		{
			// Check code from NSI (200 = success)
			if($values["Done"])
			{
				$result_arr['done'] = true;
				if(isset($values['DNS1']))
					$result_arr['dns1'] = $values['DNS1'];
				if(isset($values['DNS2']))
					$result_arr['dns2'] = $values['DNS2'];
				if(isset($values['DNS3']))
					$result_arr['dns3'] = $values['DNS3'];
				if(isset($values['DNS4']))
					$result_arr['dns4'] = $values['DNS4'];
				
				return $result_arr;
			}
			else
			{
				$this->err_msg = $values["Err1"];
				return $result_arr;
			}
		}
	}

	/**
	 * check domain
	 *
	 * @param $sld eg. 126
	 * @param $tld eg. .com
	 * @return Boolean
	 */
	public function check($sld,$tld)
	{
		// Set account username and password
		$this->AddParam("uid",$this->username);
		$this->AddParam("pw",$this->password);
		
		// Set the domain name to check
		$this->AddParam("tld",$tld);
		$this->AddParam("sld",$sld);
		
		// Check the name
		$this->AddParam("command","check");
		$this->DoTransaction($this->Server,$this->UseSSL);
		
		$values = $this->Values;
		// Were there errors?
		if($values["ErrCount"]!="0")
		{
			// Yes, get the first one
			$cErrorMsg = $values["Err1"];
			// Flag an error
			$bError = 1;
			$arr = array('flag'=>1,'msg'=>$cErrorMsg);
			return false;
		}
		else
		{
			// Check code from NSI (200 = success)
			switch($values["RRPCode"])
			{
				case "210":
					// The name is available_
					$bAvailable = 1;
					return true;
					break;
				case "211":
					// The name is not available
					$bAvailable = 0;
					$arr = array('flag'=>3,'msg'=>$values['RRPText'],'code'=>$values['RRPCode']);
					return false;
					break;
				default:
					// There was an error from NSI
					$arr = array('flag'=>4,'msg'=>$values['RRPText'],'code'=>$values['RRPCode']);
					return false;
					break;
			}
		}
	}

	/**
	 * check domain
	 *
	 * @param $sld eg. 126
	 * @param $tld eg. .com
	 * @return Boolean
	 */
	public function purchase($sld,$tld)
	{
		$this->NewRequest();
		// Set account username and password
		$this->AddParam("uid",$this->username);
		$this->AddParam("pw",$this->password);
		
		// Set the domain name to check
		$this->AddParam("tld",$tld);
		$this->AddParam("sld",$sld);
		$this->AddParam("numyears",1);
		
		// Check the name
		$this->AddParam("command","purchase");
		$this->DoTransaction($this->Server,$this->UseSSL);
		
		$values = $this->Values;
		
		// Were there errors?
		if($values["ErrCount"]!="0")
		{
			// Yes, get the first one
			$cErrorMsg = $values["Err1"];
			// Flag an error
			$bError = 1;
			return false;
		}
		else
		{
			// Check code from NSI (200 = success)
			switch($values["RRPCode"])
			{
				case "200":
					// The name is available_
					return true;
					break;
				default:
					// There was an error from NSI
					return false;
					break;
			}
		}
	}

	/**
	 * Clear out all previous values
	 */
	public function NewRequest()
	{
		$this->PostString = "";
		$this->RawData = "";
		$this->Values = "";
	}

	/**
	 * Add an error to the result list
	 *
	 * @param $error error info
	 */
	public function AddError($error)
	{
		$this->Values["ErrCount"] = "1";
		$this->Values["Err1"] = $error;
	}

	public function ParseResponse($buffer)
	{
		// Parse the string into lines
		$Lines = explode("\r",$buffer);
		
		// Get # of lines
		$NumLines = count($Lines);
		
		// Skip past header
		$i = 0;
		
		$StartLine = $i;
		
		// Parse lines
		$GotValues = 0;
		for($i = $StartLine;$i<$NumLines;$i++)
		{
			// Is this line a comment?
			if(substr($Lines[$i],1,1)!=";")
			{
				// It is not, parse it
				$Result = explode("=",$Lines[$i]);
				
				// Make sure we got 2 strings
				if(count($Result)>=2)
				{
					// Trim whitespace and add values
					$name = trim($Result[0]);
					$value = trim($Result[1]);
					$this->Values[$name] = $value;
					
					// Was it an ErrCount value?
					if($name=="ErrCount")
					{
						// Remember this!
						$GotValues = 1;
					}
				}
			}
		}
		
		if($GotValues==0)
		{
			// We didn't, so add an error message
			$this->AddError("Could not connect to Server -Please try again Later");
		}
	}

	/**
	 * URL encode the value and add to PostString 
	 */
	public function AddParam($Name,$Value)
	{
		$this->PostString = $this->PostString.$Name."=".urlencode($Value)."&";
	}

	/**
	 * $UseSSL = 0;  //Set to 1 to use SSL to connect to enom - use 0 for none
	 * $Server = 1;  // 1 for testing, 0 forlive (case sensative);
	 */
	public function DoTransaction($Server = 1,$UseSSL = 1)
	{
		$Values = "";
		
		if($Server=='1')
		{
			$host = 'resellertest.enom.com';
		}
		elseif($Server=='0')
		{
			$host = 'reseller.enom.com';
		}
		else
		{
			$host = 'resellertest.enom.com';
		}
		
		if($UseSSL==1)
		{
			$url = "https://".$host;
		}
		else
		{
			$url = "http://".$host;
		}
		// Send command with our parameters
		$out = Mytool::curl_post($url.'/interface.asp',$this->PostString);
		
		$this->ParseResponse($out);
	}
}
