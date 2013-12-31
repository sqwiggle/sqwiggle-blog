<?php
class WPHubspotSocial {

    var $meta_key = 'hs_social';
    var $settings_key = 'hs_blog_autopublish';
    var $api_endpoint = 'https://api.hubapi.com/broadcast/v1/blog-publish/wordpress';

    function WPHubspotSocial() {
        add_action('publish_post', array($this, 'check_and_publish_broadcast'), 99, 1);
    }

    // Publish broadcast if one has not yet been published
    function check_and_publish_broadcast($post_id) {
        $post = get_post($post_id);
        if ($this->is_valid($post)) {
            $this->publish_broadcast($post);
        }
    }

    function is_valid($post) {
        // Case: is a newly published post
        if ($this->is_new_post($post) &&
            $this->is_post_published($post)
        ) {

            // Case: autopublish enabled and has not yet been run for this post
            if ($this->is_autopublish_enabled() &&
                !$this->is_social_published($post->ID)
            ) {
                return true;

            // Case: autopublish disabled
            } else if (!$this->is_autopublish_enabled()) {
                // Fake the publish event to avoid publishing in the future
                $this->log_publish_event($post);
            }

            return false;

        } else {
            return false;
        }
    }

    function is_new_post($post) {
        if (post_timestamp($post) > get_option('hs_blog_autopublish_enabled')) {
            return true;
        } else {
            return false;
        }
    }

    function is_post_published($post) {
        if ($post->post_type == 'post' &&
            $post->post_status == 'publish' ||
            $post->post_status == 'future'
        ) {
            return true;
        } else {
            return false;
        }
    }

    function is_autopublish_enabled() {
        $hs_settings = get_option('hs_settings');
        $settings_key = $this->settings_key;
        if (isset($hs_settings[$settings_key]) && $hs_settings[$settings_key] == 'on') {
            return true;
        } else {
            return false;
        }
    }

    function is_social_published($post_id) {
        if (get_post_meta($post_id, $this->meta_key)) {
            return true;
        } else {
            return false;
        }
    }

    function publish_broadcast($post) {
        // Prepare post data
        $access_token = WPHubspotOauth::getAccessToken();

        $post_id = $post->ID;
        $params = array(
            'url' => get_permalink($post_id),
            'title' => $post->post_title,
            'blogPostId' => $post_id,
            'access_token' => $access_token
        );

        $query = http_build_query($params);
        $url = $this->api_endpoint . '?' . $query;

        // Make the request
        $result = wp_remote_post($url);

        // Catch error with WordPress
        if (is_wp_error($result)) {
            $error_message = $result->get_error_message();
                WPHubspotLogging::log("[hsWordpress] Error publishing social media post to HubSpot: " . $error_message);
        }

        // Catch errors from server
        $response_code = wp_remote_retrieve_response_code($result);
        if ($response_code == '') {
            WPHubspotLogging::log("[hsWordpress] Error publishing social media post to HubSpot: response code was blank");
            return false;
        }
        if ($response_code == 401) {
            WPHubspotOauth::emptyAccessToken();
            WPHubspotLogging::log("[hsWordpress] Error publishing social media post to HubSpot: Response code was 401, clearing access token.");
            return false;
        }
        if ($response_code == 400 or $response_code == 404 or $response_code == 500) {
            WPHubspotLogging::log("[hsWordpress] Error publishing social media post to HubSpot: Response code was " . $response_code);
            return false;
        }

        // If no errors, assume it was successful and log the event
        $this->log_publish_event($post);
        return true;
    }

    // Store the post id as metadata on the post
    function log_publish_event($post) {
        $post_time = post_timestamp($post);
        add_post_meta($post->ID, $this->meta_key, $post_time, true);
    }

}

function post_timestamp($post) {
    $post_time = intval(strtotime($post->post_date));
    $post_time .= '000';
    return $post_time;
}

?>