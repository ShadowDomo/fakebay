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

		$this->load->view('templates/header');
		$this->load->view('papers/login');
		$this->load->view('templates/footer');

	}

	public function login() {
		$email = $this->input->post("email");
		$password = $this->input->post("password");

		// $data['users'] = $this->papers_model->test();
		
		if ($this->papers_model->check_login($email, $password)) {
	
			$this->load->view('papers/success');
		} else {

			$this->load->view('papers/failure');
		}
	}

	public function register() {
		$email = $this->input->post("email");
		$password = $this->input->post("password");
		$confirmedpassword = $this->input->post("confirmed_password");
		$username = $this->input->post("username");

		// check if the password is valid
		$validpassword = ($confirmedpassword == $password) ? true : false;

		// check if username and email are valid
		$validUsernameAndEmail = $this->papers_model->check_registration($email, $username);

		// register user if details are valid
		if ($validpassword) {
			if ($validUsernameAndEmail) {
				$this->papers_model->register_user($email, $username, $password);
				echo "success";
			} else {
				$data['error'] = "usernameemailerror";
				$this->load->view('templates/header');
				$this->load->view('papers/register', $data);
				$this->load->view('templates/footer');
			}
		} else {
			// is not clean if passwords do not match
			$data['error'] = "passworderror";
			$this->load->view('templates/header');
			$this->load->view('papers/register', $data);
			$this->load->view('templates/footer');
		}
	}

	public function formchecker() {
		// check if register or not
		$submitvalue = $this->input->post("submit");
	
		if ($submitvalue == "register") {
			$this->registerform();
		} else {
			$this->login();
		}
	}

	public function registerform() {
		// is clean if clicked from start
		$data['error'] = "none";
		$this->load->view('templates/header');
		$this->load->view('papers/register', $data);
		$this->load->view('templates/footer');
	}

}
