<div class="wrap">
    <h1>Добавить маршрут</h1>

    <form action="" method="post">

        <table class="form-table">
            <tbody>
            <tr class="row-days">
                <th scope="row">
                    <p><strong>Дни недели</strong></p>
                </th>
                <td>
                    <label for="day_1">Пн</label>
                    <input type="checkbox" name="day_1" id="day_1" style="margin-right:10px;"/>
                    <label for="day_2">Вт</label>
                    <input type="checkbox" name="day_2" id="day_2" style="margin-right:10px;"/>
                    <label for="day_3">Ср</label>
                    <input type="checkbox" name="day_3" id="day_3" style="margin-right:10px;"/>
                    <label for="day_4">Чт</label>
                    <input type="checkbox" name="day_4" id="day_4" style="margin-right:10px;"/>
                    <label for="day_5">Пт</label>
                    <input type="checkbox" name="day_5" id="day_5" style="margin-right:10px;"/>
                    <label for="day_6">Сб</label>
                    <input type="checkbox" name="day_6" id="day_6" style="margin-right:10px;"/>
                    <label for="day_7">Вс</label>
                    <input type="checkbox" name="day_7" id="day_7" style="margin-right:10px;"/>
                </td>
            </tr>
            <tr class="row-dates">
                <th scope="row">
                    <p><strong>Или числа</strong></p>
                </th>
                <td>
                    <label for="on_even">Четные</label>
                    <input type="checkbox" name="on_even" id="on_even" style="margin-right:10px;"/>
                    <label for="on_uneven">Нечетные</label>
                    <input type="checkbox" name="on_uneven" id="on_uneven" style="margin-right:10px;"/>
                </td>
            </tr>
            <tr class="row-name">
                <th scope="row">
                    <label for="name">Название</label>
                </th>
                <td>
                    <input type="text" name="name" id="name" class="regular-text" value="" required="required"/>
                </td>
            </tr>
            <tr class="row-from_city_id">
                <th scope="row">
                    <label for="from_city_id">Из</label>
                </th>
                <td>
                    <select name="from_city_id" id="from_city_id" class="regular-text" required="required">
                        <?php foreach($this->route_obj->cities as $city): ?>
                            <option value="<?php echo esc_attr($city['id']); ?>">
                                <?php echo sanitize_text_field($city['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
            <tr class="row-to_city_id">
                <th scope="row">
                    <label for="to_city_id">В</label>
                </th>
                <td>
                    <select name="to_city_id" id="to_city_id" class="regular-text" required="required">
                        <?php foreach($this->route_obj->cities as $city): ?>
                            <option value="<?php echo esc_attr($city['id']); ?>">
                                <?php echo sanitize_text_field($city['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
            <tr class="row-bus_type">
                <th scope="row">
                    <label for="bus_type">Количество мест</label>
                </th>
                <td>
                    <select name="bus_type" id="bus_type" class="regular-text" required="required">
                        <?php foreach(BM_Route_List::$places_count_in_bus_types as $bus_type => $places_count): ?>
                            <option value="<?php echo absint($bus_type); ?>">
                                <?php echo absint($places_count); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
            <tr class="row-price">
                <th scope="row">
                    <label for="price">Цена</label>
                </th>
                <td>
                    <input type="text" name="price" id="price" class="regular-text" value="" required="required"/>
                </td>
            </tr>
            <tr class="row-arrive_time">
                <th scope="row">
                    <label for="arrive_time">Время прибытия</label>
                </th>
                <td>
                    <input type="text" name="arrive_time" id="arrive_time" class="regular-text" value=""
                           required="required"/>
                </td>
            </tr>
            <tr class="row-arrive_address">
                <th scope="row">
                    <label for="arrive_address">Адрес прибытия</label>
                </th>
                <td>
                    <input type="text" name="arrive_address" id="arrive_address" class="regular-text"
                           value="" required="required"/>
                </td>
            </tr>
            <tr class="row-leave_time">
                <th scope="row">
                    <label for="leave_time">Время отправления</label>
                </th>
                <td>
                    <input type="text" name="leave_time" id="leave_time" class="regular-text" value=""
                           required="required"/>
                </td>
            </tr>
            <tr class="row-leave_address">
                <th scope="row">
                    <label for="leave_address">Адрес отправления</label>
                </th>
                <td>
                    <input type="text" name="leave_address" id="leave_address" class="regular-text"
                           value="" required="required"/>
                </td>
            </tr>
            <tr class="row-days_in_trip">
                <th scope="row">
                    <label for="days_in_trip">Количество дней в пути</label>
                </th>
                <td>
                    <select name="days_in_trip" id="days_in_trip" required="required">
                        <option value="0">0 (прибытие в тот же день)</option>
                        <option value="1">1 (прибытие на следующий день)</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                    </select>
                </td>
            </tr>
            <tr class="row-return_route_id">
                <th scope="row">
                    <label for="return_route_id">Обратный маршрут</label>
                </th>
                <td>
                    <select name="return_route_id" id="return_route_id" required="required">
                        <option></option>                        
                        <?php foreach($routes as $route): ?>
                            <option value="<?php echo esc_attr($route['id']); ?>"><?php echo esc_attr($route['name']); ?></option>
                        <?php endforeach; ?>                        
                    </select>
                </td>
            </tr>
            </tbody>
        </table>

        <input type="hidden" name="field_id" value="0">

        <?php wp_nonce_field('bm_new_route'); ?>
        <?php submit_button('Сохранить', 'primary', 'submit_route'); ?>

    </form>
</div>