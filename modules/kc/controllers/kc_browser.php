<?php
/**
 * KCFinder插件的操作入口
 *
 * @package modules.kc
 * @author weizhifeng
 **/
class Kc_browser_Controller extends Kc_uploader_Controller{
    /**
     * 默认模板
     *
     * @var string
     **/
    public $template = 'kc_template'; 

    /**
     * 是否自动render模板，默认为TRUE
     *
     * @var bool
     **/
    public $auto_render = TRUE;
    //public $root_forder = 
    

    /**
     * 操作类型
     *
     * @var string
     **/
    private $action;

    /**
     * Mykc_folder实例
     *
     * @var obj
     **/
    private $kc_folder;

    /**
     * Mykc_image实例
     *
     * @var obj
     **/
    private $kc_image;

    public function __construct()
    {
        parent::__construct();
        $this->kc_folder = Mykc_folder::instance();
        $this->kc_image  = Mykc_image::instance();
        // 对操作请求类型进行处理，默认是browser
        $act = isset($this->get['act']) ? $this->get['act'] : "browser";
        if (!method_exists($this, "act_$act"))
        {
        	$act = "browser";
        }
        $this->action = $act;
        
        // 当前目录id
        $this->dir_id = $this->dir_exist();
    }

    /**
     * 入口
     *
     * @return void
     * @author weizhifeng
     */
    public function index()
    {
        $method = 'act_' . $this->action;
        // 如果被禁用
        if ($this->config['disabled'])
        {
            $message = Kohana::lang('o_kc.do_not_have_permission_browse');
            if (in_array($this->action, array("browser", "upload")) OR (substr($this->action, 0, 8) == "download"))
            {
                $this->back_msg($message);
            } else {
                header("Content-Type: text/xml; charset={$this->charset}");
                $this->template->content = new View("kc_error");
                $this->template->content->message = $message;
                die();
            }
        }

        if ($this->action == "browser") 
        {
            header("X-UA-Compatible: chrome=1");
            header("Content-Type: text/html; charset={$this->charset}");
        } elseif ((substr($this->action, 0, 8) != "download") AND !in_array($this->action, array("thumb", "upload"))) {
            header("Content-Type: text/xml; charset={$this->charset}");
        } elseif ($this->action != "thumb") {
            header("Content-Type: text/html; charset={$this->charset}");
        }
        
        $this->$method();
    }

    /*================================action================================================*/
    /**
     * 输出显示用的HTML框架，然后调用act_init
     * 来输出显示的数据，并填充到相应的DIV中
     * @return string
     * @author weizhifeng
     **/
    protected function act_browser() 
    {
        //检查超时
        if(!$this->check_time_out())
        {
            remind::set(Kohana::lang('o_global.first_login'), 'login');
        }
        $this->browser_stat_add();
        if (isset($this->get['dir']))
        {
            // dir 要在标题上显示，即类似于/image/test这样的虚拟目录
            $this->session['dir'] = $this->type . '/' . $this->get['dir'];
        } else {
            $this->session['dir'] = $this->type;
        }

        $this->template->content = new View("kc_browser");
        $this->template->content->dir = $this->session['dir'];
        $this->template->content->theme = $this->config['theme'];
        $this->template->content->site_id = 1;
    }

    /**
     * 初始化要显示的数据
     *
     * @return string
     * @author weizhifeng
     **/
    protected function act_init() 
    {
        if(!$this->check_time_out())
        {
            $this->time_error_msg(Kohana::lang('o_kc.time_out'));
        }
        // 获得目录信息
        $tree = $this->kc_folder->get($this->dir_id);

        // 没有子目录
        if ($tree['sub_folder_ids'] == NULL) 
        {
            $tree['has_dirs'] = FALSE;
        } else {
            $tree['has_dirs'] = TRUE;
        }

        // 是否是当前目录
        $tree['current'] = TRUE;

        // 获得子目录的信息
        $tree['dirs'] = $this->get_dirs($this->dir_id);
        if (!is_array($tree['dirs']) OR !count($tree['dirs']))
        {
            unset($tree['dirs']);
        }

        // 生成XML文档
        $tree = $this->xml_tree($tree);

        // 获取目录下文件的信息
        $files = $this->get_files($this->dir_id);
        $dir_writable = TRUE;
        $data = array(
            'tree' => &$tree,
            'files' => &$files,
            'dir_writable' => $dir_writable
        );

        $this->template->content = new View("kc_init");
        $this->template->content->tree = $tree;
        $this->template->content->files = $files;
        $this->template->content->dir_writable = $dir_writable;
        $this->template->content->now_size = $this->kc_image->get_image_size_by_site(1);
        $this->template->content->max_size = Kohana::config('kc.maxSize');
        
        // 获得文件夹下的文件数量和大小
        $dir_info = $this->get_detail($this->dir_id);
        $this->template->content->files_count = $dir_info['count'];
        $this->template->content->files_size  = $dir_info['size'];
    }

    /**
     * 展开目录，右键refresh目录时候会被调用
     * @return array
     * @author weizhifeng
     **/
    protected function act_expand() 
    {
        if(!$this->check_time_out())
        {
            $this->time_error_msg(Kohana::lang('o_kc.time_out'));
        }
        $this->template->content = new View("kc_expand");
        $this->template->content->dirs = $this->get_dirs($this->dir_exist());
    }

    /**
     * 切换目录
     *
     * @return array
     * @author weizhifeng
     **/
    protected function act_ch_dir()
    {
        if(!$this->check_time_out())
        {
            $this->time_error_msg(Kohana::lang('o_kc.time_out'));
        }
        // 目录名称
        $this->session['dir'] = $this->type . '/' . $this->dir;
        
        //目录是否可写
        $folder = $this->kc_folder->get($this->dir_id);
        //var_dump($folder);
        //die();
        //$this->error_msg(Kohana::lang('o_kc.folder_name_begin_with'));
        $dir_writable = $folder['writable'];

        $this->template->content = new View("kc_ch_dir");
        $this->template->content->files = $this->get_files($this->dir_id);
        $this->template->content->dir_writable = $dir_writable;        
        // 容量计算
		$size_info = $this->get_size_by_site_id();
    	$this->template->content->now_size = $size_info['now_size'];
    	$this->template->content->max_size = $size_info['max_size'];
    	
        // 获得文件夹下的文件数量和大小
        $dir_info = $this->get_detail($this->dir_id);
        $this->template->content->files_count = $dir_info['count'];
        $this->template->content->files_size  = $dir_info['size'];
    }

    /**
     * 新建目录
     *
     * @return bool
     * @author weizhifeng
     **/
    protected function act_new_dir() 
    {
        if(!$this->check_time_out())
        {
            $this->time_error_msg(Kohana::lang('o_kc.time_out'));
        }
        if ($this->config['readonly'] || !isset($this->post['dir']) 
            || !isset($this->post['new_dir'])) 
        {
            $this->error_msg(Kohana::lang('o_kc.unknown_error'));
        }

        //新目录名称
        $new_dir_name = trim($this->post['new_dir']);

        if (!strlen($new_dir_name))
        {
            $this->error_msg(Kohana::lang('o_kc.enter_new_folder_name'));	
        }

        if (preg_match('/[\/\\\\]/s', $new_dir_name))
        {
            $this->error_msg(Kohana::lang('o_kc.folder_name_have_unallow_char'));
        }

        if (substr($new_dir_name, 0, 1) == ".")
        {
            $this->error_msg(Kohana::lang('o_kc.folder_name_begin_with'));
        }

        // 新目录内容
        $data = array
            (
                'site_id'        => 1,
                'parent_id'      => $this->dir_id,
                'sub_folder_ids' => '',
                'name'           => $new_dir_name,
                'level_depth'    => 1,
                'date_add'       => date('Y-m-d H:i:s'),
                'date_upd'       => date('Y-m-d H:i:s'),
            );

        // 检查目录是否已经存在
        if ($this->new_dir_exist(1, $this->dir_id, $new_dir_name)) 
        {
            $this->error_msg(Kohana::lang('o_kc.folder_name_exist'));
            // 创建目录成功
        } elseif ($this->kc_folder->create($data) 
            && $this->kc_folder->update_folders_for_folder(1)) {
                $this->template->content = new View("kc_new_dir");
                $this->template->content->charset = $this->charset;
                // 创建目录失败
            } else {
                $this->error_msg(Kohana::lang('o_kc.can_not_create_folder', $new_dir_name));
            } 
    }

    /**
     * 重命名目录
     *
     * @return array
     * @author weizhifeng
     **/
    protected function act_rename_dir() 
    {
        if(!$this->check_time_out())
        {
            $this->time_error_msg(Kohana::lang('o_kc.time_out'));
        }
        if ($this->config['readonly'] || !isset($this->post['dir']) ||
            !isset($this->post['new_name']) || !isset($this->post['old_name'])) 
        {
            $this->error_msg(Kohana::lang('o_kc.unknown_error'));
        }

        //旧目录和新目录名称
        $old_name = trim($this->post['old_name']);
        $new_name = trim($this->post['new_name']);

        if (!strlen($new_name))
        {
            $this->error_msg(Kohana::lang('o_kc.enter_new_folder_name'));	
        }

        if (preg_match('/[\/\\\\]/s', $new_name))
        {
            $this->error_msg(Kohana::lang('o_kc.folder_name_have_unallow_char'));
        }

        if (substr($new_name, 0, 1) == ".")
        {
            $this->error_msg(Kohana::lang('o_kc.folder_name_begin_with'));
        }

        if (strlen($new_name) >= 254)
        {
            $this->error_msg(Kohana::lang('o_kc.folder_name_too_long'));
        }

        $dir_info = $this->kc_folder->get($this->dir_id);

        // 检查新目录名是否存在
        if (($new_name != $old_name) AND $this->new_dir_exist(1, $dir_info['parent_id'], $new_name))
        {
            $this->error_msg(Kohana::lang('o_kc.have_same_name_folder'));
        }

        $data = array('name' => $new_name, 'date_upd' => date('Y-m-d H:i:s'));

        // 重命名
        if ($this->kc_folder->update($this->dir_id, $data)
            && $this->kc_folder->update_folders_for_folder(1)) 
        {
            $this->template->content = new View("kc_rename_dir");
            $this->template->content->name = $new_name;
        } else {
            $this->error_msg(Kohana::lang('o_kc.can_not_rename_folder'));
        }
    }

    /**
     * 删除目录 
     *
     * @return array
     * @author weizhifeng
     **/
    protected function act_delete_dir() 
    {
        if(!$this->check_time_out())
        {
            $this->time_error_msg(Kohana::lang('o_kc.time_out'));
        }
        if ($this->config['readonly'] || !isset($this->post['dir']) 
            || !strlen(trim($this->post['dir'])))
        {
            $this->error_msg(Kohana::lang('o_kc.unknown_error'));
        }

        $folder = $this->kc_folder->get($this->dir_id);

        //删除时不能删除根文件夹
        $folder['level_depth'] == 1 && $this->error_msg(Kohana::lang('o_kc.root_can_not_delete'));

        //删除时删除所有的子文件夹及其图片
        $tree = $this->kc_folder->get_tree_by_folder_id($this->dir_id);
        $false_count = 0;
        foreach($tree as $key_folder => $_folder)
        {
            (!$this->kc_folder->delete($_folder['id'])) &&  ++$false_count;
            $this->kc_image->delete_by_folder_id($_folder['id']);
        }

        if ($this->kc_folder->update_folders_for_folder(1) && (!$false_count))
        {
            $this->template->content = new View("kc_delete_dir");
            $size_info = $this->get_size_by_site_id();
            $this->template->content->now_size = $size_info['now_size'];
            $this->template->content->max_size = $size_info['max_size'];
        } else {
            $this->error_msg(Kohana::lang('o_kc.can_not_delete_folder'));
        }
    }

    /**
     * 上传文件
     *
     * @return void
     * @author weizhifeng
     **/
    protected function act_upload() 
    {
        if(!$this->check_time_out())
        {
            $this->time_error_msg(Kohana::lang('o_kc.time_out'));
        }
        if ($this->config['readonly'] || !isset($this->post['dir']))
        {
            $this->error_msg(Kohana::lang('o_kc.unknown_error'));
        }

        if(!$this->dir_writable($this->dir_id)) 
        {
            $this->error_msg(Kohana::lang('o_kc.can_not_write_folder'));
        }

        // 上传文件校验
        $message = $this->check_uploaded_file();

        if ($message !== TRUE) 
        {
            $this->error_msg($message);
        }

        // 容量限制		
        $size_info = $this->get_size_by_site_id();

        if ($size_info['now_size'] >= $size_info['max_size'])
        {
            $this->error_msg(Kohana::lang('o_kc.capacity_full'));
        }

        // 上传文件
        $attachment_id = $this->do_upload($this->dir_id);

        // 容量计算
        $size_info = $this->get_size_by_site_id();
        $now_size = $size_info['now_size'];
        $max_size = $size_info['max_size'];
        echo '/' . $now_size . '|' . $max_size;
        die();
    }

    /**
     * 下载文件
     *
     * @return void
     * @author weizhifeng
     **/
    protected function act_download() 
    {
        if(!$this->check_time_out())
        {
            $this->time_error_msg(Kohana::lang('o_kc.time_out'));
        }
        if (!isset($this->post['attach_id']) || !isset($this->post['file']))
        {
            $this->error_msg(Kohana::lang('o_kc.file_not_exist'));
        }
        
        $attach_id =  trim($this->post['attach_id']);
        $file = trim($this->post['file']);
        
        $attach_data = $this->kc_image->get_attach($attach_id);
        
        //d($attach_data);
        
        if (isset($attach_data['error']) AND $attach_data['error'] == 'not_found')
        {
            $this->error_msg(Kohana::lang('o_kc.file_not_exist'));
        }

        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Cache-Control: private", false);
        header("Content-Type: application/octet-stream");
        header('Content-Disposition: attachment; filename="' . str_replace('"', "_", $file) . '"');
        header("Content-Transfer-Encoding:- binary");
        header("Content-Length: " . $attach_data['filesize']);
        echo $attach_data['filedata'];
        die;
    }

    /**
     * 重命名文件
     *
     * @return bool
     * @author weizhifeng
     **/
    protected function act_rename()
    {
        if(!$this->check_time_out())
        {
            $this->time_error_msg(Kohana::lang('o_kc.time_out'));
        }
        if ($this->config['readonly'] || !isset($this->post['file']) 
            || !isset($this->post['new_name']) || !isset($this->post['file_id'])
            || !isset($this->post['dir_id']))
        {
            $this->error_msg(Kohana::lang('o_kc.file_not_exist'));
        }

        $file_id  = (int) ($this->post['file_id']);
        $new_name = trim($this->post['new_name']);
        $old_name = trim($this->post['file']);
        $dir_id   = (int) ($this->post['dir_id']);

        if (!strlen($new_name))
        {
            $this->error_msg(Kohana::lang('o_kc.enter_new_file_name'));
        }

        if (preg_match('/[\/\\\\]/s', $new_name))
        {
            $this->error_msg(Kohana::lang('file_name_have_unallow_char'));
        }

        if (substr($new_name, 0, 1) == ".") 
        {
            $this->error_msg(Kohana::lang('o_kc.file_name_begin_with'));
        }
        if (strlen($new_name) >= 254)
        {
            $this->error_msg(Kohana::lang('o_kc.file_name_too_long'));
        }

        // 文件检查
        if(($new_name != $old_name) AND $this->file_exists(1, $dir_id, $new_name))
        {
            $this->error_msg(Kohana::lang('o_kc.file_name_exist'));
        }

        $ext = kc_file::get_extension($new_name);
        if (!$this->validate_extension($ext, $this->type))
        {
            $this->error_msg(Kohana::lang('o_kc.denied_file_extension'));
        }

        $data = array(
            'image_name' => $new_name, 
            'date_upd'   => date('Y-m-d H:i:s')
        );	
        if ($this->kc_image->update($file_id, $data) === FALSE) 
        {
            $this->error_msg(Kohana::lang('o_kc.file_rename_failed'));
        }

        $this->template->content = new View("kc_rename");
    }

    /**
     * 删除文件
     *
     * @return bool
     * @author weizhifeng
     **/
    protected function act_delete()
    {
        if(!$this->check_time_out())
        {
            $this->time_error_msg(Kohana::lang('o_kc.time_out'));
        }
        if ($this->config['readonly'] || !isset($this->post['file_id']))
        {
            $this->error_msg(Kohana::lang('o_kc.file_not_exist'));
        }

        $file_id = (int) $this->post['file_id'];

        if ($this->kc_image->delete($file_id))
        {
            $this->template->content = new View('kc_delete');
            $size_info = $this->get_size_by_site_id();
            $this->template->content->now_size = $size_info['now_size'];
            $this->template->content->max_size = $size_info['max_size'];
        } else {
            $this->error_msg(Kohana::lang('o_kc.file_rm_failed'));
        }
    }

    /**
     * 批量删除文件
     *
     * @return void
     * @author weizhifeng
     **/
    protected function act_rm_cbd() 
    {
        if(!$this->check_time_out())
        {
            $this->time_error_msg(Kohana::lang('o_kc.time_out'));
        }
        $file_count = count($this->post['file_ids']);
        if ($this->config['readonly'] || !isset($this->post['file_ids']) ||
            !is_array($this->post['file_ids']) || !$file_count)
        {
            $this->errorMsg(Kohana::lang('o_kc.file_not_exist'));
        }

        // 批量删除文件
        for ($i = 0; $i < $file_count; $i++) 
        { 
            $this->kc_image->delete($this->post['file_ids'][$i]);
        }

        // 容量计算
        $size_info = $this->get_size_by_site_id();
        $now_size = $size_info['now_size'];
        $max_size = $size_info['max_size'];

        echo '<root><size now="' . $now_size . '" max="' . $max_size . '"/></root>';
        die();
    }

    /**
     * 获得目录下的文件信息
     * @param int $dir_id
     * @return array
     * @author weizhifeng
     **/
    protected function get_files($dir_id) 
    {
        $files = $this->kc_image->get_files($dir_id);
        $file_count = count($files);
        $return = array();
        for ($i = 0; $i < $file_count; $i++) 
        { 
            $return[] = array
                (
                    'file_id'     => $files[$i]['id'],
                    'attach_id'   => $files[$i]['attach_id'],
                    'name'        => $files[$i]['image_name'],
                    'size'        => $files[$i]['image_size'],
                    'mtime'       => strtotime($files[$i]['date_upd']),
                    'date'        => $files[$i]['date_upd'],
                    'readable'    => $files[$i]['readable'],
                    'writable'    => $files[$i]['writable'],
                    'big_icon'    => $files[$i]['big_icon'],
                    'small_icon'  => $files[$i]['small_icon'],
                    'thumb'       => $files[$i]['thumb'],
                    'small_thumb' => $files[$i]['small_thumb']
                );
        }

        return $return;
    }

    /**
     * 根据$tree内容生成XML文档
     *
     * @return string
     * @author weizhifeng
     **/
    protected function xml_tree($tree) 
    {
        // 父级目录信息
        $xml = '<dir readable="' . ($tree['readable'] ? "yes" : "no") . '" writable="' . ($tree['writable'] ? "yes" : "no") . '" removable="' . ($tree['removable'] ? "yes" : "no") . '" hasDirs="' . ($tree['has_dirs'] ? "yes" : "no") . '"' . (isset($tree['current']) ? ' current="yes"' : '') . ' dirId="' . $tree['id'] . '" ><name>' . kc_text::xml_data($tree['name']) . '</name>';
        // 子目录信息
        if (isset($tree['dirs']) && is_array($tree['dirs']) && count($tree['dirs'])) {
            $xml .= "<dirs>";
            foreach ($tree['dirs'] as $dir)
                $xml .= $this->xml_tree($dir);
            $xml .= "</dirs>";
        }
        $xml .= '</dir>';
        return $xml;
    }

    /**
     * 检查目录是否存在
     * @return Int
     * @author weizhifeng
     **/
    protected function dir_exist()
    {
        //对当前的目录进行检查
        if (isset($this->post['dir_id'])) 
        {
            $folder_id = (int) $this->post['dir_id'];	
        } else {
            //检查站点有没有根目录，如没有则添加
            $check_count = $this->kc_folder->count(array(
                'where' => array(
                    'site_id' => 1,
                    'level_depth' => 1,
                ),
            ));
            if($check_count <= 0)
            {
                $root_folder = array(
                    'site_id' => 1,
                    'parent_id' => '0',
                    'sub_folder_ids' => '',
                    'name' => 'image',
                    'level_depth' => 1,
                    'date_add' => date('Y-m-d H:i:s'),
                    'date_upd' => date('Y-m-d H:i:s'),
                );
                $folder_id = $this->kc_folder->create($root_folder);
            }else{
                $root_folder = $this->kc_folder->lists(array(
                    'where' => array(
                        'site_id' => 1,
                        'level_depth' => 1,
                    ),
                ));
                $folder_id = $root_folder[0]['id'];
            }
        }

        // 检查目录是否存在
        $folder = $this->kc_folder->get($folder_id);
        if($folder['id'] == 0)
        {
            $this->error_msg(Kohana::lang('o_kc.folder_not_exist'));
        } else {
            return $folder_id;
        }
    }

    /**
     * 检查目录下的文件是否存在
     * @param int $site_id 站点id
     * @param int $dir_id 目录id
     * @param string $image_name 文件名
     * 
     * @return bool
     * @author weizhifeng
     **/
    protected function file_exists($site_id, $dir_id, $image_name)
    {
        $check_count = $this->kc_image->count(array(
            'where' => array
            (
                'site_id' => 1,
                'kc_folder_id' => $dir_id,
                'image_name' => $image_name,
            ),
        ));

        if($check_count >= 1)
        {
            return TRUE;
        } else {
            return FALSE;	
        }
    }

    /**
     * 根据父级目录的id和目录名来检查是否已经存在此目录
     * @param int $site_id
     * @param int $pid
     * @param string $dirname
     * @return bool
     * @author weizhifeng
     **/
    protected function new_dir_exist($site_id, $pid, $dirname)
    {
        $check_count = Mykc_folder::instance()->count(
            array
            (
                'where' => array
                (
                    'site_id' => 1,
                    'name' => $dirname,
                    'parent_id' => $pid
                )
            )
        );

        if($check_count >= 1)
        {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * 是否可读
     * @param int $dir_id
     *
     * @return bool
     * @author weizhifeng
     **/
    protected function dir_readable($dir_id)
    {
        return TRUE;
    }

    /**
     * @param int $dir_id
     *
     * @return bool
     * @author weizhifeng
     **/
    protected function dir_writable($dir_id)
    {
        return TRUE;
    }

    /**
     * 获得一级子目录
     * @param int $dir_id
     * @return array
     * @author weizhifeng
     **/
    protected function get_dirs($dir_id) 
    {
        $folder = $this->kc_folder->get($dir_id);
        $sub_folders = array();
        if(isset($folder['sub_folder_ids']) && $folder['sub_folder_ids'] != NULL)
        {
            $sub_folder_ids = explode(',', $folder['sub_folder_ids']);
            foreach($sub_folder_ids as $_sub_folder_id)
            {
                $sub_folder = $this->kc_folder->get($_sub_folder_id);
                $sub_folders[] = array
                    (
                        'id'        => $sub_folder['id'],
                        'name'      => $sub_folder['name'],
                        'readable'  => $sub_folder['readable'],
                        'writable'  => $sub_folder['writable'],
                        'removable' => $sub_folder['removable'],				
                        'has_dirs'  => $sub_folder['sub_folder_ids'] != NULL ? TRUE : FALSE
                    );
            }
        }
        return $sub_folders;
    }

    /**
     * 获得站点的容量信息
     *
     * @return array
     * @author weizhifeng
     **/
    protected function get_size_by_site_id()
    {
        $now_size = $this->kc_image->get_image_size_by_site(1);
        $max_size = Kohana::config('kc.maxSize');
        return array('now_size' => $now_size, 'max_size' => $max_size);
    }

    /**
     * 错误处理
     * @param string $message
     * @return void
     * @author weizhifeng
     **/
    protected function error_msg($message, $data=null) 
    {
        if (in_array($this->action, array("thumb", "upload", "download", "downloadDir"))) 
        {
            die($message);
        }

        if (($this->action === null) || ($this->action == "browser"))
        {
            $this->back_msg($message);
        } else {
            $output = '<root>';
            if (is_array($message)) 
            {
                foreach ($message as $msg)
                {
                    $output .= '<error>' . kc_text::xml_data($msg) . '</error>';
                }	
            } else {
                $output .= '<error>' . kc_text::xml_data($message) . '</error>';
            }
            $output .= '</root>';
            header("Content-Type: text/xml; charset={$this->charset}");
            die($output);
        }
    }

    /**
     * 超时错误处理
     **/
    protected function time_error_msg($message, $data=null) 
    {
        $output = '<root>';
        if (is_array($message)) 
        {
            foreach ($message as $msg) 
            {
                $output .= '<error>' . kc_text::xml_data($msg) . '</error>';
            }	
        } else {
            $output .= '<error>' . kc_text::xml_data($message) . '</error>';
        }
        $output .= '</root>';
        header("Content-Type: text/xml; charset=UTF-8");
        die($output);
    }

    /**
     * 得到文件夹中文件个数及大小
     * 包括其所有的子文件夹
     **/
    protected function get_detail($folder_id)
    {
        $image_count = 0;
        $image_total_size = 0;

        $folder_ids = array();
        $folders = Mykc_folder::instance()->get_tree_by_folder_id($folder_id);
        foreach($folders as $_folder)
        {
            $folder_ids[] = $_folder['id'];
        }

        $images = Mykc_image::instance()->lists(array(
            'where' => array(
                'kc_folder_id' => $folder_ids,
            ),
        ));
        foreach($images as $_img)
        {
            $image_count++;
            $image_total_size +=$_img['image_size'];
        }
        $return['count'] = $image_count;
        $return['size'] = $image_total_size;
        return $return;
    }

    /**
     * 添加客户端浏览器信息
     */
    protected function browser_stat_add()
    {
        $browser_stat = kc_browser::get_agent_detail();
        //add
        $check = Mykc_browser_stat::instance()->lists(array(
            'where' => array(
                'agent_detail' => $browser_stat['agent_detail'],
                'ip' => $browser_stat['ip'],
            ),
        ));
        if(!empty($check))
        {
            foreach($check as $_check)
            {
                Mykc_browser_stat::instance()->update($_check['id'],
                    array(
                        'quantity' => $_check['quantity'] + 1,
                        'date_upd' => date('Y-m-d H:i:s'),
                    ));
            }
        }else{
            $browser_stat['date_add'] = date('Y-m-d H:i:s');
            $browser_stat['date_upd'] = date('Y-m-d H:i:s');
            $browser_stat['quantity'] = 1;
            Mykc_browser_stat::instance()->create($browser_stat);
        }
    }

    protected function check_time_out()
    {
        /**
         * 判断用户登录情况
         */
        if (isset($_REQUEST['session_id'])) 
        {
            $session = Session::instance($_REQUEST['session_id']);
            $manager = role::get_manager($_REQUEST['session_id']);
        }else{
            $session = Session::instance();
            $manager = role::get_manager();
        }
        if ($manager)
        {
            $active_time = $session->get('Opococ_manager_active_time');//用户最后操作时间
            $session->set('Opococ_manager_active_time', time());//用户最后操作时间
            $login_ip = $session->get('Opococ_manager_login_ip');//用户登录的IP

            //操作超时
            if ((time() - $active_time) > Kohana::config('login.time_out'))
            {
                $session->delete('Opococ_manager');
                $session->delete('Opococ_manager_active_time');
                $session->delete('Opococ_manager_login_ip');
                return false;
            }

            //用户IP(登录状态更换IP需要重新登录)
            $ip = tool::get_long_ip();
            if ($ip <> $login_ip)
            {
                return false;
            }
            $this->manager = $manager;
            $this->manager_id = $manager['id'];
            $this->manager_name = $manager['name'];
            $this->manager_is_admin = $manager['is_admin'];
            $this->template->manager_data = $manager;
        }else{
            return false;
        }
        return true;
    }
}
