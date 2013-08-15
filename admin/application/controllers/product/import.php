<?php defined('SYSPATH') OR die('No direct access allowed.');

set_time_limit(0);

class Import_Controller extends Template_Controller {
	// Disable this controller when Kohana is set to production mode.
    const ALLOW_PRODUCTION = TRUE;
    
    private $class_name = '';
    private $package = '';
    // Set the name of the template to use
    public $template_ = 'layout/common_html';
    
	/**
     * 构造方法
     */
    public function __construct(){
        role::check('product_import');
        $this->package = 'product';
        $this->class_name = strtolower(substr(__CLASS__, 0, strpos(__CLASS__, '_')));
        parent::__construct();
        if($this->is_ajax_request()){
            $this->template = new View('layout/default_json');
        }
    }
    
    public function index()
    {
    	$return_struct = array (
            'status' => 0,
            'code' => 501,
            'msg' => 'Not Implemented',
            'content' => array()
        );
        try{
            //$profiler = new Profiler;
            //* 初始化返回数据 */
            $return_data = array ();
            //* 收集请求数据 ==根据业务逻辑定制== */
            $request_data = $this->input->get();
        	$classifies = ClassifyService::get_instance()->query_assoc(array(
    			'where'   => array(
            	),
    			'orderby' => array(
    				'id' => 'ASC',
    			),
    		));
        	
    		$html = '<option value="-1">'.ClassifyService::DEFAULT_CLASSIFY_NAME.'</option>';
    		foreach ($classifies as $classify){
    			$html .= '<option value="'.$classify['id'].'">'.htmlspecialchars($classify['name']).'</option>';
    		}
            
            //* 补充&修改返回结构体 */
            $return_struct['status'] = 1;
            $return_struct['code'] = 200;
            $return_struct['msg'] = '';
            $return_struct['content'] = $return_data;
            
            //* 请求类型 */
            if($this->is_ajax_request()){
                // ajax 请求
                // json 输出
                $this->template->content = $return_struct;
            }else{
                // html 输出
                //* 模板输出 */
                $content = new View($this->package . '/' . $this->class_name . '/' . __FUNCTION__);
                //* 变量绑定 */
                $this->template->title = Kohana::config('site.name');
                $this->template->content = $content;
                //* 请求结构数据绑定 */
                $this->template->content->request_data = $request_data;
                //* 返回结构体绑定 */
                $this->template->content->return_struct = $return_struct;
                //:: 当前应用专用数据
            	$this->template->content->classifies_html = $html;
            } // end of request type determine
        }catch(MyRuntimeException $ex){
            $return_struct['status'] = 0;
            $return_struct['code'] = $ex->getCode();
            $return_struct['msg'] = $ex->getMessage();
            //TODO 异常处理
            //throw $ex;
            if($this->is_ajax_request()){
                $this->template->content = $return_struct;
            }else{
                $this->template->return_struct = $return_struct;
                $content = new View('info');
                $this->template->content = $content;
                //* 请求结构数据绑定 */
                $this->template->content->request_data = $request_data;
                //* 返回结构体绑定 */
                $this->template->content->return_struct = $return_struct;
            }
        }
    }
    
    public function import()
    {
    	$return_struct = array (
            'status' => 0, 
            'code' => 501, 
            'msg' => 'Not Implemented', 
            'content' => array ()
        );
        try{            
            //$profiler = new Profiler;
            //* 初始化返回数据 */
            $return_data = array ();
            //* 收集请求数据 ==根据业务逻辑定制== */
            $request_data = $this->input->post();
            
            if (empty($_FILES['Filedata']))
            {
            	throw new MyRuntimeException(Kohana::lang('o_global.bad_request'));
            }
            
            $csv = $_FILES['Filedata'];
            
            if (empty($csv['name']) OR empty($csv['tmp_name']))
            {
            	throw new MyRuntimeException(Kohana::lang('o_global.bad_request'));
            }
            
            $csv['name'] = trim($csv['name']);
            if (strpos($csv['name'], '.'))
            {
            	$postfix = strtoupper(substr($csv['name'], strrpos($csv['name'], '.') + 1));
            } else {
            	$postfix = '';
            }
            if ($postfix != 'TSV' AND $postfix != 'CSV' AND $postfix != 'ZIP')
            {
            	throw new MyRuntimeException(Kohana::lang('o_global.bad_request'));
            }
            
            if (!is_uploaded_file($csv['tmp_name']))
            {
            	throw new MyRuntimeException(Kohana::lang('o_global.bad_request'));
            }
            
            if ($postfix == 'CSV' || $postfix == 'TSV')
            {
            	$csv = file_get_contents($csv['tmp_name']);
            	$dir = NULL;
            } else {
            	$zip = zip::factory($csv['tmp_name']);
            	$csv = NULL;
            	$dir = Kohana::config('product.import_tmp_dir');
            	$dir = rtrim(trim($dir), '/');
            	$dir .= '/'.uniqid();
            	if (!is_dir($dir) AND !@mkdir($dir, 0777, TRUE))
            	{
            		throw new MyRuntimeException(Kohana::lang('o_product.import_cte_tmpdir_failed'));
            	}
            	
            	if (!is_object($zip) OR !$zip->extract($dir))
            	{
            		throw new MyRuntimeException(Kohana::lang('o_product.import_wte_tmp_failed'));
            	}
            	$dirs = array($dir);
            	while ($direct = array_shift($dirs))
            	{
            		$handler = opendir($direct);
            		while (($item = readdir($handler)) !== FALSE)
            		{
            			if ($item !== '.' AND $item !== '..')
            			{
            				$path = $dir.'/'.$item;
            				if (is_dir($path))
            				{
            					$dirs[] = $path;
            				} else {
                                $extfnm = strtoupper(substr($path, strrpos($path, '.') + 1));
            					if (strpos($path, '.') && ($extfnm === 'CSV' || $extfnm === 'TSV'))
            					{
            						$dirs = array();
            						$csv  = file_get_contents($path);
            						$dir  = dirname($path);
            						break;
            					}
            				}
            			}
            		}
            		closedir($handler);
            	}
            	if (is_null($csv))
            	{
            		throw new MyRuntimeException(Kohana::lang('o_product.import_csv_not_found'));
            	}
            }
        	
        	$i = 1;
            $csv = iconv('GBK', 'UTF-8//IGNORE', $csv);
            //$import = ImportService::get_instance()->run($csv, $dir);
            $import = ImportService::get_instance()->parse_csv($csv, $dir);
            $errs = '';
	        foreach ($import['errors'] as $l => $err){				
				foreach ($err as $item){
					$errs .= $i.'、'.$item->getMessage().'；<br/>';
                    $i++;
				}
			}
			
			if (empty($errs)){
				/*require_once Kohana::find_file('vendor', 'phprpc/phprpc_client',TRUE);
				$site_id     = 0;
				$site_domain = '';
				$mime_type2postfix  = Kohana::config('mimemap.type2postfix');
				$mime_postfix2type  = Kohana::config('mimemap.postfix2type');
				$phprpc_api_key     = Kohana::config('phprpc.remote.Attachment.apiKey');
				$attachment_service = new PHPRPC_Client(Kohana::config('phprpc.remote.Attachment.host'));
				foreach ($import['products'] as $product)
				{
					$goods       = array();
					$description = $product['description'];
					$pictures    = $product['pictures'];
					$product_attributeoption_relation = !empty($product['goods_attributeoption_relation_struct_default']['items'])
													  ? $product['goods_attributeoption_relation_struct_default']['items']
													  : array();
					$product_featureoption_relation   = !empty($product['product_featureoption_relation_struct']['items'])
													  ? $product['product_featureoption_relation_struct']['items']
													  : array();
					unset($product['description'], $product['pictures']);
					if (!empty($product['goods']))
					{
						$goods = $product['goods'];
						unset($product['goods']);
					}
					
					if ($product['site_id'] != $site_id)
					{
						$site_id     = $product['site_id'];
						$site_domain = Mysite::instance($site_id)->get('domain');
					}
					
					try
					{
						$product = coding::encode_product($product);
						if (isset($product['id']))
						{
							ProductService::get_instance()->set($product['id'], $product);
							$product_id = $product['id'];
						} else {
							$product_id = ProductService::get_instance()->create($product);
						}
						
						if (!empty($product['goods_attributeoption_relation_struct_default']))
						{
							$product['goods_attributeoption_relation_struct_default'] = json_decode($product['goods_attributeoption_relation_struct_default'], TRUE);
						}
						if (!empty($product['product_featureoption_relation_struct']))
						{
							$product['product_featureoption_relation_struct'] = json_decode($product['product_featureoption_relation_struct'], TRUE);
						}
						
						ProductsearchService::get_instance()->set_single(array(
							'product_id'  => $product_id,
							'site_id'     => $product['site_id'],
							'category_id' => $product['category_id'],
							'brand_id'    => $product['brand_id'],
							'title'       => $product['title'],
							'brief'       => $product['brief'],
							'description' => $description['content'],
							'attributes'  => empty($product['goods_attributeoption_relation_struct_default']['items']) ? array() : $product['goods_attributeoption_relation_struct_default']['items'],
        					'features'    => empty($product['product_featureoption_relation_struct']['items']) ? array() : $product['product_featureoption_relation_struct']['items'],
						));
					} catch (MyRuntimeException $ex) {
						break;
					}
					
					try
					{
						$description['product_id'] = $product_id;
						if (isset($description['id']))
						{
							ProductdescsectionService::get_instance()->set($description['id'], $description);
						} else {
							ProductdescsectionService::get_instance()->create($description);
						}
					} catch (MyRuntimeException $ex) {
						ProductService::get_instance()->delete_by_product_id($product_id);
						break;
					}
					
					try
					{
						ORM::factory('product_featureoption_relation')->where('product_id', $product_id)->delete_all();
						if (!empty($product_featureoption_relation))
						{
							foreach ($product_featureoption_relation as $fid => $oid)
							{
								Product_featureoption_relationService::get_instance()->create(array(
									'site_id'          => $product['site_id'],
									'product_id'       => $product_id,
									'featureoption_id' => $oid,
									'feature_id'       => $fid,
								));
							}
						}
					} catch (MyRuntimeException $ex) {
						ProductService::get_instance()->delete_by_product_id($product_id);
						break;
					}
					
					try
					{
						ORM::factory('product_argument')->where('product_id', $product_id)->delete_all();
						if (!empty($product['arguments']))
						{
							Product_argumentService::get_instance()->create(array(
								'product_id' => $product_id,
								'arguments'  => json_encode($product['arguments']),
							));
						}
					} catch (MyRuntimeException $ex) {
						ProductService::get_instance()->delete_by_product_id($product_id);
						break;
					}
					
					try
					{
						ksort($pictures);
						$i = 0;
						foreach ($pictures as $picname => $picpath)
						{
							$attachment = array(
								'fileName'    => strip_tags($picname),
								'fileSize'    => filesize($picpath),
								'filePostfix' => strtolower(substr($picname, strrpos($picname, '.') + 1)),
								'srcIp'       => $this->input->ip_address(),
								'attachMeta'  => json_encode(array(
									'siteId'     => $site_id,
									'siteDomain' => $site_domain,
								)),
								'createTimestamp' => time(),
								'updateTimestamp' => time(),
							);
							if(array_key_exists($attachment['filePostfix'], $mime_postfix2type)){
								$attachment['fileMimeType'] = $mime_postfix2type[$attachment['filePostfix']];
							}else{
								$attachment['fileMimeType'] = 'application/octet-stream';
							}
							$pic_args = array($attachment);
							$pic_sign = md5(json_encode($pic_args).$phprpc_api_key);
							$attachment_id = $attachment_service->phprpc_addAttachmentFileData($attachment, @file_get_contents($picpath), $pic_sign);
							if (is_numeric($attachment_id))
							{
								$productpic_id = ProductpicService::get_instance()->add(array(
									'site_id'     => $site_id,
				                    'product_id'  => $product_id,
				                    'is_default'  => ProductpicService::PRODUCTPIC_IS_DEFAULT_FALSE,
				                    'title'       => '',
				                    'attach_id'   => $attachment_id,
				                    'meta_struct' => NULL,
				                    'create_timestamp' => time(),
				                    'update_timestamp' => time(),
								));
								if ($i == 0)
								{
									ProductpicService::get_instance()->set_default_pic_by_productpic_id($productpic_id, $product_id, $site_id);
								}
								$pictures[$picname] = $productpic_id;
							} else {
								//throw new MyRuntimeException('图片存储失败');
							}
							$i ++;
						}
					} catch (MyRuntimeException $ex) {
						ProductService::get_instance()->delete_by_product_id($product_id);
						break;
					}
					
					if (!empty($goods))
					{
						try
						{
							$good_ids = array();
							$aopt_ids = array();
							if (isset($product['id']))
							{
								$query_struct = array('where' => array(
									'product_id' => $product_id,
								));
								
								// 获取原有货品 ID 列表
								foreach (GoodService::get_instance()->query_assoc($query_struct) as $item)
								{
									$good_ids[$item['id']] = TRUE;
								}
								
								// 获取原有商品所关联的规格项 ID 列表
								foreach (Product_attributeoption_relationService::get_instance()->query_assoc($query_struct) as $item)
								{
									$aopt_ids[$item['attributeoption_id']] = TRUE;
								}
								
								// 删除商品、货品与规格项的关联
								ORM::factory('goods_attributeoption_relation')->where('product_id', $product_id)->delete_all();
								ORM::factory('product_attributeoption_relation')->where('product_id', $product_id)->delete_all();
								
								// 保存现有的商品与规格项关联，并且从原有关联列表中删除掉当前保存的
								foreach ($product_attributeoption_relation as $aid => $oids)
								{
									foreach ($oids as $oid)
									{
										Product_attributeoption_relationService::get_instance()->create(array(
											'site_id'            => $product['site_id'],
											'product_id'         => $product_id,
											'attributeoption_id' => $oid,
											'attribute_id'       => $aid,
										));
										unset($aopt_ids[$oid]);
									}
								}
								
								// 清理掉不再关联的规格项对应的图片关联
								if (!empty($aopt_ids))
								{
									ORM::factory('product_attributeoption_productpic_relation')->where('product_id', $product_id)->in('attributeoption_id', array_keys($aopt_ids))->delete_all();
								}
							}
							foreach ($goods as $good)
							{
								// 获取货品所关联的图片 ID 列表
								$goodpics = array();
								if (!empty($good['pictures']))
								{
									foreach ($good['pictures'] as $goodpic)
									{
										if (isset($pictures[$goodpic]) AND preg_match('/^\d+$/', $pictures[$goodpic]))
										{
											$goodpics[] = $pictures[$goodpic];
										}
									}
									$good['goods_productpic_relation_struct'] = array(
										'items' => $goodpics
									);
								}
								
								$good['product_id'] = $product_id;
								$good_attributeoption_relation = $good['goods_attributeoption_relation_struct']['items'];
								$good = coding::encode_good($good);
								
								// 存在 ID 时为更新，否则为新建
								if (isset($good['id']))
								{
									GoodService::get_instance()->set($good['id'], $good);
									$good_id = $good['id'];
									unset($good_ids[$good['id']]);
								} else {
									$good_id = GoodService::get_instance()->create($good);
								}
								
								// 保存货品与图片的关联
								foreach ($goodpics as $productpic_id)
								{
									Goods_productpic_relationService::get_instance()->create(array(
										'site_id'       => $site_id,
										'product_id'    => $product_id,
										'goods_id'      => $good_id,
										'productpic_id' => $productpic_id,
									));
								}
								
								// 保存货品与规格项的关联
								foreach ($good_attributeoption_relation as $aid => $oid)
								{
									Goods_attributeoption_relationService::get_instance()->create(array(
										'site_id'            => $product['site_id'],
										'product_id'         => $product_id,
										'goods_id'           => $good_id,
										'attributeoption_id' => $oid,
										'attribute_id'       => $aid,
									));
								}
							}
							
							// 删除原有的，当前已不存在的货品相关数据
							if (!empty($good_ids))
							{
								$good_ids = array_keys($good_ids);
								ORM::factory('good')->where('product_id', $product_id)->in('id', $good_ids)->delete_all();
								ORM::factory('goods_productpic_relation')->where('product_id', $product_id)->in('id', $good_ids)->delete_all();
							}
						} catch (MyRuntimeException $ex) {
							ProductService::get_instance()->delete_by_product_id($product_id);
							break;
						}
					} else {
						if (isset($product['id']))  // 清理商品、货品与规格的关联
						{
							ORM::factory('good')->where('product_id', $product_id)->delete_all();
							ORM::factory('goods_attributeoption_relation')->where('product_id', $product_id)->delete_all();
							ORM::factory('goods_productpic_relation')->where('product_id', $product_id)->delete_all();
							ORM::factory('product_attributeoption_relation')->where('product_id', $product_id)->delete_all();
							ORM::factory('product_attributeoption_productpic_relation')->where('product_id', $product_id)->delete_all();
						}
						GoodService::get_instance()->create(array(
							'on_sale'          => $product['on_sale'],
							'site_id'          => $product['site_id'],
							'product_id'       => $product_id,
							'is_default'       => 1,
							'sku'              => $product['sku'],
							'price'            => $product['goods_price'],
							'market_price'     => $product['goods_market_price'],
							'title'            => $product['title'],
							'cost'             => $product['goods_cost'],
							'store'            => $product['store'],
							'weight'           => $product['weight'],
							'create_timestamp' => time(),
							'update_timestamp' => time(),
						));
					}
				}*/
				
				//* 补充&修改返回结构体 */
	            $return_struct['status'] = 1;
	            $return_struct['code'] = 200;
	            $return_struct['msg'] = 'Success';
	            $return_struct['content'] = $return_data;
			} else {
				$return_struct['status'] = 0;
	            $return_struct['code'] = 400;
	            $return_struct['msg'] = $errs;
	            $return_struct['content'] = $return_data;
			}
            
			exit(json_encode($return_struct));
			
            //* 请求类型 */
            if($this->is_ajax_request()){
                // ajax 请求
                // json 输出
                $this->template->content = $return_struct;
            }else{
            	$this->template = new View('layout/empty_html');
                // html 输出
                $this->template->return_struct = $return_struct;
                $content = new View($this->package . '/' . $this->class_name . '/' . __FUNCTION__);
                $this->template->content = $content;
                //* 请求结构数据绑定 */
                $this->template->content->request_data  = $request_data;
                //* 返回结构体绑定 */
                $this->template->content->return_struct = $return_struct;
            	$this->template->content->errs          = $errs;
            } // end of request type determine
        

        }catch(MyRuntimeException $ex){
            $return_struct['status'] = 0;
            $return_struct['code'] = $ex->getCode();
            $return_struct['msg'] = $ex->getMessage();
            
            exit(json_encode($return_struct));
            
            //TODO 异常处理
            //throw $ex;
            if($this->is_ajax_request()){
                $this->template->content = $return_struct;
            }else{
                $this->template->return_struct = $return_struct;
                $content = new View('info');
                $this->template->content = $content;
                //* 请求结构数据绑定 */
                $this->template->content->request_data = $request_data;
                //* 返回结构体绑定 */
                $this->template->content->return_struct = $return_struct;
            }
        }
    }
    
    public function export()
    {
    	$return_struct = array (
            'status' => 0, 
            'code' => 501, 
            'msg' => 'Not Implemented', 
            'content' => array () 
        );
        try
        {
            //* 初始化返回数据 */
            $return_data = array ();
            //* 收集请求数据 ==根据业务逻辑定制== */
            $request_data = $this->input->get();
            
        	if (empty($request_data['classify_id'])){
            	throw new MyRuntimeException(Kohana::lang('o_global.bad_request'), 400);
            }
            
            $classify_id = $request_data['classify_id'];
            
            if ($classify_id > 0){
	            $classify = ClassifyService::get_instance()->get($request_data['classify_id']);
            } else {
            	$classify_id = 0;
            }
            
            $export = ExportService::get_instance()->get_titlebar($classify_id);
            
            $csv    = csv::encode(array($export));
            
            header('Cache-control: private');
            header('Content-Disposition: attachment; filename=import.csv');
            header('Content-type: text/csv; charset=GBK');
            
            echo iconv('UTF-8', 'GBK//IGNORE', $csv);
            exit;
            
            //* 补充&修改返回结构体 */
            $return_struct['status'] = 1;
            $return_struct['code'] = 200;
            $return_struct['msg'] = 'Success';
            $return_struct['content'] = $return_data;
            
            //* 请求类型 */
            if($this->is_ajax_request()){
                // ajax 请求
                // json 输出
                $this->template->content = $return_struct;
            }else{
                // html 输出
                $this->template->return_struct = $return_struct;
                $content = new View('info');
                $this->template->content = $content;
                //* 请求结构数据绑定 */
                $this->template->content->request_data = $request_data;
                //* 返回结构体绑定 */
                $this->template->content->return_struct = $return_struct;
            
            } // end of request type determine
        

        }catch(MyRuntimeException $ex){
            $return_struct['status'] = 0;
            $return_struct['code'] = $ex->getCode();
            $return_struct['msg'] = $ex->getMessage();
            //TODO 异常处理
            //throw $ex;
            if($this->is_ajax_request()){
                $this->template->content = $return_struct;
            }else{
                $this->template->return_struct = $return_struct;
                $content = new View('info');
                $this->template->content = $content;
                //* 请求结构数据绑定 */
                $this->template->content->request_data = $request_data;
                //* 返回结构体绑定 */
                $this->template->content->return_struct = $return_struct;
            }
        }
    }
    
    public function classifies()
    {
    	$return_struct = array (
            'status' => 0, 
            'code' => 501, 
            'msg' => 'Not Implemented', 
            'content' => array () 
        );
        try{
            
            //$profiler = new Profiler;
            //* 初始化返回数据 */
            $return_data = array ();
            //* 收集请求数据 ==根据业务逻辑定制== */
            $request_data = $this->input->get();
            //* 实现功能后屏蔽此异常抛出 */
            //throw new MyRuntimeException('Not Implemented',501);
            //* 权限验证 */
            $site_ids = role::get_site_ids();
            if (empty($site_ids))
            {
            	throw new MyRuntimeException(Kohana::lang('o_global.access_denied'), 403);
            }
            
            if (empty($request_data['site_id']) OR !in_array($request_data['site_id'], $site_ids))
            {
            	throw new MyRuntimeException(Kohana::lang('o_global.access_denied'), 403);
            }
            
            $classifies = ClassifyService::get_instance()->query_assoc(array(
    			'where'   => array(
    				'site_id' => $request_data['site_id']
            	),
    			'orderby' => array(
    				'id' => 'ASC',
    			),
    		));
        	
    		$html = '<option value="-1">通用商品类型</option>';
    		foreach ($classifies as $classify)
    		{
    			$html .= '<option value="'.$classify['id'].'">'.htmlspecialchars($classify['name']).'</option>';
    		}
    		
            //* 补充&修改返回结构体 */
            $return_struct['status'] = 1;
            $return_struct['code'] = 200;
            $return_struct['msg'] = '';
            $return_struct['content'] = $html;
            
            //* 请求类型 */
            if($this->is_ajax_request()){
                // ajax 请求
                // json 输出
                $this->template->content = $return_struct;
            }else{
            	throw new MyRuntimeException('Not Implemented');
                // html 输出
                //* 模板输出 */
                $content = new View($this->package . '/' . $this->class_name . '/' . __FUNCTION__);
                //* 变量绑定 */
                $this->template->title = Kohana::config('site.name');
                $this->template->content = $content;
                //* 请求结构数据绑定 */
                $this->template->content->request_data = $request_data;
                //* 返回结构体绑定 */
                $this->template->content->return_struct = $return_struct;
                //:: 当前应用专用数据
                $this->template->content->site_id = $site_id;
                $this->template->content->sites   = $sites;
            	$this->template->content->categorys_tree = $categorys_tree;
            } // end of request type determine
        }catch(MyRuntimeException $ex){
            $return_struct['status'] = 0;
            $return_struct['code'] = $ex->getCode();
            $return_struct['msg'] = $ex->getMessage();
            //TODO 异常处理
            //throw $ex;
            if($this->is_ajax_request()){
                $this->template->content = $return_struct;
            }else{
                $this->template->return_struct = $return_struct;
                $content = new View('info');
                $this->template->content = $content;
                //* 请求结构数据绑定 */
                $this->template->content->request_data = $request_data;
                //* 返回结构体绑定 */
                $this->template->content->return_struct = $return_struct;
            }
        }
    }
}