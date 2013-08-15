<?php defined('SYSPATH') or die('No direct script access.');

class Kc_browser_Core
{
    public static function get_agent_detail()
    {
        $result = array();
        $version = '';
        $major_version = '';
        $minor_version = '';
        $browser_type = '';
        $agent = $_SERVER['HTTP_USER_AGENT'];
        $preparens = '';
        $parens = '';
        $left_position = strpos($agent, '(');
        if($left_position >= 0)
        {
            $preparens = trim(substr($agent, 0, $left_position));
            $parens = substr($agent, $left_position+1, strlen($agent));
            if(strpos($parens, ')') >=0)
            {
                $parens = substr($parens, 0, strpos($parens, ')'));
            } 
        }else{
            $preparens = $agent;
        }
        $browser_version = $preparens;
        $token = trim(strtok($parens, ';'));
        while($token){
            if(preg_match('/MSIE/i', $token) || preg_match('/Opera/i', $token))
            {
                $browser_version = $token;
            }
            $token = strtok(';');
        }
        $msie_index = strpos($browser_version, 'MSIE');
        if($msie_index >= 0)
        {
            $browser_version = substr($browser_version, $msie_index, strlen($browser_version));
        }
        $leftover = '';
        if(substr($browser_version, 0, strlen('Mozilla')) == 'Mozilla')
        {
            $browser_type = 'Netscape';
            $leftover = substr($browser_version, strlen('Mozilla')+1, strlen($browser_version));
        }elseif(substr($browser_version, 0, strlen('Lynx')) == 'Lynx'){
            $browser_type = 'Lynx';
            $leftover = substr($browser_version, strlen('Lynx')+1, strlen($browser_version));
        }elseif(substr($browser_version, 0, strlen('MSIE')) == 'MSIE'){
            $browser_type = 'IE';
            $leftover = substr($browser_version, strlen('MSIE')+1, strlen($browser_version));
        }elseif(substr($browser_version, 0, strlen('Microsoft Internet Explorer')) 
            == 'Microsoft Internet Explorer'){
                $browser_type = 'IE';
                $leftover = substr($browser_version, strlen('Microsoft Internet Explorer')+1, strlen($browser_version));
            }elseif(substr($browser_version, 0, strlen('Opera')) == 'Opera'){
                $browser_type = 'Opera';
                $leftover = substr($browser_version, strlen('Opera')+1, strlen($browser_version));
            }
        $leftover = trim($leftover);
        $i = strpos($leftover, ' ');
        if($i > 0)
        {
            $version = substr($leftover, 0, $i);
        }else{
            $version = $leftover;
        }
        $j = strpos($version, '.');
        if($j >= 0)
        {
            $major_version = substr($version, 0, $j);
            $minor_version = substr($version, $j+1, strlen($version));
        }else{
            $major_version = $version;
        }
        $result['agent_detail'] = $agent;
        $result['version'] = $browser_version;
        $result['major_version'] = $major_version;
        $result['minor_version'] = $minor_version;
        $result['type'] = $browser_type;
        $result['ip'] = tool::get_long_ip();
        return $result;
    }
}
