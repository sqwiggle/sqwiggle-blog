<?php
class WPHubspotContact {

    function WPHubspotContact() {
        if (is_admin ()) {
            //
        } else {
            // Contact Page Hooks
            add_action('wp_footer', array(&$this, 'hs_contact_scripts'));
            add_action('wp_print_scripts', array(&$this, 'hs_contact_scripts'));
            add_action('wp_print_styles', array(&$this, 'hs_contact_style'));
            add_shortcode('hs_contact', array(&$this, 'hs_create_contact_shortcode'));
        }
    }

    //=============================================
    // Add stylesheet to header
    //=============================================
    function hs_contact_style() {
        wp_enqueue_style('hubspot', HUBSPOT_URL . 'css/hubspot.css');
    }

    //=============================================
    //Add javascript to header
    //=============================================
    function hs_contact_scripts() {
        wp_enqueue_script('google-maps', 'http://maps.google.com/maps/api/js?sensor=false');
    }

    //=============================================
    // Add shortcode
    //=============================================
    function hs_create_contact_shortcode($atts) {
        extract(shortcode_atts(array(
                    'display' => 'both',
                    'name' => null,
                    'address' => null,
                    'citystate' => null,
                    'phone' => null
                        ), $atts));
        $hs_content = $this->hs_get_contact_info($display, $name, $address, $citystate, $phone);
        
        // Check for nested shortcodes
        $hs_content = do_shortcode($hs_content);
                
        return $hs_content;
    }

    //=============================================
    // Display contact info
    //=============================================
    function hs_get_contact_info($display, $name, $address, $citystate, $phone) {
        $content = "";
        if ($display == 'address' || $display == 'both') {
            if ($name != "") {
                $content .= $name;
            }
            if ($address != "") {
                if ($content != "") {
                    $content .= "<br />";
                };
                $content .= $address;
            }
            if ($citystate != "") {
                if ($content != "") {
                    $content .= "<br />";
                };
                $content .= $citystate;
            }
            if ($phone != "") {
                if ($content != "") {
                    $content .= "<br />";
                };
                $content .= $phone;
            }
        }

        if ($content != ""){
            $content = "<p>".$content."</p>";
        }

        // Call maps function
        if ($display == 'map' || $display == 'both') {
            $content .= $this->hs_get_map($name, $address, $citystate);
        }
        return $content;
    }

    //=============================================
    // Turn address into Google Maps
    // Reference: http://code.google.com/apis/maps/documentation/javascript/tutorial.html#LoadingMap
    //=============================================
    function hs_get_map($hs_company_name, $hs_company_address, $hs_company_citystate) {
        $mapscript = "";
        $addressquery = "";

        if ($hs_company_name != "") {
            $addressquery .= $hs_company_name;
        }
        if ($hs_company_address != "") {
            if ($addressquery != "") {
                $addressquery .= " ";
            };
            $addressquery .= $hs_company_address;
        }
        if ($hs_company_citystate != "") {
            if ($addressquery != "") {
                $addressquery .= " ";
            };
            $addressquery .= $hs_company_citystate;
        }

        $mapscript = '<iframe width="425" height="350" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="http://maps.google.com/maps?hl=en&amp;q=' . urlencode($addressquery) . '&amp;t=h&amp;z=14&amp;iwloc=A&amp;output=embed"></iframe>
        <br /><small><a href="http://maps.google.com/maps?hl=en&amp;q=' . urlencode($addressquery) . '&amp;t=h&amp;z=14&amp;iwloc=A&amp;" style="color:#0000FF;text-align:left">View Larger Map</a></small>';
        return $mapscript;
    }

}
?>