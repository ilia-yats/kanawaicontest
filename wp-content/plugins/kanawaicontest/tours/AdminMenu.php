<?php
// Exit if accessed directly
if ( ! defined('ABSPATH')) {
    exit;
}

class KC_Tours_AdminMenu
{
    /**
     * @var KC_Tours_List
     */
    public $tours_list;

    public function __construct()
    {
        add_filter('set-screen-option', array(__CLASS__, 'set_screen'), 10, 3);
    }

    public function init()
    {
        if (empty($this->tours_list)) {
            $this->tours_list = new KC_Tours_List();
        }

        return $this;
    }

    public static function set_screen($status, $option, $value)
    {
        return $value;
    }

    public function plugin_menu()
    {
        $hook = add_submenu_page('kanawaicontest', 'Archive', 'Archive', 'read', 'kanawaicontest', array($this, 'plugin_settings_page'));

        add_action("load-$hook", array($this, 'screen_option'));
        add_action("load-$hook", array($this, 'form_handler'));
    }

    public function plugin_settings_page()
    {
        $action = isset($_REQUEST['action']) ? $_REQUEST['action'] : 'current';
        $id = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;
        $template = '';
        switch ($action) {
            case 'archive':
                $item = $this->tours_list->get_current_tour_id();
                $template = __DIR__ . '/views/tours-edit.php';
                break;
            case 'list':
            default:
                $template = __DIR__ . '/views/tours-list.php';
                break;
        }
        if (file_exists($template)) {
            include($template);
        }
    }

    public function screen_option()
    {
        $option = 'per_page';
        $args = array(
            'label' => 'Show on page',
            'default' => 20,
            'option' => 'tours_per_page',
        );

        add_screen_option($option, $args);

        $this->init();
    }

    public function form_handler()
    {
//        if (isset($_REQUEST['action']) && ($_REQUEST['action'] == 'new' || $_REQUEST['action'] == 'edit')) {
//            $this->tours_list->process_form_submit();
//        }

        if ((isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete')
            || (isset($_POST['action']) && $_POST['action'] == 'bulk-delete')
            || (isset($_POST['action2']) && $_POST['action2'] == 'bulk-delete')
        ) {

            $this->tours_list->process_bulk_action();
        }

//        if (isset($_REQUEST['id']) && isset($_REQUEST['action'])) {
//            if($_REQUEST['action'] == 'activate') {
//                $this->tours_list->activate_tour(absint($_REQUEST['id']));
//            } elseif($_REQUEST['action'] == 'archive') {
//                $this->tours_list->archive_tour(absint($_REQUEST['id']));
//            }
//        }

    }
}