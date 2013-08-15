<?php defined('SYSPATH') or die('No direct script access.');

class Remind_Core
{
	/*
	 * $data   string or array
	 * $url    string
	 * $type   'error','success','notice'
	 */
	public static function set($data,$url=NULL,$type='error')
	{
		$messages = array();
		if ( ! is_array($data))
		{
			$messages[] = $data;
		}
		else
		{
			$messages = $data;
		}
		$remind_message = '';
		foreach($messages as $message){
			$remind_message .= '<p>'.$message.'</p>';
		}
		$types = array('error','success','notice');
		if(!in_array($type,$types))
		{
			$type = 'error';
		}
		$session = Session::instance();
		//$session->set_flash('remind_'.$type, '<div class="'.$type.'">'.$remind_message.'</div>');
		$session->set_flash('remind_'.$type,$remind_message);
		$session->set_flash('remind_type',$type);
		if($url)
		{
            //$url = ltrim($url,url::base());
            url::redirect($url);
        }
    }

    public static function get()
    {
        $session = Session::instance();
        $types = array('error','success','notice');
        $message = '';
        $type = $session->get('remind_type');
        $return_html = '';
        if($type == 'error')
        {
        	$return_html = '<div class="ui-state-error ui-corner-all">
        					<p><span style="float: left; margin-right: 0.3em;" class="ui-icon ui-icon-alert"></span>';
        }
        else
        {
        	$return_html = '<div class="ui-state-highlight ui-corner-all">
        					<p><span style="float: left; margin-right: 0.3em;" class="ui-icon ui-icon-info"></span>';
        }
        foreach($types as $type){
            $message .=$session->get('remind_'.$type);
        }
        $return_html .= $message . '</p></div>';
        //$display = "block";
        //empty($message) && $display = "none";
		//return '<div id="tip" >'.$message.'</div>';
        //return '<div id="tip" style="display:'.$display.';">'.$message.'<img src="/images/icon_close.gif" style="cursor: pointer;position:absolute;top:2px;right:2px;" id="icon_close" onclick="javascript:$(this).parent().hide();"/></div>';
        if(!empty($message))
        {
        	return $return_html;
        }
        else
        {
        	return null;
        }
    }

	public static function get_message()
	{
        $session = Session::instance();
        $types = array('error','success','notice');
        $message = '';
        foreach($types as $type){
            $message .=$session->get('remind_'.$type);
        }
		return $message;
	}
	
	/**
	 * 无记录提示
	 */
	public static function no_rows()
	{
		$return_html = '';
        $return_html .= '<div class="ui-widget">';
        $return_html .= '<div style="padding: 0pt 0.7em;" class="ui-state-error ui-corner-all">';
        $return_html .= '<p><span style="float: left; margin-right: 0.3em;" class="ui-icon ui-icon-alert"></span>';
        $return_html .= '<strong>' . Kohana::lang('o_global.no_rows') . '</strong></p>';
        $return_html .= '</div>';
        $return_html .= '</div>';
        return $return_html;
	}
}
?>
