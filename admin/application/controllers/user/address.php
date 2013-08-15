<?php defined('SYSPATH') OR die('No direct access allowed.');

class Address_Controller extends Template_Controller {

    /**
     * 添加用户地址
     */
    function do_add($user_id) {
        //权限验证
        role::check('user_edit');

        if($_POST) {
			$data = $_POST;
            tool::filter_strip_tags($data);			
			$data['user_id'] = $user_id;
			$data['ip'] = tool::get_long_ip();
			$data['date_upd'] = date('Y-m-d H:i:s');
			if(Myaddress::instance()->add($data))
			{
				remind::set(Kohana::lang('o_global.add_success'),request::referrer(),'success');
			}
			else
			{
				remind::set(Kohana::lang('o_global.add_error'),request::referrer(),'error');
			}
        }
    }
    
    /**
     * 修改用户地址
     */
    function do_edit($id) {
        //权限验证
        $site_id_list = role::check('user_edit');
        if(!$id)
		{
            remind::set(Kohana::lang('o_global.bad_request'),'user/address');
        }

        if($_POST) {
			$data = $_POST;
			
            //标签过滤
            tool::filter_strip_tags($data);
			
			$data['date_upd'] = date('Y-m-d H:i:s');
			if(Myaddress::instance($id)->edit($data))
			{
				remind::set(Kohana::lang('o_global.update_success'),request::referrer(),'success');
			}
			else
			{
				remind::set(Kohana::lang('o_global.update_error'),request::referrer(),'error');
			}
        }
    }
    /**
     * author zhubin
     * 删除用户地址
     * @param int 
     */
    public function delete() {
        //权限验证
        role::check('user_edit');
        $address_info = $this->input->get();
        if(empty($address_info) || !isset($address_info['address_id']) || !isset($address_info['user_id']) )
        {
            remind::set(Kohana::lang('o_global.bad_request'),'user/address');
        }

        if(Myaddress::instance()->delete($address_info['address_id']))
        {
            remind::set(Kohana::lang('o_user.delete_user_address_success'),'user/user/edit/'.$address_info['user_id'],'success');
        }
        else
        {
            remind::set(Kohana::lang('o_user.delete_user_address_failed'),'user/user/edit/'.$address_info['user_id'],'error');
        }
    }
    
	/**
     * ajax添加
     */
    function ajax_add() {
        role::check('user_edit');
        $return_struct = array(
			'status' => 0,
			'code'	 => 501,
			'msg'	 => 'Not Implemented'
		);
    	//权限验证
        $countries = array();
		$user_id = intval($this->input->get('user_id'));
		//$countrys = kohana::config('country.default');
		$query_struct = array(
			'where'   => array(
				'active'   => 1
			), 
			'orderby' => array(
				'position'=>'DESC',
				'name'=>'ASC'
			)
		);
		$site_countries = Mycountry::instance()->query_assoc($query_struct);
    	foreach($site_countries as $val)
		{
			$countries[$val['iso_code']] = $val['name'].($val['name_manage']?'-'.$val['name_manage']:'');
		}

		$return_template = $this->template = new View('user/address_add');
		$this->template->user_id = $user_id;
		$this->template->countrys = $countries;
		$return_str = $return_template->render();
		$return_struct['status'] = 1;
		$return_struct['code'] = 200;
		$return_struct['msg'] = 'Success';
		$return_struct['content'] = $return_str;
		exit(json_encode($return_struct));
    }
    
	/**
     * ajax编辑
     */
    function ajax_edit() {
        $return_struct = array(
			'status' => 0,
			'code'	 => 501,
			'msg'	 => 'Not Implemented'
		);
    	
    	//权限验证
        role::check('user_edit');
        $countries = array();

		$id = intval($this->input->get('id'));

        if(!$id)
		{
            $return_struct['code'] = 400;
			$return_struct['msg'] = Kohana::lang('o_global.bad_request');
			exit(json_encode($return_struct));
        }
        
		$address = Myaddress::instance($id)->get();
        
    	$query_struct = array(
			'where'   => array(
				'active'   => 1
			), 
			'orderby' => array(
				'position'=>'DESC',
				'name'=>'ASC'
			)
		);
		$site_countries = Mycountry::instance()->query_assoc($query_struct);
    	foreach($site_countries as $val)
		{
			$countries[$val['iso_code']] = $val['name'];
		}

		$return_template = $this->template = new View('user/address_edit');
		$this->template->address = $address;
		$this->template->countries = $countries;
		$return_str = $return_template->render();
		$return_struct['status'] = 1;
		$return_struct['code'] = 200;
		$return_struct['msg'] = 'Success';
		$return_struct['content'] = $return_str;
		exit(json_encode($return_struct));
    }
}
