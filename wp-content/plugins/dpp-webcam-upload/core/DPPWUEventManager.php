<?php

require_once 'DPPWUAbstractEventManager.php';
require_once 'helpers/DPPWUHelperManager.php';
require_once 'DPPWUMenu.php';
require_once 'DPPWUView.php';
require_once 'DPPWURouter.php';
require_once 'DPPWUModel.php';


/**
 * Description of DPPWUEventHandler
 *
 * @author DPP
 */

class DPPWUEventManager extends DPPWUAbstractEventManager
{
    protected $helpers = null;
    protected $view = null;
    protected $models = null;
    protected $router = null;
    
    public function init() 
    {
        $this->helpers = new DPPWUHelperManager();
        $this->view = new DPPWUView();
        
        $this->models           = new stdClass();

        $this->models->Dummy    = new DPPWUDummyModel();

        
        $this->router = new DPPWURouter($this->models);
        
        add_action('admin_menu', array(new DPPWUMenu($this->view, $this->models), 'init'));
        add_shortcode('webcam_upload', array($this, 'shortcode'));
       
    }
    
    public function activate()
    {
        if ( version_compare( get_bloginfo( 'version' ), '4.0', '<' ) ) {
            deactivate_plugins ( 'base-plugin.php' );
            wp_die( __( 'This plugin requires WordPress version 4.0 or higher.' ), __('dpp_webcam_upload', DPPWU_TEXTDOMAIN), array( 'back_link' => true ) );
        }  
        
    }

    public function deactivate()
    {
        
    }
    
    public function uninstall()
    {
        
    }
    
    public function shortcode()
    {
        $images = [];
        $files = scandir(CAMS_DIR);
        foreach($files as $file) {
            if($file == '.' || $file=='..') {
                continue;
            }
            //if(exif_imagetype(CAMS_DIR . $file)) {
                $fileName = pathinfo ($file, PATHINFO_FILENAME  );
                $images[$fileName] = CAMS_URL . $file;
            //}
           
        }
        //pr($images); die;
        $this->loadAssets();

        return $this->view->render(DPPWU_PLUGIN_DIR . '/view/templates/shortcode/webcam_upload.php', [
            'images' => $images,
        ]);
    }

    private function loadAssets()
    {
        wp_enqueue_style ( 'DPPWUMain' );
    }
}

$em = new DPPWUEventManager();
$em->init();
