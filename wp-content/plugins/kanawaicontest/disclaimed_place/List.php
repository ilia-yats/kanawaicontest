<?php
// Exit if accessed directly
if( ! defined('ABSPATH')) {
    exit;
}

if( ! class_exists('WP_List_Table')) {
    require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}

/**
 * Manipulates with information about dismlaimed places
 *
 * Class Disclaimed_places_List
 */
class BM_Disclaimed_Place_List extends WP_List_Table
{
    /* ---------------------------------- Methods for administrative part of site ----------------- */

    /**
     * Returns base part of SQL query to get the places data
     *
     * @return string
     */
    public function get_select_query_base()
    {
        global $wpdb;

        return "SELECT
                    place.*,
                    client.name AS name,
                    client.last_name AS last_name,
                    client.phone AS phone,
                    client.email AS email,
                    client.is_blacklisted AS is_blacklisted,
                    event.initiator_name AS initiator_name,
                    event.datetime AS disclaim_datetime,
                    route.name as route_name
                FROM {$wpdb->prefix}bm_events AS event
                LEFT JOIN {$wpdb->prefix}bm_archive AS place ON event.affected_reserve_id = place.id
                LEFT JOIN {$wpdb->prefix}bm_clients AS client ON place.client_id = client.id
                LEFT JOIN {$wpdb->prefix}bm_routes AS route ON place.route_id = route.id
                WHERE event.type = " . BM_Event_Data::DISCLAIMED . "";
    }

    /**
     *  Returns part of SQL query for searching by substring
     *
     * @param string $search_string
     * @return string  SQL 'where' clause
     */
    public function create_search_sql_where_clause($search_string)
    {
        $search_string = esc_sql($search_string);

        return ' AND (name LIKE "%' . $search_string . '%"
            OR last_name LIKE "%' . $search_string . '%"
            OR phone LIKE "%' . $search_string . '%"
            OR ticket_code LIKE "%' . $search_string . '%"
            OR initiator_name LIKE "%' . $search_string . '%"
            OR route_name LIKE "%' . $search_string . '%"
            OR email LIKE "%' . $search_string . '%")';
    }

    /**
     * Constructor.
     */
    public function __construct()
    {
        global $wpdb;

        parent::__construct(array(
            'singular' => 'Отмененные резервы',
            'plural'   => 'Отмененный резерв',
            'ajax'     => FALSE,
        ));
    }

    /**
     * Retrieves disclaimed places data from the database.
     *
     * @param int $per_page
     * @param int $page_number
     *
     * @return mixed
     */
    public function get_disclaimed_places($per_page = 20, $page_number = 1)
    {
        global $wpdb;

        $sql = $this->get_select_query_base();

        if( ! empty($_REQUEST['s'])) {
            $sql .= $this->create_search_sql_where_clause($_REQUEST['s']);
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
     * Deletes a disclaimed_place record.
     *
     * @param int $id disclaimed_place id
     * @return boolean
     */
    public function delete_disclaimed_place($id)
    {
        global $wpdb;

        return ( boolean ) $wpdb->delete(
            "{$wpdb->prefix}bm_archive",
            ['id' => $id],
            ['%d']
        );
    }

    /**
     * Returns the count of records in the database.
     *
     * @return null|string
     */
    public function record_count()
    {
        global $wpdb;

        $sql = "SELECT COUNT(*) FROM {$wpdb->prefix}bm_archive AS place
                  JOIN {$wpdb->prefix}bm_events AS event ON place.id = event.affected_reserve_id
                  WHERE type = ," . BM_Event_Data::DISCLAIMED . "";

        if( ! empty($_REQUEST['s'])) {
            $sql .= $this->create_search_sql_where_clause($_REQUEST['s']) . ' AND';
        }

        return $wpdb->get_var($sql);
    }

    /**
     * Text displayed when no disclaimed_place data is available.
     *
     * @return void
     */
    public function no_items()
    {
        echo 'Нет отмененных резервов';
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
            case 'place_number':
//            case 'booking_datetime':
            case 'trip_date':
            case 'name':
            case 'last_name':
            case 'email':
            case 'phone':
            case 'ticket_code':
//            case 'client_ip':
            case 'disclaim_datetime':
            case 'initiator_name':
            case 'route_name':
                $value = $item[$column_name];
                break;
            case 'is_paid':
                $value = ($item[$column_name] == 1) ? '<strong>Да</strong>' : '<strong>Нет</strong>';
                break;
            default:
                $value = '-';
        }

        // Check if client is blacklisted
        $is_blacklisted = (isset($item['is_blacklisted']) && $item['is_blacklisted'] == 1) ? TRUE : FALSE;
        // If item is blacklisted highlight this row with special styles
        $additional_style = ($is_blacklisted ? 'style="color:red;"' : '');

        // Return full code of the table cell (wrap each cell into the link onto edit page)
        return sprintf(
            '<span ' . $additional_style . ' >%s</span>',
            $value
        );
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
            'cb'                => '<input type="checkbox" />',
            'name'              => 'Имя',
            'last_name'         => 'Фамилия',
            'email'             => 'E-mail',
            'phone'             => 'Телефон',
            'trip_date'         => 'Дата поездки',
            'route_name'        => 'Маршрут',
            'place_number'      => 'Место',
            'ticket_code'      => 'Код билета',
//            'booking_datetime' => 'Время резервирования',
            'is_paid'           => 'Оплата',
//            'client_ip'         => 'IP-адрес',
            'initiator_name'    => 'Кем отменен',
            'disclaim_datetime' => 'Когда отменен',
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
            'place_number'      => array('place_number', TRUE),
            'route_name'        => array('route_name', TRUE),
            'is_paid'           => array('is_paid', TRUE),
//            'booking_datetime' => array('booking_datetime', TRUE),
            'trip_date'         => array('trip_date', TRUE),
            'name'              => array('name', TRUE),
            'last_name'         => array('last_name', TRUE),
            'email'             => array('email', TRUE),
            'phone'             => array('phone', TRUE),
//            'ticket_code'      => array('ticket_code', TRUE),
//            'client_ip'         => array('client_ip', TRUE),
            'disclaim_datetime' => array('disclaim_datetime', TRUE),
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
            'bulk-delete' => 'Удалить навсегда',
            // TODO: Fix recovering
//            'bulk-recover' => 'Восстановить',
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

        $this->items = $this->get_disclaimed_places($per_page, $current_page);
    }

    /**
     * Handles bulk action and delete.
     *
     * @return void
     */
    public function process_bulk_action()
    {
        $page_url = menu_page_url('bookingmanager_disclaimed_places', FALSE);

        $result = FALSE;

        //Detect when a bulk action is being triggered...
        if('delete' === $this->current_action()) {
            // In our file that handles the request, verify the nonce.
            $nonce = esc_attr($_REQUEST['_wpnonce']);

            if( ! wp_verify_nonce($nonce, 'bm_delete_disclaimed_place')) {
                $result = $this->delete_disclaimed_place(absint($_REQUEST['id']));
            }
        }

        // If the delete bulk action is triggered
        if('bulk-delete' === $this->current_action()) {

            $delete_ids = isset($_POST['bulk-affected']) ? esc_sql($_POST['bulk-affected']) : NULL;

            if(is_array($delete_ids)) {
                // loop over the array of record ids and delete them
                $result = TRUE;
                foreach($delete_ids as $id) {
                    if( ! $this->delete_disclaimed_place($id)) {
                        $result = FALSE;
                    }
                }
            }
        }

        if('bulk-recover' === $this->current_action()) {

            $recover_ids = isset($_POST['bulk-affected']) ? esc_sql($_POST['bulk-affected']) : NULL;

            if(is_array($recover_ids)) {
                // loop over the array of record ids and delete them
                $result = TRUE;
                foreach($recover_ids as $id) {
                    if( ! $this->recover_disclaimed_place($id)) {
                        $result = FALSE;
                    }
                }
            }
        }

        // Redirect
        $query = array('message' => ($result) ? 'success' : 'error');
        $redirect_to = add_query_arg($query, $page_url);
        wp_redirect($redirect_to);
        exit;
    }

    /**
     * Moves the disclaimed_place record back into table with reserved places.
     *
     * @param int $id disclaimed_place id
     * @return int|boolean   id of the row inserted into table with reserved places
     */
    public function recover_disclaimed_place($id)
    {
        global $wpdb;

        // Copy the record into immediate table
        $sql = "INSERT INTO {$wpdb->prefix}bm_reserved_places
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
            FROM {$wpdb->prefix}bm_archive WHERE id = " . absint($id);

        $result = $wpdb->query($sql);

        if($wpdb->query($sql)) {

            // Return just inserted id
            return $wpdb->insert_id;
        }

        return FALSE;
    }

}