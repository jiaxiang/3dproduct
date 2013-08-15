<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * @package  Cache:Memcache
 *
 * memcache server configuration.
 */
$config['default'] = array
    (
        'server'=> array(
            array
            (
            'host'   => '127.0.0.1',
            'port'   => 11211,
                'persistent' => FALSE
            ),
            array
            (
            'host'   => '127.0.0.1',
            'port'   => 11211,
                'persistent' => FALSE
            )
        ),
        'expire' => 10,
        'compression' => FALSE
    );


$config['tt'] = array
    (
        'server'=> array(
            array
            (
            'host'   => '127.0.0.1',
            'port'   => 11211,
                'persistent' => FALSE
            ),
            array
            (
            'host'   => '127.0.0.1',
            'port'   => 11211,
                'persistent' => FALSE
            )
        ),
        'expire' => 1000,
        'compression' => FALSE
    );

$config['session'] = array
    (
        'server'=> array(
            array
            (
            'host'   => '127.0.0.1',
            'port'   => 11211,
                'persistent' => FALSE
            ),
            array
            (
            'host'   => '127.0.0.1',
            'port'   => 11211,
                'persistent' => FALSE
            )
        ),
        'expire' => 1000,
        'compression' => FALSE
    );
