//
//  ViewController.swift
//  pushy
//
//  Created by George Lim on 2018-04-19.
//  Copyright © 2018 George Lim. All rights reserved.
//

import UIKit

class ViewController: UIViewController {

  @IBOutlet weak var actionLabel: UILabel!
  @IBOutlet weak var deviceTokenLabel: UILabel!
  var deviceToken: String?

  override func viewDidLoad() {
    super.viewDidLoad()
    setupGestureRecognizer()
  }

  override var prefersStatusBarHidden: Bool {
    return true
  }

  private func setupGestureRecognizer() {
    let tapGestureRecognizer = UITapGestureRecognizer(target: self, action: #selector(handleTap))
    view.addGestureRecognizer(tapGestureRecognizer)
  }

  @objc private func handleTap() {
    guard let deviceToken = deviceToken else { return }
    UIPasteboard.general.string = deviceToken
    let alertController = UIAlertController(title: "Device Token", message: "Copied to clipboard!", preferredStyle: .alert)
    let dismissAction = UIAlertAction(title: "OK", style: .default, handler: nil)
    alertController.addAction(dismissAction)
    present(alertController, animated: true, completion: nil)
  }
}
