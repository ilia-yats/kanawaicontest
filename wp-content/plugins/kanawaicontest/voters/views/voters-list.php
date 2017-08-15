<div class="wrap">
	<h2><?php _e('Voters') ?></h2>

	<form method="post">
		<input type="hidden" name="page" value="kanawaicontest_voters">
		<?php
		$this->voters_list->prepare_items();
		$this->voters_list->search_box( 'Search', 'voters' );
		$this->voters_list->display(); ?>
	</form>
</div>