<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Checkout extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model('product_model');
		$this->load->helper('url_helper');
        $this->load->helper('form');
        // $this->load->library('Payment');
    }

    
    public function index() {
        $data['error'] = "none";
        $this->load->view('templates/header');
        $this->load->view('payment/payment');
        $this->load->view('templates/footer');
    }
}
