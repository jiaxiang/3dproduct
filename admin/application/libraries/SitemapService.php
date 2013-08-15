<?php
defined('SYSPATH') or die('No direct access allowed.');

class SitemapService_Core {
    /* 兼容php5.2环境 Start */
    private static $instance = NULL;
    // 获取单态实例
    public static function get_instance()
    {
        if(self::$instance === null){
            $classname = __CLASS__;
            self::$instance = new $classname();
        }
        return self::$instance;
    }
    /* 兼容php5.2环境 End */
    
    /*
     * 获取分类页sitemap
     */
    public function get_category_page_by_site_id($site_id)
    {
        $category_service = CategoryService::get_instance();
        $categories = $category_service->get_categories_by_site_id($site_id);
        return $categories;
    }
    
    /*
     * 获取商品页
     */
    public function get_product_page_by_site_id($site_id,$on_sale = 0)
    {
        $products = array();
        $product_service = ProductService::get_instance();
        $request_struct = array(
            'where'		=> array(
                'site_id' => $site_id,
                'status' => 1
			)
        );
        if($on_sale == 1){
            $request_struct['where']['on_sale'] = 1;
        }
        $products = $product_service->query_assoc($request_struct);
        return $products;
    }
    
    /**
     * 获取促销页
     * @param $site_id
     * return array
     */

    public function get_promotion_page_by_site_id($site_id)
    {
        $promotion_activity_sercice = Mypromotion_activity::instance();
        $request_struct = array(
            'where'     => array(
                'site_id' => $site_id,
                'disabled'=> 0,
            )
        );
        $promotion_activity = $promotion_activity_sercice->lists($request_struct);
        return $promotion_activity;
    }
    
    /*
     * 获取文案页
     */
    public function get_doc_page_by_site_id($site_id)
    {
        $docs = array();
        $doc_sercice = Mydoc::instance();
        $request_struct = array(
            'where'     => array(
                'site_id' => $site_id
            )
        );
        $docs = $doc_sercice->lists($request_struct);
        return $docs;
    }
}
?>