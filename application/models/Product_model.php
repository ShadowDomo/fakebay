<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Product_model extends CI_Model
{

	public function __construct()
	{
		$this->load->database();
	}


	// searches for products which have matching description or title
	public function search($searchterm) {

		$query = sprintf('select * from products where description like "%%%s%%" or product_name like "%%%s%%"', $searchterm, $searchterm);
		$results = $this->db->query($query);

		return $results;

	}
}
