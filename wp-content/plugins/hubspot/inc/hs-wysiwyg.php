<?php
class WPHubspotCustomEditor {
    
    function WPHubspotCustomEditor(){
        add_action('init', array(&$this, 'hubspot_button'));
    }
    
    function hubspot_button() {
 
        if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') ) {
            return;
        }

        if ( get_user_option('rich_editing') == 'true' ) {
            add_filter( 'mce_external_plugins', array(&$this, 'add_hubspot_button' ));
            add_filter( 'mce_buttons', array(&$this, 'register_hubspot_button' ));
        }
    }

    function register_hubspot_button( $buttons ) {
        array_push( $buttons, "|", "hubspot" );
        return $buttons;
    }

    function add_hubspot_button( $plugin_array ) {
        $plugin_array['hubspot'] = HUBSPOT_URL . '/js/editor_buttons.js';
        return $plugin_array;
    }
}

?>
