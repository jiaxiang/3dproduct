<?php defined('SYSPATH') OR die('No direct access allowed.');

class Mail_category_Controller extends Template_Controller {
	public $site_ids;

	public function __construct()
	{
		parent::__construct();
		role::check('manage_mail_category');
	}
    
	/**
	 * 列表
	 */
	public function index() {

		$this->template->content = new View("manage/mail_category_list");

		$mail_categories = Mymail_category::instance()->mail_categories(array(),array('id'=>'DESC'));
		foreach($mail_categories as $key=>$value)
		{
			$mail_categories[$key]['active_img'] = view_tool::get_active_img($value['active']);
		}

		$this->template->content->mail_categories = $mail_categories;
	}

	/**
	 * 添加新模块
	 */
	public function add()
	{
		if($_POST) {
            //标签过滤
            tool::filter_strip_tags($_POST);
            
			$flag = $this->input->post('flag');
			$data = Mymail_category::instance()->get_by_flag($flag);
			if($data['id'])
			{
				remind::set(Kohana::lang('o_manage.category_mark_exist'),'manage/mail_category/add');
			}

			if(Mymail_category::instance()->add($_POST)) {
				remind::set(Kohana::lang('o_global.add_success'),'manage/mail_category','success');
			}else {
				remind::set(Kohana::lang('o_global.add_error'),'manage/mail_category/add');
			}
		}

		$this->template->content = new View("manage/mail_category_add");
	}

	/**
	 * 模块编辑
	 */
	public function edit($id)
	{
		$mail_category = Mymail_category::instance($id)->get();
		if(!$mail_category['id'])
		{
			remind::set(Kohana::lang('o_manage.category_not_exist'),'manage/mail_category');
		}

		if($_POST) {
            //标签过滤
            tool::filter_strip_tags($_POST);
			
			$flag = $this->input->post('flag');
			$data = Mymail_category::instance()->get_by_flag($flag);
			if($flag <> $mail_category['flag'])
			{
				if($data['id'])
				{
					remind::set(Kohana::lang('o_manage.category_mark_exist'),'manage/mail_category/add');
				}
			}

			if(Mymail_category::instance($id)->edit($_POST)) {
				remind::set(Kohana::lang('o_global.update_success'),'manage/mail_category','success');
			}else {
				remind::set(Kohana::lang('o_global.update_error'),'manage/mail_category');
			}
		}
		$this->template->content = new View("manage/mail_category_edit");
		$this->template->content->data = $mail_category;
	}

	/**
	 * delete mail_category
	 */
	public function delete($id)
	{
		if(Mymail_category::instance($id)->delete()) {
			remind::set(Kohana::lang('o_global.delete_success'),'manage/mail_category','success');
		}else {
			$error = Mymail_category::instance($id)->error();
			remind::set(Kohana::lang('o_global.delete_error') . $error,'manage/mail_category');
		}
	}
	

}