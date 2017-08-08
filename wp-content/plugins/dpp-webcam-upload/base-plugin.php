<?php

/*
Plugin Name: DPP Webcam upload
Description: 
Version: 1.0
Plugin URI: https://der-php-programmierer.de/
Author: <a target="_blank" href="https://der-php-programmierer.de/">Der PHP Programmierer</a>
Text Domain: DPPWU_TEXTDOMAIN
Domain Path: /languages/
*/

defined('ABSPATH') or die("You are not allowed to access plugin files directly");

if (!defined('IS_DEV')) {
    define('IS_DEV', true);
}

define('DPPWU_BASE_PLUGIN_PATH', __DIR__);
define('DPPWU_BASE_PLUGIN_FILE', __FILE__);

if(IS_DEV) {
    ini_set('display_errors', 0);
} else {
    error_reporting(E_ALL);
    ini_set('display_errors', true);
    ini_set('error_reporting', E_ALL);
}



require_once 'config/defines.php';
require_once 'includes/general_functions.php';

add_action('init', function() {
    require_once 'includes/script_includes.php';
});

require_once 'core/DPPWUEventManager.php';

