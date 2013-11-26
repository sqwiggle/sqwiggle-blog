<?php
class WPHubspotTracking {
    var $subscribers_tracking_added = false;
    var $portal_id = false;
    var $posts_to_send = array();
    var $posts_per_batch = 100;
    var $posts_queue = false;
    function WPHubspotTracking($posts_per_batch) {
        $this->posts_per_batch = $posts_per_batch;
        $this->posts_queue = get_option('hs_posts_queue');
        
        // Is Feed
        add_action('wp', array($this, 'apply_filters'));
        
        if (is_admin() && WPHubspotOauth::getAccessToken()) {
            // Data aggregation after setup or Portal ID changing
            add_action('admin_footer', array($this, 'hs_setup_aggregation'), 99, 1);

            // Checking for posts in queue
            add_action('admin_footer', array($this, 'hs_posts_queue'), 99, 1);
        
            // publishing, updating posts
            add_action('publish_post', array($this, 'hs_update_post_aggregate_data'), 99, 1);

            // publishing comments
            add_action('wp_set_comment_status', array($this, 'hs_update_post_aggregate_data_on_comment'), 99, 1);
            add_action('comment_post', array($this, 'hs_update_post_aggregate_data_on_comment'), 99, 1);
            // removing comments
            add_action('deleted_comment', array($this, 'hs_update_post_aggregate_data_on_comment'), 99);
            add_action('trashed_comment', array($this, 'hs_update_post_aggregate_data_on_comment'), 99);
        }
    }
    
    //=============================================
    // Apply filters
    //=============================================
    function apply_filters() {
        if (is_feed()) {
            $hs_settings = get_option('hs_settings');
            if ($hs_settings !== false && !empty($hs_settings['hs_portal'])) {
                $this->portal_id = $hs_settings['hs_portal'];
                add_filter('the_content_feed', array($this, 'hs_add_rss_readers_tracking') );
                add_filter('the_excerpt_rss', array($this, 'hs_add_rss_readers_tracking') );
                if (!is_category() && !is_comment_feed()) {
                    add_filter('the_content_feed', array($this, 'hs_add_rss_subscribers_tracking') );
                }
            }
        }
    }
    
    //=============================================
    // Add RSS Readers tracking
    //=============================================

    
    function hs_add_rss_readers_tracking($content) {
        $page_for_posts = (int)get_option('page_for_posts');
        if ($page_for_posts > 0) {
            $blog_url = get_permalink($page_for_posts);
        } else {
            $blog_url = get_bloginfo('url');
        }
        $blog_url = urlencode($blog_url);
        $content .= '<img src="http://track.hubspot.com/__ptq.gif?a=' . $this->portal_id . 
                '&k=14&bu='. $blog_url . '&r=' . urlencode(get_permalink()) . 
                '&bvt=rss&p=wordpress" style="float:left;" xml:base="' . get_bloginfo('rss2_url') . 
                '" width="1" height="1" border="0" align="right"/>';
        return $content;
    }
    
    //=============================================
    // Add RSS Subscribers tracking
    //=============================================
    function hs_add_rss_subscribers_tracking($content) {
        if (!$this->subscribers_tracking_added) {
            $this->subscribers_tracking_added = true;
            $user_agent = urlencode($_SERVER['HTTP_USER_AGENT']);
            $user_ip = urlencode($_SERVER['REMOTE_ADDR']);
            $page_for_posts = (int)get_option('page_for_posts');
            if ($page_for_posts > 0) {
                $blog_url = get_permalink($page_for_posts);
            } else {
                $blog_url = get_bloginfo('url');
            }
            $feed_url = urlencode($blog_url . $_SERVER['REQUEST_URI']);
            $blog_url = urlencode($blog_url);
            $url = "https://track.hubspot.com/v1/rss?_ip=$user_ip&_bu=$blog_url&_ua=$user_agent&_a={$this->portal_id}&_r=$feed_url";
            $args = array(
                'method' => 'GET'
            );
            $result = wp_remote_post($url, $args);
        }
        return $content;
    }
    
    //=============================================
    // Update post data
    //=============================================
    function hs_update_post_aggregate_data($post_id) {
        $this->hs_add_post_to_batch($post_id);
        $result = $this->hs_process_batch_request();
        if (!$result) {
            $this->posts_queue[$post_id] = 'f';
            update_option('hs_posts_queue', $this->posts_queue);
        }
    }
    
    //=============================================
    // Update post data when new comment was added
    //=============================================
    function hs_update_post_aggregate_data_on_comment($comment_id) {
        $comment = get_comment($comment_id);
        $this->hs_update_post_aggregate_data($comment->comment_post_ID);
    }
    
    //=============================================
    // Update all aggregate data of all the posts in a batch
    //=============================================
    function hs_batch_update($last_page = 0) {
        $q = new WP_Query(array(
            'post_type' => 'any',
            'post_status' => 'publish',
            'posts_per_page' => $this->posts_per_batch,
            'paged' => ($last_page + 1),
            'orderby' => 'ID',
            'order' => 'ASC'
        ));
        if (count($q->posts) > 0) {
            foreach ($q->posts as $post) {
                $author = get_userdata($post->post_author);
                if (!$author)
                    continue;
                
                $author_name = (!empty($author->display_name)) ? $author->display_name : $author->user_login;
                
                $post_time = intval(strtotime($post->post_date));
                $post_time .= '000';
                
                $this->posts_to_send[] = array (
                    'url' => get_permalink($post->ID),
                    'commentCount' => $this->hs_count_only_comments($post->ID),
                    'author' => $author_name,
                    'publishDate' => $post_time
                );
            }
            $result = $this->hs_process_batch_request();
            if (!$result) {
                return false;
            }
            $last_page++;
            update_option('hs_tracking_setup', array('last_page' => $last_page));
        } else {
            update_option('hs_tracking_setup', 'done');
        }
    }
    
    //=============================================
    // Process batch request
    //=============================================
    function hs_process_batch_request() {
        $accessToken = WPHubspotOauth::getAccessToken();
        $url = 'https://api.hubapi.com/externalblogs/v1/blog-posts?access_token=' . $accessToken;
        $body = 'blogUrl=' . urlencode(site_url()) . '&platform=' . urlencode('WordPress Plugin v'.HUBSPOT_PLUGIN_VERSION);
        foreach ($this->posts_to_send as $post) {
            foreach ($post as $key => $value) {
                $body .= "&$key=" . urlencode($value);
            }
        }
        $args = array(
            'body' => $body
        );
        $result = wp_remote_post($url, $args);
        if( is_wp_error( $result ) ) {
            $error_message = $result->get_error_message();
            WPHubspotLogging::log("hsWordpress: error sending blog metadata" . $error_message);
        } 
        $response_code = wp_remote_retrieve_response_code($result);
        if ($response_code == '') {
            WPHubspotLogging::log("hsWordpress: error sending blog metadata, response code was blank");
            $this->posts_to_send = array();
            return false;
        }
        if ($response_code == 401) {
            $this->posts_to_send = array();
            WPHubspotOauth::emptyAccessToken();
            WPHubspotLogging::log("hsWordpress: error sending blog metadata. Response code was 401, clearing access token");
            return false;
        }
        if ($response_code == 400 or $response_code == 404 or $response_code == 500) {
            $this->posts_to_send = array();
            WPHubspotLogging::log("hsWordpress: error sending blog metadata. Response code" . $response_code);
            return false;
        }
        
        $this->posts_to_send = array();
        
        return true;
    }
    
    //=============================================
    // Add post to batch
    //=============================================
    function hs_add_post_to_batch($post_id) {
        $post = get_post($post_id);
        $author = get_userdata($post->post_author);
        $author_name = (!empty($author->display_name)) ? $author->display_name : $author->user_login;
        
        $post_time = intval(strtotime($post->post_date));
        $post_time .= '000';
        
        $this->posts_to_send[] = array(
            'url' => get_permalink($post->ID),
            'commentCount' =>  $this->hs_count_only_comments($post->ID),
            'author' => $author_name,
            'publishDate' => $post_time
        );
    }
    
    
    //====================================================
    // Data aggregation after setup or Portal ID changing
    //====================================================
    function hs_setup_aggregation() {
        $hs_tracking_setup = get_option('hs_tracking_setup');
        $hs_settings = get_option('hs_settings');
        if ($hs_tracking_setup 
                and $hs_settings 
                and is_array($hs_settings) 
                and array_key_exists('hs_portal', $hs_settings)) {
            if ($hs_tracking_setup === 'setup') {
                // first batch to send
                $this->hs_batch_update();
            } elseif (is_array($hs_tracking_setup)) {
                if (array_key_exists('last_page', $hs_tracking_setup)) {
                    // next batch to send
                    $this->hs_batch_update($hs_tracking_setup['last_page']);
                }
            }
        }
    }
    
    //====================================================
    // Checking for posts in queue
    //====================================================
    function hs_posts_queue() {
        if (is_array($this->posts_queue)) {
            foreach ($this->posts_queue as $key => $value) {
                $this->hs_add_post_to_batch($key);
            }
            $result = $this->hs_process_batch_request();
            if ($result) {
                update_option('hs_posts_queue', '');
            }
        }
    }
    
    //====================================================
    // Get count of all approved comments that are not trackbacks or ping backs
    //====================================================    
    function hs_count_only_comments($post_id) {
        $count = 0;
        $comment_array = get_approved_comments($post_id);
        foreach($comment_array as $comment){
            if ($comment->comment_type == ''){
                $count++;
            }
        }
        return $count;
    }
}