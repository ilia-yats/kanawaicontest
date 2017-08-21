<?php
// Exit if accessed directly
if ( ! defined('ABSPATH')) {
    exit;
}

/**
 * Admin Menu
 */
class KC_Voters_AdminMenu
{
    /**
     * WP_List_Table object.
     *
     * @var object
     */
    public $voters_list;

    /**
     * Constructor.
     *
     * @param void
     */
    public function __construct()
    {
        add_filter('set-screen-option', array(__CLASS__, 'set_screen'), 10, 3);
    }

    public function init()
    {
        if (empty($this->voters_list)) {
            $this->voters_list = new KC_Voters_List();
        }

        return $this;
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
        $hook = add_submenu_page('kanawaicontest', 'Voters', 'Voters', 'read', 'kanawaicontest_voters', array($this, 'plugin_settings_page'));

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
        $action = isset($_REQUEST['action']) ? $_REQUEST['action'] : 'list';
        $template = '';

        switch ($action) {
            default:
                $template = __DIR__ . '/views/voters-list.php';
                break;
        }

        if (file_exists($template)) {
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
            'label' => 'Show on page',
            'default' => 20,
            'option' => 'voters_per_page',
        );

        add_screen_option($option, $args);

        $this->init();
    }

    /**
     * Handle form submit data & delete record.
     *
     * @return void
     */
    public function form_handler()
    {
        if ((isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete')
            || (isset($_POST['action']) && $_POST['action'] == 'bulk-delete')
            || (isset($_POST['action2']) && $_POST['action2'] == 'bulk-delete')
        ) {
            /** Process bulk action */
            $this->voters_list->process_bulk_action();
        }
    }
}