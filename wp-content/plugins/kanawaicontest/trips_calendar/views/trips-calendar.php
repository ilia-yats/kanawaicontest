<div class="wrap">
	<h2><?php echo 'Календарь поездок'; ?></h2>

<form method="post" id="filter_form">
    <input type="hidden" name="page" value="bookingmanager">
    <?php
    $this->trips_calendar_obj->prepare_items();
    $this->trips_calendar_obj->display(); ?>
</form>
</div>