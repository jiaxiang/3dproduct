<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * $Id: product.php 225 2010-01-08 08:31:33Z zzy $
 * $Author: zzy $
 * $Revision: 225 $
 */

class Seo_manage_Controller extends Template_Controller {
    /**
     * 分类列表
     */
   public function index(){
   		
   		//* 权限验证 */
        $site_id = site::id();
        Myseo_manage::instance()->update_seo_manage_by_site_id($site_id);
        if($site_id == 0){
            remind::set('请首先进入所要操作的站点',request::referrer(),'error');  
        }
        $site_id_list = role::check('seo_manage', 0, 0);
        if(empty($site_id_list)){
             remind::set('access denied',request::referrer(),'error');
        }   	
        $query_struct = array();
		$query_struct = array(
			'where' => array(
				'site_id' => $site_id,
			),
		);
		
		$list_columns = array(
			array('name'=>'ID','column'=>'id','class_num'=>'1'),
			array('name'=>'分类','column'=>'parent_id','class_num'=>'4'),
			array('name'=>'包含子分类','column'=>'is_contain_child','class_num'=>'3'),			
			array('name'=>'Meta Title','column'=>'meta_title','class_num'=>'5'),
			array('name'=>'Meta Keywords','column'=>'meta_keywords','class_num'=>'5'),
			array('name'=>'Meta Description','column'=>'meta_description','class_num'=>'6'),
			array('name'=>'发布时间','column'=>'create_timestamp','class_num'=>'5'),
			array('name'=>'更新时间','column'=>'update_timestamp','class_num'=>'5'),
		);
		
		$this->template->content = new View("product/seo_manage_list");
		$this->template->content->list_columns = $list_columns;
		
		/**
		 * 搜索
		 */
		/*
		$search_arr = array('site_domain','site_id');
		
		$search_type = $this->input->get('search_type');
		$search_value = $this->input->get('search_value');
		if($search_value&&$search_type)
		{
			if(in_array($search_type,$search_arr))
			{
				$query_struct['like'][$search_type] = $search_value;
			}
		}*/
		
		//调用分页
		$per_page = controller_tool::per_page();
		$this->pagination = new Pagination(array('total_items'=>Myseo_manage::instance()->count($query_struct),'items_per_page'=>$per_page));
		$orderby = array('update_timestamp' => 'DESC');
		$seo_manages = Myseo_manage::instance()->seo_manages($query_struct,$orderby,$per_page,$this->pagination->sql_offset);
		$request_category = array(
            'where'=>array(
                'site_id'   => $site_id,
            ),
            'like'=>array(),
            'orderby'   => array(
            ),
        );
		$category = Mycategory::instance()->lists($request_category);
		foreach ($category as $val){
			$category_list[$val['id']] = $val;
		}
		
		
		foreach($seo_manages as $seo_manages_key=>$seo_manages_value)
		{
			$seo_manages[$seo_manages_key]['is_contain_child'] = view_tool::get_active_img($seo_manages_value['is_contain_child']);
			$seo_manages[$seo_manages_key]['meta_description'] = strip_tags(text::limit_words($seo_manages_value['meta_description'],50));
			$seo_manages[$seo_manages_key]['create_timestamp'] = date('Y-m-d H:i:s', $seo_manages_value['create_timestamp']);
			$seo_manages[$seo_manages_key]['update_timestamp'] = date('Y-m-d H:i:s', $seo_manages_value['update_timestamp']);
			
			if($seo_manages_value['parent_id'] && array_key_exists($seo_manages_value['parent_id'], $category_list)){
                    $seo_manages[$seo_manages_key]['parent_id'] = $category_list[$seo_manages_value['parent_id']]['name'];
            }else{
                    $seo_manages[$seo_manages_key]['parent_id'] = '';
            }
			
			foreach($seo_manages_value as $key=>$value)
			{
				if(!is_numeric($value)&&empty($value))
				{
					$seo_manages[$seo_manages_key][$key] = "NULL";
				}
			}
		}

		$this->template->content->seo_manages = $seo_manages;

    }


    /**
     * 添加分类主信息显示
     */
    public function add(){
    	
    	//* 权限验证 */
        $site_id = site::id();
        if($site_id == 0){
            remind::set('请首先进入所要操作的站点',request::referrer(),'error');  
        }
        $site_id_list = role::check('seo_manage', 0, 0);
        if(empty($site_id_list)){
             remind::set('access denied',request::referrer(),'error');
        }
		//VIEW
        $this->template->content = new View("product/seo_manage_add");
        // 站点信息
        $this->template->content->site_info = Mysite::instance($site_id)->get();
        // 默认获取第一个站点的分类列表
        $this->template->content->category_level_list = Mycategory::instance()->site_subcategories($site_id);

    }

    /**
     * 添加分类主信息操作
     */
    public function do_add(){
    	
    	$request_data = $this->input->post();
    	
    	//* 权限验证 */
    	$site_id = site::id();

        $site_id_list = role::check('seo_manage', 0, 0);
        if(empty($site_id_list)){
             remind::set('access denied',request::referrer(),'error');
        }
    	if(!in_array($request_data['site_id'],$site_id_list)){
            remind::set('access denied',request::referrer(),'error');		
		}
		
		//检测输入的域名是否与操作的域名一致
		$site_name = Mysite::instance($site_id)->get('domain');
		
		if($request_data['site_domain'] != $site_name){
			remind::set('输入的域名与操作的站点有误',request::referrer(),'error');
		}

		//执行添加
		if($_POST){
			$set_data = array(
				'parent_id'        => $request_data['parent_id'],
				'site_id'          => $site_id,
				'site_domain'       => $request_data['site_domain'],
				'meta_title'       => $request_data['meta_title'],
				'meta_keywords'     => $request_data['meta_keywords'],
				'meta_description' => $request_data['meta_description'],
				'create_timestamp' => time(), 
				'update_timestamp' => time(),
			);
		 	if(!empty($request_data['is_contain_child'])){
        		$set_data['is_contain_child'] = $request_data['is_contain_child'];
        	}else{
        		$set_data['is_contain_child'] = 0;
       		}
       		
       		$childs_ids = Mycategory::instance()->site_subcategories($site_id,$set_data['site_id']);
       		
       		if(!empty($set_data['is_contain_child']) && empty($childs_ids)){
       			// remind::set('添加 SEO信息失败',request::referrer(),'error');
       		}
       		
	        $return_data['id'] = Myseo_manage::instance()->add($set_data);

	        if(!$return_data['id']){
	        	
	            remind::set('添加 SEO信息失败',request::referrer(),'error');
	        }else{
	        	$myseo = Myseo_manage::instance($return_data['id'])->get();
	        	if(empty($myseo)){
	        		remind::set('添加 SEO信息失败',request::referrer(),'error');
	        	}else{
	        		Myseo_manage::instance()->update_seo_manage_by_site_id($myseo['site_id']);
	        	}
	        	remind::set('添加 SEO信息 成功','product/seo_manage','success');
	        } 
		}  	
    }

    /**
     * SEO信息编辑显示
     */
    public function edit($id) {
		
    	// 权限验证
    	$site_id = site::id();
        if($site_id == 0){
            remind::set('请首先进入所要操作的站点',request::referrer(),'error');  
        }
    	$site_id_list = role::check('seo_manage',0,0);
			
    	$seo_manages   = Myseo_manage::instance($id)->get();

    	if(!in_array($seo_manages['site_id'],$site_id_list) || $seo_manages['site_id']!=$site_id){
            remind::set('access denied',request::referrer(),'error');		
		} 
        if(!$seo_manages['id'])
        {
            remind::set('无效SEO信息,请重试',request::referrer(),'error');		
        }

        $this->template->content = new View("product/seo_manage_edit");	
        
        // 默认获取第一个站点的分类列表
        $this->template->content->category_level_list	= Mycategory::instance()->site_subcategories($seo_manages['site_id']);
        // 站点信息
        $this->template->content->site_info = Mysite::instance($seo_manages['site_id'])->get();

        $this->template->content->data					= $seo_manages;

    }

    /**
     * SEO信息编辑操作
     */
    public function do_edit(){
    	
        //收集请求
        $request_data = $this->input->post();
        
    	//* 权限验证 */
    	$site_id = site::id();
        $site_id_list = role::check('seo_manage', 0, 0);
        if(empty($site_id_list)){
             remind::set('access denied',request::referrer(),'error');
        }

    	//检测输入的域名是否与操作的域名一致
		$site_name = Mysite::instance($site_id)->get('domain');
		if($request_data['site_domain'] != $site_name){
			remind::set('输入的域名与操作的站点有误',request::referrer(),'error');
		}
		// 验证 - 数据有效性
        $seo_manages   = Myseo_manage::instance($request_data['id'])->get();
        if(!$seo_manages['id'])
        {
            remind::set('无效SEO信息,请重试',request::referrer(),'error');		
        }

		// 权限验证
		if($site_id != $seo_manages['site_id'] || !in_array($seo_manages['site_id'],$site_id_list)){
            remind::set('access denied',request::referrer(),'error');		
		}     

        //执行添加
		if($_POST){
			$set_data = array(
				'parent_id'        => $request_data['parent_id'],
				'site_id'          => $seo_manages['site_id'],
				'site_domain'       => $request_data['site_domain'],
				'meta_title'       => $request_data['meta_title'],
				'meta_keywords'     => $request_data['meta_keywords'],
				'meta_description' => $request_data['meta_description'],
				'update_timestamp' => time(),
			);
		    if(!empty($request_data['is_contain_child'])){
        		$set_data['is_contain_child'] = $request_data['is_contain_child'];
        	}else{
        		$set_data['is_contain_child'] = 0;
       		}

			if(Myseo_manage::instance($seo_manages['id'])->update($set_data))
	        {
	        	Myseo_manage::instance()->update_seo_manage_by_site_id($site_id);
	            remind::set('编辑 SEO信息成功','product/seo_manage','success');
	        }else{
	            remind::set('编辑 SEO信息 失败',request::referrer(),'error');
	        } 
		}  
    }

	/**
	 * SEO信息删除
	 */
    public function delete($id){
        //收集请求
        $seo_manages   = Myseo_manage::instance($id)->get();
    	$site_id = site::id();
        if($site_id == 0){
            remind::set('请首先进入所要操作的站点',request::referrer(),'error');  
        }

        if(!$seo_manages['id'])
        {
            remind::set('无效SEO信息,请重试',request::referrer(),'error');		
        }

        $site_id_list = role::check('seo_manage',0,0);
		// 权限验证
		if(!in_array($seo_manages['site_id'],$site_id_list)){
            remind::set('access denied',request::referrer(),'error');		
		}     

        if(Myseo_manage::instance()->delete($id))
        {
        	Myseo_manage::instance()->update_seo_manage_by_site_id($site_id);
            remind::set('删除 信息成功',request::referrer(),'success');
        }else{
            remind::set('删除 信息失败',request::referrer(),'error');
        }
    }

}
?>
