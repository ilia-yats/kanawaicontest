<?php
// Exit if accessed directly
if( ! defined('ABSPATH')) {
    exit;
}

/**
 * Admin Menu
 */
class BM_Trips_Calendar_AdminMenu
{
    /**
     * WP_List_Table object.
     *
     * @var object
     */
    public $trips_calendar_obj;

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
        $hook = add_submenu_page('bookingmanager', 'Календарь', 'Календарь', 'manage_options', 'bookingmanager', array($this, 'plugin_settings_page'));

        add_action("load-$hook", array($this, 'screen_option'));
    }

    /**
     * Plugin settings page.
     *
     * @return void
     */
    public function plugin_settings_page()
    {
        include(__DIR__ . '/views/trips-calendar.php');
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
            'option'  => 'per_page',
        );

        add_screen_option($option, $args);

        $this->trips_calendar_obj = new BM_Trips_Calendar_List;
    }

}