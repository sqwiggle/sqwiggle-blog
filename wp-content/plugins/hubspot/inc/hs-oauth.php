<?php
class WPHubspotOauth {
    
    public static $clientId;
    private static $_object = false;
    private $_accessToken = false;
    
    private function __construct() {
        if ($this->retrieveAccessToken())
            return $this;
        
        return false;
    }
    
    public static function getAccessToken() {
        if (!self::$_object) {
            self::$_object = new WPHubspotOauth();
        }

        return self::$_object->_accessToken;
    }
    
    public static function emptyAccessToken() {
        self::$_object = false;
    }
    
    private function retrieveAccessToken() {
        $access_token = get_option('hs_access_token');
        if (is_array($access_token)) {
            if ($access_token['expires'] > time()) {
                $this->_accessToken = $access_token['token'];
                update_option('hs_authorized','ok');
                
                return true;
            }
            $result = $this->refreshAccessToken();
            
            return $result;
        }
        
        return false;
    }
       
    private function refreshAccessToken() {
        $base_url = "https://api.hubapi.com/auth/v1/refresh";
        $refresh_token = get_option('hs_refresh_token');
        $params = array(
            'method' => 'POST',
            // post data
            'body'   => array(
                            'refresh_token' => $refresh_token,
                            'client_id'     => self::$clientId,
                            'grant_type'    => 'refresh_token'
                        )
        );
        
        $result = wp_remote_post($base_url, $params);

        if ( is_wp_error($result) ){
            $error_string = $result->get_error_message();
            WPHubspotLogging::log('hsWordpress: error refreshing access token ' . $error_string);
            update_option('hs_authorized','fail');
        } elseif ($result and $result['response']['code'] == 200) {
            WPHubspotLogging::log('hsWordpress: success refreshing access token ');
            $body = json_decode($result['body']);
            $this->_accessToken =  $body->access_token;
            $access_token = array('token' => $body->access_token, 'expires' => (time() + $body->expires_in));
            update_option('hs_access_token',$access_token);
            update_option('hs_refresh_token',$body->refresh_token);
            update_option('hs_authorized','ok');
            
            return true;
        } elseif ($result and $result['response']['code'] == 401) {
            WPHubspotLogging::log('hsWordpress: 401 code from auth service while refreshing ');
            update_option('hs_authorized','fail');
        }
        
        return false;
    }
}