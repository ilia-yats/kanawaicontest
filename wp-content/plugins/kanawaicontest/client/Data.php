<?php

/**
 * Class BM_Client_Data
 *
 * Represents data set of client
 *
 */
class BM_Client_Data
{
    public $id;

    public $personal_data = [
        'name'           => NULL,
        'last_name'      => NULL,
        'email'          => '',
        'phone'          => NULL,
        'is_blacklisted' => 0,
    ];

    /**
     * Creates instance and feeds it's properties with appropriate keys in given array
     *
     * @param $data
     * @param string $props_names_prefix
     * @return BM_Client_Data
     * @throws BM_Exception_InvalidEmailException
     * @throws BM_Exception_NotFullDataException
     */
    public static function feed_from_post($data, $props_names_prefix = 'passenger_')
    {
        $client_data = new self();

        // Populate personal data with appropriate keys from $data
        foreach($client_data->personal_data as $prop_name => $default_value) {
            $key = $props_names_prefix . $prop_name;
            if(isset($data[$key])) {
                $client_data->personal_data[$prop_name] = $data[$key];
            }

            // Check required values
            if($client_data->personal_data[$prop_name] === NULL) {

                throw new BM_Exception_NotFullDataException("Invalid client data");
            }
        }

        // Sanitize email
        if($client_data->email !== '') {
            $client_data->email = filter_var($client_data->email, FILTER_SANITIZE_EMAIL);
            if( ! $client_data->email) {

                throw new BM_Exception_InvalidEmailException("Invalid email");
            }
        }

        return $client_data;
    }

    /**
     * Returns id of client with given parameters (creates new client if it doesn't exist)
     *
     * @param $data array
     * @param $props_names_prefix string  prefix, to be added to prop name to find it in $data array
     * @return BM_Client_Data
     * @throws BM_Exception_InvalidEmailException
     * @throws BM_Exception_NotFullDataException
     */
    public static function get_existing_client($data, $props_names_prefix = NULL)
    {
        if($props_names_prefix === NULL) {
            // With defult prefix
            $client = self::feed_from_post($data);
        } else {
            // With given prefix
            $client = self::feed_from_post($data, $props_names_prefix);
        }

        $client->set_id($client->insert_or_update());

        return $client;
    }

    /**
     * Tries to insert new client and if client already exists, updates it.
     * The uniqueness of client is determined by email
     *
     * @return false|int  id of inserted/updated client
     */
    public function insert_or_update()
    {
        global $wpdb;

        // Check if client with such email already exists, and if yes, return this client's id
        $search_sql = $wpdb->prepare("
            SELECT * FROM {$wpdb->prefix}bm_clients WHERE email = '%s' AND phone = '%s'",
            [
                $this->email,
                $this->phone,
            ]
        );
        $client = $wpdb->get_row($search_sql, ARRAY_A);

        if(isset($client['id'])) {

            // Update existing client with new values and return it's id
            $update_sql = $wpdb->prepare(
                "UPDATE {$wpdb->prefix}bm_clients SET name = '%s', last_name = '%s' WHERE id = %d",
                [
                    $this->name,
                    $this->last_name,
                    $client['id'],
                ]
            );
            $wpdb->query($update_sql);

            return $client['id'];
        }

        // Else, if client not exists yet, insert him and return new id
        $insert_sql = $wpdb->prepare(
            "INSERT INTO {$wpdb->prefix}bm_clients
            (name, last_name, email, phone, is_blacklisted)
            VALUES (%s, %s, %s, %s, %d)",
            [
                $this->name,
                $this->last_name,
                $this->email,
                $this->phone,
                $this->is_blacklisted,
            ]
        );
        if($wpdb->query($insert_sql)) {

            return $wpdb->insert_id;
        }

        return FALSE;
    }

    /**
     * Updates client row by id
     *
     * @param $id
     * @return bool
     */
    public function update_by_id($id)
    {
        global $wpdb;

        $result = $wpdb->update("{$wpdb->prefix}bm_clients", $this->personal_data, array('id' => $id));
        if($result !== FALSE) {

            return $id;
        }

        return FALSE;
    }

    /**
     * Id setter
     *
     * @param $id
     */
    public function set_id($id)
    {
        $this->id = $id;
    }


    // Magic methods just for convenience (allows access the elements in $personal_data array as standard object properties)

    public function __get($name)
    {
        return (isset($this->personal_data[$name])) ? $this->personal_data[$name] : NULL;
    }

    public function __set($name, $value)
    {
        if(array_key_exists($name, $this->personal_data)) {
            $this->personal_data[$name] = $value;

            return TRUE;
        }

        return FALSE;
    }
}
