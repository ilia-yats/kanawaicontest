<?php

require_once 'DPPWUActionHandler.php';
/**
 * Description of DPPWURouter
 *
 * @author DPP
 */
class DPPWURouter
{
    /**
     * This variable keeps error message, if it was generated
     * @var string 
     */
    protected $errors;
    
    private $models = null;
    
    /**
     * This variable keeps inforamtional message, if it was generated
     * @var string
     */
    protected $info;
    
    /**
     * This array keeps allowed actions for non-admin users.
     * @var array
     */
    protected $allowed = array(
        'DPPWUActionHandler'   => array(
            ''
        ),
    );
    
    /**
     * Getter for $errors variable
     * @return stirng
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Getter for $info variable
     * @return string
     */
    public function getInfo()
    {
        return $this->info;
    }

    public function __construct(stdClass $models)
    {
        $this->models = $models;
        if (!function_exists('wp_get_current_user')) {
            require_once( ABSPATH . 'wp-includes/pluggable.php' );
        }
        $action = isset($_REQUEST['dppwu_action']) ? esc_attr($_REQUEST['dppwu_action']) :false;
        $params = isset($_REQUEST['dppwu_params']) ? $_REQUEST['dppwu_params'] : null;
        
        /**
         * Will handle actions of admin, all actions that come from plugin settings page
         */
        if(current_user_can( 'manage_options' ) && $action) {

            if(isset($params['isValid'])) { 
                unset($params['isValid']);
            };
            
            $result = $this->route($action, $params);
            if ($this->isAjax()) {
                echo json_encode($result);
                exit();
            }
            
            $this->errors = isset($result['error']) ? $result['error'] : false;
            $this->info   = isset($result['info'])  ? $result['info']  : false;
            /**
             * Will handle all actions coming from download form submit requests, and dowmload requests.
             * Specific for non-registered users. Only actions listed in $allowed variable will be executed.
             */
        } else if ($action && $params['isValid'] && wp_verify_nonce($params['isValid'], 'dppwu')) {
            
            if(isset($params['isValid'])) { 
                unset($params['isValid']);
            };
            
            $result = $this->route($action, $params);
            
            if ($this->isAjax()) {
                echo json_encode($result);
                exit();
            }
        }
    }
    
    /**
     * This method will prepare class name, register scripts, and validate
     * actions before being executed.
     * 
     * @param string $action
     * @param tring $params
     * @return mixed execution result - if allowed and action exists, false on failure
     */
    public function route($action, $params = array())
    {
        $class_name = 'DPPWUActionHandler';
        
        if(current_user_can( 'manage_options' )) {
            return $this->run($class_name, $action, $params);
        }
        
        if($this->isAllowedAction($class_name, $action)) {
            return $this->run($class_name, $action, $params);
        }
    }
    
    /**
     * Checks wether this action is allowed
     * 
     * @param string $class
     * @param string $action
     * @return boolean true if allowed and false if not
     */
    private function isAllowedAction($class, $action)
    {
        if (is_object($class)) {
            $class = $class::className();
        }
        if (array_key_exists($class, $this->allowed) && in_array($action, $this->allowed[$class])) {
            return true;
        }
        return false;
    }
    
    /**
     * Checks weather request is ajax.
     * @return boolean
     */
    private function isAjax()
    {
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) 
                && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) 
                && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'
            ) {
            return true;
        }
        return false;
    }
    
    /**
     * Will create an object and call it's action with params.
     * If class does not exist, or method does not exist, will return false
     * 
     * @param string $class_name
     * @param string $action
     * @param string | array | null $params
     * @return mixed result of action on sucess, false - if class or method does not exist. 
     */
    private function run($class_name, $action, $params)
    {
        if (class_exists($class_name) && method_exists($class_name, $action)) {
            $object = new $class_name($this->models);
            return $object->$action($params);
        } 
        return false;
    }
}
