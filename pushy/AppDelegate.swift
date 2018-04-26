//
//  AppDelegate.swift
//  pushy
//
//  Created by George Lim on 2018-04-19.
//  Copyright © 2018 George Lim. All rights reserved.
//

import UIKit
import UserNotifications

@UIApplicationMain
class AppDelegate: UIResponder, UIApplicationDelegate {

  var window: UIWindow?

  private func registerForRemoteNotifications(completion: ((Bool) -> Void)?) {
    UNUserNotificationCenter.current().requestAuthorization(options: [.badge, .alert, .sound]) { (granted, error) in
      guard granted else {
        completion?(false)
        return
      }
      UNUserNotificationCenter.current().getNotificationSettings { (settings) in
        guard settings.authorizationStatus == .authorized else {
          completion?(false)
          return
        }
        DispatchQueue.main.async { UIApplication.shared.registerForRemoteNotifications() }
        completion?(true)
      }
    }
  }

  func application(_ application: UIApplication, didFinishLaunchingWithOptions launchOptions: [UIApplicationLaunchOptionsKey: Any]?) -> Bool {
    UIApplication.shared.applicationIconBadgeNumber = 0
    registerForRemoteNotifications { success in
      DispatchQueue.main.async {
        guard let rootViewController = self.window?.rootViewController as? ViewController, !success else { return }
        rootViewController.actionLabel.text = "(Enable push notifications)"
      }
    }
    return true
  }

  func applicationWillEnterForeground(_ application: UIApplication) {
    UIApplication.shared.applicationIconBadgeNumber = 0
  }

  func application(_ application: UIApplication, didRegisterForRemoteNotificationsWithDeviceToken deviceToken: Data) {
    let deviceToken = deviceToken.map { return String(format: "%02.2hhx", $0) }.joined()
    guard let rootViewController = window?.rootViewController as? ViewController else { return }
    rootViewController.deviceToken = deviceToken
    rootViewController.actionLabel.text = "(Tap to copy device token)"
    rootViewController.deviceTokenLabel.text = "<\(deviceToken)>"
  }
}
