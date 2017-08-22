<div class="wrap">
    <?php
    $page_url = menu_page_url('kanawaicontest_voters', FALSE);
    $export_link = add_query_arg(array(
        'action' => 'export',
        '_wpnonce' => wp_create_nonce('kanawaicontest_export'),
        'tour_id' => $this->voters_list->get_tour_id(),
        'poster_id' => isset($_REQUEST['poster_id']) ? $_REQUEST['poster_id'] : '',
        's' => isset($_REQUEST['s']) ? $_REQUEST['s'] : '',
        'orderby' => isset($_REQUEST['orderby']) ? $_REQUEST['orderby'] : '',
        'order' => isset($_REQUEST['order']) ? $_REQUEST['order'] : '',
    ), $page_url);
    $export_btn = '<a href="' . $export_link . '" class="add-new-h2">' . __('Export to CSV') . '</a>';
    $this->voters_list->prepare_items();
    $posters = Kanawaicontest::get_instance()->posters->init()->posters_list->get_posters();
    $poster_id = ( ! empty($_REQUEST['poster_id'])) ? esc_sql($_REQUEST['poster_id']) : '';
    ?>
    <h2>
    <?php if ($this->voters_list->get_is_current_tour()): ?>
        <?php echo __('Voters of current contest') ?>
    <?php elseif (!empty($tour_id = $this->voters_list->get_tour_id())):
        $tour = Kanawaicontest::get_instance()->tours->init()->tours_list->get_tour($tour_id); ?>
        <?php echo __('Voters of tour') . '&nbsp' . esc_attr($tour['title']) ?>
    <?php else: ?>
        <?php _e('Voters') ?>
    <?php endif; ?>
    </h2>
    <?php if ($this->voters_list->has_items() &&  empty($_REQUEST['s'])): ?>
        <form id="filter_form" action="" method="get">
            <input type="hidden" name="page" value="<?= esc_attr($_REQUEST['page']) ?>">
            <input type="hidden" name="action" value="<?= esc_attr($_REQUEST['action']) ?>">
            <?php if (isset($_REQUEST['tour_id'])): ?>
                <input type="hidden" name="tour_id" value="<?= esc_attr($_REQUEST['tour_id']) ?>">
            <?php endif; ?>
            <label for="filter_poster_id"><?php _e('Voted for image') ?></label>
            <select id="filter_poster_id" name="poster_id" style="min-width: 300px;">
                <option value=""><?php _e('All') ?></option>
                <?php foreach ($posters as $image): ?>
                    <option
                        value="<?php echo $image['id']; ?>" <?php if ($image['id'] === $poster_id) echo ' selected ' ?>><?php echo $image['title']; ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <input type="submit" class="button primary-button" value="Filter">
            <?php echo sprintf('<a class="button" href="?page=%s&action=%s">' . __('Reset') . '</a>', 'kanawaicontest_voters', 'list'); ?>
        </form>
        <br>
        <?php echo $export_btn; ?>
    <?php endif; ?>
    <form method="post">
        <input type="hidden" name="page" value="kanawaicontest_voters">
        <?php
        if (empty($poster_id)) $this->voters_list->search_box(__('Search'), 'voters');
        $this->voters_list->display(); ?>
    </form>
</div>