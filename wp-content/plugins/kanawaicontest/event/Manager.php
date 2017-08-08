<?php

/**
 * Provides registering and saving for events
 *
 * Class BM_Event_Manager
 */
class BM_Event_Manager
{
    /**
     * Events pool
     *
     * @var array
     */
    private static $events = [];

    /**
     * Adds event to pool
     *
     * @param $key
     * @param BM_Event_Data $event
     */
    public static function register_event($key, BM_Event_Data $event)
    {
        self::$events[$key] = $event;
    }

    /**
     * Removes event from pool by key
     *
     * @param $key
     */
    public static function unregister_event($key)
    {
        unset(self::$events[$key]);
    }

    /**
     * Saves each event from pool to database
     */
    public static function save_all_events()
    {
        while($event = array_pop(self::$events)) {
            self::save_event($event);
        }
    }

    /**
     * Inserts event to database
     *
     * @param BM_Event_Data $event
     * @return bool|int
     */
    public static function save_event(BM_Event_Data $event)
    {
        global $wpdb;

        $result = $wpdb->insert(
            "{$wpdb->prefix}bm_events",
            [
                'type'                => $event->type,
                'datetime'            => $event->datetime,
                'affected_reserve_id' => $event->affected_reserve_id,
                'initiator_id'        => $event->initiator_id,
                'initiator_name'      => $event->initiator_name,
            ],
            ['%d', '%s', '%d', '%d', '%s']
        );

        if($result !== FALSE) {

            return $wpdb->insert_id;
        }

        return FALSE;
    }
}