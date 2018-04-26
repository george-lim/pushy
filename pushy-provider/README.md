Pushy Provider
===============

An Apple Push Notification Service (APNs) provider to send push notifications to the Pushy client. Pushy Provider is currently configured as a `Cron` task to continuously check for updated MapleStory 2 Road Map activities. A push notification is sent to every Pushy user when a new activity is found.

1. [Features](#features)
1. [Setup](#setup)
1. [Gateways](#gateways)

# Features
* `APNProvider` - A PHP extension which allows push notifications to be sent.
* `Cookie` - A PHP extension that allows retrieving / storing a dictionary of data into a `.cookie` file for data persistance.
* `URLDataTracker` - A PHP extension that tracks specific HTML changes in a URL.
* `console_push($message)` - A PHP extension method that sends debug output via push notifications to Pushy users.
* `MS2ActivityTracker` - A PHP extension built on top of `URLDataTracker` to send push notifications to Pushy users when a new MapleStory 2 Road Map activity is available.

# Setup

## Apple Push Notification Setup
1. Download APNs certificate from `developer.apple.com` -> `cert.cer`
2. Import certificate to `Keychain Access`.
3. Export private key inside certificate, using the export and PEM passphrase `Pushy` -> `key.p12`
4. Open `Terminal` and enter the following commands:
     ```
     openssl x509 -inform der -outform pem -in cert.cer -out cert.pem
     openssl pkcs12 -in key.p12 -out key.pem -nocerts
     cat cert.pem key.pem > pushy-ck.pem
     openssl s_client -connect gateway.push.apple.com:2195 -cert cert.pem -key key.pem
     ```
5. Send `pushy-ck.pem` path and the `Pushy` PEM passphrase to `PushyAPNProvider.php`.

## crontab Setup
Enter `crontab -e` in terminal and add the following:
```
* * * * * php PATH_TO_CRON_PHP >/dev/null 2>&1
```

## PushyUsers.php Setup
To get `MS2ActivityTracker.php` or `console-push.php` to work, a `PushyUsers.php` file must be created with the PHP constants `ALL_USERS` and `ALL_DEVELOPERS` and added to the `pushy-provider` folder. `ALL_USERS` is an array of APN device tokens that represent all users on the server. Similarly, `ALL_DEVELOPERS` contains the developer device tokens. A sample `PushyUsers.php` is provided below.

```
// PushyUsers.php
<?php
$georgeToken = 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx';
$jackieToken = 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx';
$belindaToken = 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx';

define('GEORGE_ONLY', array($georgeToken));
define('JACKIE_ONLY', array($jackieToken));
define('BELINDA_ONLY', array($belindaToken));
define('ALL_DEVELOPERS', array($georgeToken));
define('ALL_USERS', array($georgeToken, $jackieToken, $belindaToken));
?>
```

# Gateways
APNs provides two gateways that you can connect to in order to send push notifications. They include:
```
gateway.sandbox.push.apple.com:2195 (for sandbox development)
gateway.push.apple.com:2195 (for production)
```

Fortunately, using the production gateway for the certification key file (`pushy-ck.pem`) means that **ALL** devices (sandbox / production) are **allowed** to be sent push notifications. So as far as the certification key file is concerned, we only need one file to handle both sandbox / production devices.

However this is not the case for `APNProvider`, which requires the correct gateway to be sent for each device token in order for the device to receive notifications. If the device is compiled in debug mode, it has a sandbox device token (that is indistinguishable but different from the production token) so you must send the push notification with the `useSandbox` flag enabled. Unfortunately, it is impossible for `APNProvider` to automatically detect whether a device token is sandbox or production so you will need to manually keep track of that.