<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Product_model extends CI_Model
{

	public function __construct()
	{
		$this->load->database();
	}

	// retrieves the bidding history for an item
	public function getBidHistory($product_id) {
		$query = sprintf('select * from product_bids where product_ID = %s order by bid_price desc limit 5', 
		$product_id);

		$results = $this->db->query($query);
		return $results;
	}


	// searches for products which have matching description or title
	public function search($searchterm) {
		$query = sprintf('select * from products where description like "%%%s%%" or product_name 
							like "%%%s%%"', $searchterm, $searchterm);
		$results = $this->db->query($query);
		return $results;
	}

	// retrieves the time in seconds until the auction has ended
	public function auctionEndSeconds($product_id) {
		$query = "select (to_seconds(end_datetime) - to_seconds(now())) as 'sec' from products 
		where product_id = {$product_id}";
		$result = $this->db->query($query);

		return $result->row();
	}

	// retrieves the details for a product given the product_id
	public function getDetails($product_id) {
		$query = sprintf('select * from products where product_id = %s', $product_id);
		$result = $this->db->query($query)->row();
		return $result;
	}

	// retrieves the details for the seller given their seller_Id
	public function getSellerDetails($seller_id) {
		$query = sprintf('select distinct(user_id), username, seller_rating from users, products where users.user_id = 
							products.seller_id and products.seller_id = %s', $seller_id);
		$result = $this->db->query($query)->row();
		return $result;
	}

	// gets the current price of the product
	public function getCurrentPrice($product_id) {
		$query = "select current_price from products where product_id = {$product_id}";
		$result = $this->db->query($query)->row();
		return $result->current_price;
	}


	// submits a bid for a product
	public function submitBid($product_Id, $bid_price, $user_ID) {
		// need to update product product_bids first
		$bid_query = "insert into product_bids (product_ID, user_ID, bid_price) values ({$product_Id},
		 {$user_ID}, {$bid_price})";

		// need to update product table to reflect current price
		$product_query = "update products set current_price = {$bid_price} where product_id = {$product_Id}";

		$this->db->query($bid_query);
		$this->db->query($product_query);
	}

}