<?php

function dppwu_load_plugin_textdomain() 
{
    $locale = apply_filters( 'plugin_locale', get_locale(), DPPWU_TEXTDOMAIN );
    load_textdomain( DPPWU_TEXTDOMAIN, DPPWU_PLUGIN_DIR.'/languages/'. DPPWU_TEXTDOMAIN . '-' . $locale . '.mo' );
    load_plugin_textdomain( DPPWU_TEXTDOMAIN, FALSE, basename( dirname( __FILE__ ) ) . '/../languages/' );
}
add_action( 'init', 'dppwu_load_plugin_textdomain' ); 


function dppwu_set_html_content_type() {
    return 'text/html';
}	
if (!function_exists('vd')) {
    function vd($data) {
        var_dump('<pre>', $data, '</pre>');die;
    }
}
if (!function_exists('pr')) {
    function pr($data) {
        echo '<pre>'.print_r($data).'</pre>';die;
    }
}
function dppwu_logger($data) {
    if (DPPWU_DEBUG_MODE) {
        file_put_contents(DPPWU_LOGGER_PATH, date('d-m-Y H:i:s').': '.print_r($data, true).PHP_EOL, 
            FILE_APPEND | LOCK_EX
        );
    }
}