<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model('login_model');

		// probs dont need these two?
		$this->load->helper('url_helper'); 
		$this->load->helper('form');
	}

	public function index(){
		// if session already set redirect to profile
		if ($this->session->has_userdata('user_id')) {
			$this->viewProfile();
			return;
		}
		$data['error'] = "none";
		$this->load->view('templates/header');
		$this->load->view('login/login', $data);
		$this->load->view('templates/footer');

	}

	// opens user profile
	public function viewProfile() {
		$data['user_details'] = $this->login_model->getUserDetails($this->session->user_id);

		$this->load->view('templates/header');
		$this->load->view('product/searchbar');
		$this->load->view('login/profile' , $data);
		$this->load->view('templates/footer');
	}

	// edit user profile
	public function openEditProfile($error = 'none') {
		$data['user_details'] = $this->login_model->getUserDetails($this->session->user_id);
		$data['error'] = $error;
		$this->load->view('templates/header');
		$this->load->view('product/searchbar');
		$this->load->view('login/edit_profile' , $data);
		$this->load->view('templates/footer');
	}


	// user login
	public function login() {
		$email = $this->input->post("email");
		$password = $this->input->post("password");
		
		if ($data = $this->login_model->checkLogin($email, $password)) {
			$this->session->set_userdata('user_id', $data['user_id']);
			$this->viewProfile();
			return;
		}
		$data['error'] = "invalid";
		$this->load->view('templates/header');
		$this->load->view('login/login', $data);
		$this->load->view('templates/footer');
	}


	// logs the user out, clears session data
	public function logout() {
		$this->session->unset_userdata('user_id');
		$this->index();
	}


	public function checkEditProfile() {
		$data['email'] = $this->input->post("email");
		$data['username'] = $this->input->post("username");
		$data['current_password'] = $this->input->post("current_password");
		$data['new_password'] = $this->input->post("new_password");
		$data['confirmed_password'] = $this->input->post("confirmed_password");

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
		$email = $data['email'];
		$username = $data['username'];
		$current_password = $data['current_password'];
		$new_password = $data['new_password'];
		$confirmed_password = $data['confirmed_password'];

		$userdetails = $this->login_model->getUserDetails($this->session->user_id);

		$email_needs_changing = false;
		$username_needs_changing = false;
		$password_needs_changing = false;
		
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

		// check if new email is valid
		if ($email_needs_changing && !$this->login_model->checkNewEmailValid($email)) {
			// echo 'invalid email';
			return 'email_invalid';
		}
		
		

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

		// change password if needed
		if ($password_needs_changing) {
			$this->login_model->changePassword($this->session->user_id, $new_password);
			// echo 'success';
		}

		return;
	}

	// registers a user
	public function register() {
		$email = $this->input->post("email");
		$password = $this->input->post("password");
		$confirmedpassword = $this->input->post("confirmed_password");
		$username = $this->input->post("username");

		// check if the password is valid
		$validpassword = ($confirmedpassword == $password) ? true : false;

		// check if username and email are valid
		$validdeets = $this->login_model->checkRegistration($email, $username);

		// register user if details are valid
		if ($validpassword) {
			if ($validdeets) {
				$this->login_model->registerUser($email, $username, $password);
			
				// TODO should probs put a alert saying success
				$this->index();
				return;
			} 
			$data['error'] = "usernameemailerror";
			$this->load->view('templates/header');
			$this->load->view('login/register', $data);
			$this->load->view('templates/footer');
			return;
		} 
		// is not clean if passwords do not match
		$data['error'] = "passworderror";
		$this->load->view('templates/header');
		$this->load->view('login/register', $data);
		$this->load->view('templates/footer');
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
