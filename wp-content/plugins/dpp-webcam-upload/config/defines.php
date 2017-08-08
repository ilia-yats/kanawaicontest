<?php

define('DPPWU_LOGGER_PATH', DPPWU_BASE_PLUGIN_PATH.'/logs/log-'.date('d-m').'-'.md5('T*&^CISDH*(YCSUI'.date('m-d')).'.txt');
define('DPPWU_MODELS_PATH', DPPWU_BASE_PLUGIN_PATH.'/models/');
define('DPPWU_DEBUG_MODE', false);

define('DPPWU_TEXTDOMAIN', 'DPPWU_TEXTDOMAIN');
define('DPPWU_PLUGIN_URL',  plugin_dir_url(__FILE__).'..');
define('DPPWU_PLUGIN_DIR', __DIR__.'/..');
define('CAMS_DIR', WP_CONTENT_DIR .'/cams/cam/');
define('CAMS_URL', WP_CONTENT_URL .'/cams/cam/');

