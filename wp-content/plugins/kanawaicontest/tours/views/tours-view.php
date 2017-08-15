<?php $item = $this->tours_list->get_tour_with_images($id); ?>
<div class="wrap">
    <h1><?php _e('View tour') ?></h1>
    <p>

    <?php echo sprintf('<a href="?page=%s&action=%s&tour_id=%d" class="add-new-h2">Images</a>', 'kanawaicontest_images', 'list', $id); ?>
    <?php echo sprintf('<a href="?page=%s&action=%s&tour_id=%d" class="add-new-h2">Voters</a>', 'kanawaicontest_voters', 'list', $id); ?>
    </p>
    <table class="form-table">
        <tbody>
            <tr class="row-city_id">
                <th scope="row"><?php _e('Title') ?></th>
                <td><?php echo $item['title'] ?></td>
            </tr>
            <tr class="row-name">
                <th scope="row"><?php _e('Start Date') ?></th>
                <td><?php echo $item['start_date'] ?></td>
            </tr>
            <tr class="row-name">
                <th scope="row"><?php _e('End Date') ?></th>
                <td><?php echo $item['end_date'] ?></td>
            </tr>
        </tbody>
    </table>
</div>