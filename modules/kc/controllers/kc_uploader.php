<?php
/**
 * 文件上传控制器
 *
 * @package modules.kc
 * @author weizhifeng
 **/
class Kc_uploader_Controller extends Kc_template_Controller {
    protected $config = array();
    protected $opener = array();
    protected $type;
    protected $types = array();
    protected $charset;
    protected $file;
    protected $labels = array();
    protected $get;
    protected $post;
    protected $cookie;
    protected $session;
	protected $dir_id;
	protected $img_dir_name = 'kc';
	public  $site_id = 1;
	
    public function __get($property) 
    {
        return property_exists($this, $property) ? $this->$property : null;
    }

    public function __construct() 
    {
    	parent::__construct();
        // DISABLE MAGIC QUOTES
        if (function_exists('set_magic_quotes_runtime'))
            @set_magic_quotes_runtime(false);
        
        // Charset
        $this->charset = Kohana::config('kc.charset');
        
        // INPUT INIT
        $this->get = $this->input->get();
        $this->post = $this->input->post();
        $this->cookie = $this->input->cookie();

        // LINKING UPLOADED FILE
        if (count($_FILES))
        {
        	$this->file = &$_FILES[key($_FILES)];	
        }

        // SETTING UP SESSION
        $session_life_time = Kohana::config('kc._sessionLifetime');
        $session_dir       = Kohana::config('kc._sessionDir');
        $session_domain    = Kohana::config('kc._sessionDomain');
        
        if ($session_life_time)
	    {
	        ini_set('session.gc_maxlifetime', $session_life_time * 60);	
	    }
            
        if ($session_dir)
	    {
	     	ini_set('session.save_path', $session_dir);   	
	    }
            
        if ($session_domain)
	    {
	    	ini_set('session.cookie_domain', $session_domain);
	    }

        // RELOAD DEFAULT CONFIGURATION
		$this->config = Kohana::config('kc');

        // LOAD SESSION CONFIGURATION IF EXISTS
        if (isset($this->config['_sessionVar']) && is_array($this->config['_sessionVar'])) 
        {
            foreach ($this->config['_sessionVar'] as $key => $val)
            {
            	if ((substr($key, 0, 1) != "_") && isset($this->config[$key]))
	            {
	            	$this->config[$key] = $val;   	
	            }
            }
                       
        	if (!isset($this->config['_sessionVar']['self']))
            {
            	$this->config['_sessionVar']['self'] = array();	
            }
                
            $this->session = &$this->config['_sessionVar']['self'];
        } else {
        	$this->session = &$_SESSION;
        }

        // GET TYPE DIRECTORY
        $this->types = &$this->config['types'];
        $firstType = array_keys($this->types);
        $firstType = $firstType[0];
        $this->type = (
            isset($this->get['type']) &&
            isset($this->types[$this->get['type']])
        )
            ? $this->get['type'] : $firstType;

        // LOAD DIRECTORY TYPE SPECIFIC CONFIGURATION IF EXISTS
        if (is_array($this->types[$this->type])) 
        {
            foreach ($this->types[$this->type] as $key => $val)
            {
            	if (in_array($key, $this->typeSettings))
	            {
	            	$this->config[$key] = $val;	
	            }
            }
            
            $this->types[$this->type] = isset($this->types[$this->type]['type'])
            ? $this->types[$this->type]['type'] : "";
        }

        // COOKIES INIT
        if (isset($this->config['cookieDomain']) AND !strlen($this->config['cookieDomain']))
        {
        	$this->config['cookieDomain'] = $_SERVER['HTTP_HOST'];
        }
            
        if (isset($this->config['cookiePath']) AND !strlen($this->config['cookiePath']))
        {
        	$this->config['cookiePath'] = "/";
        }
        
        // HOST APPLICATIONS INIT
        if (isset($this->get['CKEditorFuncNum']))
	    {
	        $this->opener['CKEditor']['funcNum'] = $this->get['CKEditorFuncNum'];	
	    }
            
        if (isset($this->get['opener']) && (strtolower($this->get['opener']) == "tinymce") 
        	&& isset($this->config['_tinyMCEPath']) && strlen($this->config['_tinyMCEPath']))
    	{
    		$this->opener['TinyMCE'] = true;
    	}
    	
    	
    	// 通过参数传递过来的site_id 优先级高
    	$site_id = isset($this->get['site_id']) ? $this->get['site_id'] : 0;
    	
    	// 通过全局方法获得的site_id
    	$site_id2 = site::id();
    	
    	$this->site_id = $site_id > 0 ? $site_id : $site_id2;
    	if ($this->site_id == 0)
    	{
    		$this->back_msg(Kohana::lang('o_global.select_site'));
    	}
    }
 
    /**
     * 上传文件检查
     *
     * @return mixed
     * @author weizhifeng
     **/
    protected function check_uploaded_file() 
    {
        $config = &$this->config;
        $file = &$this->file;
        if (!is_array($file) || !isset($file['name']))
        {
            return Kohana::lang('o_kc.unknown_error');
        }           
        $extension = kc_file::get_extension($file['name']);
        $typePatt = strtolower(kc_text::clear_whitespaces($this->types[$this->type]));

        // 上传错误处理
        if ($file['error'])
        {
        	switch ($file['error']) {
        		case UPLOAD_ERR_INI_SIZE:
        			//return Kohana::lang('o_kc.upload_exceed_size', ini_get('upload_max_filesize'));
        			break;
        		case UPLOAD_ERR_FORM_SIZE:
        			//return Kohana::lang('o_kc.upload_exceed_size', $this->get['MAX_FILE_SIZE']);
        			break;
    			case UPLOAD_ERR_PARTIAL:
    				return Kohana::lang('o_kc.file_part_upload');
    				break;
				case UPLOAD_ERR_NO_FILE:
					return Kohana::lang('o_kc.no_file_upload');
					break;
				case UPLOAD_ERR_NO_TMP_DIR:
					return Kohana::lang('o_kc.miss_temp_folder');
					break;
				case UPLOAD_ERR_CANT_WRITE:
					return Kohana::lang('o_kc.fail_write_file');
					break;
        		default:
        			return Kohana::lang('o_kc.unknown_error');
        			break;
        	}
        // 隐藏文件处理
        } elseif (substr($file['name'], 0, 1) == ".") {
            return Kohana::lang('o_kc.file_name_begin_with');	
        // 扩展名校验
        } elseif (!$this->validate_extension($extension, $this->type)) {
            return Kohana::lang('o_kc.denied_file_extension');
        }
        
        return TRUE;
    }
    
    /**
     * 检查扩展名是否合法
     * @param $ext 
     * @param $type
     * @return Bool
     * @author weizhifeng
     **/
    protected function validate_extension($ext, $type) 
    {
        $ext = trim(strtolower($ext));
        if (!isset($this->types[$type]))
        {
        	return FALSE;
        }    
        
        $aollowed_exts = Kohana::config('kc.aollowedExts');
        return in_array($ext, $aollowed_exts);
    }
    
	/**
	 * 文件上传处理
	 * @param int $folder_id
	 * @return array attachment_ids
     * @author weizhifeng
     **/
    protected function do_upload($folder_id)
    {
        // 上传的表单域名字
        $attach_field           = 'upload';
        // 附件应用类型
        $attach_app_type        = 'productPicAttach';

        $file_count_total       = 0;
        $file_size_total        = 0;

        // 上传文件meta信息
        $file_meta_data         = array ();

        // 如果有上传请求
        if(!isset($_FILES[$attach_field])||empty($_FILES[$attach_field]))
        {
            exit(Kohana::lang('o_global.bad_request'));
        }

        $title = array ();

        //读取当前应用配置
        $attach_setup           = Kohana::config('attach.' . $attach_app_type);
        $mime_type2postfix      = Kohana::config('mimemap.type2postfix');
        $mime_postfix2type      = Kohana::config('mimemap.postfix2type');

        $file_type_current = FALSE;
        $file_size_current = '';
        $file_mime_current = '';

        // 如果上传标志成功
        if(( int ) $_FILES[$attach_field]['error'] === UPLOAD_ERR_OK)
        {
            if(!is_uploaded_file($_FILES[$attach_field]['tmp_name']))
            {
                $this->error_msg(Kohana::lang('o_promotion.file_not_uploaded'));
            }
            $file_size_current = filesize($_FILES[$attach_field]['tmp_name']);
            if($attach_setup['fileSizePreLimit'] > 0 && $file_size_current > $attach_setup['fileSizePreLimit']){
                $this->error_msg(Kohana::lang('o_kc.file_big_than_max', $attach_setup['fileSizePreLimit']/1024/1024));
            }
            // 尝试通过图片类型判断
            $file_type_current = $file_type_current ? 
                $file_type_current : 
                kc_page::get_image_type($_FILES[$attach_field]['tmp_name']);             
            // 尝试通过Mime类型判断
            $file_type_current = $file_type_current ? 
                $file_type_current : 
                kc_page::get_file_type($attach_field);
            // 尝试通过后缀截取
            $file_type_current = $file_type_current ? 
                $file_type_current : 
                kc_page::get_postfix($attach_field); 

            if(!empty($attach_setup['allowTypes']) && !in_array($file_type_current, $attach_setup['allowTypes']))
            {
                $this->error_msg(Kohana::lang('o_promotion.file_type_invalid') );
            }


            // 当前文件mime类型
            $file_mime_current = isset($_FILES[$attach_field]['type']) ? $_FILES[$attach_field]['type'] : '';
            // 检测规整mime类型
            if(!array_key_exists($file_mime_current, $mime_type2postfix))
            {
                if(array_key_exists($file_type_current, $mime_postfix2type))
                {
                    $file_mime_current = $mime_postfix2type[$file_type_current];
                }else{
                    $file_mime_current = 'application/octet-stream';
                }
            }

            //存储文件meta信息
            $file_meta_data = array (
                'site_id'   => $this->site_id, 
                'name'      => strip_tags(trim($_FILES[$attach_field]['name'])), 
                'size'      => $file_size_current, 
                'type'      => $file_type_current, 
                'mime'      => $file_mime_current, 
                'tmpfile'   => $_FILES[$attach_field]['tmp_name'] 
            );
            // 设置上传总数量
            $file_count_total += 1;
            // 设置上传总大小
            $file_size_total += $file_size_current;
            
        }else{
            $this->error_msg(Kohana::lang('o_product.pic_upload_failed'));
        }
        if($attach_setup['fileCountLimit'] > 0 && $file_count_total > $attach_setup['fileCountLimit'])
        {
            $this->error_msg(Kohana::lang('o_promotion.file_count_limit') . $attach_setup['fileCountLimit']);
        }
        if($attach_setup['fileSizeTotalLimit'] > 0 && $file_size_total > $attach_setup['fileSizeTotalLimit'])
        {
            $this->error_msg(Kohana::lang('o_promotion.file_size_total_limit') .
                $attach_setup['fileSizeTotalLimit'] . 
                Kohana::lang('o_promotion.size') . 
                $file_size_total);
        }
        
        $att = AttService::get_instance($this->img_dir_name);
        $attachment_id = $att->save_default_img($file_meta_data['tmpfile']);
        
        if(!$attachment_id){
            throw new MyRuntimeException(Kohana::lang('o_product.phprpc_pic_save_failed'), 500);
        }
        
        /*$attachment_id = Mykc_image::instance()->add_attach($file_meta_data);
        if (!is_numeric($attachment_id))
        {
            $this->error_msg(Kohana::lang('o_product.phprpc_pic_save_failed'));
        }*/
        $image_name =  strip_tags(trim($_FILES[$attach_field]['name']));
        while(Mykc_image::instance()->count(array('where' => array(
            'site_id' => $this->site_id,
            'kc_folder_id' => $folder_id,
            'image_name' => $image_name,),)) 
            >= 1)
            {
                $point_pos = strrpos($image_name, '.');
                if($point_pos !== FALSE)
                {
                    $postfix = substr($image_name, $point_pos + 1);
                    $pre_name = substr($image_name, 0, $point_pos);
                    $image_name = $pre_name.'_'.$attachment_id.'.'.$postfix;
                }else{
                    $image_name = $image_name.'_'.$attachment_id;
                }
            }
        $image_data = array(
            'site_id' => $this->site_id,
            'kc_folder_id' => $folder_id,
            'attach_id' => $attachment_id,
            'image_type' => $file_type_current,
            'image_size' => $file_size_current,
            'image_name' => $image_name,
            'image_mime' => $file_mime_current,
            'date_add' => date('Y-m-d H:i:s'),
            'date_upd' => date('Y-m-d H:i:s'),
        );
        $image_id = Mykc_image::instance()->create($image_data);
        if(!$image_id)
        {
            exit(Kohana::lang('o_kc.can_not_write_folder'));
        }
        return $attachment_id;
    }

    /**
     * 
     *
     * @return void
     * @author weizhifeng
     **/
    protected function label($string, array $data=null) 
    {
        $return = isset($this->labels[$string]) ? $this->labels[$string] : $string;
        if (is_array($data))
            foreach ($data as $key => $val)
                $return = str_replace("{{$key}}", $val, $return);
        return $return;
    }

    protected function back_msg($message, array $data=null) 
    {
        $this->callback("", $message);
		die;
    }

    protected function callback($url, $message="") 
    {
        $message = kc_text::js_value($message);
        $CKfuncNum = isset($this->opener['CKEditor']['funcNum'])
            ? $this->opener['CKEditor']['funcNum'] : 0;
        if (!$CKfuncNum) $CKfuncNum = 0;
        header("Content-Type: text/html; charset={$this->charset}");

?><html>
<body>
        <script type='text/javascript'>
        var kc_CKEditor = (window.parent && window.parent.CKEDITOR)
            ? window.parent.CKEDITOR.tools.callFunction
            : ((window.opener && window.opener.CKEDITOR)
            ? window.opener.CKEDITOR.tools.callFunction
            : false);
        var kc_FCKeditor = (window.opener && window.opener.OnUploadCompleted)
            ? window.opener.OnUploadCompleted
            : ((window.parent && window.parent.OnUploadCompleted)
            ? window.parent.OnUploadCompleted
            : false);
        var kc_Custom = (window.parent && window.parent.KCFinder)
            ? window.parent.KCFinder.callBack
            : ((window.opener && window.opener.KCFinder)
            ? window.opener.KCFinder.callBack
            : false);
        if (kc_CKEditor)
            kc_CKEditor(<?php echo $CKfuncNum ?>, '<?php echo $url ?>', '<?php echo $message ?>');
        if (kc_FCKeditor)
            kc_FCKeditor(<?php echo strlen($message) ? 1 : 0 ?>, '<?php echo $url ?>', '', '<?php echo $message ?>');
        if (kc_Custom) {
            if (<?php echo strlen($message) ?>) alert('<?php echo $message ?>');
            kc_Custom('<?php echo $url ?>');
        }
        if (!kc_CKEditor && !kc_FCKeditor && !kc_Custom)
            alert("<?php echo $message ?>");
        </script>
</body>
</html><?php

    }
}
