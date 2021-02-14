<?php $i = 0; ?>
<div><!-- lead text --></div>
<?php foreach ($questions as $q) : ?>
<div><?= ++$i ?></div>
<div><img src="<?= $q ?>" class="img-fluid" alt="If this image is not visible, please notify the prep monitor."></div>
<?php endforeach; ?>