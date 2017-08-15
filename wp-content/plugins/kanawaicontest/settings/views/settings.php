<div class="wrap">
    <h2><?php _e('Kanawai Contest settings'); ?></h2>


<!--    <form method="post" action="options.php">-->
<!--        --><?php
//        settings_fields('bookingmanager_settings');
//        $bookingmanager_admin_email = get_option('bookingmanager_admin_email');
//        if(empty($bookingmanager_admin_email)) {
//            $bookingmanager_admin_email = get_option('admin_email');
//        }
//        ?>
<!--        <table class="form-table">-->
<!--            <tr valign="top">-->
<!--                <th scope="row"><b>E-mail для уведомления админа (перечислите через запятую, если больше одного):</b></th>-->
<!--                <td>-->
<!--                    <input style="min-width:70%;" name="bookingmanager_admin_email"-->
<!--                           value=" --><?php //echo sanitize_text_field($bookingmanager_admin_email); ?><!--"/>-->
<!--                </td>-->
<!--            </tr>-->
<!--            <tr valign="top">-->
<!--                <th scope="row"><b>Тема e-mail'a для уведомления клиента:</b></th>-->
<!--                <td>-->
<!--                    <input style="min-width:70%;" name="bookingmanager_client_email_subject"-->
<!--                           value=" --><?php //echo sanitize_text_field(get_option('bookingmanager_client_email_subject')); ?><!--"/>-->
<!--                </td>-->
<!--            </tr>-->
<!--            <tr valign="top">-->
<!--                <th scope="row">-->
<!--                    <p><b>Текст уведомления клиента о бронировании билета:</b></p>-->
<!--                    <p>NAME - имя и фамилия клиента</p>-->
<!--                    <p>SITE - название сайта</p>-->
<!--                    <p>ROUTE - название маршрута</p>-->
<!--                    <p>PLACES - номера забронированных мест</p>-->
<!--                    <p>DATE - дата отправления</p>-->
<!--                    <p>TIME - время отправления</p>-->
<!--                    <p>ADDRESS - место отправки</p>-->
<!--                    <p><i>EXPIRED - время истечения брони в случае неоплаты</i></p>-->
<!--                    <p><i>(считается автоматически добавлением --><?php //echo BOOKINGMANAGER_RESERVE_EXPIRE_HOURS; ?><!-- часов ко времени бронирования)</i></p>-->
<!--                    <p><i>TICKET_WINDOWS - адреса касс</i></p>-->
<!--                    <p><i>(ссылка на страницу сайта с адресами касс, подставляется автоматически)</i></p>-->
<!--                </th>-->
<!--                <td>-->
<!--                    --><?php
//                    wp_editor(
//                        get_option('bookingmanager_client_email_text'),
//                        'bookingmanager_client_email_text_editor',
//                        array(
//                            'textarea_name' => 'bookingmanager_client_email_text',
//                            'media_buttons' => FALSE,
//                        )
//                    );
//                    ?>
<!--                </td>-->
<!--            </tr>-->
<!--            <tr valign="top">-->
<!--                <th scope="row">-->
<!--                    <p><b>Дополнительный текст уведомления клиента о бронировании обратного билета:</b></p>-->
<!--                    <p>ROUTE - название маршрута</p>-->
<!--                    <p>PLACES - номера забронированных мест</p>-->
<!--                    <p>DATE - дата отправления</p>-->
<!--                    <p>TIME - время отправления</p>-->
<!--                    <p>ADDRESS - место отправки</p>-->
<!--                </th>-->
<!--                <td>-->
<!--                    --><?php
//                    wp_editor(
//                        get_option('bookingmanager_additional_client_email_text'),
//                        'bookingmanager_additional_client_email_text_editor',
//                        array(
//                            'textarea_name' => 'bookingmanager_additional_client_email_text',
//                            'media_buttons' => FALSE,
//                        )
//                    );
//                    ?>
<!--                </td>-->
<!--            </tr>-->
<!--            <tr valign="top">-->
<!--                <th scope="row">-->
<!--                    <p><b>Текст письма при отправке оплаченных билетов:</b></p>-->
<!--                </th>-->
<!--                <td>-->
<!--                    --><?php
//                    wp_editor(
//                        get_option('bookingmanager_tickets_email_text'),
//                        'bookingmanager_tickets_email_text_editor',
//                        array(
//                            'textarea_name' => 'bookingmanager_tickets_email_text',
//                            'media_buttons' => FALSE,
//                        )
//                    );
//                    ?>
<!--                </td>-->
<!--            </tr>-->
<!--            <tr valign="top">-->
<!--                <th scope="row">-->
<!--                    <p><b>Правила для пасажиров:</b></p>-->
<!--                </th>-->
<!--                <td>-->
<!--                    --><?php
//                    wp_editor(
//                        get_option('bookingmanager_rules'),
//                        'bookingmanager_rules_editor',
//                        array(
//                            'textarea_name' => 'bookingmanager_rules',
//                            'media_buttons' => FALSE,
//                        )
//                    );
//                    ?>
<!--                </td>-->
<!--            </tr>-->
<!--        </table>-->
<!---->
<!--        <p class="submit">-->
<!--            <input type="submit" class="button-primary" value="--><?php //echo 'Сохранить изменения' ?><!--"/>-->
<!--        </p>-->
<!---->
<!--    </form>-->
</div>