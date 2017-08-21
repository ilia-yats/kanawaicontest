<?php
/**
 * Predefined constants for kanawaicontest plugin
 */

// Plugin root directory
define('KANAWAICONTEST_ROOT', __DIR__);
define('KANAWAICONTEST_MAX_POSTERS_TO_VOTE', 3);

// Add autoload function
function kanawaicontest_components_autoloader($class_name)
{
    $trimmed_class_name = substr($class_name, strpos($class_name, '_') + 1);
    $dir_name = strtolower(substr($trimmed_class_name, 0, strrpos($trimmed_class_name, '_')));
    $file_name = substr($trimmed_class_name, strrpos($trimmed_class_name, '_') + 1);
    $file_path = KANAWAICONTEST_ROOT . '/' . $dir_name . '/' . $file_name . '.php';

    if (file_exists($file_path)) {
        include_once($file_path);
    }
}

spl_autoload_register('kanawaicontest_components_autoloader');