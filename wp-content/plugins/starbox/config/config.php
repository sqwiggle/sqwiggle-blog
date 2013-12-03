<?php

/**
 * The configuration file
 */
define('_ABH_NONCE_ID_', 'abh_nonce');

if (!defined('PHP_VERSION_ID')) {
    $version = explode('.', PHP_VERSION);
    define('PHP_VERSION_ID', ((int) @$version[0] * 1000 + (int) @$version[1] * 100 + ((isset($version[2])) ? ((int) $version[2] * 10) : 0)));
}
if (!defined('WP_VERSION_ID') && isset($wp_version)) {
    $version = explode('.', $wp_version);
    define('WP_VERSION_ID', ((int) @$version[0] * 1000 + (int) @$version[1] * 100 + ((isset($version[2])) ? ((int) $version[2] * 10) : 0)));
}
if (!defined('WP_VERSION_ID'))
    define('WP_VERSION_ID', '3000');

if (!defined('ABH_VERSION_ID')) {
    $version = explode('.', ABH_VERSION);
    define('ABH_VERSION_ID', ((int) @$version[0] * 1000 + (int) @$version[1] * 100 + ((isset($version[2])) ? ((int) $version[2] * 10) : 0)));
}

/* No path file? error ... */
require_once(dirname(__FILE__) . '/paths.php');

/* Define the record name in the Option and UserMeta tables */
define('ABH_OPTION', 'abh_options');
?>