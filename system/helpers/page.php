<?php defined('SYSPATH') or die('No direct script access.');

class page_Core
{
    static $mimemap=array();

	public static function get_pagination($total_page, $current_page, $num_show = 5, $is_show_front_end = true, $is_show_prev_next = true)
	{
		if($total_page<=0 || $current_page<=0 || $current_page > $total_page || $num_show<=0) return false;
		
		//初始化数据
		$pages_last = $pages_first = 0;
		$return_struct = array();
		
		//计算显示的页数
		if($current_page<=ceil($num_show/2))
		{
			$pages_first = 1;
			$pages_last = $num_show<$total_page?$num_show:$total_page;
		}else if($total_page - floor($num_show/2) <= $current_page){
			$pages_first = ($total_page - $num_show +1) <= 0 ? 1 : $total_page - $num_show +1;
			$pages_last = $total_page;
		}else{
			$pages_first = $current_page - floor($num_show/2) + ($num_show+1)%2;
			$pages_first = $pages_first<=0?1:$pages_first;
			$pages_last = $current_page + floor($num_show/2);
			$pages_last = $num_show<$total_page?$num_show:$total_page;
		}
		//填充返回的数据
		$return_struct = array(
			'pages_first' => $pages_first,
			'pages_last'  => $pages_last,
			'current_page'  => $current_page,
		);
		if($is_show_front_end)
		{
			$return_struct['front'] = 1;
			$return_struct['end'] = $total_page;
		}
		if($is_show_prev_next)
		{
			$return_struct['prev'] = ($current_page - 1)>0?$current_page - 1:1;
			$return_struct['next'] = ($current_page+1>$total_page) ? $total_page : $current_page+1;
		}
		return $return_struct;
	}
	
	public static function generate_pagination($pagination,$url='')
	{
		$return_data = '';
		for($i=$pagination['pages_first']; $i<=$pagination['pages_last']; $i++)
		{
			if($i == $pagination['current_page'])
			{
				$return_data .= '<a href="'.$url.'&page='.$i.'" rev="'.$i.'" class="current">'.$i.'</a>';
			}else{
				$return_data .= '<a href="'.$url.'&page='.$i.'" rev="'.$i.'">'.$i.'</a>';
			}
		}
		if(isset($pagination['prev']))
		{
			$return_data = '<a href="'.$url.'&page='.$pagination['prev'].'"rev="'.$pagination['prev'].'" class="prev">&lt;</a>'.$return_data;
			$return_data .= '<a href="'.$url.'&page='.$pagination['next'].'" rev="'.$pagination['next'].'" class="next">&gt</a>';
		}
		if(isset($pagination['front']))
		{
			$return_data = '<a href="'.$url.'&page='.$pagination['front'].'" rev="'.$pagination['front'].'" class="first">&lt;&lt;First</a>'.$return_data;
			$return_data .= '<a href="'.$url.'&page='.$pagination['end'].'" rev="'.$pagination['end'].'" class="last">Last&gt;&gt;</a>';	
		}
		return $return_data;
	}
        
    /**
     * Tests if input data is valid file type, even if no upload is present.
     *
     * @param   array  $_FILES item
     * @return  bool
     */
    public static function fileFieldValid($file)
    {
        return (is_array($file)
            AND isset($file['error'])
            AND isset($file['name'])
            AND isset($file['type'])
            AND isset($file['tmp_name'])
            AND isset($file['size']));
    }
    /**
     * Tests if input data has valid upload data.
     *
     * @param   array    $_FILES item
     * @return  bool
     */
    public static function fileUploaded(array $file)
    {
        return (isset($file['tmp_name'])
            AND isset($file['error'])
            AND is_uploaded_file($file['tmp_name'])
            AND (int) $file['error'] === UPLOAD_ERR_OK);
    }
    
    /**
     * Validation rule to test if an uploaded file is allowed by extension.
     *
     * @param   array    $_FILES item
     * @param   array    allowed file extensions
     * @return  bool
     */
    public static function fileTypeValid(array $file, array $allowed_types)
    {
        if ((int) $file['error'] !== UPLOAD_ERR_OK)
            return TRUE;

        // Get the default extension of the file
        $extension = strtolower(substr(strrchr($file['name'], '.'), 1));

        // Get the mime types for the extension
        $mime_types = Kohana::config('mimes.'.$extension);

        // Make sure there is an extension, that the extension is allowed, and that mime types exist
        return ( ! empty($extension) AND in_array($extension, $allowed_types) AND is_array($mime_types));
    }
    
    public static function fileSizeValid(array $file,$size_limit){
        if ((int) $file['error'] !== UPLOAD_ERR_OK)
            return TRUE;
        
        return ($file['size'] <= $size);
    }
    
    
    /* addon code by axiong */
    
    public static function getImageType($srcFile) {
        $data = @GetImageSize($srcFile);
        if($data===false){
            return false;
        }else{
            switch($data[2]){
                    case 1:
                            return 'gif';
                            break;
                    case 2:
                            return 'jpg';
                            break;
                    case 3:
                            return 'png';
                            break;
                    case 4:
                            return 'swf';
                            break;
                    case 5:
                            return 'psd';
                            break;
                    case 6:
                            return 'bmp';
                            break;
                    case 7:
                            return 'tiff';
                            break;
                    case 8:
                            return 'tiff';
                            break;
                    case 9:
                            return 'jpc';
                            break;
                    case 10:
                            return 'jp2';
                            break;
                    case 11:
                            return 'jpx';
                            break;
                    case 12:
                            return 'jb2';
                            break;
                    case 13:
                            return 'swc';
                            break;
                    case 14:
                            return 'iff';
                            break;
                    case 15:
                            return 'wbmp';
                            break;
                    case 16:
                            return 'xbm';
                            break;
                    default:
                            return false;
            }
        }
    }

    /**
     * 函数说明: 截取文件Mime类型
     * 
     * @author 樊振兴(nick)<nickfan81@gmail.com> 
     * @history 2006-08-25 樊振兴 添加了本方法
     * @param string field 文件域名称
     * @param int index 如果是多文件则获取指定索引的文件的Mime类型
     * @return string /bool(false)
     */
    public static function getFileType($field , $index = 0){
        if(isset($_FILES[$field]) && !empty($_FILES[$field]['type'])){
            if(!is_array($_FILES[$field]['type'])){
                if(!isset(page::$mimemap) || empty(page::$mimemap)){
                    page::$mimemap = Kohana::config('mimemap.type2postfix');
                }
                if(array_key_exists($_FILES[$field]['type'],page::$mime_map)){
                    return page::$mimemap[$_FILES[$field]['type']];
                }else{
                    return false;
                }
            }else{
                if(!isset(page::$mimemap) || empty(page::$mimemap)){
                    page::$mimemap = Kohana::config('mimemap.type2postfix');
                }
                if(array_key_exists($_FILES[$field]['type'][$index],page::$mimemap)){
                    return page::$mimemap[$_FILES[$field]['type'][$index]];
                }else{
                    return false;
                }
            }
        }else{
             return false;
        }
    }
    
    /**
     * 函数说明: 截取文件后缀名
     * 
     * @author 樊振兴(nick)<nickfan81@gmail.com> 
     * @history 2006-08-25 樊振兴 添加了本方法
     * @param string field 文件域名称
     * @param int index 如果是多文件则获取指定索引的文件的后缀名
     * @return string /bool(false)
     */
    public static function getPostfix($field, $index = 0){
        if(isset($_FILES[$field]) && !empty($_FILES[$field]['name'])){
            if(!is_array($_FILES[$field]['name'])){
                $file_name = $_FILES[$field]['name'];
                $point_pos = strrpos($file_name, '.');
                if($point_pos !== false){
                    return substr($file_name, $point_pos + 1);
                }else{
                    return '';
                }
            }else{
                $file_name = $_FILES[$field]['name'][$index];
                $point_pos = strrpos($file_name, '.');
                if($point_pos !== false){
                    return substr($file_name, $point_pos + 1);
                }else{
                    return '';
                }
            }
        }else{
            return false;
        }
    }
    
    /**
     * 函数说明: 获取上传文件大小 index
     * 
     * @author 樊振兴(nick)<nickfan81@gmail.com> 
     * @history 2006-08-25 樊振兴 添加了本方法
     * @param string field 文件域名称
     * @return int 
     */
    public static function getFileSize($field, $index = 0){
        if(isset($_FILES[$field]) && !empty($_FILES[$field]['name'])){
            if(!is_array($_FILES[$field]['name'])){
                return sprintf("%u", filesize($_FILES[$field]['tmp_name']));
            }else{
                return sprintf("%u", filesize($_FILES[$field]['tmp_name'][$index]));
            }
        }else{
            return false;
        }
    }
    
    /**
     * 函数说明: 获取上传文件大小
     * 
     * @author 樊振兴(nick)<nickfan81@gmail.com> 
     * @history 2006-08-25 樊振兴 添加了本方法
     * @param string field 文件域名称
     * @return int 
     */
    public static function getFileSizeTotal($field){
        if(isset($_FILES[$field]) && !empty($_FILES[$field]['name'])){
            if(!is_array($_FILES[$field]['name'])){
                return sprintf("%u", filesize($_FILES[$field]['tmp_name']));
            }else{
                $total = 0;
                for($i = 0;$i < count($_FILES[$field]['name']);$i++){
                    $total += filesize($_FILES[$field]['tmp_name'][$i]);
                }
                return $total;
            }
        }else{
            return false;
        }
    }
    
    /**
     * 函数说明: 获取上传文件个数
     * 
     * @author 樊振兴(nick)<nickfan81@gmail.com> 
     * @history 2006-08-25 樊振兴 添加了本方法
     * @param string field 文件域名称
     * @return int 
     */
    public static function getFileCount($field){
        if(isset($_FILES[$field]) && !empty($_FILES[$field]['name'])){
            if(!is_array($_FILES[$field]['name'])){
                return 1;
            }else{
                $total = 0;
                for($i = 0;$i < count($_FILES[$field]['name']);$i++){
                    if(!empty($_FILES[$field]['name'][$i])){
                        $total++;
                    }
                }
                return $total;
            }
        }else{
            return false;
        }
    }
    
    /**
     * 函数说明: 文件字节显示字符串
     * 
     * @author 樊振兴(nick)<nickfan81@gmail.com> 
     * @history 2006-08-25 樊振兴 添加了本方法
     * @param int filesize 文件字节数
     * @return string 
     */
    public static function getSizeDisp($filesize){
        if($filesize >= 1073741824){
            //$filesize = round($filesize / 1073741824 * 100) / 100 . " GB";
            return sprintf("%.2fGB", $filesize/1073741824);
        }elseif($filesize >= 1048576){
            //$filesize = round($filesize / 1048576 * 100) / 100 . " MB";
            return sprintf("%.2fMB", $filesize/1048576);
        }elseif($filesize >= 1024){
            //$filesize = round($filesize / 1024 * 100) / 100 . " KB";
            return sprintf("%.2fKB", $filesize/1024);
        }else{
            return $filesize . " Bytes";
        }
    }
    
    /**
     * 函数说明: 判断是否已发送指定名称域的文件
     * @author      樊振兴(nick)<nickfan81@gmail.com>
     * @history
     *              2006-08-25 樊振兴 添加了本方法
     * @param       string item 文件域的名称(id/name)
     * @return      bool
     */
    public static function issetFile($field,$index=NULL){
        if(isset($_FILES[$field]) && !empty($_FILES[$field]['name'])){
            if(!is_array($_FILES[$field]['name'])){
                if($index===NULL){
                    return true;
                }else{
                    return false;
                }
            }else{
                if($index===NULL){
                    $isset = false;
                    for($i = 0,$j=count($_FILES[$field]['name']);$i < $j;$i++){
                        if(!empty($_FILES[$field]['name'][$i])){
                            $isset = true;
                            break;
                        }
                    }
                    return $isset;
                }else{
                    if(!empty($_FILES[$field]['name'][$index])){
                        return true;
                    }else{
                        return false;
                    }
                }
            }
        }else{
            return false;
        }
    }

    /**
     * 函数说明: 把数组数据转化为实体代码存储的文件
     * @author      樊振兴(nick)<nickfan81@gmail.com>
     * @history
     *              2006-08-25 樊振兴 添加了本方法
     * @param       array array 数组数据
     * @param       string varName 新数组的变量名称
     * @param       string $filePath 新数组文件的路径 
     * @return      bool(true)
     */
    public static function putArrayFile($array,$varName,$filePath){
        if(file_exists($filePath)){
            if(is_writable($filePath)){
                return @file_put_contents($filePath, "<?php\n" . '$' . $varName . ' = ' . preg_replace("/\,\n( *)\)/", "\n\\1)", var_export($array, true)) . ";\n// echo '<pre>'.print_r($" . $varName . ",true).'</pre>';\n?>")>0;
            }else{
                return false;
            }
        }else{
            return @file_put_contents($filePath, "<?php\n" . '$' . $varName . ' = ' . preg_replace("/\,\n( *)\)/", "\n\\1)", var_export($array, true)) . ";\n// echo '<pre>'.print_r($" . $varName . ",true).'</pre>';\n?>")>0;
        }
    }
    
    /**
     * 函数说明: 把数组数据转化为实体代码
     * @author      樊振兴(nick)<nickfan81@gmail.com>
     * @history
     *              2006-08-25 樊振兴 添加了本方法
     * @param       mixed var 数组数据
     * @return      string
     */
    public static function encodeArray($var){
        if (is_array($var)){
            $code = 'array(';
            foreach ($var as $key => $value){
                $code .= "'$key'=>" . encodeArray($value) . ',';
            }
            $code = chop($code, ','); //remove unnecessary coma
            $code .= ')';
            return $code;
        }else{
            if (is_string($var)){
                return "'" . $var . "'";
            }elseif (is_bool($var)){
                return ($var ? 'TRUE' : 'FALSE');
            }elseif (is_numeric($var)){
                return $var;
            }elseif (is_null($var)){
                return 'NULL';
            }elseif (is_object($var)){
                return encodeArray((array)$var);
            }else{
                return "''";
            }
        }
    }
    
    /**
     * 发送 & 检测 http Etag头
     * @param string $resourceEtag
     */
    public static function httpEtag($resourceEtag=NULL){
        if(isset($resourceEtag) && !empty($resourceEtag)){
            header('Etag: ' . $resourceEtag);
            if(isset($_SERVER['HTTP_IF_NONE_MATCH']) && $resourceEtag == $_SERVER['HTTP_IF_NONE_MATCH']){
                header('HTTP/1.0 304 Not Modified');
                exit;
            }
        }
    }
    /**
     * 发送 & 检测 http Last Modified头
     * @param int $resourceUpdateTimestamp
     */
    public static function httpLastModified($resourceUpdateTimestamp=0){
        if(isset($resourceUpdateTimestamp) && !empty($resourceUpdateTimestamp)){
            header('Last-Modified: ' . gmdate('D, d M Y H:i:s' , $resourceUpdateTimestamp) . ' GMT');
            if((isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) && strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']) >= $resourceUpdateTimestamp) || (isset($_SERVER['HTTP_IF_UNMODIFIED_SINCE']) && strtotime($_SERVER['HTTP_IF_UNMODIFIED_SINCE']) < $resourceUpdateTimestamp)){
                header('HTTP/1.0 304 Not Modified');
                exit;
            }
        }
    }
    /**
     * 发送http过期头 过期间隔 秒数
     * @param int $resourceCacheTimeInterval
     */
    public static function httpExpiresInterval($resourceCacheTimeInterval = NULL,$resourceExpiresTimestamp=NULL){
        if(isset($resourceCacheTimeInterval)){
            if($resourceCacheTimeInterval==-1){
                header('Expires: 0');
                header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                header('Pragma: public');
            }else{
                if($resourceCacheTimeInterval>0){
                    header('Cache-control: max-age='.$resourceCacheTimeInterval);
                }
                if(isset($resourceExpiresTimestamp) && !empty($resourceExpiresTimestamp)){
                    header('Expires: ' . gmdate('D, d M Y H:i:s', $resourceExpiresTimestamp) . ' GMT');
                }else{
                    header('Expires: ' . gmdate('D, d M Y H:i:s', time()+$resourceCacheTimeInterval) . ' GMT');
                }
            }
        }
    }

    /**
     * 发送http过期头，立即过期
     */
    public static function httpNoExpires(){
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
    }

}
