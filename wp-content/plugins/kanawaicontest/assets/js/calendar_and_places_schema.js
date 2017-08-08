jQuery(function () {

    // Check if bookingmanager object exists
    if (typeof bookingmanager == 'undefined') {
        alert('Ошибка загрузки данных. Попрбуйте перезагрузить страницу.');
        return false;
    }

    // Here we will operate with two blocks of content and types of variables:
    //  'there'-variables - relate to straight routes, trips, dates and places
    //  'return'-variables - relate to returning routes, trips, dates and places

    // Calendar blocks for routes 'there' and 'return'
    var thereCalendarBlock = jQuery('#date-departure');
    var returnCalendarBlock = jQuery('#date-return');

    // Bus places schemas blocks and forms
    var thereBusBlock = jQuery('#bus-departure');
    //var thereBusForm = thereBusBlock.find('form');
    var returnBusBlock = jQuery('#bus-return');
    //var returnBusForm = returnBusBlock.find('form');

    thereBusBlock.hide();

    // Routes data objects
    var thereRoute = '';
    var returnRoute = '';

    // Dates of there and return trips
    //var thereDate = bookingmanager.startDate;
    var thereDate = '';
    var returnDate = '';

    // Spans with information about selected dates and places
    var thereDateSpan = jQuery('#total-date');
    var therePlaceSpan = jQuery('#total-place');
    var returnDateSpan = jQuery('#total-date-return');
    var returnPlaceSpan = jQuery('#total-place-return');

    // Init calendar widget for there trip
    thereCalendarBlock.fullCalendar({
        columnFormat: {
            month: 'dd'
        },
        firstDay: 1, // Monday
        header: {
            left: 'prev',
            center: 'title',
            right: 'next'
        },
        contentHeight: 520,
        selectOverlap: false,
        eventSources: [
            {
                color: 'transparent',
                textColor: '#d76447',
                url: bookingmanager.ajaxHandler,  // Data provider back-end script
                type: 'POST',
                cache: true,
                data: {
                    route_id: bookingmanager.thereRouteId,
                    route_bus_type: bookingmanager.thereRouteBusType,
                    return_start_date: '',
                    nonce: bookingmanager.nonce,
                    action: bookingmanager.action,
                    isReturn: 0
                },
                error: function () {
                    alert('Ошибка загрузки данных. Попрбуйте перезагрузить страницу.');
                }
            }
        ],
        eventRender: function (event, element) {
            jQuery(element).addClass('selectedEvent');
        },
        eventClick: function (calendarEvent, jsEvent, view) {  // Handler of trip date changing

            // Make all actions only if new selected date not equals to date selected earlier
            var clickedTripDate = calendarEvent.start.format();
            if (thereDate != clickedTripDate) {

                // Set up date of there trip
                thereDate = clickedTripDate;

                // Set up the object with route information
                thereRoute = calendarEvent.route;

                // Reset selected places and content of information spans
                therePlaceSpan.text('');
                thereDateSpan.text('');
                //thereBusForm[0].reset();
                jQuery('#bus-departure input:checked').prop('checked', false);

                // Remove selection highlight from all previous selected trips and add it to current trip
                thereCalendarBlock.find('.selectedEvent').removeClass('selectedEvent');
                jQuery(this).addClass('selectedEvent');

                // Collect all checked places into array to show them in special span
                var thereCheckedPlaces = [];
                thereBusBlock.find('input').prop('disabled', true).change(function (jsEvent) {
                    var currentPlaceObject = jQuery(jsEvent.target);
                    var currentPlaceNumber = currentPlaceObject.val();
                    if (currentPlaceObject.prop('checked') == true) {
                        thereCheckedPlaces.push(currentPlaceNumber);
                    } else {
                        var indexOfPlaceInArray = thereCheckedPlaces.indexOf(currentPlaceNumber);
                        if (indexOfPlaceInArray > -1) {
                            thereCheckedPlaces.splice(indexOfPlaceInArray, 1);
                        }
                    }
                    therePlaceSpan.text(thereCheckedPlaces.join(', '));
                });

                // Show places schema if it was hidden
                thereBusBlock.show();

                // Remove 'disabled' property from all places that are not reserved yet according to the information places in current trip
                for (var placeNumber in calendarEvent.places) {
                    if (calendarEvent.places[placeNumber] == 0) {
                        var currentPlaceElement = jQuery('#sit-' + placeNumber);
                        currentPlaceElement.prop('disabled', false);
                    }
                }
                // Show selected date in special span
                thereDateSpan.text(moment(thereDate).format('DD.MM.YYYY'));

                // When there trip date was changed, re-render dates on return trip calendar and move it to new date
                returnCalendarBlock.fullCalendar('refetchEvents');
                returnCalendarBlock.fullCalendar('gotoDate', thereDate);
            }
        }
    });

    //Hide return block
    jQuery("#return").hide();

    // Rendering the return block
    jQuery("#back__link").on("click", function (e) {
        e.preventDefault();
        jQuery(this).toggleClass('opened');
        jQuery("#return").slideToggle(800);

        // Hide the return places schema
        returnBusBlock.hide();

        // Reset return route data object
        returnRoute = '';
        // Reset returning date, selected places and content of information spans
        returnPlaceSpan.text('');
        returnDateSpan.text('');
        returnDate = '';
        jQuery('#bus-return input:checked').prop('checked', false);
        returnCalendarBlock.find('.selectedEvent').removeClass('selectedEvent');

        // Init return calendar
        returnCalendarBlock.fullCalendar({
            columnFormat: {
                month: 'dd'
            },
            defaultDate: (thereDate != '') ? thereDate : bookingmanager.startDate, // Begin return trip calendar from the date selected on there calendar
            //defaultDate: thereDate, // Begin return trip calendar from the date selected on there calendar
            firstDay: 1,
            header: {
                left: 'prev',
                center: 'title',
                right: 'next'
            },
            contentHeight: 600,
            selectOverlap: false,
            eventSources: [
                {
                    color: 'transparent',
                    textColor: '#d76447',
                    url: bookingmanager.ajaxHandler,
                    type: 'POST',
                    cache: true,
                    data: {
                        route_id: bookingmanager.returnRouteId,
                        route_bus_type: bookingmanager.returnRouteBusType,
                        return_start_date: function () {
                            return (thereDate != '') ? thereDate : bookingmanager.startDate;
                        },// Begin return trip calendar from the date selected on there calendar,
                        // use callback to return actual value
                        nonce: bookingmanager.nonce,
                        action: bookingmanager.action,
                        isReturn: 1
                    },
                    error: function () {
                        alert('Ошибка загрузки данных.  Попрбуйте перезагрузить страницу.');
                    }
                }
            ],
            eventRender: function (event, element) {
                jQuery(element).addClass('selectedEvent');
            },
            eventClick: function (calendarEvent, jsEvent, view) { // Handler of changing of return trip date

                // Make all actions only if new selected date not equals to date selected earlier
                var clickedTripDate = calendarEvent.start.format();
                if (returnDate != clickedTripDate) {

                    returnDate = clickedTripDate;

                    // Set up return route data
                    returnRoute = calendarEvent.route;

                    // Reset selected places and spans
                    returnPlaceSpan.text('');
                    //returnBusForm[0].reset();
                    jQuery('#bus-return input:checked').prop('checked', false);
                    returnCalendarBlock.find('.selectedEvent').removeClass('selectedEvent');
                    jQuery(this).addClass('selectedEvent');

                    // Set up return date
                    returnDate = calendarEvent.start.format();

                    // Collect all checked places into array to show them in special span
                    var returnCheckedPlaces = [];
                    returnBusBlock.find('input').prop('disabled', true).change(function (jsEvent) {
                        var currentPlaceObject = jQuery(jsEvent.target);
                        var currentPlaceNumber = currentPlaceObject.val();
                        if (currentPlaceObject.prop('checked') == true) {
                            returnCheckedPlaces.push(currentPlaceNumber);
                        } else {
                            var indexOfPlaceInArray = returnCheckedPlaces.indexOf(currentPlaceNumber);
                            if (indexOfPlaceInArray > -1) {
                                returnCheckedPlaces.splice(indexOfPlaceInArray, 1);
                            }
                        }
                        returnPlaceSpan.text(returnCheckedPlaces.join(','));
                    });

                    // Remove 'disabled' property from all places that are not reserved yet according to the information places in current trip
                    for (var placeNumber in calendarEvent.places) {
                        if (calendarEvent.places[placeNumber] == 0) {
                            var currentPlaceElement = jQuery('#return-sit-' + placeNumber);
                            currentPlaceElement.prop('disabled', false);
                        }
                    }

                    // Show return places schema
                    returnBusBlock.show();

                    // Show selected return date in special span
                    returnDateSpan.text(moment(returnDate).format('DD.MM.YYYY'));
                }
            }
        });
    });

    // Set values of inputs, if they already exists
    jQuery('#popup-passenger_name').val(bookingmanager.prevName);
    jQuery('#popup-passenger_last_name').val(bookingmanager.prevLastName);
    jQuery('#popup-passenger_phone').val(bookingmanager.prevPhone);
    jQuery('#popup-passenger_email').val(bookingmanager.prevEmail);

    // Instead of submitting form in modal window, pass its values to the hidden inputs in main form and submit main form
    jQuery('#popup-places_form').submit(function (e) {
        e.preventDefault();

        var countOfSelectedTherePlaces = jQuery('#bus-departure input:checked').length;
        var countOfSelectedReturnPlaces = jQuery('#bus-return input:checked').length;

        if (thereDate != '' && countOfSelectedTherePlaces > 0) {

            jQuery('#passenger_name').val(jQuery('#popup-passenger_name').val());
            jQuery('#passenger_last_name').val(jQuery('#popup-passenger_last_name').val());
            jQuery('#passenger_phone').val(jQuery('#popup-passenger_phone').val());
            jQuery('#passenger_email').val(jQuery('#popup-passenger_email').val());

            jQuery('#there_route_id').val(thereRoute.id);
            jQuery('#there_route_name').val(thereRoute.name);
            jQuery('#there_route_arrive_time').val(thereRoute.arrive_time);
            jQuery('#there_route_arrive_address').val(thereRoute.arrive_address);
            jQuery('#there_route_leave_time').val(thereRoute.leave_time);
            jQuery('#there_route_leave_address').val(thereRoute.leave_address);
            jQuery('#there_trip_date').val(thereDate);

            if (returnRoute != '') {
                if (countOfSelectedReturnPlaces < 1) {
                    var goAhead = confirm('Вы не указали дату и место для обратной поездки.' + "\n"
                        + ' Продолжить без бронирования обратного билета ?'
                    );
                    if (!goAhead) {

                        return false;
                    }
                } else {
                    jQuery('#return_route_id').val(returnRoute.id);
                    jQuery('#return_route_name').val(returnRoute.name);
                    jQuery('#return_route_arrive_time').val(returnRoute.arrive_time);
                    jQuery('#return_route_arrive_address').val(returnRoute.arrive_address);
                    jQuery('#return_route_leave_time').val(returnRoute.leave_time);
                    jQuery('#return_route_leave_address').val(returnRoute.leave_address);
                    jQuery('#return_trip_date').val(returnDate);
                }
            }

            jQuery('#places_form').submit();

        } else {
            alert('Пожалуйста, выберите дату и место.');
        }
    });

    //popup step three departure
    jQuery('#to-step-three').magnificPopup({
        type: 'inline',
        closeBtnInside: true,
        callbacks: {
            open: function () {
                jQuery("#step-three").addClass('animated fadeInUp');
            }
        }
    });
    //popup step three return
    jQuery('#to-step-three--return').magnificPopup({
        type: 'inline',
        closeBtnInside: true,
        callbacks: {
            open: function () {
                jQuery("#step-three--return").addClass('animated fadeInUp');
            }
        }
    });
    //btn bg
    jQuery(".btn--bg").on("click", function () {
        jQuery(this).val('').css({
            "background-image": "url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAEMAAAAaCAYAAADsS+FMAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyBpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMC1jMDYwIDYxLjEzNDc3NywgMjAxMC8wMi8xMi0xNzozMjowMCAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENTNSBXaW5kb3dzIiB4bXBNTTpJbnN0YW5jZUlEPSJ4bXAuaWlkOjAzNDlBNTk3MTgzRDExRTY5RjRBOURFNkQ4MTgzMUUzIiB4bXBNTTpEb2N1bWVudElEPSJ4bXAuZGlkOjAzNDlBNTk4MTgzRDExRTY5RjRBOURFNkQ4MTgzMUUzIj4gPHhtcE1NOkRlcml2ZWRGcm9tIHN0UmVmOmluc3RhbmNlSUQ9InhtcC5paWQ6MDM0OUE1OTUxODNEMTFFNjlGNEE5REU2RDgxODMxRTMiIHN0UmVmOmRvY3VtZW50SUQ9InhtcC5kaWQ6MDM0OUE1OTYxODNEMTFFNjlGNEE5REU2RDgxODMxRTMiLz4gPC9yZGY6RGVzY3JpcHRpb24+IDwvcmRmOlJERj4gPC94OnhtcG1ldGE+IDw/eHBhY2tldCBlbmQ9InIiPz4rc8kfAAACoklEQVR42uxZwXHbMBCENPyb+eQbpoLQz4wfISuwVYHlCmyOC5BVQEZyBVYHoSsI/cjkGaYC8Z1P6AqUg2fPOcEgSIciZUW+mRuSJwAk9hZ3B2iwWq1UF/L5WzGmy4Q0UC9TCtLk8ihI2TDoAgwCQgOwxGNJmjuaB9AcbVUPbX3SEPcxAZLpG68j1JkNOV5WOoC7AoMS0qxm3E221cy9IT3n34cdU/HWBcSWZQFnRWwYqv0WzQif2Bm9gqHUHa7Hr2D8jSV7zYwvCKCc6UJaKv6+gnGCTCPZEXYNxocXnEkCsIMl8rr2ANHvqwhUNvmE66lMcz21XauQve/LX3wfgj66TE2xngLYFGyFOdrH92/rAIkafLgyvNRXW7lVeMfMuDE6zUinQM4XtjMEHBO0/0ECDyCMLbX7zNJhJsB5eCZmxbzxIZbsNDDDZ+4qfcvzD+hvAma862BsUs53GQwPaWaypfSml9W9Dl4Isps8++CYpsc/QIKI6sAoEBhnlmVQJyW2yMd4njbsc42UFxjg+CJ7tZE5Jn9hgDM3bE/A4A/JxIFMkwmNdGZB0Fw8kxGTihSYcnnccr8RWcbgIqsSkKGBXOGYfGmwIXOk1Sp7anh+ATaVoky+3cBOlIHI8a2pCPh+xTzvzACaVLxAL6M3pANcnUy4PApyeMCUn2JpLKidHvcK47MctIwTcgsQ4ztGAoBIPT2G1I6dexbPHRrF1rVAtrHQRBMqxacGXYsGwfoek/gXKR3jl4IdCeb1YIfzujkQdgkBtBTsmAqA2Hao3AfIdXIhCsYc7zg1thUj4bTHjtsA4wTnCVXBL275Ci4Eq9L0GtgSjN7PM/A/xZklyK55rIWUADSzxJPYxbremWGwJGqQydpIKGJfVuGcx/s/AgwABWzSAhaQMRkAAAAASUVORK5CYII=')",
            "background-color": "#64a0c9"
        });
    });

    // Validation for phone input (only digits)
    jQuery(document).on("change keyup", '#popup-passenger_phone', function () {
        if (this.value.match(/[^0-9]/g)) {
            this.value = this.value.replace(/[^0-9]/g, '');
        }
    });


});