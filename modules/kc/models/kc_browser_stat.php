<?php defined('SYSPATH') OR die('No direct access allowed.');

class Kc_browser_stat_Model extends ORM {
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
            ->add_rules('type', 'required', 'length[0,255]')
            ->add_rules('version', 'required', 'length[0,255]')
            ->add_rules('major_version', 'length[0,255]')
            ->add_rules('minor_version', 'length[0,255]')
            ->add_rules('agent_detail', 'required', 'length[0,255]')
            ->add_rules('ip', 'required', 'numeric')
            ->add_rules('quantity', 'numeric')
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
