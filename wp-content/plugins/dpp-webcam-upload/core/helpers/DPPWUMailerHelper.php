<?php

/**
 * Description of DPPWUMailerHelper
 *
 * @author DPP
 */
class DPPWUMailerHelper
{
    public $from;
    public $to;
    public $subject;
    public $body;
    
    /**
     * Creates email headers
     * @return array
     */
    protected function get_headers()
    {
        $headers[] = 'From: '.$this->from;
        $headers[] = 'Content-Type: text/plain; charset=UTF-8';
        return $headers;
    }
    
    /**
     * This method is a wrapper for wordpress wp_mail method.
     * 
     * @return boolean true on success, false on failure
     */
    protected function send()
    {
        add_filter( 'wp_mail_content_type', 'dppwu_set_html_content_type' );
        $res = wp_mail($this->to, $this->subject, $this->body, $this->get_headers());
        remove_filter( 'wp_mail_content_type', 'dppwu_set_html_content_type' );
        return $res;
    }
    
    /*
     * Example email helper method
     */
    public function send_dummy_message($order, $from, $renderer)
    {
        $this->from = $from; 
        $this->to = $order->email;
        $this->subject = __('Thank you for your booking!', DPPWU_TEXTDOMAIN);
        $this->body = $renderer->render(DPPWU_PLUGIN_DIR.'/view/templates/email/dummy.php', array('order' => $order));
        $this->send();
    }
}
