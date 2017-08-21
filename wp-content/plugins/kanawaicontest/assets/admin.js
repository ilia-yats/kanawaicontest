// Move to archive btn
jQuery('#archive_btn').on('click', function (e) {
    e.preventDefault();
    if (confirm('Are you sure you want to finish current contest and send all images to archive?')) {
        jQuery('#archive_form').submit();
    }
});