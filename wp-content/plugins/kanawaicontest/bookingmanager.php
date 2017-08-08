<?php
/*
Plugin Name: Kanawaicontest
Plugin URI: 
Description: Plugin for online bus ticket booking for SV-Trans
Version: 1.0
Author: uui
Author URI: 
*/

// Exit if accessed directly
if( ! defined('ABSPATH')) {
    exit;
}

//include_once(__DIR__ . '/init.php');

/**
 * Main Bookingmanager plugin class
 */
class Kanawaicontest
{
    /**
     * Instance of this class.
     *
     * @var static
     */
    protected static $instance;

    /*
     * Delete slashes from all values in POST and save in class static field
     *  (even if magic_quotes_qpc is disabled, WP adds slashes to all incoming values on its own, 
     *  so we need to unslash before inserting into database; see: http://wp-kama.ru/function/wp_unslash)
     */
    public static $unslashed_post;

//    /**
//     * Components
//     */
//    public $cities;
//    public $ticket_windows;
//    public $routes;
//    public $places;
//    public $calendar;
//    public $clients;
//    public $events;
//    public $events_stat;
//    public $public_stat;

    /**
     * Constructor.
     *
     * @param mixed
     */

    public function __construct()
    {
        // Capture POST, unslash and store in class variable
        self::$unslashed_post = wp_unslash($_POST);

//        // Start session
//        BM_Util_Util::session_start();

        if(is_admin()) {

//            // Register needed plugin settings
//            add_action('admin_init', function() {
//                register_setting('bookingmanager_settings', 'bookingmanager_rules');
//                register_setting('bookingmanager_settings', 'bookingmanager_admin_email');
//                register_setting('bookingmanager_settings', 'bookingmanager_client_email_subject');
//                register_setting('bookingmanager_settings', 'bookingmanager_client_email_text');
//                register_setting('bookingmanager_settings', 'bookingmanager_additional_client_email_text');
//                register_setting('bookingmanager_settings', 'bookingmanager_tickets_email_text');
//            });
//
//            // Create plugin components
//            $this->routes = new BM_Route_AdminMenu();
//            $this->ticket_windows = new BM_Ticket_Window_AdminMenu();
//            $this->cities = new BM_City_AdminMenu();
//            $this->places = new BM_Reserved_Place_AdminMenu();
//            $this->calendar = new BM_Trips_Calendar_AdminMenu();
//            $this->clients = new BM_Client_AdminMenu();
//            $this->events = new BM_Event_AdminMenu();
//            $this->events_stat = new BM_Event_Stat_AdminMenu();
//            $this->public_stat = new BM_Public_Stat_AdminMenu();
//
//            // Create menu items
//            // wp_get_current_user works not early than 'init' hook
//            add_action('init', array($this, 'create_menu'));
//
//            // Add needed scripts on page
//            add_action('admin_enqueue_scripts', array($this, 'enqueue_bookingmanager_admin_scripts'));
//
//            // Check if array of admin notices exists in session
//            add_action('admin_notices', array('BM_Util_Util', 'show_admin_notices'));
//
//            // Save all registered events before script execution ends
//            add_action('shutdown', array("BM_Event_Manager", 'save_all_events'));
        }
        
        add_filter( 'login_redirect', array($this, 'redirect_to_home_page'), 10, 3);

        // When plugin activated, add new tables and user role
        register_activation_hook(__FILE__, array($this, 'create_tables'));
        register_activation_hook(__FILE__, array($this, 'add_roles'));
    }


    /**
     * Singleton instance.
     *
     * @return object
     */
    public static function get_instance()
    {
        if( ! isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Create relevant tables.
     *
     * @return mixed
     */
    public function create_tables()
    {
        // TODO: create SQL dump
    }


    /**
     * Adds items to admin menu depending on the roles of current user
     */
    public function create_menu()
    {
        $user = wp_get_current_user();

        if( ! $user instanceof WP_User) {

            return FALSE;
        }

        // Show all menu items for admins
        if(in_array('administrator', $user->roles)) {

//            add_action('admin_menu', function() {
//                add_menu_page(
//                    'Резервирование мест',
//                    'Резервирование мест',
//                    'manage_options',
//                    'bookingmanager',
//                    array($this->calendar, 'plugin_menu'),
//                    'dashicons-calendar-alt',
//                    NULL
//                );
//            });
//            add_action('admin_menu', array($this->calendar, 'plugin_menu'));
//            add_action('admin_menu', array($this->places, 'plugin_menu'));
//
//            add_action('admin_menu', array($this->routes, 'plugin_menu'));
//            add_action('admin_menu', array($this->ticket_windows, 'plugin_menu'));
//            add_action('admin_menu', array($this->cities, 'plugin_menu'));
//            add_action('admin_menu', array($this->clients, 'plugin_menu'));
//
//            add_action('admin_menu', array($this->events, 'plugin_menu'));
//            add_action('admin_menu', array($this->events_stat, 'plugin_menu'));
//            add_action('admin_menu', array($this->public_stat, 'plugin_menu'));
//            add_action('admin_menu', function() {
//                add_submenu_page(
//                    'bookingmanager',
//                    'Настройки',
//                    'Настройки',
//                    'manage_options',
//                    'bookingmanager_settings',
//                    function() {
//                        include BM_ROOT . '/settings/views/settings.php';;
//                    });
//            });
        } elseif(in_array('booking_clerk', $user->roles)) {
//        // Show several items for booking-clerks
//            add_action('admin_menu', function() {
//                add_menu_page(
//                    'Резервирование мест',
//                    'Резервирование мест',
//                    'read',
//                    'bookingmanager',
//                    array($this->calendar, 'plugin_menu'),
//                    'dashicons-calendar-alt',
//                    NULL
//                );
//            });
//            add_action('admin_menu', array($this->calendar, 'plugin_menu'));
//            add_action('admin_menu', array($this->places, 'plugin_menu'));
//
//            // Dirty hack: script removes all menu items except the ones created above
//            add_action('admin_enqueue_scripts', array($this, 'enqueue_menu_items_hide_script'));
        }
//
        return TRUE;
    }  


    /**
     *  Adds a custom user role 'booking clerk'
     */
//    public function add_roles()
//    {
//        add_role(
//            'booking_clerk',
//            __('Booking-Clerk'),
//            [
//                'read' => TRUE,
//                'level_0' => TRUE,
//                'manage_options' => TRUE,
//            ]
//        );
//    }

    /**
     * Enqueues needed scripts on admin page
     */
//    public function enqueue_bookingmanager_admin_scripts()
//    {
//        wp_enqueue_style('bm_admin_style', '/wp-content/plugins/bookingmanager/assets/css/admin.css');
//        wp_enqueue_style('jquery-style', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');
//        wp_enqueue_script('bm_admin_main', '/wp-content/plugins/bookingmanager/assets/js/admin_main.js', array('jquery-ui-datepicker', 'jquery-ui-dialog'), FALSE, TRUE);
//    }



    /**
     * Enqueues script for removing not-necessary menu items
     */
//    public function enqueue_menu_items_hide_script()
//    {
//        wp_enqueue_script('bm_remove_menu_items', '/wp-content/plugins/bookingmanager/assets/js/remove_menu_items.js', array('jquery-ui-datepicker', 'jquery-ui-dialog'), FALSE, TRUE);
//    }

    /**
     * Returns data about current user
     *
     * @return array
     */
//    public static function get_current_user_data()
//    {
//        $user_data = [
//            'id'   => NULL,
//            'name' => 'Неизвестно',
//        ];
//
//        // Get current user
//        $user = wp_get_current_user();
//
//        if($user instanceof WP_User) {
//            $user_data['id'] = $user->ID;
//            $user_data['name'] = $user->display_name;
//        }
//
//        return $user_data;
//    }

    /* Redirect the user logging in to a custom admin page. */
//    function redirect_to_home_page( $redirect_to, $request, $user )
//    {
//        if ( is_array( $user->roles ) ) {
//            if ( in_array( 'booking_clerk', $user->roles ) ) {
//                return admin_url( '?page=bookingmanager' );
//            } else {
//                return admin_url();
//            }
//        }
//    }

}

BM_Bookingmanager::get_instance();

