<?php
class WPHubspotNotice {
        
    public  $admin_notice_text = '';
    public  $default_notice_text = '';
        
    //=============================================
    // Initiate new notice with default text if no errors
    //=============================================
    function WPHubspotNotice($default_notice_text = '') {
        $this->admin_notice_text = '';
        $this->default_notice_text = $default_notice_text;
    }
    
    //=============================================
    // Add Configuration Warning
    //============================================= 
    function configuration_warning(){
        $hs_settings = get_option('hs_settings');
        if(!WPHubspot::hs_is_customer($hs_settings['hs_portal'], $hs_settings['hs_appdomain'])){
            if(!$hs_settings['hs_config_notice']){
                if(!(isset($_GET['page']) && $_GET['page'] == 'hubspot_settings')){
                    $this->admin_notice('configuration-warning');
                }
            }
        } elseif (!empty($hs_settings['hs_portal']) and !WPHubspotOauth::getAccessToken() and !$hs_settings['hs_config_notice']) {
            $this->admin_notice('configuration-warning');
        } 

        // Check SSL Connection
        $this->ssl_check();      
    }

    //=============================================
    // Test SSL Connection
    //=============================================
    function ssl_check() {
        $result = wp_remote_get("https://api.hubapi.com/contacts/v1/contact/email/testingapis@hubspot.com/profile?hapikey=demo");        
        if( is_wp_error( $result ) ) {
            $error_message = $result->get_error_message();
            $this->admin_notice("HubSpot Plugin: Error connecting to API - " . $error_message);            
        } 
        $response_code = wp_remote_retrieve_response_code($result);
        if ($response_code == '') {
            $this->admin_notice("HubSpot Plugin: Error connecting to API - Response code was blank");                        
        }
        if ($response_code != 200) {
            $this->admin_notice("HubSpot Plugin: Error connecting to API - " . $response_code);                        
        }                
    }
    
    //=============================================
    // Display notice
    //============================================= 
    function admin_notice($notice = 'default-error', $fadetime = 0) {
        $notice_text = "";
        
        switch ($notice){
            case 'main-settings-update':
                $notice_text = "HubSpot settings updated.";
                break;
            case 'shortcode-settings-update':
                $notice_text = "HubSpot shortcode settings updated.";
                break;
            case 'ssl-check':
                $notice_text = "Your server is not configured to make SSL calls. Please visit <a target='_blank' href='http://help.hubspot.com/articles/How_To_Doc/how-to-troubleshoot-the-wordpress-plugin'>How To Troubleshoot the Wordpress Plugin</a> to learn more.";
                break;
            case 'configuration-warning':
                $notice_text = "Please go to the <a href='".HUBSPOT_ADMIN."admin.php?page=hubspot_settings'>HubSpot settings page</a> to insert your Hub ID and authenticate with HubSpot to begin collecting website statistics or to hide this warning.";
                break;
            case 'authorize-fail':
                $notice_text = "HubSpot authorization failed. Please go to the <a href='".HUBSPOT_ADMIN."admin.php?page=hubspot_settings'>HubSpot settings page</a> and authorize with HubSpot";
                break;
            case 'default-error':
                $notice_text = "An error occurred, please try again or contact support.";
                break;
            default:
                $notice_text = $notice;
                break;
        }
        
        echo "<div id=\"msg-" . $notice . "\" class=\"updated fade\"><p>" . $notice_text . "</p></div>\n";
        if ($fadetime != 0){
            echo "<script type=\"text/javascript\">setTimeout(function(){jQuery('#msg-" . $notice . "').hide('slow');}, " . $fadetime * 1000 . ");</script>";   
        }
    }
        
    //=============================================
    // Add new notice to instance
    //=============================================
    function add_notice($new_admin_notice_text){
        if(trim($this->admin_notice_text)!=''){
            $this->admin_notice_text .= '<br />';
        }
        $this->admin_notice_text .= $new_admin_notice_text;
    }
        
    //=============================================
    // Display current state of notice
    //=============================================
    function display_notice($fadetime = 0){
        if(trim($this->admin_notice_text)!=''){
            $this->admin_notice($this->admin_notice_text, $fadetime);
        } else {
            $this->admin_notice($this->default_notice_text, $fadetime);
        }
    }
}
?>