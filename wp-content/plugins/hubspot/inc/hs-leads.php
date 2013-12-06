<?php
class WPHubspotLeads {
    
    function WPHubspotLeads() {
        if(is_admin()){
            //
        } else {
            if($this->hs_leads_enabled()){
                add_shortcode('hs_form', array(&$this, 'hs_create_form_shortcode'));
            }
        }
    }
    
    //=============================================
    // Send lead to hubspot
    //============================================= 
    function hs_insert_lead($hs_settings){
        if ($_SERVER['REQUEST_METHOD'] == "POST"){
            if ( !empty($_POST['hs_form_submitted']) ){
                /*******************************
                your existing form processing
                ********************************/
                //START HubSpot Lead Submission
                $strPost = "";
                //create string with form POST data
                $strPost = ""
                . "FullName=" . urlencode($_POST['full_name'])
                . "&Email=" . urlencode($_POST['email'])
                . "&TwitterHandle=" . urlencode($_POST['twitter_handle'])
                . "&Message=" . urlencode($_POST['message'])
                . "&IPAddress=" . urlencode($_SERVER['REMOTE_ADDR'])
                . "&UserToken=" . urlencode($_COOKIE['hubspotutk']);
                //set POST URL
                //$url = $hs_settings['hs_leasapi']
                $url = $hs_settings['hs_leads_url'];
                //intialize cURL and send POST data
                $ch = @curl_init();
                @curl_setopt($ch, CURLOPT_POST, true);
                @curl_setopt($ch, CURLOPT_POSTFIELDS, $strPost);
                @curl_setopt($ch, CURLOPT_URL, $url);
                @curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                @curl_exec($ch);
                @curl_close($ch);
                //END HubSpot Lead Submission
                echo "Message Sent";
            }
        }
    }
    
    //=============================================
    // Add shortcode
    //=============================================
    function hs_create_form_shortcode($atts) {
        global $myhubspotwp;
        
        extract(shortcode_atts(array(
            "id" => -1
        ), $atts));
        
        $hs_content = $myhubspotwp->hs_format_text($this->hs_get_form($id));
                
                // Check for nested shortcodes
                $hs_content = do_shortcode($hs_content);
                
        return $hs_content;
    }

    //=============================================
    // Display lead form
    //=============================================
    function hs_get_form($id){
        $hs_settings = array();

        $hs_settings = get_option('hs_settings');
        $content = "";
        
        if($id > -1){
            $content .= "<div id='hs_custom_form'>";
            $content .= html_entity_decode(get_option('hs_form_settings_' . $id));
            $content .= "</div>";
        } else {
            // Insert Lead if post data exists
            $this->hs_insert_lead($hs_settings);
            
            $ip=$_SERVER['REMOTE_ADDR'];
    
            $content .= "<form action='' method='post'>";
            $content .= "<p>Your Name <span style='color: red'> *</span><br />";
            $content .= "<input type='Text' name='full_name'  id='your_full_name' value='' size='40' /><div class='fieldclear'></div>";
            $content .= "</p>";
            $content .= "<p>Your Email <span style='color: red'> *</span><br />";
            $content .= "<input type='Text' name='email' id='your_email' value='' size='40' /><div class='fieldclear'></div>";
            $content .= "</p>";
            $content .= "<p>Your Twitter<br />";
            $content .= "<input type='Text' name='twitter_handle' id='twitter_handle' value='' size='40' /><div class='fieldclear'></div>";
            $content .= "</p>";
            $content .= "<p>Subject<br />";
            $content .= "<textarea type='Text' name='message' id=your-'message' cols='40' rows='10'></textarea><div class='fieldclear'></div>";
            $content .= "</p>";
            $content .= "<p><input type='submit' name='hs_form_submitted' value='Submit'></p>";
            $content .= "</tr>";
            $content .= "</table>";
        }
        
        return $content;
    }
    
    //=============================================
    // Are forms disabled
    //=============================================
    function hs_leads_enabled($hs_leads_enabled = "") {
        if(trim($hs_leads_enabled) != ""){
            return true;
        } else {
            $hs_settings = get_option('hs_settings');
            if(trim($hs_settings['hs_leads_enabled']) != ""){
                return true;
            } else {
                return false;   
            }
        }
    }
}
?>