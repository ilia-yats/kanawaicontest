<?php
/**
 * Pre-defined constants for bookingmanager plugin
 */

// Plugin root directory
define('BM_ROOT', __DIR__);

// All non-paid reserves will expire after this amount of hours from moment of reserving
define('BOOKINGMANAGER_RESERVE_EXPIRE_HOURS', 24);

// Liqpay requisites
define('BOOKINGMANAGER_LIQ_PUBLIC_KEY', "i12040075023");
define('BOOKINGMANAGER_LIQ_PRIVATE_KEY', "SEvn1ceUdqMQFEATlCujORmarVWVK1ggMKppsafz");


// LiqPay test mode switcher: enable - 1, disable - 0
define('BOOKINGMANAGER_LIQ_TEST_MODE', 1);


// Add autoload function
function bm_components_autoloader($class_name)
{
    $trimmed_class_name = substr($class_name, strpos($class_name, '_') + 1);
    $dir_name = strtolower(substr($trimmed_class_name, 0, strrpos($trimmed_class_name, '_')));
    $file_name = substr($trimmed_class_name, strrpos($trimmed_class_name, '_') + 1);
    $file_path = BM_ROOT . '/' . $dir_name . '/' . $file_name . '.php';

    if(file_exists($file_path)) {
        include_once ($file_path);
    }
}
spl_autoload_register('bm_components_autoloader');