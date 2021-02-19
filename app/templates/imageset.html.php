<?php $i = 0; ?>
<p class="lead"><?= $theRound->legend ?></p>
<?php foreach ($theQuestions as $q) : ?>
<div><?= ++$i ?></div>
<div><img src="<?= $q ?>" class="img-fluid" alt="If this image is not visible, please notify the prep monitor."></div>
<?php endforeach; ?>