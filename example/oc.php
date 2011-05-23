<?php
require_once '../OgreCounter.php';
define('COUNTER_FILE', '../counter.txt');

$oc = new OgreCounter(COUNTER_FILE);
$oc->countUp($_GET['dispcount']);
$oc->displayCount();