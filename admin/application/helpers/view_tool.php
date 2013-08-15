<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * $Id: myview.php 168 2009-12-21 02:04:12Z hjy $
 * $Author: hjy $
 * $Revision: 168 $
 */

class View_tool_Core {
	
/**
     * 模版中表格项目排序显示
     *
     * @param String $name
     * @param Int $span     表格项目宽度，与css相关,其值有：1 ～ 24
     * @param Int $id       排序数组ID，见相应controller页
     * @return String
     */
    public static function sort($name,$id=-1,$width=100)
    {
		$input = Input::instance();
        $uri = URI::instance();

        if(!is_null($input->get('orderby'))) 
        {
            $orderby=intval($input->get('orderby'));
        }
        elseif(!is_null(cookie::get($uri->segment(1).'_'.$uri->segment(2).'_'.$uri->segment(3).'_orderby')))
        {
            $orderby=intval(cookie::get($uri->segment(1).'_'.$uri->segment(2).'_'.$uri->segment(3).'_orderby'));
        }
        else 
        {
            $orderby=0;
        }

        $_GET['orderby'] = '{orderby}';
        $url = url::base().url::current().'?'.str_replace('%7Borderby%7D', '{orderby}', http_build_query($_GET));

        $html_perfix = ($width > 0)?'<th width="' . $width . '">':'<th>';
        if($id==-1)
        {
            $url    =  $html_perfix . $name . '</th>';
        }
        elseif($orderby==$id)
        {
            $url    = $html_perfix . '<a href="'.str_replace('{orderby}', $orderby+1, $url).'" class="sort_arrow_desc"><span class="sort_title">' . $name . '</span></a></th>';
        }
        elseif($orderby==$id+1)
        {
            $url    = $html_perfix . '<a href="'.str_replace('{orderby}', $orderby-1, $url).'" class="sort_arrow_asc"><span class="sort_title">' . $name . '</span></a></th>';
        }
        else
        {
            $url    = $html_perfix . '<a href="'.str_replace('{orderby}', $id, $url).'">' . $name . '</a></th>';
        }
        // Reset page number
        $_GET['orderby'] = $orderby;
        return $url;
    }
    
    /**
     * 模版中表格项目排序显示
     *
     * @param String $name
     * @param Int $span     表格项目宽度，与css相关,其值有：1 ～ 24
     * @param Int $id       排序数组ID，见相应controller页
     * @return String
     */
    public static function orderby($name,$span_id, $id=-1)
    {
        $input = Input::instance();
        $uri = URI::instance();

        if(!is_null($input->get('orderby'))) 
        {
            $orderby=intval($input->get('orderby'));
        }
        elseif(!is_null(cookie::get($uri->segment(1).'_'.$uri->segment(2).'_'.$uri->segment(3).'_orderby')))
        {
            $orderby=intval(cookie::get($uri->segment(1).'_'.$uri->segment(2).'_'.$uri->segment(3).'_orderby'));
        }
        else 
        {
            $orderby=0;
        }

        $_GET['orderby'] = '{orderby}';
        $url=url::base().url::current().'?'.str_replace('%7Borderby%7D', '{orderby}', http_build_query($_GET));
        if($id==-1)
        {
            $url    = '<div title="'.$name.'" class="span-'.$span_id.' pointer">'.$name.'</div>';
        }
        elseif($orderby==$id)
        {
            $url    = '<div title="'.$name.'" class="span-'.$span_id.' pointer">
                <a href="'.str_replace('{orderby}', $orderby+1, $url).'">'.$name.
                '<img src="'.url::base().'images/arrow-down.gif"></a></div>';
        }
        elseif($orderby==$id+1)
        {
            $url    = '<div title="'.$name.'" class="span-'.$span_id.' pointer">
                <a href="'.str_replace('{orderby}', $orderby-1, $url).'">'.$name.
                '<img src="'.url::base().'images/arrow-up.gif"></a></div>';
        }
        else
        {
            $url    = '<div title="'.$name.'" class="span-'.$span_id.' pointer">
                <a href="'.str_replace('{orderby}', $id, $url).'">'.$name.
                '</a></div>';
        }
        // Reset page number
        $_GET['orderby'] = $orderby;
        return $url;
    }

    /**
     * 排序链接
     * @param string $name          显示名称
     * @param string $order_field   排序段如：id 或 name 等
     * @param int/string $span_id   宽度编号 默认5
     */
    public static function order_str($name,$order_string,$span_id=5){
        $order_string_get   = null;
        $order_field        = substr($order_string, 0, -1);
        $order_sort         = intval(substr($order_string, -1, 1));
        $html_tpl           = '';

        $input              = Input::instance();
        $uri                = URI::instance();
        $get_order          = FALSE;
        $is_arr             = FALSE;

        // 箭头状态
        $arrow = NULL;
        if(!is_null($input->get('order'))) 
        {
            $get_order      = TRUE;
            $order_get      = $input->get('order');
            if(is_array($order_get)){
                $is_arr=TRUE;
                foreach ($order_get as $order_string_tmp){
                    $order_field_tmp        = substr($order_string_tmp, 0, -1);
                    $order_sort_tmp         = intval(substr($order_string_tmp, -1, 1));
                    if($order_field_tmp==$order_field){
                        $order_string_get   = $order_string_tmp;
                        $order_field_get    = substr($order_string_get, 0, -1);
                        $order_sort_get     = intval(substr($order_string_get, -1, 1));
                        // 输出当前排序状态
                        $order_field_get== $order_field && $arrow = $order_sort_get;
                        break;
                    }
                }
            }else{
                $order_string_get       = $order_get;
                $order_field_get        = substr($order_string_get, 0, -1);
                $order_sort_get         = intval(substr($order_string_get, -1, 1));
                // 输出当前排序状态
                $order_field_get== $order_field && $arrow = $order_sort_get;
            }
        }
        //排序链接排除翻页参数
        $url_tpl = preg_replace("/\?page=[\d]\&?/",'?',url::base().url::current(TRUE));
        $url_tpl = preg_replace("/\&page=[\d]\&?/",'&',$url_tpl);
        if($get_order==TRUE){

            if($is_arr==TRUE){
                //请求中有order数组
                //包含当前order_field
                if(!is_null($order_string_get)){
                    // 替换成反向
                    $replace_string = $order_field.($order_sort_get>0?0:1);
                    $url_tpl        = str_ireplace('order[]='.$order_string_get,'order[]='.$replace_string,$url_tpl);
                }else{
                    //不包含当前order_field 直接添加
                    $url_tpl        .=  '&order[]='.$order_string;
                }
            }else{
                // 当前请求的field与欲添加的field不同
                if($order_field_get!=$order_field){
                    $url_tpl        = str_ireplace('order='.$order_string_get,'order='.$order_string,$url_tpl);
                }else{
                    // 替换成反向
                    $replace_string = $order_field.($order_sort_get>0?0:1);
                    $url_tpl        = str_ireplace('order='.$order_string_get,'order='.$replace_string,$url_tpl);
                }
            }

        }else{
            $url_query = parse_url($url_tpl,PHP_URL_QUERY);
            $url_tpl .= (is_null($url_query)?'?':'&').'order'.($is_arr?'[]':'').'='.$order_string;
        }
        $html_tpl .= '<div class="span-'.$span_id.' pointer">';
        $html_tpl .= '<a href="'.$url_tpl.'" title="'.$name.'">'.$name.'</a>';
        if(!is_null($arrow)){
            $html_tpl .= '<img src="'.url::base().'images/arrow-'.($arrow>0?'up':'down').'.gif" />';
        }
        $html_tpl .= '</div>';
        return $html_tpl;
    }

	public static function per_page()
	{
		$per_page = controller_tool::per_page();
		
        $_GET['per_page'] = '{per_page}';
		$_GET['page'] = 1;
        $url=url::base().url::current().'?'.str_replace('%7Bper_page%7D', '{per_page}', http_build_query($_GET));
        // Reset page number
        $_GET['per_page'] = $per_page;
		
		$str = "";
        $str .= '<div class="b_r_view">';
        $str .= '<div class="droping">';
        $str .= '<span onclick="show_foot_per_page();"><img class="imgbundle" src="' . url::base() . 'images/new_arrow_up.gif"> 每页显示 ' . $per_page . ' 条</span>';
        $str .= '<div style="position:relative;">';
        $str .= '<div class="x_drop_menu" style="display:none;" id="footer_per_page">';
		$pagination_arr = Kohana::config('pagination.per_page');
		foreach($pagination_arr as $value)
		{
			if($per_page == $value)
			{
				$str .= '<div class="item"><label><input type="radio" name="per_page_radio[]" checked="checked"> 每页' . $value . '条</label></div>';
			}
			else
			{
				$str .= '<div class="item"><label><input type="radio" name="per_page_radio[]" onclick="self.location.href=\'' . str_replace('{per_page}', $value, $url) . '\';"> 每页' . $value . '条</label></div>';
			}
		}
		$str .= "</div>";
		$str .= "</div>";
		$str .= "</div>";
		$str .= "</div>";
		return $str;
	}

    /**
     * 模版中分页条数处理
     *
     * @return String
     */
    public static function temp_per_page(){
        $input = Input::instance();
        $uri = URI::instance();

        if(!is_null($input->get('per_page'))) {
            $per_page=intval($input->get('per_page'));
        }elseif(!is_null(cookie::get($uri->segment(1).'_'.$uri->segment(2).'_'.$uri->segment(3).'_per_page'))) {
            $per_page=intval(cookie::get($uri->segment(1).'_'.$uri->segment(2).'_'.$uri->segment(3).'_per_page'));
        }else {
            $per_page=Kohana::config('tab.pagination.1');
        }

        $_GET['per_page'] = '{per_page}';
        $url=url::base().url::current().'?'.str_replace('%7Bper_page%7D', '{per_page}', http_build_query($_GET));
        // Reset page number
        $_GET['per_page'] = $per_page;

        $select_per_page='<select name="per_page" onChange="if(this.options[this.selectedIndex].value!=0){self.location.href=this.options[this.selectedIndex].value;}">';
        foreach(Kohana::config('tab.pagination') as $key=>$rs) {
            if($rs==$per_page) {
                $select_per_page.='<option value="'.str_replace('{per_page}', $rs, $url).'" selected>'.$rs.'</option>';
            }else { 
                $select_per_page.='<option value="'.str_replace('{per_page}', $rs, $url).'" >'.$rs.'</option>';
            }
        }
        $select_per_page.='</select>';

        return $select_per_page;
    }
    
    /**
     * get active status image
     * 
     * @param int $active
     * @return String
     */
    public function get_active_img($active = 1,$stat=true,$class='active_img') 
    {
    	$str = "";
		$active_condition = ($stat == true)?1:0;
    	if($active == $active_condition)
    	{
    		$str = "<img src='/images/icon/accept.png' alt='Active' class='" . $class . "'/>";
    	}
    	else
    	{
    		$str = "<img src='/images/icon/cancel.png' alt='Invalid' class='" . $class . "'/>";
    	}
    	return $str;
    }
    
    /**
     * 得到币种格式
     * 
     * @param int $format
     * @return String
     */
    /*
    public function get_price_format($format) 
    {
        $str = "";
        $currency_type   = Kohana::config('product.currency_type');
        if(isset($currency_type[$format]))
        {
            $str = $currency_type[$format];
        }
    	return $str;
    }*/
} // End myview
