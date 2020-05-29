<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Products extends CI_Controller {


	private $timeout_time;

	public function __construct()
	{
		parent::__construct();
		$this->load->model('product_model');
		$this->load->helper('url_helper');
		$this->load->helper('form');
		$this->timeout_time = 6;
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

	// search for products
	public function searchProducts() {
		$this->session->set_userdata('time_remaining', $this->timeout_time);

		$searchterm = $this->input->get("search");
		$results = $this->product_model->search($searchterm);
		// echo $searchterm;
		$data['results'] = $results;
		$this->load->view('templates/header');
		$this->load->view('product/searchbar');
		$this->load->view('product/results', $data);
		
		$this->load->view('templates/footer');
	}

	// makes a bid on a product
	public function makeBid() {
		$this->session->set_userdata('time_remaining', $this->timeout_time);

		$bid_price = $this->input->post("bid_price");
		$product_id = $this->uri->segment(3);
		$user_id = $this->session->user_id;

		// ensure only logged in can continue
		// if ($this->notLoggedIn()) {
		// 	return;
		// }

		// check if time is valid. 
		$end_time_in_secs = $this->product_model->auctionEndSeconds($product_id)->sec;
		if ($end_time_in_secs < 0) {
			// trying to bid after auction has ended
			$this->session->set_userdata('error', "time error");
			return;
		}

		$current_price = $this->product_model->getCurrentPrice($product_id);
		if ($bid_price <= $current_price || !is_numeric($bid_price)) {
			// error - bid price is too low
			$this->session->set_userdata('error', "bid error");
			$this->viewProductProper($product_id);
			return;
		}	
		
		$this->product_model->submitBid($product_id, $bid_price, $user_id);
		$this->viewProductProper($product_id);
	}

	// makes a listing for a product
	public function makeListing() {
		$this->session->set_userdata('time_remaining', $this->timeout_time);

		if ($this->notLoggedIn()) {
			return;
		};

		$product_name = $this->input->post("product_name");
		$starting_price = $this->input->post("starting_price");
		$end_date = $this->input->post("end_date");
		$end_time = $this->input->post("end_time");
		$description = $this->input->post("description");

		$datetime = date('Y-m-d H:i:s', strtotime("$end_date $end_time"));
		$filename = $this->input->post("uploaded_file");
		$currenttime = md5(time());
		$product_nameencrypt = md5($product_name);
		
		$filename = md5("{$currenttime}{$product_nameencrypt}");
		// upload config
		$config['upload_path']   = 'assets/'; 
		$config['allowed_types'] = 'jpg|png|jpeg|JPG|JPEG|PNG'; 
		$config['max_size']      = 2048; 
		$config['max_width']     = 1024; 
		$config['max_height']    = 768; 
		$config['file_name'] = $filename;
		$this->load->library('upload', $config);

		// upload file
		if (!$this->upload->do_upload('uploaded_file')) {
			echo $this->upload->display_errors();
			return;
		} else {
			$data = array('upload_data' => $this->upload->data());
		}

		$product_Id = $this->product_model->createListing($product_name, $description, $starting_price,
		$this->upload->data()['file_name'], $this->session->user_id, $datetime);
		$this->viewProductProper($product_Id);
	}

	// opens the listing page
	public function viewListPage() {
		$this->session->set_userdata('time_remaining', $this->timeout_time);

		$this->load->view('templates/header');
		$this->load->view('product/searchbar');
		$this->load->view('product/listing');
		$this->load->view('templates/footer');
	}

	// gets input from searchbar
	public function viewProduct() {
		$ajax = $this->input->get("ajax");
		$product_id = $this->input->get("product_id");
		if (isset($this->session->user_id) && $ajax != "true") {
			$this->session->set_userdata('time_remaining', $this->timeout_time);
		}
		
		// $data['product_id'] = $product_id;
		$this->viewProductProper($product_id);
	}

	// displays all info for a product
	public function viewProductProper($product_id) {
		$data['product_details'] = $this->product_model->getDetails($product_id);
		$data['bid_history'] = $this->product_model->getBidHistory($product_id);

		$seller_id = $data['product_details']->seller_id;
		$data['seller_details'] = $this->product_model->getSellerDetails($seller_id);
		$auction_end_in_seconds = $this->product_model->auctionEndSeconds($product_id)->sec;

		$data['total_seconds'] = $auction_end_in_seconds;
		$auction_end_in_minutes = $auction_end_in_seconds / 60;
		$auction_end_in_hours = $auction_end_in_minutes / 60;
		$auction_end_in_days = $auction_end_in_hours / 24;

		// todo make it countdown live
		$hours = 0;
		$minutes = 0;
		$seconds = 0;
		$days = 0;

		$days = floor($auction_end_in_days);
		$auction_end_in_hours -= $days * 24;

		$hours = floor($auction_end_in_hours);
		$auction_end_in_minutes -= $hours * 60;
		$auction_end_in_minutes -= $days * 24 * 60;

		$minutes = floor($auction_end_in_minutes);
		$auction_end_in_seconds -= $minutes * 60;
		$auction_end_in_seconds -= $days * 24 * 60 * 60;
		$auction_end_in_seconds -= $hours * 60 * 60;

		$seconds = $auction_end_in_seconds;

		$data['hours'] = $hours;
		$data['minutes'] = $minutes;
		$data['days'] = $days;
		$data['seconds'] = $seconds;
		
		$this->load->view('templates/header');
		$this->load->view('product/searchbar');
		$this->load->view('product/product', $data);
		$this->load->view('templates/footer');
	}

	public function index(){
		if (isset($this->session->user_id)) {
			$this->session->set_userdata('time_remaining', $this->timeout_time);
		}
		$data['error'] = "none";
		$this->load->view('templates/header');
		$this->load->view('product/searchbar');
		$this->load->view('templates/footer');
	}
}
