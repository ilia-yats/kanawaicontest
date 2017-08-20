<?php
// Exit if accessed directly
if ( ! defined('ABSPATH')) {
    exit;
}

if ( ! class_exists('WP_List_Table')) {
    require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}

class KC_Posters_List extends WP_List_Table
{
    public function __construct()
    {
        parent::__construct(array(
            'singular' => 'Image',
            'plural' => 'Images',
            'ajax' => FALSE,
        ));
    }

    public function get_posters($per_page = 20, $page_number = 1)
    {
        global $wpdb;

        $sql = "SELECT kci.* FROM kanawaicontest_posters kci";

        if (!empty($_REQUEST['tour_id'])) {
            $sql .= ' WHERE tour_id = ' . absint($_REQUEST['tour_id']);
        }

        // TODO: implement sorting by 'voted'

        if ( ! empty($_REQUEST['orderby'])) {
            $sql .= ' ORDER BY ' . esc_sql($_REQUEST['orderby']);
            $sql .= ! empty($_REQUEST['order']) ? ' ' . esc_sql($_REQUEST['order']) : ' ASC';
        }
        $sql .= " LIMIT $per_page";
        $sql .= ' OFFSET ' . ($page_number - 1) * $per_page;
        $result = $wpdb->get_results($sql, 'ARRAY_A');
        foreach ($result as &$item) {
            $item['image_url'] = wp_get_attachment_image_src($item['attachment_id'])[0];
            $item['voted'] = $this->count_votes($item['id']);
        }

        return $result;
    }

    public function count_votes($poster_id)
    {
        global $wpdb;

        $sql = $wpdb->prepare("SELECT COUNT(*) FROM kanawaicontest_posters_votes WHERE poster_id = %d", $poster_id);

        return $wpdb->get_var($sql);
    }

    public function delete_image($id)
    {
        global $wpdb;

        return (bool)$wpdb->delete(
            "kanawaicontest_posters",
            array('id' => $id),
            array('%d')
        );
    }

    public function record_count()
    {
        global $wpdb;

        $sql = "SELECT COUNT(*) FROM kanawaicontest_posters";

        if (!empty($_REQUEST['tour_id'])) {
            $sql .= ' WHERE tour_id = ' . absint($_REQUEST['tour_id']);
        }

        return $wpdb->get_var($sql);
    }

    public function no_items()
    {
        echo 'No images';
    }

    public function column_default($item, $column_name)
    {
        switch ($column_name) {
            case 'id':
            case 'voted':
                return $item[$column_name];
                break;
            case 'link':
                return '<a href="'. $item[$column_name] . '">' . $item[$column_name] . '</a>';
                break;
            case 'image_url':
                return '<img src="' . $item[$column_name] . '" width=\'100\' height=\'100\' style=\'max-height: 100px; width: 100px;\'>';
                break;
        }
    }

    function column_title($item)
    {
        return '<strong>' . $item['title'] . '</strong><br><a href="' . $item['link'] . '">' . $item['link'] . '</a>';
    }

    function column_cb($item)
    {
        return sprintf(
            '<input type="checkbox" name="bulk-delete[]" value="%s" />', $item['id']
        );
    }

    function get_columns()
    {
        $columns = [
            'cb' => '<input type="checkbox" />',
            'id' => 'id',
            'title' => 'title',
            'image_url' => 'image_url',
            'voted' => 'voted',
        ];

        return $columns;
    }

    public function get_sortable_columns()
    {
        $sortable_columns = array(
            'id' => array('id', TRUE),
            'voted' => array('voted', TRUE),
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

    public function prepare_items()
    {
        $this->_column_headers = $this->get_column_info();

        $per_page = $this->get_items_per_page('posters_per_page', 5);
        $current_page = $this->get_pagenum();
        $total_items = self::record_count();

        $this->set_pagination_args([
            'total_items' => $total_items,
            'per_page' => $per_page,
        ]);

        $this->items = $this->get_posters($per_page, $current_page);
    }

    public function process_bulk_action()
    {
        $result = FALSE;

        //Detect when a bulk action is being triggered...
        if ('delete' === $this->current_action()) {
            // In our file that handles the request, verify the nonce.
            $nonce = esc_attr($_REQUEST['_wpnonce']);

            if (wp_verify_nonce($nonce, 'kanawaicontest_delete_image')) {

                $result = self::delete_image(absint($_REQUEST['id']));
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
        $page_url = isset($_REQUEST['tour_id'])
            ? add_query_arg(array('tour_id' => absint($_REQUEST['tour_id'])), menu_page_url('kanawaicontest_posters', FALSE))
            : menu_page_url('kanawaicontest_posters', FALSE);
        wp_redirect($page_url);
        exit;
    }

    // TODO: Implement archivation

//    public function process_archivation()
//    {
//        $posters = $this->get_posters(null, null)
//    }

    public function process_form_submit()
    {
        if ( ! isset($_POST['submit_image'])) {
            return;
        }

        if ( ! wp_verify_nonce($_POST['_wpnonce'], 'kanawaicontest_new_image')) {
            die('Go get a life script kiddies');
        }

        // Get unslashed post
        $post = Kanawaicontest::$unslashed_post;
        $title = isset($post['title']) ? sanitize_text_field($post['title']) : '';
        $link = isset($post['link']) ? sanitize_text_field($post['link']) : '';
        $attachment_id = isset($post['image_attachment_id']) ? absint($post['image_attachment_id']) : '';
        $tour_id = isset($post['tour_id']) ? absint($post['tour_id']) : null;
        $id = isset($post['id']) ? absint($post['id']) : null;

//        if (is_null($id)) {
            $result = $this->insert_image(array(
                'tour_id' => $tour_id,
                'title' => $title,
                'link' => $link,
                'attachment_id' => $attachment_id,
            ));
            if ($result !== false) {
                Kanawaicontest_Util_Util::push_admin_notice('success', 'Image added');
            }
//        } else {
//            $result = $this->update_image($id, array(
//                'title' => $title,
//                'link' => $link,
//                'attachment_id' => $attachment_id,
//            ));
//            if ($result !== false) {
//                Kanawaicontest_Util_Util::push_admin_notice('success', 'Image updated');
//            }
//        }
        if ( ! $result) {
            Kanawaicontest_Util_Util::push_admin_notice('error', 'Cannot add or update image');
        }

        // Redirect
        $page_url = isset($_REQUEST['tour_id'])
            ? add_query_arg(array('tour_id' => absint($_REQUEST['tour_id'])), menu_page_url('kanawaicontest_posters', FALSE))
            : menu_page_url('kanawaicontest_posters', FALSE);
        wp_redirect($page_url);
        exit;
    }

    public function insert_image($args)
    {
        global $wpdb;

        if ($wpdb->insert('kanawaicontest_posters', $args)) {

            return $wpdb->insert_id;
        }
        return false;
    }

}