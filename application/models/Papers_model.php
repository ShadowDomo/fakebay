<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Papers_model extends CI_Model {

	public function __construct() {
		$this->load->database();
	}

	public function get_user() {
		$query = $this->db->get('users');
		return $query->result_array();
	}

	// public function test() {
	// 	$query = 'select * from users';
	// 	return $this->db->query($query)->result_array();
	// }

	// returns user data if the user given by email and password is valid
	// TODO sql injection?
	public function check_login($email, $password){
		$passquery = sprintf('select * from users where email = "%s"', $email);
		$userdetails = $this->db->query($passquery)->row();
		$userpassword = $userdetails->password;
	
		if ($password == $userpassword) {
			// user authenticated
			$data['username'] = $userdetails->username;
			$data['email'] = $userdetails->email;
			$data['user_id'] = $userdetails->user_id;
			return $data;
		} else {
			return false;
		}
	}

	// returns true if user with email / username is not taken
	public function check_registration($email, $username){
		$emailquery = sprintf('select count(*) as "count" from users where email = "%s"', $email);
		$usernamequery = sprintf('select count(*) as "count" from users where username = "%s"', $username);
		
		$countemails = $this->db->query($emailquery)->row()->count;
		$countusernames = $this->db->query($usernamequery)->row()->count;

		if ($countemails != 0 || $countusernames != 0 || strlen($email) == 0 || strlen($username) == 0) {
			return false;
		} else {
			return true;
		}  
	}

	public function register_user($email, $username, $password){
		$insertquery = sprintf('insert into users (email, password, username) values ("%s", "%s", "%s")', $email, $password, $username);
		$this->db->query($insertquery);
	}

}
