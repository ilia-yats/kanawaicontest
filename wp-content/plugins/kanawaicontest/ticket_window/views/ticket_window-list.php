<div class="wrap">
	<h2>Кассы <?php echo sprintf( '<a href="?page=%s&action=%s" class="add-new-h2">Добавить новую</a>',  esc_attr( $_REQUEST['page'] ), 'new' ); ?></h2>

	<form method="post">
		<input type="hidden" name="page" value="bookingmanager_ticket_windows">
		<?php
		$this->ticket_window_obj->prepare_items();
		$this->ticket_window_obj->search_box( 'Поиск', 'ticket_windows' );
		$this->ticket_window_obj->display(); ?>
	</form>
</div>