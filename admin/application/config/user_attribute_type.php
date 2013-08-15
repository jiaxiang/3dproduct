<?php defined('SYSPATH') OR die('No direct access allowed.');
//类型配置
$config['attribute'] = array(
//输入项
    'input'=>array(
        'nolimit'       =>array(
                           'form'=>'text',
                           'name'=>'输入内容不限制',
                        ),
        'numeric'       =>array(
                           'form'=>'text',
                           'name'=>'仅限输入数字',
                        ),
        'string'        =>array(
                           'form'=>'text',
                           'name'=>'仅限输入字符',
                        ),
        'numeric_string'=>array(
                           'form'=>'text',
                           'name'=>'仅限输入数字和字符',
                        ),
    ),
//选择项
    'select'=>array(
        'single_s'    =>array(
                           'form'=>'select',
                           'name'=>'单选项(select)',
                        ),
        'single_r'    =>array(
                           'form'=>'radio',
                           'name'=>'单选项(radio)',
                        ),
        'multiple'  =>array(
                           'form'=>'checkbox',
                           'name'=>'多选项',
                        ),
    ),
//时间项
    'time'=>array(
        'yy_mm_dd'  =>array(
		        'form'      =>'text',
		        'item'      =>'datepicker',
		        'name'      =>'日期（年月日）',
        ),
    ),
);
$config['type_group'] = array(
    'input'=>'输入项',
    'select'=>'选择项',
    'time'=>'时间项',
);
//系统默认项
$config['system_default']=array(
//    'user'=>array(
//            'attribute_type'    =>'input.numeric_string',
//            'attribute_required'=>1,
//            'attribute_option'  =>'',
//            'attribute_show'    =>1,
//        ),
//    'sex'=>array(
//            'attribute_type'    =>'select.single_r',
//            'attribute_required'=>1,
//            'attribute_option'  =>'boy,girl',
//            'attribute_show'    =>1,
//        ),
//    'birthday'=>array(
//            'attribute_type'    =>'time.yy_mm_dd',
//            'attribute_required'=>1,
//            'attribute_option'  =>'',
//            'attribute_show'    =>1,
//        ),
//    'country'=>array(
//            'attribute_type'    =>'input.nolimit',
//            'attribute_required'=>1,
//            'attribute_option'  =>'',
//            'attribute_show'    =>1,
//        ),
//    'address'=>array(
//            'attribute_type'    =>'input.nolimit',
//            'attribute_required'=>1,
//            'attribute_option'  =>'',
//            'attribute_show'    =>1,
//        ),
//    'post code'=>array(
//            'attribute_type'    =>'input.numeric',
//            'attribute_required'=>1,
//            'attribute_option'  =>'',
//            'attribute_show'    =>1,
//        ),
//    'mobile phone'=>array(
//            'attribute_type'    =>'input.numeric',
//            'attribute_required'=>1,
//            'attribute_option'  =>'',
//            'attribute_show'    =>1,
//        ),
//    'fixed phone'=>array(
//            'attribute_type'    =>'input.numeric',
//            'attribute_required'=>1,
//            'attribute_option'  =>'',
//            'attribute_show'    =>1,
//        ),
);