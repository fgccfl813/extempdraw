<?php
include_once TPL . '/header.html.php';
$flash->publish();
if (isset($timeout) && intval($timeout) > 0) include TPL . '/timeout.html.php';
require_once TPL . '/footer.html.php';
require_once APP . '/finish.php';