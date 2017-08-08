<div class="wrap">
    <h1>Редактирование кассы</h1>

    <?php $item = $this->ticket_window_obj->get_ticket_window($id); ?>

    <form action="" method="post">
        <table class="form-table">
            <tbody>
            <tr class="row-city_id">
                <th scope="row">
                    <label for="city_id">Город</label>
                </th>
                <td>
                    <select name="city_id" id="city_id" class="regular-text" required="required">
                        <?php foreach($this->ticket_window_obj->cities as $city): ?>
                            <option
                                value="<?php echo esc_attr($city['id']); ?>" <?php if($city['id'] == $item->city_id) echo ' selected '; ?>>
                                <?php echo sanitize_text_field($city['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
            <tr class="row-name">
                <th scope="row">
                    <label for="name">Адрес</label>
                </th>
                <td>
                    <input type="text" name="name" id="name" class="regular-text"
                           value="<?php echo esc_attr($item->name); ?>" required="required"/>
                </td>
            </tr>
            <tr class="row-lat">
                <th scope="row">
                    <label for="lat">Широта</label>
                </th>
                <td>
                    <input type="text" name="lat" id="lat" class="regular-text"
                           value="<?php echo esc_attr($item->lat); ?>" required="required"/>
                </td>
            </tr>
            <tr class="row-lon">
                <th scope="row">
                    <label for="lon">Долгота</label>
                </th>
                <td>
                    <input type="text" name="lon" id="lon" class="regular-text"
                           value="<?php echo esc_attr($item->lon); ?>" required="required"/>
                </td>
            </tr>
            <?php
            $first_phone = array_shift($item->phones);
            ?>
            <tr class="row-phone">
                <th scope="row">
                    <label for="phone">Телефоны</label>
                </th>
                <td>
                    <table id="additional_phones">
                        <tr>
                            <td>
                                <input type="text" name="phones[]" class="regular-text"
                                       value="<?php echo esc_attr($first_phone['phone']); ?>" required="required"/>
                                <a class="button" id="add_phone" href="#">Добавить еще</a>
                            </td>
                        </tr>
                        <?php foreach($item->phones as $phone): ?>
                            <tr>
                                <td>
                                    <input type="text" name="phones[]" class="regular-text"
                                           value="<?php echo esc_attr($phone['phone']); ?>" required="required"/>
                                    <a class="button remove_phone" href="#">Удалить</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>

                    </table>
                </td>
            </tr>
            <tr class="row-working_hours">
                <th scope="row">
                    <label for="working_hours">Рабочие часы</label>
                </th>
                <td>
                    <input type="text" name="working_hours" id="working_hours" class="regular-text"
                           value="<?php echo esc_attr($item->working_hours); ?>" required="required"/>
                </td>
            </tr>
            </tbody>
        </table>

        <input type="hidden" name="field_id" value="<?php echo $item->id; ?>">

        <?php wp_nonce_field('bm_new_ticket_window'); ?>
        <?php submit_button('Сохранить', 'primary', 'submit_ticket_window'); ?>

    </form>
</div>