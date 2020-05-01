<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Product_model extends CI_Model
{

	public function __construct()
	{
		$this->load->database();
	}


	// searches for products which have matching description or title
	public function search($searchterm) {
		$query = sprintf('select * from products where description like "%%%s%%" or product_name 
							like "%%%s%%"', $searchterm, $searchterm);
		$results = $this->db->query($query);
		return $results;
	}

	public function getDetails($product_id) {
		$query = sprintf('select * from products where product_id = %s', $product_id);
		$result = $this->db->query($query)->row();
		return $result;
	}

	public function getSellerDetails($seller_id) {
		$query = sprintf('select distinct(user_id), username, seller_rating from users, products where users.user_id = 
							products.seller_id and products.seller_id = %s', $seller_id);
		$result = $this->db->query($query)->row();
		return $result;
	}
}