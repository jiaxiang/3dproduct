<?php defined('SYSPATH') OR die('No direct access allowed.');

class Zip_Core {
	protected $handler = NULL;
	
	public static function factory($zipname)
	{
		if (!function_exists('zip_open'))
		{
			exit('zip_open is not found.');
		}
		
		if (file_exists($zipname) AND is_readable($zipname))
		{
			$handler = zip_open($zipname);
			if (is_resource($handler))
			{
				return new Zip_Core($handler);
			}
		}
		
		return FALSE;
	}
	
	protected function __construct($handler)
	{
		$this->handler = $handler;
	}
	
	public function __destruct()
	{
		$this->close();
	}
	
	public function read()
	{
		return Zip_Entry_Core::factory(zip_read($this->handler));
	}
	
	public function extract($direct)
	{
		$direct = rtrim($direct, '/');
		if (is_dir($direct) AND is_writeable($direct))
		{
			while ($item = $this->read())
			{
				$name = $direct.'/'.$item->name();
				if ($item->type() == Zip_Entry_Core::DIR)
				{
					$name = rtrim($name, '/');
					if (!is_dir($name) AND !@mkdir($name, 0777, TRUE))
					{
						return FALSE;
					}
				} else {
					while (TRUE)
					{
						$content = $item->read(262144);
						
						if ($content === FALSE)
						{
							return FALSE;
						}
						
						$write = @file_put_contents($name, $content, FILE_APPEND);
						if ($write === FALSE)
						{
							return FALSE;
						} elseif ($write == 0) {
							break;
						}
					}
				}
				unset($item);
			}
			
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
	public function close()
	{
		if (is_resource($this->handler))
		{
			zip_close($this->handler);
			$this->handler = NULL;
		}
	}
}

class Zip_Entry_Core {
	const DIR  = 0;
	const FILE = 1;
	
	protected $handler = NULL;
	protected $type    = NULL;
	
	public static function factory($zip_entry)
	{
		if (is_resource($zip_entry))
		{
			return new Zip_Entry_Core($zip_entry);
		} else {
			return FALSE;
		}
	}
	
	protected function __construct($zip_entry)
	{
		$this->handler = $zip_entry;
		$name = $this->name();
		$this->type = $name{strlen($name) - 1} === '/' ? self::DIR : self::FILE;
	}
	
	public function __destruct()
	{
		$this->close();
	}
	
	public function type()
	{
		return $this->type;
	}
	
	public function name()
	{
		return zip_entry_name($this->handler);
	}
	
	public function size()
	{
		return zip_entry_filesize($this->handler);
	}
	
	public function read($length = 1024)
	{
		return zip_entry_read($this->handler, $length);
	}
	
	public function compressedsize()
	{
		return zip_entry_compressedsize($this->handler);
	}
	
	public function compressionmethod()
	{
		return zip_entry_compressionmethod($this->handler);
	}
	
	public function close()
	{
		if (is_resource($this->handler))
		{
			zip_entry_close($this->handler);
			$this->handler = NULL;
		}
	}
}