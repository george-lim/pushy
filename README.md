Pushy
<br>
![GitHub downloads](https://img.shields.io/github/downloads/george-lim/pushy/total.svg)
[![GitHub release](https://img.shields.io/github/release/george-lim/pushy.svg)](https://github.com/george-lim/pushy/releases)
[![GitHub issues](https://img.shields.io/github/issues/george-lim/pushy.svg)](https://github.com/george-lim/pushy/issues)
[![GitHub pull requests](https://img.shields.io/github/issues-pr/george-lim/pushy.svg)](https://github.com/george-lim/pushy/pulls)
[![license](https://img.shields.io/github/license/george-lim/pushy.svg)](https://github.com/george-lim/pushy/blob/master/LICENSE)
===============

This project is designed to act as an ideal template for integrating push notifications through Apple Push Notification Server (APNs) to a Swift iOS client. It highlights the relationship between the client and the push notification provider, and how APNs works to send notifications to the user.

1. [Introduction](#introduction)
1. [Design](#design)
1. [Installation](#installation)

# Introduction
Push Notifications have always been difficult to deal with on iOS. That's because unlike local notification logic, push notification logic is not handled client-side, and is instead handled server-side. This makes sense, because apps like Facebook Messenger don't have to run (even in the background) for a push notification to be received. Instead, Apple has a service called APNs which allows providers to send push notifications to devices whose APNs token it has access to.

The end goal was simple. [MapleStory 2](http://maplestory2.nexon.net/en) was coming out and it had a launch event that guaranteed closed beta access to the first 10000 people that completed a series of marketing activities. Activities came out at seemingly random periods of the day, so I knew I needed to make a push notification app to get alerts for the next activity.

# Design
There are two fundamental components to setting up push notifications on an app, the provider and the client.

## The Client
The client logic should be dead simple. Request notification permissions from the user and allow the user to copy their APNs device token to clipboard if the permission is granted, else display a prompt to enable push notifications. I am planning to hard-code device tokens to the provider to send push notifications to my device specifically. If the provider is a remote web server, you can look into posting a request to your provider with the device token to dynamically "subscribe" to push notifications.

## The Provider
In my case, I want the provider to continuously check MapleStory 2's servers for any updates to the next activity, and send a push notification to every subscribed device when the update is ready. I've written [Pushy Provider](https://github.com/george-lim/pushy/blob/master/pushy-provider/) as a PHP `Cron` job to accomplish just that. Pushy Provider simply scrapes a URL for specific HTML changes and connects to APNs to send the push notification to every Pushy User (stored as a PHP array of device tokens). 

# Installation
Follow the [Pushy Provider](https://github.com/george-lim/pushy/blob/master/pushy-provider/) guide to install the provider. Compile the iOS client on your device using Xcode.
