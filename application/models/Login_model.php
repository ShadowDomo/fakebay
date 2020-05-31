<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Login_model extends CI_Model
{

	public function __construct()
	{
		$this->load->database();
	}


	// returns user data if the user given by email and password is valid
	// TODO sql injection?
	public function checkLogin($email, $password) {
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
	public function checkNewUsernameValid($username) {
		$usernamequery = "select count(*) as 'count' from users where username = ?";
		$countusernames = $this->db->query($usernamequery, array($username))->row()->count;

		if ($countusernames != 0 || strlen($username) == 0) {
			return false;
		}

		return true;
	}


	// checks if the new email is valid
	public function checkNewEmailValid($email) {
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
	public function checkRegistration($email, $username) {
		return $this->checkNewEmailValid($email) && $this->checkNewUsernameValid($username);
	}

	// registers a user. returns the user_id which was just registerd
	public function registerUser($email, $username, $password, $phone_number) {
		$hashed = password_hash($password, PASSWORD_BCRYPT);
		$insertquery = "insert into users (email, password, username, phone_number, verified) values (?, ?, ?, ?, '0')";
		$this->db->query($insertquery, array($email, $hashed, $username, $phone_number));

		$query = "select user_id from users order by user_id desc limit 1";
		return $this->db->query($query)->row()->user_id;
	}

	// store encryption key for a user
	public function storeKey($user_id, $key) {
		// only store if don't exist
		$count = "select count(*) as 'count' from encryption where user_id = ?";
		$result = $this->db->query($count, array($user_id))->row()->count;

		if ($result != 1) {
			$query = "insert into encryption (user_id, user_key) values (?, ?)";
			$this->db->query($query, array($user_id, $key));
		}
	}

	// gets the verification status for the user
	public function getVerificationStatus($user_id) {
		$query = "select verified from users where user_id = ?";
		return $this->db->query($query, array($user_id))->row()->verified;
	}


	// changes the verifications status
	public function setVerificationStatus($user_id) {
		$query = "update users set verified = 1 where user_id = ?";
		$this->db->query($query, array($user_id));
	}

	// get the encryption key
	public function getKey($user_id) {
		$query = "select user_key from encryption where user_id = ?";
		return $this->db->query($query, array($user_id))->row()->user_key;
	}

	// gets the user's details
	public function getUserDetails($user_id) {
		$query = "select * from users where user_id = ?";
		return $this->db->query($query, $user_id)->row();
	}

	// changes the user's password
	public function changePassword($user_id, $new_password)
	{
		$new_hash = password_hash($new_password, PASSWORD_BCRYPT);
		$insertquery = "update users set password = ? where user_id = ?";
		$this->db->query($insertquery, array($new_hash, $user_id));
	}

	// changes the user's email
	public function changeEmail($user_id, $new_email)
	{
		$insertquery = "update users set email = ? where user_id = ?";
		$this->db->query($insertquery, array($new_email, $user_id));
	}

	// changes the user's username
	public function changeUsername($user_id, $new_username)
	{
		$insertquery = "update users set username = ? where user_id = ?";
		$this->db->query($insertquery, array($new_username, $user_id));
	}

	// changes the user's phone number
	public function changePhoneNumber($user_id, $new_phonenumber) {
		$insertquery = "update users set phone_number = ? where user_id = ?";
		$this->db->query($insertquery, array($new_phonenumber, $user_id));
	}
}
