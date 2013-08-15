<?php defined('SYSPATH') or die('No direct script access.');

class Statking_Core{
	public static $statking_id = 0;
	public static $statking_code = "";
	/**
	 * 获取统计代码与统计id
	 *
	 * @return Int
	 */
	public static function register_statking($site_id,$site_domain,$site_name = NULL)
	{
		if(!$site_name)
		{
			// 从域名中取得后面两段
			preg_match("/[^\.\/]+\.[^\.\/]+$/", $site_domain, $matches);
			$site_name = $matches[0];
		}

		$post_url = "http://stat.abizark.com/stat/get_statking_code.php";
		$post_var = "site_id=".$site_id."&site=".$site_domain."&sitename=".$site_name;
		$result = tool::curl_pay($post_url,$post_var);
		if(is_numeric($result))
		{
			self::$statking_id = intval($result);
		}
		self::$statking_code = self::format_statking_code(self::$statking_id);
		return self::$statking_code;
	}

	/**
	 * 获取统计代码与统计id
	 *
	 * @return string
	 */
	public static function format_statking_code($statking_id)
	{
		$statking_code =
			'<script type="text/javascript">'.
			'var _PCSHost = (("https:" == document.location.protocol) ? "https://" : "http://");'.
			'var _PCSWebSite="'.$statking_id.'";'.
			'var _PCSText="num1";'.
			'document.write(unescape("%3Cscript src=\'" + _PCSHost + "stat.abizark.com/stat/count/count.js\' type=\'text/javascript\'%3E%3C/script%3E"));'.
			'</script>';
		return $statking_code;
	}
	/**
	 * 主界面信息
	 * 每月每日
	 * 最大最小
	 * 等等
	 *
	 * @return Array
			Array
			(
				[site] => Array
					(
						[id] => 1
						[managerid] => abizark
						[site] => http://www.test.com
						[site_id] =>
						[sitename] => test.com
						[website] => 100001
						[sitedes] => test
						[sitetype] => 0
						[websitetype] => 综合门户
						[siteshow] => 0
						[siterank] => 0
						[sitegroup] => 50
						[email] =>
						[msn] => strongsuperadmin
						[exclusionip] =>
						[exclusioninter] =>
						[logincount] => 0
						[lastlogin] => 0
						[all_count] => 0
						[all_count_ip] => 0
						[week0] => 0
						[week1] => 0
						[week2] => 0
						[week3] => 0
						[week4] => 0
						[week5] => 0
						[week6] => 0
						[day1] => 0
						[day2] => 0
						[day3] => 0
						[day4] => 0
						[day5] => 0
						[day6] => 0
						[day7] => 0
						[day8] => 0
						[day9] => 0
						[day10] => 0
						[day11] => 0
						[day12] => 0
						[day13] => 0
						[day14] => 0
						[day15] => 0
						[day16] => 0
						[day17] => 0
						[day18] => 0
						[day19] => 0
						[day20] => 0
						[day21] => 0
						[day22] => 0
						[day23] => 0
						[day24] => 0
						[day25] => 0
						[day26] => 0
						[day27] => 0
						[day28] => 0
						[day29] => 0
						[day30] => 0
						[day31] => 0
						[weekip0] => 0
						[weekip1] => 0
						[weekip2] => 0
						[weekip3] => 0
						[weekip4] => 0
						[weekip5] => 0
						[weekip6] => 0
						[dayip1] => 0
						[dayip2] => 0
						[dayip3] => 0
						[dayip4] => 0
						[dayip5] => 0
						[dayip6] => 0
						[dayip7] => 0
						[dayip8] => 0
						[dayip9] => 0
						[dayip10] => 0
						[dayip11] => 0
						[dayip12] => 0
						[dayip13] => 0
						[dayip14] => 0
						[dayip15] => 0
						[dayip16] => 0
						[dayip17] => 0
						[dayip18] => 0
						[dayip19] => 0
						[dayip20] => 0
						[dayip21] => 0
						[dayip22] => 0
						[dayip23] => 0
						[dayip24] => 0
						[dayip25] => 0
						[dayip26] => 0
						[dayip27] => 0
						[dayip28] => 0
						[dayip29] => 0
						[dayip30] => 0
						[dayip31] => 0
					)

				[first_day] => 2010-01-29
				[count_days] => 12
				[average] => Array
					(
						[count_ip] => 0
						[count_pv] => 0
					)

				[today] => Array
					(
						[count_ip] =>
						[count_pv] =>
					)

				[yesterday] => Array
					(
						[count_ip] =>
						[count_pv] =>
					)

				[max] => Array
					(
						[count_ip] => 1
						[count_pv] => 1
					)

				[min] => Array
					(
						[count_ip] => 1
						[count_pv] => 1
					)

				[month] => Array
					(
						[count_ip] =>
						[count_pv] =>
					)

				[year] => Array
					(
						[count_ip] => 0
						[count_pv] => 0
					)

			)
	 */
	public static function get_main_detail($statking_id)
	{
		$post_url = "http://stat.abizark.com/index/main_detail";
		$post_var = "statking_id=".$statking_id;
		$result = tool::curl_pay($post_url,$post_var);
		$res = unserialize( stripcslashes($result));
		if(is_array($res))
		{
			return $res;
		}
		else
		{
			return false;
		}
	}

	/**
	 * 每天列表
	 *
	 * @param Int $id
	 * @return Array
			Array
			(
				[0] => Array
					(
						[day_count] => 1
						[day_count_ip] => 1
						[times] => 1268092800
					)

				[1] => Array
					(
						[day_count] => 1
						[day_count_ip] => 1
						[times] => 1268006400
					)

				[2] => Array
					(
						[day_count] => 1
						[day_count_ip] => 1
						[times] => 1267920000
					)

				[3] => Array
					(
						[day_count] => 1
						[day_count_ip] => 1
						[times] => 1267833600
					)

				[4] => Array
					(
						[day_count] => 1
						[day_count_ip] => 1
						[times] => 1267747200
					)

				[5] => Array
					(
						[day_count] => 1
						[day_count_ip] => 1
						[times] => 1267660800
					)

				[6] => Array
					(
						[day_count] => 1
						[day_count_ip] => 1
						[times] => 1267574400
					)

				[7] => Array
					(
						[day_count] => 1
						[day_count_ip] => 1
						[times] => 1267488000
					)

				[8] => Array
					(
						[day_count] => 1
						[day_count_ip] => 1
						[times] => 1267401600
					)

				[9] => Array
					(
						[day_count] => 1
						[day_count_ip] => 1
						[times] => 1267315200
					)

				[10] => Array
					(
						[day_count] => 1
						[day_count_ip] => 1
						[times] => 1267228800
					)

				[11] => Array
					(
						[day_count] => 1
						[day_count_ip] => 1
						[times] => 1267142400
					)

			)
	 */
	public static function get_day_list($statking_id,$limit = NULL, $offset = NULL)
	{
		/*
			$demo = Array
			(
				'0' => Array
					(
						'day_count' => 1212,
						'day_count_ip' => 321,
						'times' => 1268092800
					),
				'1' => Array
					(
						'day_count' => 989,
						'day_count_ip' => 312,
						'times' => 1268006400
					),
				'2' => Array
					(
						'day_count' => 321,
						'day_count_ip' => 213,
						'times' => 1267920000
					),
				'3' => Array
					(
						'day_count' => 1021,
						'day_count_ip' => 509,
						'times' => 1267833600
					),
				'4' => Array
					(
						'day_count' => 743,
						'day_count_ip' => 231,
						'times' => 1267747200
					),
				'5' => Array
					(
						'day_count' => 213,
						'day_count_ip' => 128,
						'times' => 1267660800
					),
				'6' => Array
					(
						'day_count' => 523,
						'day_count_ip' => 321,
						'times' => 1267574400
					),
				'7' => Array
					(
						'day_count' => 807,
						'day_count_ip' => 409,
						'times' => 1267488000
					),
				'8' => Array
					(
						'day_count' => 232,
						'day_count_ip' => 123,
						'times' => 1267401600
					),
				'9' => Array
					(
						'day_count' => 410,
						'day_count_ip' => 240,
						'times' => 1267315200
					),
				'10' => Array
					(
						'day_count' => 199,
						'day_count_ip' => 99,
						'times' => 1267228800
					),
				'11' => Array
					(
						'day_count' => 213,
						'day_count_ip' => 120,
						'times' => 1267142400
					),
				'12' => Array
					(
						'day_count' => 213,
						'day_count_ip' => 120,
						'times' => 1267142400
					),
				'13' => Array
					(
						'day_count' => 213,
						'day_count_ip' => 120,
						'times' => 1267142400
					),
				'14' => Array
					(
						'day_count' => 213,
						'day_count_ip' => 120,
						'times' => 1267142400
					),
				'15' => Array
					(
						'day_count' => 213,
						'day_count_ip' => 120,
						'times' => 1267142400
					)
				);
		return $demo;
		 */
		$post_url = "http://stat.abizark.com/index/day_list";
		$post_var = "statking_id=".$statking_id."&limit=".$limit."&offset=".$offset;
		$result = tool::curl_pay($post_url,$post_var);
		$res = unserialize( stripcslashes($result));
		if(is_array($res))
		{
			return $res;
		}
		else
		{
			return false;
		}
	}

}
