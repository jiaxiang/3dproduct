<?php 
defined('SYSPATH') OR die('No direct access allowed.');

class Site_detail_Model extends ORM {
    protected $belongs_to = array('site');
}
