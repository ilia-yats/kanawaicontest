<?php

/**
 * Util methods
 */
class Kanawaicontest_Util_Util
{
//    /**
//     *  Creates unique code for ticket using places numbers and id of route
//     *
//     * @param $place_number
//     * @param $route_id
//     * @return string
//     */
//    public static function create_ticket_code($place_number, $route_id)
//    {
//        return strtoupper(substr(md5(time() . $place_number . $route_id), 0, 15));
//    }

    /**
     * Determines the ip current client
     *
     * @return string
     */
    public static function get_client_ip()
    {
        $client_ip = '';

        foreach (array(
                     'HTTP_CLIENT_IP',
                     'HTTP_X_FORWARDED_FOR',
                     'HTTP_X_FORWARDED',
                     'HTTP_X_CLUSTER_CLIENT_IP',
                     'HTTP_FORWARDED_FOR',
                     'HTTP_FORWARDED',
                     'REMOTE_ADDR',
                 ) as $key
        ) {
            if (array_key_exists($key, $_SERVER) === TRUE) {
                foreach (explode(',', $_SERVER[$key]) as $ip) {
                    $ip = trim($ip);
                    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== FALSE) {
                        $client_ip = $ip;
                    }
                }
            }
        }

        return $client_ip;
    }

//    /**
//     * Notifies admin about new places reserving on site
//     *
//     * @param BM_Reserved_Place_Data $reserve
//     * @return bool
//     */
//    public static function notify_admin(BM_Reserved_Place_Data $reserve)
//    {
//        $name = sanitize_text_field($reserve->client->name);
//        $last_name = sanitize_text_field($reserve->client->last_name);
//        $phone = sanitize_text_field($reserve->client->phone);
//        $email = sanitize_text_field($reserve->client->email);
//
//        $there_route = sanitize_text_field($reserve->there_data['route_name']);
//        $there_date = sanitize_text_field($reserve->there_data['trip_date']);
//        $there_places = sanitize_text_field($reserve->there_data['places_string']);
//
//        $passenger_data = "Имя: $name\nФамилия: $last_name\nТелефон: $phone\nE-mail: $email\n";
//        $routes_data = "Маршрут: $there_route\nДата: $there_date\nМеста: $there_places\n";
//
//        if( ! empty($reserve->return_data['route_id'])) {
//            $return_route = sanitize_text_field($reserve->return_data['route_name']);
//            $return_date = sanitize_text_field($reserve->return_data['trip_date']);
//            $return_places = sanitize_text_field($reserve->return_data['places_string']);
//            $routes_data .= "Обратный маршрут: $return_route\nДата: $return_date\nМеста: $return_places\n";
//        }
//
//        $bookingmanager_admin_emails = get_option('bookingmanager_admin_email');
//        $subject = 'Новые места забронированы на сайте ' . $_SERVER['SERVER_NAME'];
//        $headers = [];
//        $headers[] = 'From: <admin@' . $_SERVER['SERVER_NAME'] . '>';
//        $headers[] = 'Content-Type: text/plain';
//        $headers[] = 'charset=utf-8';
//
//        return wp_mail($bookingmanager_admin_emails, $subject, $passenger_data . $routes_data, $headers);
//    }

//    /**
//     * Notifies the client about successful places reserving
//     *
//     * @param BM_Reserved_Place_Data $reserve
//     * @return boolean
//     */
//    public static function notify_client(BM_Reserved_Place_Data $reserve)
//    {
//        $message = get_option('bookingmanager_client_email_text', '');
//
//        $replacement_values = [
//            0 => sanitize_text_field($reserve->client->name . ' ' . $reserve->client->last_name),
//            1 => get_site_url(),
//            2 => sanitize_text_field($reserve->there_data['route_name']),
//            3 => sanitize_text_field($reserve->there_data['places_string']),
//            4 => sanitize_text_field($reserve->there_data['trip_date']),
//            5 => sanitize_text_field($reserve->there_data['route_leave_time']),
//            6 => sanitize_text_field($reserve->there_data['route_leave_address']),
//            // 7 => date("d.m.Y H:i", time() + BOOKINGMANAGER_RESERVE_EXPIRE_HOURS * 3600),
//            7 => date("d.m.Y H:i:s", time()),
//            8 => get_site_url() . '/kontakty',
//        ];
//
//        // Replace template constants with real values
//        $message = str_replace(
//            ['NAME', 'SITE', 'ROUTE', 'PLACES', 'DATE', 'TIME', 'ADDRESS', 'EXPIRED', 'TICKET_WINDOWS'],
//            $replacement_values,
//            $message
//        );
//
//        if( ! empty($reserve->return_data['route_id'])) {
//            $additional_message = get_option('bookingmanager_additional_client_email_text', '');
//            $additional_replacement_values = [
//                9  => sanitize_text_field($reserve->return_data['route_name']),
//                10 => sanitize_text_field($reserve->return_data['places_string']),
//                11 => sanitize_text_field($reserve->return_data['trip_date']),
//                12 => sanitize_text_field($reserve->return_data['route_leave_time']),
//                13 => sanitize_text_field($reserve->return_data['route_leave_address']),
//            ];
//
//            // Replace template constants with real values
//            $additional_message = str_replace(
//                ['ROUTE', 'PLACES', 'DATE', 'TIME', 'ADDRESS'],
//                $additional_replacement_values,
//                $additional_message
//            );
//
//            $message .= ' ' . $additional_message;
//        }
//
//        $subject = get_option('bookingmanager_client_email_subject');
//
//        $headers = [];
//        $headers[] = 'From: <admin@' . $_SERVER['SERVER_NAME'] . '>';
//        $headers[] = 'Content-Type: text/plain';
//        $headers[] = 'charset=utf-8';
//
//        return wp_mail($reserve->client->email, $subject, $message, $headers);
//    }


    /**
     * Starts the session, after this call the PHP $_SESSION super global is available
     */
    public static function session_start()
    {
        if ( ! session_id()) session_start();
    }

    /**
     * destroys the session, this removes any data saved in the session over logout-login
     */
    public static function session_destroy()
    {
        session_destroy();
    }

    /**
     * Push flash admin notice into pool
     *
     * @param $type
     * @param $text
     */
    public static function push_admin_notice($type, $text)
    {
        if ( ! isset($_SESSION['admin_notices'])) {
            $_SESSION['admin_notices'] = [];
        }

        $_SESSION['admin_notices'][] = ['type' => $type, 'text' => $text];
    }

    /**
     * Deletes all admin notices from current session
     */
    public static function session_delete_all_admin_notices()
    {
        unset($_SESSION['admin_notices']);
    }

    /**
     * Gets a value from the session array
     * @param string $key the key in the array
     * @param mixed $default the value to use if the key is not present. empty string if not present
     * @return mixed the value found or the default if not found
     */
    public static function session_get($key, $default = NULL)
    {
        if (isset($_SESSION[$key])) {
            return $_SESSION[$key];
        } else {
            return $default;
        }
    }

    /**
     * Sets a value in the session array
     * @param string $key the key in the array
     * @param mixed $value the value to set
     */
    public static function session_set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    /**
     * Removes the value from the session array
     * @param string $key the key in the array
     */
    public static function session_unset($key)
    {
        unset($_SESSION[$key]);
    }

//    /**
//     * Increments counter of visitors, which have reserved places
//     */
//    public static function increment_reservers_count()
//    {
//        global $wpdb;
//
//        $date = date("Y-m-d");
//        $wpdb->query(
//            "INSERT INTO {$wpdb->prefix}bm_statistics(date, reserved_count)
//            VALUES('$date', 1)
//            ON DUPLICATE KEY UPDATE
//            reserved_count = reserved_count + 1 "
//        );
//    }

//    /**
//     * Checks if visitor already counted (using session), and if not, increments the counter of site unique visitors
//     */
//    public static function increment_visitors_count()
//    {
//        global $wpdb;
//
//        if( ! self::session_get('has_counted')) {
//            $date = date("Y-m-d");
//
//            $incremented = $wpdb->query(
//                "INSERT INTO {$wpdb->prefix}bm_statistics(date, visited_count)
//                VALUES('$date', 1)
//                ON DUPLICATE KEY UPDATE
//                visited_count = visited_count + 1 "
//            );
//            if($incremented > 0) {
//                self::session_set('has_counted', TRUE);
//            }
//        }
//    }

    /**
     * Shows admin notices for each message stored in session and removes shown messages
     */
    public static function show_admin_notices()
    {
        $notices = self::session_get('admin_notices');

        // Show notices
        if ( ! empty($notices) && is_array($notices)) {
            foreach ($notices as $notice) {
                if (isset($notice['text']) && isset($notice['type'])) {
                    ?>
                    <div class="notice notice-<?php echo $notice['type']; ?> is-dismissible">
                        <p><?php echo $notice['text']; ?></p>
                    </div>
                    <?php
                }
            }

            // Delete notices from session
            self::session_unset('admin_notices');
        }
    }
}
