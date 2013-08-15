<?php defined('SYSPATH') or die('No direct script access.');

class Statistics_Core{
	public static $task = array();
	/**
	 * get task id by id
	 *
	 * @param int $site_id
	 * @return int
	 */
	public static function get_task_id()
	{
		$site = Mysite::instance()->get();
		$domain = $site['domain'];
		return self::get_task_id_by_domain($domain);
	}
    
	/**
	 * get task id by domain
	 *
	 * @param string $domain
	 * @return int
	 */
	public static function get_task_id_by_domain($domain)
	{
		$monitors = Mymonitor::instance('jiankongbao')->get_list();
		if($monitors)
		{
			foreach($monitors as $key=>$value)
			{
				if($value['task_name'] == $domain)
				{
					self::$task = $value;
					return $value['task_id'];
				}
			}
		}
		return 0;
	}
	public static function report_date($id = 0,$date = NULL)
	{
		$report_date = array(
			'date' => date("Y-m-d H:i:s"),
			'start_check_time' => null,
			'last_check_time' => null,
			'uptime_percent' => null,
			'uptime_minute' => null,
			'fault_time_minute' => null,
			'fault_time_percent' => null,
			'total_check_sum' => null,
			'up_check_sum' => null,
			'fault_check_sum' => null,
			'resp_time_avg' => null,
			'resp_time_min' => null,
			'resp_time_max' => null
		);
		if(is_null($date))
		{
			$date = date('Ymd');
		}
		else
		{
			$date = date("Ymd",strtotime($date));
		}

		$report = Mymonitor::instance('jiankongbao')->report_date($id, $date);
		if($report)
		{
			return $report;
		}
		else
		{
			return $report_date;
		}
	}

	/**
	 * get statking day info list
	 *
	 * @param Int $statking_id
	 * @param Int $limit
	 * @param Int $offset
	 * @return Array
	 */
	public static function get_statkings($statking_id,$limit = 10,$offset = 0)
	{
		$data_str = '';
		$list = statking::get_day_list($statking_id,$limit,$offset);
		if($list)
		{
			foreach($list as $key=>$value)
			{
				$data_str .= date('m-d',$value['times']) . ';';
				$data_str .= $value['day_count'] . ';';
				$data_str .= $value['day_count_ip'] . '\n';
			}
		}

		return $data_str;
	}

	/**
	 * get resp string
	 *
	 * @return String
	 */
	public static function get_list($id,$begin_date = null,$num = 15)
	{
		//监控宝数据
		$list = array();
		if(is_null($begin_date))
		{
			$begin_date = date('Ymd',time()-$num*24*60*60);
		}

		for($i=1;$i<=$num;$i++)
		{
			$begin_date = date('Ymd',strtotime($begin_date)+24*60*60);
			//var_dump($cur_data);
			$list[$i] = Mymonitor::instance('jiankongbao')->report_date($id, $begin_date);
			$list[$i]['date'] = date('m-d',strtotime($begin_date));
		}

		$data_str = '';
		foreach($list as $key=>$value)
		{
			if(!isset($value['resp_time_avg']))
				$value['resp_time_avg'] = 0;
			if(!isset($value['resp_time_min']))
				$value['resp_time_min'] = 0;
			if(!isset($value['resp_time_max']))
				$value['resp_time_max'] = 0;
			$resp_time_min = $value['resp_time_min'];
			$resp_time_avg = $value['resp_time_avg'];
			$resp_time_max = $value['resp_time_max'];
			//US
			if($resp_time_min > 3000)
			{
				$value['resp_time_US'] = self::resp_compute($value['resp_time_min']);
			}
			else
			{
				$value['resp_time_US'] = $resp_time_min;
			}
			//UK
			if($resp_time_avg > 3000)
			{
				$value['resp_time_UK'] = self::resp_compute($value['resp_time_avg']);
			}
			else
			{
				$value['resp_time_UK'] = $resp_time_avg;
			}
			//CH
			if($resp_time_max > 3000)
			{
				$value['resp_time_CH'] = self::resp_compute($value['resp_time_max']);
			}
			else
			{
				$value['resp_time_CH'] = $resp_time_max;
			}

			$data_str .= $value['date'] . ';';
			$data_str .= $value['resp_time_US'] . ';';
			$data_str .= $value['resp_time_UK'] . ';';
			$data_str .= $value['resp_time_CH'] . '\n';
		}

		return $data_str;

	}

	/**
	 * compute statistics
	 *
	 * @param Int $time
	 * @return String
	 */
	public function resp_compute($time)
	{
		if($time > 3000)
		{
			$n = intval($time/3000);
			if($n > 1)
			{
				$m = $n*($time%1000);
				if($m > 3000)
				{
					return $m % 1000+100;
				}
				else
				{
					return $m;
				}
			}
			else
			{
				return $time%1000;
			}
		}
		else
		{
			return $time;
		}
	}

	/**
	 * compute statistics
	 *
	 * @param Int $time
	 * @return String
	 */
	public function get_average_data($data)
	{
		if($data['count_ip']>0)
		{
			$data['conversion_rate'] = round($data['count_order']/$data['count_ip']*100,2).'%';
		}
		else
		{
			$data['conversion_rate'] = '0%';
		}

		if($data['count_user']>0)
		{
			$data['order_user_rate'] = round($data['count_order_user']/$data['count_user']*100,2).'%';
		}
		else
		{
			$data['order_user_rate'] = '0%';
		}

		if($data['count_order']>0)
		{
			$data['average_order'] = round($data['sum_order']/$data['count_order'],2);
		}
		else
		{
			$data['average_order'] = '0';
		}
		return $data;
	}
}
