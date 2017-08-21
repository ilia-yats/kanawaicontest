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
            'singular' => __('Voter'),
            'plural' => __('Voters'),
            'ajax' => FALSE,
        ));
    }

    public static function get_voters($per_page = 20, $page_number = 1)
    {
        global $wpdb;

        $sql = "SELECT kcv.*, GROUP_CONCAT(kcp.title SEPARATOR ', ') AS voted FROM kanawaicontest_voters AS kcv
              JOIN kanawaicontest_posters_votes AS kciv ON kcv.id = kciv.voter_id 
              JOIN kanawaicontest_posters AS kcp ON kciv.poster_id = kcp.id ";

        $tour_id = ! empty($_REQUEST['tour_id'])
            ? absint($_REQUEST['tour_id'])
            : Kanawaicontest::get_instance()->tours->init()->tours_list->get_current_tour_id();

        $sql .= ' WHERE kciv.tour_id = ' . $tour_id;

        if( ! empty($_REQUEST['poster_id'])) {
            $sql .= " AND kciv.poster_id = " . absint($_REQUEST['poster_id']);
        }

        if( ! empty($_REQUEST['s'])) {
            $like = '%' . esc_sql($_REQUEST['s']) . '%';
            $sql .= " AND (kcv.last_name LIKE '$like' OR kcv.name LIKE '$like' OR kcv.email LIKE '$like')";
        }

        $sql .= ' GROUP BY kcv.id';

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

        $sql = "SELECT COUNT(*) FROM kanawaicontest_voters AS kcv
              JOIN kanawaicontest_posters_votes AS kciv ON kcv.id = kciv.voter_id ";

        $tour_id = ! empty($_REQUEST['tour_id'])
            ? absint($_REQUEST['tour_id'])
            : Kanawaicontest::get_instance()->tours->init()->tours_list->get_current_tour_id();

        $sql .= ' WHERE kciv.tour_id = ' . $tour_id;

        if( ! empty($_REQUEST['poster_id'])) {
            $sql .= " AND kciv.poster_id = " . absint($_REQUEST['poster_id']);
        }

        if( ! empty($_REQUEST['s'])) {
            $like = '%' . esc_sql($_REQUEST['s']) . '%';
            $sql .= " AND (kcv.last_name LIKE '$like' OR kcv.name LIKE '$like' OR kcv.email LIKE '$like')";
        }

        $sql .= ' GROUP BY kcv.id';

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
            'name' => __('Name'),
            'last_name' => __('Last Name'),
            'phone' => __('Phone'),
            'email' => __('Email'),
            'voted' => __('Voted'),
            'photo' => __('Photo'),
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
//            'name' => array('name', TRUE),
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
            'bulk-delete' => __('Delete'),
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

        // If the delete bulk action is triggered
        if ((isset($_POST['action']) && $_POST['action'] == 'bulk-delete')
            || (isset($_POST['action2']) && $_POST['action2'] == 'bulk-delete')
        ) {
            if (isset($_POST['bulk-delete']) && is_array($_POST['bulk-delete'])) {
                $delete_ids = esc_sql($_POST['bulk-delete']);
                // loop over the array of record ids and delete them
                $result = TRUE;
                foreach ($delete_ids as $id) {
                    if ( ! self::delete_voter($id)) {
                        $result = FALSE;
                    }
                }
            }
        }

        if ($result) {
            Kanawaicontest_Util_Util::push_admin_notice('success', __('Voters Deleted'));
        } else {
            Kanawaicontest_Util_Util::push_admin_notice('error', __('Cannot delete voters'));
        }

        // Redirect
        $page_url = isset($_REQUEST['tour_id'])
            ? add_query_arg(array('tour_id' => absint($_REQUEST['tour_id'])), menu_page_url('kanawaicontest_voters', FALSE))
            : menu_page_url('kanawaicontest_voters', FALSE);
        wp_redirect($page_url);
        exit;
    }

}