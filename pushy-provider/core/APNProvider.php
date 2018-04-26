<?php

// APNs Gateways
define('GATEWAY_SANDBOX', 'ssl://gateway.sandbox.push.apple.com:2195');
define('GATEWAY_PRODUCTION', 'ssl://gateway.push.apple.com:2195');

// A push notification to be sent to specific devices
class APN {

  var $deviceTokens;
  var $payload;

  function __construct($deviceTokens, $title, $message, $badgeCount, $sound) {
    $this->deviceTokens = $deviceTokens;
    $body['aps'] = array('sound' => $sound);
    if (!is_null($badgeCount)) $body['aps']['badge'] = $badgeCount;
    $body['aps']['alert'] = $title !== '' ? array('title' => $title, 'body' => $message) : $message;
    $this->payload = json_encode($body);
  }
}

// An APNs provider that allows push notifications to be sent
class APNProvider {

  var $certPath;
  var $passphrase;

  function __construct($certPath, $passphrase) {
    $this->certPath = $certPath;
    $this->passphrase = $passphrase;
  }

  function push($apn, $useSandbox) {
    $sslGateway = $useSandbox ? GATEWAY_SANDBOX : GATEWAY_PRODUCTION;
    $deviceTokens = $useSandbox ? array_intersect($apn->deviceTokens, ALL_DEVELOPERS) : $apn->deviceTokens;

    // Connect to APNs
    $ctx = stream_context_create();
    stream_context_set_option($ctx, 'ssl', 'local_cert', $this->certPath);
    stream_context_set_option($ctx, 'ssl', 'passphrase', $this->passphrase);
    $fp = @stream_socket_client($sslGateway, $err, $errstr, 60, STREAM_CLIENT_CONNECT, $ctx);
    if (!$fp) throw new Exception("Failed to connect to $sslGateway ($err, $errstr)");

    // Build each notification and send to APNs
    foreach ($deviceTokens as $deviceToken) {
      $msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($apn->payload)) . $apn->payload;
      fwrite($fp, $msg, strlen($msg));
    }

    // Close connection to APNs
    fclose($fp);
  }

  function pushToAll($apn) {
    try {
      $this->push($apn, FALSE);
      $this->push($apn, TRUE);
    } catch (Exception $e) { throw $e; }
  }
}
?>
