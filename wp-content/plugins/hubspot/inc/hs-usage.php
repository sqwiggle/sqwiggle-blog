<?php
class WPHubspotUsage {

    //=============================================
    // Initiate new notice with default text if no errors
    //=============================================
    function WPHubspotUsage() {
                add_action('admin_init', array(&$this, 'check_page'));
    }

    //=============================================
    // Check to see if current admin page is HubSpot plugin - ScreenID: 170
    //=============================================
        function check_page() {
            $hs_settings = array();
            $hs_settings = get_option('hs_settings');
            $appID = 42;
            $portalID = $hs_settings["hs_portal"];

            /*if(!$hs_settings['hs_email_sent']){
                $this->register_first_usage();
                $hs_settings['hs_email_sent'] = true;
                update_option('hs_settings', $hs_settings);
            }*/
            
            if(isset($_GET['page'])){
                switch($_GET['page']){
                    case 'hubspot_dashboard':
                        $action = "View HubSpot Dashboard in WordPress";
                        $screenId = 170;
                        break;
                    case 'hubspot_help':
                        $action = "View HubSpot/WordPress Plugin FAQ";
                        $screenId = 170;
                        break;
                    case 'dashboard_hs-admin.php':
                        $action = "View HubSpot Stats in WordPress";
                        $screenId = 170;
                        break;
                    case 'hubspot_settings':
                        $action = "View HubSpot/WordPress Plugin Settings";
                        $screenId = 170;
                        break;
                    case 'hubspot_shortcodes':
                        $action = "View HubSpot/WordPress Plugin Shortcode Settings";
                        $screenId = 170;
                        break;
                }
            } else {
                global $pagenow, $typenow;
                if (empty($typenow) && !empty($_GET['post'])) {
                    $post = get_post($_GET['post']);
                    $typenow = $post->post_type;
                }
                if (is_admin() && $typenow=='hs-action'){
                    switch($pagenow){
                        case 'post-new.php':
                            $action = "Add New CTA";
                            $screenId = 171;
                            break;
                        case 'post.php':
                            $action = "Edit CTA";
                            $screenId = 171;
                            break;
                         case 'edit.php':
                            $action = "View CTA Manager";
                            $screenId = 171;
                            break;
                    }
                }
            }
            if(!empty($screenId)){
                $this->register_usage($portalID, $appID, $screenId, urlencode($action), $hs_settings);
            }
        }
    //=============================================
    // Check to see if widget is updated - ScreenID: 172
    //=============================================
        function check_shortcode($action = "") {
            $hs_settings = array();
            $hs_settings = get_option('hs_settings');
            if ( $hs_settings["hs_portal"] != "" && $hs_settings["hs_appdomain"] != "" ) {
                $this->register_usage($hs_settings["hs_portal"], 42, 172, urlencode($action), $hs_settings);
            }
        }
        
    //=============================================
    // Check to see if widget is updated - ScreenID: 173
    //=============================================
        function check_widget($action = "") {
            $hs_settings = array();
            $hs_settings = get_option('hs_settings');
            if ( $hs_settings["hs_portal"] != "" && $hs_settings["hs_appdomain"] != "" ) {
                $this->register_usage($hs_settings["hs_portal"], 42, 173, urlencode($action), $hs_settings);
            }
        }
        
    //=============================================
    // Register usage
    //=============================================
        function register_usage($portalID, $appID, $screenId = 169, $action = "", $hs_settings = ""){
            if(!is_array($hs_settings)){
                $hs_settings = array();
                $hs_settings = get_option('hs_settings');
            }
            if ( $hs_settings["hs_portal"] != "" && $hs_settings["hs_appdomain"] != "" ) {
                $logging_url = 'http://usagetracking.hubapi.com/UsageTracker/addActivity';
                $logging_url .= '?portalId=' . $portalID;
                $logging_url .= '&appId=' . $appID;
                $logging_url .= '&screenId=' . $screenId;
                if($action != ""){
                    $logging_url .= '&action=' . $action;

                    // Register with StatHat
                    $replace_these = array('+', '%2F');
                    $stathat_key = 'HSWP-'.str_replace($replace_these, '-', $action);
                    stathat_ez_count("$stathat_key", 1); // Count if action is present
                }
                wp_remote_fopen($logging_url);
            }
        }

    //=============================================
    // Admin email
    //=============================================
        function register_first_usage(){
            $url = "http://hubspot.app1.hubspot.com/?app=leaddirector&FormName=WordPress+Plugin+Integration";
            $strPost = "Email=" . urlencode(get_option('admin_email'));
            $ch = @curl_init();
            @curl_setopt($ch, CURLOPT_POST, true);
            @curl_setopt($ch, CURLOPT_POSTFIELDS, $strPost);
            @curl_setopt($ch, CURLOPT_URL, $url);
            @curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            @curl_exec($ch);
            @curl_close($ch);
        }
}
?>