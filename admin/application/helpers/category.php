<?php
defined('SYSPATH') or die('No direct script access.');

class Category_Core {
    /**
     * 获取前台分类链接
     *
     * @param 	int id 分类id
     * @return 	string 分类链接
     */
    public static function permalink($id, $absolute = true)
    {
        $category_service = CategoryService::get_instance();
        $category = $category_service->get($id);
        $route = Myroute::instance()->get();
        $route_type = $route['type'];
        $category_route = $route['category'];
        $category_suffix = $route['category_suffix'];
        $domain = Mysite::instance()->get('domain');
        if($route_type == 0){
            // 0: none  get category and product with id
            $category_permalink = $category_route . '/' . $category['id'];
        }
        if($route_type == 1){
            // 1: get  product with {product}/permalink
            $category_permalink = $category_route . '/' . urlencode($category['uri_name']); 
        }
        if($route_type == 2 || $route_type == 4){
            // 2: get category and product with {category_permalink}  and {category+permalink}/{product_permalink}
            $category_permalink = urlencode($category['uri_name']);  
        }
        if($route_type == 3){
            // 3: get category and prdouct with {category_permalink1}/.../{category_permalinkn} and {category_permalink1}/.../{category_permalinkn}/{product_permalink}
            $parents = $category_service->get_parents_by_category_id($id);
            $category_permalink = '';
            foreach($parents as $category){
                if($category == end($parents)){
                    $category_permalink .= urlencode($category['uri_name']);
                }else{
                    $category_permalink .= urlencode($category['uri_name']) . '/';
                }
            }
        }
        
        if($absolute)
        {
        	return 'http://' . $domain . '/' . $category_permalink . $category_suffix;
        }
        else 
        {
        	return $category_permalink . $category_suffix;
        }
        
    }    

}
