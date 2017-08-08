<?php
// Exit if accessed directly
if( ! defined('ABSPATH')) {
    exit;
}

if( ! class_exists('WP_List_Table')) {
    require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}

class BM_Client_List extends WP_List_Table
{
    /* ---------------------------------- Methods for admin part of site ----------------- */

    /**
     *  Returns part of SQL query for searching by substring
     *
     * @param string $search_string
     * @return string  SQL 'where' clause
     */
    public static function create_search_sql_where_clause($search_string)
    {
        return ' (name LIKE "%' . esc_sql($search_string) . '%"
            OR last_name LIKE "%' . esc_sql($search_string) . '%"
            OR phone LIKE "%' . esc_sql($search_string) . '%"
            OR email LIKE "%' . esc_sql($search_string) . '%")';
    }

    /**
     * Constructor.
     *
     * @param void
     */
    public function __construct()
    {
        parent::__construct(array(
            'singular' => 'Клиент',
            'plural'   => 'Клиенты',
            'ajax'     => FALSE,
        ));
    }

    /**
     * Retrieve clients from database
     *
     * @param int $id
     *
     * @return array
     */
    public static function get_client($id = 0)
    {
        global $wpdb;

        $item = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}bm_clients WHERE id = %d", $id));

        return $item;
    }

    /**
     * Retrieve clients data from the database.
     *
     * @param int $per_page
     * @param int $page_number
     *
     * @return array
     */
    public static function get_clients($per_page = 20, $page_number = 1)
    {
        global $wpdb;

        $sql = "SELECT * FROM {$wpdb->prefix}bm_clients WHERE ";

        if( ! empty($_REQUEST['s'])) {
            $sql .= self::create_search_sql_where_clause($_REQUEST['s']);
        } else {
            $sql .= ' 1 ';
        }

        if( ! empty($_REQUEST['filter'])) {
            if($_REQUEST['filter'] == 'blacklisted') {
                $sql .= " AND is_blacklisted = 1";
            } elseif($_REQUEST['filter'] == 'not_blacklisted') {
                $sql .= " AND is_blacklisted = 0";
            }
        }

        if( ! empty($_REQUEST['orderby'])) {
            $sql .= ' ORDER BY ' . esc_sql($_REQUEST['orderby']);
            $sql .= ! empty($_REQUEST['order']) ? ' ' . esc_sql($_REQUEST['order']) : ' ASC';
        } else {
            $sql .= ' ORDER BY last_name ASC';
        }

        $sql .= " LIMIT $per_page";
        $sql .= ' OFFSET ' . ($page_number - 1) * $per_page;

        $result = $wpdb->get_results($sql, 'ARRAY_A');

        return $result;
    }

    /**
     * Removes the record about ed client from database.
     *
     * @param int $id
     * @return boolean
     */
    public static function delete_client($id)
    {
        global $wpdb;

        return (boolean) $wpdb->delete(
            "{$wpdb->prefix}bm_clients",
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

        $sql = "SELECT COUNT(*) FROM {$wpdb->prefix}bm_clients WHERE ";

        if( ! empty($_REQUEST['s'])) {
            $sql .= self::create_search_sql_where_clause($_REQUEST['s']);
        } else {
            $sql .= ' 1 ';
        }

        return $wpdb->get_var($sql);
    }

    /**
     * Text displayed when there are no clients.
     *
     * @return void
     */
    public function no_items()
    {
        echo 'Нет записей о клиентах';
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
            case 'is_blacklisted':
                $value = ($item[$column_name] == 1) ? '<strong>Да</strong>' : '<strong>Нет</strong>';
                break;
            default:
                $value = isset($item[$column_name]) ? $item[$column_name] : '';
        }

        return $value;
    }

    protected function get_views()
    {
        $status_links = array(
            "all"             => '<a href="' . add_query_arg(['filter' => FALSE]) . '">Все</a>',
            "blacklisted"     => '<a href="' . add_query_arg(['filter' => 'blacklisted']) . '">В черном списке</a>',
            "not_blacklisted" => '<a href="' . add_query_arg(['filter' => 'not_blacklisted']) . '">Не в черном списке</a>',
        );

        return $status_links;
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
            'cb'             => '<input type="checkbox" />',
            'last_name'      => 'Фамилия',
            'name'           => 'Имя',
            'phone'          => 'Телефон',
            'email'          => 'E-mail',
            'is_blacklisted' => 'В черном списке',
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
            'last_name'      => array('last_name', TRUE),
            'name'           => array('name', TRUE),
            'email'          => array('email', TRUE),
            'phone'          => array('phone', TRUE),
            'is_blacklisted' => array('is_blacklisted', TRUE),
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

        $per_page = $this->get_items_per_page('clients_per_page', 5);
        $current_page = $this->get_pagenum();
        $total_items = self::record_count();

        $this->set_pagination_args([
            'total_items' => $total_items,
            'per_page'    => $per_page,
        ]);

        $this->items = self::get_clients($per_page, $current_page);
    }

    /**
     * Handles bulk action and delete.
     *
     * @return void
     */
    public function process_bulk_action()
    {
        $page_url = menu_page_url('bookingmanager_clients', FALSE);

        $result = FALSE;

        //Detect when a bulk action is being triggered...
        if('delete' === $this->current_action()) {
            // In our file that handles the request, verify the nonce.
            $nonce = esc_attr($_REQUEST['_wpnonce']);

            if( ! wp_verify_nonce($nonce, 'bm_delete_client')) {
                die('Go get a life script kiddies');
            } else {
                $result = self::delete_client($_REQUEST['id']);

                // Register message
                $message = ($result) ? ['type' => 'success', 'text' => 'OK.'] : ['type' => 'error', 'text' => 'Ошибка !'];
                BM_Util_Util::push_admin_notice($message['type'], $message['text']);

                // Redirect
                wp_redirect($page_url);
                exit();
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
                    if( ! self::delete_client($id)) {
                        $result = FALSE;
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
    }

    /**
     * Handles form data when submitted.
     *
     * @return void
     */
    public function process_form_submit()
    {
        if( ! isset($_POST['submit_client'])) {
            return;
        }

        if( ! wp_verify_nonce($_POST['_wpnonce'], 'bm_new_client')) {
            die('Go get a life script kiddies');
        }

        if( ! current_user_can('read')) {
            wp_die(__('Permission Denied!', 'bookingmanager_textdomain'));
        }

        // Get unslashed post
        $post = BM_Bookingmanager::$unslashed_post;

        $errors = array();
        $page_url = menu_page_url('bookingmanager_clients', FALSE);
        $field_id = isset($post['field_id']) ? absint($post['field_id']) : 0;

        $name = isset($post['name']) ? sanitize_text_field($post['name']) : '';
        $last_name = isset($post['last_name']) ? $post['last_name'] : '';
        $email = isset($post['email']) ? sanitize_text_field($post['email']) : '';
        $phone = isset($post['phone']) ? sanitize_text_field($post['phone']) : '';
        $is_blacklisted = (isset($post['is_blacklisted']) && $post['is_blacklisted'] == 'on') ? 1 : 0;

        $fields = [
            'last_name'      => $last_name,
            'name'           => $name,
            'email'          => $email,
            'phone'          => $phone,
            'is_blacklisted' => $is_blacklisted,
        ];

        // New or edit?
        if( ! $field_id) {
            $insert_id = $this->insert_update_client($fields);
            $field_id = $insert_id;
        } else {
            $fields['id'] = $field_id;
            $insert_id = $this->insert_update_client($fields);
        }

        // Register message
        $message = ($insert_id) ? ['type' => 'success', 'text' => 'OK.'] : ['type' => 'error', 'text' => 'Ошибка !'];
        BM_Util_Util::push_admin_notice($message['type'], $message['text']);

        // Redirect
        wp_redirect($page_url);
        exit();
    }

    /**
     * Insert a new record about ed client.
     *
     * @param array
     * @return boolean
     */
    public function insert_update_client($args = array())
    {
        global $wpdb;

        $id = FALSE;

        try {
            $client = BM_Client_Data::feed_from_post($args, '');

            $client_id = isset($args['id']) ? absint($args['id']) : FALSE;
            if($client_id) {
                $id = $client->update_by_id($client_id);
            } else {
                $id = $client->insert_or_update();
            }
        } catch(BM_Exception_NotFullDataException $e) {

            return FALSE;
        } catch(BM_Exception_InvalidEmailException $e) {

            return FALSE;
        }

        return $id;
    }


    /**
     * Get all names, emails and phones of blacklisted clients
     *
     * @return array
     */
    public static function get_blacklisted_attributes()
    {
        global $wpdb;

        $sql = "SELECT CONCAT(name, last_name) AS full_name, email, phone FROM {$wpdb->prefix}bm_clients WHERE is_blacklisted = 1";

        return [
            'names'  => $wpdb->get_col($sql, 0),
            'emails' => $wpdb->get_col($sql, 1),
            'phones' => $wpdb->get_col($sql, 0),
        ];
    }

}