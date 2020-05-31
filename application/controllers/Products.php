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
		$this->timeout_time = 600;
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


	// retrieves the search results as json
	public function getSearchResultsOnly() {
		$searchterm = $this->input->get("searchterm");
		if ($searchterm == '' || $searchterm == NULL) {
			echo json_encode();
			return;
		}
		$results = $this->product_model->search($searchterm);

		// echo "searchterm was: " . $searchterm;
		echo json_encode($results);
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

		$num_photos = intval($this->input->post("count_files"));
		$product_name = $this->input->post("product_name");
		$starting_price = $this->input->post("starting_price");
		$end_date = $this->input->post("end_date");
		$end_time = $this->input->post("end_time");
		$description = $this->input->post("description");

		$datetime = date('Y-m-d H:i:s', strtotime("$end_date $end_time"));

		// check starting price is valid
		if (!is_numeric($starting_price)) {
			$this->session->set_userdata("error", "Starting price is invalid.");
			$this->viewListPage();
			return;
		}
		if (floatval($starting_price) < 0) {
			$this->session->set_userdata("error", "Starting price is invalid.");
			$this->viewListPage();
			return;
		}

		// check if date / time is valid
		if (strtotime($datetime) - time() < 0) {
			$this->session->set_userdata("error", "Invalid date or time.");
			$this->viewListPage();
			return;
		}

		for ($i = 1; $i <= $num_photos; ++$i) {
			// $filename = $this->input->post("uploaded_file_{$count}");
			$currenttime = md5(time());
			$product_nameencrypt = md5($product_name);
			$str = "{$currenttime}{$product_nameencrypt}";
			$filename = md5($str);
	
			// echo $filename;
			
			// file config
			$config['upload_path']   = 'assets/'; 
			$config['allowed_types'] = 'jpg|png|jpeg|JPG|JPEG|PNG'; 
			$config['max_size']      = 5000; 
			$config['max_width']     = 99999; 
			$config['max_height']    = 99999; 
			$config['file_name'] = $filename;
			$this->load->library('upload', $config);

			
	
			// upload file
			if (!$this->upload->do_upload("uploaded_file_{$i}")) {
				// echo $this->upload->display_errors();
				$this->session->set_userdata("error", $this->upload->display_errors());
				
				$this->viewListPage();
				return;
			} else {
				$data["upload_data_{$i}"] = $this->upload->data();

				// resize image
				$imgconfig['image_library'] = 'gd2';
				$imgconfig['source_image'] = '/path/to/image/mypic.jpg';
				$imgconfig['maintain_ratio'] = TRUE;
				$imgconfig['width']         = 1240;
				$imgconfig['height']       = 780;
				$this->load->library('image_lib', $imgconfig);
			}
		}

		$filenames = array();
		for ($i = 1; $i <= $num_photos; ++$i) {
			array_push($filenames, $data["upload_data_{$i}"]['file_name']);
		}
		// var_dump($filenames);
		// return;
		// return;
		$product_Id = $this->product_model->createListing($product_name, $description, $starting_price, $filenames, $this->session->user_id, $datetime);
		$this->viewProductProper($product_Id);
	}

	// opens the listing page
	public function viewListPage() {
		$this->session->set_userdata('time_remaining', $this->timeout_time);
		// $this->session->unset_userdata('error');

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
		$data['photo_details'] = $this->product_model->getPhotos($product_id);
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
