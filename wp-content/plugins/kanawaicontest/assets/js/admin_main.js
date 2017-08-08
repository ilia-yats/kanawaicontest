jQuery(function () {

    // Datepicker
    jQuery.datepicker.regional['ru'] = {
        closeText: 'Закрыть',
        prevText: '&#x3c;Пред',
        nextText: 'След&#x3e;',
        currentText: 'Сегодня',
        monthNames: ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь',
            'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'],
        monthNamesShort: ['Янв', 'Фев', 'Мар', 'Апр', 'Май', 'Июн',
            'Июл', 'Авг', 'Сен', 'Окт', 'Ноя', 'Дек'],
        dayNames: ['воскресенье', 'понедельник', 'вторник', 'среда', 'четверг', 'пятница', 'суббота'],
        dayNamesShort: ['вск', 'пнд', 'втр', 'срд', 'чтв', 'птн', 'сбт'],
        dayNamesMin: ['Вс', 'Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб'],
        weekHeader: 'Нед',
        dateFormat: 'dd.mm.yy',
        firstDay: 1,
        isRTL: false,
        showMonthAfterYear: false,
        yearSuffix: ''
    };

    jQuery.datepicker.setDefaults(jQuery.datepicker.regional['ru']);
    var datepickers = jQuery(".datepicker");
    datepickers.datepicker({
        dateFormat: 'dd.mm.yy'
    });
    // Convert date from 'dd.mm.yyyy' to 'yyyy-mm-dd' format on form submit
    datepickers.closest('form').submit(function (e) {
        datepickers.each(function (indx, el) {
            el.value = el.value.split('.').reverse().join('-');
        });
    });

    // Enable selection only from one group of checkboxes (certain days of week or even/uneven dates)
    jQuery('.row-days input[type=checkbox]').change(function () {
        jQuery('.row-dates input[type=checkbox]').prop('checked', false);
    });
    jQuery('.row-dates input[type=checkbox]').change(function () {
        jQuery('.row-days input[type=checkbox]').prop('checked', false);
    });

    // Adding and removing inputs for additional phones
    jQuery('#additional_phones').on('click', '.remove_phone', function (e) {
        e.preventDefault();
        this.parentNode.parentNode.remove();
    });
    jQuery('#add_phone').click(function (e) {
        e.preventDefault();
        jQuery('#additional_phones').append(
            '<tr><td><input type="text" name="phones[]" class="regular-text" value=""/>'
            + '<a class="button remove_phone" href="#">Удалить</a></td></tr>'
        );
    });

    // Bulk action confirmation alert
    jQuery('input[id^="doaction"]').click(function () {
        return confirm('Вы действительно хотите совершить выбранное действие для всех отмеченных элементов ?');
    });

    // Enable multi-selection without pressed ctrl key
    jQuery('.places_option').mousedown(function (e) {
        var opt = this;
        event.preventDefault();
        var scroll_offset = opt.parentElement.scrollTop;
        opt.selected = !opt.selected;
        setTimeout(function () {
            opt.parentElement.scrollTop = scroll_offset;
        }, 0);
    });

    // Redirect on page with selected trip_date
    jQuery('#trip_date_edit').change(function () {
        // disable submit
        jQuery('#submit_reserved_place').attr('disabled', true);

        var trip_date = this.value;
        // Replace trip_date in URL with new date;
        //  in the replacement $1 means the first captured group ([?&]+trip_date=) and $2 means the second captured group ([^&]+);
        //  replace only second group (value of the parameter trip_date)
        window.location.href = window.location.href.replace(/([?&]+trip_date=)([^&]+)/gi, '$1' + trip_date);
    });

    // Reset filter form
    jQuery('#filter_form').on('reset', function(e){
        e.preventDefault();
        // Just reload window to clear form and show results without filtration
        window.location.reload(true);
    });
});
