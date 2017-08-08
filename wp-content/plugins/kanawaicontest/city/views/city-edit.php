<div class="wrap">
    <h1>Редактирование города</h1>

    <?php $item = $this->city_obj->get_city($id); ?>

    <form action="" method="post">
        <table class="form-table">
            <tbody>
            <tr class="row-name">
                <th scope="row">
                    <label for="name">Название</label>
                </th>
                <td>
                    <input type="text" name="name" id="name" class="regular-text"
                           value="<?php echo esc_attr($item->name); ?>" required="required"/>
                </td>
            </tr>

            <?php
            $first_phone = array_shift($item->phones);
            ?>
            <tr class="row-phone">
                <th scope="row">
                    <label for="phone">Телефоны в заголовке сайта</label>
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
            </tbody>
        </table>

        <input type="hidden" name="field_id" value="<?php echo $item->id; ?>">

        <?php wp_nonce_field('bm_new_city'); ?>
        <?php submit_button('Сохранить', 'primary', 'submit_city'); ?>

    </form>
</div>