<?php

/**
 * Description of DPPWUAbstractEventManager
 *
 * @author DPP
 */

abstract class DPPWUAbstractEventManager
{
    public function __construct()
    {
        register_activation_hook(DPPWU_BASE_PLUGIN_FILE, array($this, 'activate'));
        register_deactivation_hook(DPPWU_BASE_PLUGIN_FILE, array($this, 'deactivate'));
        //register_uninstall_hook(DPPWU_BASE_PLUGIN_FILE, array($this, 'uninstall'));
    }
    abstract function init();
    
    abstract function activate();

    abstract function deactivate();
    
    abstract function uninstall();
}