<?php defined('SYSPATH') OR die('No direct access allowed.');

class Csv_Core {
	
	public static function encode($array)
	{
		$csv = '';
		foreach ($array as $line)
		{
			if (!empty($csv))
			{
				$csv .= "\n";
			}
			$csv_l = '';
			foreach ($line as $i => $item)
			{
				if ($i > 0)
				{
					$csv_l .= ',';
				}
				$csv_l .= self::escape($item);
			}
			$csv .= $csv_l;
		}
		return $csv;
	}
	
	public static function decode($csv)
	{
		$csv    = trim($csv);
		$array  = array();
		$line   = 0;
		$offset = 0;
		$item   = '';
		$fp     = FALSE;
		while (isset($csv{$offset}))
		{
			if (!isset($array[$line]))
			{
				$array[$line] = array();
			}
			switch ($csv{$offset})
			{
				case '"':
					$c = 0;
					while (isset($csv{$offset}) AND $csv{$offset} === '"')
					{
						$c ++;
						$offset ++;
					}
					if ($c%2 === 1)
					{
						$fp = !$fp;
						$c --;
					}
					if ($c > 0)
					{
						$item .= str_repeat('"', $c);
					}
					break;
				case ',':
					if ($fp === FALSE)
					{
						$array[$line][] = self::escape($item, 'D');
						$item = '';
					} else {
						$item .= $csv{$offset};
					}
					$offset ++;
					break;
				case "\t":
					if ($fp === FALSE)
					{
						$array[$line][] = self::escape($item, 'D');
						$item = '';
					} else {
						$item .= $csv{$offset};
					}
					$offset ++;
					break;
				case "\n":
					if ($fp === FALSE)
					{
						$array[$line][] = self::escape($item, 'D');
						$item = '';
						$line ++;
					} else {
						$item .= $csv{$offset};
					}
					$offset ++;
					break;
				default:
					$item .= $csv{$offset};
					$offset ++;
			}
			if (!isset($csv{$offset}))
			{
				$array[$line][] = self::escape($item, 'D');
			}
		}
		return $array;
	}
	
	protected static function escape($str, $coding = 'E')
	{
		if ($coding === 'E')
		{
			if (strpos($str, '"') !== FALSE)
			{
				$str = str_replace('"', '""', $str);
			}
			if (strpos($str, ',') !== FALSE OR strpos($str, "\n") !== FALSE)
			{
				$str = '"'.$str.'"';
			}
		} else {
			$str = str_replace('""', '"', $str);
		}
		return $str;
	}
}