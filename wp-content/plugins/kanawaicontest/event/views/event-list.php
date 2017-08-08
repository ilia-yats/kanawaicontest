<div class="wrap">
    <h2><?php echo 'Статистика бронирования'; ?></h2>
    <br>
    <?php
    $routes = BM_Route_List::get_all_routes();
    $type = ( ! empty($_REQUEST['type'])) ? esc_sql($_REQUEST['type']) : '';
    $route_id = ( ! empty($_REQUEST['route_id'])) ? esc_sql($_REQUEST['route_id']) : '';
    $ticket_code = ( ! empty($_REQUEST['ticket_code'])) ? esc_sql($_REQUEST['ticket_code']) : '';
    $datetime = ( ! empty($_REQUEST['datetime'])) ? date("d.m.Y", strtotime($_REQUEST['datetime'])) : '';
    $initiator_name = ( ! empty($_REQUEST['initiator_name'])) ? esc_sql($_REQUEST['initiator_name']) : '';
    ?>
    <form id="filter_form" action="" method="post">
        <label for="filter_type">Тип:</label>
        <select id="filter_type" name="type">
            <option value=""></option>
            <?php foreach(BM_Event_List::$event_types_labels as $key => $label): ?>
                <option
                    value="<?php echo $key; ?>" <?php if($type == $key) echo ' selected ' ?>><?php echo $label; ?></option>
            <?php endforeach; ?>
        </select>
        <label for="filter_route_id">Маршрут:</label>
        <select id="filter_route_id" name="route_id">
            <option value=""></option>
            <?php foreach($routes as $route): ?>
                <option
                    value="<?php echo $route['id']; ?>" <?php if($route['id'] === $route_id) echo ' selected ' ?>><?php echo $route['name']; ?></option>
            <?php endforeach; ?>
        </select>


        <label for="filter_ticket_code">Код билета:</label><input type="text" id="filter_ticket_code"
                                                                  name="ticket_code"
                                                                  value="<?php echo $ticket_code; ?>">

        <br>
        <label for="filter_datetime">Дата/Время действия:</label><input type="text" id="filter_datetime" name="datetime"
                                                         class="datepicker"
                                                         value="<?php echo $datetime; ?>">
        <label for="filter_initiator_name">Пользователь:</label><input type="text"
                                                                       id="filter_initiator_name"
                                                                       name="initiator_name"
                                                                       value="<?php echo $initiator_name; ?>">


        <button type="reset" class="button">Сброс</button>
        <input type="submit" class="button primary-button"
               value="Отфильтровать">
    </form>

    <form method="post">
        <input type="hidden" name="page" value="bookingmanager_events">
        <?php
        $this->event_obj->prepare_items();
        $this->event_obj->search_box('Поиск', 'events');
        $this->event_obj->display(); ?>
    </form>
</div>