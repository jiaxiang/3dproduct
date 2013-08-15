<?php defined('SYSPATH') OR die('No direct access allowed.');

class Myscan_single_Core {
	private $data = array();
	private $error = array();

	private static $instances;
	public static function & instance($id = 0)
	{
		if (!isset(self::$instances[$id]))
		{
			$class = __CLASS__;
			self::$instances[$id] = new $class($id);
		}
		return self::$instances[$id];
	}

	/**
	 * Construct load scan_single data
	 *
	 * @param Int $id
	 */
	public function __construct($id)
	{
		$this->_load($id);
	}

	/**
	 * load scan_single data
	 *
	 * @param Int $id
	 */
	private function _load($id)
	{
		$id = intval($id);

		$scan_single = ORM::factory('scan_single',$id)->as_array();
		$this->data = $scan_single;
	}



	/**
	 * get scan_templat data
	 *
	 * @return Array
	 */
	public function get($key = NULL)
	{
		if(empty($key))
		{
			return $this->data;
		}
		else
		{
			if(isset($this->data[$key]))
			{
				return $this->data[$key];
			}
			else
			{
				return NULL;
			}
		}
	}

	/**
	 * add a scan_single
	 *
	 * @param Array $data
	 * @return Array
	 */
	public function add($data)
	{
		//ADD
		$scan_single = ORM::factory('scan_single');
		$errors = '';
		if($scan_single->validate($data ,TRUE ,$errors))
		{
			$this->data = $scan_single->as_array();
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}



    /**
     * get api error
     *
     * @return Array
     */
    public function error()
    {
        $result = '';
        if(count($this->error))
        {
            $result     = '<br />';
            foreach($this->error as $key=>$value)
            {
                $result .= ($key+1).' . '.$value.'<br />';
            }
        }
        return $result;
    }
}
