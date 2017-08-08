<?php
// Exit if accessed directly
if( ! defined('ABSPATH')) {
    exit;
}

/**
 * Admin Menu
 */
class BM_Event_Stat_AdminMenu
{
    /**
     * WP_List_Table object.
     *
     * @var object
     */
    public $event_stat_obj;

    /**
     * Constructor.
     *
     * @param void
     */
    public function __construct()
    {
        add_filter('set-screen-option', array(__CLASS__, 'set_screen'), 10, 3);
    }

    /**
     * Setting screen option.
     *
     * @param  string $status , $option, $value
     *
     * @return string
     */
    public static function set_screen($status, $option, $value)
    {
        return $value;
    }

    /**
     * Registering plugin menu.
     *
     * @return void
     */
    public function plugin_menu()
    {
        $hook = add_submenu_page('bookingmanager', 'Статистика работы кассиров', 'Статистика работы кассиров', 'manage_options', 'bookingmanager_events_stat', array($this, 'plugin_settings_page'));

        add_action("load-$hook", array($this, 'screen_option'));
//        add_action("load-$hook", array($this, 'form_handler'));
    }

    /**
     * Plugin settings page.
     *
     * @return void
     */
    public function plugin_settings_page()
    {
        $action = isset($_REQUEST['action']) ? $_REQUEST['action'] : 'list';
        $id = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;

        $template = __DIR__ . '/views/event_stat-list.php';

        if(file_exists($template)) {
            include($template);
        }
    }

    /**
     * Screen options.
     *
     * @return void
     */
    public function screen_option()
    {
        $option = 'per_page';
        $args = array(
            'label'   => 'Показывать на странице',
            'default' => 20,
            'option'  => 'rows_per_page',
        );

        add_screen_option($option, $args);

        include_once __DIR__ . '/List.php';
        $this->event_stat_obj = new BM_Event_Stat_List();
    }
}