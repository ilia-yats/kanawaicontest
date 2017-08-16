<?php
// Exit if accessed directly
if ( ! defined('ABSPATH')) {
    exit;
}

if ( ! class_exists('WP_List_Table')) {
    require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}

class KC_Voters_List extends WP_List_Table
{
    public function __construct()
    {
        parent::__construct(array(
            'singular' => 'Voter',
            'plural' => 'Voters',
            'ajax' => FALSE,
        ));
    }

    public static function get_voters($per_page = 20, $page_number = 1)
    {
        global $wpdb;

        $sql = "SELECT kcv.* FROM kanawaicontest_voters AS kcv";

        if ( ! empty($_REQUEST['tour_id'])) {
            $sql .= ' INNER JOIN kanawaicontest_images_votes AS kciv ON kcv.id = kciv.voter_id 
                WHERE kciv.tour_id = ' . absint($_REQUEST['tour_id']) . ' AND kciv.id IS NOT NULL';
        }

        if ( ! empty($_REQUEST['orderby'])) {
            $sql .= ' ORDER BY ' . esc_sql($_REQUEST['orderby']);
            $sql .= ! empty($_REQUEST['order']) ? ' ' . esc_sql($_REQUEST['order']) : ' ASC';
        }

        $sql .= " LIMIT $per_page";
        $sql .= ' OFFSET ' . ($page_number - 1) * $per_page;

        $result = $wpdb->get_results($sql, 'ARRAY_A');

        return $result;
    }

    /**
     * Delete a city record.
     *
     * @param int $id citiy id
     * @return boolean
     */
    public static function delete_voter($id)
    {
        global $wpdb;

        return (boolean)$wpdb->delete(
            "kanawaicontest_voters",
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

        $sql = "SELECT COUNT(*) FROM kanawaicontest_voters AS kcv";
        if ( ! empty($_REQUEST['tour_id'])) {
            $sql .= ' INNER JOIN kanawaicontest_images_votes AS kciv ON kcv.id = kciv.voter_id 
                WHERE kciv.tour_id = ' . absint($_REQUEST['tour_id']) . ' AND kciv.id IS NOT NULL';
        }

        return $wpdb->get_var($sql);
    }

    public function get_id_by_email($email)
    {
        global $wpdb;

        $voter = $wpdb->get_row($wpdb->prepare("SELECT * FROM kanawaicontest_voters AS kcv WHERE email = '%s'", $email), "ARRAY_A");

        return isset($voter['id']) ? $voter['id'] : false;
    }

    public function create_voter($name, $last_name, $email, $phone)
    {
        global $wpdb;

        $wpdb->query($wpdb->prepare("INSERT INTO kanawaicontest_voters(name, last_name, email, phone)
            VALUES ('%s', '%s', '%s', '%s')", $name, $last_name, $email, $phone));

        return $wpdb->insert_id;
    }

    /**
     * Text displayed when no data is available.
     *
     * @return void
     */
    public function no_items()
    {
        echo 'No voters';
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
        switch ($column_name) {
            case 'name':
            case 'last_name':
            case 'email':
            case 'phone':
            case 'voted':
                return $item[$column_name];
                break;
            case 'photo':
                return 'photo here';
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
        $columns = array(
            'cb' => '<input type="checkbox" />',
            'name' => 'name',
            'phone' => 'phone',
            'last_name' => 'last_name',
            'email' => 'email',
            'voted' => 'voted',
            'photo' => 'photo',
        );

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
            'voted' => array('voted', TRUE),
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
        $actions = array(
            'bulk-delete' => 'Delete',
        );

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

        $per_page = $this->get_items_per_page('voters_per_page', 5);
        $current_page = $this->get_pagenum();
        $total_items = self::record_count();

        $this->set_pagination_args(array(
            'total_items' => $total_items,
            'per_page' => $per_page,
        ));

        $this->items = $this->get_voters($per_page, $current_page);
    }

    public function process_bulk_action()
    {
        $result = FALSE;

        //Detect when a bulk action is being triggered...
        if ('delete' === $this->current_action()) {
            // In our file that handles the request, verify the nonce.
            $nonce = esc_attr($_REQUEST['_wpnonce']);

            if (wp_verify_nonce($nonce, 'kanawaicontest_delete_vote')) {

                $result = self::delete_vote(absint($_REQUEST['id']));
            }
        }

        // If the delete bulk action is triggered
        if ((isset($_POST['action']) && $_POST['action'] == 'bulk-delete')
            || (isset($_POST['action2']) && $_POST['action2'] == 'bulk-delete')
        ) {
            if (isset($_POST['bulk-delete']) && is_array($_POST['bulk-delete'])) {
                $delete_ids = esc_sql($_POST['bulk-delete']);
                // loop over the array of record ids and delete them
                $result = TRUE;
                foreach ($delete_ids as $id) {
                    if ( ! self::delete_image($id)) {
                        $result = FALSE;
                    }
                }
            }
        }

        if ($result) {
            Kanawaicontest_Util_Util::push_admin_notice('success', 'Images Deleted');
        } else {
            Kanawaicontest_Util_Util::push_admin_notice('error', 'Cannot delete images');
        }

        // Redirect
        $args = array('tour_id' => absint($_REQUEST['tour_id']));
        $page_url = add_query_arg($args, menu_page_url('kanawaicontest_images', FALSE));
        wp_redirect($page_url);
        exit;
    }

//    /**
//     * Handles form data when submitted.
//     *
//     * @return void
//     */
//    public function process_form_submit()
//    {
//        if( ! isset($_POST['submit_voter'])) {
//            return;
//        }
//
//        if( ! wp_verify_nonce($_POST['_wpnonce'], 'bm_new_ticket_window')) {
//            die('Go get a life script kiddies');
//        }
//
//        if( ! current_user_can('read')) {
//            wp_die(__('Permission Denied!', 'bookingmanager_textdomain'));
//        }
//
//        // Get unslashed post
//        $post = BM_Bookingmanager::$unslashed_post;
//
//        $page_url = menu_page_url('bookingmanager_ticket_windows', FALSE);
//        $field_id = isset($post['field_id']) ? absint($post['field_id']) : 0;
//
//        $name = isset($post['name']) ? sanitize_text_field($post['name']) : '';
//        $phones = isset($post['phones']) ? $post['phones'] : [];
//        $lon = isset($post['lon']) ? sanitize_text_field($post['lon']) : '';
//        $lat = isset($post['lat']) ? sanitize_text_field($post['lat']) : '';
//        $city_id = isset($post['city_id']) ? absint($post['city_id']) : '';
//        $working_hours = isset($post['working_hours']) ? sanitize_text_field($post['working_hours']) : '';
//
//        $fields = array(
//            'name'          => $name,
////            'phone'         => $phone,
//            'lon'           => $lon,
//            'lat'           => $lat,
//            'city_id'       => $city_id,
//            'working_hours' => $working_hours,
//        );
//
//        // New or edit?
//        if( ! $field_id) {
//            $insert_id = $this->insert_ticket_window($fields);
//            $field_id = $insert_id;
//        } else {
//            $fields['id'] = $field_id;
//            $insert_id = $this->insert_ticket_window($fields);
//        }
//
//        // Save phones
//        $result = ($insert_id && $this->update_phones($field_id, $phones));
//        if($result) {
//            BM_Util_Util::push_admin_notice('success', 'ОК');
//        } else {
//            BM_Util_Util::push_admin_notice('error', 'Ошибка !');
//        }
//
//        // Redirect
//        wp_redirect($page_url);
//        exit;
//    }

//    /**
//     * Insert a new city.
//     *
//     * @param array
//     * @return boolean
//     */
//    public function insert_ticket_window($args = array())
//    {
//        global $wpdb;
//
//        $defaults = array(
//            'id'            => NULL,
//            'name'          => '',
////            'phone'         => '',
//            'lon'           => '',
//            'lat'           => '',
//            'city_id'       => '',
//            'working_hours' => '',
//        );
//
//        $args = wp_parse_args($args, $defaults);
//        $table_name = $wpdb->prefix . 'kanawaicontest_voters';
//
//        // remove row id to determine if new or update
//        $row_id = (int) $args['id'];
//        unset($args['id']);
//
//        if( ! $row_id) {
//            // insert a new
//            if($wpdb->insert($table_name, $args)) {
//
//                return $wpdb->insert_id;
//            }
//        } else {
//            // do update method here
//            $result = $wpdb->update($table_name, $args, array('id' => $row_id));
//            if($result !== FALSE) {
//
//                return $row_id;
//            }
//        }
//
//        return FALSE;
//    }

//    public static function retrieve_cities_phones()
//    {
//        global $wpdb;
//
//        $city_phones = $wpdb->get_results("SELECT id, ticket_window_id, phone FROM bm_phones", 'ARRAY_A');
//        foreach($city_phones as $phone) {
//            self::$ticket_window_phones[$phone['ticket_window_id']][$phone['id']] = $phone;
//        }
//
//    }

}