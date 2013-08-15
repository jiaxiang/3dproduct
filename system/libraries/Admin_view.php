<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * Loads and displays Kohana view files. Can also handle output of some binary
 * files, such as image, Javascript, and CSS files.
 *
 * $Id: Admin_view.php zhu $
 *
 * @package    Core
 */
class Admin_view_Core extends View{
    
	/**
	 * Sets the view filename.
	 *
	 * @chainable
	 * @param   string  view filename
	 * @param   string  view file type
	 * @return  object
	 */
	public function set_filename($name, $type = NULL)
	{
        $name = 'admin/'.$name;
        return parent::set_filename($name, $type);
    }
}