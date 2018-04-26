<?php
require_once 'PushyAPNProvider.php';

// Sends a push notification to all developers with the specified $message
function console_push($message) {
  $apnProvider = new PushyAPNProvider();
  $apn = new APN(ALL_DEVELOPERS, 'Console', $message, NULL, 'default');
  try {
    $apnProvider->push($apn, TRUE);
    echo "Successfully pushed notification.\n";
  } catch (Exception $e) {
    echo "Failed to push notification.\n";
    echo 'Caught exception: ', $e->getMessage(), "\n";
  }
}
?>
