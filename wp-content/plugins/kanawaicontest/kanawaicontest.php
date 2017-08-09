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

include_once(__DIR__ . '/init.php');

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

    /**
     * Components
     */
    public $tours;
    public $images;
    public $voters;

    /**
     * Constructor.
     *
     * @param mixed
     */
    public function __construct()
    {
        // Capture POST, unslash and store in class variable
        self::$unslashed_post = wp_unslash($_POST);

        // Start session
        Kanawaicontest_Util_Util::session_start();

        if(is_admin()) {
            // Register needed plugin settings
            add_action('admin_init', function() {
                register_setting('kanawaicontest_settings', 'kanawaicontest_rules');
//                register_setting('kanawaicontest_settings', 'kanawaicontest_admin_email');
//                register_setting('kanawaicontest_settings', 'kanawaicontest_client_email_subject');
//                register_setting('kanawaicontest_settings', 'kanawaicontest_client_email_text');
//                register_setting('kanawaicontest_settings', 'kanawaicontest_additional_client_email_text');
//                register_setting('kanawaicontest_settings', 'kanawaicontest_tickets_email_text');
            });
//

            $this->tours = new KC_Tours_AdminMenu();
            $this->images = new KC_Images_AdminMenu();
            $this->voters = new KC_Voters_AdminMenu();

            // Create menu items
            // wp_get_current_user works not early than 'init' hook
            add_action('init', array($this, 'create_menu'));

            // Add needed scripts on page
            add_action('admin_enqueue_scripts', array($this, 'enqueue_bookingmanager_admin_scripts'));

//            // Check if array of admin notices exists in session
            add_action('admin_notices', array('Kanawaicontest_Util_Util', 'show_admin_notices'));

//            // Save all registered events before script execution ends
//            add_action('shutdown', array("BM_Event_Manager", 'save_all_events'));
        }
        
//        add_filter( 'login_redirect', array($this, 'redirect_to_home_page'), 10, 3);

        // When plugin activated, add new tables and user role
//        register_activation_hook(__FILE__, array($this, 'create_tables'));
//        register_activation_hook(__FILE__, array($this, 'add_roles'));


        add_action( 'admin_footer', array( $this, 'media_selector_print_scripts' ));






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

            add_action('admin_menu', function() {
                add_menu_page(
                    'Contest',
                    'Contest',
                    'manage_options',
                    'kanawaicontest',
                    array($this->tours, 'plugin_menu'),
                    'dashicons-calendar-alt',
                    NULL
                );
            });
            add_action('admin_menu', array($this->tours, 'plugin_menu'));
            add_action('admin_menu', array($this->images, 'plugin_menu'));
            add_action('admin_menu', array($this->voters, 'plugin_menu'));
            add_action('admin_menu', function() {
                add_submenu_page(
                    'kanawaicontest',
                    'Settings',
                    'Settings',
                    'manage_options',
                    'kanawaicontest_settings',
                    function() {
                        include KANAWAICONTEST_ROOT . '/settings/views/settings.php';;
                    });
            });
        }
//        elseif(in_array('booking_clerk', $user->roles)) {
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
//        }
//
        return TRUE;
    }


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
     * Returns data about current user
     *
     * @return array
     */



    /**
     * Enqueues script for removing not-necessary menu items
     */
//    public function enqueue_menu_items_hide_script()
//    {
//        wp_enqueue_script('bm_remove_menu_items', '/wp-content/plugins/bookingmanager/assets/js/remove_menu_items.js', array('jquery-ui-datepicker', 'jquery-ui-dialog'), FALSE, TRUE);
//    }

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

    public function media_selector_print_scripts() {

        $my_saved_attachment_post_id = get_option( 'media_selector_attachment_id', 0 );

        ?><script type='text/javascript'>
            jQuery( document ).ready( function( $ ) {
                // Uploading files
                var file_frame;
                var wp_media_post_id = wp.media.model.settings.post.id; // Store the old id
                var set_to_post_id = <?php echo $my_saved_attachment_post_id; ?>; // Set this
                jQuery('#upload_image_button').on('click', function( event ){
                    event.preventDefault();
                    // If the media frame already exists, reopen it.
                    if ( file_frame ) {
                        // Set the post ID to what we want
                        file_frame.uploader.uploader.param( 'post_id', set_to_post_id );
                        // Open frame
                        file_frame.open();
                        return;
                    } else {
                        // Set the wp.media post id so the uploader grabs the ID we want when initialised
                        wp.media.model.settings.post.id = set_to_post_id;
                    }
                    // Create the media frame.
                    file_frame = wp.media.frames.file_frame = wp.media({
                        title: 'Select a image to upload',
                        button: {
                            text: 'Use this image',
                        },
                        multiple: false	// Set to true to allow multiple files to be selected
                    });
                    // When an image is selected, run a callback.
                    file_frame.on( 'select', function() {
                        // We set multiple to false so only get one image from the uploader
                        attachment = file_frame.state().get('selection').first().toJSON();
                        // Do something with attachment.id and/or attachment.url here
                        $( '#image-preview' ).attr( 'src', attachment.url ).css( 'width', 'auto' );
                        $( '#image_attachment_id' ).val( attachment.id );
                        // Restore the main post ID
                        wp.media.model.settings.post.id = wp_media_post_id;
                    });
                    // Finally, open the modal
                    file_frame.open();
                });
                // Restore the main ID when the add media button is pressed
                jQuery( 'a.add_media' ).on( 'click', function() {
                    wp.media.model.settings.post.id = wp_media_post_id;
                });
            });
        </script><?php
    }

}

Kanawaicontest::get_instance();

