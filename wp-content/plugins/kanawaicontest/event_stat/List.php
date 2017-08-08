<?php
// Exit if accessed directly
if( ! defined('ABSPATH')) {
    exit;
}

if( ! class_exists('WP_List_Table')) {
    require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}

class BM_Event_Stat_List extends WP_List_Table
{

    public static $beginning_date = NULL;
    public static $finishing_date = NULL;

    /**
     * Returns base part of SQL query to get the places data
     *
     * @return string
     */
    public static function get_select_query_base()
    {
        global $wpdb;

        return "SELECT
                COUNT(*) as count,
                initiator_id,
                initiator_name,
                type
                FROM {$wpdb->prefix}bm_events
                WHERE datetime BETWEEN '" . esc_sql(self::$beginning_date) . "' AND '" . esc_sql(self::$finishing_date) . "'
                GROUP BY initiator_id, type ";
    }

    public static $event_types_labels = [
        BM_Event_Data::RESERVED       => 'Зарезервировано новое',
        BM_Event_Data::PAID           => 'Совершена оплата',
        BM_Event_Data::PAYMENT_RETURN => 'Оплата возвращена',
        BM_Event_Data::DISCLAIMED     => 'Резерв отменен',
        BM_Event_Data::CHANGED        => 'Резерв изменен',
    ];

    /**
     * Constructor.
     *
     * @param void
     */
    public function __construct()
    {
        parent::__construct(array(
            'singular' => 'Событие',
            'plural'   => 'События',
            'ajax'     => FALSE,
        ));

        self::$beginning_date = ( ! empty($_REQUEST['from_date']) && strtotime($_REQUEST['from_date']))
            ? $_REQUEST['from_date']
            : date('Y-m-d');
        self::$finishing_date = ( ! empty($_REQUEST['to_date']) && strtotime($_REQUEST['to_date']))
            ? $_REQUEST['to_date']
            : date('Y-m-d', time() + 1 * DAY_IN_SECONDS);
    }

    /**
     * Retrieve events data from the database.
     *
     * @return array
     */
    public static function get_events_stat()
    {
        global $wpdb;

        $sql = self::get_select_query_base();

        $result = $wpdb->get_results($sql, 'ARRAY_A');

        $statistics = [];

        foreach($result as $item) {
            if( ! isset($statistics[$item['initiator_id']])) {
                $statistics[$item['initiator_id']] = [
                    'initiator_name'              => $item['initiator_name'],
                    BM_Event_Data::RESERVED       => 0,
                    BM_Event_Data::PAID           => 0,
                    BM_Event_Data::PAYMENT_RETURN => 0,
                    BM_Event_Data::DISCLAIMED     => 0,
                    BM_Event_Data::CHANGED        => 0,
                ];
            }
            $statistics[$item['initiator_id']][$item['type']] = $item['count'];
        }

        return $statistics;
    }

    /**
     * Text displayed when no city data is available.
     *
     * @return void
     */
    public function no_items()
    {
        echo 'Нет статистических данных';
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
        return isset($item[$column_name]) ? $item[$column_name] : '';
    }

    /**
     *  Associative array of columns.
     *
     * @return array
     */
    function get_columns()
    {
        $columns = [
            'initiator_name'              => 'Пользователь',
            BM_Event_Data::RESERVED       => 'Зарезервировано',
            BM_Event_Data::DISCLAIMED     => 'Отменено',
            BM_Event_Data::PAID           => 'Оплачено',
            BM_Event_Data::PAYMENT_RETURN => 'Возвращено оплат',
            BM_Event_Data::CHANGED        => 'Изменено',
        ];

        return $columns;
    }

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
                           value="<? echo esc_attr(self::$beginning_date); ?>">
                    <label for="to_date">до </label>
                    <input type="text" id="to_date" name="to_date" class="datepicker"
                           value="<? echo esc_attr(self::$finishing_date); ?>">
                    <?php submit_button('Показать', 'primary', 'submit_dates', FALSE); ?>
                </div>
                <br/>
            </form>
            <?php
        }
    }

    /**
     * Columns to make sortable.
     *
     * @return array
     */
    public function get_sortable_columns()
    {
        $sortable_columns = array(
            'initiator_name' => array('type', TRUE),
        );

        return $sortable_columns;
    }


    /**
     * Handles data query and filter, sorting, and pagination.
     *
     * @return void
     */
    public function prepare_items()
    {
        $this->_column_headers = $this->get_column_info();

        $this->items = self::get_events_stat();
    }
}