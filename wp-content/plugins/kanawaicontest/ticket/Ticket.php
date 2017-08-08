<?php

class BM_Ticket_Ticket
{
    private $content;

    public function __construct(BM_Reserved_Place_Data $reserve)
    {
        $this->content = include(BM_ROOT . '/ticket/views/ticket_template.php');
    }

    public function get_content()
    {
        return $this->content;
    }

    public function get_mPDF()
    {
        require_once(BM_ROOT . '/vendor/MPDF57/mpdf.php');

        $mpdf = new mPDF();
        $mpdf->WriteHTML($this->content);

        return $mpdf;
    }

    public function send($email)
    {
        $mpdf = $this->get_mPDF();
        $ticket_file_name = __DIR__ . '/tickets/' . uniqid() . '.pdf';

        // Send ticket to passenger email
        $headers = [];
        $headers[] = 'From: Sv-Trans<admin@' . $_SERVER['SERVER_NAME'] . '>';
        $headers[] = 'Content-Type: text/html';
        $headers[] = 'charset=utf-8';

        $mpdf->Output($ticket_file_name, "F");

        $attachments = [$ticket_file_name];

        if(wp_mail($email, 'Билеты Sv-Trans', get_option('bookingmanager_tickets_email_text'), $headers, $attachments)) {

            unlink($ticket_file_name);
        }
    }

    public function download($title)
    {
        $mpdf = $this->get_mPDF();
        $mpdf->Output($title, "D");
    }

}