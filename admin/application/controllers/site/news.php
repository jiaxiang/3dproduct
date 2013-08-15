<?php defined('SYSPATH') OR die('No direct access allowed.');

class News_Controller extends Template_Controller {
	public $site_id;
    private $img_dir_name = 'news';	
	public function __construct()
	{
        role::check('site_news');
		parent::__construct();
	}
    
	//列表
	public function index()
	{        
		$news = Mynews::instance();
		$per_page = controller_tool::per_page();
        $orderby_arr= array
        (
                0   => array('id'=>'DESC'),
                1   => array('id'=>'ASC'),
                2   => array('order'=>'ASC'),
                3   => array('order'=>'DESC')
        );
        $orderby    = controller_tool::orderby($orderby_arr);
        $query_struct = array(
            'where'=>array(
            ),
            'orderby'       => $orderby,
            'limit'         => array(
                                'per_page'  =>$per_page,
                                'offset'    =>0
                                )
        );
        $total = $news->count_site_news();
        $this->pagination = new Pagination(array(
			'base_url'			=> url::current(),
			'uri_segment'		=> 'page',
			'total_items'		=> $total,
			'items_per_page'	=> $per_page,
			'style'				=> 'digg'
		));
		$query_struct['limit']['offset'] = $this->pagination->sql_offset;
        $news = Mynews::instance()->lists($query_struct);
		
		$categorys = array();
		foreach($news as $row)
		{
			$categorys[$row['classid']] = $row['classid'];
		}
		foreach($categorys as $v){
		  $str='';
		   $aa = array(
				'where'=>array(
					'id' => $v,
				),
				'like'=>array(),
				'limit'     => array(),
			);
			$categories = Mynews_category::instance()->list_news_categories($aa);
			if(count($categories)){
			 $str=$categories[0]['category_name'];
				 if($categories[0]['parent_id']>0){
					 $aa = array(
						'where'=>array(
							'id' => $categories[0]['parent_id'],
						),
						'like'=>array(),
						'limit'     => array(),
					);
					$cate = Mynews_category::instance()->list_news_categories($aa);
					$str= $cate[0]['category_name'].' > '.$str;
				 }
			}
			$categorys[$v] =$str;
		}
		
		
        $this->template->content = new View("site/news_list");
		$this->template->content->data = $news;
		$this->template->content->categorys = $categorys;
		$this->template->content->title = "site news list";
	}
    
	//编辑
	public function edit($id)
	{	
		
		$news = Mynews::instance($id);
		if($_POST)
		{ 
            //标签过滤
            tool::filter_strip_tags($_POST, array('content'));
			empty($_POST['zxtj']) && $_POST['zxtj']=0;
 			empty($_POST['newstj']) && $_POST['newstj']=0;
			empty($_POST['zd']) && $_POST['zd']=0;
			empty($_POST['list1']) && $_POST['list1']=0;
			empty($_POST['list2']) && $_POST['list2']=0;
		if(empty($_POST['title']) || empty($_POST['content']) || empty($_POST['classid']) || empty($_POST['key']))
		  {
		  	remind::set(Kohana::lang('o_global.update_error'),'site/news/edit');
		  }
		  else{
			if($news->edit($_POST))
			{
				remind::set(Kohana::lang('o_global.update_success'),'site/news', 'success');
			}
			else
			{
				remind::set(Kohana::lang('o_global.update_error'),'site/news/edit');
			}
		  }
		}
		$news_data = $news->get();
		$news_categories = Mynews_category::instance()->news_categories(0);
        $this->template->content = new View("site/news_edit");
		$this->template->content->news_categories = $news_categories;
		$this->template->content->data = $news_data;
		$this->template->content->title = "site news edit";
	}
    
	//添加
	public function add()
	{
		if($_POST)
		{
            //标签过滤
            tool::filter_strip_tags($_POST, array('content'));
	        
			$news = Mynews::instance();
		  //  print_r($news->add($_POST));die;
		  if(empty($_POST['title']) || empty($_POST['content']) || empty($_POST['classid']) || empty($_POST['key']))
		  {
		  	remind::set(Kohana::lang('o_global.add_error'),'site/news/add');
		  }
		  else
		  {
			if($news->add($_POST))
			{
				remind::set(Kohana::lang('o_global.add_success'),'site/news','success');
			}
			else
			{	
				remind::set(Kohana::lang('o_global.add_error'),'site/news/add');
			}
		  }
								$data = $news->get();
		}
		$news_categories = Mynews_category::instance()->news_categories(0);
		//print_r($news_categories);die;
		$news = Mynews::instance();
		$data = $news->get();
        $this->template->content = new View("site/news_add");
		$this->template->content->news_categories = $news_categories;
	}
	//删除
	public function delete($id)
	{
        role::check('site_news');
        
		if(Mynews::instance($id)->delete())
        {
		    remind::set(Kohana::lang('o_global.delete_success'),'site/news/', 'success');
        }
        else
        {
            remind::set(Kohana::lang('o_global.delete_error'),'site/news/', 'success');
        }
	}


	/**
	 * 上传新Logo
	 */
	public function logo_upload_iframe()
	{
		if(request::is_ajax())			
		{
			$return_struct['status'] = 1;
			$return_struct['code'] = 200;
			$return_struct['msg'] = 'Success';		
			$return_struct['content'] = '<iframe scrolling="no" frameborder="0" style="width:100%;height:100%;" src="'.url::base().'site/news/logo_upload"></iframe>';
			exit(json_encode($return_struct));
		}
		else
		{
			$return_struct['code'] = 400;
			$return_struct['msg'] = Kohana::lang('o_global.access_denied');		
			exit(json_encode($return_struct));
		}
	}

	/**
	 * 上传新Logo
	 */
	public function logo_upload()
	{
		$return_struct = array(
			'status' => 0,
			'code'	 => 501,
			'msg'	 => 'Not Implemented'
		);
		if($_POST)
		{
			if (!isset($_FILES["img_val"]) || !is_uploaded_file($_FILES["img_val"]["tmp_name"]) || $_FILES["img_val"]["error"] != 0)
			{
				remind::set(Kohana::lang('o_global.illegal_data'),'site/news/logo_upload');
			}
			$filename = $_FILES['img_val']['name'];
			$file_ext = tool::fileext($filename);
			//资源文件最大大小(default:1M)
			$file_max_size = kohana::config('theme.file_max_size');
			$file_max_size = ($file_max_size>0) ? $file_max_size : 1048576;
			$type = array('gif','png','jpg','jpeg');
			//判断文件类型
			if(!in_array(strtolower($file_ext),$type))
			{
				remind::set(Kohana::lang('o_site.file_type_error'),'site/news/logo_upload');
			}
			$val = $_FILES['img_val']['name'];
			$file_size = filesize($_FILES['img_val']['tmp_name']);
			if($file_size>$file_max_size)
			{
				remind::set(Kohana::lang('o_site.pic_size_out_range'),'site/news/logo_upload');
			}
			//$filename = 'logo.' . $file_ext;
			//$file = file_get_contents($_FILES['img_val']["tmp_name"]);
			//Storage_server::instance()->cache_site($site['id'],$filename,$file);
            $AttService = AttService::get_instance($this->img_dir_name);
            $AttService->default_img_type = 'gif';
            $img_id = $AttService->save_default_img($_FILES['img_val']["tmp_name"], date("YmdHis",time()));
            if(!$img_id){
                remind::set(Kohana::lang('o_product.phprpc_pic_save_failed'),'site/news');
            }

				echo "<script>\n";
				echo "if (parent.document.add_form.newpic != undefined){\n";
				echo "	parent.document.add_form.newpic.value='".$AttService->get_img_url($img_id,0,0)."';\n";
				echo "} else if (parent.document.getElementById('newpic') != undefined){\n";
				echo "	parent.document.getElementById('newpic').value='".$AttService->get_img_url($img_id,0,0)."';\n";
				echo "}";
				echo "window.parent.$(\"#upload_content\").dialog(\"close\");";				
				echo "</script>\n";

			d($AttService->get_img_url($img_id,0,0));
		}
			$this->template->content = new View("site/news_logo_upload");	
	}

	/**
	 * 删除站点LOGO
	 */
	public function del_newpic($id="")
	{
		//更新数据库标识
		$newpic_h = $this->input->get('img');		
		$data['newpic'] = "";

		$news = Mynews::instance($id);

		if($news->edit($data))
		{
			$img_id = $newpic_h;
            AttService::get_instance($this->img_dir_name)->delete_img($img_id, false);
			$return_struct['status'] = 1;
			$return_struct['code'] = 200;
			$return_struct['msg'] = $img_id;		
			$return_struct['content'] = '删除成功';
			exit(json_encode($return_struct));
		}
		else
		{
			$return_struct['status'] = 1;
			$return_struct['code'] = 200;
			$return_struct['msg'] = 'Success';		
			$return_struct['content'] = '删除失败';
			exit(json_encode($return_struct));
		}
	}









	
    /**
     * 设定菜单的排序
     */
   public function set_order()
    {
        //初始化返回数组
        $return_struct = array(
            'status'        => 0,
            'code'          => 501,
            'msg'           => 'Not Implemented',
            'content'       => array(),
        );
       $request_data = $this->input->get();
       $id = isset($request_data['id']) ?  $request_data['id'] : '';
       $order = isset($request_data['order']) ?  $request_data['order'] : '';
       if(empty($id) || (empty($order) && $order != 0))
       {
           $return_struct['msg'] = Kohana::lang('o_global.bad_request');
           exit(json_encode($return_struct));
       }
       if(!is_numeric($order) || $order<0){
           $return_struct['msg'] = Kohana::lang('o_global.position_rule');
           exit(json_encode($return_struct));
       }
       if(Mynews::instance()->set_order($id,$order)){
            $return_struct = array(
                'status'        => 1,
                'code'          => 200,
                'msg'           => Kohana::lang('o_global.position_success'),
                'content'       => array('order'=>$order),
            );
       } else {
            $return_struct['msg'] = Kohana::lang('o_global.position_error');
       }
       exit(json_encode($return_struct));
    }
    
    /**
     * 批量删除新闻
     */
    public function batch_delete()
    {
        //初始化返回数据
        $return_data = array();
        //请求结构体
        $request_data = array();
        
        try {
            $new_ids = $this->input->post('new_ids');
            
            if(is_array($new_ids) && count($new_ids) > 0)
            {                
                /* 删除失败的 */
                $failed_new_names = '';
                /* 执行操作 */
                foreach($new_ids as $key=>$new_id)
                {
                    if(!Mynews::instance($new_id)->delete())
                    {
                        $failed_new_names .= ' | ' . $new_id;
                    }
                }
                if(empty($failed_new_names))
                {
                    throw new MyRuntimeException(Kohana::lang('o_site.delete_new_success'),403);
                }
                else
                {
                    /* 中转提示页面的停留时间 */
                    $return_struct['action']['time'] = 10;
                    $failed_new_names = trim($failed_new_names,' | ');
                    throw new MyRuntimeException(Kohana::lang('o_site.delete_new_error', $failed_new_names), 403);
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
