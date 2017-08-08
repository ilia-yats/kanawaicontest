<div class="wrap">
	<h2><?php echo 'Статистика посещений и резервирования'; ?></h2>
	<form method="post">
		<input type="hidden" name="page" value="bookingmanager_public_stat">
		<?php
		$this->public_stat_obj->prepare_items();
		$this->public_stat_obj->display(); ?>
	</form>
</div>