<div class="wrap">
    <h1>Add image</h1>

    <?php wp_enqueue_media(); ?>

    <div class='image-preview-wrapper'>
        <img id='image-preview' src='' width='400' height='400' style='max-height: 400px; width: 400px;'>
    </div>
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
<!--            <tr class="row-name">-->
<!--                <th scope="row">-->
<!--                    <label for="description">Description</label>-->
<!--                </th>-->
<!--                <td>-->
<!--                    <input type="text" name="description" id="description" class="regular-text"-->
<!--                           value="" required="required"/>-->
<!--                </td>-->
<!--            </tr>-->
            <tr class="row-name">
                <th scope="row">
                    <label for="image_file">Image File</label>
                </th>
                <td>
                    <input id="upload_image_button" type="button" class="button" value="<?php _e( 'Select image' ); ?>" />
                </td>
            </tr>
            </tbody>
        </table>

        <?php wp_nonce_field('kanawaicontest_new_image'); ?>
        <?php submit_button('Save', 'primary', 'submit_image'); ?>

        <input type='hidden' name='tour_id' id='tour_id' value='<?php echo absint($_REQUEST['tour_id']) ?>'>
        <input type='hidden' name='image_attachment_id' id='image_attachment_id' value=''>
    </form>
</div>


