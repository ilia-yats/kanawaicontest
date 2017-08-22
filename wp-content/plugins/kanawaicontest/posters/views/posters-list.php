<div class="wrap">
    <?php
    $page_url = menu_page_url('kanawaicontest', FALSE);
    $posters_list = $this->posters_list;
    /** @var KC_Posters_List $posters_list */
    $posters_list->prepare_items();
    $add_new_btn = sprintf('<a href="?page=%s&action=%s" class="add-new-h2">' . __('Add new') . '</a>', esc_attr($_REQUEST['page']), 'new');
    $start_tour_btn = '<a href="?page=kanawaicontest_tours&action=new" class="add-new-h2">' . __('Start new tour') . '</a>';
    $archive_link = add_query_arg(array(
        'action' => 'archive',
        '_wpnonce' => wp_create_nonce('kanawaicontest_archive')
    ), $page_url);
    $archive_btn = '<a href="' . $archive_link . '" id="archive_btn" class="add-new-h2">' . __('Move all to archive') . '</a>';
    $export_link = add_query_arg(array(
        'action' => 'export',
        '_wpnonce' => wp_create_nonce('kanawaicontest_export'),
        'tour_id' => $posters_list->get_tour_id(),
        's' => isset($_REQUEST['s']) ? $_REQUEST['s'] : '',
        'orderby' => isset($_REQUEST['orderby']) ? $_REQUEST['orderby'] : '',
        'order' => isset($_REQUEST['order']) ? $_REQUEST['order'] : '',
    ), $page_url);
    $export_btn = '<a href="' . $export_link . '" class="add-new-h2">' . __('Export to CSV') . '</a>';
    ?>
    <h2>
        <?php if ($posters_list->get_is_current_tour()): ?>
        <?php _e('Posters of current contest') ?>
            <?php echo $add_new_btn; ?>
            <?php if ($posters_list->has_items()): ?>
                <?php echo $archive_btn . $export_btn?>
            <?php endif; ?>
        <?php elseif(!empty($tour_id = $posters_list->get_tour_id())) :
            $tour = KC_Tours_List::get_tour($tour_id); ?>
            <?php echo __('Posters of tour') . '&nbsp' . esc_attr($tour['title']) ?>
            <?php echo $export_btn; ?>
        <?php else: ?>
            <?php _e('Posters') ?>
            <?php echo $add_new_btn . $start_tour_btn; ?>
        <?php endif; ?>
    </h2>
    <form method="post">
        <input type="hidden" name="page" value="kanawaicontest">
        <?php
        $posters_list->search_box(__('Search'), 'images');
        $posters_list->display();
        ?>
    </form>
</div>