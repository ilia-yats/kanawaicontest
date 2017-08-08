<?php
// Exit if accessed directly
if( ! defined('ABSPATH')) {
    exit;
}

if( ! class_exists('WP_List_Table')) {
    require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}

/**
 * Manipulates with information about reserved places on certain date and certain route
 *
 * Class Reserved_places_List
 */
class BM_Reserved_Place_List extends WP_List_Table
{
    /**
     * Id of route, on which all places in current instance are reserved
     *
     * @var int|null
     */
    public static $route_id = NULL;

    /**
     * Date, on which all places in current instance are reserved
     *
     * @var null|string
     */
    public static $trip_date = NULL;


    /* ---------------------------------- Methods for administrative part of site ----------------- */


    /**
     * Returns base part of SQL query to get the places data
     *
     * @return string
     */
    public static function get_select_query_base()
    {
        global $wpdb;

        return "SELECT
                    place.*,
                    client.name AS name,
                    client.last_name AS last_name,
                    client.phone AS phone,
                    client.email AS email,
                    client.is_blacklisted AS is_blacklisted,
                    route.name AS route_name,
                    user.display_name AS initiator_name
                FROM {$wpdb->prefix}bm_reserved_places AS place
                LEFT JOIN {$wpdb->prefix}bm_clients AS client ON place.client_id = client.id
                LEFT JOIN {$wpdb->prefix}bm_routes AS route ON place.route_id = route.id
                LEFT JOIN {$wpdb->prefix}users AS user ON place.reserved_by_user_id = user.ID
                WHERE ";
    }

    /**
     * Returns part of SQL query for searching by substring
     *
     * @param string $search_string
     * @return string  SQL 'where' clause
     */
    public static function create_search_sql_where_clause($search_string)
    {
        $search_string = esc_sql($search_string);

        return ' (name LIKE "%' . $search_string . '%"
            OR last_name LIKE "%' . $search_string . '%"
            OR phone LIKE "%' . $search_string . '%"
            OR ticket_code LIKE "%' . $search_string . '%"
            OR initiator_name LIKE "%' . $search_string . '%"
            OR email LIKE "%' . $search_string . '%")';
    }

    /**
     *  Returns part of SQL query for filtering
     *
     * @return string  SQL 'where' clause
     */
    public static function create_filter_sql_where_clause()
    {
        $filter_clause_sql = '';
        if( ! empty($_POST['email'])) {
            $filter_clause_sql = " client.email = '" . esc_sql($_POST['email']) . "' AND ";
        }
        if( ! empty($_POST['ticket_code'])) {
            $filter_clause_sql = " ticket_code = '" . esc_sql($_POST['ticket_code']) . "' AND ";
        }
        if( ! empty($_POST['route_name'])) {
            $filter_clause_sql = " route.name = '" . esc_sql($_POST['route_name']) . "' AND ";
        }

        return $filter_clause_sql;
    }


    /**
     * Constructor.
     *
     * @param $route_id
     * @param $trip_date
     */
    public function __construct($route_id, $trip_date)
    {
        global $wpdb;

        parent::__construct(array(
            'singular' => 'Забронированное место', // __('Reserved_place', 'bookingmanager_textdomain'),
            'plural'   => 'Забронированные места', //  __('Reserved_places', 'bookingmanager_textdomain'),
            'ajax'     => FALSE,
        ));

        self::$route_id = $route_id;
        self::$trip_date = $trip_date;
    }

    /**
     * Retrieve reserved_place data from the database.
     *
     * @param int $id
     *
     * @return object
     */
    public static function get_reserved_place($id = 0)
    {
        global $wpdb;

        return $wpdb->get_row($wpdb->prepare(
            static::get_select_query_base() . " place.id = %d", $id
        ), 'ARRAY_A');
    }

    /**
     * Retrieve reserved_places data from the database.
     *
     * @param int $per_page
     * @param int $page_number
     *
     * @return mixed
     */
    public static function get_reserved_places($per_page = 20, $page_number = 1)
    {
        global $wpdb;

        $sql = static::get_select_query_base() . static::create_filter_sql_where_clause();

        if( ! empty($_REQUEST['s'])) {
            $sql .= self::create_search_sql_where_clause($_REQUEST['s']) . ' AND';
        }

        if( ! empty(self::$route_id) && ! empty(self::$trip_date)) {
            $sql .= " route_id = " . self::$route_id . " AND trip_date = '" . self::$trip_date . "'";
        } else {
            $sql .= ' 1 ';
        }

        $order_by = ! empty($_REQUEST['orderby']) ? esc_sql($_REQUEST['orderby']) : 'booking_datetime';
        $order = ! empty($_REQUEST['order']) ? esc_sql($_REQUEST['order']) : 'DESC';

        $sql .= ' ORDER BY ' . $order_by . ' ';
        $sql .= $order;

        $sql .= " LIMIT $per_page";
        $sql .= ' OFFSET ' . ($page_number - 1) * $per_page;

        $result = $wpdb->get_results($sql, 'ARRAY_A');

        return $result;
    }


    /**
     * Deletes a reserved_place record.
     *
     * @param int $id reserved_place id
     * @return boolean
     */
    public static function delete_reserved_place_by_id($id)
    {
        global $wpdb;

        return ( boolean ) $wpdb->delete(
            "{$wpdb->prefix}bm_reserved_places",
            ['id' => $id],
            ['%d']
        );
    }

    /**
     * Returns the count of records in the database.
     *
     * @return null|string
     */
    public static function record_count()
    {
        global $wpdb;

        $sql = "SELECT COUNT(*) FROM {$wpdb->prefix}bm_reserved_places AS place
                LEFT JOIN {$wpdb->prefix}bm_clients AS client ON place.client_id = client.id
                LEFT JOIN {$wpdb->prefix}bm_routes AS route ON place.route_id = route.id
                WHERE";

        $sql .= static::create_filter_sql_where_clause();

        if( ! empty($_REQUEST['s'])) {
            $sql .= self::create_search_sql_where_clause($_REQUEST['s']) . ' AND';
        }

        if( ! empty(self::$route_id) && ! empty(self::$trip_date)) {
            $sql .= " route_id = " . self::$route_id . " AND trip_date = '" . self::$trip_date . "'";
        } else {
            $sql .= ' 1';
        }

        return $wpdb->get_var($sql);
    }

    /**
     * Text displayed when no reserved_place data is available.
     *
     * @return void
     */
    public function no_items()
    {
        echo 'Нет зарезервированных мест';
    }

    /**
     * Render a column when no column specific method exist.
     *
     * @param array $item
     * @param string $column_name
     *
     * @return string
     */
    public function column_default($item, $column_name)
    {
        $value = '';

        switch($column_name) {
            case 'booking_datetime':
                $value = date('d.m.Y H:i:s', strtotime($item[$column_name]));
                break;
            case 'trip_date':
                $value = date('d.m.Y', strtotime($item[$column_name]));
                break;
            case 'place_number':
            case 'email':
            case 'phone':
            case 'ticket_code':
            case 'client_ip':
            case 'route_name':
            case 'initiator_name':
                $value = $item[$column_name];
                break;
            case 'is_paid':
                $value = ($item[$column_name] == 1) ? '<strong>Да</strong>' : '<strong>Нет</strong>';
                break;
            case 'full_name':
                $value = $item['name'] . ' ' . $item['last_name'];
                break;
            default:
                $value = '-';
        }

        return $value;
    }

    public function single_row($item)
    {
        // Get emails and phones from blacklist
        $blacklisted_attrs = BM_Client_List::get_blacklisted_attributes();

        // Check if client is blacklisted
        $is_blacklisted = (isset($item['is_blacklisted']) && $item['is_blacklisted'] == 1) ? TRUE : FALSE;
        // Check if client is suspected in presence in a blacklist
        $is_suspected = (in_array($item['phone'], $blacklisted_attrs['phones']) || in_array($item['email'], $blacklisted_attrs['emails']))
            ? TRUE
            : FALSE;

        $additional_style = '';

        // If item is blacklisted or suspected, highlight this row with special styles
        if($is_blacklisted) {
            $additional_style = 'style="background-color:orange;"';
        } elseif($is_suspected) {
            $additional_style = 'style="background-color:yellow;"';
        }

        echo '<tr ' . $additional_style . '>';
        $this->single_row_columns($item);
        echo '</tr>';
    }


    public function column_ticket_code($item)
    {
        $nonce = wp_create_nonce('bm_download_ticket');

        $actions['download_ticket'] = sprintf(
            '<a class="" href="?page=%s&action=%s&id=%d&_wpnonce=%s">%s</a>',
            esc_attr($_REQUEST['page']),
            'download_ticket',
            absint($item['id']),
            $nonce,
            '<strong>Скачать билет</strong>'
        );

        return $item['ticket_code'] . '<br><br>' . $this->row_actions($actions);
    }

    public function column_is_paid($item)
    {
        $actions = [];
        $nonce = wp_create_nonce('bm_switch_payment_status');

        $page_url = menu_page_url('bookingmanager_places', FALSE);


        if($item['is_paid'] == 0) {
            $link = add_query_arg(
                [
                    'action'   => 'pay',
                    'id'       => absint($item['id']),
                    '_wpnonce' => $nonce,
                ],
                $page_url
            );
            $actions['pay'] = '<a class="" '
                . 'onclick="return confirm(\'Вы действительно хотите отметить резерв как оплаченный ?\')" '
                . 'href="' . $link . '"><strong>Оплатить</strong></a>';

        } else {
            $link = add_query_arg(
                [
                    'action'   => 'unpay',
                    'id'       => absint($item['id']),
                    '_wpnonce' => $nonce,
                ],
                $page_url
            );
            $actions['unpay'] = '<a class="" '
                . 'onclick="return confirm(\'Вы действительно хотите отменить оплату за резерв ?\')" '
                . 'href="' . $link . '"><b>Отменить оплату</b></a>';
        }

        $value = ($item['is_paid'] == 1) ? '<strong>Да</strong>' : '<strong>Нет</strong>';

        return $value . '<br><br>' . $this->row_actions($actions);
    }

    /**
     * Method for name column.
     *
     * @param array $item an array of DB data
     *
     * @return string
     */
    function column_full_name($item)
    {
        $from_full_list = 0;

        $trip_date = self::$trip_date;
        if(empty($trip_date)) {
            $trip_date = esc_attr($item['trip_date']);
            $from_full_list = 1;
        }
        $route_id = self::$route_id;
        if(empty($route_id)) {
            $route_id = esc_attr($item['route_id']);
            $from_full_list = 1;
        }

        $actions = [
            'edit' => sprintf(
                '<a href="?page=%s&action=%s&id=%d&trip_date=%s&route_id=%d&from_full_list=%d">Изменить</a>',
                esc_attr($_REQUEST['page']),
                'edit',
                absint($item['id']),
                $trip_date,
                $route_id,
                $from_full_list
            ),
        ];

        return $item['last_name'] . ' ' . $item['name'] . '<br><br>' . '<strong>' . $this->row_actions($actions) . '</strong>';
    }

    /**
     * Render the bulk edit checkbox.
     *
     * @param array $item
     *
     * @return string
     */
    function column_cb($item)
    {
        return sprintf(
            '<input type="checkbox" name="bulk-affected[]" value="%s" />', $item['id']
        );
    }

    /**
     *  Associative array of columns.
     *
     * @return array
     */
    function get_columns()
    {
        $columns = [
            'cb'               => '<input type="checkbox" />',
            'booking_datetime' => 'Время резервирования', // __('Booking Time', 'bookingmanager_textdomain'),
            'initiator_name'   => 'Кем зарезервировано', // __('Is Paid', 'bookingmanager_textdomain'),
            'client_ip'        => 'IP-адрес', // __('IP-address', 'bookingmanager_textdomain'),
            'full_name'        => 'ФИО пассажира', // __('Client Last Name', 'bookingmanager_textdomain'),
            'email'            => 'E-mail', // __('Client Email', 'bookingmanager_textdomain'),
            'phone'            => 'Телефон', // __('Client Phone', 'bookingmanager_textdomain'),
            'trip_date'        => 'Дата поездки', // __('Place Number', 'bookingmanager_textdomain'),
            'route_name'       => 'Маршрут', // __('Route Id', 'bookingmanager_textdomain'),
            'place_number'     => 'Место', // __('Place Number', 'bookingmanager_textdomain'),
            'ticket_code'      => 'Код билета', // __('Ticket Code', 'bookingmanager_textdomain'),
            'is_paid'          => 'Оплата', // __('Is Paid', 'bookingmanager_textdomain'),
        ];

        // Remove 'date' and 'route' columns if needed
        if( ! empty(self::$route_id)) {
            unset($columns['route_name']);
        }
        if( ! empty(self::$trip_date)) {
            unset($columns['trip_date']);
        }

        return $columns;
    }

    /**
     * Columns to make sortable.
     *
     * @return array
     */
    public function get_sortable_columns()
    {
        $sortable_columns = array(
            'place_number'     => array('place_number', TRUE),
            'route_name'       => array('route_name', TRUE),
            'is_paid'          => array('is_paid', TRUE),
            'booking_datetime' => array('booking_datetime', TRUE),
            'trip_date'        => array('trip_date', TRUE),
            'email'            => array('email', TRUE),
            'phone'            => array('phone', TRUE),
            'ticket_code'      => array('ticket_code', TRUE),
            'client_ip'        => array('client_ip', TRUE),
        );

        // Remove 'date' and 'route' columns if needed
        if( ! empty(self::$route_id)) {
            unset($sortable_columns['route_name']);
        }
        if( ! empty(self::$trip_date)) {
            unset($sortable_columns['trip_date']);
        }

        return $sortable_columns;
    }

    /**
     * Returns an associative array containing the bulk action.
     *
     * @return array
     */
    public function get_bulk_actions()
    {
        $actions = [
            'bulk-disclaim' => 'Отменить резервирование и оплату',
        ];

        return $actions;
    }

    /**
     * Handles data query and filter, sorting, and pagination.
     *
     * @return void
     */
    public function prepare_items()
    {
        $this->_column_headers = $this->get_column_info();

        $per_page = $this->get_items_per_page('places_per_page', 5);
        $current_page = $this->get_pagenum();
        $total_items = $this->record_count();

        $this->set_pagination_args([
            'total_items' => $total_items,
            'per_page'    => $per_page,
        ]);

        $this->items = self::get_reserved_places($per_page, $current_page);
    }


    /* ------------------------------------------------- actions handlers --------------------------------------------- */


    /**
     * Handles bulk action and delete.
     *
     * @param $action
     * @return void
     */
    public static function process_bulk_action($action)
    {
        global $wpdb;

        $page_url = menu_page_url('bookingmanager_places', FALSE);

        if( ! wp_verify_nonce($_REQUEST['_wpnonce'], 'bm_disclaim_reserved_place')) {
            switch($action) {
                case 'bulk-disclaim':
                    if(isset($_POST['bulk-affected'])) {
                        if(isset($_POST['bulk-affected']) && is_array($_POST['bulk-affected'])) {
                            // Collect data about all processed reserves
                            $delete_ids = esc_sql($_POST['bulk-affected']);
                            $sql = self::get_select_query_base() . ' place.id IN (' . implode(', ', $delete_ids) . ')';
                            $processed_reserves_data = $wpdb->get_results($sql, 'ARRAY_A');

                            // loop over the array of record ids and delete them
                            foreach($processed_reserves_data as $processed_reserve) {

                                // Quick fix: Convert places to array and change the name of key
                                $processed_reserve['places'] = [$processed_reserve['place_number']];

                                $reserve = BM_Reserved_Place_Data::feed($processed_reserve, FALSE, '', '', '');
                                $reserve->set_id($processed_reserve['id']);
                                self::disclaim_reserved_place($reserve);
                            }
                        }
                    }
                    break;
            }
        }

        // Redirect
        wp_redirect($page_url);
        exit;
    }

    public static function switch_payment_status()
    {
        $id = isset($_REQUEST['id']) ? absint($_REQUEST['id']) : NULL;

        if(( ! wp_verify_nonce($_REQUEST['_wpnonce'], 'bm_switch_payment_status'))
            || ( ! current_user_can('read'))
            || empty($id)
            || empty($_REQUEST['action'])
            || ( ! in_array($_REQUEST['action'], ['pay', 'unpay']))
        ) {
            wp_die(__('Permission Denied!', 'bookingmanager_textdomain'));
        }

        // Get data about affected reserve
        $processed_reserve_data = self::get_reserved_place($id);
        // Quick fix: Convert places to array and change the name of key
        $processed_reserve_data['places'] = [$processed_reserve_data['place_number']];
        $reserve = BM_Reserved_Place_Data::feed($processed_reserve_data, FALSE, '', '', '');
        $reserve->set_id($id);

        // Update payment status of reserve
        $status = ($_REQUEST['action'] == 'pay') ? 1 : 0;
        self::admin_update_payment_status($reserve, $status);

        // Redirect
        $page_url = menu_page_url('bookingmanager_places', FALSE);
        // Here we need to use header for redirect instead of wp_redirect because the later doesn't reload page sometimes
        header("Location:$page_url");
//        wp_redirect($page_url);
        exit;
    }

    /**
     * Downloads ticket as PDF
     * NOTE: when clicking on "Download", we found ticket by it's code,
     * then find all places belonged to this ticket and create PDF with all this places
     *
     * @throws BM_Exception_NotFullDataException
     */
    public static function download_ticket()
    {
        global $wpdb;

        $id = isset($_REQUEST['id']) ? absint($_REQUEST['id']) : NULL;

        if(( ! wp_verify_nonce($_REQUEST['_wpnonce'], 'bm_download_ticket'))
            || ( ! current_user_can('read'))
            || empty($id)
            || empty($_REQUEST['action'])
            || ($_REQUEST['action'] != 'download_ticket')
        ) {
            wp_die(__('Permission Denied!', 'bookingmanager_textdomain'));
        }

        $reserve_data = [];
        $places = $wpdb->get_results(
            self::get_select_query_base() .
            " ticket_code = (SELECT ticket_code FROM {$wpdb->prefix}bm_reserved_places WHERE id = $id)",
            'ARRAY_A'
        );

        foreach($places as $place) {
            if(empty($reserve_data)) {
                $reserve_data = [
                    'route_id'    => $place['route_id'],
                    'trip_date'   => $place['trip_date'],
                    'places'      => [],
                    'ticket_code' => $place['ticket_code'],
                    'name'        => $place['name'],
                    'last_name'   => $place['last_name'],
                    'email'       => $place['email'],
                    'phone'       => $place['phone'],
                ];
            }
            $reserve_data['places'][] = $place['place_number'];
        }

        $reserve = BM_Reserved_Place_Data::feed($reserve_data, FALSE, '', '', '');

        $ticket = new BM_Ticket_Ticket($reserve);

        $ticket_title = 'Билет на маршрут ' . $reserve->there_route->name
            . ' (дата: ' . date("d.m.Y", strtotime($reserve->there_data['trip_date'])) . '; места: '
            . $reserve->there_data['places_string'] . ')';

        $ticket->download($ticket_title . '.pdf');
    }

    /**
     * Handles form data when submitted.
     *
     * @return void
     */
    public
    static function process_form_submit()
    {
        if( ! isset($_POST['submit_reserved_place'])) {
            return;
        }
        if(( ! wp_verify_nonce($_POST['_wpnonce'], 'bm_new_reserved_place'))
            || ( ! current_user_can('read'))
        ) {
            wp_die(__('Permission Denied!', 'bookingmanager_textdomain'));
        }

        $page_url = menu_page_url('bookingmanager_places', FALSE);
        $result = FALSE;
        $args = [];

        // Get unslashed post
        $post = BM_Bookingmanager::$unslashed_post;

        // Check the id
        $field_id = isset($post['field_id']) ? absint($post['field_id']) : NULL;
        // If id empty, insert new reserves
        if($field_id === NULL) {

            // Try to validate data
            try {
                $reserve = BM_Reserved_Place_Data::feed($post, FALSE, '', '');

                // Set additional properties
                $reserve->client_ip = BM_Util_Util::get_client_ip();
                $reserve->booking_datetime = current_time('mysql');
                // Generate ticket code
                $reserve->there_data['ticket_code'] = BM_Util_Util::create_ticket_code($reserve->there_data['places_string'], $reserve->there_data['route_id']);

                // Insert new reserves with given payment status
                $is_paid = (isset($post['is_paid']) && ($post['is_paid'] == 'on')) ? 1 : 0;
                $result = self::admin_insert_reserved_place($reserve, $is_paid);

            } catch(Exception $e) {
                // Do nothing, $result still is false
            }
        } else {
            // Else, if id exists, update the reserve by this id
            try {
                $reserve = BM_Reserved_Place_Data::feed($post, FALSE, '', '');

                $result = self::admin_update_reserved_place($reserve, $field_id);

            } catch(Exception $e) {
                // Do nothing, $result still is false
            }
        }

        if(empty($_REQUEST['from_full_list'])) {
            $args['trip_date'] = $_REQUEST['trip_date'];
            $args['route_id'] = $_REQUEST['route_id'];
        }

        // Redirect
        $redirect_to = add_query_arg($args, $page_url);
        wp_redirect($redirect_to);
        exit;
    }

    /**
     * Checks if the payment status of reserve equals to the given status,
     *  and if not, updates it and registers the corresponding event bounded to the current user
     *
     * @param BM_Reserved_Place_Data $reserve
     * @param $status
     * @return boolean
     */
    public
    static function admin_update_payment_status(BM_Reserved_Place_Data $reserve, $status)
    {
        global $wpdb;

        // Static value for creating unique key each time when method is called
        static $i = 0;

        $checking_result = TRUE;

        if(empty($reserve->id)) {

            $checking_result = FALSE;
        }

        // Determine the type of action
        if($status == 1) {
            $event_type = BM_Event_Data::PAID;
            $message = '<b>Резерв оплачен.</b>';
        } else {
            $event_type = BM_Event_Data::PAYMENT_RETURN;
            $message = '<b>Оплата за резерв отменена.</b>';
        }

        $rows_affected = $wpdb->update(
            "{$wpdb->prefix}bm_reserved_places",
            ['is_paid' => $status],
            ['id' => $reserve->id],
            ['%d'],
            ['%d']
        );

        if($rows_affected === FALSE) {

            // If checking fails, return false
            $checking_result = FALSE;
        }

        if( ! $checking_result) {
            // Register message
            BM_Util_Util::push_admin_notice(
                'error',
                "<b>Не удалось проверить статус оплаты резерва.</b> <br>(место: {$reserve->there_data['places_string']}, "
                . " маршрут: '{$reserve->there_route->name}',"
                . " дата: {$reserve->there_data['trip_date']}, "
                . " код билета: {$reserve->there_data['ticket_code']})."
            );

            return FALSE;
        }

        // If status was actually changed...
        if($rows_affected > 0) {

            // ...make copy of the reserve in the archive
            $copy_id = self::archive_reserved_place($reserve->id);

            $user = BM_Bookingmanager::get_current_user_data();

            // ...and register the event bounded to the copied record
            BM_Event_Manager::register_event(
                $event_type . '_' . $i++,
                new BM_Event_Data(
                    $event_type,
                    current_time('mysql'),
                    $copy_id,
                    $user['id'],
                    $user['name']
                )
            );

            // Register message
            BM_Util_Util::push_admin_notice(
                'success',
                "$message <br>(место: {$reserve->there_data['places_string']}, "
                . " маршрут: '{$reserve->there_route->name}',"
                . " дата: {$reserve->there_data['trip_date']}, "
                . " код билета: {$reserve->there_data['ticket_code']})."
            );
        }

        // If checking was successful, return true, even if no rows was actually updated
        return TRUE;
    }

    /**
     * Disclaims the reserve and registers corresponding event
     *
     * @param BM_Reserved_Place_Data $reserve
     * @return boolean
     */
    public
    static function disclaim_reserved_place(BM_Reserved_Place_Data $reserve)
    {
        static $deleted_counter;

        global $wpdb;

        $disclaimed = FALSE;

        if(empty($reserve->id)) {

            return FALSE;
        }

        // Archive the current state of reserve
        $archived_id = self::archive_reserved_place($reserve->id);
        if( ! empty($archived_id)) {

            // After the initial state of reserve was archived, and before it is deleted,
            //  switch its payment status to 'not paid' and register the event of payment returning
            // (disclaiming of the reserve implies the implicit returning of payment)
            self::admin_update_payment_status($reserve, 0);

            // Delete the origin row
            $deleted = self::delete_reserved_place_by_id($reserve->id);
            if($deleted) {
                $disclaimed = TRUE;
            }
        }

        if($disclaimed) {
            // Register the event of type 'DISCLAIMED' and bind it to current user id and $archived_id
            $user = BM_Bookingmanager::get_current_user_data();
            BM_Event_Manager::register_event(
            // Create unique key for event for cases when many deletions are performed
                BM_Event_Data::DISCLAIMED . '_' . ($deleted_counter++),
                new BM_Event_Data(
                    BM_Event_Data::DISCLAIMED,
                    current_time('mysql'),
                    $archived_id,
                    $user['id'],
                    $user['name']
                )
            );

            // Register the message
            BM_Util_Util::push_admin_notice(
                'success',
                "<b>Резерв отменен.</b><br> (место: {$reserve->there_data['places_string']},"
                . " маршрут: '{$reserve->there_route->name}',"
                . " дата: {$reserve->there_data['trip_date']},"
                . " код билета: {$reserve->there_data['ticket_code']})"
                . " <br><b>Пожалуйста, не забудьте уведомить об этом клиента</b><br>("
                . "ФИО: {$reserve->client->name} {$reserve->client->last_name},"
                . " E-mail: {$reserve->client->email},"
                . " Телефон: {$reserve->client->phone})"
            );
        } else {
            // Register the message
            BM_Util_Util::push_admin_notice(
                'error',
                "<b>Не удалось отменить резерв.</b> (место: {$reserve->there_data['places_string']},"
                . " маршрут: '{$reserve->there_route->name}',"
                . " дата: {$reserve->there_data['trip_date']},"
                . " код билета: {$reserve->there_data['ticket_code']})."
            );

        }

        return $disclaimed;
    }

    public
    static function admin_update_reserved_place(BM_Reserved_Place_Data $reserve, $id)
    {
        global $wpdb;

        // Get data about current user
        $user = BM_Bookingmanager::get_current_user_data();
        $reserve->reserved_by_user_id = $user['id'];

        $result = FALSE;

        $updated = $wpdb->update(
            "{$wpdb->prefix}bm_reserved_places",
            [
                'place_number' => $reserve->there_data['places'][0],
                'client_id'    => $reserve->client->id,
                'trip_date'    => $reserve->there_data['trip_date'],
            ],
            [
                'id' => $id,
            ],
            ['%d', '%d', '%s'],
            ['%d']
        );

        if($updated === FALSE) {

            // Register the message
            BM_Util_Util::push_admin_notice(
                'error',
                "<b>Не удалось изменить данные.</b>"
            );
        }

        // If some rows was actually changed...
        if($updated > 0) {

            BM_Util_Util::push_admin_notice(
                'success',
                "<b>Данные о резерве обновлены.</b>"
            );

            // ...store new state of reserve in archive and register event bounded
            // to this archived reserve and current user id
            $copy_id = self::archive_reserved_place($id);

            if($copy_id) {

                BM_Event_Manager::register_event(
                    BM_Event_Data::CHANGED,
                    new BM_Event_Data(
                        BM_Event_Data::CHANGED,
                        current_time('mysql'),
                        $copy_id,
                        $user['id'],
                        $user['name']
                    )
                );

            }
        }

        return TRUE;
    }

    /**
     * Inserts a reserved_place
     *
     * @param BM_Reserved_Place_Data $reserve
     * @param int $is_paid
     * @return boolean
     */
    public
    static function admin_insert_reserved_place(BM_Reserved_Place_Data $reserve, $is_paid)
    {
        global $wpdb;

        $result = TRUE;
        // Get data about current user
        $user = BM_Bookingmanager::get_current_user_data();
        $reserve->reserved_by_user_id = $user['id'];

        // Wrap all inserts in transactions in order to provide reserving of all places or reserving nothing
        $wpdb->query("START TRANSACTION");

        try {
            foreach($reserve->there_data['places'] as $i => $place_number) {
                $inserted = $wpdb->insert(
                    "{$wpdb->prefix}bm_reserved_places",
                    [
                        'place_number'        => $place_number,
                        'route_id'            => $reserve->there_data['route_id'],
                        'client_id'           => $reserve->client->id,
                        'ticket_code'         => $reserve->there_data['ticket_code'],
                        'trip_date'           => $reserve->there_data['trip_date'],
                        'booking_datetime'    => $reserve->booking_datetime,
                        'client_ip'           => $reserve->client_ip,
                        'payment_type'        => $reserve->payment_type,
                        'reserved_by_user_id' => $reserve->reserved_by_user_id,
                    ],
                    ['%d', '%d', '%d', "%s", "%s", "%s", "%s", "%s", '%d']
                );
                if($inserted) {
                    $inserted_id = $wpdb->insert_id;
                    // Store current state of reserve in archive and register event bounded
                    // to this archived reserve and current user id

                    $copy_id = self::archive_reserved_place($inserted_id);
                    if($copy_id) {
                        BM_Event_Manager::register_event(
                            BM_Event_Data::RESERVED . '_' . $i,
                            new BM_Event_Data(
                                BM_Event_Data::RESERVED,
                                current_time('mysql'),
                                $copy_id,
                                $user['id'],
                                $user['name']
                            )
                        );
                    } else {

                        throw new Exception('Archiving of inserted reserve failed');
                    }

                    // Register the message
                    BM_Util_Util::push_admin_notice(
                        'success',
                        "<b>Место № $place_number зарезервировано</b><br> ("
                        . " маршрут: '{$reserve->there_route->name}',"
                        . " дата: {$reserve->there_data['trip_date']},"
                        . " код билета: {$reserve->there_data['ticket_code']})."
                        . " <br><b>Пожалуйста, не забудьте уведомить об этом клиента</b><br>("
                        . "ФИО: {$reserve->client->name} {$reserve->client->last_name},"
                        . " E-mail: {$reserve->client->email},"
                        . " Телефон: {$reserve->client->phone})"
                    );

                    // Set just inserted id as current id of instance
                    $reserve->set_id($inserted_id);

                    // Quick dirty fix: set current number of place instead of all places
                    $reserve->there_data['places_string'] = $place_number;

                    // Check if payment status is actual and update if needed
                    if( ! self::admin_update_payment_status($reserve, $is_paid)) {

                        throw new Exception('Payment status updating failed');
                    }
                } else {

                    throw new Exception('Insertion failed');
                }
            }

            // Commit
            $wpdb->query("COMMIT");
        } catch(Exception $e) {
            $result = FALSE;

            // Clear messages and register new one
            BM_Util_Util::session_delete_all_admin_notices();
            BM_Util_Util::push_admin_notice(
                'error',
                '<b>Не удалось забронировать места</b>'
            );

            // Rollback
            $wpdb->query("ROLLBACK");
        }

        return $result;
    }

    /**
     * Moves the reserved_place record into immediate 'before-deletion' table.
     *
     * @param int $id reserved_place id
     * @return int|boolean   id of the row inserted into 'before-deletion' table
     */
    public
    static function archive_reserved_place($id)
    {
        global $wpdb;

        // Copy the record into immediate table
        $sql = "INSERT INTO {$wpdb->prefix}bm_archive
            SELECT
                NULL as id,
                route_id,
                client_id,
                booking_datetime,
                trip_date,
                place_number,
                is_paid,
                ticket_code,
                client_ip,
                payment_type,
                reserved_by_user_id
            FROM {$wpdb->prefix}bm_reserved_places WHERE id = " . absint($id);

        $result = $wpdb->query($sql);

        if($result) {

            // Return just inserted id
            return $wpdb->insert_id;
        }

        return FALSE;
    }


    /**
     * Returns array of reserved places' numbers on this date and on this route
     *
     * @return array
     */
    public
    function get_reserved_places_numbers()
    {
        $reserved_places_data = self::get_reserved_places();
        $reserved_places = [];
        foreach($reserved_places_data as $place) {
            $reserved_places[$place['place_number']] = $place['place_number'];
        }

        return $reserved_places;
    }

    /**
     * Additional filters in above the table
     *
     * @param string $which
     */
    public
    function extra_tablenav($which)
    {
        if($which == 'top') {
            ?>
            <div class="bm_extra_tablenav">
                <div id="bm_colors_legend">
                    <p>
                        <span class="bm_legend_item orange"></span>В черном списке
                        <span class="bm_legend_item yellow"></span>Подозрение на черный список
                    </p>
                </div>
            </div>
            <?php
        }
    }


    /* ---------------------------------- Methods for public part of site ----------------- */


    /**
     * Inserts the bulk of reserved places
     *
     * @param BM_Reserved_Place_Data $reserve
     * @return boolean
     */
    public
    static function public_insert_reserved_places(BM_Reserved_Place_Data $reserve)
    {
        global $wpdb;

        // Create SQL query
        $insert_sql = "INSERT INTO {$wpdb->prefix}bm_reserved_places
                    (place_number, route_id, client_id, ticket_code, trip_date, booking_datetime,
                     client_ip, payment_type, reserved_by_user_id) VALUES ";

        $values_sql_parts = [];

        foreach($reserve->there_data['places'] as $place_number) {
            $values_sql_parts[] = $wpdb->prepare(
                '(%d,%d,%d,"%s","%s","%s","%s","%s",%d)',
                [
                    $place_number,
                    $reserve->there_data['route_id'],
                    $reserve->client->id,
                    $reserve->there_data['ticket_code'],
                    $reserve->there_data['trip_date'],
                    $reserve->booking_datetime,
                    $reserve->client_ip,
                    $reserve->payment_type,
                    $reserve->reserved_by_user_id,
                ]
            );
        }
        // Add to query places for return route, if [with_return] flag is set
        if($reserve->with_return) {
            foreach($reserve->return_data['places'] as $place_number) {
                $values_sql_parts[] = $wpdb->prepare(
                    '(%d,%d,%d,"%s","%s","%s","%s","%s",%d)',
                    [
                        $place_number,
                        $reserve->return_data['route_id'],
                        $reserve->client->id,
                        $reserve->return_data['ticket_code'],
                        $reserve->return_data['trip_date'],
                        $reserve->booking_datetime,
                        $reserve->client_ip,
                        $reserve->payment_type,
                        $reserve->reserved_by_user_id,
                    ]
                );
            }
        }
        $insert_sql .= implode(',', $values_sql_parts);

        // Execute query and return boolean result
        if($wpdb->query($insert_sql)) {

            return TRUE;
        }

        return FALSE;
    }

    /**
     * Updates payment status of given place reservation
     *
     * @param BM_Reserved_Place_Data $reserve
     * @param int $status
     * @return bool
     */
    public
    static function public_update_payment_status(BM_Reserved_Place_Data $reserve, $status)
    {
        global $wpdb;

        if($reserve->with_return) {
            $change_payment_status_sql = $wpdb->prepare(
                "UPDATE {$wpdb->prefix}bm_reserved_places
                    SET is_paid = %d
                    WHERE ticket_code = %s OR ticket_code = %s",
                [
                    $status,
                    $reserve->there_data['ticket_code'],
                    $reserve->return_data['ticket_code'],
                ]
            );
        } else {
            $change_payment_status_sql = $wpdb->prepare(
                "UPDATE {$wpdb->prefix}bm_reserved_places
                    SET is_paid = %d
                    WHERE ticket_code = %s",
                [
                    $status,
                    $reserve->there_data['ticket_code'],
                ]
            );
        }

        return (boolean) $wpdb->query($change_payment_status_sql);
    }


    /* --------------------------------------- Util methods ------------------------------------------------- */


// Delete all not-paid reserves, which were reserved more than 24 hours ago
    public
    static function delete_outdated_reserves()
    {
        global $wpdb;

        $day_ago_datetime = date('Y-m-d H:i:s', time() - DAY_IN_SECONDS);

        return $wpdb->query(
            "DELETE FROM {$wpdb->prefix}bm_reserved_places
            WHERE is_paid = 0
            AND booking_datetime < '$day_ago_datetime'"
        );
    }


}