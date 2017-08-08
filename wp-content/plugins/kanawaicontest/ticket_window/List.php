<?php
// Exit if accessed directly
if( ! defined('ABSPATH')) {
    exit;
}

if( ! class_exists('WP_List_Table')) {
    require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}

class BM_Ticket_Window_List extends WP_List_Table
{
    public $cities = array();
    public static $ticket_window_phones = array();

    /**
     *  Returns part of SQL query for searching by substring
     *
     * @param string $search_stirng
     * @return string  SQL 'where' clause
     */
    public static function create_search_sql_where_clause($search_stirng)
    {
        global $wpdb;

        return " WHERE (name LIKE '%" . esc_sql($search_stirng) . "%'
            OR id IN (SELECT ticket_window_id FROM {$wpdb->prefix}bm_phones WHERE phone LIKE '%" . esc_sql($search_stirng) . "%')
            OR city_id IN (SELECT id FROM {$wpdb->prefix}bm_cities WHERE name LIKE '%" . esc_sql($search_stirng) . "%'))";
    }

    /**
     * Constructor.
     *
     * @param void
     */
    public function __construct()
    {
        global $wpdb;

        parent::__construct(array(
            'singular' => 'Касса',
            'plural'   => 'Кассы',
            'ajax'     => FALSE,
        ));

        $cities = $wpdb->get_results("SELECT id, name FROM {$wpdb->prefix}bm_cities", 'ARRAY_A');
        foreach($cities as $city) {
            $this->cities[$city['id']] = $city;
        }

        if(empty(self::$ticket_window_phones)) {
            self::retrieve_cities_phones();
        }
    }

    /**
     * Retrieve ticket_windows data from the database.
     *
     * @param int $id
     *
     * @return array
     */
    public static function get_ticket_window($id = 0)
    {
        global $wpdb;

        if(empty(self::$ticket_window_phones)) {
            self::retrieve_cities_phones();
        }

        $item = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}bm_ticket_windows WHERE id = %d", $id));
        $item->phones = isset(self::$ticket_window_phones[$item->id])
            ? self::$ticket_window_phones[$item->id]
            : [];

        return $item;
    }

    /**
     * Retrieve ticket_windows data from the database.
     *
     * @param int $per_page
     * @param int $page_number
     *
     * @return array
     */
    public static function get_ticket_windows($per_page = 20, $page_number = 1)
    {
        global $wpdb;

        if(empty(self::$ticket_window_phones)) {
            self::retrieve_cities_phones();
        }

        $sql = "SELECT * FROM {$wpdb->prefix}bm_ticket_windows";

        if( ! empty($_REQUEST['s'])) {
            $sql .= self::create_search_sql_where_clause($_REQUEST['s']);
        }

        if( ! empty($_REQUEST['orderby'])) {
            $sql .= ' ORDER BY ' . esc_sql($_REQUEST['orderby']);
            $sql .= ! empty($_REQUEST['order']) ? ' ' . esc_sql($_REQUEST['order']) : ' ASC';
        }

        $sql .= " LIMIT $per_page";
        $sql .= ' OFFSET ' . ($page_number - 1) * $per_page;

        $result = $wpdb->get_results($sql, 'ARRAY_A');

        foreach($result as &$item) {
            $item['phones'] = isset(self::$ticket_window_phones[$item['id']])
                ? self::$ticket_window_phones[$item['id']]
                : [];
        }

        return $result;
    }

    /**
     * Retrieve all ticket_windows data from the database without limits and offsets
     *
     * @return array
     */
    public static function get_ticket_windows_in_cities()
    {
        global $wpdb;

        if(empty(self::$ticket_window_phones)) {
            self::retrieve_cities_phones();
        }

        $sql = "SELECT
                  ticket_windows.*,
                  cities.name AS city_name,
                  cities.lon AS city_lat,
                  cities.lat AS city_lon
                  FROM {$wpdb->prefix}bm_ticket_windows AS ticket_windows
                  JOIN {$wpdb->prefix}bm_cities AS cities
                  ON ticket_windows.city_id = cities.id";

        $ticket_windows = [];

        $result = $wpdb->get_results($sql, 'ARRAY_A');

        foreach($result as $row) {
            $row['phones'] = isset(self::$ticket_window_phones[$row['id']])
                ? self::$ticket_window_phones[$row['id']]
                : [];

            if( ! isset($ticket_windows[$row['city_name']])) {
                $ticket_windows[$row['city_name']] = [
                    'ticket_windows' => [],
                    'lon'            => '',
                    'lat'            => '',
                    'name'           => '',
                ];
            }
            $ticket_windows[$row['city_name']]['ticket_windows'][] = $row;
            $ticket_windows[$row['city_name']]['lon'] = $row['city_lon'];
            $ticket_windows[$row['city_name']]['lat'] = $row['city_lat'];
            $ticket_windows[$row['city_name']]['name'] = $row['city_name'];
        }

        return $ticket_windows;
    }

    /**
     * Delete a city record.
     *
     * @param int $id citiy id
     * @return boolean
     */
    public static function delete_ticket_window($id)
    {
        global $wpdb;

        return (boolean) $wpdb->delete(
            "{$wpdb->prefix}bm_ticket_windows",
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

        $sql = "SELECT COUNT(*) FROM {$wpdb->prefix}bm_ticket_windows";

        if( ! empty($_REQUEST['s'])) {
            $sql .= self::create_search_sql_where_clause($_REQUEST['s']);
        }

        return $wpdb->get_var($sql);
    }

    /**
     * Text displayed when no data is available.
     *
     * @return void
     */
    public function no_items()
    {
        echo 'Нет касс';
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
        switch($column_name) {
            case 'name':
            case 'working_hours':
            case 'lon':
            case 'lat':
                return $item[$column_name];
                break;
            case 'phone':
                return implode(",\n", array_map(function($el) {
                    return $el['phone'];
                }, $item['phones']));
                break;
            case 'city_id':
                return isset($this->cities[$item[$column_name]]['name']) ? $this->cities[$item[$column_name]]['name'] : '-';
                break;
        }
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

    /**
     * Method for name column.
     *
     * @param array $item an array of DB data
     *
     * @return string
     */
    function column_name($item)
    {
        $title = '<strong>' . $item['name'] . '</strong>';

        $actions = [
            'edit' => sprintf('<a href="?page=%s&action=%s&id=%d">Изменить</a>', esc_attr($_REQUEST['page']), 'edit', absint($item['id'])),
        ];

        return $title . $this->row_actions($actions);
    }

    /**
     *  Associative array of columns.
     *
     * @return array
     */
    function get_columns()
    {
        $columns = [
            'cb'            => '<input type="checkbox" />',
            'name'          => 'Адресс',
            'phone'         => 'Телефон',
            'lat'           => 'Широта',
            'lon'           => 'Долгота',
            'city_id'       => 'Город',
            'working_hours' => 'Рабочие часы',
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
            'name'          => array('name', TRUE),
//            'phone'         => array('phone', TRUE),
            'lon'           => array('lon', TRUE),
            'lat'           => array('lat', TRUE),
            'city_id'       => array('city_id', TRUE),
            'working_hours' => array('working_hours', TRUE),
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

        $per_page = $this->get_items_per_page('ticket_windows_per_page', 5);
        $current_page = $this->get_pagenum();
        $total_items = self::record_count();

        $this->set_pagination_args([
            'total_items' => $total_items,
            'per_page'    => $per_page,
        ]);

        $this->items = $this->get_ticket_windows($per_page, $current_page);
    }

    /**
     * Handles bulk action and delete.
     *
     * @return void
     */
    public function process_bulk_action()
    {
        $page_url = menu_page_url('bookingmanager_ticket_windows', FALSE);

        $result = FALSE;

        //Detect when a bulk action is being triggered...
        if('delete' === $this->current_action()) {
            // In our file that handles the request, verify the nonce.
            $nonce = esc_attr($_REQUEST['_wpnonce']);

            if(wp_verify_nonce($nonce, 'bm_delete_ticket_window')) {

                $result = self::delete_ticket_window(absint($_REQUEST['id']));

                if($result) {
                    BM_Util_Util::push_admin_notice('success', 'Кассы удалены');
                } else {
                    BM_Util_Util::push_admin_notice('error', 'Не удалось удалить кассы');
                }
            }
        }

        // If the delete bulk action is triggered
        if((isset($_POST['action']) && $_POST['action'] == 'bulk-delete')
            || (isset($_POST['action2']) && $_POST['action2'] == 'bulk-delete')
        ) {
            if(isset($_POST['bulk-delete']) && is_array($_POST['bulk-delete'])) {
                $delete_ids = esc_sql($_POST['bulk-delete']);
                // loop over the array of record ids and delete them
                $result = TRUE;
                foreach($delete_ids as $id) {
                    if( ! self::delete_ticket_window($id)) {
                        $result = FALSE;
                    }
                }
                if($result) {
                    BM_Util_Util::push_admin_notice('success', 'Кассы удалены');
                } else {
                    BM_Util_Util::push_admin_notice('error', 'Не удалось удалить кассы');
                }
            }
        }

        wp_redirect($page_url);
        exit;
    }

    /**
     * Handles form data when submitted.
     *
     * @return void
     */
    public function process_form_submit()
    {
        if( ! isset($_POST['submit_ticket_window'])) {
            return;
        }

        if( ! wp_verify_nonce($_POST['_wpnonce'], 'bm_new_ticket_window')) {
            die('Go get a life script kiddies');
        }

        if( ! current_user_can('read')) {
            wp_die(__('Permission Denied!', 'bookingmanager_textdomain'));
        }

        // Get unslashed post
        $post = BM_Bookingmanager::$unslashed_post;

        $page_url = menu_page_url('bookingmanager_ticket_windows', FALSE);
        $field_id = isset($post['field_id']) ? absint($post['field_id']) : 0;

        $name = isset($post['name']) ? sanitize_text_field($post['name']) : '';
        $phones = isset($post['phones']) ? $post['phones'] : [];
        $lon = isset($post['lon']) ? sanitize_text_field($post['lon']) : '';
        $lat = isset($post['lat']) ? sanitize_text_field($post['lat']) : '';
        $city_id = isset($post['city_id']) ? absint($post['city_id']) : '';
        $working_hours = isset($post['working_hours']) ? sanitize_text_field($post['working_hours']) : '';

        $fields = array(
            'name'          => $name,
//            'phone'         => $phone,
            'lon'           => $lon,
            'lat'           => $lat,
            'city_id'       => $city_id,
            'working_hours' => $working_hours,
        );

        // New or edit?
        if( ! $field_id) {
            $insert_id = $this->insert_ticket_window($fields);
            $field_id = $insert_id;
        } else {
            $fields['id'] = $field_id;
            $insert_id = $this->insert_ticket_window($fields);
        }

        // Save phones
        $result = ($insert_id && $this->update_phones($field_id, $phones));
        if($result) {
            BM_Util_Util::push_admin_notice('success', 'ОК');
        } else {
            BM_Util_Util::push_admin_notice('error', 'Ошибка !');
        }

        // Redirect
        wp_redirect($page_url);
        exit;
    }

    /**
     * Insert a new city.
     *
     * @param array
     * @return boolean
     */
    public function insert_ticket_window($args = array())
    {
        global $wpdb;

        $defaults = array(
            'id'            => NULL,
            'name'          => '',
//            'phone'         => '',
            'lon'           => '',
            'lat'           => '',
            'city_id'       => '',
            'working_hours' => '',
        );

        $args = wp_parse_args($args, $defaults);
        $table_name = $wpdb->prefix . 'bm_ticket_windows';

        // remove row id to determine if new or update
        $row_id = (int) $args['id'];
        unset($args['id']);

        if( ! $row_id) {
            // insert a new
            if($wpdb->insert($table_name, $args)) {

                return $wpdb->insert_id;
            }
        } else {
            // do update method here
            $result = $wpdb->update($table_name, $args, array('id' => $row_id));
            if($result !== FALSE) {

                return $row_id;
            }
        }

        return FALSE;
    }

    // Insert phones into another table
    public function update_phones($ticket_window_id, $phones)
    {
        global $wpdb;

        $ticket_window_id = absint($ticket_window_id);

        // Delete all old phones
        $delete_phones_sql = "DELETE FROM {$wpdb->prefix}bm_phones WHERE ticket_window_id = $ticket_window_id";
        $wpdb->query($delete_phones_sql);

        // Insert new phones
        $insert_phones_sql = "INSERT {$wpdb->prefix}bm_phones (ticket_window_id, phone) VALUES ";
        foreach($phones as $phone) {
            $phone = esc_sql(trim($phone));
            if( ! empty($phone)) {
                $insert_phones_sql .= "(" . $ticket_window_id . ", '" . $phone . "'),";
            }
        }

        $insert_phones_sql = trim($insert_phones_sql, ',');

        $result = $wpdb->query($insert_phones_sql);

        return ($result !== FALSE);
    }

    public static function retrieve_cities_phones()
    {
        global $wpdb;

        $city_phones = $wpdb->get_results("SELECT id, ticket_window_id, phone FROM {$wpdb->prefix}bm_phones", 'ARRAY_A');
        foreach($city_phones as $phone) {
            self::$ticket_window_phones[$phone['ticket_window_id']][$phone['id']] = $phone;
        }

    }

}