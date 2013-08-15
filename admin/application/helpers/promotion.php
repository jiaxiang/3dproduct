
<?php defined('SYSPATH') OR die('No direct access allowed.');

class Promotion_Core {
	/**
	 * 清除打折缓存
	 * 
     * @param int $id   
	 * @return Boolean
	 */
    public static function delete_memcache($id)
    {
        $route          = 'promotion';
        $route_key      = $id.'_'.$route;
        $line_key       = array('id'=>$id);
        $cacheInstance  = ServRouteInstance::getInstance(ServRouteConfig::getInstance())
            ->getMemInstance($route,$line_key)->getInstance();
        $cacheInstance->delete($route_key);
        return TRUE;
    }
    
    /*
     *转换数组形式
     */
    public static function convert($categories)
    {
        $all_ids_tmp = array();
        foreach($categories as $key=>$all_id){
             $all_ids_tmp[$all_id['level_depth']][] = $all_id;
        }
        ksort($all_ids_tmp);
        return $all_ids_tmp;
    }
    /**
     *生成树 
     */
    public static function generate_tree($categories,$level_depth,$pid,$category,$category_field,$checkAll)
    {
        $tree = '';
        //设定标识
        static $related = ''; 
        static $mark_tree = 0;
        if($related!=$category){
            $related = $category;
            $mark_tree = 0;
        }
        if($mark_tree == 0){
           $tree .= '<tr style="text-align:left;">
                        <th style="text-align:left;width:40px;padding-left:4px"><input type="checkbox" name="'.$checkAll.'" id="'.$checkAll.'" /></th>';
           if(isset($category_field)){
               foreach($category_field as $field){
                   $tree .= '<th style="text-align:left">'.$field.'</th>';
               }
           }
           $tree .= '</tr>';
           $mark_tree++;
        }
        
        foreach($categories[$level_depth] as $value){
            $mark_pic = 0;
            $nbsp = '';
            for($i=1;$i<$level_depth;$i++){
                $nbsp .= '&nbsp;&nbsp;&nbsp;&nbsp;';
            }
           if($value['pid'] == $pid){
               $tree .= '<tr style="text-align:left;">
                           <td>
                              <input name="'.$category.'[]" type="checkbox" value="'.$value['id'].'" title="'.$value['title_manage'].'"/>
                              <input type="hidden" name="parentId" value="'.$value['pid'].'"/>
                           </td>';
                if(isset($category_field)){
                    foreach($category_field as $key=>$field){
                        
                       $tree .= '<td>';
                       if($mark_pic == 0){
                          $tree .= $nbsp.'<img src="/images/icon_dot2.gif" class="icon_dot"  />';
                          $mark_pic++;
                       }
                       $tree .= $value[$key].'</td>'; 
                    }
                }
               $tree .='</tr>';
               if(isset($categories[$level_depth+1])){
                  $tree .= promotion::generate_tree($categories,$level_depth+1,$value['id'],$category,$category_field,$checkAll);
               }
           }
        }
        return $tree;
    }
    
}
