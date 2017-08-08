<?php
/**
 * @var object $route
 * @var string $trip_date
 */
?>
<div class="wrap">
    <h1>Зарезервировать место</h1>
    <?php  echo '<h3>Маршрут: ' . esc_attr($route->name) . '</h3>'; ?>
    <?php  echo '<h3>Дата: ' . date("d.m.Y", strtotime($trip_date)) . '</h3>'; ?>

    <br/>
    <form action="" method="post">

        <table class="form-table">
            <tbody>
            <tr class="row-places">
                <th scope="row">
                    <label for="places">Место </label>
                </th>
                <td>
                    <select name="places[]" id="places" required="required" multiple="multiple" size="7">
                        <?php
                        $places_in_bus = (isset(BM_Route_List::$places_count_in_bus_types[$route->bus_type]))
                            ? BM_Route_List::$places_count_in_bus_types[$route->bus_type]
                            : BM_Route_List::$places_count_in_bus_types[1];
                        $reserved_places = $this->reserved_place_obj->get_reserved_places_numbers($route->id, $trip_date);
                        for($place_number = 1; $place_number <= $places_in_bus; $place_number++) {
                            if( ! in_array($place_number, $reserved_places)) {
                                echo '<option class="places_option" value="' . $place_number . '">' . $place_number . '</option>';
                            } else {
                                echo '<option style="color:red;" disabled class="places_option" value="' . $place_number . '" >' . $place_number . '</option>';
                            }
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr class="row-is_paid">
                <th scope="row">
                    <label for="is_paid">Оплата </label>
                </th>
                <td>
                    <input name="is_paid" type="checkbox" id="is_paid">
                </td>
            </tr>
            <tr class="row-name">
                <th scope="row">
                    <label for="name">Имя </label>
                </th>
                <td>
                    <input type="text" name="name" id="name" class="regular-text"
                           value="" required="required"/>
                </td>
            </tr>
            <tr class="row-last_name">
                <th scope="row">
                    <label for="last_name">Фамилия </label>
                </th>
                <td>
                    <input type="text" name="last_name" id="last_name" class="regular-text"
                           value="" required="required"/>
                </td>
            </tr>
            <tr class="row-email">
                <th scope="row">
                    <label for="email">E-mail </label>
                </th>
                <td>
                    <input type="email" name="email" id="email" class="regular-text"
                           value="" required="required"/>
                </td>
            </tr>
            <tr class="row-phone">
                <th scope="row">
                    <label for="phone">Телефон </label>
                </th>
                <td>
                    <input type="text" name="phone" id="phone" class="regular-text"
                           value="" required="required"/>
                </td>
            </tr>
            </tbody>
        </table>

        <input type="hidden" name="route_id" value="<?php echo $route->id; ?>">

        <input type="hidden" name="trip_date" value="<?php echo $trip_date; ?>">


        <?php wp_nonce_field('bm_new_reserved_place'); ?>
        <?php submit_button('Сохранить', 'primary', 'submit_reserved_place'); ?>

    </form>
</div>