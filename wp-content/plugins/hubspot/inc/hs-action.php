<?php
class WPHubspotAction {
    
    function WPHubspotAction() {
        if($this->hs_actions_enabled()){
          if(is_admin()){
              add_action('init', array(&$this, 'hs_action_admin'));
              add_filter('post_updated_messages', array(&$this, 'hs_updated_messages'));
          }
          if($this->hs_actions_stats_enabled()){
              if(is_admin()){
                  add_filter('manage_edit-hs-action_sortable_columns', array(&$this, 'hs_column_register_sortable'));
                  add_filter('posts_orderby', array(&$this, 'hs_column_orderby'), 10, 2);
                  add_action("manage_posts_custom_column", array(&$this, "hs_column"));
                  add_filter("manage_edit-hs-action_columns", array(&$this, "hs_columns"));
              } else {
                  add_action('init', array(&$this, 'hs_do_redirect'), 11);
              }
          }
          add_shortcode( 'hs_action', array(&$this, 'hs_create_shortcode') );
        }
    }
    //=============================================
    // Create Action Custom post Type
    //=============================================
    function hs_action_admin() 
    {
      $labels = array(
        'name' => __('Call to Action'),
        'singular_name' => __('Calls to Action'),
        'add_new' => __('Add New'),
        'add_new_item' => __('Add New Call to Action'),
        'edit_item' => __('Edit Call to Action'),
        'new_item' => __('New Call to Action'),
        'view_item' => __('View Call to Action'),
        'search_items' => __('Search Calls to Action'),
        'not_found' =>  __('No Calls to Action found'),
        'not_found_in_trash' => __('No Calls to Action found in Trash'), 
        'parent_item_colon' => '',
        'menu_name' => __('Calls to Action')
    
      );
      $args = array(
        'labels' => $labels,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true, 
        'show_in_menu' => true, 
        'query_var' => false,
        'rewrite' => true,
        'menu_icon' => HUBSPOT_URL.'images/hubspot-logo.png',
        'capability_type' => 'post',
        'has_archive' => true, 
        'hierarchical' => false,
        'menu_position' => null,
        'supports' => array('title','editor','author')
      ); 
      
        register_post_type('hs-action',$args);
    
    }
    
    //=============================================
    // Create messages for the action post type
    //=============================================
    function hs_updated_messages( $messages ) {
      global $post, $post_ID;
    
      $messages['hs-action'] = array(
        0 => '', // Unused. Messages start at index 1.
        1 => __('Action updated. Now use it in your sidebar or in one of your posts/pages.'),
        2 => __('Custom field updated.'),
        3 => __('Custom field deleted.'),
        4 => __('Action updated.'),
        /* translators: %s: date and time of the revision */
        5 => isset($_GET['revision']) ? sprintf( __('Action restored to revision from %s'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
        6 => __('Action created. Now use it in your sidebar or in one of your posts/pages.' ),
        7 => __('Action saved.'),
        8 => __('Action submitted.'),
        9 => sprintf( __('Action scheduled for: <strong>%1$s</strong>.'),
          // translators: Publish box date format, see http://php.net/date
          date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ) ),
        10 => __('Action draft updated.'),
      );
    
      return $messages;
    }
    
    //=============================================
    // Add new columns to action post type
    //=============================================
    function hs_columns($columns)
    {
        $columns = array(
            "cb" => "<input type=\"checkbox\" />",
                        "ID" => "ID",
            "title" => "Action Title",
            "impressions" => "Impressions",
            "clicks" => "Clicks",
            "ctr" => "CTR",
            "author" => "Author",
            "date" => "Date"
        );
        return $columns;
    }
    
    //=============================================
    // Add data to new columns of action post type
    //=============================================
    function hs_column($column)
    {
        global $post;
        $hs_impressions = $this->hs_get_impressions($post->ID);
        $hs_clicks = $this->hs_get_clicks($post->ID);
        if ($hs_clicks>0){                 
            $hs_ctr = round(($hs_clicks/$hs_impressions)*100, 2) . "%";
        } else {
            $hs_ctr = "0%";
        }
        if ("ID" == $column) echo $post->ID;
        elseif ("impressions" == $column) echo $hs_impressions;
        elseif ("clicks" == $column)  echo $hs_clicks;
        elseif ("ctr" == $column)  echo $hs_ctr;
    }
    
    //=============================================
    // Queries to run when sorting
    // new columns of action post type
    //=============================================
    function hs_column_orderby($orderby, $wp_query) {
        global $wpdb;
     
        $wp_query->query = wp_parse_args($wp_query->query);
                // Sort Impressions
        if ( 'impressions' == @$wp_query->query['orderby'] )
            $orderby = "(SELECT CAST(meta_value as decimal) FROM $wpdb->postmeta WHERE post_id = $wpdb->posts.ID AND meta_key = 'hs_impressions') " . $wp_query->get('order');

                // Sort Clicks
        if ( 'clicks' == @$wp_query->query['orderby'] )
            $orderby = "(SELECT CAST(meta_value as decimal) FROM $wpdb->postmeta WHERE post_id = $wpdb->posts.ID AND meta_key = 'hs_clicks') " . $wp_query->get('order');

                // Sort CTR
                if ( 'ctr' == @$wp_query->query['orderby'] )
                        $orderby = "((SELECT CAST(meta_value as decimal) FROM $wpdb->postmeta WHERE post_id = $wpdb->posts.ID AND meta_key = 'hs_clicks')/(SELECT CAST(meta_value as decimal) FROM $wpdb->postmeta WHERE post_id = $wpdb->posts.ID AND meta_key = 'hs_impressions')) " . $wp_query->get('order');

                //echo $orderby;
        return $orderby;
                
    }
    
    //=============================================
    // Make new columns to action post type sortable
    //=============================================
    function hs_column_register_sortable($columns) {
                $columns['ID'] = 'ID';
        $columns['impressions'] = 'impressions';
        $columns['clicks'] = 'clicks';
                $columns['ctr'] = 'ctr';
        return $columns;
    }
    
    //=============================================
    // Display call to action
    //=============================================
    function hs_display_action($before_widget, $after_widget, $before_title, $after_title, $hide_title = false, $action_ids = null){
        global $myhubspotwp_action;
        $possible_actions = array();
        $possible_titles = array();
        $alt_actions = array();
        $alt_titles = array();
                $args = array('post_type' => 'hs-action');

                if($action_ids != null && trim($action_ids) != ""){
                    $args = wp_parse_args( array('post__in' => explode(',', $action_ids)), $args );
                }

                $queryObject = new WP_Query($args);
                // The Loop...
                if ($queryObject->have_posts()) {
                        while ($queryObject->have_posts()) {
                                $queryObject->the_post();
                                array_push($possible_actions,
                                        array(
                                            get_the_ID(),
                                            get_the_title(),
                                            wpautop(get_the_content())
                                        ));
                        }

                    //display results
                    $rand_key = array_rand($possible_actions,1);
                    $hs_id = $possible_actions[$rand_key][0];
                    $hs_title = $possible_actions[$rand_key][1];
                    $hs_content = $possible_actions[$rand_key][2];

                    if($myhubspotwp_action->hs_actions_stats_enabled()){
                            $siteurl = get_page_link();
                            $symbol = (preg_match('/\?/', $siteurl)) ? '&' : '?';
                            $hs_content = str_replace('"', '\'', $hs_content);
                            $hs_content = str_replace('href=\'http', 'href=\'' . $siteurl . $symbol . 'hs_redirect_' . $hs_id . '=http', $hs_content);
                    }
                    $content = "";

                    $content .= $before_widget;
                    if(!$hide_title){
                            $content .= $before_title . $hs_title . $after_title;
                    }
                    $content .= $hs_content;
                    $content .= $after_widget;
                    if($this->hs_actions_stats_enabled() && !$this->is_bot($_SERVER['HTTP_USER_AGENT'])){
                            $this->hs_register_impression($hs_id);
                    }
        } else {
            $content = "";
        }
                wp_reset_postdata();

        return $content;
    }
    
    //=============================================
    // Create 'Call to Action' shortcode
    //=============================================
    function hs_create_shortcode($atts) {
        global $myhubspotwp;
                extract( shortcode_atts( array(
                        'id' => null,
                ), $atts ) );
        $hs_content = do_shortcode($this->hs_display_action('', '', '', '', true, $id));
        return $hs_content;
    }
    //=============================================
    // Redirect URLs to hs_redirect value
    //=============================================
    function hs_do_redirect() {
        if ($qs = $_SERVER['REQUEST_URI']) {
            $pos = strpos($qs, 'hs_redirect');
            if (!(false === $pos)) { 
                $link = substr($qs, $pos);
                $link = str_replace('hs_redirect=', '', $link);
    
                // Extract the ID and get the link
                $pattern = '/hs_redirect_(\d+?)\=/';
                preg_match($pattern, $link, $matches);
                $link = preg_replace($pattern, '', $link);
    
                // Save click!
                //if (get_option('administer_statistics') == 'true') { 
                    $id = $matches[1];
                    if(!$this->is_bot($_SERVER['HTTP_USER_AGENT'])){
                        $this->hs_register_click($id);
                    }
                //}
    
                // Redirect
                header("HTTP/1.1 302 Temporary Redirect");
                header("Location:" . $link);
                // I'm outta here!
                exit(1);
            }
        } 
    }
    
    //=============================================
    // Retrieve number of impressions in action meta,
    // increase by 1
    //=============================================
    function hs_register_impression($id) {
        if (!is_admin()) {
            if(get_post_custom_keys($id)&&in_array('hs_impressions',get_post_custom_keys($id))){
                $hs_impressions = get_post_meta($id,'hs_impressions',true);
            }
            if (!isset($hs_impressions)){
                $hs_impressions = 0;
            }
            $hs_impressions++;
            update_post_meta($id, 'hs_impressions', $hs_impressions);
        }
    }
    
    //=============================================
    // Retrieve number of clicks in action meta,
    // increase by 1
    //=============================================
    function hs_register_click($id) {
        if (!is_admin()) {
            if(get_post_custom_keys($id)&&in_array('hs_clicks',get_post_custom_keys($id))){
                $hs_clicks = get_post_meta($id,'hs_clicks',true);
            }
            if (!isset($hs_clicks)){
                $hs_clicks = 0;
            }
            $hs_clicks++;
            update_post_meta($id, 'hs_clicks', $hs_clicks);
        }
    }
    
    //=============================================
    // Retrieve number of impressions in action meta
    //=============================================
    function hs_get_impressions($id) {
                if(get_post_custom_keys($id)&&in_array('hs_impressions',get_post_custom_keys($id))){
                        return get_post_meta($id,'hs_impressions',true);
                } else {
                   return 0;
                }
    }
    
    //=============================================
    // Retrieve number of clicks in action meta
    //=============================================
    function hs_get_clicks($id) {
        if(get_post_custom_keys($id)&&in_array('hs_clicks',get_post_custom_keys($id))){
            return get_post_meta($id,'hs_clicks',true);
        } else {
           return 0;
        }
    }
    
    //=============================================
    // Are actions disabled
    //=============================================
    function hs_actions_enabled($hs_actions_disabled = "") {
        if(trim($hs_actions_disabled) != ""){
            return false;
        } else {
            $hs_settings = get_option('hs_settings');
            if(trim($hs_settings['hs_actions_disabled']) != ""){
                return false;
            } else {
                return true;    
            }
        }
    }
    
    //=============================================
    // Are actions stats disabled
    //=============================================
    function hs_actions_stats_enabled($hs_actions_stats_disabled = "") {
        if(trim($hs_actions_stats_disabled) != ""){
            return false;
        } else {
            $hs_settings = get_option('hs_settings');
            if(trim($hs_settings['hs_actions_stats_disabled']) != ""){
                return false;
            } else {
                return true;    
            }
        }
    }
    
    //=============================================
    // Is User Agent a bot?
    //============================================= 
    function is_bot($user_agent){ 
        $bots = array("alexa","appie","Ask Jeeves","Baiduspider","bingbot","Butterfly","crawler","facebookexternalhit","FAST","Feedfetcher-Google","Firefly","froogle","Gigabot","girafabot","Googlebot","InfoSeek","inktomi","looksmart","Me.dium","Mediapartners-Google","msnbot","NationalDirectory","rabaz","Rankivabot","Scooter","Slurp","Sogou web spider","Spade","TechnoratiSnoop","TECNOSEEK","Teoma","TweetmemeBot","Twiceler","Twitturls","URL_Spider_SQL","WebAlta Crawler","WebBug","WebFindBot","www.galaxy.com","ZyBorg"); 
         
        foreach($bots as $bot){ 
            if(strpos($user_agent,$bot)!==false) 
                return true; // Is a bot 
        } 
        return false; // Not a bot 
    } 
    
}
?>