<div class="wrap">
	<h2><?php echo 'Статистика работы кассиров'; ?></h2>
	<form method="post">
		<input type="hidden" name="page" value="bookingmanager_events_stat">
		<?php
		$this->event_stat_obj->prepare_items();
		$this->event_stat_obj->display(); ?>
	</form>
</div>