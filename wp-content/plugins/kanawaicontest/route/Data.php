<?php

/**
 * Class BM_Route_Data
 *
 * Represents data set of client
 *
 */
class BM_Route_Data
{
    public $id;

    public $data = [
        'name'           => NULL,
        'leave_time'     => NULL,
        'arrive_time'    => NULL,
        'bus_type'       => NULL,
        'arrive_address' => '',
        'leave_address'  => '',
        'price'          => 0,
        'days_in_trip'   => 0,
    ];

    /**
     * Finds route in database by id, creates new instance of route-data object
     * and feeds its properties with appropriate values
     *
     * @param $id
     * @return BM_Route_Data
     * @throws BM_Exception_NotFullDataException
     * @throws Exception
     */
    public static function feed_from_db_by_id($id)
    {
        $route_data = BM_Route_List::get_route($id);
        if( ! empty($route_data)) {
            $route = self::feed_from_db(( array ) $route_data);
            $route->id = $id;

            return $route;

        } else {
            throw new Exception('Required route doesn\'t exist');
        }
    }

    /**
     * Creates new instance of route-data object
     *  and tries to feed it's properties with given array of values
     *
     * @param $data
     * @param bool $is_return
     * @return BM_Route_Data
     * @throws BM_Exception_NotFullDataException
     */
    public static function feed_from_post($data, $is_return = FALSE)
    {
        $route_data = new self();

        foreach($route_data->data as $prop_name => $default_value) {
            $key = $is_return ? 'return_route_' . $prop_name : 'there_route_' . $prop_name;
            if(isset($data[$key])) {
                $route_data->data[$prop_name] = $data[$key];
            }
            // Check required values
            if($route_data->data[$prop_name] === NULL) {

                throw new BM_Exception_NotFullDataException("Invalid route data");
            }
        }
        $route_data->id = isset($data['id']) ? $data['id'] : NULL;

        return $route_data;
    }

    /**
     * Creates new instance of route-data object
     *  and tries to feed it's properties with given array of values received from database
     *
     * @param $data
     * @return BM_Route_Data
     * @throws BM_Exception_NotFullDataException
     */
    public static function feed_from_db($data)
    {
        $route_data = new self();

        foreach($route_data->data as $prop_name => $default_value) {
            if(isset($data[$prop_name])) {
                $route_data->data[$prop_name] = $data[$prop_name];
            }
            // Check required values
            if($route_data->data[$prop_name] === NULL) {

                throw new BM_Exception_NotFullDataException("Invalid route data");
            }
        }
        $route_data->id = isset($data['id']) ? $data['id'] : NULL;

        return $route_data;
    }


    // Magic methods just for convenience (allows access the elements in $personal_data array as standard object properties)

    public function __get($name)
    {
        return (isset($this->data[$name])) ? $this->data[$name] : NULL;
    }

    public function __set($name, $value)
    {
        if(array_key_exists($name, $this->data)) {
            $this->data[$name] = $value;

            return TRUE;
        }

        return FALSE;
    }
}