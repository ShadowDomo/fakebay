<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Papers extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model('papers_model');
		$this->load->helper('url_helper');
		$this->load->helper('form');
	}

	public function read($year = 2018) {
		if (! file_exists(APPPATH.'views/papers/'.$year.'.php')) {
			show_404();
//			echo !file_exists(APPPATH.'view/papers/'.$year.'.php');
		}
		$data['year'] = $year;
		$this->load->view('templates/header', $data);
		$this->load->view('papers/'.$year, $data);
		$this->load->view('templates/footer', $data);
	}

	public function index(){
		// $data['users'] = $this->papers_model->get_user();
		// $data['title'] = 'Users';

		$data['error'] = "none";
		$this->load->view('templates/header');
		$this->load->view('papers/login', $data);
		$this->load->view('templates/footer');

	}


	public function homePage() {
		$this->load->view('templates/header');
		$this->load->view('papers/searchbar');
		$this->load->view('papers/home');
		$this->load->view('templates/footer');
	}

	// user login
	public function login() {
		$email = $this->input->post("email");
		$password = $this->input->post("password");
		
		if ($this->papers_model->checkLogin($email, $password)) {
			$this->load->view('papers/success');
			return;
		}
		$data['error'] = "invalid";
		$this->load->view('templates/header');
		$this->load->view('papers/login', $data);
		$this->load->view('templates/footer');
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
		$validdeets = $this->papers_model->checkRegistration($email, $username);

		// register user if details are valid
		if ($validpassword) {
			if ($validdeets) {
				$this->papers_model->registerUser($email, $username, $password);
				echo "success";
				return;
			} 
			$data['error'] = "usernameemailerror";
			$this->load->view('templates/header');
			$this->load->view('papers/register', $data);
			$this->load->view('templates/footer');
			return;
		} 
		// is not clean if passwords do not match
		$data['error'] = "passworderror";
		$this->load->view('templates/header');
		$this->load->view('papers/register', $data);
		$this->load->view('templates/footer');
	}

	// checks whether login or register button clicked
	public function formChecker() {
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
		$this->load->view('papers/register', $data);
		$this->load->view('templates/footer');
	}

}
