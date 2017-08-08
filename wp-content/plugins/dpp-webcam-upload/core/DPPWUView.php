<?php

/**
 * This view class is intended to render modal form dialog box along with download 
 * buttons. It also contain helper methods, which allow to reduce code in view files.
 */
class DPPWUView
{
    /**
     * Variable that shows if modal form already rendered.
     * 
     * @var boolean
     */
    protected $isRendered = false;
    
    /**
     * This method will take care about rendering file only once.
     * 
     * @param resource $file
     * @param mixed $params
     * @return mixed 
     */
    public function renderOnce($file, $params)
    {
        try {
            if (!$this->isRendered) {
                $this->isRendered = true;
                return $this->render($file, $params);
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * Simple buffered renderer, it puts data to buffer, and then fush it.
     * 
     * @param resource $file
     * @param mixed $params
     * @return mixed
     */
    public function render($file, $params)
    {
        ob_start();
        ob_implicit_flush(false);
        if (file_exists($file)) {
            extract($params);
            require($file);
        }
        return ob_get_clean();
    }
    
    /**
     * Getter for $isRendered variable
     * @return boolean
     */
    public function getIsRendered()
    {
        return $this->isRendered;
    }
    
    /**
     * This is a simple helper method, that allows to limit number of words to be
     * displayed.
     * 
     * @param string $str
     * @param integer $limit
     * @param string $end_char
     * @return string
     */
    public function word_limiter($str, $limit = 100, $end_char = '&#8230;')
    {
        if (trim($str) == '') {
            return $str;
        }

        preg_match('/^\s*+(?:\S++\s*+){1,' . (int) $limit . '}/', $str, $matches);

        if (strlen($str) == strlen($matches[0])) {
            $end_char = '';
        }

        return rtrim($matches[0]) . $end_char;
    }
    
    /**
     * Helper method that accept url and return part of it after last slash
     * @param type $link
     * @return string
     */
    public function file_link_cut($link) 
    {
        if (trim($link) == '') {
            return $link;
        }
        
        $link_parts = explode('/', $link);
        return end($link_parts);
    }
    
    public function currency($int, $curr = '€')
    {
        return number_format($int, 2, ',', ' ').' '.$curr;
    }
}