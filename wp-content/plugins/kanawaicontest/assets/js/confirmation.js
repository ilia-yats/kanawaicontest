jQuery(function () {

    // Check if bookingmanager js object exists
    if (typeof bookingmanager == 'undefined') {
        alert('Ошибка загрузки данных.');
        return false;
    }

    jQuery('#cash_pay_button').click(function (e) {

        e.preventDefault();       

        sendReservingRequest('cash');        
    });


    jQuery('#online_pay_button').click(function (e) {

        e.preventDefault();
        
        sendReservingRequest('online');
    });

    function sendReservingRequest (paymentType) {

        var statusMessageBlock = jQuery('#status_message');

        jQuery.ajax({
            url: bookingmanager.ajaxHandler,
            type: 'post',
            data: {
                reserve: bookingmanager.reserve,
                nonce: bookingmanager.nonce,
                action: bookingmanager.action,
                payment_type: paymentType
            },

            success: function (responseData) {
                try {
                    responseData = JSON.parse(responseData);
                    if (responseData.status == 1) {
                        // message = 'Места успешно забронированы. Наш консультант свяжется с Вами в ближайшее время';
                        message = 'Места успешно забронированы. <br><br>';

                        if(paymentType == 'online') {
                            // Append button for returning on previous page, if it hasn't been appended yet
                            if (jQuery('#go-to-pay').length <= 0) {
                                // Add handler for button before appending it
                                jQuery('#confirmation-popup-buttons').on('click', '#go-to-pay', function (event) {
                                    event.preventDefault();
                                    // Submit pay form
                                    jQuery('#online_pay_form').submit();
                                }).append('<a id="go-to-pay" href="#" class="btn btn--status">Перейти к оплате</a>');
                            }                            
                        } else {
                            message = message + '<small>Пожалуйста, оплатите их в одной из наших касс в течении 24 часов. Письмо с данными о поездке и адресах касс выслано на Ваш E-mail.</small>';
                        }


                        // Append button for printing the tickets, only if it hasn't been appended yet
                        // if (jQuery('#print-ticket-button').length <= 0) {
                        //     // Add handler for button before appending it
                        //     jQuery('#confirmation-popup-buttons').on('click', '#print-ticket-button', function (event) {
                        //         event.preventDefault();
                        //         // Add rules block to the ticket, if it was not added yet
                        //         var printableTicketBlock = jQuery("#printable-ticket");
                        //         var printableRulesContent = jQuery("#rules-area").html();
                        //         // If rules block hasn't been appended to printable area, append it.
                        //         if (jQuery('#printable-ticket-rules-area').length <= 0) {
                        //             printableTicketBlock.append('<div id="printable-ticket-rules-area"' + printableRulesContent + '</div>');
                        //         }
                        //         // Print the area with tickets and rules
                        //         printableTicketBlock.printArea();
                        //     }).append('<a id="print-ticket-button" href="" class="btn btn--status">Распечатать билет</a>');
                        // }

                    } else {
                        // Append button for returning on previous page, if it hasn't been appended yet
                        if (jQuery('#on-previous-button').length <= 0) {
                            // Add handler for button before appending it
                            jQuery('#confirmation-popup-buttons').on('click', '#on-previous-button', function (event) {
                                event.preventDefault();
                                // Submit form on previous page
                                jQuery('#on-previous-form').submit();
                            }).append('<a id="on-previous-button" href="#" class="btn btn--status">На предыдущий шаг</a>');
                        }
                        message = responseData.message + '<br><br><small>Пожалуйста, вернитесь на предыдущий шаг и перепроверьте информацию.</small>';
                    }
                } catch (exception) {
                    message = 'Ошибка загрузки данных.';
                }

                statusMessageBlock.html(message);
            },
            error: function () {
                message = 'Ошибка загрузки данных.';
                statusMessageBlock.html(message);
            }
        });

        jQuery.magnificPopup.open({
            items: {
                src: '#confirmation-status-popup',
                type: 'inline',
                midClick: true,
                preloader: true,
                closeBtnInside: true
            }
        });

    }

});











  // var statusMessageBlock = jQuery('#status_message');

        // jQuery.ajax({
        //     url: bookingmanager.ajaxHandler,
        //     type: 'post',
        //     data: bookingData,

        //     success: function (responseData) {
        //         try {
        //             responseData = JSON.parse(responseData);
        //             if (responseData.status == 1) {
        //                 // message = 'Места успешно забронированы. Наш консультант свяжется с Вами в ближайшее время';
        //                 message = 'Места успешно забронированы. Пожалуйста, оплатите их в одной из наших касс в течении 24 часов. Письмо с данными о поездке и адресах касс выслано на Ваш E-mail.';

        //                 // Append button for printing the tickets, only if it hasn't been appended yet
        //                 // if (jQuery('#print-ticket-button').length <= 0) {
        //                 //     // Add handler for button before appending it
        //                 //     jQuery('#confirmation-popup-buttons').on('click', '#print-ticket-button', function (event) {
        //                 //         event.preventDefault();
        //                 //         // Add rules block to the ticket, if it was not added yet
        //                 //         var printableTicketBlock = jQuery("#printable-ticket");
        //                 //         var printableRulesContent = jQuery("#rules-area").html();
        //                 //         // If rules block hasn't been appended to printable area, append it.
        //                 //         if (jQuery('#printable-ticket-rules-area').length <= 0) {
        //                 //             printableTicketBlock.append('<div id="printable-ticket-rules-area"' + printableRulesContent + '</div>');
        //                 //         }
        //                 //         // Print the area with tickets and rules
        //                 //         printableTicketBlock.printArea();
        //                 //     }).append('<a id="print-ticket-button" href="" class="btn btn--status">Распечатать билет</a>');
        //                 // }

        //             } else {
        //                 // Append button for returning on previous page, if it hasn't been appended yet
        //                 if (jQuery('#on-previous-button').length <= 0) {
        //                     // Add handler for button before appending it
        //                     jQuery('#confirmation-popup-buttons').on('click', '#on-previous-button', function (event) {
        //                         event.preventDefault();
        //                         // Submit form on previous page
        //                         jQuery('#on-previous-form').submit();
        //                     }).append('<a id="on-previous-button" href="" class="btn btn--status">На предыдущий шаг</a>');
        //                 }
        //                 message = responseData.message + ' Пожалуйста, вернитесь на предыдущий шаг и перепроверьте информацию.';
        //             }
        //         } catch (exception) {
        //             message = 'Ошибка загрузки данных.';
        //         }

        //         statusMessageBlock.text(message);
        //     },
        //     error: function () {
        //         message = 'Ошибка загрузки данных.';
        //         statusMessageBlock.text(message);
        //     }
        // });