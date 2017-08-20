<div class="wrap">
	<h2><?php _e('Voters') ?></h2>
	<?php
	$posters = Kanawaicontest::get_instance()->posters->init()->posters_list->get_posters();
	$poster_id = ( ! empty($_REQUEST['poster_id'])) ? esc_sql($_REQUEST['poster_id']) : '';
	?>
	<form id="filter_form" action="" method="get">
		<input type="hidden" name="page" value="<?= esc_attr($_REQUEST['page']) ?>">
		<label for="filter_poster_id"><?php _e('Voted for image')?></label>
		<select id="filter_poster_id" name="poster_id">
			<option value=""></option>
			<?php foreach($posters as $image): ?>
				<option
					value="<?php echo $image['id']; ?>" <?php if($image['id'] === $poster_id) echo ' selected ' ?>><?php echo $image['title']; ?></option>
			<?php endforeach; ?>
		</select>
		<input type="submit" class="button primary-button" value="Filter">
		<?php echo sprintf('<a class="button" href="?page=%s&action=%s">Reset</a>', 'kanawaicontest_voters', 'list'); ?>
	</form>
	<form method="post">
		<input type="hidden" name="page" value="kanawaicontest_voters">
		<?php
		$this->voters_list->prepare_items();
		$this->voters_list->search_box( 'Search', 'voters' );
		$this->voters_list->display(); ?>
	</form>
</div>