<div class="wrap">
    <h2><?php _e('Kanawai Contest settings'); ?></h2>

    <form method="post" action="options.php">
        <?php settings_fields('kanawaicontest_settings'); ?>
        <table class="form-table">
            <tr valign="top">
                <th scope="row"><b><?php _e('Show "Contest" pop-up button on all pages') ?></b></th>
                <td>
                    <input type="checkbox" name="kanawaicontest_button_activated" <?php echo get_option('kanawaicontest_button_activated') ? ' checked ' : ''; ?> value="1"/>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><b><?php _e('Main banner') ?></b></th>
                <td>
                    <div class='image-preview-wrapper'>
                        <img id='image-preview' src='<?php echo wp_get_attachment_image_src(get_option('kanawaicontest_main_banner_attachment_id'), 'full')[0]; ?>' width='400' height='400' style='max-height: 400px; width: 400px;'>
                    </div>
                    <input id="upload_image_button" type="button" class="button" value="<?php _e('Select image'); ?>"/>
                    <input type='hidden' name='kanawaicontest_main_banner_attachment_id' id='image_attachment_id' value='<?php echo get_option('kanawaicontest_main_banner_attachment_id'); ?>'>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">
                    <p><b><?php _e('Contest finished notification') ?></b></p>
                </th>
                <td>
                    <?php
                    wp_editor(
                        get_option('kanawaicontest_contest_finished_message'),
                        'kanawaicontest_contest_finished_message_editor',
                        array(
                            'textarea_name' => 'kanawaicontest_contest_finished_message',
                            'media_buttons' => FALSE,
                        )
                    );
                    ?>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">
                    <p><b><?php _e('Rules under main banner') ?></b></p>
                </th>
                <td>
                    <?php
                    wp_editor(
                        get_option('kanawaicontest_rules'),
                        'kanawaicontest_rules_editor',
                        array(
                            'textarea_name' => 'kanawaicontest_rules',
                            'media_buttons' => FALSE,
                        )
                    );
                    ?>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">
                    <p><b><?php _e('Official terms and conditions') ?></b></p>
                </th>
                <td>
                    <?php
                    wp_editor(
                        get_option('kanawaicontest_terms_and_conditions'),
                        'kanawaicontest_terms_and_conditions_editor',
                        array(
                            'textarea_name' => 'kanawaicontest_terms_and_conditions',
                            'media_buttons' => FALSE,
                        )
                    );
                    ?>
                </td>
            </tr>
        </table>

        <p class="submit"><input type="submit" class="button-primary" value="<?php _e('Save changes') ?>"/></p>

    </form>
</div>