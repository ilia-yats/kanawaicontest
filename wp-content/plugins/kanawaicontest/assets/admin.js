// Move to archive btn
jQuery('#archive_btn').on('click', function (e) {
    e.preventDefault();
    var name = prompt('Please type the name of archive entry:', '');
    if (name !== '' && name !== null && name !== undefined) {
        var input = document.getElementById('archive_name');
        input.value = name;
        jQuery('#archive_form').submit();
    }
});