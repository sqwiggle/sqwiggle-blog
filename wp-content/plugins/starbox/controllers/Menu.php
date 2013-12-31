<?php

class ABH_Controllers_Menu extends ABH_Classes_FrontController {
    /** @var array themes */

    /** @var array snippet */
    var $options = array();

    //
    function init() {

    }

    function upgradeRedirect() {
        // Bail if no activation redirect
        if (!get_transient('abh_upgrade'))
            return;

        // Delete the redirect transient
        delete_transient('abh_upgrade');
        ABH_Classes_Tools::emptyCache();

        wp_safe_redirect(admin_url('admin.php?page=abh_settings'));
        exit();
    }

    /*
     * Creates the Setting menu in Wordpress
     */

    public function hookMenu() {

        $this->upgradeRedirect();
        ABH_Classes_Tools::checkErrorSettings(true);

        /* add the plugin menu in admin */
        if (current_user_can('administrator')) {
            $this->model->addSubmenu(array('options-general.php',
                __('StarBox Settings', _ABH_PLUGIN_NAME_),
                __('StarBox', _ABH_PLUGIN_NAME_) . ABH_Classes_Tools::showNotices(ABH_Classes_Tools::$errors_count, 'errors_count'),
                'edit_posts',
                'abh_settings',
                array($this, 'showMenu')
            ));
        }
        add_action('edit_user_profile', array(ABH_Classes_ObjController::getBlock('ABH_Core_UserSettings'), 'init'));
        add_action('show_user_profile', array(ABH_Classes_ObjController::getBlock('ABH_Core_UserSettings'), 'init'));

        add_action('personal_options_update', array(ABH_Classes_ObjController::getBlock('ABH_Core_UserSettings'), 'action'));
        add_action('edit_user_profile_update', array(ABH_Classes_ObjController::getBlock('ABH_Core_UserSettings'), 'action'));
    }

    public function showMenu() {
        ABH_Classes_Tools::checkErrorSettings();
        /* Force call of error display */
        ABH_Classes_ObjController::getController('ABH_Classes_Error')->hookNotices();

        parent::init();
    }

    /**
     * Called when Post action is triggered
     *
     * @return void
     */
    public function action() {

        parent::action();
        switch (ABH_Classes_Tools::getValue('action')) {

            case 'abh_settings_update':

                if (ABH_Classes_Tools::getValue('data') <> '') {
                    parse_str(ABH_Classes_Tools::getValue('data'), $params);
                    $this->saveValues($params);
                    exit();
                } else {
                    $this->saveValues($_POST);
                }

                ABH_Classes_Tools::emptyCache();
                break;
            case 'abh_settings_subscribe':
                ABH_Classes_Tools::saveOptions('abh_subscribe', 1);
                break;
        }
    }

    private function saveValues($params) {
        if (!empty($params))
            foreach ($params as $key => $value)
                if ($key <> 'action' && $key <> 'nonce')
                    ABH_Classes_Tools::saveOptions($key, $value);
    }

}

?>