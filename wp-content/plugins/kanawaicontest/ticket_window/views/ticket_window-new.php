<div class="wrap">
    <h1>Добавить кассу</h1>

    <form action="" method="post">

        <table class="form-table">
            <tbody>
            <tr class="row-city_id">
                <th scope="row">
                    <label for="city_id">Город</label>
                </th>
                <td>
                    <select name="city_id" id="city_id" required="required">
                        <?php foreach($this->ticket_window_obj->cities as $city): ?>
                            <option value="<?php echo esc_attr($city['id']); ?>">
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
                           value="" required="required"/>
                </td>
            </tr>
            <tr class="row-lat">
                <th scope="row">
                    <label for="lat">Широта</label>
                </th>
                <td>
                    <input type="text" name="lat" id="lat" class="regular-text"
                           value="" required="required"/>
                </td>
            </tr>
            <tr class="row-lon">
                <th scope="row">
                    <label for="lon">Долгота</label>
                </th>
                <td>
                    <input type="text" name="lon" id="lon" class="regular-text"
                           value="" required="required"/>
                </td>
            </tr>
            <tr class="row-phone">
                <th scope="row">
                    <label for="phone">Телефоны</label>
                </th>
                <td>
                    <table id="additional_phones">
                        <tr>
                            <td>
                                <input type="text" name="phones[]"
                                class="regular-text"
                                value="" required="required"/>
                                <a class="button" id="add_phone" href="#">Добавить еще</a>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr class="row-working_hours">
                <th scope="row">
                    <label for="working_hours">Рабочие часы</label>
                </th>
                <td>
                    <input type="text" name="working_hours" id="working_hours" class="regular-text"
                           value="" required="required"/>
                </td>
            </tr>
            </tbody>
        </table>

        <input type="hidden" name="field_id" value="0">

        <?php wp_nonce_field('bm_new_ticket_window'); ?>
        <?php submit_button('Сохранить', 'primary', 'submit_ticket_window'); ?>

    </form>
</div>