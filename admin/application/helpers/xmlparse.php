<?php defined('SYSPATH') or die('No direct script access.');
/**
 * xml 解析
 */
class xmlparse_Core {
    private static $instance = NULL;

    // 获取单态实例
    public static function get_instance(){
        if(self::$instance === null){
            $classname = __CLASS__;
            self::$instance = new $classname();
        }
        return self::$instance;
    }
    
    
    public function xml2array($xmlstr){
   		$xmlObj = simplexml_load_string($xmlstr);
   		$arrXml = $this->objectsIntoArray($xmlObj);
   		return $arrXml;
    }
    
    
	public  function objectsIntoArray($arrObjData, $arrSkipIndices = array())
	 {
	     $arrData = array();
	     
	     // if input is object, convert into array
	     if (is_object($arrObjData)) {
	         $arrObjData = get_object_vars($arrObjData);
	     }
	     
	     if (is_array($arrObjData)) {
	         foreach ($arrObjData as $index => $value) {
	             if (is_object($value) || is_array($value)) {
	                 $value = $this->objectsIntoArray($value, $arrSkipIndices); // recursive call
	             }
	             if (in_array($index, $arrSkipIndices)) {
	                 continue;
	             }
	             $arrData[$index] = $value;
	         }
	     }
	     return $arrData;
	 }
}