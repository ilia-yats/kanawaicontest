<div class="wrap">
    <h2><?php echo 'Отмененные резервы'; ?></h2>
    <form method="post">
        <input type="hidden" name="page" value="bookingmanager_disclaimed_places">
        <?php
            $this->disclaimed_place_obj->prepare_items();
            $this->disclaimed_place_obj->search_box('Поиск', 'disclaimed_places');
            $this->disclaimed_place_obj->display();
        ?>
    </form>
</div>
