<?php
defined('SYSPATH') or die('No direct script access.');

class Tool_Core{

	/*
	 * 64们机和32位机兼容的ip2long
	 */
	public static function myip2long($strIP)
	{
		$longIP = ip2long($strIP);
		if($longIP < 0){
			$longIP += 4294967296;
		}
		return $longIP;
	}

	/*
	 * 得到服务器上的长整型IP
	 */
	public static function get_str_ip()
	{
		return Input::instance()->ip_address();
	}
    
	/*
	 * 得到服务器上的长整型IP
	 */
	public static function get_long_ip()
	{
		$ip = Input::instance()->ip_address();
		return self::myip2long($ip);
	}

	/**
	 * 得到IP地址的详细信息
	 */
	public static function get_ip_detail($ip)
	{
		$key = 'ipinfo.'.str_replace('.', '-', $ip);
		if(!($ip_arr = Cache::get($key, 900))) {
			$ip_arr = array();
			$ip_arr = @unserialize(stripcslashes(@file_get_contents("http://ip.backstage-gateway.com/ip?ip=$ip")));
			if(isset($ip_arr['country'])){
				$ip_country = explode(":",$ip_arr['country']);
				$ip_arr['country_code'] = $ip_country[0];
			}
			Cache::set($key, $ip_arr);
		}
		return $ip_arr;
	}

	/**
	 * get ip country code,default US
	 */
	public static function get_ip_country($ip)
	{
		$ip_arr = tool::get_ip_detail($ip);
		return (isset($ip_arr['country_code'])) ? $ip_arr['country_code'] : 'US';
	}

	/*
	 * curl模拟post
	 */
	public static function curl_pay($API_Endpoint, $nvpStr)
	{
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL,$API_Endpoint);
		curl_setopt($ch,CURLOPT_VERBOSE,1);
		curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,FALSE);
		curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,FALSE);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch,CURLOPT_POST,1);
		curl_setopt($ch,CURLOPT_POSTFIELDS,$nvpStr);
		$response = curl_exec($ch);
		if(curl_errno($ch)){
			$curl_error_no = curl_errno($ch);
			$curl_error_msg = curl_error($ch);
		} else{
			curl_close($ch);
		}
		return $response;
	}

	/*
	 * 安全过滤字符串,在添加入数据之前用
	 */
	public static function filter_keywords($keywords)
	{
		$keyowrds = strip_tags($keywords);
		
		$search = array(
			'http://', 
			'www.', 
			'www'
		);
		$keyowrds = str_replace($search,"",$keywords);
		return trim($keyowrds);
	}

	/**
	 * 过滤数组，保留指定键值列表
	 * @param array $array
	 * @param array $requestkeys
	 * @example tool::filter_keys（$array,$requestkeys);
	 */
	public static function filter_keys(&$array, $requestkeys)
	{
		foreach($array as $key=>$val){
			if(!in_array($key,$requestkeys)){
				unset($array[$key]);
			}
		}
	}

	/**
	 * 过滤数组，去除指定键值列表
	 * @param array $array
	 * @param array $requestkeys
	 * @example tool::filter_keys（$array,$requestkeys);
	 */
	public static function erase_keys(&$array, $requestKyes)
	{
		foreach($array as $key=>$val){
			if(in_array($key,$requestKyes)){
				unset($array[$key]);
			}
		}
	}

	/*
	 * 解密链接
	 */
	public static function query_decode($sEncode)
	{
		if(strlen($sEncode) == 0){
			return '';
		} else{
			$s_tem = strrev($sEncode);
			$s_tem = base64_decode($s_tem);
			$s_tem = rawurldecode($s_tem);
			$vcode = substr($s_tem,6,7);
			$s_tem = substr($s_tem,14);
			$a_tem = explode('&',$s_tem);
			$hash = 'id8ap';
			$verifyCode = '';
			foreach($a_tem as $rs){
				$verifyCode .= $hash . $rs;
			}
			$verifyCode = substr(md5($verifyCode),3,7);
			if($verifyCode == $vcode){
				return $s_tem;
			} else{
				return '';
			}
		}
	}

	/**
	 * 字符串截断用...代替显示
	 */
	public static function my_substr($str, $num)
	{
		if(strlen($str) > ($num + 2)){
			$str = substr($str,0,$num) . '...';
		}
		return $str;
	}

	/**
	 * 强制数组有固定的键值
	 * 例 $key_arr = ('a','b');
	 * $value_arr = ('a'=>'123');
	 * 返回   $arr = ('a'=>'123','b'=>'');
	 */
	public static function init_arr($key_arr, $value_arr)
	{
		if(is_array($key_arr)){
			$return_arr = array();
			foreach($key_arr as $key){
				$return_arr[$key] = isset($value_arr[$key]) ? $value_arr[$key] : '';
			}
		}
	}

	/**
	 * 检查生日格式是否正确
	 */
	public static function check_date($date)
	{
	
		//2010-06-01
		if(strpos($date,'-')){
			list ($yy, $mm, $dd) = explode("-",$date);
			if(is_numeric($yy) && is_numeric($mm) && is_numeric($dd)){
			   return (!checkdate($mm,$dd,$yy)) ? false : $yy . '-' . $mm . '-' . $dd;
			}
			return false;
		}
		//01/06/2010
		if(strpos($date,'/')){
			list ($dd, $mm, $yy) = explode("/",$date);
			if(is_numeric($yy) && is_numeric($mm) && is_numeric($dd)){
				return (!checkdate($mm,$dd,$yy)) ? false : $yy . '-' . $mm . '-' . $dd;
			}
			return false;
		}
		return false;
	}

	/**
	 * 根据时间格式得到当前的本地时间
	 */
	public static function get_date()
	{
		return date('Y-m-d H:i:s',time());
	}

	/**
	 * author zhubin 
	 * 对数组进行循环去除标签
	 * @param array $arr 需要过滤的数组
	 * @param array $nfilter 不需要过滤的键名
	 */
	public static function filter_strip_tags(&$arr, $nfilter = array())
	{
		if(!is_array($arr))
		{
			$arr = strip_tags($arr);
		} else{
			if(!is_array($nfilter))
			{
				$nfilter = array($nfilter);
			}
			foreach($arr as $key=>$value)
			{
				if(in_array($key,$nfilter))
				{
					continue;
				}
				self::filter_strip_tags($arr[$key],$nfilter);
			}
		}
	}

	/**
	 * 检查是否是序列化字符
	 * @param $string
	 * @param $errmsg
	 */
	function check_serialization($string, &$errmsg)
	{
		$str = 's';
		$array = 'a';
		$integer = 'i';
		$any = '[^}]*?';
		$count = '\d+';
		$content = '"(?:\\\";|.)*?";';
		$open_tag = '\{';
		$close_tag = '\}';
		$parameter = "($str|$array|$integer|$any):($count)" . "(?:[:]($open_tag|$content)|[;])";
		$preg = "/$parameter|($close_tag)/";
		if(!preg_match_all($preg,$string,$matches)){
			$errmsg = 'not a serialized string';
			return false;
		}
		$open_arrays = 0;
		foreach($matches[1] as $key=>$value){
			if(!empty($value) && ($value != $array xor $value != $str xor $value != $integer)){
				$errmsg = 'undefined datatype';
				return false;
			}
			if($value == $array){
				$open_arrays++;
				if($matches[3][$key] != '{'){
					$errmsg = 'open tag expected';
					return false;
				}
			}
			if($value == ''){
				if($matches[4][$key] != '}'){
					$errmsg = 'close tag expected';
					return false;
				}
				$open_arrays--;
			}
			if($value == $str){
				$aVar = ltrim($matches[3][$key],'"');
				$aVar = rtrim($aVar,'";');
				if(strlen($aVar) != $matches[2][$key]){
					$errmsg = 'stringlen for string not match';
					return false;
				}
			}
			if($value == $integer){
				if(!empty($matches[3][$key])){
					$errmsg = 'unexpected data';
					return false;
				}
				if(!is_integer((int)$matches[2][$key])){
					$errmsg = 'integer expected';
					return false;
				}
			}
		}
		if($open_arrays != 0){
			$errmsg = 'wrong setted arrays';
			return false;
		}
		return true;
	}

	/*
	 * 根据GET请求数组生成GET请求字符串
	 * @param array $request GET请求数组
	 * @param string $key 需改变的GET请求数组KEY
	 * @param string $value 改变后的值
	 * @return string 生成的GET请求字符串
	 */
	public static function create_query_string($request = array(), $key, $value)
	{
		$query_string = '';
		$request[$key] = $value;
		if(!empty($request)){
			foreach($request as $key=>$val){
				$query_string .= '&' . $key . '=' . $val;
			}
			$query_string = preg_replace('/^&/','?',$query_string);
		}
		return $query_string;
	}

	/*
     * 二维数组排序
     */
	public static function sort_array(&$arr, $order_array = array())
	{
		if(!empty($arr) && is_array($arr)){
			if(!empty($order_array)){
				$str = '';
				foreach($order_array as $key=>$rule){
					if($rule == 'desc'){
						$str .= 'if($arr1["' . $key . '"] < $arr2["' . $key . '"]) return 1;elseif($arr1["' . $key . '"] > $arr2["' . $key . '"]) return -1;else';
					} elseif($rule == 'asc'){
						$str .= 'if($arr1["' . $key . '"] > $arr2["' . $key . '"]) return 1;elseif($arr1["' . $key . '"] < $arr2["' . $key . '"]) return -1;else';
					}
				}
				$str .= ' return 0;';
				uasort($arr,create_function('$arr1,$arr2',$str));
			}
		}
	}

	/** 
	 * 判断提交是否正确
	 */
	function submit_check()
	{
		if((!empty($_SERVER['REQUEST_METHOD'])) && ($_SERVER['REQUEST_METHOD'] == 'POST')){
			if((empty($_SERVER['HTTP_REFERER']) || preg_replace("/https?:\/\/([^\:\/]+).*/i","\\1",$_SERVER['HTTP_REFERER']) == preg_replace("/([^\:]+).*/","\\1",$_SERVER['HTTP_HOST']))){
				return true;
			} else{
				return false;
			}
		} else{
			return false;
		}
	}
	
	/**
	 * 获取文件后缀名函数
	 */
	public function fileext($filename)
	{
		return substr(strrchr($filename,'.'),1);
	}
	

	/**
	 * 关键字符检查
	 * @param string $string
	 * @param string $type
	 * @return boolen
	 */
	function filter_keywords_check($type,$string)
	{
		$site_keywords = Kohana::config('keywords.site');
		$keywords = Kohana::config('keywords.'.$type);
		$keywords = !empty($keywords) ? $site_keywords.'|'.$keywords : $site_keywords;
		$string = strtolower($string);
		$matched = preg_match('/'.$keywords.'/i', $string, $result);
		if ( $matched && isset($result[0]) && strlen($result[0]) > 0 )
		{
			if(strlen($result[0]) == 2 )
			{
				$matched = preg_match('/'.$keywords.'/iu', $string, $result);
			} 
			if($matched && isset($result[0]) && strlen($result[0]) > 0 )
			{
				return true;
			}else{
				return false;
			}  
		}else{
		    return false;
		}
	}

	/**
	 * hash password
	 *
	 * @param 	Str 	$password
	 * @return 	Str 	hashed password
	 */
	public static function hash($password)
	{
		return sha1($password);
	}

	/**
	 * javaScript decode for xxs clean
	 *
	 * @param 	Str 	$code
	 * @return 	Str 	decode javaScript code
	 */
	public static function js_decode($code)
	{
		return base64_decode($code);	
	}

	/**
	 * javaScript encode for xxs clean
	 *
	 * @param 	Str 	$code
	 * @return 	Str 	encode javaScript code
	 */
	public static function js_encode($code)
	{
		return base64_encode($code);	
	}

	/**
	 * 根据日期返回星期几
	 *
	 * @param 	time 	$date   时间<非时间戳>
	 * @param 	integer $type	返回的样式(1:星期一;2:1)	
	 * @return 	string 	返回样式
	 */
	public static function get_weekday($date = NULL, $type=1)
	{
	    if (empty($date))
	    {
	        $date = time();
	    }
	    else 
	    {
	        $date = strtotime($date);
	    }
	    
	    $getweek = date("D", $date);
	    $return = '';
	    if ($type == 1) 
	    {
	        switch ($getweek) 
	        {
	            case 'Mon':
	                $return =  '星期一';
	                break;
	            case 'Tue':
	                $return =  '星期二';
	                break;	                
	            case 'Wed':
	                $return =  '星期三';
	                break;		                
	            case 'Thu':
	                $return =  '星期四';
	                break;	
	            case 'Fri':
	                $return =  '星期五';
	                break;
	            case 'Sat':
	                $return =  '星期六';
	                break;	                
	            case 'Sun':
	                $return =  '星期日';
	                break;
	            default:
	               $return = ''; 
	        }	        
	    }
	    elseif($type == 2) 
	    {
	    	switch ($getweek) 
	        {
	            case 'Mon':
	                $return =  '1';
	                break;
	            case 'Tue':
	                $return =  '2';
	                break;	                
	            case 'Wed':
	                $return =  '3';
	                break;		                
	            case 'Thu':
	                $return =  '4';
	                break;	
	            case 'Fri':
	                $return =  '5';
	                break;
	            case 'Sat':
	                $return =  '6';
	                break;	                
	            case 'Sun':
	                $return =  '7';
	                break;
	            default:
	               $return = ''; 
	        }
	    }  
		return $return;
	}
	
	/*
	 * 根据输入的星期获取日期,日期大于等于今天
	 * @param 	string $week	星期的值
	 * @param 	integer $type	录入的样式(1:周四)
	 * @param 	date  $reftime 	参考时间
	 * @return 	date  返回日期
	 */
	public  static  function  get_date_byweek($week, $type=1, $reftime = NULL) 
	{	
	    if ($type == 1) 
	    {
	        $weeknum = tool::get_week_en($week);
	        if (!empty($reftime))
	        {
	            $max = 5; 
	            $getdate = '';
	            for($i=0; $i<=$max; $i++)
	            {
	                $timeadd = strtotime($reftime) + $i*86400;
	                $timedef = strtotime($reftime) - $i*86400;
	                
	                if (date('D', $timeadd) == $weeknum)
	                {
	                    $getdate = $timeadd;
	                    break;
	                }
	                elseif (date('D', $timedef) == $weeknum) 
	                {
	                    $getdate = $timedef;
	                    break;
	                }
	            }

	            if (!empty($getdate))
	            {
	                return date("Y-m-d", $getdate);
	            }
	            else 
	            {
	                return date("Y-m-d", time());
	            }
	        }
	        	        
	        if ($weeknum == '') 
	        {
	            return date("Y-m-d H:i:s", time());
	        }
	        else
	        {
	            return date("Y-m-d H:i:s", strtotime($weeknum));
	        }
	    }
	    
	    elseif ($type == 2) 
	    {
	    	$weektype = array('Mon'=>'1', 'Tue'=>'2', 'Wed'=>'3', 'Thu'=>'4', 'Fri'=>'5', 'Sat'=>'6', 'Sun'=>'7');
	        
	        $weeknum = '';
	        foreach ($weektypek as $key => $value) 
	        {   
	            $check = strpos($week, $value);
	            if ($check !== FALSE)
	            {
	                $weeknum = $key;
	                break;
	            }
	        }
	        
	    	if (!empty($reftime))
	        {
	            $max = 5; 
	            $getdate = '';
	            for($i=0; $i<=$max; $i++)
	            {
	                $timeadd = strtotime($reftime) + $i*86400;
	                $timedef = strtotime($reftime) - $i*86400;
	                if (date('D', $timeadd) == $weeknum)
	                {
	                    $getdate = $timeadd;
	                    break;
	                }
	                elseif (date('D', $timedef) == $weeknum) 
	                {
	                    $getdate = $timeadd;
	                    break;
	                }
	            }
	            if (!empty($getdate))
	            {
	                return date("Y-m-d", $getdate);
	            }
	            else 
	            {
	                
	            }
	        }
	        
	        if ($weeknum == '') 
	        {
	            return date("Y-m-d H:i:s", time());
	        }
	        
	        //当参考时间不能为空(参考时间前后5日内是否有相应日期)
	        if (!empty($reftime))
	        {   
	        	$max = 5; 
	            $getdate = '';
	            for($i=0; $i<=$max; $i++)
	            {
	                $timeadd = strtotime($reftime) + $i*86400;
	                $timedef = strtotime($reftime) - $i*86400;
	                if (date('D', $timeadd) == $weeknum)
	                {
	                    $getdate = $timeadd;
	                    break;
	                }
	                elseif (date('D', $timedef) == $weeknum) 
	                {
	                    $getdate = $timeadd;
	                    break;
	                }
	            }
	        } 
	        else
	        {
	            return date("Y-m-d H:i:s", strtotime($weeknum));
	        }
	        
	    	if ($weeknum == '') 
	        {
	            return date("Y-m-d H:i:s", time());
	        }
	        else
	        {
	            return date("Y-m-d H:i:s", strtotime($weeknum));
	        }
	        
	    }
	}
	
	
	/**
	 * 调试数组
	 *
	 * @param 	array 	$arr
	 * @param	string	char		
	 * @return 	string 	
	 */
	public static function debu_array($arr , $char = ',')
	{
	    $return = '';
	    
	    if (!is_array($arr))
	    {
	        $return = $arr;
	    }
	    else 
	    {
	        foreach ($arr as $key => $value)
	        {
	            $return .= $key.':'.$value.$char;
	        }
	    }
	    
	    return $return;
	}

    /* 
    	Utf-8、gb2312都支持的汉字截取函数 
    	cut_str(字符串, 截取长度, 开始长度, 编码); 
    	编码默认为 utf-8 
    	开始长度默认为 0 
    */ 
    public static function cut_str($string, $sublen, $start = 0, $code = 'UTF-8'){ 
        if($code == 'UTF-8'){ 
            $pa = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/"; 
            preg_match_all($pa, $string, $t_string); 
     
            if(count($t_string[0]) - $start > $sublen) return join('', array_slice($t_string[0], $start, $sublen)).".."; 
            return join('', array_slice($t_string[0], $start, $sublen)); 
        }else{ 
            $start = $start*2; 
            $sublen = $sublen*2; 
            $strlen = strlen($string); 
            $tmpstr = ''; 
     
            for($i=0; $i< $strlen; $i++){ 
                if($i>=$start && $i< ($start+$sublen)){ 
                    if(ord(substr($string, $i, 1))>129){ 
                        $tmpstr.= substr($string, $i, 2); 
                    }else{ 
                        $tmpstr.= substr($string, $i, 1); 
                    } 
                } 
                if(ord(substr($string, $i, 1))>129) $i++; 
            } 
            if(strlen($tmpstr)< $strlen ) $tmpstr.= "..."; 
            return $tmpstr; 
        } 
    }
    public static function substring($str, $start, $len){
         $tmpstr = "";
         $strlen = $start + $len;
         for($i = 0; $i < $strlen; $i++) {
             if(ord(substr($str, $i, 1)) > 0xa0) {
                 $tmpstr .= substr($str, $i, 2);
                 $i++;
             } else
                 $tmpstr .= substr($str, $i, 1);
         }
         return $tmpstr;
    } 

    /*
     * 获得组合
     * @param array $arr 数组
     * @param integer 数量
     */
    public static function get_combination($arr, $m, $char='/')
    {
        $result = array();
        if ($m ==1)
        {
           return $arr;
        }
        
        if ($m == count($arr))
        {
            $result[] = implode($char , $arr);
            return $result;
        }
    
        $temp_firstelement = $arr[0];
        unset($arr[0]);
        $arr = array_values($arr);
        $temp_list1 = tool::get_combination($arr, ($m-1), $char);
        
        foreach ($temp_list1 as $s)
        {
            $s = $temp_firstelement.$char.$s;
            $result[] = $s;
        }
        unset($temp_list1);
    
        $temp_list2 = tool::get_combination($arr, $m, $char);
        foreach ($temp_list2 as $s)
        {
            $result[] = $s;
        }    
        unset($temp_list2);
        return $result;
    }    
    
    
    /*
     * 返回英文状态的日期值
     */
    public static function get_week_en($week)
    {
       if (empty($week))
           return FALSE;
       
       $arr = array('Mon'=>'周一', 'Tue'=>'周二', 'Wed'=>'周三', 'Thu'=>'周四', 'Fri'=>'周五', 'Sat'=>'周六', 'Sun'=>'周日');
       $return = '';
       foreach ($arr as $key => $value)
       {
           if ($value == $week)
           {
               $return = $key;
               break;
           }
       }
       return $return;
    }
    
    /*
     * 根据输入的数字返回星期几
     */
    public static function get_cn_week_by_num($num)
    {
       if (empty($num))
           return FALSE;
       
       $arr = array('1'=>'周一', '2'=>'周二', '3'=>'周三', '4'=>'周四', '5'=>'周五', '6'=>'周六', '7'=>'周日');
       if (!empty($arr[$num]))
       {
           return $arr[$num];
       }
       else 
       {
           return FALSE;       
       }
    }    
    
}
