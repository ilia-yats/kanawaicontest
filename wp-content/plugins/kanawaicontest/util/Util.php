<?php

/**
 * Util methods
 */
class Kanawaicontest_Util_Util
{
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
