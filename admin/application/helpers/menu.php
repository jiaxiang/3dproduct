<?php
defined ( 'SYSPATH' ) or die ( 'No direct script access.' );
class Menu_Core
{
	private static $expire = 300;
	/**
	 * 根据用户权限得到用户可查看的菜单
	 *
	 * @reture Arrat
	 */
	public static function user_menus()
	{
		$menu_flags = array ();
		$current_url = url::current ();
		$menus = self::get_avalible_menu();

		foreach ( $menus as $menus_key => $menus_value )
		{
			//子项中无菜单把主项也不显示
			$sub_menu = $menus_value ['children'];
			if (count ( $sub_menu ) < 1)
			{
				unset ( $menus [$menus_key] );
			}
			foreach ( $sub_menu as $key => $value )
			{
				$url_arr = explode ( '/', $value ['url'] );
				if (count ( $url_arr ) > 0)
				{
					$menu_flags [$url_arr [0]] = array ('key' => $menus_key, 'subkey' => $key );
				}
				if (count ( $url_arr ) > 1)
				{
					$menu_flags [$url_arr [0] . $url_arr [1]] = array ('key' => $menus_key, 'subkey' => $key );
				}
				if (count ( $url_arr ) > 2)
				{
					$menu_flags [$url_arr [0] . $url_arr [1] . $url_arr [2]] = array ('key' => $menus_key, 'subkey' => $key );
				}
				$value['aliases'] = explode('|',$value['alias']);
				if(count($value['aliases'])>0)
				{
					foreach($value['aliases'] as $k=>$v)
					{
						$alias_url_arr = explode ( '/', $v );
						if (count ( $alias_url_arr ) > 0)
						{
							$menu_flags [$alias_url_arr[0]] = array ('key' => $menus_key, 'subkey' => $key );
						}
						if (count ( $alias_url_arr ) > 1)
						{
							$menu_flags [$alias_url_arr[0] . $alias_url_arr[1]] = array ('key' => $menus_key, 'subkey' => $key );
						}
						if (count ( $alias_url_arr ) > 2)
						{
							$menu_flags [$alias_url_arr[0] . $alias_url_arr[1] . $alias_url_arr[2]] = array ('key' => $menus_key, 'subkey' => $key );
						}
					}
				}
			}
		}
		$current_url_arr = explode ( '/', $current_url );
		if (count ( $current_url_arr ) > 2)
		{
			$key = $current_url_arr [0] . $current_url_arr [1] . $current_url_arr [2];
			if (isset ( $menu_flags [$key] ))
			{
				$menus [$menu_flags [$key] ['key']] ['active'] = 10;
				$menus [$menu_flags [$key] ['key']] ['children'] [$menu_flags [$key] ['subkey']] ['active'] = 10;
				return $menus;
			}
		}
		if (count ( $current_url_arr ) > 1)
		{
			$key = $current_url_arr [0] . $current_url_arr [1];
			if (isset ( $menu_flags [$key] ))
			{
				$menus [$menu_flags [$key] ['key']] ['active'] = 10;
				$menus [$menu_flags [$key] ['key']] ['children'] [$menu_flags [$key] ['subkey']] ['active'] = 10;
				return $menus;
			}
		}
		return $menus;
	}

	/*
	 * 当前菜单
	 */
	public static function current_menus()
	{
		$menus = self::user_menus();

		echo '<ul class="new_main_nav">';
		//echo '<li><a href="/">首页</a></li>';

		foreach ( $menus as $key => $value )
		{
			if ($value ['active'] == 10)
			{
				echo '<li class="on" id="on">';
				echo "<a class='on' href='javascript:void(0);' id='".$value ['target']."' onclick='menu_toggle(this)'>".$value ['name']."</a>";
			}
			else
			{
				echo "<li>";
				echo "<a href='javascript:void(0);' id='".$value ['target']."' onclick='menu_toggle(this)'>".$value ['name']."</a>";
			}
            
				echo "<ul>";
				$end_val = end($value['children']);
				foreach ( $value ['children'] as $sub_key => $sub_value )
				{
					$link_url = self::get_url($sub_value ['url']);
					if($end_val['id'] == $sub_value['id'])
					{
						echo (count($sub_value['children'])>0)?"<li class='last parent'>":"<li class='last'>";
					}
					else
					{
						echo (count($sub_value['children'])>0)?"<li class='parent'>":"<li>";
					}
					echo "<a href='" . $link_url . "'>" . $sub_value ['name'] . "</a>";
					if(count($sub_value['children'])>0)
					{
						echo "<ul>";
						$sub_end_val = end($sub_value['children']);
						foreach($sub_value['children'] as $third_key=>$third_value)
						{
							$link_url = self::get_url($third_value ['url']);
							if($sub_end_val['id'] == $third_value['id'])
							{
								echo "<li class='last'><a href='" . $link_url . "'>" . $third_value ['name'] . "</a></li>";
							} else {
								echo "<li><a href='" . $link_url . "'>" . $third_value ['name'] . "</a></li>";
							}
							
						}
						echo "</ul>";
					}
					echo "</li>";
				}
				echo "</ul>";
				echo "</li>";
		}
		echo '</ul>';
	}
	
	/**
	 * 得到菜单的URL过滤无效链接
	 * @param string $url 
	 * @return string
	 */
	public static function get_url($url = '')
	{
		if(empty($url))
		{
			return 'javascript:void(0);';
		}
		$exist_arr = array('#','javascript:void(0);','javascript:void(0)');
		if(in_array($url, $exist_arr))
		{
			return 'javascript:void(0);';
		} else {
			return url::base() . $url;
		}
	}


	/**
	 * get user avalible menu
	 */
	public static function get_avalible_menu()
	{
		//all menus
		$menus = Mymenu::instance()->get_level_menus();

		//账号的资源列表
		$action_ids = role::get_action_ids();

        //无权限的菜单删除
		foreach ( $menus as $menus_key => $menus_value )
		{
			$sub_menu = $menus_value ['children'];
			foreach ( $sub_menu as $key => $value )
			{
				//if ($value ['action_id'] > 0){
					if (!in_array($value['action_id'], $action_ids))
					{
						unset ( $menus [$menus_key] ['children'] [$key] );
					}					
				//}
			}			
		}
		return $menus;
	}
}
