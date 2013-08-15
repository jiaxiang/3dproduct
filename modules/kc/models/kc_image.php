<?php defined('SYSPATH') OR die('No direct access allowed.');

class Kc_image_Model extends ORM {
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
            ->add_rules('kc_folder_id', 'required', 'numeric')
            ->add_rules('attach_id', 'required','length[1,50]')
            ->add_rules('image_type', 'length[0,25]')
            ->add_rules('image_size', 'required', 'numeric')
            ->add_rules('image_name', 'required', 'length[1,255]')
            ->add_rules('image_mime', 'length[0,100]')
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
