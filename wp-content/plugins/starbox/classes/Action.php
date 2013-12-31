<?php

/**
 * Set the ajax action and call for wordpress
 */
class ABH_Classes_Action extends ABH_Classes_FrontController {

    /** @var array with all form and ajax actions  */
    var $actions = array();

    /** @var array from core config */
    private static $config;

    /**
     * The hookAjax is loaded as custom hook in hookController class
     *
     * @return void
     */
    function hookInit() {

        /* Only if ajax */
        if ((isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') || strpos($_SERVER['PHP_SELF'], '/admin-ajax.php') !== false) {
            $this->actions = array();
            $this->getActions(((isset($_GET['action']) ? $_GET['action'] : (isset($_POST['action']) ? $_POST['action'] : ''))));
        }
    }

    /**
     * The hookSubmit is loaded when action si posted
     *
     * @return void
     */
    function hookMenu() {
        /* Only if post */
        if ((!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') || strpos($_SERVER['PHP_SELF'], '/admin-ajax.php') !== false)
            return;

        $this->actions = array();
        $this->getActions(((isset($_GET['action']) ? $_GET['action'] : (isset($_POST['action']) ? $_POST['action'] : ''))));
    }

    /**
     * The hookHead is loaded as admin hook in hookController class for script load
     * Is needed for security check as nonce
     *
     * @return void
     */
    function hookHead() {

        echo '<script type="text/javascript">
                  var abh_Query = {
                    "ajaxurl": "' . admin_url('admin-ajax.php') . '",
                    "adminposturl": "' . admin_url('post.php') . '",
                    "adminlisturl": "' . admin_url('edit.php') . '",
                    "nonce": "' . wp_create_nonce(_ABH_NONCE_ID_) . '"
                  }
              </script>';
    }

    /**
     * Get all actions from config.xml in core directory and add them in the WP
     *
     * @return void
     */
    public function getActions($cur_action) {

        /* if config allready in cache */
        if (!isset(self::$config)) {
            $config_file = _ABH_CORE_DIR_ . 'config.xml';
            if (!file_exists($config_file))
                return;

            /* load configuration blocks data from core config files */
            $data = file_get_contents($config_file);
            self::$config = json_decode(json_encode((array) simplexml_load_string($data)), 1);
        }

        if (is_array(self::$config))
            foreach (self::$config['block'] as $block) {
                if (isset($block['active']) && $block['active'] == 1) {
                    /* if there is a single action */
                    if (isset($block['actions']['action']))

                    /* if there are more actions for the current block */
                        if (!is_array($block['actions']['action'])) {
                            /* add the action in the actions array */
                            if ($block['actions']['action'] == $cur_action)
                                $this->actions[] = array('class' => $block['name']);
                        }else {
                            /* if there are more actions for the current block */
                            foreach ($block['actions']['action'] as $action) {
                                /* add the actions in the actions array */
                                if ($action == $cur_action)
                                    $this->actions[] = array('class' => $block['name']);
                            }
                        }
                }
            }

        /* add the actions in WP */
        foreach ($this->actions as $actions) {
            ABH_Classes_ObjController::getController($actions['class'])->action();
        }
    }

}

?>