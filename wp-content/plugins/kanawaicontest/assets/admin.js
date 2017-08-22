// Move to archive btn
jQuery('#archive_btn').on('click', function (e) {
    e.preventDefault();
    if (confirm(kanawaicontest.confirmArchiveMsg)) {
        window.location.href = this.href;
    }
});