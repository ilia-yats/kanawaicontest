<?php
// Exit if accessed directly
if( ! defined('ABSPATH')) {
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
     * Messages to be displayed in popup window
     *
     * @var array
     */
    public static $popup_messages = [];

    public static function add_popup_message($key, $text)
    {
        self::$popup_messages[$key] = $text;
    }


//    /**
//     * Handler for unusual actions
//     * e.g. for request from remote APIs like LiqPay and so on
//     * @param $wp
//     */
//    public function bookingmanager_check_action($wp)
//    {
//        // catch only requests with "bookingmanager_action" parameter
//        if(array_key_exists('bookingmanager_action', $wp->query_vars)) {
//            switch($wp->query_vars['bookingmanager_action']) {
//
//                // LiqPay sends the status of payment, catch it and perform appropriate actions
//                // Link http://domain.com?bookingmanager_action=online_payment_result_handler
//                case 'online_payment_result_handler':
//                    include __DIR__ . '/online_payment_result_handler.php';
//                    wp_die();
//                    break;
//                // CRON-runnable script, which deletes outdated and not-paid reserves
//                // Link http://domain.com?bookingmanager_action=delete_outdated_reserves
//                case 'delete_outdated_reserves':
//                    include BM_ROOT . '/reserved_place/List.php';
//
//                    BM_Reserved_Place_List::delete_outdated_reserves();
//
//                    wp_die();
//                    break;
//            }
//        }
//    }

    /**
     * Registers plugin own action handler
     *
     * @param $vars
     * @return array
     */
    public function bookingmanager_register_actions($vars)
    {
        $vars[] = 'bookingmanager_action';

        return $vars;
    }

//    public function __construct()
//    {
//        // Capture POST, unlsash and store in class variable
//        self::$unslashed_post = wp_unslash($_POST);
//
//        // Start session
//        BM_Util_Util::session_start();
//
//        // Count new visitor (only if he is actually new)
//        BM_Util_Util::increment_visitors_count();
//
//        // Set request filters for catching all requests to specific bookingmanager actions
//        add_filter('query_vars', array($this, 'bookingmanager_register_actions'));
//        add_action('parse_request', array($this, 'bookingmanager_check_action'));
//
//        // If we aren't inside the admin panel, enqueue some scripts
//        add_action('wp_ajax_nopriv_calendar_and_places_schema_ajax_handler', array($this, 'calendar_and_places_schema_ajax_handler'));
//        add_action('wp_ajax_calendar_and_places_schema_ajax_handler', array($this, 'calendar_and_places_schema_ajax_handler'));
//
//        // Set needed ajax handlers
//        add_action('wp_ajax_nopriv_confirmation_ajax_handler', array($this, 'confirmation_ajax_handler'));
//        add_action('wp_ajax_confirmation_ajax_handler', array($this, 'confirmation_ajax_handler'));
//
////        add_action('wp_enqueue_scripts', array($this, 'enqueue_public_scripts'));
//    }

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

//    /**
//     * Renders needed part of view depending on the data we have already received from user
//     */
//    public static function booking()
//    {
//        // Determine the stage depending on the present parameters.
//        if( ! empty(self::$unslashed_post['passenger_name']) && ! empty(self::$unslashed_post['there_route_id'])) {
//            // If we have got information about passenger, try to get and prepare all needed data
//            //  and show the last, confirmation page with all information about ticket reserving
//
//            try {
//                $with_return = empty(self::$unslashed_post['return_route_id']) ? FALSE : TRUE;
//                $client = BM_Client_Data::feed_from_post(self::$unslashed_post);
//                $reserve = BM_Reserved_Place_Data::feed(self::$unslashed_post, $with_return);
//                self::enqueue_confirmation_page_scripts($reserve);
//                $current_page = 3;
//
//            } catch(Exception $e) {
//                self::add_popup_message('msg1', 'Ошибка ! Пожалуйста, проверьте корректность введенных данных');
//                $routes = BM_Route_List::get_all_routes();
//                $current_page = 1;
//            }
//
//        } elseif( ! empty($_REQUEST['route_id'])) {
//
//            try {
//                $route = BM_Route_Data::feed_from_db_by_id($_REQUEST['route_id']);
//                if($return_route_data = BM_Route_List::get_return_route($_REQUEST['route_id'])) {
//                    $return_route = BM_Route_Data::feed_from_db(( array ) $return_route_data);
//                } else {
//                    $return_route = NULL;
//                }
//                self::enqueue_calendar_and_places_schema_scripts($route, $return_route);
//
//                $current_page = 2;
//
//            } catch(Exception $e) {
//                self::add_popup_message('msg2', 'Ошибка ! Пожалуйста, проверьте корректность введенных данных');
//                $routes = BM_Route_List::get_all_routes();
//                $current_page = 1;
//            }
//
//        } else {
//            // By default, consider that we are on the first stage and show the table with available routes
//            $routes = BM_Route_List::get_all_routes();
//            $current_page = 1;
//        }
//
//        self::enqueue_public_scripts();
//
//        // Include view, which will render needed content depending on the $current_page
//        include_once BM_ROOT . '/public/views/booking.php';
//    }

//    /**
//     * Renders LiqPay payment button, which contain all needed payment data and references to payment page
//     *
//     * @param BM_Reserved_Place_Data $reserve
//     */
//    public static function render_pay_button(BM_Reserved_Place_Data $reserve)
//    {
//        // Init LiqPay SDK
//        include_once BM_ROOT . '/vendor/liqpay/LiqPay.php';
//
//        $liqpay = new LiqPay(BOOKINGMANAGER_LIQ_PUBLIC_KEY, BOOKINGMANAGER_LIQ_PRIVATE_KEY);
//
//        $lng = 'ru';
//        $order_id = time() . rand(1, 9999);
//        $action = 'pay';
//        $sandbox = BOOKINGMANAGER_LIQ_TEST_MODE;
//        $total = $reserve->there_data['ticket_price'];
//        if(isset($reserve->return_data['ticket_price'])) {
//            $total += $reserve->return_data['ticket_price'];
//        }
//
//        $params = array(
//            'version'           => '3',
//            'amount'            => number_format(str_replace(',', '.', $total), 2, '.', ''),
//            'currency'          => 'UAH', // USD | RUB | EUR | RUR
//            'description'       => 'Оплата за билеты',
//            'order_id'          => $order_id,
//            'language'          => $lng,
//            'action'            => $action,
//            'sandbox'           => $sandbox,
//            'sender_first_name' => $reserve->client->name,
//            'sender_last_name'  => $reserve->client->last_name,
//            'info'              => serialize($reserve),
//            'server_url'        => get_site_url() . '?bookingmanager_action=online_payment_result_handler',
//            'result_url'        => get_site_url(),
//        );
//
//        $params = $liqpay->cnb_params($params);
//        $data = base64_encode(json_encode($params));
//        $signature = $liqpay->cnb_signature($params);
//
//        // Print LiqPay button or standard site button to continue
//
//        // $html   = $liqpay->cnb_form($params);
//        // echo $html;
//
////        echo '$params[server_url]' , $params['server_url'];
//
//        include(BM_ROOT . '/public/views/payment_button.php');
//    }

//    // Enqueue scripts for page with calendar and schema of places
//    protected static function enqueue_calendar_and_places_schema_scripts(BM_Route_Data $route, BM_Route_Data $return_route = NULL)
//    {
//        // Pass parameters to javascript and enqueue needed scripts
//        wp_enqueue_script('bm_moment', '/wp-content/plugins/bookingmanager/assets/js/vendor/moment.min.js', array('jquery'), FALSE, TRUE);
//        wp_enqueue_script('bm_fullcalendar', '/wp-content/plugins/bookingmanager/assets/js/vendor/fullcalendar.min.js', array('bm_moment'), FALSE, TRUE);
//        wp_enqueue_script('bm_ru', '/wp-content/plugins/bookingmanager/assets/js/vendor/ru.js', array('bm_fullcalendar'), FALSE, TRUE);
//        wp_enqueue_script('bm_calendar_and_places_schema', '/wp-content/plugins/bookingmanager/assets/js/calendar_and_places_schema.js', array('bm_fullcalendar'), FALSE, TRUE);
//        wp_localize_script('bm_calendar_and_places_schema', 'bookingmanager', array(
//                'prevName'           => isset(self::$unslashed_post['passenger_name']) ? sanitize_text_field(self::$unslashed_post['passenger_name']) : '',
//                'prevLastName'       => isset(self::$unslashed_post['passenger_last_name']) ? sanitize_text_field(self::$unslashed_post['passenger_last_name']) : '',
//                'prevPhone'          => isset(self::$unslashed_post['passenger_phone']) ? sanitize_text_field(self::$unslashed_post['passenger_phone']) : '',
//                'prevEmail'          => isset(self::$unslashed_post['passenger_email']) ? sanitize_text_field(self::$unslashed_post['passenger_email']) : '',
//                'thereRouteId'       => absint($route->id),
//                'thereRouteBusType'  => absint($route->bus_type),
//                'returnRouteId'      => ($return_route !== NULL) ? absint($return_route->id) : '',
//                'returnRouteBusType' => ($return_route !== NULL) ? absint($return_route->bus_type) : '',
//                'startDate'          => date('Y-m-d'),
//                'ajaxHandler'        => admin_url('admin-ajax.php'),
//                'action'             => 'calendar_and_places_schema_ajax_handler',
//                'nonce'              => wp_create_nonce('bm_trips_calendar_and_places_schema'),
//            )
//        );
//    }
//
//    // Pass given data to javascript and enqueue needed scripts for page with confirmation of reserving
//    public static function enqueue_confirmation_page_scripts(BM_Reserved_Place_Data $reserve)
//    {
//        wp_enqueue_script('bm_jquery_printarea', '/wp-content/plugins/bookingmanager/assets/js/vendor/jquery.PrintArea.js', array('jquery'));
//        wp_enqueue_script('bm_confirmation', '/wp-content/plugins/bookingmanager/assets/js/confirmation.js', array('bm_jquery_printarea'));
//        wp_localize_script('bm_confirmation', 'bookingmanager', array(
//                'reserve'     => serialize($reserve),
//                'cssHref'     => get_stylesheet_directory_uri() . '/css/style.min.css',
//                'ajaxHandler' => admin_url('admin-ajax.php'),
//                'action'      => 'confirmation_ajax_handler',
//                'nonce'       => wp_create_nonce('bm_confirmation_nonce'),
//            )
//        );
//    }
//
//    // Handler for AJAX request from fullCalendar plugin
//    public function calendar_and_places_schema_ajax_handler()
//    {
//        $action = isset(self::$unslashed_post['bm_action']) ? self::$unslashed_post['bm_action'] : null;
//        $nonce = isset(self::$unslashed_post['nonce']) ? self::$unslashed_post['nonce'] : null;
//
//        if($action && wp_verify_nonce($nonce, 'bm_trips_calendar_and_places_schema')) {
//            switch($action){
//                case 'get_dates':
//                    // If requested data about returning route ($is_return flag is true), set start date from request, else set today
//                    $is_return_route = absint(self::$unslashed_post['isReturn']);
//                    if($is_return_route) {
//                        $startDate = self::$unslashed_post['return_start_date'];
//                    } else {
//                        $startDate = date('Y-m-d');
//                    }
//
//                    $trips_data = BM_Trips_Calendar_List::public_get_places_data($startDate, self::$unslashed_post['end'], self::$unslashed_post['route_id'], self::$unslashed_post['bus_type']);
//
//                    wp_die(json_encode($trips_data));
//
//                    break;
//                case 'get_schema':
//                    $schema_html = '';
//                    if(isset(self::$unslashed_post['direction']) && isset(self::$unslashed_post['bus_type'])) {
//                        $schema_file = BM_ROOT . '/public/views/';
//                        $schema_file .= self::$unslashed_post['direction'] . '_bus_type_' . self::$unslashed_post['bus_type'] . '.php';
//
//                        if(file_exists($schema_file)) {
//                            $schema_html = file_get_contents($schema_file);
//                        }
//                    }
//                    wp_die($schema_html);
//
//                    break;
//            }
//        }
//
//        wp_die('error');
//    }
//
//    /**
//     * Handler for AJAX request for reserving places, does the main work.
//     * Reserves places without paying
//     */
//    public function confirmation_ajax_handler()
//    {
//        global $wpdb;
//
//        // Default response
//        $response = [
//            'status'  => 0,
//            'message' => 'Не удалось забронировать места.',
//        ];
//
//        if(isset(self::$unslashed_post['nonce']) && wp_verify_nonce(self::$unslashed_post['nonce'], 'bm_confirmation_nonce')) {
//
//            // Try to validate data
//            try {
//
//                // Unserialize data object and check if it is valid
//                $reserve = unserialize(self::$unslashed_post['reserve']);
//                if( ! $reserve instanceof BM_Reserved_Place_Data
//                    || ! $reserve->there_route instanceof BM_Route_Data
//                    || ($reserve->with_return && ! $reserve->return_route instanceof BM_Route_Data)
//                    || ! $reserve->client instanceof BM_Client_Data
//                ) {
//                    throw new Exception('Invalid reserve data');
//                }
//
//                // Set additional properties
//                $reserve->payment_type = self::$unslashed_post['payment_type'];
//                $reserve->client_ip = BM_Util_Util::get_client_ip();
//                $reserve->booking_datetime = current_time('mysql');
//
//                if(BM_Reserved_Place_List::public_insert_reserved_places($reserve)) {
//                    $response['status'] = 1;
//                    $response['message'] = 'Места успешно забронированы';
//
//                    // Increment counter of visitors, which have reserved places
//                    BM_Util_Util::increment_reservers_count();
//
//                    // Send emails to admin and client
//                    BM_Util_Util::notify_admin($reserve);
//
//                    // Notify client only if in-cash payment
//                    if($reserve->payment_type == 'cash') {
//                        BM_Util_Util::notify_client($reserve);
//                    }
//                }
//
//            } catch(BM_Exception_NotFullDataException $e) {
//                $response['message'] = 'Не удалось забронировать места. Похоже, не все необходимые данные заполнены корректно.';
//            } catch(BM_Exception_InvalidEmailException $e) {
//                $response['message'] = 'Не удалось забронировать места. Похоже, указан некорректный E-mail';
//            } catch(Exception $e) {
//                $response['message'] = 'Неизвестная ошибка';
//            }
//        }
//
//        // Echo JSON response
//        wp_die(json_encode($response));
//    }

//    /**
//     * Enqueues additional scripts in public area of site
//     */
//    public static function enqueue_public_scripts()
//    {
//        // If some popup messages was set, add script to show them
//        if( ! empty(self::$popup_messages)) {
//            wp_enqueue_script('bm_popup_message', '/wp-content/plugins/bookingmanager/assets/js/popup_message.js', array('jquery'));
//            wp_localize_script('bm_popup_message', 'msg', array(
//                    'messages'        => json_encode(self::$popup_messages, JSON_UNESCAPED_UNICODE),
//                )
//            );
//        }
//    }

    public static function get_images()
    {
        return KC_Images_List::get_images();
    }
}

KC_Public_Kanawaicontest::get_instance();