<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Papers_model extends CI_Model
{

	public function __construct()
	{
		$this->load->database();
	}


	// returns user data if the user given by email and password is valid
	// TODO sql injection?
	public function checkLogin($email, $password)
	{
		$passquery = sprintf('select * from users where email = "%s"', $email);
		$userdetails = $this->db->query($passquery);
		if ($userdetails->num_rows() == 0) {
			return false;
		}
		$userpassword = $userdetails->row()->password;

		if (
			$password == $userpassword && strlen($email) != 0
			&& strlen($password) != 0
		) {
			// user authenticated
			$data['username'] = $userdetails->row()->username;
			$data['email'] = $userdetails->row()->email;
			$data['user_id'] = $userdetails->row()->user_id;
			return $data;
		}
		return false;
	}

	

	// returns true if user with email / username is not taken
	public function checkRegistration($email, $username)
	{
		$emailquery = sprintf('select count(*) as "count" from users where email = "%s"', $email);
		$usernamequery = sprintf('select count(*) as "count" from users where username = "%s"', $username);

		$countemails = $this->db->query($emailquery)->row()->count;
		$countusernames = $this->db->query($usernamequery)->row()->count;

		if ($countemails != 0 || $countusernames != 0 || strlen($email) == 0 || strlen($username) == 0) {
			return false;
		}
		return true;
	}

	public function registerUser($email, $username, $password)
	{
		$insertquery = sprintf('insert into users (email, password, username) values ("%s", "%s", "%s")', $email, $password, $username);
		$this->db->query($insertquery);
	}
}
