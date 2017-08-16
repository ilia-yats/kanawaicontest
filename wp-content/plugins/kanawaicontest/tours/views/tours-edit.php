<?php $item = $this->tours_list->get_tour_with_images($id); ?>
<div class="wrap">
    <h1>Edit tour</h1>
    <form action="" method="post">
        <table class="form-table">
            <tbody>
            <tr class="row-city_id">
                <th scope="row">
                    <label for="title">Title</label>
                </th>
                <td>
                    <input type="text" name="title" id="title" class="regular-text"
                           value="<?php echo $item['title'] ?>" required="required"/>
                </td>
            </tr>
            <tr class="row-name">
                <th scope="row">
                    <label for="start_date">Start Date</label>
                </th>
                <td>
                    <input type="text" name="start_date" id="start_date" class="regular-text"
                           value="<?php echo $item['start_date'] ?>" required="required"/>
                </td>
            </tr>
            <tr class="row-name">
                <th scope="row">
                    <label for="end_date">End Date</label>
                </th>
                <td>
                    <input type="text" name="end_date" id="end_date" class="regular-text"
                           value="<?php echo $item['end_date'] ?>" required="required"/>
                </td>
            </tr>
            </tbody>
        </table>
        <input type="hidden" name="field_id" value="<?php echo $item['id']; ?>">
        <?php wp_nonce_field('kanawaicontest_new_tour'); ?>
        <?php submit_button('Save', 'primary', 'submit_tour'); ?>
    </form>

</div>