<?php defined('SYSPATH') OR die('No direct access allowed.');

class Site_Core {

	/**
	 * 得到站点默认的域名
	 *
	 * @param Int $site_id    站点ID 
	 * @return String
	 */
	public static function default_domain()
	{
		$default_demain = Mysite::instance()->default_domain();
		$default_demain = ltrim($default_demain,'http://');
		return 'http://'.$default_demain;
	}

	/**
	 * enter site by site id
	 *
	 * @param int $id
	 * @return int
	 */
	public static function enter($id)
	{
		$site = Mysite::instance($id)->get();

		$session = Session::instance();

		$id = $session->set('switch_site_id',$id);
		$domain = $session->set('switch_site_domain',$site['domain']);
		//verity flag
		$flag = $session->set('switch_site_flag','site');

		return $id;
	}

	/**
	 * 登出当前站点
	 *
	 * @return boolean
	 */
	public static function out()
	{
		$session = Session::instance();

		$session->delete('switch_site_id');
		$session->delete('switch_site_domain');
		//verity flag
		$session->delete('switch_site_flag');

		return true;
	}

	/**
	 * get site type
	 */
	public static function type()
	{
		$site = Mysite::instance(self::id())->get();
		return $site['type'];
	}

	/**
	 * get active site id
	 *
	 * @return Int
	 */
	public static function id()
	{
        return 1;
	}

	/**
	 * 当前切入到指定站点后的只显示单站点数据
	 */
	public static function current_query_site_ids()
	{
        return array(1);
	}

	/**
	 * get active site domain
	 *
	 * @return String
	 */
	public static function domain()
	{
		$session = Session::instance();

		$id = $session->get('switch_site_id');
		$domain = $session->get('switch_site_domain');
		$flag = $session->get('switch_site_flag');

		if($flag == 'site') {
			return $domain;
		}else {
			return false;
		}
	}

	/**
	 * init b2b site data
	 * 
	 * @param int $site_id
	 * @param int $theme_id
	 * @return boolean
	 */
	public static function b2b_init($site_id,$theme_id = 100)
	{
		$type = 1;
		//doc default info
		//Mydoc::instance()->init($site_id,$type);
		//seo default info
		Myseo::instance()->init($site_id,$type);
		//route default info
		Myroute::instance()->init($site_id,$type);
		//menu default info
		//Mysite_menu::instance()->init($site_id,$type);
		//theme default info
		Mytheme::instance($theme_id)->init($site_id,$type);
		//mail default info
		Mymail::instance()->init($site_id,$type);
		//product default info
		Myb2b_demo_data_import::instance()->import($site_id);
	}

	public static function menu_init($site_id,$site_type_id = 0)
	{
	}
	/**
	 * init site data
	 * 
	 * @param int $site_id
	 * @param int $site_type_id
	 * @return boolean
	 */
	public static function doc_init($site_id,$site_type_id = 0)
	{
		$type = 'default';
		if($site_type_id)
		{
			$type = $site_type_id;
		}
		$permalink = kohana::config("doc.$type.permalink");
		$name = kohana::config("doc.$type.name");
		foreach($permalink as $key=>$value)
		{
			$doc = Mydoc::instance()->get_by_permalink($value,$site_id);
			$content = kohana::config("doc.$type.$value");
			$data = array();
			$data['title'] = $name[$key];
			$data['permalink'] = $value;
			$data['content'] = $content;
			$data['site_id'] = $site_id;
			if($doc['id'])
			{
				Mydoc::instance($doc['id'])->edit($data);
			}
			else
			{
				Mydoc::instance()->add($data);
			}
		}
	}
	/**
	 * init site data
	 * 
	 * @param int $site_id
	 * @param int $theme_id
	 * @return boolean
	 */
	public static function init($site_id,$theme_id = 1)
	{
		$type = 0;
		//doc default info
		//Mydoc::instance()->init($site_id);
		site::doc_init($site_id);
		//faq default info
		Myfaq::instance()->init($site_id);
		//seo default info
		Myseo::instance()->init($site_id);
		//route default info
		Myroute::instance()->init($site_id);
		//menu default info
		Mysite_menu::instance()->init($site_id);
		//theme default info
		Mytheme::instance($theme_id)->init($site_id);
		//mail default info
		Mymail::instance()->init($site_id);
		//product default info
		//Mydata_import::instance()->import($site_id);
	}

	/**
	 * next flow button
	 *
	 * @param string $current_flow_flag
	 * @return string
	 */
	public static function next_flow_btn($current_flow_flag)
	{
		$next_flow = self::next_flow($current_flow_flag);
		$str = '';
		if($next_flow)
		{
			$str .= '<input type="button" name="button" class="ui-button" title="' . $next_flow['name'] . '" value="保存下一步"  onclick="submit_form(2);"/>';
			return $str;	
		}
		else
		{
			return NULL;
		}
	}

	/**
	 * site config next flow button
	 * 
	 * @param object $current_flow_flag
	 * @return 
	 */
	public static function site_next_flow_btn($current_flow_flag)
	{
		$next_flow = self::site_next_flow($current_flow_flag);
		$str = '';
		if($next_flow)
		{
			$str .= '<input type="button" name="button" class="ui-button" title="' . $next_flow['name'] . '" value="保存继续' . $next_flow['name'] . '配置"  onclick="submit_form(2);"/>';
			return $str;	
		}
		else
		{
			return NULL;
		}
	}

	/**
	 * get site config next flow info
	 * 
	 * @param object $current_flow_flag
	 * @return 
	 */
	public static function site_next_flow($current_flow_flag)
	{
		return self::next_flow($current_flow_flag,2);
	}

	/**
	 * get next flow info
	 *
	 * @param string $current_flow_flag
	 * @param int $type
	 * @return array
	 */	
	public static function next_flow($current_flow_flag,$type=1)
	{
		if($type == 1)
		{
			$flows = Kohana::config('site.manager_flow');
		}
		else
		{
			$flows = Kohana::config('site.site_config_flow');	
		}
		$flow_flags = array();
		if(isset($flows))
		{
			foreach($flows as $key=>$value)
			{
				$flow_flags[$value['flag']] = $key;
			}
			if(in_array($current_flow_flag,$flow_flags))
			{
				$key_num = $flow_flags[$current_flow_flag];
				if(isset($flows[$key_num+1]))
				{
					return $flows[$key_num+1];
				}
				else
				{
					return false;
				}
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}
	}
}
