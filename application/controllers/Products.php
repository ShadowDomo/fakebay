<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Products extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model('product_model');
		$this->load->helper('url_helper');
		$this->load->helper('form');
	}

	// search for products
	public function searchProducts() {
		$searchterm = $this->input->get("search");
		$results = $this->product_model->search($searchterm);
		// echo $searchterm;
		$data['results'] = $results;
		$this->load->view('templates/header');
		$this->load->view('product/searchbar');
		$this->load->view('product/results', $data);
		
		// foreach ($results->result() as $row) {
			
		// 	echo $row->product_id . '<br>';
		// 	echo $row->product_name . '<br>';
		// 	echo $row->description . '<br>';
		// }
		$this->load->view('templates/footer');
	}

	// makes a bid on a product
	public function makeBid() {
		$bid_price = $this->input->post("bid_price");
		$product_id = $this->uri->segment(3);
		$user_id = $this->session->user_id;

		// check if time is valid. 
		$end_time_in_secs = $this->product_model->auctionEndSeconds($product_id)->sec;
		if ($end_time_in_secs < 0) {
			// trying to bid after auction has ended
			$this->session->set_userdata('error', "time error");
			return;
		}

		$current_price = $this->product_model->getCurrentPrice($product_id);
		if ($bid_price < $current_price || !is_numeric($bid_price)) {
			// error - bid price is too low
			$this->session->set_userdata('error', "bid error");
			$this->viewProductProper($product_id);
			return;
		}	
		
		$this->product_model->submitBid($product_id, $bid_price, $user_id);
		$this->viewProductProper($product_id);
	}

	// gets input from searchbar
	public function viewProduct() {
		$product_id = $this->input->get("product_id");
		

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

		if ($auction_end_in_days >= 1) {
			$days = floor($auction_end_in_days);
		}
		$auction_end_in_hours -= $days * 24;

		if ($auction_end_in_hours >= 1) {
			$hours = floor($auction_end_in_hours);
		}
		$auction_end_in_minutes -= $hours * 60;

		if ($auction_end_in_minutes >= 1) {
			$minutes = floor($auction_end_in_minutes);
		}
		$auction_end_in_seconds -= $minutes * 60;
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
		$data['error'] = "none";
		$this->load->view('templates/header');
		$this->load->view('product/searchbar');
		$this->load->view('templates/footer');

	}
}
