<?php defined('SYSPATH') OR die('No direct access allowed.');
// $Id$
class Delivery_category_Model extends ORM {
    /**
     * Validates and optionally saves a new delivery category from an array.
     *
     * @param array value to check
     * @param boolean save the record when validation succeeds
     * @return boolean      
     */
    public function validate(array & $array, $save = FALSE, & $errors){
        $fields = parent::as_array();
        $array = array_merge($fields,$array);
        $array = Validation::factory($array)
                 ->pre_filter('trim')
                 ->add_rules('name','required','length[1,255]')
                 ->add_rules('ename','required','length[1,255]')
                 ->add_rules('description','length[1,1000]')
                 ->add_rules('edescription','length[1,1000]');

        if(parent::validate($array,$save)) {
            return TRUE;
        }else {
            $errors = $array->errors();
            log::write('form_error',$errors,__FILE__,__LINE__);
            return FALSE;
        }
    }
}
?>
