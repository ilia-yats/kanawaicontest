<?php /** @var array $item */ ?>
<div class="wrap">
    <h1>Edit tour</h1>
    <p>
        <?php
        if ($item['status'] != 'archived'):
            echo sprintf('<a href="?page=%s&action=%s&id=%d" id="archive-tour-btn" class="add-new-h2">Archive tour</a>', 'kanawaicontest', 'archive', $id);
        endif;
        if ($item['status'] != 'active'):
            echo sprintf('<a href="?page=%s&action=%s&id=%d" id="activate-tour-btn" class="add-new-h2">Activate tour</a>', 'kanawaicontest', 'activate', $id);
        endif;
        ?>
    </p>
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
            <!--            <tr class="row-name">-->
            <!--                <th scope="row">-->
            <!--                    <label for="start_date">Start Date</label>-->
            <!--                </th>-->
            <!--                <td>-->
            <!--                    <input type="text" name="start_date" id="start_date" class="regular-text"-->
            <!--                           value="--><?php //echo $item['start_date'] ?><!--" required="required"/>-->
            <!--                </td>-->
            <!--            </tr>-->
            <!--            <tr class="row-name">-->
            <!--                <th scope="row">-->
            <!--                    <label for="end_date">End Date</label>-->
            <!--                </th>-->
            <!--                <td>-->
            <!--                    <input type="text" name="end_date" id="end_date" class="regular-text"-->
            <!--                           value="--><?php //echo $item['end_date'] ?><!--" required="required"/>-->
            <!--                </td>-->
            <!--            </tr>-->
            </tbody>
        </table>
        <input type="hidden" name="field_id" value="<?php echo $item['id']; ?>">
        <?php wp_nonce_field('kanawaicontest_new_tour'); ?>
        <?php submit_button('Save', 'primary', 'submit_tour'); ?>
    </form>

</div>