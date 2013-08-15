<?php defined('SYSPATH') or die('No direct script access.');
 
class log_Core {
	private static $instance;

	public static function & instance()
	{
		if (!isset(self::$instance))
		{
			$class = __CLASS__;
			self::$instance = new $class;
		}

		return self::$instance;
	}

	public static function append($log,$type = 'opococ',$line = 0)
	{
		Mylog::add('info',$log,$type,$line);
	}

	/**
	 * 程序运行及调试信息记录
	 *
	 * @param 	string 	                日子记录类型  
     * @param 	string|array|object 	日子信息
     * @param   string                  调用此方法的程序位置
     * @param   string                  调用此方法的程序行
     */
    //  样例
    //  log::write('log_type',$data,__FILE__,__LINE__);
	public static function write($type,$data,$file,$line)
    {
        $log_data['type']         = $type;
        $log_data['data']         = $data;
        $log_data['file']         = $file;
        $log_data['line']         = $line;
        $log_data['time']         = date('Y-m-d H:i:s');

        // 日志目录处理
		//$directory = APPPATH.'logs/'.date('Y/m').'/';
		$directory = PROJECT_ROOT.'var/log/'.date('Y/m').'/';

		if ( ! is_dir($directory))
		{
			// 创建日期目录
			mkdir($directory, 0777, TRUE);
		}

		// 文件名
		$filename = $directory.$log_data['type'].'_'.date('d').EXT;

		if ( ! file_exists($filename))
		{
			// 创建日字文件
			file_put_contents($filename, '<?php defined(\'SYSPATH\') or die(\'No direct script access.\'); ?>'.PHP_EOL);
			chmod($filename, 0666);
		}

		// 设置日字格式
		$format = 'time --- type: file - line';

        // Write each message into the log file
        $data   = PHP_EOL.strtr($format, $log_data).
            PHP_EOL.var_export($log_data['data'],true).
            PHP_EOL;
        file_put_contents($filename, $data, FILE_APPEND);
	}
}
