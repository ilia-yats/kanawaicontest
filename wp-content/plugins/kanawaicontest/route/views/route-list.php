<div class="wrap">
	<h2><?php echo 'Маршруты'; ?> <?php echo sprintf( '<a href="?page=%s&action=%s" class="add-new-h2">Добавить новый</a>',  esc_attr( $_REQUEST['page'] ), 'new' ); ?></h2>

	<form method="post">
		<input type="hidden" name="page" value="bookingmanager_routes">
		<?php
		$this->route_obj->prepare_items();
		$this->route_obj->search_box( 'Поиск', 'routes' );
		$this->route_obj->display(); ?>
	</form>
</div>