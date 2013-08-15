<?php defined('SYSPATH') OR die('No direct access allowed.');

class Site_Model extends ORM {
    protected $has_and_belongs_to_many = array('managers','payments','site_groups');
    protected $belongs_to = array('site_type','theme');
    protected $has_many = array('logs','domains','mails','user_logs');
	protected $has_one = array('site_detail');
    
    /**
     * Validates and optionally saves a new user record from an array.
     *
     * @param  array    values to check
     * @param  boolean  save the record when validation succeeds
     * @return boolean
     */
	public function validate(array & $array, $save = FALSE, & $errors)
	{
        $fields=parent::as_array();
        $array=array_merge($fields,$array);

        $array = Validation::factory($array)
            ->pre_filter('trim')
            ->add_rules('name','required','length[1,200]')
            ->add_rules('domain','required','length[0,320]')
			->add_rules('site_title','length[0,250]')
			->add_rules('logo','length[0,250]')
            ->add_rules('site_email','required','length[0,320]')
            ->add_rules('theme_id','required','numeric')
            ->add_rules('active', 'numeric')
            ->add_rules('wholesale', 'numeric')
            ->add_rules('is_wholesale', 'numeric')
            ->add_rules('https', 'numeric')
            ->add_rules('memo','length[0,255]');

		if(parent::validate($array, $save))
		{
            return TRUE;
		}
		else
		{
            $errors = $array->errors();
            return FALSE;
        }
    }
}
