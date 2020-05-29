<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

	// how long the user can remain idle before being kicked
	private $timeout_time;

	// big number
	private $infinity;

	public function __construct()
	{
		parent::__construct();
		$this->load->model('login_model');

		// probs dont need these two?
		$this->load->helper('url_helper'); 
		$this->load->helper('form');
		$this->load->library('SMS');

		$this->timeout_time = 6;
		$this->infinity = 9999999999999999;
	}

	// tells javascript to log you out when you run out of time
	public function timeout() {
		$this->session->time_remaining -= 1;

		if ($this->session->time_remaining < 1) {
			echo 'logout';
		} else {
			echo $this->session->time_remaining;
		}
	}

	// generates secret code for Two-Factor Authentication.
	// secret code has the time as a seed so the same key is generated 
	// within the same 60 second period. 
	public function generateSecretCode($user_id) {
		// should have 60 seconds before the number is changed
		srand(floor(time() / 60) + intval($user_id));
		$num = rand(100000,999999);
		// echo $num;
		return $num;
	}

	public function index(){
		// if session already set redirect to profile
		if ($this->session->has_userdata('user_id')) {
			$this->viewProfile();
			return;
		}
		$this->session->time_remaining = $this->infinity;
		$data['error'] = "none";
		$this->load->view('templates/header');
		$this->load->view('login/login', $data);
		$this->load->view('templates/footer');
	}

	// redirects to login page if not logged in
	// returns true if not logged in
	public function notLoggedIn() {
		if (!$this->session->has_userdata('user_id')) {
			// $data['error'] = "none";
			// $this->load->view('templates/header');
			// $this->load->view('login/login', $data);
			// $this->load->view('templates/footer');
			return redirect()->to('Login');
		}
		return false;
	}

	// opens user profile
	public function viewProfile() {
		if ($this->notLoggedIn()) {
			return;
		}
		$this->session->set_userdata('time_remaining', $this->timeout_time);

		$data['user_details'] = $this->login_model->getUserDetails($this->session->user_id);

		$this->load->view('templates/header');
		$this->load->view('product/searchbar');
		$this->load->view('login/profile' , $data);
		$this->load->view('templates/footer');
	}

	// edit user profile
	public function openEditProfile($error = 'none') {
		if ($this->notLoggedIn()) {
			return;
		}
		$this->session->set_userdata('time_remaining', $this->timeout_time);
		$data['user_details'] = $this->login_model->getUserDetails($this->session->user_id);
		$data['error'] = $error;
		$this->load->view('templates/header');
		$this->load->view('product/searchbar');
		$this->load->view('login/edit_profile' , $data);
		$this->load->view('templates/footer');
	}

	// loads 2fa
	public function twoFactor($user_id) {
		$this->load->view('templates/header');
		$this->load->view('login/authentication');

		// generates code and sends text
		$secret = $this->generateSecretCode($user_id);
		$phoneNumber = $this->login_model->getPhoneNumber($user_id);
		$this->sms->sendMessage($phoneNumber, $secret);
		$this->load->view('templates/footer');
	}

	// checks authentication 
	public function checkTwoFactor() {
		$secret = $this->input->post("secret");
		if ($secret == strval($this->generateSecretCode($this->session->logged_in))) {
			$this->session->set_userdata('user_id', $this->session->logged_in);
			$this->session->unset_userdata('logged_in');
			$this->viewProfile();
		} else {
			// didn't work - try again
			$this->session->set_userdata('error', "two factor");
			$this->twoFactor($this->session->logged_in);
		}
	}
	
	// user login
	public function login() {
		$email = $this->input->post("email");
		$password = $this->input->post("password");
		
		if ($data = $this->login_model->checkLogin($email, $password)) {
			$this->session->set_userdata('logged_in', $data['user_id']);
			$this->session->set_userdata('time_remaining', $this->timeout_time);
			// $this->twoFactor($data['user_id']); //TODO uncomment for 2fa

			// comment following 2 lines for 2fa
			$this->session->set_userdata('user_id', $data['user_id']);
			$this->viewProfile();
			return;
		}
		$this->session->set_userdata('logged_in', 99999999999);
		$data['error'] = "invalid";
		$this->load->view('templates/header');
		$this->load->view('login/login', $data);
		$this->load->view('templates/footer');
	}


	// logs the user out, clears session data
	public function logout() {
		$this->session->set_userdata('logged_in', 9999999999999);
		$this->session->unset_userdata('user_id');
		$this->index();
	}


	public function checkEditProfile() {
		$this->session->set_userdata('time_remaining', $this->timeout_time);

		$data['email'] = $this->input->post("email");
		$data['username'] = $this->input->post("username");
		$data['phone_number'] = $this->input->post("phone_number");
		$data['current_password'] = $this->input->post("current_password");
		$data['new_password'] = $this->input->post("new_password");
		$data['confirmed_password'] = $this->input->post("confirmed_password");

		// gets the current details for the user
		$data['userdetails'] = $this->login_model->getUserDetails($this->session->user_id);

		$result = $this->editProfile($data);
		if (!is_string($result)) {
			$this->viewProfile();
			return;
		}
		$this->openEditProfile($result);

	}

	// returns true on success, false otherwise
	public function editProfile($data) {
		$this->session->set_userdata('time_remaining', $this->timeout_time);

		if ($this->notLoggedIn()) {
			return;
		}
		$email = $data['email'];
		$username = $data['username'];
		$phone_number = $data['phone_number'];
		$current_password = $data['current_password'];
		$new_password = $data['new_password'];
		$confirmed_password = $data['confirmed_password'];

		$userdetails = $data['userdetails'];

		$email_needs_changing = false;
		$username_needs_changing = false;
		$password_needs_changing = false;
		$number_needs_changing = false;
		
		// if confirmed isn't the same
		if ($new_password != $confirmed_password) {
		
			// echo 'passwords not same';
			return 'password_not_same';
		}

		// check if we are changing password
		if (strlen($new_password) != 0) {
			$password_needs_changing = true;
		}

		// they are the same

		// if entered current password isn't correct
		if (!password_verify($current_password, $userdetails->password) && $password_needs_changing) {

			// echo "password not correct";
			return 'password_incorrect';
		}
		
		if ($email != $userdetails->email) {
			$email_needs_changing = true;
		}

		if ($username != $userdetails->username) {
			$username_needs_changing = true;
		}

		if ($phone_number != $userdetails->phone_number) {
			$number_needs_changing = true;
		}

		// check if phone number is valid
		if (strlen($phone_number) != 10) {
			return 'phone invalid';
		}

		// check if new email is valid
		if ($email_needs_changing && !$this->login_model->checkNewEmailValid($email)) {
			// echo 'invalid email';
			return 'email_invalid';
		}

		// check if username is valid
		if ($username_needs_changing && !$this->login_model->checkNewUsernameValid($username)) {
		
			// echo 'invalid username';
			return 'invalid_username';
		}

		// change username
		if ($username_needs_changing) {
			$this->login_model->changeUsername($this->session->user_id, $username);
			// echo 'username changed';
		}
		// change email
		if ($email_needs_changing) {
			$this->login_model->changeEmail($this->session->user_id, $email);
			// echo 'email changed';
		}

		if ($number_needs_changing) {
			$this->login_model->changePhoneNumber($this->session->user_id, $phone_number);
		}

		// change password if needed
		if ($password_needs_changing) {
			$this->login_model->changePassword($this->session->user_id, $new_password);
			// echo 'success';
		}

		return;
	}

	 /** Returns true if the password satisfies requirements
	  * Requirements: At least 8 characters, need atleast one capital,
	  * need a number.
	 */
	public function checkPasswordRequirements($password, $confirmed_password) {
		$minimum_length = 8;
		// check if passwords match
		if ($password !== $confirmed_password) {
			$this->session->set_userdata('error', 'dont match Passwords do not match! Please try again.');
			return false;
		}

		// check password length
		if (strlen($password) < $minimum_length) {
			$this->session->set_userdata('error', 'length Password does not meet requirements. Please try again');
			return false;
		}

		// checks if there is atleast one capital letter
		if (strtolower($password) == $password) {
			$this->session->set_userdata('error', ' capital Password does not meet requirements. Please try again');
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
			$this->session->set_userdata('error', 'no number Password does not meet requirements. Please try again');
			return false;
		}

		$this->session->unset_userdata('error');

		// password satisfies requirements
		return true;
	}

	// registers a user
	public function register() {
		$email = $this->input->post("email");
		$password = $this->input->post("password");
		$confirmed_password = $this->input->post("confirmed_password");
		$username = $this->input->post("username");
		$phone_number = $this->input->post("phone_number");

		// check if password meets requirments
		$validpassword = $this->checkPasswordRequirements($password, $confirmed_password);

		// check if username and email are valid
		$validdeets = $this->login_model->checkRegistration($email, $username);

		// check if the phone number is of the correct length
		$validnumber = TRUE;
		if (strlen($phone_number) != 10) {
			$validnumber = FALSE;
		}

		// register user if details are valid
		if ($validpassword) {
			if ($validdeets) {
				if ($validnumber) {
					$this->login_model->registerUser($email, $username, $password, $phone_number);
					// TODO should probs put a alert saying success
					$this->index();
					return;
				}
				$this->session->set_userdata('error', 'Phone number is not valid! Please try again.');
				$this->load->view('templates/header');
				$this->load->view('login/register');
				$this->load->view('templates/footer');
				return;
			} 
			$this->session->set_userdata('error', 'Invalid username or email. Please try again.');
			$this->load->view('templates/header');
			$this->load->view('login/register');
			$this->load->view('templates/footer');
			return;
		} 
		// is not clean if passwords do not match
		$this->load->view('templates/header');
		$this->load->view('login/register');
		$this->load->view('templates/footer');
		return;
	}

	// checks whether login or register button clicked
	public function formChecker() {
		if ($this->session->has_userdata('user_id')) {
			$this->viewProfile();
			return;
		}
		$submitvalue = $this->input->post("submit");
	
		if ($submitvalue == "register") {
			$this->registerform();
			return;
		}
		$this->login();
	}

	// opens register form
	public function registerform() {
		// error value determs what the form says
		$data['error'] = "none";
		$this->load->view('templates/header');
		$this->load->view('login/register', $data);
		$this->load->view('templates/footer');
	}

}
