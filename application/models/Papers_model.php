<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Papers_model extends CI_Model {

	public function __construct() {
		$this->load->database();
	}

	public function get_user() {
		$query = $this->db->get('users');
		return $query->result_array();
	}

	public function test() {
		$query = 'select * from users';
		return $this->db->query($query)->result_array();
	}

	// returns true if the user given by email and password is valid
	public function check_login($email, $password){
		$passquery = sprintf('select * from users where email = "%s"', $email);
		$userdetails = $this->db->query($passquery)->row();
		$userpassword = $userdetails->password;
	
		if ($password == $userpassword) {
			// user authenticated
			$data['username'] = $userdetails->username;
			$data['email'] = $userdetails->email;
			$data['user_id'] = $userdetails->user_id;
			return true;
		} else {
			return false;
		}
	}


}
