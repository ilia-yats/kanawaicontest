<?php
// Exit if accessed directly
if( ! defined('ABSPATH')) {
    exit;
}

if( ! class_exists('WP_List_Table')) {
    require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}

class BM_Event_List extends WP_List_Table
{
    /**
     * Returns base part of SQL query to get the places data
     *
     * @return string
     */
    public static function get_select_query_base()
    {
        global $wpdb;

        return "SELECT
                    place.route_id AS route_id,
                    place.client_id AS client_id,
                    place.booking_datetime AS booking_datetime,
                    place.trip_date AS trip_date,
                    place.place_number AS place_number,
                    place.is_paid AS is_paid,
                    place.ticket_code AS ticket_code,
                    client.name AS name,
                    client.last_name AS last_name,
                    client.phone AS phone,
                    client.email AS email,
                    client.is_blacklisted AS is_blacklisted,
                    route.name as route_name,
                    event.*
                FROM {$wpdb->prefix}bm_events AS event
                LEFT JOIN {$wpdb->prefix}bm_archive AS place ON event.affected_reserve_id = place.id
                LEFT JOIN {$wpdb->prefix}bm_routes AS route ON place.route_id = route.id
                LEFT JOIN {$wpdb->prefix}bm_clients AS client ON place.client_id = client.id
                WHERE ";
    }

    /**
     *  Returns part of SQL query for searching by substring
     *
     * @param string $search_string
     * @return string  SQL 'where' clause
     */
    public static function create_search_sql_where_clause($search_string)
    {
        $search_string = esc_sql($search_string);

        return ' (client.name LIKE "%' . $search_string . '%"
            OR client.last_name LIKE "%' . $search_string . '%"
            OR client.phone LIKE "%' . $search_string . '%"
            OR client.email LIKE "%' . $search_string . '%"
            OR place.ticket_code LIKE "%' . $search_string . '%"
            OR event.initiator_name LIKE "%' . $search_string . '%")';
    }

    public static $event_types_labels = [
        BM_Event_Data::RESERVED       => 'Зарезервировано новое',
        BM_Event_Data::PAID           => 'Совершена оплата',
        BM_Event_Data::PAYMENT_RETURN => 'Оплата возвращена',
        BM_Event_Data::DISCLAIMED     => 'Резерв отменен',
        BM_Event_Data::CHANGED        => 'Резерв изменен',
    ];

    /**
     * Constructor.
     *
     * @param void
     */
    public function __construct()
    {
        parent::__construct(array(
            'singular' => 'Событие',
            'plural'   => 'События',
            'ajax'     => FALSE,
        ));
    }

    /**
     * Retrieve events data from the database.
     *
     * @param int $id
     *
     * @return array
     */
    public static function get_event($id = 0)
    {
        global $wpdb;

        $item = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}bm_events WHERE id = %d", $id));

        return $item;
    }

    /**
     * Retrieve events data from the database.
     *
     * @param int $per_page
     * @param int $page_number
     *
     * @return array
     */
    public static function get_events($per_page = 20, $page_number = 1)
    {
        global $wpdb;

        $sql = self::get_select_query_base();

        if( ! empty($_REQUEST['s'])) {
            $sql .= self::create_search_sql_where_clause($_REQUEST['s']) . ' AND ';
        }
        if( ! empty($_REQUEST['type'])) {
            $sql .= " event.type = '" . esc_sql($_REQUEST['type']) . "'  AND ";
        }
        if( ! empty($_REQUEST['route_id'])) {
            $sql .= " place.route_id = '" . esc_sql($_REQUEST['route_id']) . "'  AND ";
        }
        if( ! empty($_REQUEST['datetime'])) {
            $sql .= " DATE(event.datetime) = '" . esc_sql($_REQUEST['datetime']) . "'  AND ";
        }
        if( ! empty($_REQUEST['ticket_code'])) {
            $sql .= " place.ticket_code LIKE '" . esc_sql($_REQUEST['ticket_code']) . "%' AND ";
        }
        if( ! empty($_REQUEST['initiator_name'])) {
            $sql .= " event.initiator_name LIKE '" . esc_sql($_REQUEST['initiator_name']) . "%' AND ";
        }

        $sql .= ' 1 ';

        if( ! empty($_REQUEST['orderby'])) {
            $sql .= ' ORDER BY ' . esc_sql($_REQUEST['orderby']);
            $sql .= ! empty($_REQUEST['order']) ? ' ' . esc_sql($_REQUEST['order']) : ' ASC';
        } else {
            $sql .= ' ORDER BY datetime DESC';
        }

        $sql .= " LIMIT $per_page";
        $sql .= ' OFFSET ' . ($page_number - 1) * $per_page;

        $result = $wpdb->get_results($sql, 'ARRAY_A');

        return $result;
    }

    /**
     * Delete a event record and all archived items bounded to this event
     *
     * @param int $id event id
     * @return  boolean
     */
    public static function delete_event($id)
    {
        global $wpdb;

        $event_affected_reserve_id = $wpdb->get_var(
            "SELECT affected_reserve_id FROM {$wpdb->prefix}bm_events WHERE id = " . absint($id)
        );

        $event_deleted = $wpdb->delete(
            "{$wpdb->prefix}bm_events",
            ['id' => $id],
            ['%d']
        );

        if($event_deleted !== FALSE) {

            $event_archive_items_deleted = $wpdb->delete(
                "{$wpdb->prefix}bm_archive",
                ['id' => $event_affected_reserve_id],
                ['%d']
            );

            if($event_archive_items_deleted !== FALSE) {

                return TRUE;
            }
        }

        return FALSE;
    }

    /**
     * Returns the count of records in the database.
     *
     * @return null|string
     */
    public static function record_count()
    {
        global $wpdb;

        $sql = "SELECT COUNT(*) FROM {$wpdb->prefix}bm_events AS event
                LEFT JOIN {$wpdb->prefix}bm_archive AS place ON event.affected_reserve_id = place.id
                LEFT JOIN {$wpdb->prefix}bm_routes AS route ON place.route_id = route.id
                LEFT JOIN {$wpdb->prefix}bm_clients AS client ON place.client_id = client.id
                WHERE ";

        if( ! empty($_REQUEST['s'])) {
            $sql .= self::create_search_sql_where_clause($_REQUEST['s']) . ' AND ';
        }
        if( ! empty($_REQUEST['type'])) {
            $sql .= " event.type = '" . esc_sql($_REQUEST['type']) . "'  AND ";
        }
        if( ! empty($_REQUEST['route_id'])) {
            $sql .= " place.route_id = '" . esc_sql($_REQUEST['route_id']) . "'  AND ";
        }
        if( ! empty($_REQUEST['datetime'])) {
            $sql .= " DATE(event.datetime) = '" . esc_sql($_REQUEST['datetime']) . "'  AND ";
        }
        if( ! empty($_REQUEST['ticket_code'])) {
            $sql .= " place.ticket_code LIKE '" . esc_sql($_REQUEST['ticket_code']) . "%' AND ";
        }
        if( ! empty($_REQUEST['initiator_name'])) {
            $sql .= " event.initiator_name LIKE '" . esc_sql($_REQUEST['initiator_name']) . "%' AND ";
        }

        $sql .= ' 1 ';

        return $wpdb->get_var($sql);
    }

    /**
     * Text displayed when no citiy data is available.
     *
     * @return void
     */
    public function no_items()
    {
        echo 'Нет событий';
    }

    /**
     * Render a column when no column specific method exist.
     *
     * @param array $item
     * @param string $column_name
     *
     * @return mixed
     */
    public function column_default($item, $column_name)
    {
        return isset($item[$column_name]) ? $item[$column_name] : '';
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
            '<input type="checkbox" name="bulk-delete[]" value="%s" />', $item['id']
        );
    }

    public function column_datetime($item)
    {
        return date('d.m.Y H:i:s', strtotime($item['datetime']));
    }

    public function column_trip_date($item)
    {
        return date('d.m.Y', strtotime($item['trip_date']));
    }

    public function column_type($item)
    {
        return isset(self::$event_types_labels[$item['type']]) ? self::$event_types_labels[$item['type']] : ' - ';
    }

    public function column_is_paid($item)
    {
        return ($item['is_paid'] == 1) ? '<strong>Да</strong>' : '<strong>Нет</strong>';
    }

    public function column_name($item)
    {
        return $item['name'] . ' ' . $item['last_name'];
    }

    /**
     *  Associative array of columns.
     *
     * @return array
     */
    function get_columns()
    {
        $columns = [
            'cb'             => '<input type="checkbox" />',
            'type'           => 'Тип',
            'datetime'       => 'Дата/Время действия',
            'route_name'     => 'Маршрут',
            'place_number'   => 'Место',
            'trip_date'      => 'Дата поездки',
            'initiator_name' => 'Пользователь',
            'name'           => 'ФИО пассажира', // __('Client Name', 'bookingmanager_textdomain'),
            'email'          => 'E-mail пассажира', // __('Client Email', 'bookingmanager_textdomain'),
            'phone'          => 'Телефон пассажира', // __('Client Phone', 'bookingmanager_textdomain'),
            'ticket_code'    => 'Код билета', // __('Ticket Code', 'bookingmanager_textdomain'),
            'is_paid'        => 'Оплата', // __('Is Paid', 'bookingmanager_textdomain'),
        ];

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
            'trip_date'      => array('trip_date', TRUE),
            'datetime'       => array('datetime', TRUE),
            'initiator_name' => array('initiator_name', TRUE),
        );

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
            'bulk-delete' => 'Удалить',
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

        $per_page = $this->get_items_per_page('events_per_page', 5);
        $current_page = $this->get_pagenum();
        $total_items = self::record_count();

        $this->set_pagination_args([
            'total_items' => $total_items,
            'per_page'    => $per_page,
        ]);

        $this->items = self::get_events($per_page, $current_page);
    }

    /**
     * Handles bulk action and delete.
     *
     * @return void
     */
    public function process_bulk_action()
    {
        $page_url = menu_page_url('bookingmanager_events', FALSE);

        $result = FALSE;

        //Detect when a bulk action is being triggered...
        if('delete' === $this->current_action()) {
            // In our file that handles the request, verify the nonce.
            $nonce = esc_attr($_REQUEST['_wpnonce']);

            if(wp_verify_nonce($nonce, 'bm_delete_event')) {

                $result = self::delete_event($_REQUEST['id']);
            }
        }

        // If the delete bulk action is triggered
        if('bulk-delete' === $this->current_action()) {
            if(isset($_POST['bulk-delete']) && is_array($_POST['bulk-delete'])) {
                $delete_ids = esc_sql($_POST['bulk-delete']);
                // loop over the array of record ids and delete them
                $result = TRUE;
                foreach($delete_ids as $id) {
                    if( ! self::delete_event($id)) {
                        $result = FALSE;
                    }
                }
            }
        }

        // Register message
        $message = ($result) ? ['type' => 'success', 'text' => 'OK.'] : ['type' => 'error', 'text' => 'Ошибка !'];
        BM_Util_Util::push_admin_notice($message['type'], $message['text']);

        // Redirect
        wp_redirect($page_url);
        exit();
    }
}