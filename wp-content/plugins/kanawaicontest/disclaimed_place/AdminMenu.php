<?php
// Exit if accessed directly
if( ! defined('ABSPATH')) {
    exit;
}

/**
 * Admin Menu
 */
class BM_Disclaimed_Place_AdminMenu
{
    /**
     * WP_List_Table object.
     *
     * @var object
     */
    public $disclaimed_place_obj;

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
        $hook = add_submenu_page('bookingmanager', 'Отмененные резервы', 'Отмененные резервы', 'manage_options', 'bookingmanager_disclaimed_places', array($this, 'plugin_settings_page'));

        add_action("load-$hook", array($this, 'screen_option'));
        add_action("load-$hook", array($this, 'form_handler'));
    }

    /**
     * Plugin settings page.
     *
     * @return void
     */
    public function plugin_settings_page()
    {
        $template = '';
        $action = isset($_REQUEST['action']) ? $_REQUEST['action'] : 'list';
        $id = isset($_REQUEST['id']) ? absint($_REQUEST['id']) : 0;

        $template = __DIR__ . '/views/disclaimed_place-list.php';

        if(file_exists($template)) {
            include_once($template);
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
            'option'  => 'places_per_page',
        );

        add_screen_option($option, $args);

        include_once __DIR__ . '/List.php';

        $this->disclaimed_place_obj = new BM_Disclaimed_Place_List();
    }

    /**
     * Handle form submit data & delete record.
     *
     * @return void
     */
    public function form_handler()
    {
        $action = NULL;
        if(isset($_REQUEST['action'])) {
            $action = $_REQUEST['action'];
        } elseif(isset($_REQUEST['action2'])) {
            $action = $_REQUEST['action2'];
        }

        if($action == 'delete' || $action == 'bulk-delete' || $action == 'bulk-recover') {
            $this->disclaimed_place_obj->process_bulk_action();
        }
    }

}