<?php defined('SYSPATH') or die('No direct script access.');

class Kc_page_Core
{
    static $mimemap=array();

    /**
     * 得到图片类型
     */
    public static function get_image_type($src_file) {
        $data = @GetImageSize($src_file);
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
     * @param string field 文件域名称
     * @return string /bool(false)
     */
    public static function get_file_type($field){
        if(isset($_FILES[$field]) && !empty($_FILES[$field]['type'])){
            if(!is_array($_FILES[$field]['type'])){
                if(!isset(kc_page::$mimemap) || empty(kc_page::$mimemap)){
                    kc_page::$mimemap = Kohana::config('mimemap.type2postfix');
                }

                if(array_key_exists($_FILES[$field]['type'],kc_page::$mimemap)){
                    return kc_page::$mimemap[$_FILES[$field]['type']];
                }else{
                    return false;
                }
            }else{
                if(!isset(kc_page::$mimemap) || empty(kc_page::$mimemap)){
                    kc_page::$mimemap = Kohana::config('mimemap.type2postfix');
                }
                if(array_key_exists($_FILES[$field]['type'],kc_page::$mimemap)){
                    return kc_page::$mimemap[$_FILES[$field]['type']];
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
     * @param string field 文件域名称
     * @return string /bool(false)
     */
    public static function get_postfix($field)
    {
        if(isset($_FILES[$field]) && !empty($_FILES[$field]['name']))
        {
            if(!is_array($_FILES[$field]['name']))
            {
                $file_name = $_FILES[$field]['name'];
                $point_pos = strrpos($file_name, '.');
                if($point_pos !== false){
                    return substr($file_name, $point_pos + 1);
                }else{
                    return FALSE;
                }
            }else{
                $file_name = $_FILES[$field]['name'];
                $point_pos = strrpos($file_name, '.');
                if($point_pos !== false){
                    return substr($file_name, $point_pos + 1);
                }else{
                    return FALSE;
                }
            }
        }else{
            return FALSE;
        }
    }
    
    /**
     * Tests if input data is valid file type, even if no upload is present.
     *
     * @param   array  $_FILES item
     * @return  bool
     */
    public static function file_field_valid($file)
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
    public static function file_uploaded(array $file)
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
    public static function file_type_valid(array $file, array $allowed_types)
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

    public static function file_size_valid(array $file,$size_limit){
        if ((int) $file['error'] !== UPLOAD_ERR_OK)
            return TRUE;

        return ($file['size'] <= $size);
    }

    /**
     * 函数说明: 获取上传文件大小 index
     * 
     * @param string field 文件域名称
     * @return int 
     */
    public static function get_file_size($field, $index = 0){
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
     * @param string field 文件域名称
     * @return int 
     */
    public static function get_file_size_total($field){
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
     * @param string field 文件域名称
     * @return int 
     */
    public static function get_file_count($field){
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
     * @param int filesize 文件字节数
     * @return string 
     */
    public static function get_size_disp($filesize){
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
     *
     * @param       string item 文件域的名称(id/name)
     * @return      bool
     */
    public static function isset_file($field,$index=NULL){
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
     *
     * @param       array array 数组数据
     * @param       string varName 新数组的变量名称
     * @param       string $filePath 新数组文件的路径 
     * @return      bool(true)
     */
    public static function put_array_file($array,$varName,$filePath){
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
     *
     * @param       mixed var 数组数据
     * @return      string
     */
    public static function encode_array($var){
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
     *
     * @param string $resourceEtag
     */
    public static function http_etag($resourceEtag=NULL){
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
     *
     * @param int $resourceUpdateTimestamp
     */
    public static function http_last_modified($resourceUpdateTimestamp=0){
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
     *
     * @param int $resourceCacheTimeInterval
     */
    public static function http_expires_interval($resourceCacheTimeInterval = NULL,$resourceExpiresTimestamp=NULL){
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
    public static function http_no_expires(){
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
    }

}
