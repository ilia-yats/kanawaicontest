<div class="wrap">
    <h2>
        <?php _e('Tours') ?>
    </h2>
    <form method="post">
        <?php
        $this->tours_list->prepare_items();
        $this->tours_list->search_box('Search', 'tours');
        $this->tours_list->display(); ?>
    </form>
</div>