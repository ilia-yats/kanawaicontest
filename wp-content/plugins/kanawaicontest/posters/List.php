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
            'singular' => __('Image'),
            'plural' => __('Images'),
            'ajax' => FALSE,
        ));
    }

    public function get_posters($per_page = 20, $page_number = 1)
    {
        global $wpdb;

        $sql = "SELECT kci.*, kpv.voted FROM kanawaicontest_posters kci
            LEFT JOIN (SELECT poster_id, COUNT(*) AS voted FROM kanawaicontest_posters_votes GROUP BY poster_id) kpv 
            ON kci.id = kpv.poster_id";

        $sql .= ' WHERE tour_id = ' . $this->get_tour_id();

        if( ! empty($_REQUEST['s'])) {
            $like = '%' . esc_sql($_REQUEST['s']) . '%';
            $sql .= " AND (kci.title LIKE '$like' OR kci.link LIKE '$like')";
        }

        if ( ! empty($_REQUEST['orderby'])) {
            $sql .= ' ORDER BY ' . esc_sql($_REQUEST['orderby']);
            $sql .= ! empty($_REQUEST['order']) ? ' ' . esc_sql($_REQUEST['order']) : ' ASC';
        }
        $sql .= " LIMIT $per_page";
        $sql .= ' OFFSET ' . ($page_number - 1) * $per_page;
        $result = $wpdb->get_results($sql, 'ARRAY_A');
        foreach ($result as &$item) {
            $item['image_url'] = wp_get_attachment_image_src($item['attachment_id'])[0];
        }

        return $result;
    }

    public function get_tour_id()
    {
        return (! empty($_REQUEST['tour_id']))
            ? absint($_REQUEST['tour_id'])
            : KC_Tours_List::get_current_tour_id();
    }

    public function get_is_current_tour()
    {
        $tour_id = $this->get_tour_id();
        return !empty($tour_id) && ($tour_id == KC_Tours_List::get_current_tour_id());
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

        $sql .= ' WHERE tour_id = ' . $this->get_tour_id();

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
                return '<a href="' . $item[$column_name] . '">' . $item[$column_name] . '</a>';
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
            'title' => __('Title'),
            'image_url' => __('Image'),
            'voted' => __('Votes'),
        ];

        return $columns;
    }

    public function get_sortable_columns()
    {
        $sortable_columns = array(
            'voted' => array('voted', TRUE),
        );

        return $sortable_columns;
    }

    public function get_bulk_actions()
    {
        $actions = [
            'bulk-delete' => __('Delete'),
        ];

        return $actions;
    }

    public function prepare_items()
    {
        $this->_column_headers = $this->get_column_info();

        $per_page = $this->get_items_per_page('posters_per_page', 5);
        $current_page = $this->get_pagenum();
        $total_items = $this->record_count();

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

                $result = $this->delete_image(absint($_REQUEST['id']));
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
                    if ( ! $this->delete_image($id)) {
                        $result = FALSE;
                    }
                }
            }
        }

        if ($result) {
            Kanawaicontest_Util_Util::push_admin_notice('success', __('Images Deleted'));
        } else {
            Kanawaicontest_Util_Util::push_admin_notice('error', __('Cannot delete images'));
        }

        // Redirect
        $page_url = isset($_REQUEST['tour_id'])
            ? add_query_arg(array('tour_id' => absint($_REQUEST['tour_id'])), menu_page_url('kanawaicontest', FALSE))
            : menu_page_url('kanawaicontest', FALSE);
        wp_redirect($page_url);
        exit;
    }

    public function process_archivation()
    {
        global $wpdb;

        if ( ! wp_verify_nonce($_REQUEST['_wpnonce'], 'kanawaicontest_archive')) {
            die('Go get a life script kiddies');
        }

        $tour_id = KC_Tours_List::get_current_tour_id();
        $result = $wpdb->query("UPDATE kanawaicontest_tours SET end_date = '" . date('Y-m-d H:i:s')
            . "' WHERE id = " . $tour_id)
            &&
            $this->determine_winner_of_tour($tour_id);

        if ($result !== false) {
            Kanawaicontest_Util_Util::push_admin_notice('success', __('Images was sent to archive'));
        } else {
            Kanawaicontest_Util_Util::push_admin_notice('error', __('Cannot archive images'));
        }

        // Redirect
        $page_url = menu_page_url('kanawaicontest', FALSE);
        wp_redirect($page_url);
        exit;
    }

    public function determine_winner_of_tour($tour_id)
    {
        global $wpdb;

        $winner_poster_sql = "SELECT kcp.id FROM kanawaicontest_posters kcp JOIN (
                SELECT poster_id, COUNT(*) AS votes_count 
                FROM kanawaicontest_posters_votes kpv WHERE tour_id = ". absint($tour_id) . " GROUP BY poster_id
            ) kcvc ON kcp.id = kcvc.poster_id
            ORDER BY kcvc.votes_count DESC LIMIT 1";
        $winner_poster_id = $wpdb->get_var($winner_poster_sql);

        return $wpdb->query("UPDATE kanawaicontest_tours SET winner_poster_id = "
            . absint($winner_poster_id) . " WHERE id = " . absint($tour_id)
        );
    }

    public function process_export()
    {
        if ( ! wp_verify_nonce($_REQUEST['_wpnonce'], 'kanawaicontest_export')) {
            die('Go get a life script kiddies');
        }

        $tour = KC_Tours_List::get_tour($this->get_tour_id());
        $file_name = 'Posters of tour ' . (isset($tour['title']) ? $tour['title'] : '');
        header("Content-type: text/csv");
        header("Content-disposition: filename=$file_name.csv");
        $output = fopen('php://output', 'w');
        $columns = $this->get_columns();
        unset($columns['cb']);
        $columns['link'] = 'link';
        fputcsv($output, $columns);
        foreach ($this->get_posters() as $item) {
            $row = array();
            foreach ($columns as $key => $title) {
                $row[] = $item[$key];
            }
            fputcsv($output, $row);
        }
        fclose($output);
        exit;
    }

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
        $tour_id = KC_Tours_List::get_current_tour_id(); // Current tour or 0, if no tours started

        $result = $this->insert_image(array(
            'tour_id' => $tour_id,
            'title' => $title,
            'link' => $link,
            'attachment_id' => $attachment_id,
        ));
        if ($result !== false) {
            Kanawaicontest_Util_Util::push_admin_notice('success', __('Image added'));
        } else {
            Kanawaicontest_Util_Util::push_admin_notice('error', __('Cannot add image'));
        }

        // Redirect
        $page_url = isset($_REQUEST['tour_id'])
            ? add_query_arg(array('tour_id' => absint($_REQUEST['tour_id'])), menu_page_url('kanawaicontest', FALSE))
            : menu_page_url('kanawaicontest', FALSE);
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