<?php defined('SYSPATH') OR die('No direct access allowed.');

class Mytable_Core {

	private $data;
	private static $instance;
	private $extra_table = array();

	public static function & instance()
	{
		if (!isset(self::$instance))
		{
			$class = __CLASS__;
			self::$instance = new $class();
		}
		return self::$instance;
	}

	public function delete_sql($site_id)
	{
		$database = Kohana::config('database.default.connection.database');

		$sql = "show tables";
		$tables=Database::instance()->query($sql)->result_array(FALSE);

		$sqls = array();
		foreach($tables as $key=>$rs){
			$sql = "DESCRIBE ".$rs['Tables_in_'.$database];
			$fields=Database::instance()->query($sql)->result_array(FALSE);

			foreach($fields as $k=>$v){
				if(!in_array($rs['Tables_in_'.$database],$this->extra_table))
				{
					if($v['Field'] == 'site_id'){
						$sqls[] =  "delete from ".$rs['Tables_in_'.$database]." where site_id = ".$site_id;
					}
				}
			}
		}
		$sqls[] = "delete from sites where id = ".$site_id;
		return $sqls;
	}

	public function delete_by_site_id($site_id)
	{
		$sqls = Mytable::instance()->delete_sql($site_id);
		foreach($sqls as $sql){
			//echo $sql.";<br />";
			Database::instance()->query($sql);
		}
	}
}
