<?php

/**
 * Description of DPPWUMenu
 */
class DPPWUMenu
{
    private $view = null;
    private $models = null;
    
    /**
     * This method hooks up plugin settings page in wordpress dashboard settigns menu.
     */
    
    public function __construct(DPPWUView $view, stdClass $models)
    {
        $this->view = $view;
        $this->models = $models;
    }

    public function init()
    {
        //$this->loadAssets();
        /*add_menu_page(
                __( 'dpp_webcam_upload', DPPWU_TEXTDOMAIN ),
                __( 'dpp_webcam_upload', DPPWU_TEXTDOMAIN ),
                'manage_options',
                'dppwu-main',
                array($this, 'main')
        );
        
        add_submenu_page( 
                'dppwu-main', 
                __('Settings', DPPWU_TEXTDOMAIN),  
                __('Settings', DPPWU_TEXTDOMAIN),    
                'manage_options', 
                'dppwu-settings',                
                array($this, 'settings'));
        */
    }
    
    /*public function main()
    {
        echo $this->view->render(DPPWU_PLUGIN_DIR . '/view/templates/admin/main.php', [
            'data' => ['Dummy', 'data']
        ]);
    }
    
    public function settings()
    {
        echo $this->view->render(DPPWU_PLUGIN_DIR . '/view/templates/admin/settings.php', [
            'data' => ['Dummy', 'data']
        ]);
    }*/
    
   /* private function loadAssets()
    {
        wp_enqueue_style ( 'DPPWUAdmin' );
        
        wp_enqueue_script( 'DPPWUtr_i18n' );
        wp_enqueue_script( 'DPPWUMainJs' );
    }*/
}