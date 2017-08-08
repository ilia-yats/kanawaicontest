
<div class='webcam-upload-container'>
    <?php foreach ($images as $name=>$image): ?>
       <div class='image-container'>
            <img  src="<?php echo $image ?>">
            <div class='title'><?php echo $name ?></div>
       </div>

    <?php endforeach; ?>
</div>
