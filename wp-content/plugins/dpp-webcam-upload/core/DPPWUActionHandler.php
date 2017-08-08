<?php

/**
 * Description of DPPWUActionHandler
 *
 * @author DPP
 */
class DPPWUActionHandler
{
    private $models = null;
    
    public function __construct(stdClass $models)
    {
        $this->models = $models;
    }
}
