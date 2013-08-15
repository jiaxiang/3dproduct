<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * $Id: tab.php 248 2010-01-19 02:44:34Z ll $
 * $Author: ll $
 * $Revision: 248 $
 */

$config['pagination'] = array
    (
	0   => 20,
	1   => 50,
	2   => 100,
	3   => 300,
    );
/***********************************************	library	   *****************************************/
$config['library_category_list_category'] = array
    (
	'orderby'   => array
	(
	    0   => array('id'=>'ASC'),
	    1   => array('id'=>'DESC'),
	    2   => array('name'=>'ASC'),
	    3   => array('name'=>'DESC'),
	    4   => array('name_url'=>'ASC'),
	    5   => array('name_url'=>'DESC'),
	    6   => array('parent_id'=>'ASC'),
	    7   => array('parent_id'=>'DESC'),
	    8   => array('position'=>'ASC'),
	    9   => array('position'=>'DESC'),
	    10   => array('date_add'=>'ASC'),
	    11   => array('date_add'=>'DESC'),
	    12   => array('active'=>'ASC'),
	    13   => array('active'=>'DESC'),
	    14   => array('virtual'=>'ASC'),
	    15   => array('virtual'=>'DESC'),
	),

    );

$config['library_attribute_list_group'] = array
    (
	'orderby'   => array
	(
	    0   => array('id'=>'ASC'),
	    1   => array('id'=>'DESC'),
	    2   => array('name'=>'ASC'),
	    3   => array('name'=>'DESC'),
	    4   => array('refer'=>'ASC'),
	    5   => array('refer'=>'DESC'),
	    6   => array('active'=>'ASC'),
	    7   => array('active'=>'DESC'),
	),

    );
$config['library_feature_list_group'] = array
    (
	'orderby'   => array
	(
	    0   => array('id'=>'ASC'),
	    1   => array('id'=>'DESC'),
	    2   => array('name'=>'ASC'),
	    3   => array('name'=>'DESC'),
	    4   => array('active'=>'ASC'),
	    5   => array('active'=>'DESC'),
	),

    );
$config['library_supplier_list_supplier'] = array
    (
	'orderby'   => array
	(
	    0   => array('id'=>'ASC'),
	    1   => array('id'=>'DESC'),
	    2   => array('name'=>'ASC'),
	    3   => array('name'=>'DESC'),
	    4   => array('description'=>'ASC'),
	    5   => array('description'=>'DESC'),
	    6   => array('position'=>'ASC'),
	    7   => array('position'=>'DESC'),
	    8   => array('active'=>'ASC'),
	    9   => array('active'=>'DESC'),
	),

    );

$config['library_product_list_product'] = array
    (
	'orderby'   => array
	(
	    0   => array('id'=>'ASC'),
	    1   => array('id'=>'DESC'),
	    2   => array('name'=>'ASC'),
	    3   => array('name'=>'DESC'),
	    4   => array('name_url'=>'ASC'),
	    5   => array('name_url'=>'DESC'),
	    6   => array('SKU'=>'ASC'),
	    7   => array('SKU'=>'DESC'),
	    8   => array('category_default_id'=>'ASC'),
	    9   => array('category_default_id'=>'DESC'),
	    10   => array('on_sale'=>'ASC'),
	    11   => array('on_sale'=>'DESC'),
	    12   => array('price'=>'ASC'),
	    13   => array('price'=>'DESC'),
	    14   => array('active'=>'ASC'),
	    15   => array('active'=>'DESC'),
	),

    );
// 小分类
$config['library_smallsort_list_smallsort'] = array
    (
	'orderby'   => array
	(
	    0   => array('id'=>'ASC'),
	    1   => array('id'=>'DESC'),
	    2   => array('name'=>'ASC'),
	    3   => array('name'=>'DESC'),
	    4   => array('intro'=>'ASC'),
	    5   => array('intro'=>'DESC'),
	    6   => array('total'=>'ASC'),
	    7   => array('total'=>'DESC'),
	    8   => array('day'=>'ASC'),
	    9   => array('day'=>'DESC'),
	    10   => array('date_add'=>'ASC'),
	    11   => array('date_add'=>'DESC'),
	    12   => array('date_upd'=>'ASC'),
	    13   => array('date_upd'=>'DESC'),
	),
    );

$config['library_currency_list_currency'] = array
    (
	'orderby'   => array
	(
	    0   => array('id'=>'ASC'),
	    1   => array('id'=>'DESC'),
	    2   => array('name'=>'ASC'),
	    3   => array('name'=>'DESC'),
	    4   => array('sign'=>'ASC'),
	    5   => array('sign'=>'DESC'),
	    6   => array('format'=>'ASC'),
	    7   => array('format'=>'DESC'),
	    8   => array('decimals'=>'ASC'),
	    9   => array('decimals'=>'DESC'),
	    10   => array('conversion_rate'=>'ASC'),
	    11   => array('conversion_rate'=>'DESC'),
	    12   => array('default'=>'ASC'),
	    13   => array('default'=>'DESC'),
	    14   => array('active'=>'ASC'),
	    15   => array('active'=>'DESC'),
	),

    );
$config['library_comment_list_comment'] = array
    (
	'orderby'   => array
	(
	    0   => array('id'=>'ASC'),
	    1   => array('id'=>'DESC'),
	    2   => array('library_comment_type_id'=>'ASC'),
	    3   => array('library_comment_type_id'=>'DESC'),
	    4   => array('grade'=>'ASC'),
	    5   => array('grade'=>'DESC'),
	    6   => array('content'=>'ASC'),
	    7   => array('content'=>'DESC'),
	    8   => array('count'=>'ASC'),
	    9   => array('count'=>'DESC'),
	    10   => array('date_add'=>'ASC'),
	    11   => array('date_add'=>'DESC'),
	    12   => array('date_upd'=>'ASC'),
	    13   => array('date_upd'=>'DESC'),
	    14   => array('active'=>'ASC'),
	    15   => array('active'=>'DESC'),
	),

    );
$config['library_product_description_list_description'] = array
    (
	'orderby'   => array
	(
	    0   => array('id'=>'ASC'),
	    1   => array('id'=>'DESC'),
	    2   => array('product_id'=>'ASC'),
	    3   => array('product_id'=>'DESC'),
	    4   => array('group'=>'ASC'),
	    5   => array('grade'=>'DESC'),
	    6   => array('content'=>'ASC'),
	    7   => array('content'=>'DESC'),
	    8   => array('position'=>'ASC'),
	    9   => array('position'=>'DESC'),
	    10   => array('date_add'=>'ASC'),
	    11   => array('date_add'=>'DESC'),
	    12   => array('date_upd'=>'ASC'),
	    13   => array('date_upd'=>'DESC'),
	    14   => array('active'=>'ASC'),
	    15   => array('active'=>'DESC'),
	),

    );
$config['library_country_list_country'] = array
    (
	'orderby'   => array
	(
	    0   => array('id'=>'ASC'),
	    1   => array('id'=>'DESC'),
	    2   => array('name'=>'ASC'),
	    3   => array('name'=>'DESC'),
	    4   => array('iso_code'=>'ASC'),
	    5   => array('iso_code'=>'DESC'),
	    6   => array('contains_states'=>'ASC'),
	    7   => array('contains_states'=>'DESC'),
	    8   => array('position'=>'ASC'),
	    9   => array('position'=>'DESC'),
	    10  => array('active'=>'ASC'),
	    11  => array('active'=>'DESC'),
	),
    );

/***********************************************	product	***********************************************/

$config['product_category_list_category'] = array
    (
	'orderby'   => array
	(
	    0   => array('category_id'=>'ASC'),
	    1   => array('category_id'=>'DESC'),
	    2   => array('site_id'=>'ASC'),
	    3   => array('site_id'=>'DESC'),
	    4   => array('name'=>'ASC'),
	    5   => array('name'=>'DESC'),
	    6   => array('name_url'=>'ASC'),
	    7   => array('name_url'=>'DESC'),
	    8   => array('parent_id'=>'ASC'),
	    9   => array('parent_id'=>'DESC'),
	    10   => array('position'=>'ASC'),
	    11   => array('position'=>'DESC'),
	    12   => array('date_add'=>'ASC'),
	    13   => array('date_add'=>'DESC'),
	    14   => array('virtual'=>'ASC'),
	    15   => array('virtual'=>'DESC'),
	),

    );

$config['product_attribute_list_group'] = array
    (
	'orderby'   => array
	(
	    0   => array('attribute_group_id'=>'ASC'),
	    1   => array('attribute_group_id'=>'DESC'),
	    2   => array('site_id'=>'ASC'),
	    3   => array('site_id'=>'DESC'),
	    4   => array('name'=>'ASC'),
	    5   => array('name'=>'DESC'),
	    6   => array('refer'=>'ASC'),
	    7   => array('refer'=>'DESC'),
	    8   => array('active'=>'ASC'),
	    9   => array('active'=>'DESC'),
	),
    );

$config['product_feature_list_group'] = array
    (
	'orderby'   => array
	(
	    0   => array('feature_group_id'=>'ASC'),
	    1   => array('feature_group_id'=>'DESC'),
	    2   => array('site_id'=>'ASC'),
	    3   => array('site_id'=>'DESC'),
	    4   => array('name'=>'ASC'),
	    5   => array('name'=>'DESC'),
	    6   => array('active'=>'ASC'),
	    7   => array('active'=>'DESC'),
	),
    );

$config['product_supplier_list_supplier'] = array
    (
	'orderby'   => array
	(
	    0   => array('id'=>'ASC'),
	    1   => array('id'=>'DESC'),
	    2   => array('site_id'=>'ASC'),
	    3   => array('site_id'=>'DESC'),
	    4   => array('name'=>'ASC'),
	    5   => array('name'=>'DESC'),
	    6   => array('description'=>'ASC'),
	    7   => array('description'=>'DESC'),
	    8   => array('position'=>'ASC'),
	    9   => array('position'=>'DESC'),
	    10   => array('active'=>'ASC'),
	    11   => array('active'=>'DESC'),
	),

    );

$config['product_product_list_product'] = array
    (
	'orderby'   => array
	(
	    0   => array('product_id'=>'ASC'),
	    1   => array('product_id'=>'DESC'),
	    2   => array('site_id'=>'ASC'),
	    3   => array('site_id'=>'DESC'),
	    4   => array('name'=>'ASC'),
	    5   => array('name'=>'DESC'),
	    6   => array('name_url'=>'ASC'),
	    7   => array('name_url'=>'DESC'),
	    8   => array('SKU'=>'ASC'),
	    9   => array('SKU'=>'DESC'),
	    10   => array('category_default_id'=>'ASC'),
	    11   => array('category_default_id'=>'DESC'),
	    12   => array('on_sale'=>'ASC'),
	    13   => array('on_sale'=>'DESC'),
	    14   => array('price'=>'ASC'),
	    15   => array('price'=>'DESC'),
	    16   => array('active'=>'ASC'),
	    17   => array('active'=>'DESC'),
	),
    );
$config['product_country_list_country'] = array
    (
	'orderby'   => array
	(
	    0   => array('country_id'=>'ASC'),
	    1   => array('country_id'=>'DESC'),
	    2   => array('site_id'=>'ASC'),
	    3   => array('site_id'=>'DESC'),
	    4   => array('name'=>'ASC'),
	    5   => array('name'=>'DESC'),
	    6   => array('iso_code'=>'ASC'),
	    7   => array('iso_code'=>'DESC'),
	    8   => array('contains_states'=>'ASC'),
	    9   => array('contains_states'=>'DESC'),
	    10   => array('position'=>'ASC'),
	    11   => array('position'=>'DESC'),

	),
    );

$config['product_comment_list_comment'] = array
    (
	'orderby'   => array
	(
	    0   => array('id'=>'ASC'),
	    1   => array('id'=>'DESC'),
	    2   => array('site_id'=>'ASC'),
	    3   => array('site_id'=>'DESC'),
	    4   => array('product_comment_id'=>'ASC'),
	    5   => array('product_comment_id'=>'DESC'),
	    6   => array('product_id'=>'ASC'),
	    7   => array('product_id'=>'DESC'),
	    8   => array('title'=>'ASC'),
	    9   => array('title'=>'DESC'),
	    10   => array('name'=>'ASC'),
	    11   => array('name'=>'DESC'),
	    12   => array('email'=>'ASC'),
	    13   => array('email'=>'DESC'),
	    14   => array('grade'=>'ASC'),
	    15   => array('grade'=>'DESC'),
	    16   => array('content'=>'ASC'),
	    17   => array('content'=>'DESC'),		
	    18   => array('date_add'=>'ASC'),
	    19   => array('date_add'=>'DESC'),
	    20   => array('date_upd'=>'ASC'),
	    21   => array('date_upd'=>'DESC'),
	    22   => array('ip_add'=>'ASC'),
	    23   => array('ip_add'=>'DESC'),
	    24   => array('active'=>'ASC'),
	    25   => array('active'=>'DESC'),
	),

    );
$config['library_carrier_list_carrier'] = array
    (
	'orderby'   => array
	(
	    0   => array('id'=>'ASC'),
	    1   => array('id'=>'DESC'),
	    2   => array('name'=>'ASC'),
	    3   => array('name'=>'DESC'),
	    4   => array('url'=>'ASC'),
	    5   => array('url'=>'DESC'),
	    6   => array('delay'=>'ASC'),
	    7   => array('delay'=>'DESC'),
	    8   => array('type'=>'ASC'),
	    9   => array('type'=>'DESC'),
	    10  => array('position'=>'ASC'),
	    11  => array('position'=>'DESC'),
	    12  => array('active'=>'ASC'),
	    13  => array('active'=>'DESC'),
	),
    );
$config['order_list_order'] = array
    (
	'orderby'   => array
	(
	    0   => array('date_add'=>'DESC'),
	    1   => array('date_add'=>'ASC'),
	    2   => array('site_id'=>'ASC'),
	    3   => array('site_id'=>'DESC'),
	    4   => array('order_num'=>'ASC'),
	    5   => array('order_num'=>'DESC'),
	    6   => array('email'=>'ASC'),
	    7   => array('email'=>'DESC'),
	    8   => array('order_status'=>'ASC'),
	    9   => array('order_status'=>'DESC'),
	    10   => array('id'=>'ASC'),
	    11   => array('id'=>'DESC'),
	    12   => array('date_pay'=>'ASC'),
	    13   => array('date_pay'=>'DESC'),
	    14   => array('total_real'=>'ASC'),
	    15   => array('total_real'=>'DESC'),
	    16   => array('ip_add'=>'ASC'),
	    17   => array('ip_add'=>'DESC'),
	),
    );

$config['library_category_list_product'] = array
    (
	'orderby'   => array
	(
	    0   => array('id'=>'ASC'),
	    1   => array('id'=>'DESC'),
	    2   => array('name'=>'ASC'),
	    3   => array('name'=>'DESC'),
	    4   => array('name_url'=>'ASC'),
	    5   => array('name_url'=>'DESC'),
	    6   => array('SKU'=>'ASC'),
	    7   => array('SKU'=>'DESC'),
	    8   => array('category_default_id'=>'ASC'),
	    9   => array('category_default_id'=>'DESC'),
	    10   => array('on_sale'=>'ASC'),
	    11   => array('on_sale'=>'DESC'),
	    12   => array('price'=>'ASC'),
	    13   => array('price'=>'DESC'),
	    14   => array('active'=>'ASC'),
	    15   => array('active'=>'DESC'),
	),
    );

$config['library_carrier_list_country'] = array
    (
	'orderby'   => array
	(
	    0   => array('id'=>'ASC'),
	    1   => array('id'=>'DESC'),
	    2   => array('name'=>'ASC'),
	    3   => array('name'=>'DESC'),
	    4   => array('iso_code'=>'ASC'),
	    5   => array('iso_code'=>'DESC'),
	    6   => array('contains_states'=>'ASC'),
	    7   => array('contains_states'=>'DESC'),
	    8   => array('position'=>'ASC'),
	    9   => array('position'=>'DESC'),
	    10  => array('active'=>'ASC'),
	    11  => array('active'=>'DESC'),
	),
    );
$config['product_carrier_list_carrier'] = array
    (
	'orderby'   => array
	(
	    0   => array('id'=>'ASC'),
	    1   => array('id'=>'DESC'),
	    2   => array('site_id'=>'ASC'),
	    3   => array('site_id'=>'DESC'),
	    4   => array('name'=>'ASC'),
	    5   => array('name'=>'DESC'),
	    6   => array('url'=>'ASC'),
	    7   => array('url'=>'DESC'),
	    8   => array('delay'=>'ASC'),
	    9   => array('delay'=>'DESC'),
	    10   => array('type'=>'ASC'),
	    11   => array('type'=>'DESC'),
	    12   => array('position'=>'ASC'),
	    13   => array('position'=>'DESC'),
	),
    );

$config['product_smallsort_list_smallsort'] = array
    (
	'orderby'   => array
	(
	    0   => array('small_sort_id'=>'ASC'),
	    1   => array('small_sort_id'=>'DESC'),
	    2   => array('site_id'=>'ASC'),
	    3   => array('site_id'=>'DESC'),
	    4   => array('name'=>'ASC'),
	    5   => array('name'=>'DESC'),
	    6   => array('intro'=>'ASC'),
	    7   => array('intro'=>'DESC'),
	    8   => array('total'=>'ASC'),
	    9   => array('total'=>'DESC'),
	    10   => array('day'=>'ASC'),
	    11   => array('day'=>'DESC'),
	    12   => array('date_add'=>'ASC'),
	    13   => array('date_add'=>'DESC'),
	    14   => array('date_upd'=>'ASC'),
	    15   => array('date_upd'=>'DESC'),
	),
    );

$config['product_currency_list_currency'] = array
    (
	'orderby'   => array
	(
	    0   => array('id'=>'ASC'),
	    1   => array('id'=>'DESC'),
	    2   => array('site_id'=>'ASC'),
	    3   => array('site_id'=>'DESC'),
	    4   => array('currency_id'=>'ASC'),
	    5   => array('currency_id'=>'DESC'),
	    6   => array('name'=>'ASC'),
	    7   => array('name'=>'DESC'),
	    8   => array('sign'=>'ASC'),
	    9   => array('sign'=>'DESC'),
	    10   => array('format'=>'ASC'),
	    11   => array('format'=>'DESC'),
	    12   => array('decimals'=>'ASC'),
	    13   => array('decimals'=>'DESC'),
	    14   => array('conversion_rate'=>'ASC'),
	    15   => array('conversion_rate'=>'DESC'),
	    16   => array('default'=>'ASC'),
	    17   => array('default'=>'DESC'),

	),

    );


/***********************************************	promotion	***********************************************/
$config['promotion_promotion_list_promotion'] = array
    (
	'orderby'   => array
	(
	    0   => array('id'=>'ASC'),
	    1   => array('id'=>'DESC'),
	    2   => array('site_id'=>'ASC'),
	    3   => array('site_id'=>'DESC'),
	    4   => array('description_2'=>'ASC'),
	    5   => array('description_2'=>'DESC'),
	    6   => array('time_begin'=>'ASC'),
	    7   => array('time_begin'=>'DESC'),
	    8   => array('time_end'=>'ASC'),
	    9   => array('time_end'=>'DESC'),
	),
    );
/*****************************   user  ****************************************/
$config['user_list_user'] = array
    (
	'orderby'   => array
	(
	    0   => array('id'=>'ASC'),
	    1   => array('id'=>'DESC'),
	    2   => array('site_id'=>'ASC'),
	    3   => array('site_id'=>'DESC'),
	    4   => array('email'=>'ASC'),
	    5   => array('email'=>'DESC'),
	    6   => array('firstname'=>'ASC'),
	    7   => array('firstname'=>'DESC'),
	    8   => array('lastname'=>'ASC'),
	    9   => array('lastname'=>'DESC'),
	    10   => array('password'=>'ASC'),
	    11   => array('password'=>'DESC'),
	    12   => array('active'=>'ASC'),
	    13   => array('active'=>'DESC'),
	),
    );
/****************  address  ***********************/

$config['address_list_address'] = array
    (
	'orderby'   => array
	(
	    0   => array('id'=>'ASC'),
	    1   => array('id'=>'DESC'),
	    2   => array('site_id'=>'ASC'),
	    3   => array('site_id'=>'DESC'),
	    4   => array('user_id'=>'ASC'),
	    5   => array('user_id'=>'DESC'),
	    6   => array('firstname'=>'ASC'),
	    7   => array('firstname'=>'DESC'),
	    8   => array('lastname'=>'ASC'),
	    9   => array('lastname'=>'DESC'),
	    10   => array('country'=>'ASC'),
	    11   => array('country'=>'DESC'),
	    12   => array('state'=>'ASC'),
	    13   => array('state'=>'DESC'),
	    14   => array('city'=>'ASC'),
	    15   => array('city'=>'DESC'),
	    16   => array('address'=>'ASC'),
	    17   => array('address'=>'DESC'),
	    18   => array('zip'=>'ASC'),
	    19   => array('zip'=>'DESC'),
	    20   => array('phone'=>'ASC'),
	    21   => array('phone'=>'DESC'),
	    22   => array('phone_mobile'=>'ASC'),
	    23   => array('phone_mobile'=>'DESC'),
	    24   => array('other'=>'ASC'),
	    25   => array('other'=>'DESC'),
	    26   => array('date_add'=>'ASC'),
	    27   => array('date_add'=>'DESC'),
	    28   => array('date_upd'=>'ASC'),
	    29   => array('date_upd'=>'DESC'),
	    30   => array('ip_add'=>'ASC'),
	    31   => array('ip_add'=>'DESC'),
	    32   => array('active'=>'ASC'),
	    33   => array('active'=>'DESC'),
	),
    );

/****************  discount  ***********************/
$config['promotion_discount_list_discount'] = array
    (
	'orderby'   => array
	(
	    0   => array('id'=>'ASC'),
	    1   => array('id'=>'DESC'),
	    2   => array('site_id'=>'ASC'),
	    3   => array('site_id'=>'DESC'),
	    4   => array('discount_type_id'=>'ASC'),
	    5   => array('discount_type_id'=>'DESC'),
	    6   => array('discount_number'=>'ASC'),
	    7   => array('discount_number'=>'DESC'),
	    8   => array('description'=>'ASC'),
	    9   => array('description'=>'DESC'),
	    10   => array('discount_type'=>'ASC'),
	    11   => array('discount_type'=>'DESC'),
	    12   => array('discount_value'=>'ASC'),
	    13   => array('discount_value'=>'DESC'),
	    14   => array('allow_nums'=>'ASC'),
	    15   => array('allow_nums'=>'DESC'),
	    16   => array('used_nums'=>'ASC'),
	    17   => array('used_nums'=>'DESC'),
	    18   => array('time_begin'=>'ASC'),
	    19   => array('time_begin'=>'DESC'),
	    20   => array('time_end'=>'ASC'),
	    21   => array('time_end'=>'DESC'),
	    22   => array('int_1'=>'ASC'),
	    23   => array('int_1'=>'DESC'),
	    24   => array('int_2'=>'ASC'),
	    25   => array('int_2'=>'DESC'),
	),
    );

/*************   contactus  ************/

$config['contact_list_contactus'] = array
    (
	'orderby'   => array
	(
	    0   => array('id'=>'ASC'),
	    1   => array('id'=>'DESC'),
	    2   => array('site_id'=>'ASC'),
	    3   => array('site_id'=>'DESC'),
	    4   => array('is_receive'=>'ASC'),
	    5   => array('is_receive'=>'DESC'),
	    6   => array('email'=>'ASC'),
	    7   => array('email'=>'DESC'),
	    8   => array('name'=>'ASC'),
	    9   => array('name'=>'DESC'),
	    10   => array('message'=>'ASC'),
	    11   => array('message'=>'DESC'),
	    12   => array('date_add'=>'ASC'),
	    13   => array('date_add'=>'DESC'),
	    14   => array('active'=>'ASC'),
	    15   => array('active'=>'DESC'),
	),
    );
$config['mbaobao_list_bags_stock'] = array
    (
	'orderby'   => array
	(
	    0   => array('id'=>'ASC'),
	    1   => array('id'=>'DESC'),
	    2   => array('SKU'=>'ASC'),
	    3   => array('SKU'=>'DESC'),
	    4   => array('stock'=>'ASC'),
	    5   => array('stock'=>'DESC'),
	),
    );
