<div class="wrap">
    <h2><?php echo 'Клиенты'; ?><?php echo sprintf( '<a href="?page=%s&action=%s" class="add-new-h2">Добавить новую запись</a>',  esc_attr( $_REQUEST['page'] ), 'new' ); ?></h2>
    <?php $this->client_obj->views(); ?>
    <form method="post">
        <input type="hidden" name="page" value="bookingmanager_clients">
        <?php
            $this->client_obj->prepare_items();
            $this->client_obj->search_box('Поиск', 'client');
            $this->client_obj->display();
        ?>
    </form>
</div>
