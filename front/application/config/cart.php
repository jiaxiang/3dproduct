<?php defined('SYSPATH') OR die('No direct access allowed.');
$config['default']=array(
        'addType'=>array(
            'default'=>'increment',//增长方式
            'normalized',//归一
        ),
        'status'=>array(
            1=> '{$product_name} cannot be retailed!',
            2=> '{$product_name} cannot be retailed!',
        ),
    );

//$config['www_biz2checkout_com']=array(
//        'addType'=>array(
//	        'default'=>'increment',//增长方式
//	        'normalized',//归一
//        ),
//        'status'=>array(
//            1=> '{$product_name} ',
//            2=> '{$product_name} ',
//        ),
//    );