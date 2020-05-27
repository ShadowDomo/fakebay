<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Payment{

    // for Paypal sandbox api
    public $client_ID;
    public $secret;

    public $token;
    
    public function __construct() {        
        // super secret stuff
        $this->client_ID = "AePRm1rdA51yIgECneOiet1gCCGoljdrcrmK4UFmYD_x2ntmyBch9sq35bgkNiyj89LJaH-jeZYAb5yE";
        $this->secret = "EJ_pylWVlwpsHdUIoEWri_YViu6m_30W-LXA6U6EWEKibDaE9vPYHkadTHVivtjYOTndXC1u6soB3jJ9";
    }

    public function index() {
        $this->token = $this->getToken();

        echo $this->token;
    }

    // returns access token
    public function getToken() {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://api.sandbox.paypal.com/v1/oauth2/token');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");
        curl_setopt($ch, CURLOPT_USERPWD, $this->client_ID . ':' . $this->secret);
        
        $headers = array();
        $headers[] = 'Accept: application/json';
        $headers[] = 'Accept-Language: en_US';
        $headers[] = 'Content-Type: application/x-www-form-urlencoded';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        
        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);
        $result = json_decode($result, true);
        
        return $result['access_token'];
    }



}