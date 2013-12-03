<?php

/**
 * Handles the parameters and url
 *
 * @author StarBox
 */
class ABH_Classes_Tools extends ABH_Classes_FrontController {

    /** @var array Saved options in database */
    public static $options = array();

    /** @var integer Count the errors in site */
    static $errors_count = 0;

    /** @var array */
    private static $debug;

    function __construct() {
        parent::__construct();

        self::$options = $this->getOptions();

        $this->checkDebug(); //Check for debug
    }

    public static function getUserID() {
        global $current_user;
        return $current_user->ID;
    }

    /**
     * This hook will save the current version in database
     *
     * @return void
     */
    function hookInit() {

        //TinyMCE editor required
        //set_user_setting('editor', 'tinymce');

        $this->loadMultilanguage();

        //add setting link in plugin
        add_filter('plugin_action_links', array($this, 'hookActionlink'), 5, 2);
    }

    /**
     * Hook the frontent event to load the translations
     */
    function hookFrontinit() {
        $this->loadMultilanguage();
    }

    /**
     * Add a link to settings in the plugin list
     *
     * @param array $links
     * @param type $file
     * @return array
     */
    public function hookActionlink($links, $file) {

        if ($file == strtolower(_ABH_PLUGIN_NAME_) . '/' . strtolower(_ABH_PLUGIN_NAME_) . '.php') {
            $link = '<a href="' . admin_url('admin.php?page=abh_settings') . '">' . __('Settings', _ABH_PLUGIN_NAME_) . '</a>';
            array_unshift($links, $link);
        }
        return $links;
    }

    /**
     * Load the Options from user option table in DB
     *
     * @return void
     */
    public static function getOptions() {
        $default = array(
            'abh_version' => ABH_VERSION,
            'abh_use' => 1,
            'abh_subscribe' => 0,
            'abh_inposts' => 1,
            'abh_strictposts' => 0,
            'abh_inpages' => 0,
            'abh_ineachpost' => 0,
            'abh_showopengraph' => 1,
            'abh_shortcode' => 1,
            // --
            'abh_position' => 'down',
            'anh_crt_posts' => 3,
            'abh_author' => array(),
            'abh_theme' => 'business',
            'abh_achposttheme' => 'drop-down',
        );
        $options = json_decode(get_option(ABH_OPTION), true);

        if (is_array($options)) {
            $options = @array_merge($default, $options);
        } else {
            $options = $default;
        }

        $options['abh_themes'] = array('business', 'fancy', 'minimal', 'drop-down', 'topstar', 'topstar-round');
        $options['abh_achpostthemes'] = array('drop-down', 'topstar', 'topstar-round');
        return $options;
    }

    public static function getOption($value) {
        if (isset(self::$options[$value]))
            return self::$options[$value];
        else
            return false;
    }

    /**
     * Save the Options in user option table in DB
     *
     * @return void
     */
    public static function saveOptions($key, $value) {
        self::$options[$key] = $value;
        update_option(ABH_OPTION, json_encode(self::$options));
    }

    /**
     * Set the header type
     * @param type $type
     */
    public static function setHeader($type) {
        if (ABH_Classes_Tools::getValue('abh_debug') == 'on')
            return;

        switch ($type) {
            case 'json':
                header('Content-Type: application/json');
        }
    }

    /**
     * Get a value from $_POST / $_GET
     * if unavailable, take a default value
     *
     * @param string $key Value key
     * @param mixed $defaultValue (optional)
     * @return mixed Value
     */
    public static function getValue($key, $defaultValue = false) {
        if (!isset($key) OR empty($key) OR !is_string($key))
            return false;
        $ret = (isset($_POST[$key]) ? $_POST[$key] : (isset($_GET[$key]) ? $_GET[$key] : $defaultValue));

        if (is_string($ret) === true)
            $ret = urldecode(preg_replace('/((\%5C0+)|(\%00+))/i', '', urlencode($ret)));
        return !is_string($ret) ? $ret : stripslashes($ret);
    }

    /**
     * Check if the parameter is set
     *
     * @param string $key
     * @return boolean
     */
    public static function getIsset($key) {
        if (!isset($key) OR empty($key) OR !is_string($key))
            return false;
        return isset($_POST[$key]) ? true : (isset($_GET[$key]) ? true : false);
    }

    /**
     * Show the notices to WP
     *
     * @return void
     */
    public static function showNotices($message, $type = 'abh_notices') {
        if (file_exists(_ABH_THEME_DIR_ . 'Notices.php')) {
            ob_start();
            include (_ABH_THEME_DIR_ . 'Notices.php');
            $message = ob_get_contents();
            ob_end_clean();
        }

        return $message;
    }

    /**
     * Load the multilanguage support from .mo
     */
    private function loadMultilanguage() {
        if (!defined('WP_PLUGIN_DIR')) {
            load_plugin_textdomain(_ABH_PLUGIN_NAME_, _ABH_PLUGIN_NAME_ . '/languages/');
        } else {
            load_plugin_textdomain(_ABH_PLUGIN_NAME_, null, _ABH_PLUGIN_NAME_ . '/languages/');
        }
    }

    /**
     * Connect remote with CURL if exists
     */
    public static function abh_remote_get($url, $param = array()) {

        $url_domain = parse_url($url);
        $url_domain = $url_domain['host'];

        if (isset($param['timeout']))
            $timeout = $param['timeout'];
        else
            $timeout = 30;

        if (function_exists('curl_init')) {
            return self::abh_curl($url, array('timeout' => $timeout,));
        } else {
            return self::abh_wpcall($url, array('timeout' => $timeout));
        }
    }

    /**
     * Call remote UR with CURL
     * @param string $url
     * @param array $param
     * @return string
     */
    private static function abh_curl($url, $param = array()) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, $param['timeout']);

        $response = curl_exec($ch);
        $response = self::cleanResponce($response);

        if (curl_errno($ch) == 1) { //if protocol not supported
            self::dump(curl_getinfo($ch), curl_errno($ch), curl_error($ch));
            $response = self::abh_wpcall($url, $param); //use the wordpress call
        }
        self::dump('CURL', $url, $param, $response); //output debug

        curl_close($ch);
        return $response;
    }

    /**
     * Use the WP remote call
     * @param string $url
     * @param array $param
     * @return string
     */
    private static function abh_wpcall($url, $param = array()) {
        $response = wp_remote_get($url, $param);
        $response = self::cleanResponce(wp_remote_retrieve_body($response)); //clear and get the body
        return $response;
    }

    /**
     * Connect remote with CURL if exists
     */
    public static function abh_remote_head($url) {
        $response = array();

        if (isset($param['timeout']))
            $timeout = $param['timeout'];
        else
            $timeout = 30;

        if (function_exists('curl_exec')) {
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
            curl_exec($ch);

            $response['headers']['content-type'] = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
            $response['response']['code'] = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            return $response;
        } else {
            return wp_remote_head($url, array('timeout' => $timeout));
        }

        return false;
    }

    /**
     * Get the Json from responce if any
     * @param string $response
     * @return string
     */
    private static function cleanResponce($response) {

        if (function_exists('substr_count'))
            if (substr_count($response, '(') > 1)
                return $response;

        if (strpos($response, '(') !== false && strpos($response, ')') !== false)
            $response = substr($response, (strpos($response, '(') + 1), (strpos($response, ')') - 1));

        return $response;
    }

    /**
     * Check for SEO blog bad settings
     */
    public static function checkErrorSettings($count_only = false) {


        if (function_exists('is_network_admin') && is_network_admin())
            return;

        if (isset(self::$options['ignore_warn']) && self::$options['ignore_warn'] == 1)
            return;

        if (false) {
            if ($count_only)
                self::$errors_count++;
            else
                ABH_Classes_Error::setError(__('Notice', _ABH_PLUGIN_NAME_) . " <br /> ", 'settings');
        }
    }

    /**
     * Support for i18n with wpml, polyglot or qtrans
     *
     * @param string $in
     * @return string $in localized
     */
    public static function i18n($in) {
        if (function_exists('langswitch_filter_langs_with_message')) {
            $in = langswitch_filter_langs_with_message($in);
        }
        if (function_exists('polyglot_filter')) {
            $in = polyglot_filter($in);
        }
        if (function_exists('qtrans_useCurrentLanguageIfNotFoundUseDefaultLanguage')) {
            $in = qtrans_useCurrentLanguageIfNotFoundUseDefaultLanguage($in);
        }
        $in = apply_filters('localization', $in);
        return $in;
    }

    /**
     * Convert integer on the locale format.
     *
     * @param int $number The number to convert based on locale.
     * @param int $decimals Precision of the number of decimal places.
     * @return string Converted number in string format.
     */
    public static function i18n_number_format($number, $decimals = 0) {
        global $wp_locale;
        $formatted = number_format($number, absint($decimals), $wp_locale->number_format['decimal_point'], $wp_locale->number_format['thousands_sep']);
        return apply_filters('number_format_i18n', $formatted);
    }

    /**
     * Check if debug is called
     */
    private function checkDebug() {
        //if debug is called
        if (self::getIsset('abh_debug')) {
            if (self::getValue('abh_debug') === 'on') {
                if (function_exists('register_shutdown_function'))
                    register_shutdown_function(array($this, 'showDebug'));
            }
        }
    }

    /**
     * Store the debug for a later view
     */
    public static function dump() {
        $output = '';
        $callee = array('file' => '', 'line' => '');
        if (function_exists('func_get_args')) {
            $arguments = func_get_args();
            $total_arguments = count($arguments);
        }
        else
            $arguments = array();



        if (function_exists('debug_backtrace'))
            list( $callee ) = debug_backtrace();

        $output .= '<fieldset style="background: #FFFFFF; border: 1px #CCCCCC solid; padding: 5px; font-size: 9pt; margin: 0;">';
        $output .= '<legend style="background: #EEEEEE; padding: 2px; font-size: 8pt;">' . $callee['file'] . ' @ line: ' . $callee['line']
                . '</legend><pre style="margin: 0; font-size: 8pt; text-align: left;">';

        $i = 0;
        foreach ($arguments as $argument) {
            if (count($arguments) > 1)
                $output .= "\n" . '<strong>#' . (++$i ) . ' of ' . $total_arguments . '</strong>: ';

            // if argument is boolean, false value does not display, so ...
            if (is_bool($argument))
                $argument = ( $argument ) ? 'TRUE' : 'FALSE';
            else
            if (is_object($argument) && function_exists('array_reverse') && function_exists('class_parents'))
                $output .= implode("\n" . '|' . "\n", array_reverse(class_parents($argument))) . "\n" . '|' . "\n";

            $output .= htmlspecialchars(print_r($argument, TRUE))
                    . ( ( is_object($argument) && function_exists('spl_object_hash') ) ? spl_object_hash($argument) : '' );
        }
        $output .= "</pre>";
        $output .= "</fieldset>";

        self::$debug[] = $output;
    }

    /**
     * Show the debug dump
     */
    public static function showDebug() {
        echo "Debug result: <br />" . @implode('<br />', self::$debug);
    }

    public static function emptyCache() {
        if (function_exists('w3tc_pgcache_flush')) {
            w3tc_pgcache_flush();
        }
        if (function_exists('wp_cache_clear_cache')) {
            wp_cache_clear_cache();
        }
    }

}

?>