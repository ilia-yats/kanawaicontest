<div class="wrap">
    <h1><?php _e('Add image') ?></h1>

    <?php wp_enqueue_media(); ?>

    <div class='image-preview-wrapper'>
        <img id='image-preview' src='' width='400' height='400' style='max-height: 400px; width: 400px;'>
    </div>
    <form action="" method="post">
        <table class="form-table">
            <tbody>
            <tr class="row-city_id">
                <th scope="row">
                    <label for="title"><?php _e('Title') ?></label>
                </th>
                <td>
                    <input type="text" name="title" id="title" class="regular-text"
                           value="" required="required"/>
                </td>
            </tr>
            <tr class="row-name">
                <th scope="row">
                    <label for="link"><?php _e('Link') ?></label>
                </th>
                <td>
                    <input type="text" name="link" id="link" class="regular-text"
                           value="" required="required"/>
                </td>
            </tr>
            <tr class="row-name">
                <th scope="row">
                    <label for="image_file"><?php _e('Image File') ?></label>
                </th>
                <td>
                    <input id="upload_image_button" type="button" class="button" value="<?php _e('Select image'); ?>"/>
                </td>
            </tr>
            </tbody>
        </table>

        <?php wp_nonce_field('kanawaicontest_new_image'); ?>
        <?php submit_button(__('Save'), 'primary', 'submit_image'); ?>

        <input type='hidden' name='image_attachment_id' id='image_attachment_id' value=''>
    </form>
</div>


