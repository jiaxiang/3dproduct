<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * @package  Core
 *
 * This path is relative to your index file. Absolute paths are also supported.
 */
//$config['directory'] = DOCROOT.'upload';
$config['directory'] = UPDOCROOT.'upload';

/**
 * Enable or disable directory creation.
 */
$config['create_directories'] = FALSE;

/**
 * Remove spaces from uploaded filenames.
 */
$config['remove_spaces'] = TRUE;
$config['file_max_size'] = 25 * 1024 * 1024;//25M
$config['pic_max_size'] = 2 * 1024 * 1024;//2M
$config['pic_file_ext'] = array('gif','png','jpg','jpeg');
$config['model_file_ext'] = array('stl');