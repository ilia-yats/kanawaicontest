<?php
// Exit if accessed directly
if( ! defined('ABSPATH')) {
    exit;
}

/**
 * Admin Menu
 */
class KC_Images_AdminMenu
{
    /**
     * WP_List_Table object.
     *
     * @var object
     */
    public $images_list;

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
        $hook = add_submenu_page('kanawaicontest', 'Images', 'Images', 'read', 'kanawaicontest_images', array($this, 'plugin_settings_page'));

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
        $id = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;

        $template = '';

        switch($action) {
//            case 'view':
//            case 'edit':
//                $template = __DIR__ . '/views/images-edit.php';
//                break;

            case 'new':
                $template = __DIR__ . '/views/images-new.php';
                break;

            default:
                $template = __DIR__ . '/views/images-list.php';
                break;
        }

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
            'label'   => 'Show on page',
            'default' => 20,
            'option'  => 'images_per_page',
        );

        add_screen_option($option, $args);

        $this->images_list = new KC_Images_List();
    }

    /**
     * Handle form submit data & delete record.
     *
     * @return void
     */
    public function form_handler()
    {
        if(isset($_REQUEST['action']) && ($_REQUEST['action'] == 'new' || $_REQUEST['action'] == 'edit')) {
            $this->images_list->process_form_submit();
        }

        if((isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete')
            || (isset($_POST['action']) && $_POST['action'] == 'bulk-delete')
            || (isset($_POST['action2']) && $_POST['action2'] == 'bulk-delete')
        ) {
            /** Process bulk action */
            $this->images_list->process_bulk_action();
        }
    }
}