<?php defined('SYSPATH') OR die('No direct access allowed.');
class Cron_bjdc_Controller extends Controller{
	public function update_plan_match_result() {
		$obj = Plans_bjdcService::get_instance();
		$obj->get_plans_result();
	}
}