<?php
require_once 'MS2ActivityTracker.php';

define('IS_ENABLED', TRUE);

if (!IS_ENABLED) return;
$stage = 1;
$tracker = new MS2ActivityTracker($stage, 28760 + $stage);
$tracker->run();
?>
