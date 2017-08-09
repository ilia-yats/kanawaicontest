<div class="wrap">
	<h2>Voters <?php echo sprintf( '<a href="?page=%s&action=%s" class="add-new-h2">Add new</a>',  esc_attr( $_REQUEST['page'] ), 'new' ); ?></h2>

	<form method="post">
		<input type="hidden" name="page" value="kanawaicontest_voters">
		<?php
		$this->voters_list->prepare_items();
		$this->voters_list->search_box( 'Search', 'voters' );
		$this->voters_list->display(); ?>
	</form>
</div>