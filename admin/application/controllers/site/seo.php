<?php defined('SYSPATH') OR die('No direct access allowed.');

class Seo_Controller extends Template_Controller {
	protected $current_flow = 'seo';

	public function __construct()
	{
		parent::__construct();
		role::check('site_seo');
	}

	public function index()
	{
		$seo_data = Myseo::instance()->get();
		if($_POST)
		{
			$site_next_flow = site::site_next_flow($this->current_flow);
			$submit_target = intval($this->input->post('submit_target'));

			if(Myseo::instance()->edit($_POST))	
			{
				//判断添加成功去向
				switch($submit_target)
				{
				case 2:
					remind::set(Kohana::lang('o_global.update_success'),$site_next_flow['url'],'success');
				default:
					remind::set(Kohana::lang('o_global.update_success'),'site/seo','success');
				}
			}
			else
			{
				remind::set(Kohana::lang('o_global.update_error'),'site/seo');
			}
		}

        $this->template->content = new View("site/seo_edit");
        $this->template->content->data = $seo_data;
	}
}