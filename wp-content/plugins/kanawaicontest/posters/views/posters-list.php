<div class="wrap">
	<h2>Images <?php echo sprintf( '<a href="?page=%s&action=%s" class="add-new-h2">Add new</a>',  esc_attr( $_REQUEST['page'] ), 'new' ); ?></h2>

	<form method="post">
		<input type="hidden" name="page" value="kanawaicontest_posters">
		<?php
		$this->posters_list->prepare_items();
		$this->posters_list->search_box( 'Search', 'images' );
		$this->posters_list->display(); ?>
	</form>
</div>