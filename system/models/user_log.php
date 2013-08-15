<?php defined('SYSPATH') OR die('No direct access allowed.');

class User_log_Model extends ORM 
{
	protected $belongs_to = array('manager','site');

    public function validate(array & $array, $save = FALSE, & $errors) 
    {
        $fields=parent::as_array();
        $array=array_merge($fields,$array);

        $array = Validation::factory($array)
            ->pre_filter('trim')
            ->add_rules('status','numeric')
            ->add_rules('manager_id', 'numeric')
            ->add_rules('user_log_type', 'numeric')
            ->add_rules('ip','length[0,255]')
            ->add_rules('method','length[0,255]')
            ->add_rules('memo','length[0,512]') ;

        if(parent::validate($array, $save)) 
        {
            return TRUE;
        }
        else 
        {
            $errors = $array->errors();
			log::write('form_error',$errors,__FILE__,__LINE__);
            return FALSE;
        }
    }
}
