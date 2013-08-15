<?php defined('SYSPATH') OR die('No direct access allowed.');

class Mykc_folder_Core extends Mykc{

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
     * @param $category_id
     */
    public function clear($id)
    {
        $cache = Mycache::instance();
        $cache_key = $this->object_name.'_'.$id;

        $cache->delete($cache_key);
    }

    /**
     * 依照id得到文件夹的一条数据
     *
     * @param Int $id
     * @return Array
     */ 
    public function get($id)
    {
        $cache = Mycache::instance();
        $cache_key = $this->object_name.'_'.$id;
        $cache_data = $cache->get($cache_key);
        if(!is_array($cache_data) )
        {
            $cache_data = $this->read(array(
                'id' => $id,
            ));
            if(!empty($cache_data))
            {
                //可写性
                $cache_data['writable'] = isset($cache_data['is_writable']) ? $cache_data['is_writable'] : TRUE;
                //可读性
                $cache_data['readable'] = isset($cache_data['is_readable']) ? $cache_data['is_readable'] : TRUE;
                //可删除
                $cache_data['removable'] = TRUE;
                $cache->set($cache_key,$cache_data);
            }
        }
        return $cache_data;        
    }

    /**
     * 依据文件夹ID得到所有上级文件夹的信息(包含文件夹自己)
     *
     * @param Int $id
     * @return Array
     */
    public function get_parent_list_by_id($id)
    {    
        $result     = array();

        $folder     = $this->get($id);
        $result[]   = $folder;
        if($folder['parent_id'] != 0)
        {
            $temp = $this->get_parent_list_by_id($folder['parent_id']);
            if(is_array($temp) && count($temp))
            {
                $result = array_merge($result,$temp);
            }
        }
        return $result;
    }

    /**
     * 得到整个站点文件夹树,去除给定文件夹及其子文件夹
     *
     *
     * @param Int $site_id
     * @param Int $folder_id
     * @return Array
     */
    public function get_tree_by_site_id($site_id, $folder_id = 0)
    {
        $all_folder        = array();
        $dis_folder        = array();
        $dis_folder_ids    = array();
        $result            = array();

        $folders = $this->lists(array(
            'where' => array(
                'site_id'       => $site_id,
                'level_depth'   => 1,
            ),
            'orderby'   => array(
                'name'      => 'ASC',
            ),
        ));
        foreach($folders as $item)
        {
            $all_folder = array_merge($all_folder,$this->get_tree_by_folder_id($item['id']));
        }

        if($folder_id != 0)
        {
            $dis_folder = $this->get_tree_by_folder_id($folder_id);
        }
        foreach($dis_folder as $_dis_folder)
        {
            $dis_folder_ids[] = intval($_dis_folder['id']);
        }

        foreach($all_folder as $_folder)
        {
            if(!in_array($_folder['id'], $dis_folder_ids))
            {
                $result[] = $_folder;
            }
        }
        return $result;
    }



    /**
     * 得到以此文件夹为根的子树
     *
     * @param Int $id
     * @return Array
     */
    public function get_tree_by_folder_id($id)
    {
        $result             = array();
        $sub_folder_ids     = array();

        $folder = $this->get($id);
        $result[] = $folder;
        if(isset($folder['sub_folder_ids']) && ($folder['sub_folder_ids'] != NULL))
        {
            $sub_folder_ids = explode(',', $folder['sub_folder_ids']);
            foreach($sub_folder_ids as $_sub_folder_id)
            {
                $temp = $this->get_tree_by_folder_id($_sub_folder_id);
                if(is_array($temp) && count($temp))
                {
                    $result = array_merge($result, $temp);
                }
            }
        }
        return $result;
    }

    /**
     * 更新一个文件夹
     *
     * @param Int $id
     * @param Array $data
     * @return Boolean
     */
    public function update($id, $data)
    {
        $folder = $this->get($id);
        $this->clear($id);
        return parent::update($id, $data);
    }

    /**
     * 删除一个文件夹
     *
     * @param Int $id
     * @return Boolean
     */
    public function delete($id)
    {
        $folder = $this->get($id);
        $this->clear($id);
        return parent::delete($id);
    }

    /**
     * 更新由于文件夹的更新或者添加引起的整个站点文件夹数据的变动
     * 更新文件夹的子文件夹,级数
     * @param Int $site_id
     * @return Boolean
     */
    public function update_folders_for_folder($site_id)
    {    
        $folders = $this->lists(array(
            'where' => array(
                'site_id' => $site_id,
            ),
            'orderby'   => array(
                'name'      => 'ASC',
            ),
        ));
        if(!is_array($folders) || empty($folders))
        {
            return FALSE;
        }
        $temp_folders    = $folders;

        foreach($folders as $key_f => $_folder)
        {
            $sub_folder_ids = array();
            
            //子文件夹
            foreach($folders as $_folder_sub)
            {
                if($_folder_sub['parent_id'] == $_folder['id'])
                {
                    $sub_folder_ids[] = $_folder_sub['id'];
                }
            }
            $folders[$key_f]['sub_folder_ids'] = implode(',', $sub_folder_ids);

            //级数
            $parent_list = $this->get_parent_list_by_id($_folder['id']);
            $folders[$key_f]['level_depth'] = count($parent_list);

        }
        foreach($folders as $key_f => $_folder)
        {
            // 当子文件夹有改动时，更新文件夹
            if(($temp_folders[$key_f]['sub_folder_ids'] <> $_folder['sub_folder_ids']) || 
                ($temp_folders[$key_f]['level_depth'] <> $_folder['level_depth']))
            {
                $this->update($_folder['id'], $_folder);
            }
        }
        return TRUE;
    }

}
