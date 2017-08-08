<div class="wrap">
    <h1><?php echo 'Редактирование маршрута'; ?></h1>

    <?php $item = $this->route_obj->get_route($id); ?>

    <form action="" method="post">
        <table class="form-table">
            <tbody>
            <tr class="row-days">
                <th scope="row">
                    <p><strong><?php echo 'Дни'; ?></strong></p>
                </th>
                <td>
                    <label for="day_1"><?php echo 'Пн'; ?></label>
                    <input type="checkbox" name="day_1"
                           id="day_1" <? if($item->day_1 == 1) echo 'checked'; ?> style="margin-right:10px;"/>
                    <label for="day_2"><?php echo 'Вт'; ?></label>
                    <input type="checkbox" name="day_2"
                           id="day_2" <? if($item->day_2 == 1) echo 'checked'; ?> style="margin-right:10px;"/>
                    <label for="day_3"><?php echo 'Ср'; ?></label>
                    <input type="checkbox" name="day_3"
                           id="day_3" <? if($item->day_3 == 1) echo 'checked'; ?> style="margin-right:10px;"/>
                    <label for="day_4"><?php echo 'Чт'; ?></label>
                    <input type="checkbox" name="day_4"
                           id="day_4" <? if($item->day_4 == 1) echo 'checked'; ?> style="margin-right:10px;"/>
                    <label for="day_5"><?php echo 'Пт'; ?></label>
                    <input type="checkbox" name="day_5"
                           id="day_5" <? if($item->day_5 == 1) echo 'checked'; ?> style="margin-right:10px;"/>
                    <label for="day_6"><?php echo 'Сб'; ?></label>
                    <input type="checkbox" name="day_6"
                           id="day_6" <? if($item->day_6 == 1) echo 'checked'; ?> style="margin-right:10px;"/>
                    <label for="day_7"><?php echo 'Вс'; ?></label>
                    <input type="checkbox" name="day_7"
                           id="day_7" <? if($item->day_7 == 1) echo 'checked'; ?> style="margin-right:10px;"/>
                </td>
            </tr>
            <tr class="row-dates">
                <th scope="row">
                    <p><strong>Или числа</strong></p>
                </th>
                <td>
                    <label for="on_even">Четные</label>
                    <input type="checkbox" name="on_even" id="on_even"
                        <? if($item->on_even == 1) echo 'checked'; ?> style="margin-right:10px;"/>
                    <label for="on_uneven">Нечетные</label>
                    <input type="checkbox" name="on_uneven" id="on_uneven"
                        <? if($item->on_uneven == 1) echo 'checked'; ?> style="margin-right:10px;"/>
                </td>
            </tr>
            <tr class="row-name">
                <th scope="row">
                    <label for="name"><?php echo 'Название'; ?></label>
                </th>
                <td>
                    <input type="text" name="name" id="name" class="regular-text"
                           value="<?php echo esc_attr($item->name); ?>" required="required"/>
                </td>
            </tr>
            <tr class="row-from_city_id">
                <th scope="row">
                    <label for="from_city_id"><?php echo 'Из'; ?></label>
                </th>
                <td>
                    <select name="from_city_id" id="from_city_id" class="regular-text" required="required">
                        <?php foreach($this->route_obj->cities as $city): ?>
                            <option
                                value="<?php echo esc_attr($city['id']); ?>" <?php if($city['id'] == $item->from_city_id) echo ' selected '; ?>>
                                <?php echo esc_attr($city['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
            <tr class="row-to_city_id">
                <th scope="row">
                    <label for="to_city_id"><?php echo 'В'; ?></label>
                </th>
                <td>
                    <select name="to_city_id" id="to_city_id" class="regular-text" required="required">
                        <?php foreach($this->route_obj->cities as $city): ?>
                            <option
                                value="<?php echo esc_attr($city['id']); ?>" <?php if($city['id'] == $item->to_city_id) echo ' selected '; ?>>
                                <?php echo esc_attr($city['name']); ?>
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
                            <option
                                value="<?php echo absint($bus_type); ?>" <?php if($bus_type == $item->bus_type) echo ' selected '; ?>>
                                <?php echo absint($places_count); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
            <tr class="row-price">
                <th scope="row">
                    <label for="price"><?php echo 'Цена'; ?></label>
                </th>
                <td>
                    <input type="text" name="price" id="price" class="regular-text"
                           value="<?php echo esc_attr($item->price); ?>" required="required"/>
                </td>
            </tr>
            <tr class="row-arrive_time">
                <th scope="row">
                    <label for="arrive_time"><?php echo 'Время прибытия'; ?></label>
                </th>
                <td>
                    <input type="text" name="arrive_time" id="arrive_time" class="regular-text"
                           value="<?php echo esc_attr($item->arrive_time); ?>" required="required"/>
                </td>
            </tr>
            <tr class="row-arrive_address">
                <th scope="row">
                    <label for="arrive_address"><?php echo 'Адрес прибытия'; ?></label>
                </th>
                <td>
                    <input type="text" name="arrive_address" id="arrive_address" class="regular-text"
                           value="<?php echo esc_attr($item->arrive_address); ?>" required="required"/>
                </td>
            </tr>
            <tr class="row-leave_time">
                <th scope="row">
                    <label for="leave_time"><?php echo 'Время отправления'; ?></label>
                </th>
                <td>
                    <input type="text" name="leave_time" id="leave_time" class="regular-text"
                           value="<?php echo esc_attr($item->leave_time); ?>" required="required"/>
                </td>
            </tr>
            <tr class="row-leave_address">
                <th scope="row">
                    <label for="leave_address"><?php echo 'Адрес отправления'; ?></label>
                </th>
                <td>
                    <input type="text" name="leave_address" id="leave_address" class="regular-text"
                           value="<?php echo esc_attr($item->leave_address); ?>" required="required"/>
                </td>
            </tr>
            <tr class="row-days_in_trip">
                <th scope="row">
                    <label for="days_in_trip">Количество дней в пути</label>
                </th>
                <td>
                    <select name="days_in_trip" id="days_in_trip" required="required">
                        <option <?php if($item->days_in_trip == 0) echo ' selected '; ?> value="0">0 (прибытие в тот же
                            день)
                        </option>
                        <option <?php if($item->days_in_trip == 1) echo ' selected '; ?> value="1">1 (прибытие на
                            следующий день)
                        </option>
                        <option <?php if($item->days_in_trip == 2) echo ' selected '; ?> value="2">2</option>
                        <option <?php if($item->days_in_trip == 3) echo ' selected '; ?> value="3">3</option>
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
                            <?php if($route['id'] != $item->id): ?>
                                <option value="<?php echo esc_attr($route['id']); ?>"
                                    <?php if($item->return_route_id == $route['id']) echo ' selected '; ?>>
                                    <?php echo esc_attr($route['name']); ?>
                                </option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
            </tbody>
        </table>

        <input type="hidden" name="field_id" value="<?php echo $item->id; ?>">

        <?php wp_nonce_field('bm_new_route'); ?>
        <?php submit_button('Сохранить', 'primary', 'submit_route'); ?>

    </form>
</div>