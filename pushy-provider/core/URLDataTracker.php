<?php
require_once 'APNProvider.php';
require_once 'Cookie.php';

define('URLDATATRACKER_ISDATAINURL', 'isDataInURL');

class URLDataTracker {

  var $cookie;
  var $url;
  var $data;
  var $apnProvider;
  var $apn;

  function __construct($cookie, $url, $data, $apnProvider, $apn) {
    $this->cookie = $cookie;
    $this->url = $url;
    $this->data = $data;
    $this->apnProvider = $apnProvider;
    $this->apn = $apn;
  }

  // Checks if $this->data exists inside $this->url
  private function checkDataInURL() {
    $html = @file_get_contents($this->url);
    if ($html === FALSE) throw new Exception('Unable to get html from URL.');
    return strpos($html, $this->data) !== FALSE;
  }

  // Checks if $this->checkDataInURL() status has changed from the the last known status stored in the cookie
  private function hasURLDataChanged() {
    $isDataInURL = filter_var($this->cookie->get(URLDATATRACKER_ISDATAINURL), FILTER_VALIDATE_BOOLEAN);
    try {
      $dataCompareResult = $isDataInURL !== $this->checkDataInURL();
      echo "Successfully performed data check.\n";
      return $dataCompareResult;
    } catch (Exception $e) {
      echo "Failed to perform data check.\n";
      throw $e;
    }
  }

  // Stores the current $this->checkDataInURL() status to a cookie
  private function storeCookie() {
    try {
      $this->cookie->store(URLDATATRACKER_ISDATAINURL, $this->checkDataInURL() ? 'TRUE' : 'FALSE');
      echo "Successfully stored cookie.\n";
    } catch (Exception $e) {
      echo "Failed to store cookie.\n";
      throw $e;
    }
  }

  // Sends a push notification to all Pushy users
  private function sendAPN() {
    try {
      $this->apnProvider->pushToAll($this->apn);
      echo "Successfully pushed notification.\n";
    } catch (Exception $e) {
      echo "Failed to push notification.\n";
      throw $e;
    }
  }

  // Calls $this->hasURLDataChanged() and sends a push notification if the status has changed
  function run() {
    try {
      if (empty($this->cookie->get(URLDATATRACKER_ISDATAINURL))) {
        $this->storeCookie();
        return;
      }

      if ($this->hasURLDataChanged()) {
        $this->storeCookie();
        $this->sendAPN();
      }
    } catch (Exception $e) {
      echo 'Caught exception: ', $e->getMessage(), "\n";
    }
  }
}
?>
