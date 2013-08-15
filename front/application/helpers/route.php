<?php
defined('SYSPATH') or die('No direct script access.');

class Route_Core {
    public static function action($action)
    {
        return url::base(); //. Myroute::instance()->get_action($action);
    }
    
    /**
     * 判断链接是否设置
     * 目前用于打折链接处理
     */
    public static function isset_action($action)
    {
        return Myroute::instance()->get_action($action) ? 1 : 0;
    }
    
    public static function name($name)
    {
        return Myroute::instance()->get_name($name);
    }
    
    public static function parse($current_uri)
    {
        $current_uri = trim($current_uri);
        if(empty($current_uri)){
            return FALSE;
        }
        
        $site_id = Mysite::instance()->id();
        
        // 尝试解析 doc
        $doc_uris = Mydoc::get_uris($site_id);
        if(isset($doc_uris[$current_uri])){
            return '/doc/view/' . $current_uri;
        }
        
        $route_type = Myroute::instance()->type();
        $category_suffix = Myroute::instance()->get_action('category_suffix');
        $category_key = Myroute::instance()->get_action('category');
        $categorys = CategoryService::get_instance()->get_uri_name();
        
        // 尝试解析 category
        $arguments = explode('/', $current_uri);
        switch($route_type){
            case 0: // www.example.com/category/1.html/page/2
                if($arguments[0] === $category_key and isset($arguments[1])){
                    if(!empty($category_suffix)){
                        $arguments[1] = substr($arguments[1], 0, strlen($arguments[1]) - strlen($category_suffix));
                    }
                    if(preg_match('/^\d+$/', $arguments[1])){
                        $result = 'category/index/' . $arguments[1];
                        for($i = 2;isset($arguments[$i]);$i++){
                            $result .= '/' . $arguments[$i];
                        }
                        return $result;
                    }
                }
                break;
            case 1: // www.example.com/category/xxxxx.html/page/2
                if($arguments[0] === $category_key and isset($arguments[1])){
                    if(!empty($category_suffix)){
                        $arguments[1] = substr($arguments[1], 0, strlen($arguments[1]) - strlen($category_suffix));
                    }
                    if(isset($categorys[$arguments[1]])){
                        $result = 'category/index/' . $arguments[1];
                        for($i = 2;isset($arguments[$i]);$i++){
                            $result .= '/' . $arguments[$i];
                        }
                        return $result;
                    }
                }
                break;
            case 2: // www.example.com/xxxxx.html/page/2
                if(count($arguments) == 1 or count($arguments) > 2){
                    if(!empty($category_suffix)){
                        $arguments[0] = substr($arguments[0], 0, strlen($arguments[0]) - strlen($category_suffix));
                    }
                    if(isset($categorys[$arguments[0]])){
                        $result = 'category/index/' . $arguments[0];
                        for($i = 1;isset($arguments[$i]);$i++){
                            $result .= '/' . $arguments[$i];
                        }
                        return $result;
                    }
                }
                break;
            case 3: // www.example.com/xxxxx/ooooo.html/page/2
                for($i = 0;isset($arguments[$i]);$i++){
                    if(!isset($categorys[$arguments[$i]])){
                        break;
                    }
                }
                if($i == 0){
                    break;
                }
                if(empty($category_suffix)){
                    $i--;
                }
                $count = count($arguments) - $i;
                if($count == 1 or $count > 2){
                    if(!empty($category_suffix)){
                        $arguments[$i] = substr($arguments[$i], 0, strlen($arguments[$i]) - strlen($category_suffix));
                    }
                    $result = 'category/index/' . $arguments[$i];
                    for($i = $i + 1;isset($arguments[$i]);$i++){
                        $result .= '/' . $arguments[$i];
                    }
                    return $result;
                }
                break;
            case 4: // www.example.com/xxxxx.html/page/2
                if(count($arguments) == 1 or count($arguments) > 2){
                    if(!empty($category_suffix)){
                        $arguments[0] = substr($arguments[0], 0, strlen($arguments[0]) - strlen($category_suffix));
                    }
                    if(isset($categorys[$arguments[0]])){
                        $result = 'category/index/' . $arguments[0];
                        for($i = 1;isset($arguments[$i]);$i++){
                            $result .= '/' . $arguments[$i];
                        }
                        return $result;
                    }
                }
                break;
        }
        
        $product_suffix = Myroute::instance()->get_action('product_suffix');
        $product_key = Myroute::instance()->get_action('product');
       
        // 尝试解析  product
        $arguments = explode('/', $current_uri);
        switch($route_type){
            case 0: // www.example.com/product/220.html
                if($arguments[0] === $product_key and isset($arguments[1])){
                    if(!empty($product_suffix)){
                        $arguments[1] = substr($arguments[1], 0, strlen($arguments[1]) - strlen($product_suffix));
                    }
                    if(preg_match('/^\d+$/', $arguments[1])){
                        return 'product/get/' . $arguments[1];
                    }
                }
                break;
            case 1: // www.example.com/product/yyyyy.html
                if($arguments[0] === $product_key and isset($arguments[1])){
                    if(!empty($product_suffix)){
                        $arguments[1] = substr($arguments[1], 0, strlen($arguments[1]) - strlen($product_suffix));
                    }
                    return 'product/get/' . $arguments[1];
                }
                break;
            case 2: // www.example.com/xxxxx/yyyyy.html
                if(isset($categorys[$arguments[0]]) and isset($arguments[1])){
                    if(!empty($product_suffix)){
                        $arguments[1] = substr($arguments[1], 0, strlen($arguments[1]) - strlen($product_suffix));
                    }
                    return 'product/get/' . $arguments[1];
                }
                break;
            case 3: // www.example.com/xxxxx/ooooo/yyyyy.html
                for($i = 0;isset($arguments[$i]);$i++){
                    if(!isset($categorys[$arguments[$i]])){
                        break;
                    }
                }
                if($i > 0 and isset($arguments[$i])){
                    if(!empty($product_suffix)){
                        $arguments[$i] = substr($arguments[$i], 0, strlen($arguments[$i]) - strlen($product_suffix));
                    }
                    return 'product/get/' . $arguments[$i];
                }
                break;
            case 4: // www.example.com/product/yyyyy.html
                if($arguments[0] === $product_key and isset($arguments[1])){
                    if(!empty($product_suffix)){
                        $arguments[1] = substr($arguments[1], 0, strlen($arguments[1]) - strlen($product_suffix));
                    }
                    return 'product/get/' . $arguments[1];
                }
                break;
        }
        
        return FALSE;
    }
}