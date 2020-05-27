<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Login_model extends CI_Model
{

	public function __construct()
	{
		$this->load->database();
	}


	// returns user data if the user given by email and password is valid
	// TODO sql injection?
	public function checkLogin($email, $password)
	{
		$passquery = "select * from users where email = ?";
		$userdetails = $this->db->query($passquery, array($email));
		if ($userdetails->num_rows() == 0) {
			return false;
		}
		$userpassword_hash = $userdetails->row()->password;

		if (
			password_verify($password, $userpassword_hash) && strlen($email) != 0
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

	// checks if the new username is valid
	public function checkNewUsernameValid($username)
	{
		$usernamequery = "select count(*) as 'count' from users where username = ?";
		$countusernames = $this->db->query($usernamequery, array($username))->row()->count;

		if ($countusernames != 0 || strlen($username) == 0) {
			return false;
		}

		return true;
	}


	// checks if the new email is valid
	public function checkNewEmailValid($email)
	{
		$emailquery = "select count(*) as 'count' from users where email = ?";
		$countemails = $this->db->query($emailquery, array($email))->row()->count;

		if ($countemails != 0 || strlen($email) == 0) {
			return false;
		}

		return true;
	}

	// gets the phone number associated with the user
	public function getPhoneNumber($user_ID) {
		$query = "select phone_number from users where user_id = ?";
		$result = $this->db->query($query, array($user_ID))->row()->phone_number;
		return $result;
	}


	// checks if the details are valid for registration
	public function checkRegistration($email, $username)
	{
		return $this->checkNewEmailValid($email) && $this->checkNewUsernameValid($username);
	}

	// registers a user 
	public function registerUser($email, $username, $password, $phone_number)
	{
		$hashed = password_hash($password, PASSWORD_BCRYPT);
		$insertquery = "insert into users (email, password, username, phone_number) values (?, ?, ?, ?)";
		$this->db->query($insertquery, array($email, $hashed, $username, $phone_number));
	}

	public function getUserDetails($user_id)
	{
		$query = "select * from users where user_id = ?";
		return $this->db->query($query, $user_id)->row();
	}

	public function changePassword($user_id, $new_password)
	{
		$new_hash = password_hash($new_password, PASSWORD_BCRYPT);
		$insertquery = "update users set password = ? where user_id = ?";
		$this->db->query($insertquery, array($new_hash, $user_id));
	}


	public function changeEmail($user_id, $new_email)
	{
		$insertquery = "update users set email = ? where user_id = ?";
		$this->db->query($insertquery, array($new_email, $user_id));
	}

	public function changeUsername($user_id, $new_username)
	{
		$insertquery = "update users set username = ? where user_id = ?";
		$this->db->query($insertquery, array($new_username, $user_id));
	}

	public function changePhoneNumber($user_id, $new_phonenumber) {
		$insertquery = "update users set phone_number = ? where user_id = ?";
		$this->db->query($insertquery, array($new_phonenumber, $user_id));
	}
}
