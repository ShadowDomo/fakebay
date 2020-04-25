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
		$data['users'] = $this->papers_model->get_user();
		$data['title'] = 'Users';

		$this->load->view('templates/header', $data);
		$this->load->view('papers/login', $data);
		$this->load->view('templates/footer', $data);

	}

	public function login() {

		$email = $this->input->post("email");
		$password = $this->input->post("password");

		

		if ($email == "admin@gmail.com" && $password=="pass") {
			echo 'success';
		} else {

			echo 'failure';
		}

	}

}
