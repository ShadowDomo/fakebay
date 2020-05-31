<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model('product_model');
		$this->load->helper('url_helper');
        $this->load->helper('form');
		$this->load->library('Payment');
		$this->load->library('encryption');
    }


    
    
    public function index() {
		// $key = $this->encryption->create_key(16);
		$key = "c4d23157404c7f8eb8adc18a5c5862c2";
		// echo bin2hex($key);
		// $this->login_model->storeKey($this->session->user_id, $key);
		$this->encryption->initialize(array('key' => $key));
		// $new_pass = "Fcac12312";
		// $secret = $this->encryption->encrypt($new_pass);
		$secret = "01441370dcf2f6034b8b97b2a795c342965c99a7291f8c109a72cf6eefbd80eed97545bf059d9bc9fe94bd227665d660d7753998050b8d9fec91e755fca087993ONQ6zBbdeSZfvXUpuXrtbU6aReZlJfc5rspfbR980o=";
		// echo $secret;

		$solved = $this->encryption->decrypt($secret);
		echo $solved;
    }
	
	
}
