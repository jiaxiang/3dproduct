<?php defined('SYSPATH') OR die('No direct access allowed.');

class Action_Model extends ORM {
	protected $has_and_belongs_to_many = array('roles');
    protected $has_many = array('menus');
	/**
	 * Validates and optionally saves a new user record from an array.
	 *
	 * @param  array    values to check
	 * @param  boolean  save the record when validation succeeds
	 * @return boolean
	 */
	public function validate(array & $array, $save = FALSE, & $errors) {
		$fields=parent::as_array();
		$array=array_merge($fields,$array);

		$array = Validation::factory($array)
			->pre_filter('trim')
			->add_rules('name','required','length[1,200]')
			->add_rules('resource','required','length[1,200]')
			->add_rules('parent_id', 'numeric')
			->add_rules('order','numeric');

		if(parent::validate($array, $save)) {
			return TRUE;
		}else {
			$errors = $array->errors();
			return FALSE;
		}
	}
}
?>
