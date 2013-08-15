<?php defined('SYSPATH') OR die('No direct access allowed.');

class Att_Controller {

    /**
     * 图片附件显示 
     */
    public function index($img_dir, $img = NULL)
    {
        if($img_dir && !$img){
            $img = $img_dir;
            $img_dir = "att";
        }
        //$img_type='jpg'
        $imgArr = explode('.', $img);
        $img = $imgArr[0];
        $img_type = isset($imgArr[1])?$imgArr[1]:'jpg';
                
        //$img_id=NULL, $w=0, $h=0
        $imgArr = explode('_', $img);
        $img_id = isset($imgArr[0])?$imgArr[0]:NULL;
        $w = isset($imgArr[1])?$imgArr[1]:0;
        $h = isset($imgArr[2])?$imgArr[2]:0;
        //兼容格式:40x40
        if(strpos(strtolower($w),'x')){
            $x = explode("x", $w);
            $w = isset($x[0])?$x[0]:0;
            $h = isset($x[1])?$x[1]:0;
        }
        
        $filename = AttService::get_instance($img_dir)->get_img_dir($img_id, $w, $h, $img_type);
        // 尝试通过图片类型判断
        $file_type_current = page::getImageType($filename);
        $file_type_current = (($file_type_current == 'jpg')?'jpeg':'gif');
        ob_end_clean();
        header("Content-type: image/{$file_type_current}");
        @readfile($filename);
        ob_end_flush();
        die();
    }

}