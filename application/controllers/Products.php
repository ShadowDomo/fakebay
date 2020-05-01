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
	public function searchProducts(){
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

	// displays info for a product
	public function viewProduct(){
		$product_id = $this->input->get("product_id");
		
		
		$data['product_details'] = $this->product_model->getDetails($product_id);

		$seller_id = $data['product_details']->seller_id;
		$data['seller_details'] = $this->product_model->getSellerDetails($seller_id);
		// $data['product_id'] = $product_id;
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
