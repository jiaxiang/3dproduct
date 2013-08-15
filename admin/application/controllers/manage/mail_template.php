<?php defined('SYSPATH') OR die('No direct access allowed.');

class Mail_template_Controller extends Template_Controller {
	public $site_ids;

	public function __construct()
	{
		parent::__construct();
		role::check('manage_mail_template');
	}
    
	/**
	 * 列表
	 */
	public function index()
	{
		$query_struct = array();

		/**
		 * 搜索
		 */
		$search_value = $this->input->get('search_value');
		if($search_value)
		{
			$query_struct['where']['mail_category_id'] = $search_value;
		}
		//调用分页
		$per_page    = controller_tool::per_page();
		$this->pagination = new Pagination(
			array(
				'total_items'    => Mymail_template::instance()->count($query_struct),
				'items_per_page' => $per_page,
			)
		);
		$mail_templates = Mymail_template::instance()->mail_templates($query_struct,array('id'=>'DESC'),$per_page,$this->pagination->sql_offset);
		foreach($mail_templates as $k=>$v)
		{
			foreach($v as $key=>$value)
			{
				if(!is_numeric($value) && empty($value))
				{
					$mail_templates[$k][$key] = 'NULL';
				}
			}
			$mail_templates[$k]['content_small'] = strip_tags(text::limit_words($v['content'],30));
			$mail_templates[$k]['active_img'] = view_tool::get_active_img($v['active']);
		}
		$mail_categories = Mymail_category::instance()->mail_categories();

		$this->template->content = new View("manage/mail_template_list");
		$this->template->content->mail_templates = $mail_templates;
		$this->template->content->mail_categories = $mail_categories;
	}

	/**
	 * 添加新模板
	 */
	public function add()
	{
        if($_POST) {
            //标签过滤
            tool::filter_strip_tags($_POST);
        	
            if(Mymail_template::instance()->add($_POST)) {
                remind::set(Kohana::lang('o_global.add_success'),'manage/mail_template/add','success');
            }else {
                remind::set(Kohana::lang('o_global.add_error'),'manage/mail_template/add');
            }
        }
		$mail_categories = Mymail_category::instance()->mail_categories();

		$this->template->content = new View("manage/mail_template_add");
		$this->template->content->mail_categories = $mail_categories;
	}

	/**
	 * 模块编辑
	 */
	public function edit($id)
    {
		$mail_template = Mymail_template::instance($id)->get();
		if(!$mail_template['id'])
		{
			remind::set(Kohana::lang('o_manage.template_not_exist'),'manage/mail_template');
		}

        if($_POST) {
            //标签过滤
            tool::filter_strip_tags($_POST);
        	
            if(Mymail_template::instance($id)->edit($_POST)) {
                remind::set(Kohana::lang('o_global.update_success'),'manage/mail_template','success');
            }else {
                remind::set(Kohana::lang('o_global.update_error'),'manage/mail_template');
            }
        }

		$mail_categories = Mymail_category::instance()->mail_categories();
		foreach($mail_categories as $key=>$value)
		{
			if($value['id'] == $mail_template['mail_category_id'])
			{
				$mail_categories[$key]['selected'] = 'selected';
			}
			else
			{
				$mail_categories[$key]['selected'] = '';
			}
		}

		$this->template->content = new View("manage/mail_template_edit");
		$this->template->content->data = $mail_template;
		$this->template->content->mail_categories = $mail_categories;
	}

	/**
	 * ajax get mail_template content
	 */
	public function ajax_content()
	{
		$return_struct = array(
			'status' => 0,
			'code'	 => 501,
			'msg'	 => 'Not Implemented'
		);
		if(request::is_ajax()) {
			$id = intval($this->input->get('id'));

			$mail_template = Mymail_template::instance($id)->get();

			$return_template = $this->template = new View('template_blank');
			$this->template->content = $mail_template['content'];
			$return_str = $return_template->render();
			$return_struct['status'] = 1;
			$return_struct['code'] = 200;
			$return_struct['msg'] = 'Success';
			$return_struct['content'] = $return_str;
			exit(json_encode($return_struct));
		}
	}

	/**
	 * delete mail template
	 */
	public function delete($id)
	{
		if(Mymail_template::instance($id)->delete()) {
			remind::set(Kohana::lang('o_global.delete_success'),'manage/mail_template','success');
		}else {
			$error = Mymail_template::instance($id)->error();
			remind::set(Kohana::lang('o_global.delete_error') . $error,'manage/mail_template');
		}
	}
    
}
