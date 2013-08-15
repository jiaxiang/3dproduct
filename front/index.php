<?php
/**
 * This file acts as the "front controller" to your application. You can
 * configure your application, modules, and system directories here.
 * PHP error_reporting level may also be changed.
 *
 * @see http://kohanaphp.com
 */

/**
 * Define the website environment status. When this flag is set to TRUE, some
 * module demonstration controllers will result in 404 errors. For more information
 * about this option, read the documentation about deploying Kohana.
 *
 * @see http://docs.kohanaphp.com/installation/deployment
 */
define('IN_PRODUCTION', FALSE);



if(IN_PRODUCTION==FALSE){
//    // only for development under windows workstation
//    $project_root = '';
//    $is_win = strtoupper(substr(PHP_OS,0,3))=='WIN';
//    $is_win && $project_root .= 'D:';
//    $project_root .= '/data0/apps/opococ2mod';
    $project_root = dirname(dirname(__FILE__));

    function f($var, $fnm = 'f', $die = '')
    {
    	switch (TRUE) {
    		case is_bool($var):
    			$var = $var ? 'TRUE' : 'FALSE';
    			break;
    		case is_null($var):
    			$var = 'NULL';
    			break;
    	}
    	file_put_contents($fnm.'.txt', var_export($var, TRUE));

    	if ($die) {
    		die();
    	}
    }

    function d($var, $die = TRUE)
    {
    	switch (TRUE) {
    		case is_bool($var):
    			$var = $var ? 'TRUE' : 'FALSE';
    			break;
    		case is_null($var):
    			$var = 'NULL';
    			break;
    	}

    	echo '<pre>';
    	echo print_r($var, TRUE);
    	echo '</pre>';

    	if ($die === TRUE) {
    		die();
    	}
    }

}else{
    /**
     * project root path
     */
    $project_root = '/dule';
}

function microtime_float()
{
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}

/* define Project Root path PROJECT_ROOT */
define('PROJECT_ROOT', str_replace('\\', '/', realpath($project_root)).'/');

/**
 * Website application directory. This directory should contain your application
 * configuration, controllers, models, views, and other resources.
 *
 * This path can be absolute or relative to this file.
 */
$kohana_application = 'application';

/**
 * Kohana modules directory. This directory should contain all the modules used
 * by your application. Modules are enabled and disabled by the application
 * configuration file.
 *
 * This path can be absolute or relative to this file.
 */
$kohana_modules = PROJECT_ROOT.'modules';

/**
 * Kohana system directory. This directory should contain the core/ directory,
 * and the resources you included in your download of Kohana.
 *
 * This path can be absolute or relative to this file.
 */
$kohana_system = PROJECT_ROOT.'system';

/** zhu add
 * Website attachment directory. This directory should contain your uploaded
 * images, files, and other resources.
 *
 * This path can be absolute or relative to this file.
 */
$kohana_attachment = PROJECT_ROOT.'attachment';

/** zhu add
 * Website cache directory. This directory should contain your theme template
 * files cache.
 *
 * This path can be absolute or relative to this file.
 */
$kohana_cache = $kohana_application.'/cache';

/**
 * Test to make sure that Kohana is running on PHP 5.2 or newer. Once you are
 * sure that your environment is compatible with Kohana, you can comment this
 * line out. When running an application on a new server, uncomment this line
 * to check the PHP version quickly.
 */
version_compare(PHP_VERSION, '5.2', '<') and exit('Kohana requires PHP 5.2 or newer.');

/**
 * Set the error reporting level. Unless you have a special need, E_ALL is a
 * good level for error reporting.
 */
error_reporting(E_ALL & ~E_STRICT);
//error_reporting(0);

/**
 * Turning off display_errors will effectively disable Kohana error display
 * and logging. You can turn off Kohana errors in application/config/config.php
 */
ini_set('display_errors', TRUE);
//ini_set('display_errors', FALSE);

/**
 * If you rename all of your .php files to a different extension, set the new
 * extension here. This option can left to .php, even if this file has a
 * different extension.
 */
define('EXT', '.php');

//
// DO NOT EDIT BELOW THIS LINE, UNLESS YOU FULLY UNDERSTAND THE IMPLICATIONS.
// ----------------------------------------------------------------------------
// $Id: index.php 3915 2009-01-20 20:52:20Z zombor $
//

$kohana_pathinfo = pathinfo(__FILE__);
// Define the front controller name and docroot
define('DOCROOT', $kohana_pathinfo['dirname'].DIRECTORY_SEPARATOR);
define('UPDOCROOT', dirname($kohana_pathinfo['dirname'].DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR);
define('KOHANA',  $kohana_pathinfo['basename']);

// If the front controller is a symlink, change to the real docroot
is_link(KOHANA) and chdir(dirname(realpath(__FILE__)));

// If kohana folders are relative paths, make them absolute.
$kohana_application = file_exists($kohana_application) ? $kohana_application : DOCROOT.$kohana_application;
$kohana_modules = file_exists($kohana_modules) ? $kohana_modules : DOCROOT.$kohana_modules;
$kohana_system = file_exists($kohana_system) ? $kohana_system : DOCROOT.$kohana_system;
$kohana_attachment = file_exists($kohana_attachment) ? $kohana_attachment : DOCROOT.$kohana_attachment;
$kohana_cache = file_exists($kohana_cache) ? $kohana_cache : DOCROOT.$kohana_cache;

// Define application and system paths
define('APPPATH', str_replace('\\', '/', realpath($kohana_application)).'/');
define('MODPATH', str_replace('\\', '/', realpath($kohana_modules)).'/');
define('SYSPATH', str_replace('\\', '/', realpath($kohana_system)).'/');
define('ATTPATH', str_replace('\\', '/', realpath($kohana_attachment)).'/');
define('CACHEPATH', str_replace('\\', '/', realpath($kohana_cache)).'/');

//自定义模板目录
define('APPTPL', str_replace('\\', '/', DOCROOT).'tpl_shop/');
define('WEBROOT',__FILE__ ? getdirname(__FILE__).'/' : './');

/**
*取得相对路径
*
*系统根目录相对路径
*/
function getdirname($path){
	if(strpos($path,'\\')!==false){
		return substr($path,0,strrpos($path,'\\'));
	}elseif(strpos($path,'/')!==false){
		return substr($path,0,strrpos($path,'/'));
	}else{
		return '/';
	}
}

function dump($vars, $label = '', $return = false) {
	if(ini_get('html_errors')) {
		$content = "<pre>\n";
		if($label != '') {
			$content .= "<strong>{$label} :</strong>\n";
		}
		$content .= htmlspecialchars(print_r($vars, true));
		$content .= "\n<pre>\n";
	}else {
		$content = $label . " :\n" . print_r($vars, true);
	}
	if($return) {
		return $content;
	}else {
		echo $content;
		return null;
	}
}

// Clean up
unset($kohana_application, $kohana_modules, $kohana_system, $kohana_attachment, $kohana_cache);

// Initialize Kohana
//TODO 需要优化
//require MODPATH.'payment/lib/motopay/chinabank.MotoClient.php';
require SYSPATH.'core/Bootstrap'.EXT;


