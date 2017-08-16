<?php /**
 * @var array $item
 * @var bool $is_current
 */ ?>
<div class="wrap">
    <?php if ($is_current): ?>
    <h1><?php _e('Currently active tour') ?></h1>
    <?php else: ?>
    <h1><?php _e('View tour') ?></h1>
    <?php endif; ?>
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
        <?php if (!$is_current) : ?>
            <tr class="row-name">
                <th scope="row"><?php _e('End Date') ?></th>
                <td><?php echo $item['end_date'] ?></td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>