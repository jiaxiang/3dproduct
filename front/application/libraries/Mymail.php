<?php defined('SYSPATH') OR die('No direct access allowed.');

class Mymail_Core {
	private $data = array();
	private $errors = array();

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
	 * Construct load mail data
	 *
	 * @param Int $id
	 */
	public function __construct($id)
	{
		$this->_load($id);
	}

	/**
	 * load mail data
	 *
	 * @param Int $id
	 */
	private function _load($id)
	{
		$id = intval($id);

		$mail = ORM::factory('mail',$id)->as_array();
		$this->data = $mail;
	}

	/**
	 * get mail data
	 *
	 * @param <String> $key column
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
	 * get mail by mail type
	 *
	 * @param <String> $type mail type
	 * @param <Int> $site_id site id
	 * 
	 * @return Array
	 */
	public function get_by_type($site_id = 0,$category_id = 0)
	{
		$where = array();
		$where['mail_category_id'] = $category_id;
        $mail = ORM::factory('mail')->where($where)->find();
        return $mail->as_array();
	}

    /**
     * get api error
     *
     * @return Array
     */
    public function error()
    {
        $result = '';
        if(count($this->errors))
        {
            $result     = '<br />';
            foreach($this->errors as $key=>$value)
            {
                $result .= ($key+1).' . '.$value.'<br />';
            }
        }
        return $result;
    }
}
