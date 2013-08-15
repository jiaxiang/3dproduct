<?php
defined('SYSPATH') or die('No direct script access.');

class Myseo_Core
{
	private static $instance;
	private $data;

	public static function &instance()
	{
		if(!isset(self::$instance))
		{
			$class = __CLASS__;
			self::$instance = new $class();
		}
		
		return self::$instance;
	}

	public function __construct()
	{

	}

	/**
	 * get site's seo data
	 *
	 * @param 	Int 	site id
	 * @return 	Array 	seo data
	 */
	public function get()
	{
		return ORM::factory('seo')->find()->as_array();
	}

	/**
	 * edit site's seo data
	 *
	 * @param 	Int 	site id
	 * @param 	Array 	seo data
	 * @return 	Boolean 
	 */
	public function edit($data)
	{
		$seo = ORM::factory('seo')->find();
		if($seo->validate($data,TRUE))
		{
			return TRUE;
		}
		
		return FALSE;
	}

	/**
	 * init site'seo data
     * 
	 */
	public function init()
	{
		$data = array();
		$data['title'] = '';
		$data['description'] = '';
		$data['keywords'] = '';
		$data['index_title'] = '';
		$data['index_description'] = '';
		$data['index_keywords'] = '';
		$data['seowords'] = '';
        
		if($this->edit($data))
		{
			return TRUE;
		}
		return FALSE;
	}

	/**
	 * 删除站点所有的信息
	 * 
	 * @param int $site_id
	 * @return boolean
	 */
	public function delete()
	{
		//删除
		$seo = ORM::factory('seo');
		$seo->delete_all();
		
		return true;
	}
}
