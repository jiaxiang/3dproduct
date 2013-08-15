<?php defined('SYSPATH') OR die('No direct access allowed.');

class Mykc_browser_stat_Core extends Mykc{

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
     * 业务逻辑
     */

    /**
     * 重载lists
     * 加百分比统计功能
     */
    public function lists($query_struct = array())
    {
        $list = parent::lists($query_struct);

        $agent_detail_num = array();
        $version_num = array();
        $type_num = array();

        $total_num = $this->count(array());

        $db     = new Database();

        //统计agent_detail数目
        $sql    = "SELECT agent_detail, count(id) as agent_num FROM kc_browser_stats WHERE 1=1 GROUP BY agent_detail";
        $query = $db->query($sql);
        foreach($query as $key => $_query)
        {
            $agent_detail_num[$_query->agent_detail]  = $_query->agent_num;
        }

        //统计version数目
        $sql    = "SELECT version, count(id) as version_num FROM kc_browser_stats WHERE 1=1 GROUP BY version";
        $query = $db->query($sql);
        foreach($query as $key => $_query)
        {
            $version_num[$_query->version]  = $_query->version_num;
        } 

        //统计type数目
        $sql    = "SELECT type, count(id) as type_num FROM kc_browser_stats WHERE 1=1 GROUP BY type";
        $query = $db->query($sql);
        foreach($query as $key => $_query)
        {
            $type_num[$_query->type]  = $_query->type_num;
        }

        //计算百分比
        foreach($list as $key_list => $_list)
        {
            $list[$key_list]['agent_detail_percentage'] = round($agent_detail_num[$_list['agent_detail']]/$total_num*100);
            $list[$key_list]['version_percentage'] = round($version_num[$_list['version']]/$total_num*100);
            $list[$key_list]['type_percentage'] = round($type_num[$_list['type']]/$total_num*100);
        }
        return $list;
    }

}
