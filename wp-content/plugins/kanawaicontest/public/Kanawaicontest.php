<?php
// Exit if accessed directly
if ( ! defined('ABSPATH')) {
    exit;
}

include_once(__DIR__ . '/../init.php');

class KC_Public_Kanawaicontest
{
    /**
     * Instance of this class.
     *
     * @var static
     */
    protected static $instance;

    /*
     * Unslashed post array.
     *  We need to delete slashes from all values in POST and save in class static field
     *  (even if magic_quotes_qpc is disabled, WP adds slashes to all incoming values on its own,
     *  so we need to unslash before inserting into database; see: http://wp-kama.ru/function/wp_unslash)
     */
    public static $unslashed_post;

    /**
     * Handler for unusual actions
     * e.g. for request from remote APIs like LiqPay and so on
     * @param $wp
     */
    public function kanawaicontest_check_action($wp)
    {
        // catch only requests with "kanawaicontest_action" parameter
        if (array_key_exists('kanawaicontest_action', $wp->query_vars)) {
            switch ($wp->query_vars['kanawaicontest_action']) {
                case 'submit_vote':
                    //

                    break;
            }
        }
    }

    /**
     * Registers plugin own action handler
     *
     * @param $vars
     * @return array
     */
    public function kanawaicontest_register_actions($vars)
    {
        $vars[] = 'kanawaicontest_action';

        return $vars;
    }

    public function __construct()
    {
        // Capture POST, unslash and store in class variable
        self::$unslashed_post = wp_unslash($_POST);

        // Set request filters for catching all requests to specific bookingmanager actions
        add_filter('query_vars', array($this, 'kanawaicontest_register_actions'));
        add_action('parse_request', array($this, 'kanawaicontest_check_action'));

        // Set needed ajax handlers
        add_action('wp_ajax_nopriv_confirmation_ajax_handler', array($this, 'submit_vote_ajax_handler'));
        add_action('wp_ajax_confirmation_ajax_handler', array($this, 'submit_vote_ajax_handler'));
    }

    /**
     * Singleton instance.
     *
     * @return object
     */
    public static function get_instance()
    {
        if ( ! isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Handler for AJAX request
     */
    public function submit_vote_ajax_handler()
    {
        // Default response
        $response = [
            'status' => 0,
            'message' => 'Error',
        ];

        if (isset(self::$unslashed_post['nonce']) && wp_verify_nonce(self::$unslashed_post['nonce'], 'kanawaicontest_submit_vote_nonce')) {

            // Try to validate data
            try {
                $name = isset($post['name']) ? sanitize_text_field($post['name']) : '';
                $last_name = isset($post['last_name']) ? sanitize_text_field($post['last_name']) : '';
                $email = isset($post['email']) ? sanitize_text_field($post['email']) : '';
                $phone = isset($post['phone']) ? sanitize_text_field($post['phone']) : '';
                $voted_posters_ids = isset($post['voted_posters_ids'])
                    ? implode(',', sanitize_text_field($post['voted_posters_ids']))
                    : array();

                $voter_id = Kanawaicontest::get_instance()->get_voters_list()->get_id_by_email($email);
                if (empty($voter_id)) {
                    $voter_id = Kanawaicontest::get_instance()->get_voters_list()->create_voter(
                        $name, $last_name, $email, $phone
                    );
                }
                $current_tour = Kanawaicontest::get_instance()->get_tours_list()->get_current_tour();
                foreach ($voted_posters_ids as $id) {
                    Kanawaicontest::get_instance()->saveVote($id, $voter_id, $current_tour['id']);
                }
            } catch (Exception $e) {
                $response['message'] = 'Error';
            }
        }

        // Echo JSON response
        wp_die(json_encode($response));
    }

    public static function get_current_tour_images()
    {
        return Kanawaicontest::get_instance()->get_current_tour_images();
    }
}

KC_Public_Kanawaicontest::get_instance();