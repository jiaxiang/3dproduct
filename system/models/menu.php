<?php defined('SYSPATH') OR die('No direct access allowed.');

class Menu_Model extends ORM {
    protected $belongs_to = array('action');
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
			->add_rules('name','required','length[1,255]')
			->add_rules('url','required','length[1,255]')
			->add_rules('target','required','length[1,255]')
			->add_rules('parent_id','required','numeric')
			->add_rules('action_id','numeric')
			->add_rules('order','required','numeric')
			->add_rules('active','required','numeric')
			->add_rules('alias','length[0,65535]')
			->add_rules('memo','length[0,255]');

		if(parent::validate($array, $save)) {
			return TRUE;
		}else {
			$errors = $array->errors();
			return FALSE;
		}
	}
}
