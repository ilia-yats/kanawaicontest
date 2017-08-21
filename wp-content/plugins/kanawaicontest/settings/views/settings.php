<div class="wrap">
    <h2><?php _e('Kanawai Contest settings'); ?></h2>

    <form method="post" action="options.php">
        <?php settings_fields('kanawaicontest_settings'); ?>
<!--        --><?php //var_dump(get_option('kanawaicontest_is_active')); wp_die();?>
        <table class="form-table">
            <tr valign="top">
                <th scope="row"><b><?php _e('Is activated') ?></b></th>
                <td>
                    <input type="checkbox" name="kanawaicontest_is_active" <?php echo get_option('kanawaicontest_is_active') ? 'checked' : ''; ?> value="1"/>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">
                    <p><b><?php _e('Contest rules') ?></b></p>
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
        </table>

        <p class="submit"><input type="submit" class="button-primary" value="<?php _e('Save changes') ?>"/></p>

    </form>
</div>