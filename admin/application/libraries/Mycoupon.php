<?php defined('SYSPATH') OR die("No direct access allowed.");
  
class Mycoupon_Core extends My
{
	protected $object_name = "coupon";
	protected static $instances;
	//protected $orm_instance = NULL;
	//protected $data = array();
	
	public static function & instance($id=0)
	{
		if(!isset(self::$instances[$id]))
		{
			$class=__CLASS__;
			self::$instances[$id]=new $class($id);
		}
		return self::$instances[$id];
    }
    /*
	
	public function __construct($id)
	{
		$id 		= intval($id);
		$this->data = ORM::factory($this->object_name, $id)->as_array();
    }
     */
	
	 /**
     * 获取公共实例
     */
    /*
    private  function get_orm_instance()
    {
        if (is_null($this->orm_instance)) {
            $this->orm_instance = ORM::factory($this->object_name);
        }
        return $this->orm_instance;
    }
     */

    /**
     * Overload lists() method
     *
     * @param array $request_struct
     * @return array
     * @throws Exception
     */
    /*
    public function lists($request_struct) 
    {
        $list    = array();
        $coupon_list = parent::lists($request_struct);

        foreach ( $coupon_list as $key => $value )
        {
            $list[$key] = $value;
            $list[$key]['site_name'] = Mysite::instance($value['site_id'])->get('name');
            $promotions = ORM::factory('cpn_promotion')
                ->where('cpn_id', $value['id'])
                ->find_all();
            $list[$key]['promotions'] = array();

            foreach ( $promotions as $keyp => $_promotion ) {
                $list[$key]['promotions'][$keyp]['id'] = $_promotion->id;
                $list[$key]['promotions'][$keyp]['description'] = $_promotion->cpn_description;
                $list[$key]['promotions'][$keyp]['time_begin'] = $_promotion->time_begin;
                $list[$key]['promotions'][$keyp]['time_end'] = $_promotion->time_end;
            }

            // Get a group of use-once-only coupon codes for listing below an coupon item on which mouse clicks.
            if ( $value['cpn_type'] == 'A' ) {
                $list[$key]['codes'] = self::instance()->gen_coupons('A', $value['cpn_prefix'], $value['cpn_key'], $value['cpn_gen_quantity'], 'array');
            }
        }
        return $list;
    }
     */

    /**
     * 检查cpn_prefix重复
     *
     * @param string $site_id
     * @param string $cpn_prefix
     * @param Int
     */
    public function check_cpn_prefix($cpn_prefix)
    {
        return ORM::factory($this->object_name)
            ->where('cpn_prefix', $cpn_prefix)
            ->count_all();
    }

    /**
     * Encrypt coupon code of type A ( use once only )
     * php extension mcrypt is required
     * 
     * @param string $prefix
     * @param string $key
     * @param integer $num
     * @return string
     */
    private function encrypt($prefix, $key, $num) {
        $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
        return mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $prefix, $key.$num, MCRYPT_MODE_ECB, $iv);
    }

    /**
     * Generate a coupon code
     * 
     * @param string $coupon_type
     * @param string $cpn_prefix
     * @param string $key
     * @param integer $number
     * @return string
     */
    private function generate_coupon($coupon_type = 'A', $cpn_prefix, $cpn_key = '', $number = 1) {
        //print_r(func_get_args());
        //exit;
        if ( $coupon_type == 'A' ) {
            // coupon code format: prefix + encrypted 6 characters + serial number
            $serial_no = '';
            $zeros = 5 - strlen($number);

            for ( $j = 0; $j < $zeros; $j++ ) {
                $serial_no .= '0';
            }

            $serial_no .= $number;
            $cryptchars = $this->encrypt($cpn_prefix, $cpn_key, $number);    	
            $cryptchars = substr(strtoupper(base64_encode($cryptchars)), 0, 6);
            return $cpn_prefix . $cryptchars . $serial_no;
        } else {
            return $cpn_prefix;
        }
    }

    /**
     * Generate multiple coupon codes
     * 
     * @param string $prefix
     * @param string $seed
     * @param integer $quantity
     * @param string $return_type
     * @return string or array (depends on $return_type)
     */
    /*
    public function gen_coupons($coupon_type = 'A', $cpn_prefix, $cpn_key, $quantity, $return_type = 'text', $type = 0,$used_coupons= array()) {
        if ( $return_type == 'text' ) {
            $coupons = '';
        } else if ( $return_type == 'array' ) {
            $coupons = array();
        }

        for ( $i = 1; $i <= $quantity; $i++ ) {
            if ( $return_type == 'text' ) {
                $coupon = $this->generate_coupon($coupon_type, $cpn_prefix, $cpn_key, $i);

                if ( $type == 0 ) { // download not used coupons only
                    if ( ! in_array($coupon, $used_coupons) ) {
                        $coupons .= $this->generate_coupon($coupon_type, $cpn_prefix, $cpn_key, $i) . "\n";
                    }
                } else if ( $type == 1 ) { // download used coupons only
                    if ( in_array($coupon, $used_coupons) ) {
                        $coupons .= $this->generate_coupon($coupon_type, $cpn_prefix, $cpn_key, $i) . "\n";
                    }
                } else { // download all the coupons
                    $coupons .= $this->generate_coupon($coupon_type, $cpn_prefix, $cpn_key, $i) . "\n";    				
                }
            } else if ( $return_type == 'array' ) {
                $coupons[] = $this->generate_coupon($coupon_type, $cpn_prefix, $cpn_key, $i);
            }
        }
        return $coupons;
    }
     */
    /**
     * Generate multiple coupon codes
     * 
     * @param Integer $coupon_id
     * @param Integer $quantity
     * @return Array|Boolean
     */
    public function gen_coupons($coupon_id ,$quantity) 
    {
        $coupon         = Mycoupon::instance($coupon_id)->get();
        if(!$coupon['id'])
        {
            return $false;
        }

        $coupons    = array();
        if($coupon['cpn_type']=='A')
        {
            $end    = $coupon['cpn_gen_quantity']+$quantity;
            for ( $i = $coupon['cpn_gen_quantity']+1; $i <= $end; $i++ ) {
                $coupons[] = $this->generate_coupon($coupon['cpn_type'], $coupon['cpn_prefix'], $coupon['cpn_key'], $i);
            }
        }
        else
        {
            $end    = 1;
            $coupons[] = $this->generate_coupon($coupon['cpn_type'], $coupon['cpn_prefix'], $coupon['cpn_key'], $end);
        } 

        $coupon_orm = ORM::factory('coupon',$coupon_id);
        $coupon_orm->cpn_gen_quantity   = $end;
        $coupon_orm->save();            

        return $coupons;
    }

    /**
     * Generate a string composed of random characters
     * 
     * @param integer, length of random string returned 
     * @return string
     */
    public function gen_random_string($length) {
        $alphabet = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 
            'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');

        $num_alphabet = count($alphabet);
        $str = '';

        for ( $i = 0; $i < $length; $i++ ) {
            $str .= $alphabet[mt_rand(0, $num_alphabet)];
        }
        return $str;
    }

    /**
     * Get used coupon codes
     * 
     * @param string $prefix
     * @param string $seed
     * @param integer $quantity
     * @param string $return_type
     * @return string or array (depends on $return_type)
     */
    public function get_used_coupons($site_id) {
        $coupons = array();
        $coupons['listings'] = Myused_coupon::instance()->lists(array('where' => array('site_id' => $site_id)));
        $coupons['count'] = count($coupons['listings']);
        return $coupons;
    }

    /**
     * Overload delete() method
     *
     * @param Int $id
     * @return Boolean
     */
    public function delete($id = 0)
    {
        $orm_instance = ORM::factory('used_coupon')
            ->where('cpn_id', $id)
            ->delete_all();
        Mycpn_promotion::instance()->delete_by_couponid($id);
        return parent::delete($id);
    }

}
