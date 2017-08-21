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

        // Set needed ajax handlers
        add_action('wp_ajax_nopriv_kanawaicontest_vote', array($this, 'submit_vote_ajax_handler'));
        add_action('wp_ajax_kanawaicontest_vote', array($this, 'submit_vote_ajax_handler'));

        $templateDir = get_stylesheet_directory_uri();
        wp_register_style('kc-main-style', $templateDir . '/css/main.css');
        wp_enqueue_style('kc-main-style');
        wp_register_script('kc-main-script', $templateDir . '/js/index.js', ['jquery']);
        wp_enqueue_script('kc-main-script');

        wp_localize_script('kc-main-script', 'kanawaicontest', array(
                'ajaxHandler' => admin_url('admin-ajax.php'),
                'action'      => 'kanawaicontest_vote',
                'nonce'       => wp_create_nonce('kanawaicontest_vote_nonce'),
            )
        );
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
            'status' => 'fail',
//            'message' => 'Error',
        ];

        if (isset(self::$unslashed_post['nonce']) && wp_verify_nonce(self::$unslashed_post['nonce'], 'kanawaicontest_vote_nonce')) {

            parse_str(self::$unslashed_post['form'], $form_data);

            // Try to validate data
            try {
                $name = isset($form_data['first_name']) ? sanitize_text_field($form_data['first_name']) : '';
                $last_name = isset($form_data['last_name']) ? sanitize_text_field($form_data['last_name']) : '';
                $email = isset($form_data['email']) ? sanitize_text_field($form_data['email']) : '';
                $phone = isset($form_data['phone']) ? sanitize_text_field($form_data['phone']) : '';
                $voted_posters_ids = isset(self::$unslashed_post['chosen_ids']) ? self::$unslashed_post['chosen_ids'] : array();
                foreach ($voted_posters_ids as &$id) {
                    $id = explode('-', $id)[1];
                }
                unset($id);
                $voter_id = Kanawaicontest::get_instance()->voters->init()->voters_list->get_id_by_email($email);
                if (empty($voter_id)) {
                    $voter_id = Kanawaicontest::get_instance()->voters->init()->voters_list->create_voter(
                        $name, $last_name, $email, $phone
                    );
                } else if ($this->count_votes_of_voter_in_tour($voter_id) > KANAWAICONTEST_MAX_POSTERS_TO_VOTE) {
                    throw new \Exception('Cannot save vote');
                }
                $current_tour_id = Kanawaicontest::get_instance()->tours->init()->tours_list->get_current_tour_id();
                foreach ($voted_posters_ids as $id) {
                    if (!Kanawaicontest::get_instance()->saveVote($id, $voter_id, $current_tour_id)) {
                        throw new \Exception('Cannot save vote');
                    }
                }
                $response['status'] = 'success';
            } catch (Exception $e) {
//                echo $e->getMessage();
//                $response['message'] = 'Error';
            }
        }

        // Echo JSON response
        wp_die(json_encode($response));
    }

    public function count_votes_of_voter_in_tour($voter_id)
    {
        global $wpdb;

        return $wpdb->get_var("SELECT COUNT(*) FROM kanawaicontest_posters_votes WHERE voter_id = "
            . absint($voter_id) . " AND tour_id = " . absint(KC_Tours_List::get_current_tour_id()));
    }

    public static function render_posters()
    {
        global $wpdb;

        $sql = "SELECT kci.* FROM kanawaicontest_posters kci";
        $sql .= ' WHERE tour_id = ' . KC_Tours_List::get_current_tour_id();
        $posters = $wpdb->get_results($sql, 'ARRAY_A');
        foreach ($posters as &$item) {
            $item['image_url'] = wp_get_attachment_image_src($item['attachment_id'])[0];
        }
        foreach ($posters as $poster): ?>
            <div class="img-container" id="image-<?php echo $poster['id']?>">
                <div class="img" data-background="<?php echo $poster['image_url']; ?>">
                    <div class="layer"></div>
                </div>
                <a href="<?php echo $poster['link']; ?>" class="link"></a>
                <svg class="star-button" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 70.0354767 67.0449219"  xml:space="preserve">
                    <path d="M53.9242935,67.0449219c-0.7185936,0-1.4357224-0.1764832-2.0729027-0.51091
                        l-16.4696312-8.6591988c-0.2037811-0.1092033-0.4226723-0.1033516-0.6069527-0.0058479l-16.486208,8.6582184
                        c-1.4323101,0.7663727-3.3243465,0.6522903-4.6854811-0.3266296c-1.3855104-1.0218277-2.0607147-2.6891212-1.7803955-4.3632393
                        l3.1420183-18.3489914c0.0380259-0.2164574-0.0341263-0.4368134-0.186718-0.5859909L1.4533342,29.9159775
                        c-1.2041557-1.1758804-1.6473044-2.9679756-1.1300538-4.5670166c0.5182257-1.6029396,1.9324991-2.7953949,3.6032043-3.0362263
                        l18.4113922-2.6744938c0.2125568-0.0321751,0.407074-0.1696529,0.4997005-0.3588085l8.2428589-16.6885242
                        c0.7512569-1.530789,2.2820473-2.4853382,3.9927292-2.4853382c1.715065,0,3.2482948,0.9545492,4.0014992,2.4902134
                        l8.2253113,16.6807232c0.0955505,0.1901302,0.287632,0.3295593,0.5089607,0.362709l18.4109039,2.6744938
                        c1.6658325,0.2408314,3.0776672,1.432312,3.5958939,3.0352516c0.5255356,1.6243916,0.0926285,3.3745613-1.1295624,4.5670166
                        L55.3678169,42.9033089c-0.1599045,0.1540527-0.2286415,0.3724594-0.1920776,0.5918388l3.1395798,18.3353424
                        c0.2905579,1.6702156-0.3836746,3.3414078-1.7608948,4.3564072C55.7768402,66.751442,54.8715286,67.0449219,53.9242935,67.0449219z"
                    />
                </svg>
            </div>
        <?php endforeach;
    }
}

KC_Public_Kanawaicontest::get_instance();