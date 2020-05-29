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
    }


    public function checkPasswordRequirements($password, $confirmed_password) {
		$minimum_length = 8;
		// check if passwords match
		if ($password !== $confirmed_password) {
            echo "dont match";
			return false;
		}

		// check password length
		if (strlen($password) < $minimum_length) {
            echo "len too short";
            return false;
		}

		// checks if there is atleast one capital letter
		if (strtolower($password) == $password) {
            echo "no capital";
            return false;
        }

		// checks if there is atleast one number
		$is_num = false;
		for ($i = 0; $i < 9; ++$i) {
			if (strpos($password, strval($i)) !== FALSE) {
				$is_num = true;
			}
		}
		if ($is_num === FALSE) {
            echo "no number";
			return false;
		}

		$this->session->unset_userdata('error');

        // password satisfies requirements
        echo "good";
		return true;
	}
    
    public function index() {
        // $this->payment->index();
        $password = "aaaaaaaA2";
        $confirmed_password = $password;
        $this->checkPasswordRequirements($password, $confirmed_password);
    }
    
}
