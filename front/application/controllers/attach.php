<?php defined('SYSPATH') OR die('No direct access allowed.');

class Attach_Controller extends Template_Controller {

	public $obj_user_help, $obj_order, $obj_orderbasic, $obj_orderdetail;
	public function __construct() {
		parent::__construct();
		$this->obj_user_help = userfunc::get_instance();
	}
    /**
     * 图片显示
     */
    public function pic($img_name, $img_dir1, $img_dir2, $img_dir3, $img_dir4, $img_filename) {
        $imgArr = explode('.', $img_filename);
        $img = $imgArr[0];
        $img_type = isset($imgArr[1])?$imgArr[1]:'jpg';
        $img_id = $img_dir1.'/'.$img_dir2.'/'.$img_dir3.'/'.$img_dir4.'/'.$imgArr[0];
        $filename = AttService::get_instance($img_name)->get_img_dir($img_id, 0, 0, $img_type);
        $file_type_current = page::getImageType($filename);
        $file_type_current = (($file_type_current == 'jpg')?'jpeg':'gif');
        ob_end_clean();
        header("Content-type: image/{$file_type_current}");
        @readfile($filename);
        ob_end_flush();
        exit();
    }

    public function stl($img_name, $img_dir1, $img_dir2, $img_dir3, $img_dir4, $img_filename) {
    	$imgArr = explode('.', $img_filename);
    	$img = $imgArr[0];
    	$img_type = isset($imgArr[1])?$imgArr[1]:'stl';
    	$img_id = $img_dir1.'/'.$img_dir2.'/'.$img_dir3.'/'.$img_dir4.'/'.$imgArr[0];
    	$filename = AttService::get_instance($img_name)->get_img_dir($img_id, 0, 0, $img_type);
    	//$file_type_current = page::getImageType($filename);
    	//$file_type_current = (($file_type_current == 'jpg')?'jpeg':'gif');
    	ob_end_clean();
    	//header("Content-type: image/{$file_type_current}");
    	@readfile($filename);
    	ob_end_flush();
    	exit();
    }

    public function download($img_name, $img_dir1, $img_dir2, $img_dir3, $img_dir4, $img_filename, $ori_filename) {
    	$imgArr = explode('.', $img_filename);
    	$img = $imgArr[0];
    	$img_type = isset($imgArr[1])?$imgArr[1]:'jpg';
    	$img_id = $img_dir1.'/'.$img_dir2.'/'.$img_dir3.'/'.$img_dir4.'/'.$imgArr[0];
    	$filename = AttService::get_instance($img_name)->get_img_dir($img_id, 0, 0, $img_type);
    	//$file_type_current = page::getImageType($filename);
    	//$file_type_current = (($file_type_current == 'jpg')?'jpeg':'gif');
    	ob_end_clean();
    	$mime = 'application/force-download';
		header('Pragma: public'); // required
		header('Expires: 0'); // no cache
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Cache-Control: private',false);
		header('Content-Type: '.$mime);
		header('Content-Disposition: attachment; filename="'.basename($ori_filename).'"');
		header('Content-Transfer-Encoding: binary');
		header('Connection: close');
		readfile($filename); // push it out
    	ob_end_flush();
    	exit();
    }

    public function upload_preview($upload_name, $dirname) {
    	if ($this->obj_user_help->is_login() == FALSE) {
    		//header('Location: http://'.$this->_site_config['site_config']['name'].'/user/login');
    	}
    	$return = order::get_instance()->upload_attach($upload_name, $dirname);
    	if ($return['code'] == 1) {
    		$this->obj_session->set('PRINT_3D_PREVIEW', $return['data']);
    	}
    	echo json_encode($return);
    	return ;
    }

    public function upload_model($upload_name, $dirname) {
    	if ($this->obj_user_help->is_login() == FALSE) {
    		//header('Location: http://'.$this->_site_config['site_config']['name'].'/user/login');
    	}
    	$return = order::get_instance()->upload_attach($upload_name, $dirname, 'model');
    	if ($return['code'] == 1) {
    		$this->obj_session->set('PRINT_3D_MODELSTL', $return['data']);
    	}
    	echo json_encode($return);
    	return ;
    }

}
