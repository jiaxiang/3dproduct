<?php defined('SYSPATH') OR die('No direct access allowed.');

class Kc_folder_Model extends ORM {
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
            ->add_rules('site_id', 'required', 'numeric')
            ->add_rules('parent_id', 'numeric')
            ->add_rules('sub_folder_ids', 'length[0,1024]')
            ->add_rules('name', 'required', 'length[1,255]')
            ->add_rules('level_depth', 'required', 'numeric')
            ->add_rules('date_add', 'required', 'length[1,255]')
            ->add_rules('date_upd', 'required', 'length[1,255]');
        if(parent::validate($array, $save)) {
            return TRUE;
        }else {
            $errors = $array->errors();
            return FALSE;
        }
    }
}
