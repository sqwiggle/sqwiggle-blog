<?php
/**
 * @package TinyMCE
 * @author Moxiecode
 * @copyright Copyright © 2005-2006, Moxiecode Systems AB, All rights reserved.
 */

/** @ignore */
require_once('../../../wp-load.php');
header('Content-Type: text/html; charset=' . get_bloginfo('charset'));
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
<head>
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php echo get_option('blog_charset'); ?>" />
<title><?php _e('HubSpot Shortcodes') ?></title>
<script type="text/javascript" src="<?php echo get_bloginfo('wpurl'); ?>/wp-includes/js/tinymce/tiny_mce_popup.js?ver=3223"></script>
<script type="text/javascript" src="<?php echo get_bloginfo('wpurl'); ?>/wp-includes/js/jquery/jquery.js"></script>
<script type="text/javascript" src="<?php echo get_bloginfo('wpurl'); ?>/wp-includes/js/jquery/ui.core.js"></script>
<script type="text/javascript" src="<?php echo get_bloginfo('wpurl'); ?>/wp-includes/js/jquery/ui.widget.js"></script>
<script type="text/javascript" src="<?php echo get_bloginfo('wpurl'); ?>/wp-includes/js/jquery/ui.mouse.js"></script>
<script type="text/javascript" src="<?php echo get_bloginfo('wpurl'); ?>/wp-includes/js/jquery/ui.sortable.js"></script>
<script type="text/javascript" src="<?php echo HUBSPOT_URL; ?>/js/wp_hubspot_visual.js"></script>
<?php
wp_admin_css( 'global', true );
wp_admin_css( 'wp-admin', true );
?>
<style type="text/css">
        .ui-socialize-highlight {
                height:15px;
                width:120px;
                background:#ffebd6;
                border:2px dashed #ff8a10;
        }
    #wphead {
        font-size: 80%;
        border-top: 0;
        color: #555;
        background-color: #f1f1f1;
    }
    #wphead h1 {
        font-size: 24px;
        color: #555;
        margin: 0;
        padding: 10px;
    }
    #tabs {
        padding: 15px 15px 3px;
        background-color: #f1f1f1;
        border-bottom: 1px solid #dfdfdf;
    }
    #tabs li {
        display: inline;
    }
    #tabs a.current {
        background-color: #fff;
        border-color: #dfdfdf;
        border-bottom-color: #fff;
        color: #d54e21;
    }
    #tabs a {
        color: #2583AD;
        padding: 6px;
        border-width: 1px 1px 0;
        border-style: solid solid none;
        border-color: #f1f1f1;
        text-decoration: none;
    }
    #tabs a:hover {
        color: #d54e21;
    }
    .wrap h2 {
        border-bottom-color: #dfdfdf;
        color: #555;
        margin: 5px 0;
        padding: 0;
        font-size: 18px;
    }
    #user_info {
        right: 5%;
        top: 5px;
    }
    h3 {
        font-size: 1.1em;
        margin-top: 10px;
        margin-bottom: 0px;
    }
    #flipper {
        margin: 0;
        padding: 5px 20px 10px;
        background-color: #fff;
        border-left: 1px solid #dfdfdf;
        border-bottom: 1px solid #dfdfdf;
    }
    * html {
        overflow-x: hidden;
        overflow-y: scroll;
    }
    #flipper div p {
        margin-top: 0.4em;
        margin-bottom: 0.8em;
        text-align: justify;
    }
    th {
        text-align: center;
    }
    .top th {
        text-decoration: underline;
    }
    .top .key {
        text-align: center;
        width: 5em;
    }
    .top .action {
        text-align: left;
    }
    .align {
        border-left: 3px double #333;
        border-right: 3px double #333;
    }
    .keys {
        margin-bottom: 15px;
    }
    .keys p {
        display: inline-block;
        margin: 0px;
        padding: 0px;
    }
    .keys .left { text-align: left; }
    .keys .center { text-align: center; }
    .keys .right { text-align: right; }
    td b {
        font-family: "Times New Roman" Times serif;
    }
    #buttoncontainer {
        text-align: center;
        margin-bottom: 20px;
    }
    #buttoncontainer a, #buttoncontainer a:hover {
        border-bottom: 0px;
    }
        .hs-tinymce-pu-text{
            width:175px;
        }
</style>
<?php if ( is_rtl() ) : ?>
<style type="text/css">
    #wphead, #tabs {
        padding-left: auto;
        padding-right: 15px;
    }
    #flipper {
        margin: 5px 0 3px 10px;
    }
    .keys .left, .top, .action { text-align: right; }
    .keys .right { text-align: left; }
    td b { font-family: Tahoma, "Times New Roman", Times, serif }
</style>
<?php endif; ?>
<script type="text/javascript">
    function d(id) { return document.getElementById(id); }

    function flipTab(n) {
        for (i=1;i<=4;i++) {
            c = d('content'+i.toString());
            t = d('tab'+i.toString());
                        if(c != null){
                            if ( n == i ) {
                                    c.className = '';
                                    t.className = 'current';
                            } else {
                                    c.className = 'hidden';
                                    t.className = '';
                            }
                        }
        }
    }
</script>
</head>
<body>
<?php

global $wpdb, $myhubspotwp_action, $myhubspotwp, $myhubspotusage;
$hs_settings = array();
$hs_settings = get_option('hs_settings');

$myhubspotusage->check_shortcode('Display shortcode popup');

?>

<ul id="tabs">
    <li><a id="tab1" href="javascript:flipTab(1)" title="<?php _e('Contact Info') ?>" accesskey="1" tabindex="1" class="current"><?php _e('Contact Info') ?></a></li>
    <li><a id="tab2" href="javascript:flipTab(2)" title="<?php _e('Team List') ?>" accesskey="2" tabindex="2"><?php _e('Team List') ?></a></li>
    <?php if($myhubspotwp_leads->hs_leads_enabled() && $myhubspotwp->hs_is_customer($hs_settings['hs_portal'], $hs_settings['hs_appdomain'])){?>
        <li><a id="tab3" href="javascript:flipTab(3)" title="<?php _e('Custom Form') ?>" accesskey="3" tabindex="3"><?php _e('Custom Form') ?></a></li>
    <?php } ?>
        <?php if($myhubspotwp_action->hs_actions_enabled()){ ?>
        <li><a id="tab4" href="javascript:flipTab(4)" title="<?php _e('Calls to Action') ?>" accesskey="4" tabindex="4"><?php _e('Calls to Action') ?></a></li>
        <?php } ?>
</ul>

<div id="flipper" class="wrap">

<div id="content1">
        <h2><?php _e('Contact Info') ?></h2>
        <table class="form-table">
            <form onsubmit="HubSpotInsertCompanyShortcode(<?php echo $hs_settings["hs_portal"]; ?>);return false" action="#" name="popup_form1">
                <tr><th scope="row"><label for="hs_company_name"><?php _e("Company Name") ?></label></th><td><input type="text" class="hs-tinymce-pu-text" name="hs_company_name" id ="hs_company_name" value="<?php echo $hs_settings['hs_company_name'] ?>" /></td></tr>
                <tr><th scope="row"><label for="hs_company_address"><?php _e("Company Address") ?></label></th><td><input type="text" class="hs-tinymce-pu-text" name="hs_company_address" id="hs_company_address" value="<?php echo $hs_settings['hs_company_address'] ?>" /></td></tr>
                <tr><th scope="row"><label for="hs_company_citystate"><?php _e("Company City/State/Zip") ?></label></th><td><input type="text" class="hs-tinymce-pu-text" name="hs_company_citystate" id="hs_company_citystate" value="<?php echo $hs_settings['hs_company_citystate'] ?>" /></td></tr>
                <tr><th scope="row"><label for="hs_company_phone"><?php _e("Company Phone") ?></label></th><td><input type="text" class="hs-tinymce-pu-text" name="hs_company_phone" id="hs_company_phone" value="<?php echo $hs_settings['hs_company_phone'] ?>" /></td></tr>
                <tr><th scope="row"><label for="hs_company_phone"><?php _e("Display") ?></label></th><td><select class="widefat" id="hs_contact_display" name="hs_contact_display">
                <?php
                foreach (array('both' =>  'Address & Map', 'address' =>  'Address', 'map' =>  'Map') as $display_key => $display_val){
                        echo '<option value="' . $display_key.'" >' . $display_val . '</option>';
                }
                ?>
                </select></td></tr>
                <tr><td scope="row" colspan="2">
                    <div style="margin: 8px auto; text-align: right;padding-bottom: 10px;">
                        <input type="button" id="cancel" name="cancel" value="<?php _e('Cancel'); ?>" title="<?php _e('Cancel'); ?>" onclick="tinyMCEPopup.close();" />
                        <input type="submit" id="insert" name="insert" value="Insert" />
                    </div>
                </td></tr>
            </form>
        </table>

</div>

<div id="content2" class="hidden">
    <h2><?php _e('Team List') ?></h2>
            <table class="form-table">
            <form onsubmit="HubSpotInsertTeamShortcode(<?php echo $hs_settings["hs_portal"]; ?>);return false" action="#" name="popup_form2">
                <tr><th scope="row">Select which team members to include on this team page.</th>
                 <td><ul id="team-sortable">
                <?php
                $team_results = $wpdb->get_results('SELECT DISTINCT ID FROM '.$wpdb->users);
                foreach($team_results as $team_member){
                    $member_ID = $team_member->ID;
                    $userdata = get_userdata($member_ID);
                    ?>
            <li class="ui-state-default"><label class="selectit" for="team-member-<?php echo $member_ID; ?>"><input type="checkbox" name="team_members" id ="team-member-<?php echo $member_ID; ?>" value="<?php echo $member_ID; ?>"/> <span><?php echo get_the_author_meta('display_name', $member_ID); ?></span></label></li> 
                    <?php
                }
                ?>
                </ul></td></tr>
                <tr><td scope="row" colspan="2">
                    <div style="margin: 8px auto; text-align: right;padding-bottom: 10px;">
                        <input type="button" id="cancel" name="cancel" value="<?php _e('Cancel'); ?>" title="<?php _e('Cancel'); ?>" onclick="tinyMCEPopup.close();" />
                        <input type="submit" id="insert" name="insert" value="Insert" />
                    </div>
                </td></tr>
            </form>
        </table>
</div>
<?php if($myhubspotwp_leads->hs_leads_enabled() && $myhubspotwp->hs_is_customer($hs_settings['hs_portal'], $hs_settings['hs_appdomain'])){?>
<div id="content3" class="hidden">
    <h2><?php _e('Custom Form') ?></h2>
                     <table class="form-table">
            <form onsubmit="HubSpotInsertFormShortcode(<?php echo $hs_settings["hs_portal"]; ?>);return false" action="#" name="popup_form3">
                <tr><th scope="row">Select which custom form to include on this team page.</th>
                 <td>
                    <select class="widefat" id="lead-form" name="lead_form">

                    <?php
                    $form_options = array();
                    $formid = 0;
                    while($form_option = get_option("hs_form_settings_" . $formid)){
                        $form_options[$formid] = $form_option;
                        $formid++;
                    }

                    for ($formid = 0; $formid < count($form_options); $formid++){ ?>
                        <option value="<?php echo $formid; ?>"><?php echo 'Form ID: ' . $formid; ?></option>
                    <?php } ?>
                    </select>
                </td></tr>
                <tr><td scope="row" colspan="2">
                    <div style="margin: 8px auto; text-align: right;padding-bottom: 10px;">
                        <input type="button" id="cancel" name="cancel" value="<?php _e('Cancel'); ?>" title="<?php _e('Cancel'); ?>" onclick="tinyMCEPopup.close();" />
                        <input type="submit" id="insert" name="insert" value="Insert" />
                    </div>
                </td></tr>
            </form>
        </table>  
</div>
    <?php } ?>
        <?php if($myhubspotwp_action->hs_actions_enabled()){ ?>
<div id="content4" class="hidden">
    <h2><?php _e('Calls to Action'); ?></h2>
            <table class="form-table">
            <form onsubmit="HubSpotInsertActionShortcode(<?php echo $hs_settings["hs_portal"]; ?>);return false" action="#" name="popup_form4">
                <tr><th scope="row">Select which Calls to Action you want to randomly rotate through.</th></tr>
                 <tr><td><ul>
                <?php
                $args = array('post_type' => 'hs-action', 'numberposts' => -1);
                $hs_actions = new WP_Query($args);
                global $post;
                if($hs_actions->have_posts()):
                    while ($hs_actions->have_posts()):
                        $hs_actions->the_post(); ?>
                        <li class="checkbox"><label class="selectit" for="hs-action-<?php _e($post->ID); ?>"><input type="checkbox" name="hs_action" id ="hs-action-<?php _e($post->ID); ?>" value="<?php _e($post->ID); ?>"/> <span><?php _e($post->post_title); ?></span></label></li>
                        <?php
                    endwhile;
                endif;
                wp_reset_query();
                ?>
                </ul></td></tr>
                <tr><td scope="row">
                    <div style="margin: 8px auto; text-align: right;padding-bottom: 10px;">
                        <input type="button" id="cancel" name="cancel" value="<?php _e('Cancel'); ?>" title="<?php _e('Cancel'); ?>" onclick="tinyMCEPopup.close();" />
                        <input type="submit" id="insert" name="insert" value="Insert" />
                    </div>
                </td></tr>
            </form>
        </table>
</div>
    <?php } ?>
</div>

</body>
</html>
