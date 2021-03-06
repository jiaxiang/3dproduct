<?php defined('SYSPATH') OR die('No direct access allowed.');
	
	$config['play_method'] = array(
		'501' => 'spf_result',
		'502' => 'sxds_result',
		'503' => 'zjqs_result',
		'504' => 'bf_result',
		'505' => 'bqc_result'
	);
	
 	$config['spf_result'] = array(
   		'胜' => 1,
		'平' => 2,
		'负' => 3
   	);
   	
   	$config['sxds_result'] = array(
   		'上+单' => 1,
		'上+双' => 2,
		'下+单' => 3,
		'下+双' => 4
   	);
   	
   	$config['zjqs_result'] = array(
   		'0' => 1,
		'1' => 2,
		'2' => 3,
		'3' => 4,
		'4' => 5,
		'5' => 6,
		'6' => 7,
		'7+' => 8
   	);
   	
   	$config['bf_result'] = array(
   		'1:0' => 1,
		'2:0' => 2,
		'3:0' => 3,
		'2:1' => 4,
		'3:1' => 5,
		'4:1' => 6,
		'3:2' => 7,
		'4:2' => 8,
		'4:0' => 9,
		'胜其他' => 10,
			
		'0:0' => 11,
		'1:1' => 12,
		'2:2' => 13,
		'3:3' => 14,
		'平其他' => 15,
			
		'0:1' => 16,
		'0:2' => 17,
		'0:3' => 18,
		'1:2' => 19,
		'1:3' => 20,
		'1:4' => 21,
		'2:3' => 22,
		'2:4' => 23,
		'0:4' => 24,
		'负其他' => 25
   	);
   	
   	$config['bqc_result'] = array(
   		'胜-胜' => 1,
		'胜-平' => 2,
		'胜-负' => 3,
		'平-胜' => 4,
		'平-平' => 5,
		'平-负' => 6,
		'负-胜' => 7,
		'负-平' => 8,
		'负-负' => 9
   	);
   	
   	$config['play_method_dyj'] = array(
   			'501' => 'spf_result_dyj',
   			'502' => 'sxds_result_dyj',
   			'503' => 'zjqs_result_dyj',
   			'504' => 'bf_result_dyj',
   			'505' => 'bqc_result_dyj'
   	);
   	
   	$config['spf_result_dyj'] = array(
   			'胜' => '3',
   			'平' => '1',
   			'负' => '0',
   	);
   	
   	$config['sxds_result_dyj'] = array(
   			'上+单' => '3',
   			'上+双' => '2',
   			'下+单' => '1',
   			'下+双' => '0',
   	);
   	
   	$config['zjqs_result_dyj'] = array(
   			'0' => '0',
   			'1' => '1',
   			'2' => '2',
   			'3' => '3',
   			'4' => '4',
   			'5' => '5',
   			'6' => '6',
   			'7+' => '7',
   	);
   	
   	$config['bf_result_dyj'] = array(
   			'负其他' => '9',
			'胜其他' => '90',
			'平其他' => '99',
			'0:0' => '0',
			'0:1' => '1',
			'0:2' => '2',
			'0:3' => '3',
			'0:4' => '4',
			'1:0' => '10',
			'1:1' => '11',
			'1:2' => '12',
			'1:3' => '13',
			'1:4' => '14',
			'2:0' => '20',
			'2:1' => '21',
			'2:2' => '22',
			'2:3' => '23',
			'2:4' => '24',
			'3:0' => '30',
			'3:1' => '31',
			'3:2' => '32',
			'3:3' => '33',
			'4:0' => '40',
			'4:1' => '41',
			'4:2' => '42',
   	);
   	
   	$config['bqc_result_dyj'] = array(
   			'胜-胜' => '33',
			'胜-平' => '31',
			'胜-负' => '30',
			'平-胜' => '13',
			'平-平' => '11',
			'平-负' => '10',
			'负-胜' => '3',
			'负-平' => '1',
			'负-负' => '0',
   	);