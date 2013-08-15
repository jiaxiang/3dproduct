<?php defined('SYSPATH') or die('No direct script access.');

class Tool_Core
{
	/*
	 * 64们机和32位机兼容的ip2long
	 */
	public static function myip2long($strIP)
	{
		$longIP=ip2long($strIP);
		if ($longIP < 0){
			$longIP += 4294967296;
		}
		return $longIP;
	}

	/*
	 * 得到服务器上的IP
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
		$ip     = Input::instance()->ip_address();
		return self::myip2long($ip);
	}


	/*
	 * 根据IP得到商业数据库里面的IP详情
	 */
	public static function get_ip_country($ip)
	{
		$ip_arr = array();
		$ip_arr = @unserialize(stripcslashes(@file_get_contents("http://ip.backstage-gateway.com/ip?ip=$ip")));
		if(isset($ip_arr['country']) && !empty($ip_arr['city']))
		{
			return '('.$ip_arr['country'].')';
		}
		else
		{
			return NULL;
		}
	}

	/*
	 * curl模拟post
	 */
	public static function curl_pay($API_Endpoint,$nvpStr)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$API_Endpoint);
		curl_setopt($ch, CURLOPT_VERBOSE, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch,CURLOPT_POSTFIELDS,$nvpStr);
		$response = curl_exec($ch);
		if(curl_errno($ch)) {
			$curl_error_no 	=curl_errno($ch) ;
			$curl_error_msg	=curl_error($ch);
		}else {
			curl_close($ch);
		}
		return $response;
	}


	/*
	 * 安全过滤字符串,在添加入数据训之前用
	 */
	public static function filter_keywords($keywords)
	{
		$keyowrds			= strip_tags($keywords);

		$search				= array('http://','www.','www');
		$keyowrds			= str_replace($search,"",$keywords);
		return trim($keyowrds);
	}

	/*
	 *tiffany解密链接
	 */
	public static function query_decode($sEncode){
		if(strlen($sEncode)==0){
			return '';
		}else{
			$s_tem = strrev($sEncode);
			$s_tem = base64_decode($s_tem);
			$s_tem = rawurldecode($s_tem);
			$vcode=substr($s_tem,6,7);
			$s_tem=substr($s_tem,14);
			$a_tem = explode('&', $s_tem);
			$hash='id8ap';
			$verifyCode='';
			foreach($a_tem as $rs){
				$verifyCode.=$hash.$rs;
			}
			$verifyCode=substr(md5($verifyCode),3,7);
			if($verifyCode==$vcode){
				return $s_tem;
			}else{
				return '';
			}
		}
	}
    
    /* 
    Utf-8、gb2312都支持的汉字截取函数 
    cut_str(字符串, 截取长度, 开始长度, 编码); 
    编码默认为 utf-8 
    开始长度默认为 0 
    */ 
    function my_substr($string, $sublen, $start = 0, $code = 'UTF-8'){ 
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
    function substring($str, $start, $len){
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

	/**
	 * 根据键值求二维数组的交集
	 *
	 * @param <Array> $array1
	 * @param <Array> $array2
	 *
	 * @return Array
	 */
	function array_common($array1,$array2,$compare_string = 'id') 
	{
		if (!is_array($array1) || !is_array($array2))
		{
			return false;
		}
		$compare_arr = array();
		foreach($array1 as $value)
		{
			$compare_arr[] = $value[$compare_string];
		}

		$arr_result = array();
		foreach ($array2 as $value)
		{
			if(in_array($value[$compare_string],$compare_arr))
			{
				$arr_result[] = $value;
			}
		}
		return $arr_result;
	}

	/**
	 * 二维数组相差，根据ID
	 * @param <Array> $source_array
	 * @param <Array> $target_array
	 *
	 * @return Array
	 */
	public static function my_array_diff($source_array = array(),$target_array = array())
	{
		if(count($target_array))
		{
			$id_arr = array();
			$result_arr = array();

			foreach($target_array as $key=>$value)
			{
				$id_arr[] = $value['id'];
			}

			foreach($source_array as $key=>$value)
			{
				if(!in_array($value['id'],$id_arr))
				{
					$result_arr[] = $value;
				}
			}
			return $result_arr;
		}
		else
		{
			return $source_array;
		}
	}
	
	/**
	 * 数据库当前时间格式
	 */
	public static function db_date()
	{
		return date("Y-m-d H:i:s",time());
	}

	/**
	 * 简化输出数组
	 * @param $item
	 * @param $key
	 * @param $requestkeys
	 * @example @array_walk($result_arr,'tool::simplify_return_array',$requestkeys);
	 */
	public static function simplify_return_array(&$item,$key,$requestkeys){
		$diffkeys = array_diff(array_keys($item),$requestkeys);
		foreach ($diffkeys as $diffkey){
			unset($item[$diffkey]);
		}
	}

	/**
	 * 过滤数组，保留指定键值列表
	 * @param array $array
	 * @param array $requestkeys
	 * @example tool::filter_keys（$array,$requestkeys);
	 */
	public static function filter_keys(&$array,$requestkeys){
		foreach ($array as $key=>$val){
			if(!in_array($key,$requestkeys)){
				unset($array[$key]);
			}
		}
	}

	/**
	 * 多维数组合并
	 */
	public static function multimerge ($array1, $array2) 
	{
		
		if (is_array($array2) && count($array2)) 
		{
			
			foreach ($array2 as $k => $v) 
			{
				
				if (is_array($v) && count($v)) 
				{
					if(isset($array1[$k]))
					{
						$array1[$k] = self::multimerge($array1[$k], $v);
					}
					else
					{
						$array1[$k] = $v;
					}
				} 
				else
			   	{
						$array1[$k] = $v;
				}
			}
		} 
		else 
		{
			$array1 = $array2;
		}
		return $array1;
	}

	/**
	 * 获取文件后缀名函数
	 */
	public function fileext($filename)
	{
		return substr(strrchr($filename,'.'),1);
	}

	/**
	 * 判断referrer和current_url是否相关，防止循环转向
	 */
	public function referrer_url()
	{
		$referrer = request::referrer();
		$current_url = url::current();
		if(strlen($current_url) > $referrer)
		{
			return url::base();
		}
		if(substr($referrer,-1,1) == '/')
		{
			$referrer = substr($referrer,0,-1);
		}
		if(substr($current_url,-1,1) == '/')
		{
			$current_url = substr($current_url,0,-1);
		}
		if($current_url == substr($referrer,-1,strlen($current_url)))
		{
			return url::base();
		}
		else
		{
			return $referrer;	
		}
	}
	
	/*
	 * 生成合法的uri_name
	 * @param string $uri_name
	 * @return string
	 */
	public static function create_uri_name($uri_name)
	{
	    $uri_name = trim($uri_name);

	    /* URL全部使用小写字符 */
	    $uri_name = strtolower($uri_name);

	    /* 特殊处理 */
	    $uri_name = str_ireplace('(','-',$uri_name);
	    $uri_name = str_ireplace(')','',$uri_name);
	    $uri_name = str_ireplace('\'','',$uri_name);
	    $uri_name = str_ireplace('"','',$uri_name);

	    /* 字母、数字和下划线外的都使用横杠代替 */
	    $uri_name = preg_replace('/[^\w]/','-',$uri_name);
	    $uri_name = preg_replace('/-+/','-',$uri_name);
	    return $uri_name;
	}
	
	/**
     * author zhubin 
     * 对数组进行循环去除标签
     * @param array $arr 需要过滤的数组
     * @param array $nfilter 不需要过滤的键名
     */
    public static function filter_strip_tags(&$arr,$nfilter=array('content'))
    {
       if(!is_array($arr))
       {
            $arr = strip_tags($arr);
       }else {
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
	 * 根据时间格式得到当前的本地时间
	 */
	public static function get_date()
	{
		return tool::db_date();
	}
	
	
    /*
     * 获得组合
     * @param array $arr 数组
     * @param integer 数量
     */
    public static function get_combination($arr, $m, $char=',')
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
	
	
}
