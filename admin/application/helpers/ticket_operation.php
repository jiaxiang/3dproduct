<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 彩票操作
 */
class ticket_operation_Core {
    private static $instance = NULL;
    
    static public $zhushufenpei = array(
    	'1' 	=> array(1,0,0,0,0,0,0,0),
    	'21' 	=> array(0,1,0,0,0,0,0,0),
    	'31' 	=> array(0,0,1,0,0,0,0,0),
    	'41' 	=> array(0,0,0,1,0,0,0,0),
    	'51' 	=> array(0,0,0,0,1,0,0,0),
    	'61' 	=> array(0,0,0,0,0,1,0,0),
    	'71' 	=> array(0,0,0,0,0,0,1,0),
    	'81' 	=> array(0,0,0,0,0,0,0,1),
    	'23' 	=> array(2,1,0,0,0,0,0,0),
    	'36' 	=> array(3,3,0,0,0,0,0,0),
    	'37' 	=> array(3,3,1,0,0,0,0,0),
    	'410' 	=> array(4,6,0,0,0,0,0,0),
    	'414' 	=> array(4,6,4,0,0,0,0,0),
    	'415' 	=> array(4,6,4,1,0,0,0,0),
    	'515'	=> array(5,10,0,0,0,0,0,0),
    	'525' 	=> array(5,10,10,0,0,0,0,0),
    	'530' 	=> array(5,10,10,5,0,0,0,0),
    	'531' 	=> array(5,10,10,5,1,0,0,0),
    	'621' 	=> array(6,15,0,0,0,0,0,0),
    	'641' 	=> array(6,15,20,0,0,0,0,0),
    	'656' 	=> array(6,15,20,15,0,0,0,0),
    	'662' 	=> array(6,15,20,15,6,0,0,0),
    	'663' 	=> array(6,15,20,15,6,1,0),
    	'7127' 	=> array(7,21,35,35,21,7,1,0),
    	'8255' 	=> array(8,28,56,70,56,28,8,1),
    	'33' 	=> array(0,3,0,0,0,0,0,0),
    	'34' 	=> array(0,3,1,0,0,0,0,0),
    	'46' 	=> array(0,6,0,0,0,0,0,0),
    	'411' 	=> array(0,6,4,1,0,0,0,0),
    	'510' 	=> array(0,10,0,0,0,0,0,0),
    	'520' 	=> array(0,10,10,0,0,0,0,0),
    	'526' 	=> array(0,10,10,5,1,0,0,0),
    	'615' 	=> array(0,15,0,0,0,0,0,0),
    	'635' 	=> array(0,15,20,0,0,0,0,0),
    	'650' 	=> array(0,15,20,15,0,0,0,0),
    	'657' 	=> array(0,15,20,15,6,1,0,0),
    	'7120' 	=> array(0,21,35,35,21,7,1,0),
    	'8247' 	=> array(0,28,56,70,56,28,8,1),
    	'44' 	=> array(0,0,4,0,0,0,0,0),
    	'45' 	=> array(0,0,4,1,0,0,0,0),
    	'516' 	=> array(0,0,10,5,1,0,0,0),
    	'620' 	=> array(0,0,20,0,0,0,0,0),
    	'642' 	=> array(0,0,20,15,6,1,0,0),
    	'55' 	=> array(0,0,0,5,0,0,0,0),
    	'56' 	=> array(0,0,0,5,1,0,0,0),
    	'622' 	=> array(0,0,0,15,6,1,0,0),
    	'735' 	=> array(0,0,0,35,0,0,0,0),
    	'870' 	=> array(0,0,0,70,0,0,0,0),
    	'66' 	=> array(0,0,0,0,6,0,0,0),
    	'67' 	=> array(0,0,0,0,6,1,0,0),
    	'721' 	=> array(0,0,0,0,21,0,0,0),
    	'856' 	=> array(0,0,0,0,56,0,0,0),
    	'77' 	=> array(0,0,0,0,0,7,0,0),
    	'78' 	=> array(0,0,0,0,0,7,1,0),
    	'828' 	=> array(0,0,0,0,0,28,0,0),
    	'88' 	=> array(0,0,0,0,0,0,8,0),
    	'89' 	=> array(0,0,0,0,0,0,8,1),
    	'91'	=> array(0,0,0,0,0,0,0,0,1),
    	'101'	=> array(0,0,0,0,0,0,0,0,0,1),
    	'111'	=> array(0,0,0,0,0,0,0,0,0,0,1),
    	'121'	=> array(0,0,0,0,0,0,0,0,0,0,0,1),
    	'131'	=> array(0,0,0,0,0,0,0,0,0,0,0,0,1),
    	'141'	=> array(0,0,0,0,0,0,0,0,0,0,0,0,0,1),
    	'151'	=> array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,1),
    	
    );
    
    
    // 获取单态实例
    public static function get_instance(){
        if(self::$instance === null){
            $classname = __CLASS__;
            self::$instance = new $classname();
        }
        return self::$instance;
    }
    
    /**
     * 计算出彩票的注数 适用于竞彩部分
     * Enter description here ...
     * @param unknown_type $code
     * codes:46:[1,2]/47:[胜]/48:[胜]/49:[胜]
     * @param unknown_type $chuanfa
     * 2串1
     */
    public function zhushu($codes, $chuanfa) {
    	$arrcode = explode('/', $codes);
    	if ($chuanfa == '单关') {
    		$chuan_code = 1;
    	}
    	else {
    		$chuans = explode('串', $chuanfa);
    		$chuan_code = $chuans[0].$chuans[1];
    	}
    	
    	
    	$zhushu_info = self::$zhushufenpei[$chuan_code];
    	$return = 0;
    	for ($i = 0; $i < count($zhushu_info); $i++) {
    		if ($zhushu_info[$i] > 0) {
    			$j = $i + 1;
    			$r = tool::get_combination($arrcode, $j, '/');
    			
    			for ($k = 0; $k < count($r); $k++) {
    				$code_t1 = explode('/', $r[$k]);
    				$match_re = 1;
    				for ($l = 0; $l < count($code_t1); $l++) {
    					$t1 = explode(':', $code_t1[$l]);
						$match_no = $t1[0];
						$no_len = strlen($match_no)+2;
						$t2 = substr(substr($code_t1[$l], $no_len), 0, -1);
						$t3 = explode(',', $t2);
						$match_re *= count($t3);
						
    				}
    				$return += $match_re;
    			}
    		}
    	}
    	
    	return $return;
    	
    }
    
    
    /*
     * 转化彩票
     */
    public function change_code_jclq($arrcode, $arrtype, $maxcode = 0, $special_num = NULL)
    {
    	return change_code_jczq($arrcode, $arrtype, $maxcode = 0, $special_num);
    }
    public function change_code_jczq($arrcode, $arrtype, $maxcode = 0, $special_num = NULL)
    {
        $maxcode = intval($maxcode);
        $rules = Kohana::config('rule_mxn.jczq');
        $match_count = count($arrcode);
        
        //错误处理
        if (empty($rules[$match_count]))
            return FALSE;
        
        if ($maxcode > 0 && $match_count > $maxcode)
            return  FALSE;      

        //当胆码不为空时
        if (!empty($special_num))
            return FALSE;
        //d($arrcode, FALSE);
        //d($arrtype, FALSE);

        $changeto = '';
        foreach ($rules[$match_count] as $key => $rs)
        {
            //d('key:'.$key, FALSE);
            
            $checkcount = 0;
            $rowcount = 0;
            foreach ($rs as $keysub => $value)
            {
                if ($value > 0)
                {
                    $rowcount++;
                    if (in_array($keysub, $arrtype))
                    {
                        $checkcount++;
                    }
                }
            }
            
            //d('num:'.$checkcount, FALSE);
            
            if ($checkcount == count($arrtype) && $checkcount == $rowcount)
            {
                $changeto = $match_count.'串'.$key;
                break;
            }
        }

        //d($changeto, FALSE);
        
        if (!empty($changeto))
        {
            $arrtype = array($changeto);
        }
    }
    
	/*
     * 转化彩票，北京单场
     */
    public function change_code_bjdc($arrcode, $arrtype, $maxcode = 0)
    {
        $maxcode = intval($maxcode);
        $rules = Kohana::config('rule_mxn.bjdc');
        $match_count = count($arrcode);
        
        if (empty($rules[$match_count]))
            return FALSE;
        
        if ($maxcode > 0 && $match_count > $maxcode)
            return  FALSE;      
         
        //d($arrcode, FALSE);
        //d($arrtype, FALSE);
        $changeto = '';
        foreach ($rules[$match_count] as $key => $rs)
        {
            //d('key:'.$key, FALSE);
            $checkcount = 0;
            $rowcount = 0;
            foreach ($rs as $keysub => $value)
            {
                if ($value > 0)
                {
                    $rowcount++;
                    if (in_array($keysub, $arrtype))
                    {
                        $checkcount++;
                    }
                }
            }
            //d('num:'.$checkcount, FALSE);
            if ($checkcount == count($arrtype) && $checkcount == $rowcount)
            {
                $changeto = $match_count.'串'.$key;
                break;
            }
        }
        //d($changeto, FALSE);
        if (!empty($changeto))
        {
            $arrtype = array($changeto);
        }
    }

}