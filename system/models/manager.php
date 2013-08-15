<?php defined('SYSPATH') OR die('No direct access allowed.');

class Manager_Model extends ORM {
    protected $has_and_belongs_to_many = array('site_groups','sites');
    //zhu modify add acl
    protected $has_one = array('acl');
    protected $belongs_to = array('role');
    protected $has_many = array('logs','domains','user_logs');
    /**
     * Validates and optionally saves a new user record from an array.
     *
     * @param  array    values to check
     * @param  boolean  save[Optional] the record when validation succeeds
     * @param  string   error info
     * @return boolean
     */
    public function validate(array & $array, $save = FALSE , & $errors) {
        $fields=parent::as_array();
        $array=array_merge($fields,$array);

        $array = Validation::factory($array)
            ->pre_filter('trim')
            ->add_rules('name','required','length[1,250]')
            ->add_rules('username','required','length[1,250]')
            ->add_rules('email','required','length[1,320]')
            ->add_rules('password','required','length[1,100]')
            ->add_rules('address','length[0,255]')
            //zhu modify
            //->add_rules('role_id','required','numeric')
            ->add_rules('role_id','numeric')
            ->add_rules('site_num','required','numeric')
            ->add_rules('contact_name','length[0,250]')
            ->add_rules('phone','length[0,250]')
            ->add_rules('mobile_phone','length[0,100]')
            ->add_rules('postcode','length[0,20]')
            ->add_rules('fax','length[0,255]')
            ->add_rules('country','length[0,255]')
            ->add_rules('province','length[0,255]')
            ->add_rules('city','length[0,255]')
            ->add_rules('township','length[0,255]')
            ->add_rules('is_admin', 'digit')
			->add_rules('type', 'numeric')
            ->add_rules('active', 'numeric');

        if(parent::validate($array, $save)) {
            return TRUE;
        } else {
            $errors = $array->errors();
            return FALSE;
        }
    }
}
