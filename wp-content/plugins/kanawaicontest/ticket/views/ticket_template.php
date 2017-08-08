<?php
/**
 * @var $reserve BM_Reserved_Place_Data
 */

$there_places_count = count($reserve->there_data['places']);

$ticket = 
'<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
    <style>
      .center{
        max-width: 940px;
        margin: auto;
      }
    </style>
</head>
<body>
    <div ' . ($reserve->with_return ? 'style="page-break-after:always"' : 'style="page-break-after:avoid"') . 'class="center">
        <table width="940" border="0" cellpadding="0" cellspacing="0" style="margin:0; padding:0; text-align: center; border-collapse: collapse;">
            <thead style="background-color: #f5fafd;">
                <tr>
                    <td style="padding:15px 0;border: 1px solid #d6d6d6;font: 16px Arial, sans-serif;" colspan="2">
                        <span style="color: #696667;display: block;  font: 16px Arial, sans-serif; -webkit-text-size-adjust:none; font-weight: bold;">Міністерство транспорту України</span>
                        <span style="color: #696667;display: block;  font: 16px Arial, sans-serif; -webkit-text-size-adjust:none; font-weight: bold;">Ministry of transport of Ukraine</span>
                    </td>                  
                    <td style="padding:15px 0;border: 1px solid #d6d6d6;font: 16px Arial, sans-serif;" colspan="4">
                        <span style="color: #696667;display: block;  font: 16px Arial, sans-serif; -webkit-text-size-adjust:none; font-weight: bold;">Посадковий ваучер</span>
                        <span style="color: #696667;display: block;  font: 16px Arial, sans-serif; -webkit-text-size-adjust:none; font-weight: bold;">Boarding voucher</span>
                    </td>
                    <td style="padding:15px 0;border: 1px solid #d6d6d6;font: 16px Arial, sans-serif;" colspan="2">
                        <span style="color: #696667;display: block;  font: 16px Arial, sans-serif; -webkit-text-size-adjust:none; font-weight: bold;">AP</span>
                    </td>
                </tr>
            </thead>
            <tbody>               
                <tr>

                    <td style="padding:5px 0;border: 1px solid #d6d6d6;font: 16px Arial, sans-serif;" colspan="4">
                        <span style="color: #696667;display: block;  font: 16px Arial, sans-serif; -webkit-text-size-adjust:none; font-weight: bold;">Прізвище, І., Б.</span>
                        <span style="color: #696667;display: block;  font: 16px Arial, sans-serif; -webkit-text-size-adjust:none; font-weight: bold;">' .  sanitize_text_field($reserve->client->name . ' ' . $reserve->client->last_name). '</span>
                    </td>
                    <td style="padding:5px 0;border: 1px solid #d6d6d6;font: 16px Arial, sans-serif;" colspan="2" rowspan="2">
                        <span style="color: #696667;display: block;  font: 16px Arial, sans-serif; -webkit-text-size-adjust:none; font-weight: bold;">Код ваучера</span>
                        <span style="color: #696667;display: block;  font: 16px Arial, sans-serif; -webkit-text-size-adjust:none; font-weight: bold;">Ticket code</span>
                        ' .  sanitize_text_field($reserve->there_data['ticket_code']). '
                    </td>
                     <td style="padding:5px 0;border: 1px solid #d6d6d6;font: 16px Arial, sans-serif;" colspan="2" rowspan="2">
                        <span style="color: #696667;display: block;  font: 16px Arial, sans-serif; -webkit-text-size-adjust:none; font-weight: bold;">Штамп і дата продажу</span>
                        <span style="color: #696667;display: block;  font: 16px Arial, sans-serif; -webkit-text-size-adjust:none; font-weight: bold;">Stamp of issue, date</span>
                        <img style="width: 190px; height:190px;" src="' . get_template_directory_uri() . '/img/images/stamp.png" alt="" width="190" height="190">
                    </td>
                </tr>
                <tr>
                    <td style="padding:5px 0;border: 1px solid #d6d6d6;font: 16px Arial, sans-serif;" colspan="2">
                        <span style="color: #696667;display: block;  font: 16px Arial, sans-serif; -webkit-text-size-adjust:none; font-weight: bold;">Маршрут</span>
                        <span style="color: #696667;display: block;  font: 16px Arial, sans-serif; -webkit-text-size-adjust:none; font-weight: bold;">Route</span>
                        ' .  sanitize_text_field($reserve->there_route->name). '
                    </td>
                    <td style="padding:5px 0;border: 1px solid #d6d6d6;font: 16px Arial, sans-serif;" rowspan="1">
                        <span style="color: #696667;display: block;  font: 16px Arial, sans-serif; -webkit-text-size-adjust:none; font-weight: bold;">Місця</span>
                        <span style="color: #696667;display: block;  font: 16px Arial, sans-serif; -webkit-text-size-adjust:none; font-weight: bold;">Seats</span>
                        <br>' .  sanitize_text_field($reserve->there_data['places_string']). '
                    </td>
                    <td style="padding:5px 0;border: 1px solid #d6d6d6;font: 16px Arial, sans-serif;" rowspan="1">
                        <span style="color: #696667;display: block;  font: 16px Arial, sans-serif; -webkit-text-size-adjust:none; font-weight: bold;">Осіб</span>
                        <span style="color: #696667;display: block;  font: 16px Arial, sans-serif; -webkit-text-size-adjust:none; font-weight: bold;">Persons</span>
                        <br>' .  absint($there_places_count) . '
                    </td>    
                </tr>
                <tr>
                    <td style="padding:5px 0;border: 1px solid #d6d6d6;font: 16px Arial, sans-serif;" colspan="4">
                        <span style="color: #696667;display: block;  font: 16px Arial, sans-serif; -webkit-text-size-adjust:none; font-weight: bold;">Відправлення</span>
                        <span style="color: #696667;display: block;  font: 16px Arial, sans-serif; -webkit-text-size-adjust:none; font-weight: bold;">Departure</span>
                    </td>
                    <td style="padding:5px 0;border: 1px solid #d6d6d6;font: 16px Arial, sans-serif;" colspan="4">
                        <span style="color: #696667;display: block;  font: 16px Arial, sans-serif; -webkit-text-size-adjust:none; font-weight: bold;">Прибуття</span>
                        <span style="color: #696667;display: block;  font: 16px Arial, sans-serif; -webkit-text-size-adjust:none; font-weight: bold;">Arrival</span>
                    </td>
                </tr>
                <tr>
                    <td style="padding:5px 0;border: 1px solid #d6d6d6;font: 16px Arial, sans-serif;" colspan="2">
                        <span style="color: #696667;display: block;  font: 16px Arial, sans-serif; -webkit-text-size-adjust:none; font-weight: bold;">Мiсце вiдправлення</span>
                        <span style="color: #696667;display: block;  font: 16px Arial, sans-serif; -webkit-text-size-adjust:none; font-weight: bold;">Departure place</span>
                    </td>
                    <td style="padding:5px 0;border: 1px solid #d6d6d6;font: 16px Arial, sans-serif;">
                        <span style="color: #696667;display: block;  font: 16px Arial, sans-serif; -webkit-text-size-adjust:none; font-weight: bold;">Дата</span>
                        <span style="color: #696667;display: block;  font: 16px Arial, sans-serif; -webkit-text-size-adjust:none; font-weight: bold;">Date</span>
                    </td>
                    <td style="padding:5px 0;border: 1px solid #d6d6d6;font: 16px Arial, sans-serif;">
                        <span style="color: #696667;display: block;  font: 16px Arial, sans-serif; -webkit-text-size-adjust:none; font-weight: bold;">Час</span>
                        <span style="color: #696667;display: block;  font: 16px Arial, sans-serif; -webkit-text-size-adjust:none; font-weight: bold;">Time</span>
                    </td>
                    <td style="padding:5px 0;border: 1px solid #d6d6d6;font: 16px Arial, sans-serif;" colspan="2">
                        <span style="color: #696667;display: block;  font: 16px Arial, sans-serif; -webkit-text-size-adjust:none; font-weight: bold;">Мiсце прибуття</span>
                        <span style="color: #696667;display: block;  font: 16px Arial, sans-serif; -webkit-text-size-adjust:none; font-weight: bold;">Arrival place</span>
                    </td>
                    <td style="padding:5px 0;border: 1px solid #d6d6d6;font: 16px Arial, sans-serif;">
                        <span style="color: #696667;display: block;  font: 16px Arial, sans-serif; -webkit-text-size-adjust:none; font-weight: bold;">Дата</span>
                        <span style="color: #696667;display: block;  font: 16px Arial, sans-serif; -webkit-text-size-adjust:none; font-weight: bold;">Date</span>
                    </td>
                    <td style="padding:5px 0;border: 1px solid #d6d6d6;font: 16px Arial, sans-serif;">
                        <span style="color: #696667;display: block;  font: 16px Arial, sans-serif; -webkit-text-size-adjust:none; font-weight: bold;">Час</span>
                        <span style="color: #696667;display: block;  font: 16px Arial, sans-serif; -webkit-text-size-adjust:none; font-weight: bold;">Time</span>
                    </td>
                </tr>

                <tr>
                    <td style="padding:5px 0;border: 1px solid #d6d6d6;font: 16px Arial, sans-serif;" colspan="2">
                        ' .  sanitize_text_field($reserve->there_route->leave_address). '
                    </td>
                    <td style="padding:5px 0;border: 1px solid #d6d6d6;font: 16px Arial, sans-serif;">
                        ' .  date('d.m.Y', strtotime($reserve->there_data['trip_date'])). '
                    </td>
                    <td style="padding:5px 0;border: 1px solid #d6d6d6;font: 16px Arial, sans-serif;">
                        ' .  sanitize_text_field($reserve->there_route->leave_time). '
                    </td>
                    <td style="padding:5px 0;border: 1px solid #d6d6d6;font: 16px Arial, sans-serif;" colspan="2">
                        ' .  sanitize_text_field($reserve->there_route->arrive_address). '
                    </td>
                    <td style="padding:5px 0;border: 1px solid #d6d6d6;font: 16px Arial, sans-serif;">
                        ' .  date('d.m.Y', strtotime($reserve->there_data['arrive_date'])). '
                    </td>
                    <td style="padding:5px 0;border: 1px solid #d6d6d6;font: 16px Arial, sans-serif;">
                        ' .  sanitize_text_field($reserve->there_route->arrive_time). '
                    </td>                                          
                </tr>

                <tr>
                    <td style="padding:5px 0;border: 1px solid #d6d6d6;font: 16px Arial, sans-serif;" colspan="2">
                        <span style="color: #696667;display: block;  font: 16px Arial, sans-serif; -webkit-text-size-adjust:none; font-weight: bold;">Вартість мiсця, грн.</span>
                        <span style="color: #696667;display: block;  font: 16px Arial, sans-serif; -webkit-text-size-adjust:none; font-weight: bold;">Fare, hrn(costs)</span>
                    </td>    
                    <td style="padding:5px 0;border: 1px solid #d6d6d6;font: 16px Arial, sans-serif;" colspan="2">
                        ' .  sanitize_text_field($reserve->there_data['place_price']) . '
                    </td>                 
                    <td style="padding:5px 0;border: 1px solid #d6d6d6;font: 16px Arial, sans-serif;" style="padding:5px 0;border: 1px solid #d6d6d6;" colspan="2">
                        <span style="color: #696667;display: block;  font: 16px Arial, sans-serif; -webkit-text-size-adjust:none; font-weight: bold;">(в т.ч. з ПДВ), грн.</span>
                        <span style="color: #696667;display: block;  font: 16px Arial, sans-serif; -webkit-text-size-adjust:none; font-weight: bold;">Total, hrn(costs)</span>
                    </td>
                    <td style="padding:5px 0;border: 1px solid #d6d6d6;font: 16px Arial, sans-serif;" colspan="2">
                        ' .  sanitize_text_field($reserve->there_data['ticket_price']) . '
                    </td> 
                </tr>     
                <tr>
                    <td style="padding:5px 0;border: 1px solid #d6d6d6;font: 16px Arial, sans-serif;" colspan="8">
                        <small>Ви застрахованi СК "Uniqa"</small>
                    </td>                     
                </tr>
            </tbody>
        </table>
    </div>';

if($reserve->with_return) {

    $return_places_count = count($reserve->return_data['places']);

    $ticket .= '
    <div style="page-break-after:avoid" class="center">
        <table width="940" border="0" cellpadding="0" cellspacing="0" style="margin:0; padding:0; text-align: center; border-collapse: collapse;">
            <thead style="background-color: #f5fafd;">
                <tr>
                    <td style="padding:15px 0;border: 1px solid #d6d6d6;font: 16px Arial, sans-serif;" colspan="2">
                        <span style="color: #696667;display: block;  font: 16px Arial, sans-serif; -webkit-text-size-adjust:none; font-weight: bold;">Міністерство транспорту України</span>
                        <span style="color: #696667;display: block;  font: 16px Arial, sans-serif; -webkit-text-size-adjust:none; font-weight: bold;">Ministry of transport of Ukraine</span>
                    </td>                  
                    <td style="padding:15px 0;border: 1px solid #d6d6d6;font: 16px Arial, sans-serif;" colspan="4">
                        <span style="color: #696667;display: block;  font: 16px Arial, sans-serif; -webkit-text-size-adjust:none; font-weight: bold;">Посадковий ваучер</span>
                        <span style="color: #696667;display: block;  font: 16px Arial, sans-serif; -webkit-text-size-adjust:none; font-weight: bold;">Boarding voucher</span>
                    </td>
                    <td style="padding:15px 0;border: 1px solid #d6d6d6;font: 16px Arial, sans-serif;" colspan="2">
                        <span style="color: #696667;display: block;  font: 16px Arial, sans-serif; -webkit-text-size-adjust:none; font-weight: bold;">AP</span>
                    </td>
                </tr>
            </thead>
            <tbody>               
                <tr>

                    <td style="padding:5px 0;border: 1px solid #d6d6d6;font: 16px Arial, sans-serif;" colspan="4">
                        <span style="color: #696667;display: block;  font: 16px Arial, sans-serif; -webkit-text-size-adjust:none; font-weight: bold;">Прізвище, І., Б.</span>
                        <span style="color: #696667;display: block;  font: 16px Arial, sans-serif; -webkit-text-size-adjust:none; font-weight: bold;">' .  sanitize_text_field($reserve->client->name . ' ' . $reserve->client->last_name). '</span>
                    </td>
                    <td style="padding:5px 0;border: 1px solid #d6d6d6;font: 16px Arial, sans-serif;" colspan="2" rowspan="2">
                        <span style="color: #696667;display: block;  font: 16px Arial, sans-serif; -webkit-text-size-adjust:none; font-weight: bold;">Код ваучера</span>
                        <span style="color: #696667;display: block;  font: 16px Arial, sans-serif; -webkit-text-size-adjust:none; font-weight: bold;">Ticket code</span>
                        ' .  sanitize_text_field($reserve->return_data['ticket_code']). '
                    </td>
                     <td style="padding:5px 0;border: 1px solid #d6d6d6;font: 16px Arial, sans-serif;" colspan="2" rowspan="2">
                        <span style="color: #696667;display: block;  font: 16px Arial, sans-serif; -webkit-text-size-adjust:none; font-weight: bold;">Штамп і дата продажу</span>
                        <span style="color: #696667;display: block;  font: 16px Arial, sans-serif; -webkit-text-size-adjust:none; font-weight: bold;">Stamp of issue, date</span>
                        <img style="width: 190px; height:190px;" src="' . get_template_directory_uri() . '/img/images/stamp.png" alt="" width="190" height="190">
                    </td>
                </tr>
                <tr>
                    <td style="padding:5px 0;border: 1px solid #d6d6d6;font: 16px Arial, sans-serif;" colspan="2">
                        <span style="color: #696667;display: block;  font: 16px Arial, sans-serif; -webkit-text-size-adjust:none; font-weight: bold;">Маршрут</span>
                        <span style="color: #696667;display: block;  font: 16px Arial, sans-serif; -webkit-text-size-adjust:none; font-weight: bold;">Route</span>
                        ' .  sanitize_text_field($reserve->return_route->name). '
                    </td>
                    <td style="padding:5px 0;border: 1px solid #d6d6d6;font: 16px Arial, sans-serif;" rowspan="1">
                        <span style="color: #696667;display: block;  font: 16px Arial, sans-serif; -webkit-text-size-adjust:none; font-weight: bold;">Місця</span>
                        <span style="color: #696667;display: block;  font: 16px Arial, sans-serif; -webkit-text-size-adjust:none; font-weight: bold;">Seats</span>
                        <br>' .  sanitize_text_field($reserve->return_data['places_string']). '
                    </td>
                    <td style="padding:5px 0;border: 1px solid #d6d6d6;font: 16px Arial, sans-serif;" rowspan="1">
                        <span style="color: #696667;display: block;  font: 16px Arial, sans-serif; -webkit-text-size-adjust:none; font-weight: bold;">Осіб</span>
                        <span style="color: #696667;display: block;  font: 16px Arial, sans-serif; -webkit-text-size-adjust:none; font-weight: bold;">Persons</span>
                        <br>' .  absint($return_places_count) . '
                    </td>    
                </tr>
                <tr>
                    <td style="padding:5px 0;border: 1px solid #d6d6d6;font: 16px Arial, sans-serif;" colspan="4">
                        <span style="color: #696667;display: block;  font: 16px Arial, sans-serif; -webkit-text-size-adjust:none; font-weight: bold;">Відправлення</span>
                        <span style="color: #696667;display: block;  font: 16px Arial, sans-serif; -webkit-text-size-adjust:none; font-weight: bold;">Departure</span>
                    </td>
                    <td style="padding:5px 0;border: 1px solid #d6d6d6;font: 16px Arial, sans-serif;" colspan="4">
                        <span style="color: #696667;display: block;  font: 16px Arial, sans-serif; -webkit-text-size-adjust:none; font-weight: bold;">Прибуття</span>
                        <span style="color: #696667;display: block;  font: 16px Arial, sans-serif; -webkit-text-size-adjust:none; font-weight: bold;">Arrival</span>
                    </td>
                </tr>
                <tr>
                    <td style="padding:5px 0;border: 1px solid #d6d6d6;font: 16px Arial, sans-serif;" colspan="2">
                        <span style="color: #696667;display: block;  font: 16px Arial, sans-serif; -webkit-text-size-adjust:none; font-weight: bold;">Мiсце вiдправлення</span>
                        <span style="color: #696667;display: block;  font: 16px Arial, sans-serif; -webkit-text-size-adjust:none; font-weight: bold;">Departure place</span>
                    </td>
                    <td style="padding:5px 0;border: 1px solid #d6d6d6;font: 16px Arial, sans-serif;">
                        <span style="color: #696667;display: block;  font: 16px Arial, sans-serif; -webkit-text-size-adjust:none; font-weight: bold;">Дата</span>
                        <span style="color: #696667;display: block;  font: 16px Arial, sans-serif; -webkit-text-size-adjust:none; font-weight: bold;">Date</span>
                    </td>
                    <td style="padding:5px 0;border: 1px solid #d6d6d6;font: 16px Arial, sans-serif;">
                        <span style="color: #696667;display: block;  font: 16px Arial, sans-serif; -webkit-text-size-adjust:none; font-weight: bold;">Час</span>
                        <span style="color: #696667;display: block;  font: 16px Arial, sans-serif; -webkit-text-size-adjust:none; font-weight: bold;">Time</span>
                    </td>
                    <td style="padding:5px 0;border: 1px solid #d6d6d6;font: 16px Arial, sans-serif;" colspan="2">
                        <span style="color: #696667;display: block;  font: 16px Arial, sans-serif; -webkit-text-size-adjust:none; font-weight: bold;">Мiсце прибуття</span>
                        <span style="color: #696667;display: block;  font: 16px Arial, sans-serif; -webkit-text-size-adjust:none; font-weight: bold;">Arrival place</span>
                    </td>
                    <td style="padding:5px 0;border: 1px solid #d6d6d6;font: 16px Arial, sans-serif;">
                        <span style="color: #696667;display: block;  font: 16px Arial, sans-serif; -webkit-text-size-adjust:none; font-weight: bold;">Дата</span>
                        <span style="color: #696667;display: block;  font: 16px Arial, sans-serif; -webkit-text-size-adjust:none; font-weight: bold;">Date</span>
                    </td>
                    <td style="padding:5px 0;border: 1px solid #d6d6d6;font: 16px Arial, sans-serif;">
                        <span style="color: #696667;display: block;  font: 16px Arial, sans-serif; -webkit-text-size-adjust:none; font-weight: bold;">Час</span>
                        <span style="color: #696667;display: block;  font: 16px Arial, sans-serif; -webkit-text-size-adjust:none; font-weight: bold;">Time</span>
                    </td>
                </tr>

                <tr>
                    <td style="padding:5px 0;border: 1px solid #d6d6d6;font: 16px Arial, sans-serif;" colspan="2">
                        ' .  sanitize_text_field($reserve->return_route->leave_address). '
                    </td>
                    <td style="padding:5px 0;border: 1px solid #d6d6d6;font: 16px Arial, sans-serif;">
                        ' .  date('d.m.Y', strtotime($reserve->return_data['trip_date'])). '
                    </td>
                    <td style="padding:5px 0;border: 1px solid #d6d6d6;font: 16px Arial, sans-serif;">
                        ' .  sanitize_text_field($reserve->return_route->leave_time). '
                    </td>
                    <td style="padding:5px 0;border: 1px solid #d6d6d6;font: 16px Arial, sans-serif;" colspan="2">
                        ' .  sanitize_text_field($reserve->return_route->arrive_address). '
                    </td>
                    <td style="padding:5px 0;border: 1px solid #d6d6d6;font: 16px Arial, sans-serif;">
                        ' .  date('d.m.Y', strtotime($reserve->return_data['arrive_date'])). '
                    </td>
                    <td style="padding:5px 0;border: 1px solid #d6d6d6;font: 16px Arial, sans-serif;">
                        ' .  sanitize_text_field($reserve->return_route->arrive_time). '
                    </td>                                          
                </tr>

                <tr>
                    <td style="padding:5px 0;border: 1px solid #d6d6d6;font: 16px Arial, sans-serif;" colspan="2">
                        <span style="color: #696667;display: block;  font: 16px Arial, sans-serif; -webkit-text-size-adjust:none; font-weight: bold;">Вартість мiсця, грн.</span>
                        <span style="color: #696667;display: block;  font: 16px Arial, sans-serif; -webkit-text-size-adjust:none; font-weight: bold;">Fare, hrn(costs)</span>
                    </td>    
                    <td style="padding:5px 0;border: 1px solid #d6d6d6;font: 16px Arial, sans-serif;" colspan="2">
                        ' .  sanitize_text_field($reserve->return_data['place_price']) . '
                    </td>                 
                    <td style="padding:5px 0;border: 1px solid #d6d6d6;font: 16px Arial, sans-serif;" style="padding:5px 0;border: 1px solid #d6d6d6;" colspan="2">
                        <span style="color: #696667;display: block;  font: 16px Arial, sans-serif; -webkit-text-size-adjust:none; font-weight: bold;">(в т.ч. з ПДВ), грн.</span>
                        <span style="color: #696667;display: block;  font: 16px Arial, sans-serif; -webkit-text-size-adjust:none; font-weight: bold;">Total, hrn(costs)</span>
                    </td>
                    <td style="padding:5px 0;border: 1px solid #d6d6d6;font: 16px Arial, sans-serif;" colspan="2">
                        ' .  sanitize_text_field($reserve->return_data['ticket_price']) . '
                    </td> 
                </tr>     
                <tr>
                    <td style="padding:5px 0;border: 1px solid #d6d6d6;font: 16px Arial, sans-serif;" colspan="8">
                        <small>Ви застрахованi СК "Uniqa"</small>
                    </td>                     
                </tr>
            </tbody>
        </table>
    </div>';
}

$ticket .= '</body></html>';

return $ticket;