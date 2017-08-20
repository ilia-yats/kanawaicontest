<?php
// Exit if accessed directly
if ( ! defined('ABSPATH')) {
    exit;
}

if ( ! class_exists('WP_List_Table')) {
    require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}

class KC_Tours_List extends WP_List_Table
{
    public function __construct()
    {
        parent::__construct(array(
            'singular' => 'Tour',
            'plural' => 'Tours',
            'ajax' => FALSE,
        ));
    }

    public function get_tours($per_page = 20, $page_number = 1)
    {
        global $wpdb;

        // Get all tours except the current one
        $sql = "SELECT * FROM kanawaicontest_tours WHERE id != " . $this->get_current_tour_id();

        if ( ! empty($_REQUEST['orderby'])) {
            $sql .= ' ORDER BY ' . esc_sql($_REQUEST['orderby']);
            $sql .= ! empty($_REQUEST['order']) ? ' ' . esc_sql($_REQUEST['order']) : ' ASC';
        } else {
            $sql .= ' ORDER BY start_date DESC';
        }

        $sql .= " LIMIT $per_page";
        $sql .= ' OFFSET ' . ($page_number - 1) * $per_page;

        $result = $wpdb->get_results($sql, 'ARRAY_A');

        return $result;
    }

    public function get_current_tour_id()
    {
        global $wpdb;
        static $current_tour_id = null;
        if (null === $current_tour_id) {
            $current_tour_id = $wpdb->get_var("SELECT id FROM kanawaicontest_tours WHERE status = 'active' ORDER BY start_date DESC LIMIT 1");
        }

        return $current_tour_id;
    }

    public function get_tour($id)
    {
        global $wpdb;

        $tour = $wpdb->get_row($wpdb->prepare("SELECT * FROM kanawaicontest_tours WHERE id = %d", $id), 'ARRAY_A');

        return $tour;
    }

    public function record_count()
    {
        global $wpdb;

        $sql = "SELECT COUNT(*) FROM kanawaicontest_tours  WHERE id != " . $this->get_current_tour_id();

        return $wpdb->get_var($sql);
    }

    public function no_items()
    {
        echo 'No tours';
    }

    public function column_default($item, $column_name)
    {
        switch ($column_name) {
            case 'id':
            case 'title':
            case 'status':
            case 'start_date':
            case 'end_date':
                return $item[$column_name];
                break;
        }
    }

    function column_cb($item)
    {
        return sprintf(
            '<input type="checkbox" name="bulk-delete[]" value="%s" />', $item['id']
        );
    }

    function column_id($item)
    {
        $title = '<strong>' . $item['id'] . '</strong>';
        $actions = [
            'posters' => sprintf('<a href="?page=kanawaicontest_posters&action=list&tour_id=%d">Posters</a>', absint($item['id'])),
            'voters' => sprintf('<a href="?page=kanawaicontest_voters&action=list&id=%d">Voters</a>', absint($item['id'])),
        ];

        return $title . $this->row_actions($actions);
    }

    function get_columns()
    {
        $columns = [
            'cb' => '<input type="checkbox" />',
            'id' => 'id',
            'title' => 'title',
            'status' => 'status',
            'start_date' => 'start_date',
            'end_date' => 'end_date',
        ];

        return $columns;
    }

    public function get_sortable_columns()
    {
        $sortable_columns = array(
            'id' => array('id', TRUE),
            'status' => array('status', TRUE),
            'start_date' => array('start_date', TRUE),
            'end_date' => array('end_date', TRUE),
        );

        return $sortable_columns;
    }

    public function get_bulk_actions()
    {
        $actions = [
            'bulk-delete' => 'Delete',
        ];

        return $actions;
    }

    public static function delete_tour($id)
    {
        global $wpdb;

        return (boolean)$wpdb->delete(
            "kanawaicontest_tours",
            ['id' => $id],
            ['%d']
        );
    }

    public function prepare_items()
    {
        $this->_column_headers = $this->get_column_info();

        $per_page = $this->get_items_per_page('tours_per_page', 5);
        $current_page = $this->get_pagenum();
        $total_items = self::record_count();

        $this->set_pagination_args([
            'total_items' => $total_items,
            'per_page' => $per_page,
        ]);

        $this->items = $this->get_tours($per_page, $current_page);
    }

    public function process_bulk_action()
    {
        $result = FALSE;

        //Detect when a bulk action is being triggered...
        if ('delete' === $this->current_action()) {
            // In our file that handles the request, verify the nonce.
            $nonce = esc_attr($_REQUEST['_wpnonce']);

            if (wp_verify_nonce($nonce, 'kanawaicontest_delete_tour')) {

                $result = self::delete_tour(absint($_REQUEST['id']));
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
                    if ( ! self::delete_tour($id)) {
                        $result = FALSE;
                    }
                }
            }
        }

        if ($result) {
            Kanawaicontest_Util_Util::push_admin_notice('success', 'Tours Deleted');
        } else {
            Kanawaicontest_Util_Util::push_admin_notice('error', 'Cannot delete tours');
        }

        $page_url = menu_page_url('kanawaicontest', FALSE);
        wp_redirect($page_url);
        exit;
    }

//    public function process_form_submit()
//    {
//        if ( ! isset($_POST['submit_tour'])) {
//            return;
//        }
//
//        if ( ! wp_verify_nonce($_POST['_wpnonce'], 'kanawaicontest_new_tour')) {
//            die('Go get a life script kiddies');
//        }
//
//        // Get unslashed post
//        $post = Kanawaicontest::$unslashed_post;
//
//        $title = isset($post['title']) ? sanitize_text_field($post['title']) : '';
////        $start_date = isset($post['start_date']) ? sanitize_text_field($post['start_date']) : '';
////        $end_date = isset($post['end_date']) ? sanitize_text_field($post['end_date']) : '';
//        $id = isset($post['id']) ? absint($post['id']) : null;
//
////        if (is_null($id)) {
//            $result = $this->insert_tour(array(
//                'title' => $title,
////                'start_date' => $start_date,
////                'end_date' => $end_date,
//            ));
//            if ($result !== false) {
//                Kanawaicontest_Util_Util::push_admin_notice('success', 'Tour created');
//            }
////        } else {
////            $result = $this->update_tour($id, array(
////                'title' => $title,
////                'start_date' => $start_date,
////                'end_date' => $end_date,
////            ));
////            if ($result !== false) {
////                Kanawaicontest_Util_Util::push_admin_notice('success', 'Tour updated');
////            }
////        }
//        if ( ! $result) {
//            Kanawaicontest_Util_Util::push_admin_notice('error', 'Cannot add or update tour');
//        }
//
//        // Redirect
//        $page_url = menu_page_url('kanawaicontest', FALSE);
//        wp_redirect($page_url);
//        exit;
//    }

    public function insert_tour($args)
    {
        global $wpdb;

        if ($wpdb->insert('kanawaicontest_tours', $args)) {

            return $wpdb->insert_id;
        }
        return false;
    }

//    public function update_tour($id, $args)
//    {
//        global $wpdb;
//
//        $result = $wpdb->update('kanawaicontest_tours', $args, array('id' => $id));
//        if ($result !== FALSE) {
//            return $id;
//        }
//
//        return false;
//    }
}