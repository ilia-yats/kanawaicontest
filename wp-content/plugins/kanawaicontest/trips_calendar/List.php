<?php
// Exit if accessed directly
if( ! defined('ABSPATH')) {
    exit;
}

if( ! class_exists('WP_List_Table')) {
    require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}

class BM_Trips_Calendar_List extends WP_List_Table
{
    public $routes = [];
    public $beginning_date = NULL;
    public $finishing_date = NULL;

    /**
     * Constructor.
     *
     * @param void
     */
    public function __construct()
    {
        global $wpdb;

        parent::__construct(array(
            'singular' => 'Запланированные поездки',
            'plural'   => 'Запланированные поездки',
            'ajax'     => FALSE,
        ));

        $this->routes = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}bm_routes", ARRAY_A);
        $this->beginning_date = ( ! empty($_REQUEST['from_date']) && strtotime($_REQUEST['from_date']))
            ? $_REQUEST['from_date']
            : date('Y-m-d');
        $this->finishing_date = ( ! empty($_REQUEST['to_date']) && strtotime($_REQUEST['to_date']))
            ? $_REQUEST['to_date']
            : date('Y-m-d', time() + 15 * DAY_IN_SECONDS);
    }


    /* ---------------------------------- Methods for admin part of site ----------------- */


    /**
     * Displays inputs for dates in table header
     *
     * @param string $which
     */
    protected function display_tablenav($which)
    {
        parent::display_tablenav($which);
        if($which === 'top') {
            ?>
            <form>
                <div>
                    <label for="from_date">В диапазоне дат от </label>
                    <input type="text" id="from_date" class="datepicker" name="from_date"
                           value="<? echo date("d.m.Y", strtotime($this->beginning_date)); ?>">
                    <label for="to_date">до </label>
                    <input type="text" id="to_date" name="to_date" class="datepicker"
                           value="<? echo date("d.m.Y", strtotime($this->finishing_date)); ?>">
                    <?php submit_button('Показать', 'secondary', 'submit_dates', FALSE); ?>
                </div>
                <br/>
            </form>
            <?php
        }
    }

    /**
     * Determines if given route runs in given date
     *
     * @param $date_ts int  timestamp of date
     * @param $route_data array  array with information about route
     *
     * @return boolean
     */
    public static function check_if_route_runs_in_date($date_ts, $route_data)
    {
        $week_day_number = date('N', $date_ts); // Week number of day

        // Get integer number of current day in month (without leading zero, e.g. 1, 2, ...31)
        //  and determine the parity of current date
        $date_parity = (intval(date('j', $date_ts)) % 2 > 0) ? FALSE : TRUE;

        // Determine if the route runs on parity or non-parity dates
        $route_runs_on_parity_dates = (isset($route_data['on_even']) && $route_data['on_even'] != 0)
            ? TRUE
            : FALSE;
        $route_runs_on_non_parity_dates = (isset($route_data['on_uneven']) && $route_data['on_uneven'] != 0)
            ? TRUE
            : FALSE;

        // Determine if the route runs in current week day
        $route_runs_in_week_day = (isset($route_data['day_' . $week_day_number])
            && ($route_data['day_' . $week_day_number] != 0))
            ? TRUE
            : FALSE;

        // Add trip on calendar in these cases:
        //  if route runs in current week day,
        //  or if it runs on non-parity dates and current date is non-parity
        //  or if it runs on parity dates and current date is parity
        if($route_runs_in_week_day
            || ($date_parity && $route_runs_on_parity_dates)
            || ( ! $date_parity && $route_runs_on_non_parity_dates)
        ) {

            return TRUE;
        } else {

            return FALSE;
        }
    }

    /**
     * Retrieve reserved_places data from the database.
     *
     * @return mixed
     */
    public function admin_get_places_data()
    {
        global $wpdb;

        $beginning_date_ts = strtotime($this->beginning_date);
        $finishing_date_ts = strtotime($this->finishing_date);

        $count_paid_places_sql = $wpdb->prepare(
            "SELECT
              trip_date,
              route_id,
              COUNT(*) AS count_of_paid_places
            FROM {$wpdb->prefix}bm_reserved_places
            WHERE is_paid = 1 AND (trip_date BETWEEN '%s' AND '%s') GROUP BY route_id, trip_date",
            array($this->beginning_date, $this->finishing_date)
        );

        $count_reserved_places_sql = $wpdb->prepare(
            "SELECT
              trip_date,
              route_id,
              COUNT(*) AS count_of_reserved_places
            FROM {$wpdb->prefix}bm_reserved_places
            WHERE (trip_date BETWEEN '%s' AND '%s') GROUP BY route_id, trip_date",
            array($this->beginning_date, $this->finishing_date)
        );

        $paid_places_data = $wpdb->get_results($count_paid_places_sql, 'ARRAY_A');
        $reserved_places_data = $wpdb->get_results($count_reserved_places_sql, 'ARRAY_A');

        $result = [];

        $date_ts = $beginning_date_ts;
        while($date_ts <= $finishing_date_ts) {
            $date = date('Y-m-d', $date_ts);
            $result[$date] = [
                'date' => $date,
            ];

            foreach($this->routes as $number => $route_data) {
                // Check if route runs in this date
                if(self::check_if_route_runs_in_date($date_ts, $route_data)) {
                    $row_data = [
                        'route_runs' => TRUE, // Route runs in this date
                        'route_id'   => $route_data['id'],
                        'date'       => $date,
                        'reserved'   => 0,
                        // Get the total count of places in bus on this route
                        'free'       => isset(BM_Route_List::$places_count_in_bus_types[$route_data['bus_type']])
                            ? BM_Route_List::$places_count_in_bus_types[$route_data['bus_type']]
                            : BM_Route_List::$places_count_in_bus_types[1], // Set bus type to 1 as default
                        'paid'       => 0,
                    ];
                    // If route doesn't run, set appropriate flag to false
                } else {
                    $row_data = [
                        'route_runs' => FALSE,
                    ];
                }

                $result[$date]['route_' . $route_data['id']] = $row_data;
            }

            $date_ts += DAY_IN_SECONDS;
        }

        foreach($paid_places_data as $one_date_paid_places_data) {
            $result[$one_date_paid_places_data['trip_date']]['route_' . $one_date_paid_places_data['route_id']]['paid'] = $one_date_paid_places_data['count_of_paid_places'];
        }

        foreach($reserved_places_data as $one_trip_reserved_places_data) {
            $result[$one_trip_reserved_places_data['trip_date']]['route_' . $one_trip_reserved_places_data['route_id']]['reserved'] = $one_trip_reserved_places_data['count_of_reserved_places'];
            $result[$one_trip_reserved_places_data['trip_date']]['route_' . $one_trip_reserved_places_data['route_id']]['free'] -= $one_trip_reserved_places_data['count_of_reserved_places'];
        }

        return $result;
    }

    /**
     * Text displayed when no reserved_place data is available.
     *
     * @return void
     */
    public function no_items()
    {
        echo 'Нет запланированных поездок';
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
        $content = '';

        if($column_name == 'date') {
            $content = date("d.m.Y", strtotime($item[$column_name]));
        } else {
            $trip_data = $item[$column_name];

            $content = '<div>';
            $reserved = isset($trip_data['reserved']) ? absint($trip_data['reserved']) : 0;
            $paid = isset($trip_data['paid']) ? absint($trip_data['paid']) : 0;
            $free = isset($trip_data['free']) ? absint($trip_data['free']) : 0;


            // If route runs in current date, show information about trip
            if(isset($trip_data['route_runs']) && $trip_data['route_runs']) {
                if($reserved > 0) {
                    $content .= '<p style="color:orangered">Зарезервировано: ' . $trip_data['reserved'] . '</p>';
                }
                if($paid > 0) {
                    $content .= '<p style="color:red">Оплачено: ' . $trip_data['paid'] . '</p>';
                }
                $content .= '<p style="color:green">Свободно: ' . $free . '</p>';
                $places_page_url = menu_page_url('bookingmanager_places', FALSE);
                $args = [];
                $args['route_id'] = isset($trip_data['route_id']) ? $trip_data['route_id'] : '';
                $args['trip_date'] = isset($trip_data['date']) ? $trip_data['date'] : '';
                $trip_places_page_url = add_query_arg($args, $places_page_url);
                $content .= '<p><a class="button" href="' . $trip_places_page_url . '">Просмотр</a></p>';
                if($free > 0) {
                    $args['action'] = 'new';
                    $add_new_page_url = add_query_arg($args, $places_page_url);
                    $content .= '<p><a class="button" href="' . $add_new_page_url . '">Зарезервировать</a></p>';
                }
                // Else, if current route doesn't run on this date, show empty table cell
            } else {
                $content = '-';
            }
            $content .= '</div>';
        }

        return $content;
    }

    /**
     *  Associative array of columns.
     *
     * @return array
     */
    function get_columns()
    {
        $columns = [
            'date' => 'Дата',
        ];
        foreach($this->routes as $route_data) {
            $columns['route_' . $route_data['id']] = $route_data['name'];
        }

        return $columns;
    }

    /**
     * Columns to make sortable.
     *
     * @return array
     */
    public function get_sortable_columns()
    {
        return array();
    }

    /**
     * Handles data query and filter, sorting, and pagination.
     *
     * @return void
     */
    public function prepare_items()
    {
        $this->_column_headers = $this->get_column_info();

//        $per_page = $this->get_items_per_page('trips_per_page', 5);
//        $current_page = $this->get_pagenum();

        $this->set_pagination_args([]);
        $this->items = $this->admin_get_places_data();
    }



    /* ---------------------------------- Methods for public part of site ----------------- */


    /**
     * Returns data about reserved and free places for rendering in public part of site
     *
     * @param $beginning_date
     * @param $finishing_date
     * @param $route_id
     * @param $route_bus_type
     * @return array
     */
    public static function public_get_places_data($beginning_date, $finishing_date, $route_id, $route_bus_type)
    {
        global $wpdb;

        $beginning_date_ts = strtotime($beginning_date);
        $finishing_date_ts = strtotime($finishing_date);

        $reserved_places_table_name = "{$wpdb->prefix}bm_reserved_places";
        $routes_table_name = "{$wpdb->prefix}bm_routes";

        $route_id = absint($route_id);

        $route_data = $wpdb->get_row(
            "SELECT * FROM $routes_table_name WHERE id = $route_id",
            'ARRAY_A'
        );

        // Determine the count of places in bus on current route, depending on the route bus type
        $route_data['places_count'] = isset(BM_Route_List::$places_count_in_bus_types[$route_bus_type])
            ? BM_Route_List::$places_count_in_bus_types[$route_bus_type]
            : BM_Route_List::$places_count_in_bus_types[1]; // Set bus type to 1 as default

        $get_route_reserved_places_sql = $wpdb->prepare(
            "SELECT
              id,
              place_number,
              trip_date
            FROM $reserved_places_table_name
            WHERE route_id = %d
            AND (trip_date BETWEEN '%s' AND '%s')",
            array($route_id, $beginning_date, $finishing_date)
        );

        $reserved_places_data = $wpdb->get_results($get_route_reserved_places_sql, 'ARRAY_A');
        $places_result = [];

        // Fill array of places statuses for each date in given diapason
        $date_ts = $beginning_date_ts;
        while($date_ts <= $finishing_date_ts) {
            $current_date = date('Y-m-d', $date_ts); // Date in yyyy-mm-dd format

            // Check if given route runs in this date, and if yes, fill the information about places
            if(self::check_if_route_runs_in_date($date_ts, $route_data)) {
                $places_result[$current_date] = [
                    'total_free'      => $route_data['places_count'], // Basically, all places are free
                    'places_statuses' => [],
                ];

                // Insert each place number and status (all places are free by default)
                for($place_number = 1; $place_number <= $route_data['places_count']; $place_number++) {
                    $places_result[$current_date]['places_statuses'][$place_number] = 0;
                }
            }

            // Go to next day by increasing timestamp
            $date_ts += DAY_IN_SECONDS;
        }

        // Then, overwrite all reserved places' statuses with ids of these places;
        // And decrease amount of free places
        foreach($reserved_places_data as $place) {
            if(isset($places_result[$place['trip_date']])) {
                $places_result[$place['trip_date']]['places_statuses'][$place['place_number']] = $place['id'];
                $places_result[$place['trip_date']]['total_free']--;
            }
        }

        $response_data = [];

        foreach($places_result as $date => $places) {
            $response_data[] = [
                'title'  => "Цена: " . sanitize_text_field($route_data['price']) . " грн"
                    . "\nСвободно: " . $places['total_free'],
                'start'  => $date,
                'end'    => $date,
                'places' => $places['places_statuses'],
                'route'  => $route_data,
            ];
        }

        return $response_data;
    }

}