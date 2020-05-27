<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model('product_model');
		$this->load->helper('url_helper');
		$this->load->helper('form');
    }

    
    public function index() {
        $one = "fuhrer";
        $two = "shirt";
        $metone = metaphone($one);
        $mettwo = metaphone($two);

        echo $metone;
        echo "<br>";
        echo $mettwo;
        echo "<br>";

        $le = levenshtein($metone, $mettwo);
        $n = 100 * (strlen($mettwo) - $le) / strlen($mettwo);
        echo "leveshtein {$n}% <br>";
        similar_text($one, $two, $perc);
        echo  '<br> similartext '. $perc . '%';
    }
}
