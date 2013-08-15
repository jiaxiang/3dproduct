<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * $Id: database.php 168 2009-12-21 02:04:12Z hjy $
 * $Author: hjy $
 * $Revision: 168 $
 */

/**
 * @package  Database
 *
 * Database connection settings, defined as arrays, or "groups". If no group
 * name is used when loading the database library, the group named "default"
 * will be used.
 *
 * Each group can be connected to independently, and multiple groups can be
 * connected at once.
 *
 * Group Options:
 *  benchmark     - Enable or disable database benchmarking
 *  persistent    - Enable or disable a persistent connection
 *  connection    - Array of connection specific parameters; alternatively,
 *                  you can use a DSN though it is not as fast and certain
 *                  characters could create problems (like an '@' character
 *                  in a password):
 *                  'connection'    => 'mysql://dbuser:secret@localhost/kohana'
 *  character_set - Database character set
 *  table_prefix  - Database table prefix
 *  object        - Enable or disable object results
 *  cache         - Enable or disable query caching
 *	escape        - Enable automatic query builder escaping
 */
if (gethostname() == 'www.jingbo365.com') {
	$config['default'] = array(
			'benchmark'     => TRUE,
			'persistent'    => FALSE,
			'connection'    => array
			(
					'type'     => 'mysqli',
					'user'     => 'root',
					'pass'     => 'jbDB365#',
					'host'     => '127.0.0.1',
					'port'     => '3306',
					'socket'   => FALSE,
					'database' => '3dproducts'
			),
			'character_set' => 'utf8',
			'table_prefix'  => '',
			'object'        => TRUE,
			'cache'         => FALSE,
			'escape'        => TRUE
	);

	$config['read_db'] = array(
			'benchmark'     => TRUE,
			'persistent'    => FALSE,
			'connection'    => array
			(
					'type'     => 'mysqli',
					'user'     => 'root',
					'pass'     => 'jbDB365#',
					'host'     => '127.0.0.1',
					'port'     => '3306',
					'socket'   => FALSE,
					'database' => '3dproducts'
			),
			'character_set' => 'utf8',
			'table_prefix'  => '',
			'object'        => TRUE,
			'cache'         => FALSE,
			'escape'        => TRUE
	);
	$config['db2'] = array(
			'benchmark'     => TRUE,
			'persistent'    => FALSE,
			'connection'    => array
			(
					'type'     => 'mysqli',
					'user'     => 'root',
					'pass'     => 'jbDB365#',
					'host'     => '127.0.0.1',
					'port'     => '3306',
					'socket'   => FALSE,
					'database' => '3dproducts'
			),
			'character_set' => 'utf8',
			'table_prefix'  => '',
			'object'        => TRUE,
			'cache'         => FALSE,
			'escape'        => TRUE
	);
}
else {
	$config['default'] = array(
	        'benchmark'     => TRUE,
	        'persistent'    => FALSE,
	        'connection'    => array
	        (
	            'type'     => 'mysqli',
				'user'     => 'root',
				'pass'     => '111111',
				'host'     => '127.0.0.1',
	            'port'     => '3306',
	            'socket'   => FALSE,
	            'database' => '3dproducts'
	        ),
	        'character_set' => 'utf8',
	        'table_prefix'  => '',
	        'object'        => TRUE,
	        'cache'         => FALSE,
	        'escape'        => TRUE
	    );

	$config['read_db'] = array(
	        'benchmark'     => TRUE,
	        'persistent'    => FALSE,
	        'connection'    => array
	        (
	            'type'     => 'mysqli',
				'user'     => 'root',
				'pass'     => '111111',
				'host'     => '127.0.0.1',
	            'port'     => '3306',
	            'socket'   => FALSE,
	            'database' => '3dproducts'
	        ),
	        'character_set' => 'utf8',
	        'table_prefix'  => '',
	        'object'        => TRUE,
	        'cache'         => FALSE,
	        'escape'        => TRUE
	    );
	$config['db2'] = array(
			'benchmark'     => TRUE,
			'persistent'    => FALSE,
			'connection'    => array
			(
				'type'     => 'mysqli',
				'user'     => 'root',
				'pass'     => '111111',
				'host'     => '127.0.0.1',
	            'port'     => '3306',
	            'socket'   => FALSE,
	            'database' => '3dproducts'
			),
			'character_set' => 'utf8',
			'table_prefix'  => '',
			'object'        => TRUE,
			'cache'         => FALSE,
			'escape'        => TRUE
	);
}