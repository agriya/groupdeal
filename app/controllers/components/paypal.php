<?php
/**
 * Group Deal
 *
 * PHP version 5
 *
 * @category   PHP
 * @package    GroupDeal
 * @subpackage Core
 * @author     Agriya <info@agriya.com>
 * @copyright  2018 Agriya Infoway Private Ltd
 * @license    http://www.agriya.com/ Agriya Infoway Licence
 * @link       http://www.agriya.com
 */
class PaypalComponent extends Component
{
    public $components = array(
        'RequestHandler'
    );
    //Fixed settings
    private $postback_url = array(
        'testmode' => 'https://www.sandbox.paypal.com/cgi-bin/webscr',
        'livemode' => 'https://www.paypal.com/cgi-bin/webscr'
    );
    private $masspay_url = array(
        'testmode' => 'https://api-3t.sandbox.paypal.com/nvp',
        'livemode' => 'https://api-3t.paypal.com/nvp'
    );
    private $paypal_post_vars_in_str = '';
    private $errno;
    private $paypal_response = '';
    public $paypal_post_arr = array();
    //Overridable settings
    public $payee_account = '';
    public $paypal_receiver_emails = '';
    public $is_test_mode = 0;
    public $amount_for_item;
    public $paypal_transaction_model = 'PaypalTransactionLog';
    private $do_direct_pay_constants = array(
        'subject' => '',
        'use_proxy' => false,
        'proxy_host' => '127.0.0.1',
        'proxy_port' => 808,
        'paypal_url' => array(
            'test_mode' => 'https://api-3t.sandbox.paypal.com/nvp',
            'live_mode' => 'https://api-3t.paypal.com/nvp'
        ) ,
        'version' => 62.0
    );
    public function initialize($controller, $settings = array()) 
    {
        $this->_set($settings);
    }
    public function startup($controller) 
    {
    }
	public function setExpress_checkout ($post_info, $sender_info)
    {
	
        $returnURL = urlencode($post_info['return_url']);
        $cancelURL = urlencode($post_info['cancel_url']);
        $paymentType = urlencode($post_info['payment_type']);
        $paymentAmount = urlencode($post_info['amount']);
		$item_name = urlencode($post_info['item_name']);
		$item_qty = urlencode($post_info['qty']);
        $currencyID = urlencode($post_info['currency_code']);
		$temp_log_id = urlencode($post_info['temp_log_id']);
		$item_price = urlencode($post_info['item_price']);
        $nvpStr = "&Amt=$paymentAmount&ReturnUrl=$returnURL&CANCELURL=$cancelURL&PAYMENTACTION=$paymentType&CURRENCYCODE=$currencyID&CUSTOM=$temp_log_id&L_NAME0=$item_name&L_QTY0=$item_qty&L_AMT0=$item_price";
		$nvpHeader = $this->nvpHeader($sender_info);
        $nvpStr = $nvpHeader . $nvpStr;
        /* Make the API call to PayPal, using API signature.
        The API response is stored in an associative array called $resArray */
        $resArray = $this->hash_call("SetExpressCheckout", $nvpStr, $sender_info);
        return $resArray;
    }
	public function getExpress_checkout_details ($post_info, $sender_info) 
    {
		$token = urlencode($post_info['TOKEN']);
 		$nvpStr = "&TOKEN=$token";
        $nvpHeader = $this->nvpHeader($sender_info);
        $nvpStr = $nvpHeader . $nvpStr;
        /* Make the API call to PayPal, using API signature.
        The API response is stored in an associative array called $resArray */
        $resArray = $this->hash_call("GetExpressCheckoutDetails", $nvpStr, $sender_info);
        return $resArray;
    }
	public function doExpress_checkout ($post_info, $sender_info) 
    {
        $token = urlencode($post_info['TOKEN']);
		$payer_id = urlencode($post_info['payer_id']);
		$paymentType = urlencode($post_info['payment_type']);
        $paymentAmount = urlencode($post_info['amount']);
		$currencyID = urlencode($post_info['currency_code']);
		$temp_log_id = urlencode($post_info['temp_log_id']);
		$serverName = urlencode($_SERVER['SERVER_NAME']);
		
 		$nvpStr = "&AMT=$paymentAmount&PAYERID=$payer_id&TOKEN=$token&PAYMENTACTION=$paymentType&CURRENCYCODE=$currencyID&IPADDRESS=$serverName&PAYMENTREQUEST_0_CUSTOM=$temp_log_id";
		$this->log($nvpStr);
        $nvpHeader = $this->nvpHeader($sender_info);
        $nvpStr = $nvpHeader . $nvpStr;
        /* Make the API call to PayPal, using API signature.
        The API response is stored in an associative array called $resArray */
        $resArray = $this->hash_call("DoExpressCheckoutPayment", $nvpStr, $sender_info);
        return $resArray;
    }	
    public function process() 
    {
        $this->errno = 0; //initialize to no error
        $this->errno|= (strcmp($this->postResponse2PayPal() , 'VERIFIED') == 0) ? 0 : (1<<0);
        $this->errno|= (strcmp($this->paypal_post_arr['payment_status'], 'Completed') == 0) ? 0 : (1<<1);
        $this->errno|= (!$this->_isTransactionProcessed()) ? 0 : (1<<2);
        $this->errno|= ($this->_isValidReceiverEmail()) ? 0 : (1<<3);
        if (!(strcmp($this->paypal_post_arr['payment_status'], 'Refunded') == 0)) {
            //$this->errno|= (($this->amount_for_item != 0) and ($this->paypal_post_arr['mc_gross'] >= $this->amount_for_item)) ? 0 : (1 << 4);
            
        }
        //$this->errno|= ($this->paypal_post_arr['test_ipn'] != '1') ? 0 : (1<<5);
        return (!$this->errno);
    }
    public function auth_process() 
    {
        $this->errno = 0; //initialize to no error
        $this->errno|= (strcmp($this->postResponse2PayPal() , 'VERIFIED') == 0) ? 0 : (1<<0);
        $this->errno|= (!$this->_isTransactionProcessed()) ? 0 : (1<<2);
        $this->errno|= ($this->_isValidReceiverEmail()) ? 0 : (1<<3);
        //$this->errno|= ($this->paypal_post_arr['test_ipn'] != '1') ? 0 : (1<<5);
        return (!$this->errno);
    }
    private function _isValidReceiverEmail() 
    {
        $receiver_emails[] = $this->payee_account;
        $tmp_receiver_emails = explode(',', $this->paypal_receiver_emails);
        if (is_array($tmp_receiver_emails)) {
            foreach($tmp_receiver_emails as $receiver_email) {
                $receiver_emails[] = trim($receiver_email);
            }
        }
        return (in_array($this->paypal_post_arr['receiver_email'], $receiver_emails));
    }
    public function postResponse2PayPal() 
    {
        // post back to PayPal system to validate
        $this->paypal_post_vars_in_str = 'cmd=_notify-validate' . $this->paypal_post_vars_in_str;
        $url = parse_url((($this->is_test_mode) ? $this->postback_url['testmode'] : $this->postback_url['livemode']));
        $ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'https://' .$url['host']. '/cgi-bin/webscr');
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $this->paypal_post_vars_in_str);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Host: www.' .$url['host']));
		$res = curl_exec($ch);
		curl_close($ch);
		$this->log("Response");
		$this->log($res);
		if (strcmp($res, 'VERIFIED') == 0) {
              $this->paypal_response = 'VERIFIED';
		}
        else if (strcmp($resp, 'INVALID') == 0) {
              $this->paypal_response = 'INVALID';
        }
        return $this->paypal_response;
    }
    public function sanitizeServerVars($_POST) 
    {
        $this->paypal_post_arr = (!empty($_POST)) ? $_POST : array();
        foreach($this->paypal_post_arr as $key => $value) {
            $value = urlencode(stripslashes($value));
            $this->paypal_post_vars_in_str.= "&$key=$value";
        }
        $expected_paypal_post_arr = array(
            'txn_id' => '',
            'item_name' => '',
            'payer_email' => '',
            'payment_date' => '',
            'mc_gross' => 0,
            'mc_fee' => 0,
            'mc_currency' => '',
            'payment_status' => '',
            'payment_type' => '',
            'receiver_email' => '',
            'quantity' => 0,
            'payer_status' => '',
            'test_ipn' => '',
            'option_selection1' => '',
            'option_name1' => '',
            'option_selection5' => '',
            'option_name5' => '',
            'type' => '',
            'memo' => '',
            'auth_exp' => '',
            'transaction_entity' => '',
            'parent_txn_id' => '',
            'remaining_settle' => '',
            'auth_id' => '',
            'auth_amount' => '',
            'pending_reason' => '',
            'payment_gross' => '',
            'auth_status' => '',
        );
        $tmp_arr = array();
        foreach($expected_paypal_post_arr as $key => $default_value) {
            $tmp_arr[$key] = (isset($this->paypal_post_arr[$key])) ? htmlspecialchars(trim($this->paypal_post_arr[$key])) : $default_value;
        }
        //Processing and fetching the user defiend fields
        if (!empty($this->paypal_post_arr['option_name1']) and $this->paypal_post_arr['option_name1'] == 'Transkey' and !empty($this->paypal_post_arr['option_selection1'])) {
            $transkey_parts = explode('~', $this->paypal_post_arr['option_selection1']);
            if (count($transkey_parts) == 2) {
                if ($transkey_parts[1] == (substr(md5(Configure::read('Security.salt') . $transkey_parts[0]) , 5, 5))) {
                    $user_defined = unserialize(gzinflate(base64_url_decode($transkey_parts[0])));
                    if (is_array($user_defined)) {
                        $tmp_arr = array_merge($tmp_arr, $user_defined);
                    }
                }
            }
        }
        if (!empty($this->paypal_post_arr['option_name2']) and $this->paypal_post_arr['option_name2'] == 'SysKey' and !empty($this->paypal_post_arr['option_selection2'])) {
            $transkey_parts = explode('~', $this->paypal_post_arr['option_selection2']);
            if (count($transkey_parts) == 2) {
                if ($transkey_parts[1] == (substr(md5(Configure::read('Security.salt') . $transkey_parts[0]) , 5, 5))) {
                    $system_defined = unserialize(gzinflate(base64_url_decode($transkey_parts[0])));
                    if (is_array($system_defined)) {
                        $tmp_arr = array_merge($tmp_arr, $system_defined);
                    }
                }
            }
        }
        if (!empty($this->paypal_post_arr['option_name3']) and $this->paypal_post_arr['option_name3'] == 'Message' and !empty($this->paypal_post_arr['option_selection3'])) {
            $transkey_parts = explode('~', $this->paypal_post_arr['option_selection3']);
            if (count($transkey_parts) == 2) {
                if ($transkey_parts[1] == (substr(md5(Configure::read('Security.salt') . $transkey_parts[0]) , 5, 5))) {
                    $system_defined = unserialize(gzinflate(base64_url_decode($transkey_parts[0])));
                    if (is_array($system_defined)) {
                        $tmp_arr = array_merge($tmp_arr, $system_defined);
                    }
                }
            }
        }
        if (!empty($this->paypal_post_arr['option_name4']) and $this->paypal_post_arr['option_name4'] == 'Gift' and !empty($this->paypal_post_arr['option_selection4'])) {
            $transkey_parts = explode('~', $this->paypal_post_arr['option_selection4']);
            if (count($transkey_parts) == 2) {
                if ($transkey_parts[1] == (substr(md5(Configure::read('Security.salt') . $transkey_parts[0]) , 5, 5))) {
                    $system_defined = unserialize(gzinflate(base64_url_decode($transkey_parts[0])));
                    if (is_array($system_defined)) {
                        $tmp_arr = array_merge($tmp_arr, $system_defined);
                    }
                }
            }
        }
        if (!empty($this->paypal_post_arr['option_name5']) and $this->paypal_post_arr['option_name5'] == 'Refer' and !empty($this->paypal_post_arr['option_selection5'])) {
            $transkey_parts = explode('~', $this->paypal_post_arr['option_selection5']);
            if (count($transkey_parts) == 2) {
                if ($transkey_parts[1] == (substr(md5(Configure::read('Security.salt') . $transkey_parts[0]) , 5, 5))) {
                    $system_defined = unserialize(gzinflate(base64_url_decode($transkey_parts[0])));
                    if (is_array($system_defined)) {
                        $tmp_arr = array_merge($tmp_arr, $system_defined);
                    }
                }
            }
        }
        $this->paypal_post_arr = $tmp_arr;
    }
    private function _isTransactionProcessed() 
    {
        $data = array();
        $paypalTransactionModel = ClassRegistry::init($this->paypal_transaction_model);
        return ($paypalTransactionModel->find('count', array(
            'conditions' => array(
                $this->paypal_transaction_model . '.txn_id' => $this->paypal_post_arr['txn_id'],
                $this->paypal_transaction_model . '.error_no' => 0
            )
        )));
    }
    public function logPaypalTransactions() 
    {
        //Creating error message string from errorno
        $errorMessaegString = '';
        if ($this->errno) {
            $_errMessages = array(
                1 => 'Problem in VERIFIED status', // 0
                2 => 'Not completed', // 1
                4 => 'Problem in processing transaction', // 2
                8 => 'Invlaid receiver email', // 3
                16 => 'Enought amount not received', // 4
                32 => 'Test ipn (sandbox transaction) enabled', // 5
                64 => 'vacant', // 6
                128 => 'vacant', // 7
                256 => 'vacant', // 8
                512 => 'vacant', // 9
                1024 => 'vacant', // 10
                
            );
            $errMessages = array();
            for ($i = 0; $i < count($_errMessages); ++$i) {
                if ($this->errno&(1<<$i)) {
                    $errMessages[] = $_errMessages[(1<<$i) ];
                }
            }
            $errorMessaegString.= implode(',', $errMessages);
        }
        $paypalTransactionModel = ClassRegistry::init($this->paypal_transaction_model);
        $data['PaypalTransactionLog']['paypal_response'] = $this->paypal_response;
        $data['PaypalTransactionLog']['error_no'] = $this->errno;
        $data['PaypalTransactionLog']['error_message'] = $errorMessaegString;
        if (!($this->errno == 0)) {
            $data['PaypalTransactionLog']['paypal_post_vars'] = $this->paypal_post_vars_in_str;
        }
        $data['PaypalTransactionLog']['ip_id'] = $paypalTransactionModel->toSaveIp();
        foreach($this->paypal_post_arr as $key => $value) {
            if (in_array($key, array(
                'auth_exp',
                'transaction_entity',
                'parent_txn_id',
                'remaining_settle',
                'auth_id',
                'auth_amount',
                'pending_reason',
                'payment_gross',
                'auth_status'
            ))) {
                $key = 'authorization_' . $key;
                $data['PaypalTransactionLog']['is_authorization'] = 1;
            }
            $data['PaypalTransactionLog'][$key] = $value;
        }
        $paypalTransactionModel->save($data);
        return $paypalTransactionModel->getLastInsertId();
    }
    // PayPal Mass pay Implementation
    // It will accept Sneders Login credentials as well and the Recieves details and Amoount
    // It returns the Output
    // Input Params
    // $sender_info=array(
    //			['API_UserName']
    //			['API_Password']
    //			['API_Signature']
    // or other currency ('GBP', 'EUR', 'JPY', 'CAD', 'AUD')
    public function massPay($sender_info, $reciever_info, $notify_url = '', $email_subject = '', $test_mode = false, $currency = 'USD') 
    {
        // Set request-specific fields.
        $receiver_type = urlencode('EmailAddress');
        $API_Endpoint = ($test_mode) ? $this->masspay_url['testmode'] : $this->masspay_url['livemode'];
        $nvpStr = '';
        // Set up your API credentials, PayPal end point, and API version.
        $API_UserName = urlencode($sender_info['API_UserName']);
        $API_Password = urlencode($sender_info['API_Password']);
        $API_Signature = urlencode($sender_info['API_Signature']);
        // Add request-specific fields to the request string.
        $nvpStr = "&EMAILSUBJECT=$email_subject&RECEIVERTYPE=$receiver_type&CURRENCYCODE=$currency";
        foreach($reciever_info as $i => $receiverData) {
            $receiverEmail = urlencode($receiverData['receiverEmail']);
            $amount = urlencode($receiverData['amount']);
            $uniqueID = urlencode($receiverData['uniqueID']);
            $note = urlencode($receiverData['note']);
            $notify = ($notify_url != '') ? ('notify_url=' . $notify_url) : '';
            $nvpStr.= "&L_EMAIL$i=$receiverEmail&L_Amt$i=$amount&L_UNIQUEID$i=$uniqueID&L_NOTE$i=$note&$notify";
        }
        $version = urlencode('51.0');
        // Set the curl parameters.
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $API_Endpoint);
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        // Turn off the server and peer verification (TrustManager Concept).
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        // Set the API operation, version, and API signature in the request.
        $nvpreq = "METHOD=MassPay&VERSION=$version&PWD=$API_Password&USER=$API_UserName&SIGNATURE=$API_Signature$nvpStr";
        // Set the request as a POST FIELD for curl.
        curl_setopt($ch, CURLOPT_POSTFIELDS, $nvpreq);
        // Get response from the server.
        $httpResponse = curl_exec($ch);
        if (!$httpResponse) {
            exit("Mass Pay  failed: " . curl_error($ch) . '(' . curl_errno($ch) . ')');
        }
        // Extract the response details.
        $httpResponseAr = explode("&", $httpResponse);
        $httpParsedResponseAr = array();
        foreach($httpResponseAr as $i => $value) {
            $tmpAr = explode("=", $value);
            if (sizeof($tmpAr) > 1) {
                $httpParsedResponseAr[$tmpAr[0]] = $tmpAr[1];
            }
        }
        if ((0 == sizeof($httpParsedResponseAr)) || !array_key_exists('ACK', $httpParsedResponseAr)) {
            exit("Invalid HTTP Response for POST request($nvpreq) to $API_Endpoint.");
        }
        $httpParsedResponseAr['TIMESTAMP'] = urldecode($httpParsedResponseAr['TIMESTAMP']);
        return $httpParsedResponseAr;
    }
    public function nvpHeader($sender_info) 
    {
        $API_Endpoint = ($sender_info['is_testmode']) ? $this->do_direct_pay_constants['paypal_url']['test_mode'] : $this->do_direct_pay_constants['paypal_url']['live_mode'];
        $nvpstr = '';
        // Set up your API credentials, PayPal end point, and API version.
        $API_UserName = urlencode($sender_info['API_UserName']);
        $API_Password = urlencode($sender_info['API_Password']);
        $API_Signature = urlencode($sender_info['API_Signature']);
        $getAuthModeFromConstantFile = true;
        //$getAuthModeFromConstantFile = false;
        $nvpHeader = "";
        if (!$getAuthModeFromConstantFile) {
            //$AuthMode = "3TOKEN"; //Merchant's API 3-TOKEN Credential is required to make API Call.
            //$AuthMode = "FIRSTPARTY"; //Only merchant Email is required to make EC Calls.
            $AuthMode = "THIRDPARTY"; //Partner's API Credential and Merchant Email as Subject are required.
            
        } else {
            if (!empty($API_UserName) && !empty($API_Password) && !empty($API_Signature) && !empty($this->do_direct_pay_constants['subject'])) {
                $AuthMode = "THIRDPARTY";
            } else if (!empty($API_UserName) && !empty($API_Password) && !empty($API_Signature)) {
                $AuthMode = "3TOKEN";
            } else if (!empty($this->do_direct_pay_constants['subject'])) {
                $AuthMode = "FIRSTPARTY";
            }
        }
        switch ($AuthMode) {
            case "3TOKEN":
                $nvpHeader = "&PWD=" . urlencode($API_Password) . "&USER=" . urlencode($API_UserName) . "&SIGNATURE=" . urlencode($API_Signature);
                break;

            case "FIRSTPARTY":
                $nvpHeader = "&SUBJECT=" . urlencode($this->do_direct_pay_constants['subject']);
                break;

            case "THIRDPARTY":
                $nvpHeader = "&PWD=" . urlencode($API_Password) . "&USER=" . urlencode($API_UserName) . "&SIGNATURE=" . urlencode($API_Signature) . "&SUBJECT=" . urlencode($this->do_direct_pay_constants['subject']);
                break;
        }
        return $nvpHeader;
    }
    /**
     * doDirectPayment: Submits a credit card transaction to PayPal using a DoDirectPayment request.
     * @methodName is name of API  method.
     * @nvpStr is nvp string.
     * returns an associtive array containing the response from the server.
     */
    public function doDirectPayment($post_info, $sender_info) 
    {
        /**
         * Get required parameters from the web form for the request
         */
        $paymentType = urlencode($post_info['paymentType']);
        $firstName = urlencode($post_info['firstName']);
        $lastName = urlencode($post_info['lastName']);
        $creditCardType = urlencode($post_info['creditCardType']);
        $creditCardNumber = urlencode($post_info['creditCardNumber']);
        $expDateMonth = urlencode($post_info['expDateMonth']['month']);
        // Month must be padded with leading zero
        $padDateMonth = str_pad($expDateMonth, 2, '0', STR_PAD_LEFT);
        $expDateYear = urlencode($post_info['expDateYear']['year']);
        $cvv2Number = urlencode($post_info['cvv2Number']);
        $address = urlencode($post_info['address']);
        $city = urlencode($post_info['city']);
        $state = urlencode($post_info['state']);
        $country_code = urlencode($post_info['country']);
        $zip = urlencode($post_info['zip']);
        $amount = urlencode($post_info['amount']);
        $currencyCode = urlencode($post_info['currency_code']); //Configure::read('paypal.currency_code'); //only USD allowed
        $paymentType = urlencode($post_info['paymentType']);
        /* Construct the request string that will be sent to PayPal.
        The variable $nvpstr contains all the variables and is a
        name value pair string with & as a delimiter */
        $nvpstr = "&PAYMENTACTION=$paymentType&AMT=$amount&CREDITCARDTYPE=$creditCardType&ACCT=$creditCardNumber&EXPDATE=" . $padDateMonth . $expDateYear . "&CVV2=$cvv2Number&FIRSTNAME=$firstName&LASTNAME=$lastName&STREET=$address&CITY=$city&STATE=$state" . "&ZIP=$zip&COUNTRYCODE=$country_code&CURRENCYCODE=$currencyCode";
        $nvpHeader = $this->nvpHeader($sender_info);
        $nvpstr = $nvpHeader . $nvpstr;
        /* Make the API call to PayPal, using API signature.
        The API response is stored in an associative array called $resArray */
        $resArray = $this->hash_call("doDirectPayment", $nvpstr, $sender_info);
        return $resArray;
    }
    /**
     * Sends a DoCapture NVP API request to PayPal.
     * returns an associtive array containing the response from the server.
     */
    public function doCapture($post_info, $sender_info) 
    {
        /**
         * Get required parameters from the web form for the request
         */
        $authorizationID = urlencode($post_info['authorization_id']);
        $completeCodeType = urlencode($post_info['CompleteCodeType']);
        $amount = urlencode($post_info['amount']);
        $invoiceID = urlencode($post_info['invoice_id']);
        $currency = urlencode($post_info['currency']);
        $note = urlencode($post_info['note']);
        /* Construct the request string that will be sent to PayPal.
        The variable $nvpstr contains all the variables and is a
        name value pair string with & as a delimiter */
        $nvpStr = "&AUTHORIZATIONID=$authorizationID&AMT=$amount&COMPLETETYPE=$completeCodeType&CURRENCYCODE=$currency&NOTE=$note";
        $nvpHeader = $this->nvpHeader($sender_info);
        $nvpStr = $nvpHeader . $nvpStr;
        /* Make the API call to PayPal, using API signature.
        The API response is stored in an associative array called $resArray */
        $resArray = $this->hash_call("DOCapture", $nvpStr, $sender_info);
        return $resArray;
    }
    /**
     * Sends a DoVoid NVP API request to PayPal.
     * returns an associtive array containing the response from the server.
     */
    public function doVoid($post_info, $sender_info) 
    {
        /**
         * Get required parameters from the web form for the request
         */
        $authorizationID = urlencode($post_info['authorization_id']);
        $note = urlencode($post_info['note']);
        /* Construct the request string that will be sent to PayPal.
        The variable $nvpstr contains all the variables and is a
        name value pair string with & as a delimiter */
        $nvpStr = "&AUTHORIZATIONID=$authorizationID&NOTE=$note";
        $nvpHeader = $this->nvpHeader($sender_info);
        $nvpStr = $nvpHeader . $nvpStr;
        /* Make the API call to PayPal, using API signature.
        The API response is stored in an associative array called $resArray */
        $resArray = $this->hash_call("DOVoid", $nvpStr, $sender_info);
        return $resArray;
    }
    /**
     * hash_call: Function to perform the API call to PayPal using API signature
     * @methodName is name of API  method.
     * @nvpStr is nvp string.
     * returns an associtive array containing the response from the server.
     */
    public function hash_call($methodName, $nvpStr, $sender_info) 
    {
        // Set request-specific fields.
        $API_Endpoint = ($sender_info['is_testmode']) ? $this->do_direct_pay_constants['paypal_url']['test_mode'] : $this->do_direct_pay_constants['paypal_url']['live_mode'];
        // Set up your API credentials, PayPal end point, and API version.
        $API_UserName = urlencode($sender_info['API_UserName']);
        $API_Password = urlencode($sender_info['API_Password']);
        $API_Signature = urlencode($sender_info['API_Signature']);
        //setting the curl parameters.
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $API_Endpoint);
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        //turning off the server and peer verification(TrustManager Concept).
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        //if USE_PROXY constant set to TRUE in Constants.php, then only proxy will be enabled.
        //Set proxy name to PROXY_HOST and port number to PROXY_PORT in constants.php
        if ($this->do_direct_pay_constants['use_proxy']) curl_setopt($ch, CURLOPT_PROXY, $this->do_direct_pay_constants['proxy_host'] . ":" . $this->do_direct_pay_constants['proxy_port']);
        //check if version is included in $nvpStr else include the version.
        if (strlen(str_replace('VERSION=', '', strtoupper($nvpStr))) == strlen($nvpStr)) {
            $nvpStr = "&VERSION=" . urlencode($this->do_direct_pay_constants['version']) . $nvpStr;
        }
        $nvpreq = "METHOD=" . urlencode($methodName) . $nvpStr;
        //setting the nvpreq as POST FIELD to curl
        curl_setopt($ch, CURLOPT_POSTFIELDS, $nvpreq);
        //getting response from server
        $response = curl_exec($ch);
        //convrting NVPResponse to an Associative Array
        $nvpResArray = $this->deformatNVP($response);
        $nvpReqArray = $this->deformatNVP($nvpreq);
        $_SESSION['nvpReqArray'] = $nvpReqArray;
        if (curl_errno($ch)) {
            // moving to display page to display curl errors
            $_SESSION['curl_error_no'] = curl_errno($ch);
            $_SESSION['curl_error_msg'] = curl_error($ch);
            $location = "APIError.php";
            header("Location: $location");
        } else {
            //closing the curl
            curl_close($ch);
        }
        return $nvpResArray;
    }
    /** This function will take NVPString and convert it to an Associative Array and it will decode the response.
     * It is usefull to search for a particular key and displaying arrays.
     * @nvpstr is NVPString.
     * @nvpArray is Associative Array.
     */
    public function deformatNVP($nvpstr) 
    {
        $intial = 0;
        $nvpArray = array();
        while (strlen($nvpstr)) {
            //postion of Key
            $keypos = strpos($nvpstr, '=');
            //position of value
            $valuepos = strpos($nvpstr, '&') ? strpos($nvpstr, '&') : strlen($nvpstr);
            /*getting the Key and Value values and storing in a Associative Array*/
            $keyval = substr($nvpstr, $intial, $keypos);
            $valval = substr($nvpstr, $keypos+1, $valuepos-$keypos-1);
            //decoding the respose
            $nvpArray[urldecode($keyval) ] = urldecode($valval);
            $nvpstr = substr($nvpstr, $valuepos+1, strlen($nvpstr));
        }
        return $nvpArray;
    }
}
?>
