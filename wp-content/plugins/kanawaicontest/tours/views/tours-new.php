<div class="wrap">
    <h1>Add tour</h1>
    <form action="" method="post">
        <table class="form-table">
            <tbody>
            <tr class="row-city_id">
                <th scope="row">
                    <label for="title">Title</label>
                </th>
                <td>
                    <input type="text" name="title" id="title" class="regular-text"
                           value="" required="required"/>
                </td>
            </tr>
            </tbody>
        </table>
        <?php wp_nonce_field('kanawaicontest_new_tour'); ?>
        <?php submit_button('Save', 'primary', 'submit_tour'); ?>
    </form>
</div>