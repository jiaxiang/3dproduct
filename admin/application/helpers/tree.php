<?php
defined('SYSPATH') or die('No direct script access.');
/**
 * 通用的树型类，可以生成任何树型结构
 */
class Tree_Core {
    /**
     * 得到父级数组
     * @param $arr array 2维数组，例如：
     * array(
     * 1 => array('id'=>'1','pid'=>0,'title'=>'一级栏目一','level_depth'=>1),
     * 2 => array('id'=>'2','pid'=>0,'title'=>'一级栏目二','level_depth'=>1),
     * 3 => array('id'=>'3','pid'=>1,'title'=>'二级栏目一','level_depth'=>2),
     * 4 => array('id'=>'4','pid'=>1,'title'=>'二级栏目二','level_depth'=>2),
     * 5 => array('id'=>'5','pid'=>2,'title'=>'二级栏目三','level_depth'=>2),
     * 6 => array('id'=>'6','pid'=>3,'title'=>'三级栏目一','level_depth'=>3),
     * 7 => array('id'=>'7','pid'=>3,'title'=>'三级栏目二','level_depth'=>3)
     * )
     * @param $myid int 获取此id的所有的父级
     * @return array
     */
    public function get_parents($arr, $myid = 0, $newarr = array())
    {
        if(!is_array($arr) || !isset($arr[$myid])){
            return false;
        }
        $pid = $arr[$myid]['pid'];
        $newarr[] = $arr[$myid];
        if($pid){
            $newarr = self::get_parents($arr, $pid, $newarr);
        }
        return $newarr;
    }
    
    /**
     * 得到子级数组
     * @param $arr array 2维数组，例如：
     * array(
     * 1 => array('id'=>'1','pid'=>0,'title'=>'一级栏目一','level_depth'=>1),
     * 2 => array('id'=>'2','pid'=>0,'title'=>'一级栏目二','level_depth'=>1),
     * 3 => array('id'=>'3','pid'=>1,'title'=>'二级栏目一','level_depth'=>2),
     * 4 => array('id'=>'4','pid'=>1,'title'=>'二级栏目二','level_depth'=>2),
     * 5 => array('id'=>'5','pid'=>2,'title'=>'二级栏目三','level_depth'=>2),
     * 6 => array('id'=>'6','pid'=>3,'title'=>'三级栏目一','level_depth'=>3),
     * 7 => array('id'=>'7','pid'=>3,'title'=>'三级栏目二','level_depth'=>3)
     * )
     * @param $myid int 获取此id的所有的子级
     * @return array
     */
    public function get_childs($arr, $myid = 0)
    {
        $newarr = array ();
        if(is_array($arr)){
            foreach($arr as $key => $a){
                if($a['pid'] == $myid){
                    $newarr[$a['id']] = $a['id'];
                }
            }
        }
        if(is_array($newarr)){
            foreach($newarr as $a){
                $newarr = array_merge($newarr, self::get_childs($arr, $a));
            }
        }
        return $newarr;
    }
    
    public function get_tree_array($arr, $myid = 0)
    {
        $newarr = array ();
        $tmparr = array();
        if (is_array($arr)) {
            foreach ($arr as $key => $a) {
                if ($a['pid'] == $myid) {
                    $tmparr[$a['id']] = $a;
                }
            }
        }
        if (!empty($tmparr) && is_array($tmparr)) {
            foreach ($tmparr as $a) {
                $newarr[] = $a;
                $tmparr1 = self::get_tree_array($arr, $a['id']);
                if(!empty($tmparr1)){
                    $newarr = array_merge($newarr,$tmparr1);
                }
            }
        }
        return $newarr;
    }
    
    /**
     * 得到树型结构
     * @param $arr array 2维数组，例如：
     * array(
     * 1 => array('id'=>'1','pid'=>0,'title'=>'一级栏目一','level_depth'=>1),
     * 2 => array('id'=>'2','pid'=>0,'title'=>'一级栏目二','level_depth'=>1),
     * 3 => array('id'=>'3','pid'=>1,'title'=>'二级栏目一','level_depth'=>2),
     * 4 => array('id'=>'4','pid'=>1,'title'=>'二级栏目二','level_depth'=>2),
     * 5 => array('id'=>'5','pid'=>2,'title'=>'二级栏目三','level_depth'=>2),
     * 6 => array('id'=>'6','pid'=>3,'title'=>'三级栏目一','level_depth'=>3),
     * 7 => array('id'=>'7','pid'=>3,'title'=>'三级栏目二','level_depth'=>3)
     * )
     * @param $str string 生成树型结构的基本代码，例如：'<option value={$id} {$selected}>{$spacer}{$title}</option>'
     * @param $myid int 指定生成此id的子级树
     * @param $sid int 被选中的ID，比如在做树型下拉框的时候需要用到
     * @param $icon 前缀
     * @return string
     */
    public static function get_tree($arr, $str, $myid = 0, $sid = 0, $icon = '--')
    {
        $return_str = '';
        $newarr = array ();
        if(is_array($arr)){
            foreach($arr as $key => $a){
                if($a['pid'] == $myid)
                    $newarr[$key] = $a;
            }
        }
        if(is_array($newarr)){
            foreach($newarr as $key => $a){
                @extract($a);
                $spacer = '';
                for($i = 1;$i < $level_depth;$i++){
                    $spacer .= $icon;
                }
                $selected = $id == $sid ? 'selected' : '';
                if(isset($a['is_show']))
                {
                	$is_show = view_tool::get_active_img($a['is_show']);
                }
                eval("\$nstr = \"$str\";");
                $myid = $id;
                $return_str .= $nstr . self::get_tree($arr, $str, $myid, $sid, $icon);
            }
        }
        return $return_str;
    }
}