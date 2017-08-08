<?php
// Exit if accessed directly
if( ! defined('ABSPATH')) {
    exit;
}

if( ! class_exists('WP_List_Table')) {
    require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}

class BM_City_List extends WP_List_Table
{
    public static $city_phones = array();

    /**
     *  Returns part of SQL query for searching by substring
     *
     * @param string $search_stirng
     * @return string  SQL 'where' clause
     */
    public static function create_search_sql_where_clause($search_stirng)
    {
        return ' WHERE (name LIKE "%' . esc_sql($search_stirng) . '%"';
    }

    /**
     * Constructor.
     *
     * @param void
     */
    public function __construct()
    {
        parent::__construct(array(
            'singular' => 'Город',
            'plural'   => 'Города',
            'ajax'     => FALSE,
        ));

        if(empty(self::$city_phones)) {
            self::retrieve_cities_phones();
        }
    }

    /**
     * Retrieve cities data from the database.
     *
     * @param int $id
     *
     * @return array
     */
    public static function get_city($id = 0)
    {
        global $wpdb;

        if(empty(self::$city_phones)) {
            self::retrieve_cities_phones();
        }

        $item = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}bm_cities WHERE id = %d", $id));

        $item->phones = isset(self::$city_phones[$item->id])
            ? self::$city_phones[$item->id]
            : [];

        return $item;
    }

    /**
     * Retrieve cities data from the database.
     *
     * @param int $per_page
     * @param int $page_number
     *
     * @return array
     */
    public static function get_cities($per_page = 20, $page_number = 1)
    {
        global $wpdb;

        $sql = "SELECT * FROM {$wpdb->prefix}bm_cities";

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

        if(empty(self::$city_phones)) {
            self::retrieve_cities_phones();
        }

        foreach($result as &$city) {
            $city['phones'] = isset(self::$city_phones[$city['id']])
                ? self::$city_phones[$city['id']]
                : [];
        }

        return $result;
    }

    /**
     * Retrieve all cities data from the database without limits and offsets
     *
     * @return array
     */
    public static function get_all_cities()
    {
        global $wpdb;

        $sql = "SELECT * FROM {$wpdb->prefix}bm_cities";

        if(empty(self::$city_phones)) {
            self::retrieve_cities_phones();
        }

        $result = $wpdb->get_results($sql, 'ARRAY_A');
        foreach($result as &$city) {
            $city['phones'] = isset(self::$city_phones[$city['id']])
                ? self::$city_phones[$city['id']]
                : [];
        }

        return $result;
    }

    /**
     * Delete a citiy record.
     *
     * @param int $id citiy id
     * @return  boolean
     */
    public static function delete_city($id)
    {
        global $wpdb;

        return ( boolean ) $wpdb->delete(
            "{$wpdb->prefix}bm_cities",
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

        $sql = "SELECT COUNT(*) FROM {$wpdb->prefix}bm_cities";

        if( ! empty($_REQUEST['s'])) {
            $sql .= self::create_search_sql_where_clause($_REQUEST['s']);
        }

        return $wpdb->get_var($sql);
    }

    /**
     * Text displayed when no citiy data is available.
     *
     * @return void
     */
    public function no_items()
    {
        echo 'Нет городов';
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
            case 'lon':
            case 'lat':
                return $item[$column_name];
                break;
            case 'phone':
                return implode(",\n", array_map(function($el) {
                    return $el['phone'];
                }, $item['phones']));
                break;
            default:
                return $item[$column_name];
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
            'cb'    => '<input type="checkbox" />',
            'name'  => 'Название',
            'phone' => 'Телефоны в заголовке сайта',
            'lat'   => 'Широта',
            'lon'   => 'Долгота',
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
            'name' => array('name', TRUE),
            'lon'  => array('lon', TRUE),
            'lat'  => array('lat', TRUE),
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

        $per_page = $this->get_items_per_page('cities_per_page', 5);
        $current_page = $this->get_pagenum();
        $total_items = self::record_count();

        $this->set_pagination_args([
            'total_items' => $total_items,
            'per_page'    => $per_page,
        ]);

        $this->items = self::get_cities($per_page, $current_page);
    }

    /**
     * Handles bulk action and delete.
     *
     * @return void
     */
    public function process_bulk_action()
    {
        $page_url = menu_page_url('bookingmanager_cities', FALSE);

        $result = FALSE;

        //Detect when a bulk action is being triggered...
        if('delete' === $this->current_action()) {
            // In our file that handles the request, verify the nonce.
            $nonce = esc_attr($_REQUEST['_wpnonce']);

            if(wp_verify_nonce($nonce, 'bm_delete_city')) {

                $result = self::delete_city($_REQUEST['id']);
            }
        }

        // If the delete bulk action is triggered
        if('bulk-delete' === $this->current_action()) {
            if(isset($_POST['bulk-delete']) && is_array($_POST['bulk-delete'])) {
                $delete_ids = esc_sql($_POST['bulk-delete']);
                // loop over the array of record ids and delete them
                $result = TRUE;
                foreach($delete_ids as $id) {
                    if( ! self::delete_city($id)) {
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

    /**
     * Handles form data when submitted.
     *
     * @return void
     */
    public function process_form_submit()
    {
        if( ! isset($_POST['submit_city'])) {
            return;
        }

        if( ! wp_verify_nonce($_POST['_wpnonce'], 'bm_new_city')) {
            die('Go get a life script kiddies');
        }

        if( ! current_user_can('read')) {
            wp_die(__('Permission Denied!', 'bookingmanager_textdomain'));
        }

        $result = FALSE;

        // Get unslashed post
        $post = BM_Bookingmanager::$unslashed_post;

        $page_url = menu_page_url('bookingmanager_cities', FALSE);
        $field_id = isset($post['field_id']) ? absint($post['field_id']) : 0;

        $name = isset($post['name']) ? sanitize_text_field($post['name']) : '';
        $phones = isset($post['phones']) ? $post['phones'] : [];
        $lon = isset($post['lon']) ? sanitize_text_field($post['lon']) : '';
        $lat = isset($post['lat']) ? sanitize_text_field($post['lat']) : '';

        $fields = array(
            'name' => $name,
            'lon'  => $lon,
            'lat'  => $lat,
        );

        // New or edit?
        if( ! $field_id) {
            $insert_id = $this->insert_city($fields);
            $field_id = $insert_id;
        } else {
            $fields['id'] = $field_id;
            $insert_id = $this->insert_city($fields);
        }

        // Save phones
        $result = ($insert_id && $this->update_phones($field_id, $phones));

        // Register message
        $message = ($result) ? ['type' => 'success', 'text' => 'OK.'] : ['type' => 'error', 'text' => 'Ошибка !'];
        BM_Util_Util::push_admin_notice($message['type'], $message['text']);

        // Redirect
        wp_redirect($page_url);
        exit();
    }

    /**
     * Insert a new city.
     *
     * @param array
     * @return boolean
     */
    public function insert_city($args = array())
    {
        global $wpdb;

        $defaults = array(
            'id'   => NULL,
            'name' => '',
            'lon'  => '',
            'lat'  => '',
        );

        $args = wp_parse_args($args, $defaults);
        $table_name = $wpdb->prefix . 'bm_cities';

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
    public function update_phones($city_id, $phones)
    {
        global $wpdb;

        $city_id = absint($city_id);

        // Delete all old phones
        $delete_phones_sql = "DELETE FROM {$wpdb->prefix}bm_phones WHERE city_id = $city_id";
        $wpdb->query($delete_phones_sql);

        // Insert new phones
        $insert_phones_sql = "INSERT {$wpdb->prefix}bm_phones (city_id, phone) VALUES ";
        foreach($phones as $phone) {
            $phone = esc_sql(trim($phone));
            if( ! empty($phone)) {
                $insert_phones_sql .= "(" . $city_id . ", '" . $phone . "'),";
            }
        }

        $insert_phones_sql = trim($insert_phones_sql, ',');

        $result = $wpdb->query($insert_phones_sql);

        if($result !== FALSE) {

            return TRUE;
        }

        return FALSE;
    }

    public static function retrieve_cities_phones()
    {
        global $wpdb;

        $city_phones = $wpdb->get_results("SELECT id, city_id, phone FROM {$wpdb->prefix}bm_phones", 'ARRAY_A');
        foreach($city_phones as $phone) {
            self::$city_phones[$phone['city_id']][$phone['id']] = $phone;
        }

        return self::$city_phones;
    }
}