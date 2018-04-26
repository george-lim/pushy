<?php
require_once 'core/APNProvider.php';
require_once 'PushyUsers.php';

class PushyAPNProvider extends APNProvider {
  function __construct() {
    $this->certPath = '';
    $this->passphrase = '';
  }
}
?>
