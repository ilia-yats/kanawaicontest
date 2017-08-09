<?php
// Exit if accessed directly
if( ! defined('ABSPATH')) {
    exit;
}

if( ! class_exists('WP_List_Table')) {
    require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}

class KC_Images_List extends WP_List_Table
{
    /**
     * Constructor.
     *
     * @param void
     */
    public function __construct()
    {
        global $wpdb;

        parent::__construct(array(
            'singular' => 'Image',
            'plural'   => 'Images',
            'ajax'     => FALSE,
        ));

//        $cities = $wpdb->get_results("SELECT id, name FROM bm_cities", 'ARRAY_A');
//        foreach($cities as $city) {
//            $this->cities[$city['id']] = $city;
//        }
//
//        if(empty(self::$ticket_window_phones)) {
//            self::retrieve_cities_phones();
//        }
    }

//    /**
//     * Retrieve ticket_windows data from the database.
//     *
//     * @param int $id
//     *
//     * @return array
//     */
//    public static function get_ticket_window($id = 0)
//    {
//        global $wpdb;
//
//        if(empty(self::$ticket_window_phones)) {
//            self::retrieve_cities_phones();
//        }
//
//        $item = $wpdb->get_row($wpdb->prepare("SELECT * FROM kanawaicontest_images WHERE id = %d", $id));
//        $item->phones = isset(self::$ticket_window_phones[$item->id])
//            ? self::$ticket_window_phones[$item->id]
//            : [];
//
//        return $item;
//    }

    /**
     * Retrieve ticket_windows data from the database.
     *
     * @param int $per_page
     * @param int $page_number
     *
     * @return array
     */
    public static function get_images($per_page = 20, $page_number = 1)
    {
        global $wpdb;

        $sql = "SELECT kci.* FROM kanawaicontest_images kci";

//        if( ! empty($_REQUEST['s'])) {
//            $sql .= self::create_search_sql_where_clause($_REQUEST['s']);
//        }

        if( ! empty($_REQUEST['orderby'])) {
            $sql .= ' ORDER BY ' . esc_sql($_REQUEST['orderby']);
            $sql .= ! empty($_REQUEST['order']) ? ' ' . esc_sql($_REQUEST['order']) : ' ASC';
        }

        $sql .= " LIMIT $per_page";
        $sql .= ' OFFSET ' . ($page_number - 1) * $per_page;

        $result = $wpdb->get_results($sql, 'ARRAY_A');

        foreach($result as &$item) {
            $item['image_url'] = wp_get_attachment_image_src($item['attachment_id'])[0];
        }

        return $result;
    }

//    /**
//     * Retrieve all ticket_windows data from the database without limits and offsets
//     *
//     * @return array
//     */
//    public static function get_ticket_windows_in_cities()
//    {
//        global $wpdb;
//
//        if(empty(self::$ticket_window_phones)) {
//            self::retrieve_cities_phones();
//        }
//
//        $sql = "SELECT
//                  ticket_windows.*,
//                  cities.name AS city_name,
//                  cities.lon AS city_lat,
//                  cities.lat AS city_lon
//                  FROM kanawaicontest_images AS ticket_windows
//                  JOIN bm_cities AS cities
//                  ON ticket_windows.city_id = cities.id";
//
//        $ticket_windows = [];
//
//        $result = $wpdb->get_results($sql, 'ARRAY_A');
//
//        foreach($result as $row) {
//            $row['phones'] = isset(self::$ticket_window_phones[$row['id']])
//                ? self::$ticket_window_phones[$row['id']]
//                : [];
//
//            if( ! isset($ticket_windows[$row['city_name']])) {
//                $ticket_windows[$row['city_name']] = [
//                    'ticket_windows' => [],
//                    'lon'            => '',
//                    'lat'            => '',
//                    'name'           => '',
//                ];
//            }
//            $ticket_windows[$row['city_name']]['ticket_windows'][] = $row;
//            $ticket_windows[$row['city_name']]['lon'] = $row['city_lon'];
//            $ticket_windows[$row['city_name']]['lat'] = $row['city_lat'];
//            $ticket_windows[$row['city_name']]['name'] = $row['city_name'];
//        }
//
//        return $ticket_windows;
//    }

    /**
     * Delete a city record.
     *
     * @param int $id citiy id
     * @return boolean
     */
    public static function delete_image($id)
    {
        global $wpdb;

        return (boolean) $wpdb->delete(
            "kanawaicontest_images",
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

        $sql = "SELECT COUNT(*) FROM kanawaicontest_images";

//        if( ! empty($_REQUEST['s'])) {
//            $sql .= self::create_search_sql_where_clause($_REQUEST['s']);
//        }

        return $wpdb->get_var($sql);
    }

    /**
     * Text displayed when no data is available.
     *
     * @return void
     */
    public function no_items()
    {
        echo 'No images';
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
            case 'id':
            case 'title':
            case 'description':
            case 'voted':
                return $item[$column_name];
                break;
            case 'image_url':
                return '<img src="' . $item[$column_name] . '" width=\'100\' height=\'100\' style=\'max-height: 100px; width: 100px;\'>';
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
     *  Associative array of columns.
     *
     * @return array
     */
    function get_columns()
    {
        $columns = [
            'cb'            => '<input type="checkbox" />',
            'id'            => 'id',
            'title'         => 'title',
            'description'   => 'description',
            'image_url' => 'image_url',
            'voted'         => 'voted',
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
            'id'          => array('id', TRUE),
            'voted'       => array('voted', TRUE),
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
            'bulk-delete' => 'Delete',
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

        $per_page = $this->get_items_per_page('images_per_page', 5);
        $current_page = $this->get_pagenum();
        $total_items = self::record_count();

        $this->set_pagination_args([
            'total_items' => $total_items,
            'per_page'    => $per_page,
        ]);

        $this->items = $this->get_images($per_page, $current_page);
    }

    /**
     * Handles bulk action and delete.
     *
     * @return void
     */
    public function process_bulk_action()
    {
        $page_url = menu_page_url('kanawaicontest_images', FALSE);

        $result = FALSE;

        //Detect when a bulk action is being triggered...
        if('delete' === $this->current_action()) {
            // In our file that handles the request, verify the nonce.
            $nonce = esc_attr($_REQUEST['_wpnonce']);

            if(wp_verify_nonce($nonce, 'kanawaicontest_delete_image')) {

                $result = self::delete_image(absint($_REQUEST['id']));
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
                    if( ! self::delete_image($id)) {
                        $result = FALSE;
                    }
                }
            }
        }

        if($result) {
            Kanawaicontest_Util_Util::push_admin_notice('success', 'Images Deleted');
        } else {
            Kanawaicontest_Util_Util::push_admin_notice('error', 'Cannot delete images');
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
        if( ! isset($_POST['submit_image'])) {
            return;
        }

        if( ! wp_verify_nonce($_POST['_wpnonce'], 'kanawaicontest_new_image')) {
            die('Go get a life script kiddies');
        }

        // Get unslashed post
        $post = Kanawaicontest::$unslashed_post;

        $page_url = menu_page_url('kanawaicontest_images', FALSE);

        $title = isset($post['title']) ? sanitize_text_field($post['title']) : '';
        $description = isset($post['description']) ? sanitize_text_field($post['description']) : '';
        $attachment_id = isset($post['image_attachment_id']) ? absint($post['image_attachment_id']) : '';

        $fields = array(
            'title'          => $title,
            'description'    => $description,
            'attachment_id'  => $attachment_id,
        );

        $insert_id = $this->insert_image($fields);

        // Redirect
        wp_redirect($page_url);
        exit;
    }

    /**
     * Insert a new image.
     *
     * @param array
     * @return boolean
     */
    public function insert_image($args = array())
    {
        global $wpdb;

        if($wpdb->insert('kanawaicontest_images', $args)) {

            return $wpdb->insert_id;
        }
        return false;
    }
}