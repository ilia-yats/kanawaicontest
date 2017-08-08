<?php
// Exit if accessed directly
if( ! defined('ABSPATH')) {
    exit;
}

if( ! class_exists('WP_List_Table')) {
    require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}

class BM_Route_List extends WP_List_Table
{
    /* ---------------------------------- Methods for admin part of site ----------------- */

    public $cities = NULL;

    public static $places_count_in_bus_types = array(
        1 => 8,
        2 => 14,
        3 => 33,
    );

    /**
     *  Returns part of SQL query for searching by substring
     *
     * @param string $search_stirng
     * @return string  SQL 'where' clause
     */
    public static function create_search_sql_where_clause($search_stirng)
    {
        global $wpdb;

        return " WHERE (route.name LIKE '%" . esc_sql($search_stirng) . "%'
            OR route.from_city_id IN (SELECT id FROM {$wpdb->prefix}bm_cities WHERE name LIKE '%" . esc_sql($search_stirng) . "%')
            OR route.to_city_id IN (SELECT id FROM {$wpdb->prefix}bm_cities WHERE name LIKE '%" . esc_sql($search_stirng) . "%'))";
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
            'singular' => 'Маршрут',
            'plural'   => 'Маршруты',
            'ajax'     => FALSE,
        ));

        $cities = $wpdb->get_results("SELECT id, name FROM {$wpdb->prefix}bm_cities", 'ARRAY_A');

        foreach($cities as $city) {
            $this->cities[$city['id']] = $city;
        }
    }

    /**
     * Retrieve route data from the database.
     *
     * @param int $id
     *
     * @return array
     */
    public static function get_route($id = 0)
    {
        global $wpdb;

        return $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}bm_routes WHERE id = %d", $id));
    }

    /**
     * Retrieve routes data from the database.
     *
     * @param int $per_page
     * @param int $page_number
     *
     * @return array
     */
    public static function get_routes($per_page = 20, $page_number = 1)
    {
        global $wpdb;

//        $sql = "SELECT * FROM {$wpdb->prefix}bm_routes";

        $sql = "
          SELECT
              route.*,
              return_route.name AS return_route_name
          FROM {$wpdb->prefix}bm_routes AS route
          LEFT JOIN {$wpdb->prefix}bm_routes AS return_route
          ON route.return_route_id = return_route.id";

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

        // Collect information about week days and dates in strings for each route
        $days = [
            'day_1' => 'Пн',
            'day_2' => 'Вт',
            'day_3' => 'Ср',
            'day_4' => 'Чт',
            'day_5' => 'Пт',
            'day_6' => 'Сб',
            'day_7' => 'Вс',
        ];

        $dates_types = [
            'on_even'   => 'четные',
            'on_uneven' => 'нечетные',
        ];

        // Convert some properties for good representation
        foreach($result as &$item) {
            $item_days = [];
            $item_dates_types = [];
            foreach($days as $day_code => $day_name) {
                if($item[$day_code] == 1) {
                    $item_days[] = $day_name;
                }
            }
            foreach($dates_types as $date_type => $type_name) {
                if($item[$date_type] == 1) {
                    $item_dates_types[] = $type_name;
                }
            }

            $item['days'] = implode(', ', $item_days);
            $item['dates'] = implode(', ', $item_dates_types);
        }

        return $result;
    }

    /**
     * Retrieve all routes data from the database without limits and offsets
     *
     * @return array
     */
    public static function get_all_routes()
    {
        global $wpdb;

        $sql = "SELECT * FROM {$wpdb->prefix}bm_routes";

        return $wpdb->get_results($sql, 'ARRAY_A');
    }

    /**
     * Retrieve all routes which start from given city
     *
     * @param string $city
     * @return array
     */
    public static function get_all_routes_from($city)
    {
        global $wpdb;

        $sql = "SELECT * FROM {$wpdb->prefix}bm_routes WHERE from_city_id LIKE '%" . esc_sql($city) . "%'";

        return $wpdb->get_results($sql, 'ARRAY_A');
    }

    /**
     * Retrieve all routes which start to given city
     *
     * @param string $city
     * @return array
     */
    public static function get_all_routes_to($city)
    {
        global $wpdb;

        $sql = "SELECT * FROM {$wpdb->prefix}bm_routes WHERE to_city_id LIKE '%" . esc_sql($city) . "%'";

        return $wpdb->get_results($sql, 'ARRAY_A');
    }

    /**
     * Returns price of given route
     *
     * @param $route_id
     * @return mixed
     * @throws Exception
     */
    public static function get_price($route_id)
    {
        global $wpdb;

        $sql = "SELECT price FROM {$wpdb->prefix}bm_routes WHERE id = " . absint($route_id);

        $result = $wpdb->get_row($sql, 'ARRAY_A');

        if(empty($result['price'])) {

            throw new Exception('Cannot get information about price on route ' . $route_id);
        }

        return $result['price'];
    }

    /**
     * Delete a route record.
     *
     * @param int $id route id
     * @return boolean
     */
    public static function delete_route($id)
    {
        global $wpdb;

        return ( boolean ) $wpdb->delete(
            "{$wpdb->prefix}bm_routes",
            ['id' => $id],
            ['%d']
        );
    }

    /**
     * Returns data about route, which are reverse to the given one, i.e. runs in reverse direction
     *
     * @param $route_id
     * @return array|null
     */
    public static function get_return_route($route_id)
    {
        global $wpdb;

        $route_id = absint($route_id);

        $route_data = $wpdb->get_row(
            "SELECT * FROM {$wpdb->prefix}bm_routes
            WHERE id = (SELECT return_route_id FROM {$wpdb->prefix}bm_routes WHERE id = $route_id)",
            'ARRAY_A'
        );

//        $there_route_data = $wpdb->get_row(
//            "SELECT * FROM {$wpdb->prefix}bm_routes WHERE id = $route_id",
//            'ARRAY_A'
//        );
//
//        $route_data = $wpdb->get_row(
//            "SELECT * FROM {$wpdb->prefix}bm_routes
//            WHERE from_city_id = " . absint($there_route_data['to_city_id']) . "
//            AND to_city_id = " . absint($there_route_data['from_city_id']),
//            'ARRAY_A'
//        );

        return $route_data;
    }

    /**
     * Returns the count of records in the database.
     *
     * @return null|string
     */
    public static function record_count()
    {
        global $wpdb;

        $sql = "SELECT COUNT(*) FROM {$wpdb->prefix}bm_routes AS route";

        if( ! empty($_REQUEST['s'])) {
            $sql .= self::create_search_sql_where_clause($_REQUEST['s']);
        }

        return $wpdb->get_var($sql);
    }

    /**
     * Text displayed when no route data is available.
     *
     * @return void
     */
    public function no_items()
    {
        echo 'Нет маршрутов';
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
            case 'price':
            case 'arrive_time':
            case 'leave_time':
            case 'leave_address':
            case 'arrive_address':
            case 'days':
            case 'dates':
            case 'return_route_name':
                return $item[$column_name];
            case 'from_city_id':
            case 'to_city_id':
                return isset($this->cities[$item[$column_name]]['name']) ? $this->cities[$item[$column_name]]['name'] : '-';
                break;
            case 'bus_type':
                return isset(self::$places_count_in_bus_types[$item[$column_name]])
                    ? self::$places_count_in_bus_types[$item[$column_name]]
                    : self::$places_count_in_bus_types[1];
                break;
            default:
                return '-';
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
            'cb'                => '<input type="checkbox" />',
            'name'              => 'Название',
            'from_city_id'      => 'Из',
            'to_city_id'        => 'В',
            'price'             => 'Цена',
            'leave_time'        => 'Отправление',
            'leave_address'     => 'Адрес отправления',
            'arrive_time'       => 'Прибытие',
            'arrive_address'    => 'Адрес прибытия',
            'days'              => 'Дни',
            'dates'             => 'Числа',
            'bus_type'          => 'Мест',
            'return_route_name' => 'Обратный',
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
            'name'              => array('name', TRUE),
            'from_city_id'      => array('from_city_id', TRUE),
            'to_city_id'        => array('to_city_id', TRUE),
            'price'             => array('price', TRUE),
            'arrive_time'       => array('arrive_time', TRUE),
            'leave_time'        => array('leave_time', TRUE),
            'leave_address'     => array('leave_address', TRUE),
            'arrive_address'    => array('arrive_address', TRUE),
            'days'              => array('days', TRUE),
            'dates'             => array('dates', TRUE),
            'bus_type'          => array('bus_type', TRUE),
            'return_route_name' => array('return_route_name', TRUE),
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

        $per_page = $this->get_items_per_page('routes_per_page', 5);
        $current_page = $this->get_pagenum();
        $total_items = self::record_count();

        $this->set_pagination_args([
            'total_items' => $total_items,
            'per_page'    => $per_page,
        ]);

        $this->items = self::get_routes($per_page, $current_page);
    }

    /**
     * Handles bulk action and delete.
     *
     * @return void
     */
    public function process_bulk_action()
    {
        $page_url = menu_page_url('bookingmanager_routes', FALSE);

        $result = FALSE;

        //Detect when a bulk action is being triggered...
        if('delete' === $this->current_action()) {
            // In our file that handles the request, verify the nonce.
            $nonce = esc_attr($_REQUEST['_wpnonce']);

            if( ! wp_verify_nonce($nonce, 'bm_delete_route')) {
                die('Go get a life script kiddies');
            } else {
                $result = self::delete_route(absint($_REQUEST['id']));
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
                    if( ! self::delete_route($id)) {
                        $result = FALSE;
                    }
                }
            }
        }

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
     * Handles form data when submitted.
     *
     * @return void
     */
    public function process_form_submit()
    {
        if( ! isset($_POST['submit_route'])) {
            return;
        }

        if( ! wp_verify_nonce($_POST['_wpnonce'], 'bm_new_route')) {
            die('Go get a life script kiddies');
        }

        if( ! current_user_can('read')) {
            wp_die(__('Permission Denied!', 'bookingmanager_textdomain'));
        }

        $errors = array();
        $page_url = menu_page_url('bookingmanager_routes', FALSE);
        $field_id = isset($_POST['field_id']) ? absint($_POST['field_id']) : 0;

        // Get unslashed post
        $post = BM_Bookingmanager::$unslashed_post;

        $name = isset($post['name']) ? sanitize_text_field($post['name']) : '';
        $from_city_id = isset($post['from_city_id']) ? absint($post['from_city_id']) : '';
        $to_city_id = isset($post['to_city_id']) ? absint($post['to_city_id']) : '';
        $price = isset($post['price']) ? sanitize_text_field($post['price']) : '';
        $arrive_time = isset($post['arrive_time']) ? sanitize_text_field($post['arrive_time']) : '';
        $arrive_address = isset($post['arrive_address']) ? sanitize_text_field($post['arrive_address']) : '';
        $leave_address = isset($post['leave_address']) ? sanitize_text_field($post['leave_address']) : '';
        $leave_time = isset($post['leave_time']) ? sanitize_text_field($post['leave_time']) : '';
        $in_monday = (isset($post['day_1']) && $post['day_1'] == 'on') ? 1 : 0;
        $in_tuesday = (isset($post['day_2']) && $post['day_2'] == 'on') ? 1 : 0;
        $in_wednesday = (isset($post['day_3']) && $post['day_3'] == 'on') ? 1 : 0;
        $in_thursday = (isset($post['day_4']) && $post['day_4'] == 'on') ? 1 : 0;
        $in_friday = (isset($post['day_5']) && $post['day_5'] == 'on') ? 1 : 0;
        $in_saturday = (isset($post['day_6']) && $post['day_6'] == 'on') ? 1 : 0;
        $in_sunday = (isset($post['day_7']) && $post['day_7'] == 'on') ? 1 : 0;
        $on_even = (isset($post['on_even']) && $post['on_even'] == 'on') ? 1 : 0;
        $on_uneven = (isset($post['on_uneven']) && $post['on_uneven'] == 'on') ? 1 : 0;
        $bus_type = isset($post['bus_type']) ? absint($post['bus_type']) : 1;
        $days_in_trip = isset($post['days_in_trip']) ? absint($post['days_in_trip']) : 0;
        $return_route_id = isset($post['return_route_id']) ? absint($post['return_route_id']) : 0;

        $fields = array(
            'name'            => $name,
            'from_city_id'    => $from_city_id,
            'to_city_id'      => $to_city_id,
            'price'           => $price,
            'arrive_time'     => $arrive_time,
            'leave_time'      => $leave_time,
            'arrive_address'  => $arrive_address,
            'leave_address'   => $leave_address,
            'day_1'           => $in_monday,
            'day_2'           => $in_tuesday,
            'day_3'           => $in_wednesday,
            'day_4'           => $in_thursday,
            'day_5'           => $in_friday,
            'day_6'           => $in_saturday,
            'day_7'           => $in_sunday,
            'on_even'         => $on_even,
            'on_uneven'       => $on_uneven,
            'bus_type'        => $bus_type,
            'days_in_trip'    => $days_in_trip,
            'return_route_id' => $return_route_id,
        );

        // New or edit?
        if( ! $field_id) {
            $insert_id = $this->insert_route($fields);
        } else {
            $fields['id'] = $field_id;

            $insert_id = $this->insert_route($fields);
        }

        if($insert_id) {
            BM_Util_Util::push_admin_notice('success', 'ОК');
        } else {
            BM_Util_Util::push_admin_notice('error', 'Ошибка !');
        }

        // Redirect
        wp_redirect($page_url);
        exit;
    }

    /**
     * Insert a new route.
     *
     * @param array
     * @return boolean
     */
    public function insert_route($args = array())
    {
        global $wpdb;

        $defaults = array(
            'id'              => NULL,
            'name'            => '',
            'from_city_id'    => '',
            'to_city_id'      => '',
            'price'           => '',
            'arrive_time'     => '',
            'leave_time'      => '',
            'arrive_address'  => '',
            'leave_address'   => '',
            'day_1'           => '',
            'day_2'           => '',
            'day_3'           => '',
            'day_4'           => '',
            'day_5'           => '',
            'day_6'           => '',
            'day_7'           => '',
            'on_even'         => '',
            'on_uneven'       => '',
            'bus_type'        => '',
            'days_in_trip'    => '',
            'return_route_id' => '',
        );

        $args = wp_parse_args($args, $defaults);
        $table_name = $wpdb->prefix . 'bm_routes';

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

}