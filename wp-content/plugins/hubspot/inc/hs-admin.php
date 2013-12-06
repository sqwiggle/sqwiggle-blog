<?PHP
class WPHubspotAdmin {
       
    function WPHubspotAdmin() {
        if(is_admin()){
            // Admin Hooks
            $hs_settings = get_option('hs_settings');
            
            // Only display contextual help if WP version is greater than 3.0
            if (substr(get_bloginfo('version'), 0, 3) >= '3.0') {
                add_action('contextual_help', array(&$this, 'hs_add_help_text'), 10, 3);
            }
            add_action('admin_menu', array(&$this, 'add_hs_options_subpanel'));
            //add_action('admin_head', array(&$this, 'custom_admin_style'));
            //add_action('right_now_discussion_table_end', array(&$this, 'add_leads_to_discussion'));
            add_filter('plugin_action_links_' . HUBSPOT_BASENAME, array(&$this, 'hs_plugin_settings_link'));
        }
    }
    
    //=============================================
    // Add contextual help
    //=============================================
    function hs_add_help_text($contextual_help, $screen_id, $screen) { 
        $hs_settings = get_option('hs_settings');
      //$contextual_help .= var_dump($screen); // use this to help determine $screen->id
      if ($screen_id == 'hs-action') {
        $contextual_help =
          '<p><h2>' . __('Things to remember when adding or editing an action:') . '</h2></p>' .
          '<ul>' .
          '<li>' . __('Create Urgency') . '</li>' .
          '<li>' . __('Use Numbers') . '</li>' .
          '<li>' . __('Indicate a Specific Action') . '</li>' .
          '<li>' . __('Use Images ') . '</li>' .
          '<li>' . __('Use Contrasting Colors') . '</li>' .
          '</ul>';
      } elseif ($screen_id == 'edit-hs-action'){
          
          $contextual_help =
          '<p><h2>' . __('What are Actions?') . '</h2></p>' .
          '<p>' . __('An action is a request for your reader to do something. You can use the Call to Action manager below to create calls to action. You can then use the <code>[hs_action]</code> shortcode or sidebar widget to randomly display your actions. The manager keeps track of clicks, impressions and CTR.') . '</p>' ;

      } elseif ($screen_id == 'hubspot_page_hubspot_settings') {
        $contextual_help = 
          '<p><h3>' . __('HubSpot Configuration.') . '</h3></p>' .
          '<p>' . __('Enter your HubSpot Hub ID and Application Domain to set up HubSpot Analytics. (<a href="http://www.youtube.com/watch?v=RymsL14wrcc">Watch a Video</a>)') . '</p>'  .
          '<p>' . __('Learn where to find your HubSpot Hub ID and Application Domain in the <a href="'.HUBSPOT_ADMIN.'admin.php?page=hubspot_help#help-1">Help Section</a>.') . '</p>'  .     
          '<p>' . __('You can also enter your Feedburner URL to have al links to your RSS feed redirect to your Feedburner feed. You can learn how to find your Feedburner URL in the <a href="'.HUBSPOT_ADMIN.'admin.php?page=hubspot_help#help-2">Help Section</a>.') . '</p>'  .
          '<p><h3>' . __('Call To Action Settings') . '</h3></p>' .
          '<p>' . __('These settings allow you to disable the \'Actions\' post type or to prevent the plugin from tracking clicks and impressions.') . '</p>' ;
      } elseif ($screen_id == 'toplevel_page_hubspot_dashboard') {
            $contextual_help = 
          '<p>' . __('If you have your App Domain and Hub ID filled in on the <a href="'.HUBSPOT_ADMIN.'admin.php?page=hubspot_settings">HubSpot Settings Page</a> you should be able to access you HubSpot dashboard below. Your dashboard is loaded in an iFrame so this plugin does not save any username or password information.') . '</p>' ;
       } elseif ($screen_id == 'dashboard_page_dashboard_hs-admin') {
            $contextual_help = 
          '<p>' . __('If you have your App Domain and Hub ID filled in on the <a href="'.HUBSPOT_ADMIN.'admin.php?page=hubspot_settings">HubSpot Settings Page</a> you should be able to access you HubSpot Analytics Sources below. Your stats are loaded in an iFrame so this plugin does not save any username or password information.') . '</p>' ;
      } elseif ($screen_id == 'hubspot_page_hubspot_shortcodes') {
            $contextual_help = 
          '<p><h2>What are Shortcodes?</h2></p>' .
          '<p>Shortcodes are small bits of code that make the creation of advanced HTML elements easy. The HubSpot WordPress plugin uses shortcodes to display contact info <code>[hs_contact]</code>, team info <code>[hs_team]</code>, custom forms <code>[hs_form]</code> and Calls to Action <code>[hs_action]</code> to make inserting and managing the content associated with these pages effortless.</p>' .
          '<p><h3>' . __('Contact Shortcode Settings') . '</h3></p>' .
          '<p>' . __('Fill in the Contact Shortcode Settings and use the <code>[hs_contact]</code> shortcode to display your company info with a map on any page.') . '</p>'  .
                  '<p>' . __('You can use the HubSpot button on the visual editor of any page, post or custom post type to easily insert the shortcode or to enter a custom address.') . '</p>'  .
                  '<p><h3>' . __('Team Page Shortcode Settings') . '</h3></p>' .
          '<p>' . __('Add Team Members as new users in the <a href="'.HUBSPOT_ADMIN.'users.php">Users Section</a> and use the <code>[hs_team]</code> shortcode to display a list of your team members with an image, bio and links to their social media profiles. You can choose to hide user images and the site admin from the Team Page listing on this page.') . '</p>'  .
          '<p>' . __('You can use the HubSpot button on the visual editor of any page, post or custom post type to easily insert the team page shortcode or to select which team members to display on a specific page.') . '</p>'  .
                  '<p><h3>' . __('Lead Form Shortcode Settings') . '</h3></p>' .
          '<p>' . __('HubSpot customers can paste HTML into the \'Custom Form HTML\' field from your <a href="http://'.str_replace('.app','.web',$hs_settings['hs_appdomain']).'/app/LeadGen?subpage=EditContactForm&subaction=ManageForms" target="_blank">HubSpot Form Manager</a> to use a custom form. Each time you save a custom form a new text area will appear to allow multiple forms. You can use the HubSpot button on the visual editor of any page, post or custom post type to easily insert a specific form.') . '</p>' ;
      }
      return $contextual_help;
    }
    
    //=============================================
    // Add admin options panel link
    //=============================================
    function add_hs_options_subpanel() {
        if (function_exists('add_menu_page') && current_user_can('manage_options')) {
            global $submenu, $myhubspotwp;
            add_menu_page('HubSpot', 'HubSpot', 'manage_options', 'hubspot_dashboard', array($this, 'hs_dashboard'), HUBSPOT_URL.'images/hubspot-logo.png');
            add_submenu_page('hubspot_dashboard','Settings','Settings','manage_options', 'hubspot_settings', array($this, 'hs_settings_options'));
            add_submenu_page('hubspot_dashboard','Shortcodes','Shortcodes','manage_options', 'hubspot_shortcodes', array($this, 'hs_shortcodes_options'));
            add_submenu_page('hubspot_dashboard','Help','Help','manage_options', 'hubspot_help', array($this, 'hs_help'));
            $hs_settings = get_option('hs_settings');
            
            if($myhubspotwp->hs_is_customer()){
                add_submenu_page('index.php',__('Hubspot Stats'),__('Hubspot Stats'),'manage_options','dashboard_'.basename(__FILE__),array($this, 'hs_stats'));
            }
            
            $submenu['hubspot_dashboard'][0][0] = 'Dashboard';
        }
    }
    
    //=============================================
    // Display Hubspot stats in an iFrame
    //=============================================
    function hs_stats() {
        $hs_settings = get_option('hs_settings');
        echo '  
            <div class="wrap hs_wrap" style="max-width: 1040px;">
                <h2>'.__('Hubspot Stats').'</h2>
                <iframe id="hs_dashboard" src="http://app.hubspot.com/analytics/sources?portalId='.$hs_settings["hs_portal"].'" scrolling="auto" style="width: 100%; height: 1100px; border: 1px solid #bfbfbf;"></iframe>
            </div>
            ';
    }
    
    //=============================================
    // Display the Hubspot Dashboard in an iFrame
    //=============================================
    function hs_dashboard() {
        global $myhubspotwp;
        if($myhubspotwp->hs_is_customer()){
            $hs_settings = get_option('hs_settings');   
            echo '  
                <div class="wrap hs_wrap" style="max-width: 1040px;">
                    <h2>'.__('Hubspot Dashboard').'</h2>
                    <iframe id="hs_dashboard" src="https://app.hubspot.com/dashboard-plus/'.$hs_settings["hs_portal"].'/dash/" scrolling="auto" style="width: 100%; height: 1100px; border: 1px solid #bfbfbf;"></iframe>
                </div>
                ';
        } else {
            $content = "";  
            $content .= "<p>HubSpot Customers can insert their App Domain and Hub ID on the Settings page to display their HubSpot Dashboard here.</p>";
            $content .= "<p>HubSpot Customers also benefit from:
                            <ul>
                                <li>Automatic tracking code integration</li>
                                <li>Contact form w/ HubSpot Lead API integration</li>
                            </ul>
                        </p>";
        
            $wrapped_content = $this->hs_postbox('hubspot-dashboard', 'Dashboard', $content);
            
            $this->hs_admin_wrap('HubSpot WordPress Plugin Help', $wrapped_content);
        }
    }
    
    //=============================================
    // Help page content
    //=============================================
    function hs_help(){
        $content = "";  
        $content = "<ol>
                      <li><a href='#help-1'>Where do I find my HubSpot Application Domain and HubSpot Hub ID number?</a></li>
                      <li><a href='#help-2'>Where do I find my feedburner feed?</a></li>
                      <li><a href='#help-3'>What are shortcodes?</a></li>
                      <li><a href='#help-4'>How do I insert the 'HubSpot Follow Me' buttons into my sidebar?</a></li>
                      <li><a href='#help-5'>How does the HubSpot WordPress plugin forward to my feedburner?</a></li>
                      <li><a href='#help-6'>How do I change the names displayed on my Team Page?</a></li>
                      <li><a href='#help-7'>What are Actions?</a></li>
                    </ol>
                    <p><a name='help-1'></a><h2>1) Where do I find my HubSpot Application Domain and HubSpot Hub ID number?</h2></p>
                    <p> Find your HubSpot Hub Number and Application domain, a number with between 2 and 6 digits, and a string of text that ends in hubspot.com, as shown below:<br />
                      <br />
                      <img src='".HUBSPOT_URL."/images/ExternalSiteTrafficLogging.png' alt='Hub ID' /><br />
                      <br />
                    Once you have entered your Hub ID and application domain in the box above your pages will be trackable by HubSpot Analytics.</p>
                    <p><a name='help-2'></a><h2>2) Where do I find my feedburner feed?</h2></p>
                    <p>Your feedburner feed will look something like <code>http://feeds.feedburner.com/myfeedid</code>. Go to   <a href='http://feedburner.google.com/'>http://feedburner.google.com</a> and right-click on the RSS icon next to the Feed Title and click 'Copy Link Address'. You can also just left-click on the RSS icon next to the Feed Title and copy the URL in the addressbar on the next page.</p>
                    <p><a name='help-3'></a><h2>3) What are shortcodes?</h2></p>
                    <p>Shortcodes are small bits of code that   make the creation of advanced HTML elements easy. The HubSpot WordPress plugin uses shortcodes to display contact info, <code>[hs_contact]</code>, and team info, <code>[hs_team]</code>, to make inserting and managing the content associated with these pages effortless.</p>
                    <p><a name='help-4'></a><h2>4) How do I insert the 'HubSpot Follow Me' buttons into my sidebar?</h2></p>
                    <p>Make sure you have input the proper   HubSpot Hub ID on the HubSpot WordPress plugin configuration page. You can then use the widget under Appearance-&gt;Widgets titled &quot;HubSpot: Follow Widget&quot;.</p>
                    <p><a name='help-5'></a><h2>5) How does the HubSpot WordPress plugin forward to my feedburner?</h2></p>
                    <p>The plugin forwards all requests to <code>http://www.yoursite.com/feed</code> with a 302 redirect. The plugin also replaces all links with your Feedburner address.</p>
                    <p><a name='help-6'></a><h2>6) How do I change the names displayed on my Team Page?</h2></p>
                    <p>Go to the <a href='".HUBSPOT_ADMIN."users.php'>profile page</a> of the name you want to change. Make sure you filled out your first and last name, then select the full name you would like to display for 'Display name publicly as'.</p>
                    <p><a name='help-7'></a><h2>7) What are Actions?</h2></p>
                    <p>An action is a request for your reader to do something. The HubSpot plugin comes with a <a href='".HUBSPOT_ADMIN."edit.php?post_type=hs-action'>Call to Action manager</a> that allows you to create calls to action. You can then use the <code>[hs_action]</code> shortcode or sidebar widget to randomly display your actions. The manager keeps track of clicks, impressions and CTR.</p>";

                    
    
        $wrapped_content = $this->hs_postbox('hubspot-help', 'FAQ', $content);
        
        $this->hs_admin_wrap('HubSpot WordPress Plugin Help', $wrapped_content);
    }
    
    //=============================================
    // Process contact page form data
    //=============================================
    function hs_process_shortcodes_options() {
        $myhubspotsettings_notice = new WPHubspotNotice('shortcode-settings-update');
        if ( !empty($_POST['hs_option_submitted']) ){
            $hs_settings=get_option('hs_settings');
            if($_GET['page'] == 'hubspot_shortcodes' && check_admin_referer('hubspot-shortcode-update-options')){
                if(isset($_POST['hs_company_name'])){ $hs_settings['hs_company_name']=$_POST['hs_company_name']; }
                if(isset($_POST['hs_company_address'])){ $hs_settings['hs_company_address']=$_POST['hs_company_address']; }
                if(isset($_POST['hs_company_citystate'])){ $hs_settings['hs_company_citystate']=$_POST['hs_company_citystate']; }
                if(isset($_POST['hs_company_phone'])){ $hs_settings['hs_company_phone']=$_POST['hs_company_phone']; }
                if(isset($_POST['hs_team_avatars'])){ $hs_settings['hs_team_avatars']=$_POST['hs_team_avatars']; } else { $hs_settings['hs_team_avatars']=''; }
                if(isset($_POST['hs_team_admin'])){ $hs_settings['hs_team_admin']=$_POST['hs_team_admin']; } else { $hs_settings['hs_team_admin']=''; }
                if(isset($_POST['hs_leads_enabled'])){ $hs_settings['hs_leads_enabled']=$_POST['hs_leads_enabled']; } else { $hs_settings['hs_leads_enabled']=''; }

                
                // Process all custom HTML forms (if any)
                $formid = 0;
                                $form_options = array();
                foreach($_POST['hs_leads_html'] as $form){
                    if($formid == (count($_POST['hs_leads_html']) - 1) && htmlentities(stripslashes($form)) == ""){
                        // Do Nothing
                    } else{
                        // $hs_settings['hs_leads_html'][$formid] = htmlentities(stripslashes($form));
                        array_push($form_options, htmlentities(stripslashes($form)));
                    }
                    $formid++;
                }

                                $myhubspotsettings_notice->display_notice(3);

                                $formid = 0;
                                if(!empty($form_options)){
                                    foreach($form_options as $form_option){
                                        update_option("hs_form_settings_" . $formid, $form_option);
                                        $formid++;
                                    }
                                }
                update_option("hs_settings", $hs_settings);
            }
        }//updated
    
        $hs_settings=get_option('hs_settings');
        return $hs_settings;
    }
    
    //=============================================
    // Contact page options
    //=============================================
    function hs_shortcodes_options(){
        global $myhubspotwp;
        $hs_settings = $this->hs_process_shortcodes_options();
        $contact_content = "";  
        $team_content = ""; 
        $contact_content = '<table class="form-table">';
        if ( function_exists('wp_nonce_field') ){ $contact_content .= wp_nonce_field('hubspot-shortcode-update-options','_wpnonce',true,false); }
        $contact_content .= '<tr><th scope="row"><label for="hs_company_name">' . __("Company Name") . '</label></th><td><input type="text" class="regular-text" name="hs_company_name" value="' . $hs_settings['hs_company_name'] . '" /></td></tr>';
        $contact_content .= '<tr><th scope="row"><label for="hs_company_address">' . __("Company Address") . '</label></th><td><input type="text" class="regular-text" name="hs_company_address" value="' . $hs_settings['hs_company_address'] . '" /></td></tr>';
        $contact_content .= '<tr><th scope="row"><label for="hs_company_citystate">' . __("Company City/State/Zip") . '</label></th><td><input type="text" class="regular-text" name="hs_company_citystate" value="' . $hs_settings['hs_company_citystate'] . '" /></td></tr>';
        $contact_content .= '<tr><th scope="row"><label for="hs_company_phone">' . __("Company Phone") . '</label></th><td><input type="text" class="regular-text" name="hs_company_phone" value="' . $hs_settings['hs_company_phone'] . '" /></td></tr>';
        $contact_content .= '<tr><td scope="row" colspan="2">Insert <code>[hs_contact]</code> into any page or post to display your contact information with a Google Map.</td></tr>';  
        $contact_content .= '</table>';
    
        $team_content = '<table class="form-table">';
        $team_content .= '<tr><th scope="row"><label for="hs_team_avatars">' . __("Display Avatars") . '</label></th><td><input type="checkbox" name="hs_team_avatars" ' .checked($hs_settings['hs_team_avatars'], 'on', false) . ' /></td></tr>';
        $team_content .= '<tr><th scope="row"><label for="hs_team_admin">' . __("Hide Admin") . '</label></th><td><input type="checkbox" name="hs_team_admin" ' .checked($hs_settings['hs_team_admin'], 'on', false) . ' /></td></tr>'; 
        $team_content .= '<tr><td scope="row" colspan="2">Insert <code>[hs_team]</code> into any page or post to display a list of your team members with an image, bio and links to their social media profiles. To add team members just add new WordPress users and configure their profiles.</td></tr>';    
        $team_content .= '</table>';
        
        if($myhubspotwp->hs_is_customer($hs_settings['hs_portal'], $hs_settings['hs_appdomain']) ){
            $form_content = '<table class="form-table">';
            $form_content .= '<tr><th scope="row"><label for="hs_leads_enabled">' . __("Enable Form Shortcode") . '</label></th><td><input type="checkbox" name="hs_leads_enabled" ' .checked($hs_settings['hs_leads_enabled'], 'on', false) . ' /></td></tr>'; 
            $form_content .= '<tr><th scope="row"><label for="hs_leads_html">' . __("Custom Form HTML") . '</label></th><td><small>Copy and paste HTML from your <a href="http://'.str_replace('.app','.web',$hs_settings['hs_appdomain']).'/app/LeadGen?subpage=EditContactForm&subaction=ManageForms" target="_blank">HubSpot Form Manager</a> here.</small></td></tr>';

                        $form_options = array();
                        $formid = 0;
                        while($form_option = get_option("hs_form_settings_" . $formid)){
                            $form_options[$formid] = $form_option;
                            $formid++;
                        }
            // Loop through all saved custom forms (if any) and display forms plus an empty text area for a new form
                        if(!isset($form_options) || count($form_options) <= 0){
                           $form_options = array("");
                        }
            for ($formid = 0; $formid < count($form_options); $formid++){
                if ($form_options[$formid] != ""){
                    $formcontent = $form_options[$formid];
                } else {
                    $formcontent = "";  
                }
                $form_content .= '<tr><td>[hs_form id="' . $formid . '"]</td><td><textarea name="hs_leads_html[]" rows="4" style="width:100%;">' . $formcontent . '</textarea></td></tr>';  
                if ($formid == (count($form_options)-1) && $form_options[$formid] != ""){
                    $form_content .= '<tr><td>[hs_form id="' . ($formid + 1) . '"]</td><td><textarea name="hs_leads_html[]" rows="4" style="width:100%;"></textarea></td></tr>';    
                }
            }
            $form_content .= '<tr><td scope="row" colspan="2">Insert <code>[hs_form id="#"]</code> into any page or post to display a contact form that sends leads to your HubSpot Account.</td></tr>';    
            $form_content .= '</table>';
        }
        
        $wrapped_content = $this->hs_postbox('hubspot-company-settings', 'Contact Shortcode Settings', $contact_content);
        $wrapped_content .= $this->hs_postbox('hubspot-team-settings', 'Team Page Shortcode Settings', $team_content);
        if($myhubspotwp->hs_is_customer($hs_settings['hs_portal'], $hs_settings['hs_appdomain']) ){
            $wrapped_content .= $this->hs_postbox('hubspot-team-settings', 'Lead Form Shortcode Settings', $form_content);
        }
        
        $this->hs_admin_wrap('HubSpot Shortcode Settings', $wrapped_content);
    }
    
    //=============================================
    // Process settings page form data
    //=============================================
    function hs_process_settings_options() {
        $myhubspotsettings_notice = new WPHubspotNotice('main-settings-update');
        if ( !empty($_POST['hs_option_submitted']) ){
            $hs_settings=get_option('hs_settings');
            if($_GET['page'] == 'hubspot_settings' && check_admin_referer('hubspot-dashboard-update-options')){
                if(isset($_POST['hs_portal'])){ 
                                    $hs_settings['hs_portal']=$_POST['hs_portal'];
                                }
                                // Check to be sure app domain does not have 'http' or 'www'
                if(isset($_POST['hs_appdomain'])){
                                    $hs_settings['hs_appdomain'] = $_POST['hs_appdomain'];
                                    $hs_settings['hs_appdomain']=str_replace('http://', '', $hs_settings['hs_appdomain']);
                                    $hs_settings['hs_appdomain']=str_replace('www.', '', $hs_settings['hs_appdomain']);
                                }
                                // Check to be sure feedburner URL is a valid URL
                if(isset($_POST['hs_feedburner_url'])){
                                    if(trim($_POST['hs_feedburner_url']) == '' || strpos($_POST['hs_feedburner_url'], 'feeds.feedburner.com') !== false){
                                         $hs_settings['hs_feedburner_url']=$_POST['hs_feedburner_url'];
                                    } else {
                                            $hs_settings['hs_feedburner_url']='';
                                            $myhubspotsettings_notice->add_notice('Please enter a valid Feedburner URL');
                                    }
                                }
                if(isset($_POST['hs_actions_disabled'])){ $hs_settings['hs_actions_disabled']=$_POST['hs_actions_disabled']; } else { $hs_settings['hs_actions_disabled']=''; }
                if(isset($_POST['hs_actions_stats_disabled'])){ $hs_settings['hs_actions_stats_disabled']=$_POST['hs_actions_stats_disabled']; } else { $hs_settings['hs_actions_stats_disabled']=''; }
                if(isset($_POST['hs_config_notice'])){ $hs_settings['hs_config_notice']=$_POST['hs_config_notice']; } else { $hs_settings['hs_config_notice']=''; }
                if(isset($_POST['hs_blog_autopublish'])){
                    $hs_settings['hs_blog_autopublish'] = $_POST['hs_blog_autopublish'];

                    // Store the enabled date
                    if ($hs_settings['hs_blog_autopublish'] == 'on') {
                      add_option('hs_blog_autopublish_enabled', mktime() . '000');
                    }
                } else {
                    $hs_settings['hs_blog_autopublish']='';
                }

                $myhubspotsettings_notice->display_notice(3);
                update_option("hs_settings", $hs_settings);
            }
        }//updated
        if (isset($_POST['auth-revoke'])) {
            update_option('hs_refresh_token', '');
            update_option('hs_access_token', '');
            WPHubspotOauth::emptyAccessToken();
        }
        // add/update refresh_token
        if (!empty($_GET['refresh_token']) && !empty($_GET['access_token']) && !empty($_GET['expires_in'])) {
            $access_token = array('token' => $_GET['access_token'], 'expires' => (time() + $_GET['expires_in']));
            update_option('hs_access_token',$access_token);
            update_option('hs_refresh_token',$_GET['refresh_token']);
            WPHubspotOauth::emptyAccessToken();
            $hs_tracking_setup = get_option('hs_tracking_setup');
            if (!$hs_tracking_setup) {
                add_option('hs_tracking_setup', 'setup', '', 'yes');
            } else {
                // check if hs_portal different
                $hs_settings = get_option('hs_settings');
                if ($hs_tracking_setup
                        and is_array($hs_tracking_setup)
                        and array_key_exists('hs_portal', $hs_tracking_setup)) {
                    if ($hs_tracking_setup['hs_portal'] != $hs_settings['hs_portal']) {
                        update_option('hs_tracking_setup', 'setup');
                    }
                }
            }
        }
        
        $hs_settings = get_option('hs_settings');
        $hs_authorized = get_option('hs_authorized');
        
        // Warning notice is disabled for this page so it needs to be called here. This is so updating customer info or disabling the warning has an immediate effect without having to refresh
        if(!$hs_settings['hs_config_notice'] && !WPHubspot::hs_is_customer($hs_settings['hs_portal'], $hs_settings['hs_appdomain'])){
                    $myhubspotwp_notice = new WPHubspotNotice('configuration-warning');
                    $myhubspotwp_notice->display_notice(10);
        }
        if($hs_authorized === 'fail'){
                    $myhubspotwp_notice = new WPHubspotNotice('authorize-fail');
                    $myhubspotwp_notice->display_notice(10);
        }
        
        // Authenticate url  
        if (!empty($hs_settings['hs_portal'])) {
            $access_token = WPHubspotOauth::getAccessToken();
            if ($access_token) {
                $hs_settings['hs_authorize_url'] = 'OK';
            } else {
                $blog_url = urlencode(HUBSPOT_ADMIN . 'admin.php?page=hubspot_settings');
                $hs_settings['hs_authorize_url'] = 'https://app.hubspot.com/auth/authenticate'
                               .'?client_id=' . HUBSPOT_CLIENT_ID
                               .'&portalId=' . $hs_settings['hs_portal']
                               .'&scope=leads-rw+offline'
                               .'&redirect_uri=' . $blog_url;
            }
        } else {
            $hs_settings['hs_authorize_url'] = false;
        }
        
        return $hs_settings;
    }
    
    //=============================================
    // Settings page options
    //=============================================
    function hs_settings_options(){
        $hs_settings = $this->hs_process_settings_options();
        $content = "";
        $action_content = "";
        $content = '<p>'.__('HubSpot customers can input their Hub ID and App Domain to enable analytics and other cool features').'.</p>';
        $content .= '<table class="form-table">';
        if ( function_exists('wp_nonce_field') ){ $content .= wp_nonce_field('hubspot-dashboard-update-options','_wpnonce',true,false); }
        $content .= '<tr><th scope="row"><label for="hs_portal">' . __("HubSpot Hub ID #") . '</label></th><td><input type="text" class="regular-text" name="hs_portal" value="' . $hs_settings['hs_portal'] . '" /></td></tr>';
        if ($hs_settings['hs_authorize_url']) {
            if ($hs_settings['hs_authorize_url'] === 'OK') {
                $content .= '<tr><th scope="row"><label for="authentication">' . __("Authorized with HubSpot:") . 
                        '</label></th><td><input type="submit" class="button-primary" name="auth-revoke" value="' . __('Revoke access') . '"></td></tr>';
            } else {
                $content .= '<tr><th scope="row"><label for="authentication">' . __("Please authenticate with HubSpot to provide more accurate stats:") . 
                        '</label></th><td><a href="' . $hs_settings['hs_authorize_url'] . '"><input type="button" class="button-primary" name="auth" value="' . __('Click here to authenticate') . '" /></a></td></tr>';
            }
        }
        //$content .= '<tr><th scope="row"><label for="hs_appdomain">' . __("HubSpot Application Domain") . '</label></th><td><input type="text" class="regular-text" name="hs_appdomain" value="' . $hs_settings['hs_appdomain'] . '" /></td></tr>';        
        //$content .= '<tr><th scope="row"><label for="hs_feedburner_url">' . __("FeedBurner Feed") . '</label></th><td><input type="text" class="regular-text code" name="hs_feedburner_url" value="' . $hs_settings['hs_feedburner_url'] . '" /></td></tr>';    
        $content .= '<tr><th scope="row"><label for="hs_config_notice">' . __("Disable Config Warning") . '</label></th><td><input type="checkbox" name="hs_config_notice" ' .checked($hs_settings['hs_config_notice'], 'on', false) . ' /></td></tr>';
        
        $content .= '</table>';

        $action_content = '<table class="form-table">';
        $action_content .= '<tr><th scope="row"><label for="hs_actions_disabled">' . __("Disable Actions") . '</label></th><td><input type="checkbox" name="hs_actions_disabled" ' .checked($hs_settings['hs_actions_disabled'], 'on', false) . ' /></td></tr>';
        $action_content .= '<tr><th scope="row"><label for="hs_actions_stats_disabled">' . __("Disable Impressions/Clicks Stats") . '</label></th><td><input type="checkbox" name="hs_actions_stats_disabled" ' .checked($hs_settings['hs_actions_stats_disabled'], 'on', false) . ' /></td></tr>'; 
        $action_content .= '</table>';

        $wrapped_content = $this->hs_postbox('hubspot-settings', 'HubSpot Configuration', $content);
        if (substr(get_bloginfo('version'), 0, 3) >= '3.0') {
            $wrapped_content .= $this->hs_postbox('hubspot-action-settings', 'Call To Action Settings', $action_content);
        }

        $autopublish_content = '<table class="form-table">';
        $autopublish_content .= '<tr><th scope="row"><label for="hs_blog_autopublish">' . __("Enable Blog Auto-publisher") . '</label></th><td><input type="checkbox" name="hs_blog_autopublish" ' .checked($hs_settings['hs_blog_autopublish'], 'on', false) . ' /></td></tr>';
        $autopublish_content .= '</table>';

        $wrapped_content .= $this->hs_postbox('hubspot-autopublish-settings', 'Blog Auto-publisher Settings', $autopublish_content);

        $this->hs_admin_wrap('General HubSpot Settings', $wrapped_content);
    }
    
    //=============================================
    // Add settings link to plugins page
    //=============================================
    function hs_plugin_settings_link($links) {
        $settings_link = '<a href="'.HUBSPOT_ADMIN.'admin.php?page=hubspot_settings">' . __('Settings') . '</a>';
        array_unshift( $links, $settings_link );
        return $links;
    }
    
    //=============================================
    // Display support info
    //=============================================
    function hs_show_plugin_support() {
        $content = '<p>'.__('Please check the <a href="'.HUBSPOT_ADMIN.'admin.php?page=hubspot_help">Help Section</a> first. If you have any problems with this plugin or good ideas for improvements or new features, please use the').' <a href="http://wordpress.org/tags/hubspot">WordPress Plugin Support Forum</a>.</p>';
        return $this->hs_postbox('hubspot-support', 'Need support?', $content);
    }

    function hs_show_leadin_widget() {
        $content = '<p><a href="http://leadin.com?source=plugin">
        <img src="' . HUBSPOT_URL . 'images/LeadIn_CTA.png" alt="Not a HubSpot Customer? 
        Looking for an easy way to help manage your marketing on WordPress? Try LeadIn: http://leadin.com?source=plugin"/></a>'; 
        return $this->hs_postbox('hubpot-leadin', 'Not a HubSpot customer?', $content);
    }
    
    //=============================================
    // Display HubSpot feed
    //=============================================
    function hs_show_blogfeed() {
        include_once(ABSPATH . WPINC . '/feed.php');
        $content = "";
        $maxitems = 0;
        $rss = fetch_feed("http://feeds.feedburner.com/HubSpot");
        if (!is_wp_error( $rss ) ) {
          $maxitems = $rss->get_item_quantity(3); 
          $rss_items = $rss->get_items(0, $maxitems); 
        }
        if ($maxitems == 0) {
            $content .= "<p>No Posts</p>";
        } else {
            foreach ( $rss_items as $item ) { 
                $content .= "<a href='" . $item->get_permalink(). "' title='Posted ".$item->get_date('j F Y | g:i a') ."'>" . $item->get_title() . "</a><br />- " . $item->get_date('n/j/Y') . "</p>";
            }
            $content .= "<p><a href='" . $rss->get_permalink() . "'>Go To HubSpot Blog &raquo;</a></p>";
        }
        return $this->hs_postbox('hubspot-blog-rss', 'HubSpot Blog', $content);
    }
    
    //=============================================
    // Create postbox for admin
    //============================================= 
    function hs_postbox($id, $title, $content) {
        $postbox_wrap = "";
        $postbox_wrap .= '<div id="' . $id . '" class="postbox">';
        $postbox_wrap .= '<div class="handlediv" title="Click to toggle"><br /></div>';
        $postbox_wrap .= '<h3 class="hndle"><span>' . $title . '</span></h3>';
        $postbox_wrap .= '<div class="inside">' . $content . '</div>';
        $postbox_wrap .= '</div>';
        return $postbox_wrap;
    }   
    
    //=============================================
    // Admin page wrap
    //============================================= 
    function hs_admin_wrap($title, $content) {
    ?>
        <div class="wrap">
            <div class="dashboard-widgets-wrap">
                <h2><?php echo $title; ?></h2>
                <form method="post" action="">
                    <div id="dashboard-widgets" class="metabox-holder">
                        <div class="postbox-container" style="width:60%;">
                            <div class="meta-box-sortables ui-sortable">
                            <?php
                                    echo $content;
                            ?>
                            <p class="submit">
                                    <input type="submit" name="hs_option_submitted" class="button-primary" value="Save Changes" /> 
                            </p>
                            </div>
                        </div>
                        <div class="postbox-container" style="width:40%;">
                            <div class="meta-box-sortables ui-sortable">
                           <?php
                                    echo $this->hs_show_plugin_support();
                                    echo $this->hs_show_leadin_widget();
                            ?>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    <?php
    }
 }

?>