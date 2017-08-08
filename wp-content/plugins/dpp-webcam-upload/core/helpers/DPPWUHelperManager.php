<?php

require_once 'DPPWUMailerHelper.php';

/**
 * Description of DPPWUHelperManager
 *
 * @author DPP
 */
class DPPWUHelperManager
{
    public $mailer = null;
    
    public function __construct()
    {
        $this->mailer = new DPPWUMailerHelper();
    }
}
