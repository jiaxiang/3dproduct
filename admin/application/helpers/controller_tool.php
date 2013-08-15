<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * $Id: myview.php 168 2009-12-21 02:04:12Z hjy $
 * $Author: hjy $
 * $Revision: 168 $
 */

class Controller_tool_Core {

	/**
	 *  //列表排序   调用样例见order/order.php
	 $orderby_arr= array
	 (
		 0   => array('order_num'=>'ASC'),
				1   => array('order_num'=>'DESC'),
				2   => array('site_id'=>'ASC'),
				3   => array('site_id'=>'DESC'),
				4   => array('email'=>'ASC'),
				5   => array('email'=>'DESC'),
				6   => array('total_real'=>'ASC'),
				7   => array('total_real'=>'DESC'),
				8   => array('order_status_id'=>'ASC'),
				9   => array('order_status_id'=>'DESC'),
				10   => array('date_add'=>'ASC'),
				11   => array('date_add'=>'DESC'),
				12   => array('date_pay'=>'ASC'),
				13   => array('date_pay'=>'DESC'),
			);

	$orderby    = controller_tool::orderby($orderby_arr);
	 */
	public static function orderby($orderby_arr = NULL)
	{
		$input = Input::instance();
		$uri = URI::instance();
		$segment = $uri->segment(1).'_'.$uri->segment(2).'_'.$uri->segment(3);

		if(!is_null($input->get('orderby')))
		{
			$orderby=intval($input->get('orderby'));
			cookie::set($segment.'_orderby',$orderby);
		}
		elseif(!is_null(cookie::get($segment.'_orderby')))
		{
			$orderby=intval(cookie::get($segment.'_orderby'));
		}
		else
		{
			$orderby=0;
		}
		if(isset($orderby_arr[$orderby]))
		{
			$orderby    = $orderby_arr[$orderby];
		}
		else
		{
			$orderby	= $orderby_arr[0];
		}
		return $orderby;
	}

	/**
	 * 每页显示条数   调用样例见order/order.php
	 $pagination_arr = array
	 (
		 0   => 20,
		 1   => 50,
		 2   => 100,
		 3   => 300,
	 );
	$per_page    = controller_tool::per_page($pagination_arr);
	或者
		$per_page    = controller_tool::per_page();
	 */

	public static function per_page($pagination_arr = NULL){
		$input = Input::instance();
		$uri = URI::instance();
		$segment = $uri->segment(1).'_'.$uri->segment(2).'_'.$uri->segment(3);

		//不带参数时给个默认值
		if(empty($pagination_arr))
		{
			$pagination_arr = Kohana::config('pagination.per_page');
		}

		if(!is_null($input->get('per_page')))
		{
			$per_page=intval($input->get('per_page'));
			cookie::set($segment.'_per_page',$per_page);
		}elseif(!is_null(cookie::get($segment.'_per_page')))
		{
			$per_page=intval(cookie::get($segment.'_per_page'));
		}else {
			$per_page=$pagination_arr[0];
		}
		return $per_page;
	}

	/**
	 * 请求分页处理
     *
     * 2010-04-21
     */
	public static function request_per_page(&$request_struct_current,&$request_data){
        $preset_perpages = Kohana::config('pagination.per_page');
        $uri = URI::instance();
		$segment = $uri->segment(1).'_'.$uri->segment(2).'_'.$uri->segment(3);
        if(isset($request_data['per_page']) && !empty($request_data['per_page']) && in_array($request_data['per_page'],$preset_perpages))
        {
            $request_struct_current['limit']['per_page'] = $request_data['per_page'];
        }elseif(!is_null(cookie::get($segment.'_per_page')))
		{
			$request_struct_current['limit']['per_page'] = intval(cookie::get($segment.'_per_page'));
		}else{
			$request_struct_current['limit']['per_page'] = $preset_perpages[0];
		}
        /*
        $request_data['per_page']	= $request_struct_current['limit']['per_page'];
        if(isset($request_data['page']) && !empty($request_data['page']) && is_numeric($request_data['page']) && $request_data['page']>0)
        {
            $request_struct_current['limit']['page']        = $request_data['page'];
        }
        $request_data['page']		= $request_struct_current['limit']['page'];
         */
	}

	/**
	 * 请求站点处理
     *
     * 2010-04-21
     */
    public static function request_site(&$request_struct_current,&$request_data,$site_id_list){
        if(isset($request_data['site_id']) && intval($request_data['site_id'])>0)
        {
            $request_struct_current['where']['site_id']     = intval($request_data['site_id']);
            $request_data['site_id']						= $request_struct_current['where']['site_id'];
        }
        else
        {
			$site_id = site::id();
			if($site_id>0)
			{
				$request_struct_current['where']['site_id']     = $site_id;
				$request_data['site_id']						= $site_id;
			}
			else
			{
				$request_struct_current['where']['site_id']     = $site_id_list;
				$request_data['site_id']						= -1;
			}
        }
    }

	/**
	 * 请求排序处理
     *
     * 2010-04-21
     */
    public static function request_orderby(&$request_struct_current,&$request_data){
        // 排序处理 
        if(isset($request_data['order']) && !empty($request_data['order']))
        {
            // 如果查询请求中包含了排序条件则覆盖当前的默认排序请求
            $order_string               = trim($request_data['order']);
            $order_field                = substr($order_string, 0, -1);
            // 预设0为ASC,1为DESC
            $order_sort = intval(substr($order_string, -1, 1));
            $request_struct_current['orderby']		= array();
            $request_struct_current['orderby'][$order_field]= ($order_sort==0) ? 'ASC':'DESC';
        }
        /*
        $request_data['order'] = array();
        if(count($request_struct_current['orderby'])==1)
        {
            foreach($request_struct_current['orderby'] as $ord_field=>$ord_sort)
            {
                $request_data['order'] = $ord_field.($ord_sort=='ASC'?0:1);
            }
        }
         */
    }


    

} // End myview
