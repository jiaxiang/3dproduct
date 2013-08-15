<?php defined('SYSPATH') OR die('No direct access allowed.');

class Mykc_image_Core extends Mykc{

    private static $instances;

    /**
     * 单实例方法
     *
     * @param $id
     */
    public static function & instance($id = 0)
    {
        if (!isset(self::$instances[$id]))
        {
            $class = __CLASS__;
            self::$instances[$id] = new $class($id);
        }
        return self::$instances[$id];
    }

    /**
     * 清除单体缓存
     *
     * @param $kc_folder_id
     */
    public function clear($kc_folder_id)
    {
        $cache = Mycache::instance();
        $cache_key = $this->object_name.'_'.$kc_folder_id;

        $cache->delete($cache_key);
    }

    /**
     * 添加一个图片数据
     *
     * @param Array $data
     */
    public function create($data)
    {
        $this->clear($data['kc_folder_id']);
        $id = parent::create($data);
        return $id;
    }


    /**
     * 更新一个图片数据
     *
     * @param Int $id
     * @param Array $data
     * @return Boolean
     */
    public function update($id, $data)
    {
        $kc_image = $this->get($id);
        $this->clear($kc_image['kc_folder_id']);
        return parent::update($id, $data);
    }

    /**
     * 删除一个图片数据
     *
     * @param Int $id
     * @return Boolean
     */
    public function delete($id)
    {
        $kc_image = $this->get($id);
        $this->clear($kc_image['kc_folder_id']);
        $result = parent::delete($id);
        $this->delete_attach($kc_image['attach_id']);
        return $result;
    }

    //业务逻辑
    
    /**
     * 依照文件夹id删除图片
     *
     * @param Int $folder_id
     * @return Boolean
     */
    public function delete_by_folder_id($folder_id)
    {
        $images = $this->get_files($folder_id);
        $this->clear($folder_id);
        $result = $this->delete_all(array(
            'where' => array(
                'kc_folder_id' => $folder_id,
            ),
        ));
        foreach($images as $_img)
        {
            $this->delete_attach($_img['attach_id']);
        }
        return $result;
    }

    /**
     * 依照site_id得到整个站点容量大小
     */
    public function get_image_size_by_site($site_id)
    {
        $db = new Database();
        $sql    = "SELECT site_id, sum(image_size) as total_size FROM kc_images WHERE site_id=$site_id GROUP BY site_id";
        $query = $db->query($sql);
        if(empty($query) || !isset($query[0]))
        {
            return 0;
        }
        return $query[0]->total_size;
    }

    /**
     * 依照folder_id得到文件信息
     */
    public function get_files($folder_id)
    {
        $cache = Mycache::instance();
        $cache_key = $this->object_name.'_'.$folder_id;
        $cache_data = $cache->get($cache_key);
        //if(!is_array($cache_data))
        //{
            $folder = Mykc_folder::instance()->get($folder_id);
            $cache_data = $this->lists(array(
                'where' => array(
                    'site_id' => $folder['site_id'],
                    'kc_folder_id' => $folder['id'],
                ),
            ));
            if(!empty($cache_data))
            {
                foreach($cache_data as $key_cache_data => $_cache_data)
                {
                    //扩充
                    $cache_data[$key_cache_data]['readable'] = TRUE;
                    $cache_data[$key_cache_data]['writable'] = isset($_cache_data['writable']) ? $_cache_data['writable'] : true;
                    $cache_data[$key_cache_data]['big_icon'] = isset($_cache_data['big_icon']) ? $_cache_data['big_icon'] : true;
                    $cache_data[$key_cache_data]['small_icon'] = isset($_cache_data['small_icon']) ? $_cache_data['small_icon'] : true;
                    $cache_data[$key_cache_data]['thumb'] = isset($_cache_data['thumb']) ? $_cache_data['thumb'] : true;
                    $cache_data[$key_cache_data]['small_thumb'] = isset($_cache_data['small_thumb']) ? $_cache_data['small_thumb'] : false;
                }
                $cache->set($cache_key, $cache_data);
            }
        //}
        return $cache_data;
    }

    /**
     * 添加存储文件
     *
     */
    public function add_attach($file_meta)
    {
        $site_id            = $file_meta['site_id'];
        $site               = Mysite::instance($site_id)->get();
        $site_domain        = $site['domain']; //'www.2.opococ.com';

        $timestamp_current  = time();
        $src_ip_address     = Input::instance()->ip_address();

        // 需要提效数据
        $attach_meta = array(
            'siteId'            => $site_id,
            'siteDomain'        => $site_domain,
        );
        $attachment_data_original = array(
            'filePostfix'       => $file_meta['type'],
            'fileMimeType'      => $file_meta['mime'],
            'fileSize'          => $file_meta['size'],
            'fileName'          => $file_meta['name'],
            'srcIp'             => $src_ip_address,
            'attachMeta'        => json_encode($attach_meta),
            'createTimestamp'   => $timestamp_current,
            'modifyTimestamp'   => $timestamp_current,
        );

        require_once(Kohana::find_file('vendor', 'phprpc/phprpc_client',TRUE));
        $attachmentService  = new PHPRPC_Client(Kohana::config('phprpc.remote.Attachment.host'));
        $phprpcApiKey       = Kohana::config('phprpc.remote.Attachment.apiKey');

        // 调用后端添加附件信息，并调用存储服务存储文件
        $args_org = array($attachment_data_original);
        $sign_org = md5(json_encode($args_org).$phprpcApiKey);
        $attachment_original_id = $attachmentService->phprpc_addAttachmentFileData($attachment_data_original,@file_get_contents($file_meta['tmpfile']),$sign_org);
        return $attachment_original_id;
    }

    /**
     * 删除存储文件
     *
     */
    public function delete_attach($attach_id)
    {
        AttService::get_instance("kc")->delete_img($attach_id);
        
        //require_once(Kohana::find_file('vendor', 'phprpc/phprpc_client',TRUE));
        //$attachmentService  = new PHPRPC_Client(Kohana::config('phprpc.remote.Attachment.host'));
        //$phprpcApiKey       = Kohana::config('phprpc.remote.Attachment.apiKey');

        //$args       = array($attach_id);
        //$sign       = md5(json_encode($args).$phprpcApiKey);
        //$result     = $attachmentService->phprpc_removeAttachmentDataByAttachmentId($attach_id,$sign);
        return empty($result);
    }

    /**
     * 得到存储文件
     *
     */
    public function get_attach($attach_id)
    {
        if(!isset($attach_id) || empty($attach_id))
        {
            exit('Bad Request!');
        }
        
        $filedata = AttService::get_instance("kc")->get_img_data($attach_id,'kc');

        if(empty($filedata) || $filedata == false) {
            $return['error'] = 'not_found';
            return $return;
        }else {
            $return['filedata'] = $filedata['filedata'];
            $return['filesize'] = $filedata['filesize'];
            return $return;                
        }

/*        require_once(Kohana::find_file('vendor', 'phprpc/phprpc_client',TRUE));
        !isset($attachmentService) && $attachmentService = new PHPRPC_Client(Kohana::config('phprpc.remote.Attachment.host'));
        !isset($phprpcApiKey) && $phprpcApiKey = Kohana::config('phprpc.remote.Attachment.apiKey');
        
        $args = array($attach_id);
        $sign = md5(json_encode($args).$phprpcApiKey);
        $attachment_data = $attachmentService->phprpc_getAttachmentDataById($attach_id, $sign);
        if(empty($attachment_data) || $attachment_data instanceof PHPRPC_Error)
        {
        	$return['error'] = 'not_found';
        	return $return;
        }

        $attachment_allow_view = in_array($attachment_data['filePostfix'], Kohana::config('mimemap.allowViewTypes'));
        $is_img_type =  in_array($attachment_data['filePostfix'],Kohana::config('mimemap.isImgType'));

        $args = array($attachment_data['storeId']);
        $sign = md5(json_encode($args).$phprpcApiKey);
        $store_data = $attachmentService->phprpc_getStoreDataByStoreId($attachment_data['storeId'], $sign);
        $return['attachment_data'] = $attachment_data;
        $return['store_data'] = $store_data;
        return $return;*/
    }
}
