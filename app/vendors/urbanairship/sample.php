<?php
require_once 'urbanairship.php';
// Your testing data
$APP_MASTER_SECRET = '';
$APP_KEY = '';
$TEST_DEVICE_TOKEN = '';
// Create Airship object
$airship = new Airship($APP_KEY, $APP_MASTER_SECRET);
?>
