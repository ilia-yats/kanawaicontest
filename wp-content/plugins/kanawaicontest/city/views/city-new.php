<div class="wrap">
    <h1>Добавить город</h1>

    <form action="" method="post">

        <table class="form-table">
            <tbody>
            <tr class="row-name">
                <th scope="row">
                    <label for="name">Название</label>
                </th>
                <td>
                    <input type="text" name="name" id="name" class="regular-text"
                           value="" required="required"/>
                </td>
            </tr>

            <tr class="row-phone">
                <th scope="row">
                    <label for="phone">Телефоны в заголовке сайта</label>
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
            </tbody>
        </table>

        <input type="hidden" name="field_id" value="0">

        <?php wp_nonce_field('bm_new_city'); ?>
        <?php submit_button('Сохранить', 'primary', 'submit_city'); ?>

    </form>
</div>