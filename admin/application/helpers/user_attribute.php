<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * author zhubin 
 */

class User_attribute_Core {
	/**
	 * 根据注册项信息生成相应的html代码
	 * @param $user_profile array  注册项信息
	 * @param $class  string  css属性
	 * return string
	 */
	public static function show_view($user_attribute, $class=array())
	{
		empty($class) && $class=array(
					       'text'=>'',
					       'select'=>'',
					       'radio'=>'',
					       'checkbox'=>'',
					    ); 
		$type = kohana::config('user_attribute_type.attribute.'.$user_attribute['attribute_type'].'.form');
		$html = '';
	    if(!is_array($type) && !empty($type))
	    {
	        switch($type)
	        {
	            case 'text':
	            	$attribute_type_arr = explode('.', $user_attribute['attribute_type']);
			        if($attribute_type_arr[0] == 'time')
		            {
		                $html .= '<input type="'.$type.'" name="'.$user_attribute['attribute_type'].'"  readonly="true" class="'.$class[$type].'" value="日期选择器"/>';
		            }else if($attribute_type_arr[0] == 'input'){
	                    $html .= '<input type="'.$type.'" name="'.$user_attribute['attribute_type'].'"  readonly="true" class="'.$class[$type].'"/>';
		            }else{
		                $html .= '<input type="'.$type.'" name="'.$user_attribute['attribute_type'].'"  readonly="true" class="'.$class[$type].'"/>';
		            }
	                break;
	            case 'select':
	                $attribute_options = explode(',', trim($user_attribute['attribute_option'], ','));
	                $html .= '<select name="'.$user_attribute['attribute_type'].'"  class="'.$class[$type].'">';
	                foreach($attribute_options as $attribute_option)
	                {
	                    $html .= '<option value="'.$attribute_option.'">'.$attribute_option.'</option>';
	                }
	                $html .= "</select>";

	                break;
	            case 'radio':
	                $attribute_options = explode(',', trim($user_attribute['attribute_option'], ','));
	                foreach($attribute_options as $attribute_option)
	                {
	                    $html .= $attribute_option.' <input type="'.$type.'" name="'.$user_attribute['attribute_type'].'" value="'.$attribute_option.'" class="'.$class[$type].'"/> ';
	                }
	                break;
	            case 'checkbox':
	                $attribute_options = explode(',', trim($user_attribute['attribute_option'], ','));
	                foreach($attribute_options as $attribute_option)
	                {
	                    $html .=$attribute_option.'<input type="'.$type.'" name="'.$user_attribute['attribute_type'].'[]" value="'.$attribute_option.'" class="'.$class[$type].'"/>';
	                }
	                break;
	            default:
	                break;
	        }
	    }else{
	        
	        $html = '';
	        
	    }
	    return $html;
	}
}
