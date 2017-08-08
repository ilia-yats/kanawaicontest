<div class="wrap">
    <h2><?php echo 'Зарезервированные места'; ?></h2>

    <?php if( ! empty($route) && ! empty($trip_date)): ?>
    <br/>
    <?php echo '<h3>Маршрут: ' . esc_attr($route->name).'</h3>';?>
    <?php echo '<h3>Дата: ' . date("d.m.Y", strtotime($trip_date)) .'</h3>';?>
    <h2><?php echo sprintf('<a href="?page=%s&action=%s&route_id=%d&trip_date=%s" class="add-new-h2">Зарезервировать новое</a>', esc_attr($_REQUEST['page']), 'new', $route->id, $trip_date); ?></h2>
    <br/>
    <?php endif; ?>

    <form method="post">
        <input type="hidden" name="page" value="bookingmanager_places">
        <?php
            $this->reserved_place_obj->prepare_items();
            $this->reserved_place_obj->search_box('Поиск', 'reserved_places');
            $this->reserved_place_obj->display();
        ?>
    </form>
</div>
