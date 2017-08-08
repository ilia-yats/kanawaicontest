<div class="wrap">
	<h2>Города <?php echo sprintf( '<a href="?page=%s&action=%s" class="add-new-h2">Добавить новый</a>',  esc_attr( $_REQUEST['page'] ), 'new' ); ?></h2>

	<form method="post">
		<input type="hidden" name="page" value="bookingmanager_cities">
		<?php
		$this->city_obj->prepare_items();
		$this->city_obj->search_box( 'Поиск', 'cities' );
		$this->city_obj->display(); ?>
	</form>
</div>