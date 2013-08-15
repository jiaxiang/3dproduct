<?php defined('SYSPATH') OR die('No direct access allowed.');

class Mail_Controller extends Template_Controller {
	protected $current_flow = 'mail';
	public $site_id;

	public function __construct()
	{
		parent::__construct();
		role::check('site_mail');
	}
    
	/**
	 * 列表
	 */
	public function index()
	{
		$mails = Mymail::instance()->mails();
		foreach($mails as $k=>$v)
		{
			$mails[$k]['content_small'] = strip_tags(text::limit_words($v['content'],30));
			$mails[$k]['active_img'] = view_tool::get_active_img($v['active']);
		}

		$this->template->content = new View("site/mail_list");
		$this->template->content->mails = $mails;
	}

	/**
	 * ajax get mail template by type
	 */
	public function ajax_template($type)
	{
		$mail = mail::content($type);
		
		$this->template = new View('template_blank');
		if(isset($mail['content_result']))
		{
			$this->template->content = $mail['content_result'];
		}
		else
		{
			$this->template->content = 'Hacking attempt';
		}
	}

	/**
	 * ajax get site mail content
	 */
	public function ajax_content()
	{
		$return_struct = array(
			'status' => 0,
			'code'	 => 501,
			'msg'	 => 'Not Implemented'
		);
		$id = intval($this->input->get('id'));
		$mail = Mymail::instance($id)->get();

		$return_template = $this->template = new View('template_blank');
		$this->template->content = $mail['content'];
		$return_str = $return_template->render();
		$return_struct['status'] = 1;
		$return_struct['code'] = 200;
		$return_struct['msg'] = 'Success';
		$return_struct['content'] = $return_str;
		exit(json_encode($return_struct));
	}
    
	/**
	 * set mail template
	 */
	public function set()
	{
		if($_POST)
		{
			$site_next_flow = site::site_next_flow($this->current_flow);
			$submit_target = intval($this->input->post('submit_target'));

			$flag = 0;
			$mail_ids = $this->input->post('mail_id');
			foreach($mail_ids as $mail_id)
			{
				if($mail_id > 0)
				{
					$data = Mymail_template::instance($mail_id)->get();
					if(!Mymail::instance()->set($data))
					{
						$flag++;
					}
				}
			}
			if($flag > 0)
			{
				$error = Mymail::instance()->error();
				remind::set(Kohana::lang('o_global.set_error').$error,'site/mail');
			}
			else
			{
				//判断添加成功去向
				switch($submit_target)
				{
					case 1:
						remind::set(Kohana::lang('o_global.update_success'),$site_next_flow['url'],'success');
					default:
						remind::set(Kohana::lang('o_global.update_success'),'site/mail','success');
				}
			}
		}

		$mails = Mymail::instance()->mails();

		$query_struct = array();
		$mail_categories = Mymail_category::instance()->mail_categories($query_struct);
		foreach($mail_categories as $key=>$value)
		{
			$query_struct = array();
			$query_struct['where']['mail_category_id'] = $value['id'];
			$mail_template = Mymail_template::instance()->mail_templates($query_struct);
			$mail_categories[$key]['mail_template_list'] = $mail_template;

			
			$mail_categories[$key]['mail_template'] = " -未设定- ";
			$mail_categories[$key]['mail_template_perview_link'] = "#";

			foreach($mails as $m_key=>$m_value)
			{
				if($m_value['mail_category_id'] == $value['id'])
				{
					$mail_categories[$key]['mail_template'] = $m_value['name'];
					$mail_categories[$key]['mail_template_perview_link'] = url::base() . "site/mail/perview/site/" . $m_value['id'];
				}
			}
		}

		$this->template->content = new View('site/mail_set');
		$this->template->content->site_categories = $mail_categories;
	}

	/**
	 * pervice a mail
	 */
	function perview($type,$id)
	{
		//非法请求
		if(!request::referrer())
		{
            remind::set(Kohana::lang('o_global.bad_request'), 'site/mail', 'error');
		}
        
		if($type == 'site')
		{
			$mail = Mymail::instance($id)->get();
		}
		else
		{
			$mail = Mymail_template::instance($id)->get();
		}

		$this->template = new View('template_blank');
		$this->template->content = $mail['content'];
	}

	/**
	 * edit item
	 */
	public function edit($id)
	{
		if($_POST)
		{
            //标签过滤			
            tool::filter_strip_tags($_POST,array('content'));

            if(Mymail::instance($id)->edit($_POST))
			{
				remind::set(Kohana::lang('o_global.update_success'),'site/mail','success');
			}
			else
			{
				remind::set(Kohana::lang('o_global.update_error'),'site/mail');
			}
		}
        
		$mail = Mymail::instance($id)->get();
		if(!$mail['id'])
		{
			remind::set(Kohana::lang('o_site.mail_template_not_exist'),'site/mail');
		}

		$mail_categories = Mymail_category::instance()->mail_categories();
		$mail_categories_tmp = array();
		foreach($mail_categories as $value){
		  $mail_categories_tmp[$value['id']] = $value;
		}
		$mail_categories = $mail_categories_tmp;
		if(!isset($mail_categories[$mail['mail_category_id']]))
		{
			remind::set(Kohana::lang('o_site.check_mail_category'),'site/mail');
		}

		$mail['mail_category_name'] = $mail_categories[$mail['mail_category_id']]['name'];

        $this->template->content = new View("site/mail_edit");
		$this->template->content->mail_categories = $mail_categories;
		$this->template->content->data = $mail;
		$this->template->title = "编辑邮件模板";
	}

	/**
	 * delete site mail
	 */
	public function delete($id)
	{
		if(Mymail::instance($id)->delete())
		{
			remind::set(Kohana::lang('o_global.delete_success'),'site/mail','success');
		}
		else
		{
			remind::set(Kohana::lang('o_global.delete_error'),'site/mail');
		}
	}
	
    /**
     * 批量删除邮件
     */
    public function batch_delete()
    {
        //初始化返回数据
        $return_data = array();
        //请求结构体
        $request_data = array(); 
        try { 
            $mail_ids = $this->input->post('mail_ids');
            
            if(is_array($mail_ids) && count($mail_ids) > 0)
            {
                /* 删除失败的 */
                $failed_mail_names = '';
                
                /* 执行操作 */
                foreach($mail_ids as $mail_id)
                {
                    if(!Mymail::instance($mail_id)->delete())
                    {
                        $failed_mail_names .= ' | ' . $mail_id;
                    }
                }
                if(empty($failed_mail_names))
                {
                    throw new MyRuntimeException(Kohana::lang('o_site.delete_mail_success'),403);
                }
                else
                {
                    /* 中转提示页面的停留时间 */
                    $return_struct['action']['time'] = 10;
                    $failed_mail_names = trim($failed_mail_names,' | ');
                    throw new MyRuntimeException(Kohana::lang('o_site.delete_mail_error',$failed_mail_names),403);
                }
            }
            else
            {
                throw new MyRuntimeException(Kohana::lang('o_global.data_load_error'),403);
            }
        } catch (MyRuntimeException $ex) {
            $return_struct['status'] = 0;
            $return_struct['code']   = $ex->getCode();
            $return_struct['msg']    = $ex->getMessage();
            //TODO 异常处理
            //throw $ex;
            if($this->is_ajax_request()) {
                $this->template = new View('layout/empty_html');
                $this->template->content = $return_struct['msg'];
            } else {
                $this->template->return_struct = $return_struct;

                $content = new View('info');
                $this->template->content = $content;
                /* 请求结构数据绑定 */
                $this->template->content->request_data = $request_data;
                /* 返回结构体绑定 */
                $this->template->content->return_struct = $return_struct;
            }
        }
    }
	
}
