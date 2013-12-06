<?php
class WPHubspotAnalytics {

    function WPHubspotAnalytics() 
    {
        add_action('wp_footer', array(&$this, 'hs_analytics_insert'));
    }

    //=============================================
    // Insert tracking code
    //=============================================
    function hs_analytics_insert() {
        global $current_user;
        wp_reset_query();

        get_currentuserinfo();
        $hs_settings = array();
        $hs_settings = get_option('hs_settings');

        if ( $hs_settings["hs_portal"] != "" ) 
        {
            echo "\n".'<!-- Start of Async HubSpot Analytics Code for WordPress v'.HUBSPOT_PLUGIN_VERSION.' -->'."\n";
            echo '<script type="text/javascript">'."\n";
            echo 'var _hsq = _hsq || [];'."\n";
            // Pass along the correct content-type
            if ( is_single () ) 
            {
                echo '_hsq.push(["setContentType", "blog-post"]);' . "\n";  
            } 
            else if ( is_archive () || is_search() )
            {
               
                echo '_hsq.push(["setContentType", "listing-page"]);' . "\n";
            } 
            else 
            {
                echo '_hsq.push(["setContentType", "standard-page"]);' . "\n";
            }

            // Identify the current user if logged in
            if ($current_user->user_email) {
                echo "_hsq.push([\"identify\", {\n";
                echo "\"email\" : \"".$current_user->user_email."\",\n";
                echo "\"name\" : \"".$current_user->user_login."\",\n";
                echo "\"id\" : \"".md5($current_user->user_email)."\"\n";
                echo "}]);\n";
                stathat_ez_count('HSWP-identified-user', 1);
            } else {
                // See if current user is a commenter
                $commenter = wp_get_current_commenter();
                if ($commenter['comment_author_email']) {
                    echo "_hsq.push([\"identify\", {\n";
                    echo "\"email\" : \"".$commenter['comment_author_email']."\",\n";
                    echo "\"name\" : \"".$commenter['comment_author']."\",\n";
                    echo "\"id\" : \"".md5($commenter['comment_author_email'])."\"\n";
                    echo "}]);\n";
                    stathat_ez_count('HSWP-identified-user', 1);
                }
            }

            echo "\t".'(function(d,s,i,r) {'."\n";
            echo "\t".'if (d.getElementById(i)){return;}'."\n";
            echo "\t".'var n = d.createElement(s),e = document.getElementsByTagName(s)[0];'."\n";
            echo "\t".'n.id=i;n.src = \'//js.hs-analytics.net/analytics/\'+(Math.ceil(new Date()/r)*r)+\'/'.trim($hs_settings["hs_portal"]).'.js\';'."\n";
            echo "\t".'e.parentNode.insertBefore(n, e);'."\n";
            echo "\t".'})(document, "script", "hs-analytics",300000);'."\n";
            echo '</script>'."\n";
            echo '<!-- End of Async HubSpot Analytics Code -->'."\n";
        }
    }
}
?>
