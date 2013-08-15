<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * $Id: pagination.php 168 2009-12-21 02:04:12Z hjy $
 * $Author: hjy $
 * $Revision: 168 $
 */

/**
 * @package  Pagination
 *
 * Pagination configuration is defined in groups which allows you to easily switch
 * between different pagination settings for different website sections.
 * Note: all groups inherit and overwrite the default group.
 *
 * Group Options:
 *  directory      - Views folder in which your pagination style templates reside
 *  style          - Pagination style template (matches view filename)
 *  uri_segment    - URI segment (int or 'label') in which the current page number can be found
 *  query_string   - Alternative to uri_segment: query string key that contains the page number
 *  items_per_page - Number of items to display per page
 *  auto_hide      - Automatically hides pagination for single pages
 */
$config['default'] = array
    (
    'directory'      => 'pagination',
    'query_string'   => '',
    'auto_hide'      => FALSE,
    'uri_segment'    => 'page',
    'total_items'    => 1000,
    'query_string'   => 'page',
    'style'          => 'opococ',
);

$config['per_page'] = array
    (
	0   => 20,
	1   => 50,
	2   => 100,
	3   => 300,
);