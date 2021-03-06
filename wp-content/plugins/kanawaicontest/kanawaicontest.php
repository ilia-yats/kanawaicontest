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
if ( ! defined('ABSPATH')) {
    exit;
}

include_once(__DIR__ . '/init.php');

class Kanawaicontest
{
    protected static $instance;

    /*
     * Delete slashes from all values in POST and save in class static field
     *  (even if magic_quotes_qpc is disabled, WP adds slashes to all incoming values on its own, 
     *  so we need to unslash before inserting into database; see: http://wp-kama.ru/function/wp_unslash)
     */
    public static $unslashed_post;

    public $archive;
    public $posters;
    public $voters;

    public function __construct()
    {
        // Capture POST, unslash and store in class variable
        self::$unslashed_post = wp_unslash($_POST);

        // Start session
        Kanawaicontest_Util_Util::session_start();

        $this->posters = new KC_Posters_AdminMenu();
        $this->tours = new KC_Tours_AdminMenu();
        $this->voters = new KC_Voters_AdminMenu();

        if (is_admin()) {
            // Register needed plugin settings
            add_action('admin_init', function () {
                register_setting('kanawaicontest_settings', 'kanawaicontest_button_activated');
                register_setting('kanawaicontest_settings', 'kanawaicontest_main_banner_attachment_id');
                register_setting('kanawaicontest_settings', 'kanawaicontest_contest_finished_message');
                register_setting('kanawaicontest_settings', 'kanawaicontest_rules');
                register_setting('kanawaicontest_settings', 'kanawaicontest_terms_and_conditions');
            });

            // Create menu items
            // wp_get_current_user works not early than 'init' hook
            add_action('init', array($this, 'create_menu'));

            // Add needed scripts on page
            add_action('admin_enqueue_scripts', array($this, 'enqueue_kanawaicontest_admin_scripts'));

            // Check if array of admin notices exists in session
            add_action('admin_notices', array('Kanawaicontest_Util_Util', 'show_admin_notices'));

            add_action('admin_footer', array($this, 'media_selector_print_scripts'));
        }

        // When plugin activated, add new tables and user role
//        register_activation_hook(__FILE__, array($this, 'create_tables'));
    }

    public static function get_instance()
    {
        if ( ! isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function create_tables()
    {
        // TODO: create SQL dump
    }

    public function create_menu()
    {
        $user = wp_get_current_user();

        if ( ! $user instanceof WP_User) {

            return FALSE;
        }

        // Show all menu items for admins
        if (in_array('administrator', $user->roles)) {

            add_action('admin_menu', function () {
                add_menu_page(
                    'Contest',
                    'Contest',
                    'manage_options',
                    'kanawaicontest',
                    array($this->posters, 'plugin_menu'),
                    'dashicons-calendar-alt',
                    NULL
                );
            });
            add_action('admin_menu', array($this->posters, 'plugin_menu'));
            add_action('admin_menu', array($this->voters, 'plugin_menu'));
            add_action('admin_menu', array($this->tours, 'plugin_menu'));
            add_action('admin_menu', function () {
                add_submenu_page(
                    'kanawaicontest',
                    'Settings',
                    'Settings',
                    'manage_options',
                    'kanawaicontest_settings',
                    function () {
                        include KANAWAICONTEST_ROOT . '/settings/views/settings.php';;
                    });
            });
        }

        return TRUE;
    }

    public function enqueue_kanawaicontest_admin_scripts()
    {
        wp_enqueue_script('kc_admin_script', '/wp-content/plugins/kanawaicontest/assets/admin.js', array('jquery'), false, true);
        wp_localize_script('kc_admin_script', 'kanawaicontest', array(
                'confirmArchiveMsg' => __('Are you sure you want to finish current contest and send all images to archive?'),
            )
        );
    }

    public function media_selector_print_scripts()
    {
        $my_saved_attachment_post_id = get_option('media_selector_attachment_id', 0);

        ?>
        <script type='text/javascript'>
            jQuery(document).ready(function ($) {
                // Uploading files
                var file_frame;
                var wp_media_post_id = wp.media.model.settings.post.id; // Store the old id
                var set_to_post_id = <?php echo $my_saved_attachment_post_id; ?>; // Set this
                jQuery('#upload_image_button').on('click', function (event) {
                    event.preventDefault();
                    // If the media frame already exists, reopen it.
                    if (file_frame) {
                        // Set the post ID to what we want
                        file_frame.uploader.uploader.param('post_id', set_to_post_id);
                        // Open frame
                        file_frame.open();
                        return;
                    } else {
                        // Set the wp.media post id so the uploader grabs the ID we want when initialised
                        wp.media.model.settings.post.id = set_to_post_id;
                    }
                    // Create the media frame.
                    file_frame = wp.media.frames.file_frame = wp.media({
                        title: '<?php _e('Select an image to upload') ?>',
                        button: {
                            text: '<?php _e('Use this image') ?>',
                        },
                        multiple: false	// Set to true to allow multiple files to be selected
                    });
                    // When an image is selected, run a callback.
                    file_frame.on('select', function () {
                        // We set multiple to false so only get one image from the uploader
                        attachment = file_frame.state().get('selection').first().toJSON();
                        // Do something with attachment.id and/or attachment.url here
                        $('#image-preview').attr('src', attachment.url).css('width', 'auto');
                        $('#image_attachment_id').val(attachment.id);
                        // Restore the main post ID
                        wp.media.model.settings.post.id = wp_media_post_id;
                    });
                    // Finally, open the modal
                    file_frame.open();
                });
                // Restore the main ID when the add media button is pressed
                jQuery('a.add_media').on('click', function () {
                    wp.media.model.settings.post.id = wp_media_post_id;
                });
            });
        </script><?php
    }

    public function saveVote($poster_id, $voter_id, $tour_id)
    {
        global $wpdb;

        if (!empty($tour_id) && !empty($poster_id) && !empty($voter_id)) {
            $sql = $wpdb->prepare(
                "INSERT INTO kanawaicontest_posters_votes(poster_id, voter_id, tour_id) VALUES(%d, %d, %d)",
                $poster_id, $voter_id, $tour_id
            );
            $result = $wpdb->query($sql);

            return $result;
        }

        return false;
    }
}

Kanawaicontest::get_instance();

