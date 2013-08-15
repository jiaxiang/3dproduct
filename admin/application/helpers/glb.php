<?php defined('SYSPATH') or die('No direct script access.');

class Glb_Core{
	//a标签中要提取内容
	public static $link_global = array('href','title');
	//IMG标签中要提取内容
	public static $image_global = array('src','alt');
	//要处理内容
	public static $content = NULL;
	//替换以后变量
	public static $result = array();
	//变量前缀
	public static $prefix = NULL;
	/**
	 * 初始化模板
	 *
	 * @param int $id 标识ID(站点ID)
	 * @param array $files 要替换文件名列表，如果留空刚目录全部替换
	 * 
	 * @return boolean
	 */
	public static function init($id=1,$files=array())
	{
		if($id < 1)
		{
			return false;
		}
		//$dir = DOCROOT . 'themes' . DIRECTORY_SEPARATOR .$id.DIRECTORY_SEPARATOR;
		$dir = 'D:\www\opococ.com\opococ2\themes\test\\';
		$file_list = self::readdir($dir);
		if(count($files) < 1)
		{
			$lists = $file_list;
		}
		else
		{
			foreach($files as $key=>$value)
			{
				if(in_array($value,$file_list))
				{
					$lists[] = $value;
				}
			}
		}

		foreach($lists as $key=>$value)
		{
			self::clear();
			$file_name = $dir . $value;
			$content = file_get_contents($file_name);
			self::compile($content,$value);
			file_put_contents($file_name,self::$content);
			echo $value."<br/>";
		}
		print_r(self::$result);
	}

	/**
	 * 清理成员变量值
	 */
	public static function clear()
	{
		self::$content = NULL;
	}

	/**
	 * 读取目录文件列表
	 */
	public static function readdir($dir) 
	{
		$list = array();
		$handle=opendir($dir);
		$i=0;
		while($file=readdir($handle)) {
			if (($file!=".")and($file!="..")and(substr($file,0,1)!='.')and(trim(substr(strrchr($file, '.'), 1, 10))=='php')) {
				$list[$i]=$file;
				$i=$i+1;
			}
		}
		closedir($handle);
		return $list;
	}

	/**
	 * 编译文件，把要替换内容换成指定内容
	 *
	 * @param string $content 文件内容
	 * @param string $prefix  文件前缀标识
	 * @param array $glb 当前已经替换的全局内容
	 *
	 * @return string
	 */
	public static function compile($content=NULL,$prefix=NULL,$glb=array())
	{
		self::$content = $content;
		self::$prefix = basename($prefix,'.php');
		self::$result = $glb;
		if(empty(self::$content))
		{
			return false;
		}
		self::text_compile();
		self::image_compile();
		self::link_compile();
	}

	/**
	 * 编译要替换的文本内容
	 */
	public static function text_compile()
	{
		if (preg_match_all('/<!-- TEXT\|(.*) -->(.*)<!-- ENDTEXT -->/',self::$content,$var))
		{
			$i = count(self::$result);
			$var_names = $var[1];
			foreach($var[2] as $k=>$value)
			{
				$key_temp = self::$prefix . "text" . $i;
				$regex = "<!-- TEXT|" . $var_names[$k] . " -->" . $value . "<!-- ENDTEXT -->";
				$code = '<?php echo glb::get(\'' . $key_temp . '\');?>';
				self::$content = str_replace($regex,$code,self::$content);
				$i++;
			}
			self::$content = str_replace("<!-- ENDTEXT -->",'',self::$content);
		}
	}

	/**
	 * 编译要替换的链接内容
	 */
	public static function link_compile()
	{
		if (preg_match_all('/<!-- LINK\|(.*) -->(.*)<!-- ENDLINK -->/',self::$content,$var))
		{
			$var_names = $var[1];
			$i = 1;
			foreach($var[2] as $k=>$tag)
			{
				if(self::$prefix = NULL)
				{
					$prefix = self::rand_var();
					$key = $prefix . 'link' . $i . '_';
				}
				else
				{
					$key = self::$prefix . 'link' . $i . '_';
				}
				//保证无重复
				while(isset(self::$result[$key]))
				{
					$prefix = self::rand_var();
					$key = $prefix . 'link' . $i . '_';
				}
				//print_r(self::$image_global);
				$temp_str = $tag;
				foreach(self::$link_global as $value)
				{
					$key_temp = $key . $value;
					if(preg_match("/" . $value . "\s*=\s*/",$tag))
					{
						$regex = "/" . $value . "\s*=\s*[\'\"]?(.*?)[\'\"][^>]/si";
						preg_match_all($regex ,$tag ,$link_var);
						self::$result[$key_temp] = array('name'=>$var_names[$k].$value,'value'=>$link_var[1]);
						$temp_str = preg_replace($regex,$value . '="<?php echo glb::get(\'' . $key_temp . '\');?>" ',$temp_str);
					}
				}
				//var_dump($tag);
				//var_dump($temp_str);exit;
				self::$content = str_replace($tag,$temp_str,self::$content);
				$i++;
			}
			foreach($var_names as $value)
			{
				self::$content = str_replace("<!-- LINK|" . $value . " -->","",self::$content);
			}
			self::$content = str_replace("<!-- ENDLINK -->","",self::$content);
		}
	}

	/**
	 * 编译要替换的图片内容
	 */
	public static function image_compile()
	{
		if (preg_match_all('/<!-- IMAGE\|(.*) -->(.*)<!-- ENDIMAGE -->/',self::$content,$var))
		{
			$var_names = $var[1];
			$i = 1;
			foreach($var[2] as $k=>$tag)
			{
				if(self::$prefix = NULL)
				{
					$prefix = self::rand_var();
					$key = $prefix . 'image' . $i . '_';
				}
				else
				{
					$key = self::$prefix . 'image' . $i . '_';
				}
				//保证无重复
				while(isset(self::$result[$key]))
				{
					$prefix = self::rand_var();
					$key = $prefix . 'image' . $i . '_';
				}
				//print_r(self::$image_global);
				$temp_str = $tag;
				foreach(self::$image_global as $value)
				{
					$key_temp = $key . $value;
					if(preg_match("/" . $value . "\s*=\s*/",$tag))
					{
						$regex = "/" . $value . "\s*=\s*[\'\"]?(.*?)[\'\"][^>]/si";
						preg_match_all($regex ,$tag ,$image_var);
						self::$result[$key_temp] = array('name'=>$var_names[$k].$value,'value'=>$image_var[1]);
						$temp_str = preg_replace($regex,$value . '="<?php echo glb::get(\'' . $key_temp . '\');?>" ',$temp_str);
					}
				}
				//var_dump($tag);
				//var_dump($temp_str);exit;
				self::$content = str_replace($tag,$temp_str,self::$content);
				$i++;
			}
			foreach($var_names as $value)
			{
				self::$content = str_replace("<!-- IMAGE|" . $value . " -->","",self::$content);
			}
			self::$content = str_replace("<!-- ENDIMAGE -->","",self::$content);
		}
	}

	/**
	 * 得到随机字符串
	 *
	 * @param init $num 字符串长度
	 * 
	 * @return string
	 */
	public static function rand_var($num = 5)
	{
		$s = "A B C D E F G H I J K L M N O P Q R S T U V W X Y Z 0 1 2 3 4 5 6 7 8 9";
		$sa = explode ( " ", $s );
		$randomnum = "";
		$ss = "";
		$rand_keys = array ();
		srand ( ( float ) microtime () * 10000000 );
		for($i=0;$i<$num;$i++)
		{
			$rand_keys = array_rand ( $sa, 1 );
			$ss .= $sa [trim ( $rand_keys [0] )];
		}
		return $ss;
	}
}
