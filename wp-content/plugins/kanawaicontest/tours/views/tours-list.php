<div class="wrap">
	<h2>Tours <?php echo sprintf( '<a href="?page=%s&action=%s" class="add-new-h2">Add new</a>',  esc_attr( $_REQUEST['page'] ), 'new' ); ?></h2>

	<form method="post">
		<?php
		$this->tours_list->prepare_items();
		$this->tours_list->search_box( 'Search', 'tours' );
		$this->tours_list->display(); ?>
	</form>
</div>