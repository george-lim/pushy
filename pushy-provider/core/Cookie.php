<?php

// Cookie dictionary key / value delimiter
define('COOKIE_DELIMITER', '=>');

// Allows dictionary data persistence using File IO
class Cookie {

  var $file;
  var $dataDict;

  function __construct($file) {
    $this->file = $file;
    $this->dataDict = array();
    $this->import(@file_get_contents($file));
  }

  private function import($fileContents) {
    $cookieEntries = explode("\n", $fileContents);

    foreach ($cookieEntries as $entry) {
      if (empty($entry)) continue;
      $keyValuePair = explode(COOKIE_DELIMITER, $entry);
      $this->dataDict[$keyValuePair[0]] = $keyValuePair[1];
    }
  }

  private function export() {
    $fileContents = '';
    foreach ($this->dataDict as $key => $value) {
      if (empty($key)) continue;
      $fileContents .= $key . COOKIE_DELIMITER . $value . "\n";
    }

    $fileDir = dirname($this->file);
    if (!is_dir($fileDir)) mkdir(dirname($this->file));
    @file_put_contents($this->file, $fileContents);
  }

  function get($key) {
    return $this->dataDict[$key];
  }

  function store($key, $value) {
    if (strpos($key, COOKIE_DELIMITER) !== false || strpos($key, COOKIE_DELIMITER) !== false) {
      throw new Exception('Key or Value contains cookie delimiter (=>).');
    }

    $this->dataDict[$key] = $value;
    $this->export();
  }
}
?>
