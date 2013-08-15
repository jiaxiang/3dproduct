<?php defined('SYSPATH') OR die('No direct access allowed.');
/* 附件存储相关 */
/*
    const STORE_TYPE_ENTITY = 1; // 实体存储（存储在表字段内）
    const STORE_TYPE_FS = 2; // 存储在FS系统内（本地磁盘或者NFS）
    const STORE_TYPE_TT = 3; // 存储在网络KVDB数据库里（如TT,MemcacheDB等）
    const STORE_TYPE_MEM = 4; // 存储在网络兼容MEMCACHE协议的KVDB数据库里（如TT,MemcacheDB等,使用MEMCACHE协议）
    const STORE_TYPE_WEBDAV = 5; // 存储在网络路径里（如WebDAV,SVN等）
    const STORE_TYPE_PHPRPC = 6; // 存储到网络的PHPRPC协议远程服务端
 */
$config['defaultType'] = 6;
$config['apiDefaultType'] = 3;
$config['local'] = array(
    'phprpcApiKey' => 'a23406d543be07c937182ca25f6cff5f',
);
$config['remote'] = array(
    'phprpcApiKey' => 'a23406d543be07c937182ca25f6cff5f',
);