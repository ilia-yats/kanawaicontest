<div class="wrap">
    <?php
    $posters_list = $this->posters_list;
    /** @var KC_Posters_List $posters_list */
    $posters_list->prepare_items();
    ?>
    <h2>
        <?php if ($posters_list->get_is_current_tour()): ?>
        Images of current contest
            <?php echo sprintf('<a href="?page=%s&action=%s" class="add-new-h2">Add new</a>', esc_attr($_REQUEST['page']), 'new'); ?>
            <?php if ($posters_list->has_items()): ?>
                <a href="" class="add-new-h2" id="archive_btn">Move all to archive</a>
            <?php endif; ?>
        <?php else:
            $tour = Kanawaicontest::get_instance()->tours->init()->tours_list->get_tour($posters_list->get_tour_id()); ?>
            Images of tour <?php echo esc_attr($tour['title']) ?>
        <?php endif; ?>
    </h2>
    <?php if ($posters_list->has_items()): ?>
        <form id="archive_form" action="?page=kanawaicontest_tours&action=archive" method="post">
            <input type="hidden" name="archive_name" id="archive_name" value="">
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