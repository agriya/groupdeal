<?php
class CurlClient
{
    /**
     * The Application User.
     */
    protected $appUser;
    /**
     * The Application Password.
     */
    protected $apiPasswd;
    /**
     * Maps aliases to Phonegap apps domains.
     */
    public static $DOMAIN_MAP = array(
        'api_url' => 'https://go.urbanairship.com/api'
    );
    /**
     * Default options for curl.
     */
    public static $CURL_OPTS = array(
        CURLOPT_CONNECTTIMEOUT => 10,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 60,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => 2,
        CURLOPT_FOLLOWLOCATION => true,
    );
    public function __construct($config)
    {
        $this->setAppUser($config['user']);
        $this->setApiPasswd($config['passwd']);
    }
    /**
     * Set the Application User.
     *
     * @param String $appUser the Application User
     */
    public function setAppUser($appUser)
    {
        $this->appUser = $appUser;
        return $this;
    }
    /**
     * Get the Application User.
     *
     * @return String the Application User
     */
    public function getsetAppUser()
    {
        return $this->appUser;
    }
    /**
     * Set the API Passwd.
     *
     * @param String $Passwd the API Passwd
     */
    public function setApiPasswd($apiPasswd)
    {
        $this->apiPasswd = $apiPasswd;
        return $this;
    }
    /**
     * Get the API Passwd.
     *
     * @return String the API Passwd
     */
    public function getApiPasswd()
    {
        return $this->apiPasswd;
    }
    public function processUrban($url, $params, $type = 'post', $content_type = '')
    {
        //$url = self::$DOMAIN_MAP['api_url'].'apps';
        if ($type == 'put') {
            $response = $this->_put($url, $params, $content_type);
            return $this->_parseReturn($response);
        } elseif ($type == 'post') {
            $response = $this->_post($url, $params, $content_type);
            return $this->_parseReturn($response);
        }
        return $this->_post($url, $params);
    }
    protected function _parseReturn($response)
    {
        if (!empty($response)) {
            $return = array();
            $json_res = json_decode($response);
            if (is_object($json_res)) {
                $return['is_error'] = 1;
                $return['error_code'] = $json_res->{"error_code"};
                $return['error'] = $json_res->{"error"};
                $return['error_detail'] = json_encode($json_res->{'details'});
            } else {
                $return['is_success'] = 1;
                $return['success_message'] = $response;
            }
            return $return;
        }
    }
    /**
     * Makes an HTTP delete request.
     * @return String the response text
     */
    protected function _delete($url, $params)
    {
        $opts = self::$CURL_OPTS;
        $opts[CURLOPT_CUSTOMREQUEST] = 'DELETE';
        $opts[CURLOPT_URL] = $url;
        $opts[CURLOPT_USERPWD] = $this->getsetAppUser() . ':' . $this->getApiPasswd();
        $ch = curl_init();
        curl_setopt_array($ch, $opts);
        $result = curl_exec($ch);
        if ($result === false) {
            $e = new PhoneGapApiException(array(
                'error_code' => curl_errno($ch) ,
                'error' => array(
                    'message' => curl_error($ch) ,
                    'type' => 'CurlException',
                ) ,
            ));
            curl_close($ch);
            throw $e;
        }
        curl_close($ch);
        return $result;
    }
    /**
     * Makes an HTTP post request.
     * @return String the response text
     */
    protected function _post($url, $params, $content_type)
    {
        $opts = self::$CURL_OPTS;
        $opts[CURLOPT_POST] = 1;
        $opts[CURLOPT_POSTFIELDS] = $params;
        $opts[CURLOPT_URL] = $url;
        if (!empty($content_type)) {
            $opts[CURLOPT_HTTPHEADER] = array(
                "Content-Type: " . $content_type
            );
        }
        $opts[CURLOPT_USERPWD] = $this->getsetAppUser() . ':' . $this->getApiPasswd();
        $ch = curl_init();
        curl_setopt_array($ch, $opts);
        $result = curl_exec($ch);
        if ($result === false) {
            $e = array(
                'error_code' => curl_errno($ch) ,
                'error' => array(
                    'message' => curl_error($ch) ,
                    'type' => 'CurlException',
                ) ,
            );
            curl_close($ch);
            return $e;
        }
        curl_close($ch);
        return $result;
    }
    /**
     * Makes an HTTP PUT request.
     * @return String the response text
     */
    protected function _put($url, $params, $content_type)
    {
        $opts = self::$CURL_OPTS;
        $opts[CURLOPT_CUSTOMREQUEST] = 'PUT';
        if (is_array($params)) {
            $post_url = http_build_query($params);
        } else {
            $post_url = $params;
        }
        $opts[CURLOPT_POSTFIELDS] = $post_url;
        if (!empty($content_type)) {
            $opts[CURLOPT_HTTPHEADER] = array(
                "Content-Type: " . $content_type
            );
        }
        $opts[CURLOPT_URL] = $url;
        $opts[CURLOPT_USERPWD] = $this->getsetAppUser() . ':' . $this->getApiPasswd();
        $ch = curl_init();
        curl_setopt_array($ch, $opts);
        $result = curl_exec($ch);
        if ($result === false) {
            $e = array(
                'error_code' => curl_errno($ch) ,
                'error' => array(
                    'message' => curl_error($ch) ,
                    'type' => 'CurlException',
                ) ,
            );
            curl_close($ch);
            return $e;
        }
        curl_close($ch);
        return $result;
    }
    /**
     * Makes an HTTP get request.
     * @return String the response text
     */
    protected function _get($url)
    {
        $opts = self::$CURL_OPTS;
        $opts[CURLOPT_URL] = $url;
        $opts[CURLOPT_USERPWD] = $this->getsetAppUser() . ':' . $this->getApiPasswd();
        $ch = curl_init();
        curl_setopt_array($ch, $opts);
        $result = curl_exec($ch);
        if ($result === false) {
            $e = array(
                'error_code' => curl_errno($ch) ,
                'error' => array(
                    'message' => curl_error($ch) ,
                    'type' => 'CurlException',
                ) ,
            );
            curl_close($ch);
            return $e;
        }
        curl_close($ch);
        return $result;
    }
}
?>