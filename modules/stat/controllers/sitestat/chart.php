<?php defined('SYSPATH') OR die('No direct access allowed.');
ini_set('display_errors',0);

class Chart_Controller extends Controller{
	
	
	public function index(){
		$chart_dir = dirname(__FILE__).'/chart/';
		if ($_GET['type'] == 'lc') {
			require_once($chart_dir.'drawLineChart.php');
		}elseif ($_GET['type'] == 'bg'){
			require_once($chart_dir.'drawBarGraph.php');
		}elseif ($_GET['type'] == 'pc'){
			require_once($chart_dir.'drawPieChart.php');
		}
	}
	
	public function description(){
		$chart_dir = dirname(__FILE__).'/chart/';
		echo file_get_contents($chart_dir.'chart.html');
	}
}