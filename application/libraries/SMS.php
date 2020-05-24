<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SMS{

    // Telstra API details
    public $client_key;
    public $client_secret;  
    
    // Auth token - lasts 10 minutes
    private $authToken;

    // provisioned number
    private $subNumber;

	public function __construct()
	{        
        // super secret stuff
        $this->client_key = "xJAOWaKJBrehiXZdslpXakAG8H0UDoDh";
        $this->client_secret = "bBhGRMqXNFk1C2LS";
    }

    // sends a SMS to the destination with the given message
    public function sendMessage($dest, $message) {
        $this->getAuthToken();
        $this->subscription();
        
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://tapi.telstra.com/v2/messages/sms');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "{\n    \"to\":\"$dest\",\n    \"body\":\"$message\"  \n  }");

        $headers = array();
        $headers[] = "authorization: Bearer $this->authToken";
        $headers[] = 'Content-Type: application/json';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);
        // echo $result;
    }

    // gets the provisioned number
    public function subscription() {
        $this->getAuthToken();
        // echo $this->authToken;
        
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://tapi.telstra.com/v2/messages/provisioning/subscriptions');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "{\n\"activeDays\":30,\n\"notifyURL\":\"http://example.com/\"\n}");

        $headers = array();
        $headers[] = "authorization: Bearer $this->authToken";
        $headers[] = 'Cache-Control: no-cache';
        $headers[] = 'Content-Type: application/json';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);
        // echo $result;
        $result = json_decode($result, true);
        $this->subNumber = $result['destinationAddress'];
        // echo $this->subNumber;
    }
    
    // gets the Authentication token
    public function getAuthToken() {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://tapi.telstra.com/v2/oauth/token');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials&client_id={$this->client_key}&client_secret={$this->client_secret}&scope=NSMS");

        $headers = array();
        $headers[] = 'Content-Type: application/x-www-form-urlencoded';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);

        // convert to JSON
        $result = json_decode($result, true);
        $this->authToken = $result['access_token'];
    }


}