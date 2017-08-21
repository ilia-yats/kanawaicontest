<div class="wrap">
    <?php
    $posters_list = $this->posters_list;
    /** @var KC_Posters_List $posters_list */
    $posters_list->prepare_items();
    $add_new_btn = sprintf('<a href="?page=%s&action=%s" class="add-new-h2">' . __('Add new') . '</a>', esc_attr($_REQUEST['page']), 'new');
    $archive_btn = '<a href="" id="archive_btn" class="add-new-h2">' . __('Move all to archive') . '</a>';
    $start_tour_btn = '<a href="?page=kanawaicontest_tours&action=new" class="add-new-h2">' . __('Start new tour') . '</a>';
    ?>
        <h2>
            <?php if ($posters_list->get_is_current_tour()): ?>
            Images of current contest
                <?php echo $add_new_btn; ?>
                <?php if ($posters_list->has_items()) echo $archive_btn; ?>
            <?php elseif(!empty($tour_id = $posters_list->get_tour_id())) :
                $tour = Kanawaicontest::get_instance()->tours->init()->tours_list->get_tour($tour_id); ?>
                Images of tour <?php echo esc_attr($tour['title']) ?>
            <?php else: ?>
                Images
                <?php echo $add_new_btn; echo $start_tour_btn; ?>
            <?php endif; ?>
        </h2>
        <?php if ($posters_list->has_items()): ?>
            <form id="archive_form" action="?page=kanawaicontest_posters" method="post">
                <input type="hidden" name="action" value="archive">
            </form>
        <?php endif; ?>
        <form method="post">
            <input type="hidden" name="page" value="kanawaicontest_posters">
            <?php
            $posters_list->search_box('Search', 'images');
            $posters_list->display();
            ?>
        </form>

</div>