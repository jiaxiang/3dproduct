<?php defined('SYSPATH') OR die('No direct access allowed.');
/* 远程调用相关 */
$config['local'] = array(
    'Attachment'=>array(
        'apiKey'=>'a23406d543be07c937182ca25f6cff5f',
    ),
);
$config['remote'] = array(
    'Attachment'=>array(
        'host'=>'http://store.ketai-cluster.com/phprpc/attachment',
        'apiKey'=>'a23406d543be07c937182ca25f6cff5f',
    ),
    'statking'=>array(
        'host'=>'http://172.16.0.7:8008/phprpc/statking_server',
        //'host'=>'http://admin.haifeng.com/sitestat/rpcserver',
        'api_key'=>'a23406d543be07c937182ca25f6cff5f',
    ),
);