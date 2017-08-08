<div class="wrap">
    <h1>Добавить клиента</h1>

    <form action="" method="post">

        <table class="form-table">
            <tbody>
            <tr class="row-name">
                <th scope="row">
                    <label for="name">Имя</label>
                </th>
                <td>
                    <input type="text" name="name" id="name" class="regular-text"
                           value="" />
                </td>
            </tr>

            <tr class="row-last_name">
                <th scope="row">
                    <label for="last_name">Фамилия</label>
                </th>
                <td>
                    <input type="text" name="last_name" id="last_name" class="regular-text"
                           value="" />
                </td>
            </tr>

            <tr class="row-email">
                <th scope="row">
                    <label for="email">E-mail </label>
                </th>
                <td>
                    <input type="email" name="email" id="email" class="regular-text"
                           value="" />
                </td>
            </tr>
            <tr class="row-phone">
                <th scope="row">
                    <label for="phone">Телефон </label>
                </th>
                <td>
                    <input type="text" name="phone" id="phone" class="regular-text"
                           value="" />
                </td>
            </tr>
            <tr class="row-is_blacklisted">
                <th scope="row">
                    <label for="is_blacklisted">Поместить в черный список</label>
                </th>
                <td>
                    <input type="checkbox" name="is_blacklisted" id="is_blacklisted" style="margin-right:10px;"/>
                </td>
            </tr>
            </tbody>
        </table>

        <input type="hidden" name="field_id" value="0">

        <?php wp_nonce_field('bm_new_client'); ?>
        <?php submit_button('Сохранить', 'primary', 'submit_client'); ?>

    </form>
</div>