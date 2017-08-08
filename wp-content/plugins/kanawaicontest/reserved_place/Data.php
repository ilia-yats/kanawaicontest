<?php

/**
 * Class BM_Places_Reserve_Data
 *
 * Represents data set of certain places reservation
 *
 */
class BM_Reserved_Place_Data
{
    public $id;
    public $client;
    public $there_route;
    public $return_route;
    public $booking_datetime;
    public $payment_type = '';
    public $with_return = FALSE;
    public $client_ip;
    public $reserved_by_user_id;

    // Data about trip in 'there' direction
    public $there_data = [
        'route_id'      => NULL,
        'trip_date'     => NULL,
        'places'        => NULL,
        'places_string' => '',
        'ticket_code'   => '',
        'ticket_price'  => 0,
        'place_price'  => 0,
        'arrive_date'  => '',
    ];

    // Data about trip in 'return' direction
    public $return_data = [
        'route_id'      => NULL,
        'trip_date'     => NULL,
        'places'        => NULL,
        'places_string' => '',
        'ticket_code'   => '',
        'ticket_price'  => 0,
        'place_price'  => 0,
        'arrive_date'  => '',
    ];

    /**
     * Creates new instance and feeds it's properties with appropriate values from given array.
     *  Also sets up the related instances of BM_Client_Data and BM_Route_Data
     *
     * @param $data
     * @param bool $with_return
     * @param string $client_props_prefix prefix for names of client properties
     * @param string $there_props_prefix prefix for names of 'there' properties
     * @param string $return_props_prefix prefix for names of 'return' properties
     * @return BM_Reserved_Place_Data
     * @throws BM_Exception_NotFullDataException
     * @throws Exception
     */
    public static function feed(
        $data,
        $with_return = FALSE,
        $client_props_prefix = 'passenger_',
        $there_props_prefix = 'there_',
        $return_props_prefix = 'return_'
    )
    {
        $reserve = new self();

        // Set the flag which shows if places reservation contains the 'return' ticket
        $reserve->with_return = $with_return;

        // Create/update client with given data and get it's instance
        $reserve->client = BM_Client_Data::get_existing_client($data, $client_props_prefix);

        // Populate data about the trip (place reservation)
        foreach($reserve->there_data as $prop_name => $default_value) {
            $key = $there_props_prefix . $prop_name;
            if(isset($data[$key])) {

                $reserve->there_data[$prop_name] = $data[$key];
            }

            // Check necessary fields
            if($reserve->there_data[$prop_name] === NULL) {

                throw new BM_Exception_NotFullDataException("Invalid place reserving data: $prop_name is empty");
            }
        }
        // Concat places numbers to show in ticket
        $reserve->there_data['places_string'] = implode(', ', $reserve->there_data['places']);

        // Create unique ticket code if it was not set early
        if(empty($reserve->there_data['ticket_code'])) {
            $reserve->there_data['ticket_code'] = BM_Util_Util::create_ticket_code(
                $reserve->there_data['places_string'],
                $reserve->there_data['route_id']
            );
        }

        // Try to find the data about there route in database
        $reserve->there_route = BM_Route_Data::feed_from_db_by_id($reserve->there_data['route_id']);

        // Count the price of the there ticket
        $there_route_price = BM_Route_List::get_price($reserve->there_route->id);
        $there_places_count = count($reserve->there_data['places']);
        $reserve->there_data['ticket_price'] = $there_route_price * $there_places_count;
        $reserve->there_data['place_price'] = $there_route_price;
        $reserve->there_data['arrive_date'] = date(
            "d.m.Y",
            strtotime($reserve->there_data['trip_date']) + ($reserve->there_route->days_in_trip * DAY_IN_SECONDS)
        );


        // Populate data about return trip, if appropriate flag was set
        if($reserve->with_return) {
            foreach($reserve->return_data as $prop_name => $default_value) {
                $key = $return_props_prefix . $prop_name;
                if(isset($data[$key])) {
                    $reserve->return_data[$prop_name] = $data[$key];
                }

                // Check necessary fields
                if($reserve->return_data[$prop_name] === NULL) {

                    throw new BM_Exception_NotFullDataException("Invalid place reserving data: $prop_name is empty");
                }
            }

            // Concat places numbers to show in ticket
            $reserve->return_data['places_string'] = implode(', ', $reserve->return_data['places']);

            // Create unique ticket code if it was not set early
            if(empty($reserve->return_data['ticket_code'])) {
                $reserve->return_data['ticket_code'] = BM_Util_Util::create_ticket_code(
                    $reserve->return_data['places_string'],
                    $reserve->return_data['route_id']
                );
            }

            // Try to find the data about return route in database
            $reserve->return_route = BM_Route_Data::feed_from_db_by_id($reserve->return_data['route_id']);

            // Count the price of the return ticket
            $return_route_price = BM_Route_List::get_price($reserve->return_route->id);
            $return_places_count = count($reserve->return_data['places']);
            $reserve->return_data['ticket_price'] = $return_route_price * $return_places_count;
            $reserve->return_data['place_price'] = $return_route_price;
            $reserve->return_data['arrive_date'] = date(
                "d.m.Y",
                strtotime($reserve->return_data['trip_date']) + ($reserve->return_route->days_in_trip * DAY_IN_SECONDS)
            );
        }

        return $reserve;
    }

    public function set_id($id)
    {
        $this->id = $id;
    }

}