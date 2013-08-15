<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * 数字彩相关config
 */

$config['bonusinfo'] = array('dlt' => array('1'=>array('name'=>'一等奖','default'=>''),
                                            '2'=>array('name'=>'一等追加','default'=>''),
                                            '3'=>array('name'=>'二等奖','default'=>''),
                                            '4'=>array('name'=>'二等追加','default'=>''),
                                            '5'=>array('name'=>'三等奖','default'=>''),
                                            '6'=>array('name'=>'三等追加','default'=>''),
                                            '7'=>array('name'=>'四等奖','default'=>3000),
                                            '8'=>array('name'=>'四等追加','default'=>1500),
                                            '9'=>array('name'=>'五等奖','default'=>600),
                                            '10'=>array('name'=>'五等追加','default'=>300),
                                            '11'=>array('name'=>'六等奖','default'=>100),
                                            '12'=>array('name'=>'六等追加','default'=>50),
                                            '13'=>array('name'=>'七等奖','default'=>10),
                                            '14'=>array('name'=>'七等追加','default'=>5),
                                            '15'=>array('name'=>'八等奖','default'=>5),
                                            '16'=>array('name'=>'生肖乐','default'=>60)
                                             ),
                                             'plw' => array('1'=>array('name'=>'一等奖','default'=>'100000'),
                                             
                                             ),
                                             'qxc' => array('1'=>array('name'=>'一等奖','default'=>''),
												'2'=>array('name'=>'二等奖','default'=>''),
												'3'=>array('name'=>'三等奖','default'=>'1800'),
												'4'=>array('name'=>'四等奖','default'=>'300'),
												'5'=>array('name'=>'五等奖','default'=>'20'),
												'6'=>array('name'=>'六等奖','default'=>'5'),
                                             ),
											'pls' => array('1'=>array('name'=>'直选奖金','default'=>'1000'),
												'2'=>array('name'=>'组三奖金','default'=>'320'),
												'3'=>array('name'=>'组六奖金','default'=>'160'),
                                             ),

                           );
$config['kjxml'] = array('dlt'=>'http://trade.cpdyj.com/staticdata/guoguan/clt/expect.xml',
'plw'=>'http://trade.cpdyj.com/staticdata/guoguan/p5/expect.xml',
'qxc'=>'http://trade.cpdyj.com/staticdata/guoguan/qx/expect.xml',
'pls'=>'http://trade.cpdyj.com/staticdata/guoguan/p3/expect.xml',

);                           
                           
$config['cpstatinfo'] = array('0'=>'未出票',
                              '1'=>'出票中',
                              '2'=>'<font color="red">已出票</font>');


$config['restatinfo'] = array('0'=>'-',
                              '1'=>'<font color="red">已撤单</font>');

$config['isfullinfo']= array('0'=>'未满员',
                             '1'=>'理论满员',
                             '2'=>'<font color="red">满员</font>');


$config['jobstat']  = array('0'=>'未开始',
                            '1'=>'执行中',
                            '2'=>'执行完成',
                            '3'=>'执行失败');

$config['jobtype']  = array('1'=>'清算任务',
                            '2'=>'过关任务',
                            '3'=>'派奖任务');