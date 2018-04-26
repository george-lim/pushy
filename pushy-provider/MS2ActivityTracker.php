<?php
require_once 'core/URLDataTracker.php';
require_once 'PushyAPNProvider.php';
require_once 'console-push.php';

class MS2ActivityTrackerAPN extends APN {
  function __construct() {
    $title = 'MapleStory 2';
    $message = 'Your next Road Map activity is ready!';
    parent::__construct(ALL_USERS, $title, $message, NULL, 'default');
  }
}

class MS2ActivityTracker extends URLDataTracker {
  function __construct($uid, $roadTripUID) {
    $this->cookie = new Cookie(__DIR__ . DIRECTORY_SEPARATOR . "cookies/road-map-activity-$uid.cookie");
    $this->url = "http://maplestory2.nexon.net/en/microsite/roadtrip/$roadTripUID/";
    $this->data = 'Not Found!';
    $this->apnProvider = new PushyAPNProvider();
    $this->apn = new MS2ActivityTrackerAPN();
  }

  function run() {
    // console_push('Checking event...');
    parent::run();
  }
}
?>
