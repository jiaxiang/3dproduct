<?php defined('SYSPATH') OR die('No direct access allowed.');

class AttService_Core {
    private static $instance = NULL;
    public $control_name = 'attach';
    public $default_img_name = 'default';
    public $default_img_type = 'jpg';
    public $img_dir_name = 'att';
    public $dir_name;
    public $no_img_file;

    // 获取单态实例
    public static function get_instance($img_dir_name = '') {
		if (self::$instance === NULL) {
			$classname = __CLASS__;
			self::$instance = new $classname($img_dir_name);
		}
		return self::$instance;
    }

    /**
     * 构造方法
     */
    public function __construct($img_dir_name = '') {
    	$upload_path = kohana::config('upload.directory');
        $this->img_dir_name = $img_dir_name?$img_dir_name:$this->img_dir_name;
        $this->dir_name = $upload_path.'/'.$this->img_dir_name.'/';
        $this->no_img_file = $upload_path.'/no_img.gif';
    }

    /**
     * 保存上传的默认原始图片
     */
    public function save_default_img($file_tmp_name) {
        require_once(Kohana::find_file('vendor', 'phpthumb/ThumbLib.inc',TRUE));
        $default_img = PhpThumbFactory::create($file_tmp_name);
        $default_img_path = $this->dir_name;
        $path = $default_img_path;
        $string = md5(strtotime(date('now')).rand(1,9999));
        $key = substr($string, 0,24);
        $dir1 = substr($key,0,2);
        $dir2 = substr($key,2,2);
        $dir3 = substr($key,4,2);
        $dir4 = substr($key,6,2);
        if (is_dir($path) == false) {
        	mkdir($path);
        }
        if( is_dir($path.$dir1) == false ){
        	if( mkdir($path.$dir1) == false){
        		return false;
        	}
        }
        $path .= $dir1.'/';
        if( is_dir($path.$dir2) == false){
        	if( mkdir($path.$dir2) == false){
        		return false;
        	}
        }
        $path .= $dir2.'/';
        if( is_dir($path.$dir3) == false){
        	if( mkdir($path.$dir3) == false){
        		return false;
        	}
        }
        $path .= $dir3.'/';
        if( is_dir($path.$dir4) == false){
        	if( mkdir($path.$dir4) == false){
        		return false;
        	}
        }
        $path .= $dir4.'/';
        $this->default_img_name = substr($key, 8, 16);
        $default_img_path = $path;
        $result = $dir1.'/'.$dir2.'/'.$dir3.'/'.$dir4.'/'.$this->default_img_name;
        if ($default_img->save($default_img_path.$this->default_img_name.'.'.$this->default_img_type, $this->default_img_type)) {
        	//$this->thumb($default_img_path, 222, 274, 's1');
        	//$this->thumb($default_img_path, 450, 308, 's2');
            return $result;
        }
        else {
            return false;
        }
    }

    public function save_default_file($file_tmp_name, $file_ext) {
    	//require_once(Kohana::find_file('vendor', 'phpthumb/ThumbLib.inc',TRUE));
    	//$default_img = PhpThumbFactory::create($file_tmp_name);
    	//$default_img
    	$default_img_path = $this->dir_name;
    	$path = $default_img_path;
    	$string = md5(strtotime(date('now')).rand(1,9999));
    	$key = substr($string, 0,24);
    	$dir1 = substr($key,0,2);
    	$dir2 = substr($key,2,2);
    	$dir3 = substr($key,4,2);
    	$dir4 = substr($key,6,2);
    	if (is_dir($path) == false) {
    		mkdir($path);
    	}
    	if( is_dir($path.$dir1) == false ){
    		if( mkdir($path.$dir1) == false){
    			return false;
    		}
    	}
    	$path .= $dir1.'/';
    	if( is_dir($path.$dir2) == false){
    		if( mkdir($path.$dir2) == false){
    			return false;
    		}
    	}
    	$path .= $dir2.'/';
    	if( is_dir($path.$dir3) == false){
    		if( mkdir($path.$dir3) == false){
    			return false;
    		}
    	}
    	$path .= $dir3.'/';
    	if( is_dir($path.$dir4) == false){
    		if( mkdir($path.$dir4) == false){
    			return false;
    		}
    	}
    	$path .= $dir4.'/';
    	$this->default_img_name = substr($key, 8, 16);
    	$default_img_path = $path;
    	$result = $dir1.'/'.$dir2.'/'.$dir3.'/'.$dir4.'/'.$this->default_img_name.'.'.$file_ext;
    	if (move_uploaded_file($file_tmp_name, $default_img_path.$this->default_img_name.'.'.$file_ext)) {
    		return $result;
    	}
    	else {
    		return false;
    	}
    }

    /**
     * 获得图片url
     */
    public function get_img_url($img_id, $w=120, $h=90)
    {
        $url = '/'.$this->control_name.'/'.$this->img_dir_name.'/'.$img_id;
        $url .= ($w>0 && $h>0)?('_'.$w.'_'.$h):'';
        $url .= ".".$this->default_img_type;
        return $url;
    }

    /**
     * 获取图片文件
     */
    public function get_img_dir($img_id=NULL, $w=0, $h=0, $type='jpg')
    {
        $img = '';
        $this->default_img_type != $type && $this->default_img_type = $type;
        if($img_id)
        {
            //默认图片
            $img = $this->dir_name.$img_id.'.'.$this->default_img_type;

           	//d($img);
            if($w > 0 && $h > 0 && file_exists($img))
            {
                //缩略图片
                $img = $this->dir_name.$img_id.'/'.$w.'_'.$h.'.'.$type;
                if(!file_exists($img))
                {
                    $this->thumb($img_id, $w, $h, $type);
                }
            }
        }
        if(!file_exists($img))
        {
            $img = $this->no_img_file;
        }
        return $img;
    }

    /**
     * 删除图片文件
     */
    public function delete_img($img_id, $delete_root=true)
    {
        $img_dir = $this->dir_name.$img_id;
        if(is_dir($img_dir))
        {
            $this->delete_dir_file($img_dir, $delete_root);
        }
    }

    //循环删除目录下的所有文件
    public function delete_dir_file($dirnm, $delete_root=true)
    {
        if ( $handle = opendir( $dirnm ) ) {
           while ( false !== ( $item = readdir( $handle ) ) ) {
               if ( $item != "." && $item != ".." ) {
                   $item = $dirnm."/$item";
                   if ( is_dir( $item ) ) {
                        $this->delete_dir_file( $item, true);
                   } else {
                        @unlink( $item ) ;
                   }
               }
           }
           closedir( $handle );
        }
        $delete_root==true && @rmdir($dirnm);
    }

    /**
     * 生成缩略图
     */
    private function thumb($img_id, $w=120, $h=90, $img_name = 's') {
         //require_once(Kohana::find_file('vendor', 'phpthumb/ThumbLib.inc',TRUE));
         $img_path = $img_id;
         $default_img = $img_path.$this->default_img_name.'.'.$this->default_img_type;
         //d($img_path);
         $thumb_img = $img_path.$this->default_img_name.$img_name;
         $this->resize_image($default_img, $w, $h, $thumb_img, $this->default_img_type);
         return true;
         //$thumb = PhpThumbFactory::create($default_img);
         //$thumb->adaptiveResize($w, $h);
         //$thumb->save($thumb_img, $type);
    }

    /**
     * 读取图片文件
     */
    public function get_img_data($img_id, $img_dir_name = null)
    {
        if(empty($img_dir_name)) {
            $img_dir_name = $this->dir_name;
        }else {
            $img_dir_name = ATTPATH.$img_dir_name;
        }

        $cur_img = $img_dir_name."/".$img_id."/".$this->default_img_name.'.'.$this->default_img_type;

        if(is_file($cur_img)) {
            $file = fopen($cur_img,"r"); // 打开文件
            $size = filesize($cur_img);

            $return['filesize'] = $size;
            $return['filedata'] = fread($file,$size);

            return $return ;
        }else {
            return false;
        }
    }

    /*等比缩放图片
    参数说明：
    $im 图片对象，应用函数之前，你需要用imagecreatefromjpeg()读取图片对象，如果PHP环境支持PNG，GIF，也可使用imagecreatefromgif()，imagecreatefrompng()；

    $maxwidth 定义生成图片的最大宽度（单位：像素）

    $maxheight 生成图片的最大高度（单位：像素）

    $name 生成的图片名

    $filetype 最终生成的图片类型（.jpg/.png/.gif）

    代码注释：

    第3~4行：读取需要缩放的图片实际宽高

    第8~26行：通过计算实际图片宽高与需要生成图片的宽高的压缩比例最终得出进行图片缩放是根据宽度还是高度进行缩放，
    当前程序是根据宽度进行图片缩放。如果你想根据高度进行图片缩放，你可以将第22行的语句改成$widthratio>$heightratio

    第28~31行：如果实际图片的长度或者宽度小于规定生成图片的长度或者宽度，则要么根据长度进行图片缩放，要么根据宽度进行图片缩放。

    第33~34行：计算最终缩放生成的图片长宽。

    第36~45行：根据计算出的最终生成图片的长宽改变图片大小，有两种改变图片大小的方法：ImageCopyResized()函数在所有GD版本中有效，
    但其缩放图像的算法比较粗糙。ImageCopyResamples()，其像素插值算法得到的图像边缘比较平滑，但该函数的速度比ImageCopyResized()慢。

    第47~49行：最终生成经过处理后的图片，如果你需要生成GIF或PNG，你需要将imagejpeg()函数改成imagegif()或imagepng()

    第51~56行：如果实际图片的长宽小于规定生成的图片长宽，则保持图片原样，同理，如果你需要生成GIF或PNG，你需要将imagejpeg()函数改成imagegif()或imagepng()。
    */
    public function resize_image($default_img, $maxwidth, $maxheight, $name, $filetype){
        $im = imagecreatefromjpeg($default_img);
        $pic_width = imagesx($im);
        $pic_height = imagesy($im);

        if(($maxwidth && $pic_width > $maxwidth) || ($maxheight && $pic_height > $maxheight)){
            if($maxwidth && $pic_width>$maxwidth)
            {
                $widthratio = $maxwidth/$pic_width;
                $resizewidth_tag = true;
            }else{
                $widthratio = 0;
                $resizewidth_tag = false;
            }

            if($maxheight && $pic_height>$maxheight)
            {
                $heightratio = $maxheight/$pic_height;
                $resizeheight_tag = true;
            }else{
                $heightratio = 0;
                $resizeheight_tag = false;
            }

            if($resizewidth_tag && $resizeheight_tag)
            {
                if($widthratio<$heightratio)
                    $ratio = $widthratio;
                else
                    $ratio = $heightratio;
            }

            if($resizewidth_tag && !$resizeheight_tag)
                $ratio = $widthratio;
            if($resizeheight_tag && !$resizewidth_tag)
                $ratio = $heightratio;

            $newwidth = $pic_width * $ratio;
            $newheight = $pic_height * $ratio;

            if(function_exists("imagecopyresampled"))
            {
                $newim = imagecreatetruecolor($newwidth,$newheight);
               imagecopyresampled($newim,$im,0,0,0,0,$newwidth,$newheight,$pic_width,$pic_height);
            }
            else
            {
                $newim = imagecreate($newwidth,$newheight);
               imagecopyresized($newim,$im,0,0,0,0,$newwidth,$newheight,$pic_width,$pic_height);
            }

            $name = $name.'.'.$filetype;
            imagejpeg($newim,$name,100);
            imagedestroy($newim);
        }else{
            $name = $name.'.'.$filetype;
            imagejpeg($im,$name,100);
        }
    }

}