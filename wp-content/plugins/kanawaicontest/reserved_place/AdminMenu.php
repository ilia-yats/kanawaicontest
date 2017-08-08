<?php
// Exit if accessed directly
if( ! defined('ABSPATH')) {
    exit;
}

/**
 * Admin Menu
 */
class BM_Reserved_Place_AdminMenu
{
    /**
     * @var BM_Reserved_Place_List
     */
    public $reserved_place_obj;

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
        $hook = add_submenu_page('bookingmanager', 'Зарезервированные места', 'Зарезервированные места', 'read', 'bookingmanager_places', array($this, 'plugin_settings_page'));

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

        $route_id = isset($_REQUEST['route_id']) ? absint($_REQUEST['route_id']) : 0;
        $trip_date = isset($_REQUEST['trip_date']) ? sanitize_text_field($_REQUEST['trip_date']) : '';
        $from_full_list = (isset($_REQUEST['from_full_list']) && ($_REQUEST['from_full_list'] == 1)) ? TRUE : FALSE;
        $route = NULL;

        if( ! empty($route_id) && ! empty($trip_date)) {
            $route = BM_Route_List::get_route($route_id);
            if(empty($route)) {
                wp_die('Несуществующий маршрут');
            }

            // Check if given route runs in given date
            $trip_date_ts = strtotime($trip_date);
            $route_runs = BM_Trips_Calendar_List::check_if_route_runs_in_date($trip_date_ts, ( array ) $route);

            if( ! $route_runs) {
                ?>
                <div class="notice notice-<?php echo 'error' ?> is-dismissible">
                    <p><?php echo 'Нет рейсов по выбранному маршруту на указанную дату. Пожалуйста выберите другую дату.'; ?></p>
                </div>
                <?php
            }

            switch($action) {
                case 'new':
                    $template = __DIR__ . '/views/reserved_place-new.php';
                    break;
                case 'edit':
                    $template = __DIR__ . '/views/reserved_place-edit.php';
                    break;
                default:
                    $template = __DIR__ . '/views/reserved_place-list.php';
                    break;
            }
        } else {
            $template = __DIR__ . '/views/reserved_place-list.php';
        }
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

        // Try to get parameters of the list of reserved places from request
        $route_id = ! empty($_REQUEST['route_id']) ? absint($_REQUEST['route_id']) : NULL;
        $trip_date = ! empty($_REQUEST['trip_date']) ? sanitize_text_field($_REQUEST['trip_date']) : NULL;
        // Create places list instance
        $this->reserved_place_obj = new BM_Reserved_Place_List($route_id, $trip_date);
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

        if($action == 'new' || $action == 'edit') {
            $this->reserved_place_obj->process_form_submit();
        } elseif($action == 'pay' || $action == 'unpay') {
            $this->reserved_place_obj->switch_payment_status();
        } elseif($action == 'download_ticket') {
            $this->reserved_place_obj->download_ticket();
        } elseif($action == 'disclaim' || $action == 'bulk-disclaim') {
            $this->reserved_place_obj->process_bulk_action($action);
        }
    }

}