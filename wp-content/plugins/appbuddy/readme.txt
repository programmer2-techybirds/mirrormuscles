=== AppBuddy ===
Contributors: apppresser, webdevstudios, williamsba1, scottopolis, jtsternberg, Messenlehner, LisaSabinWilson, modemlooper, stillatmylinux
Donate link: http://apppresser.com/
Tags: mobile, app, ios, android, application, phonegap, iphone app, android app, mobile app, native app, wordpress mobile, ipad app, iOS app, buddypress
Requires at least: 3.5
Tested up to: 4.7.5
Stable tag: 3.2.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

== Description ==

Tie your BuddyPress community into a WordPress powered smartphone app.

== Installation ==

1. Upload AppBuddy to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. You will now find a new "AppBuddy" tab in your AppPresser settings.

Please visit there to configure the extension's settings.

== Changelog ==

= 3.2.1 =
* Send login info to the app to fix notifications for private messages

= 3.2.0 =
* Add admin setting to force login
* Allow translation for the appbuddy.js

= 3.1.0 =
* Add feature to use /me/ for directs for BuddyPress URLs with usernames

= 3.0.0 =
* Add admin setting to skip the activation by email step of the registration process

= 2.2.1 =
* Don’t send message if no devices

= 2.2.0 =
* view avatars and dynamic modals
* fix: app not reloading on successful registration

= 2.1.2 =
* Fix HTML template to compose private messages missing closing label tag

= 2.1.1 =
* Fix HTML template to compose private messages

= 2.1.0 =
* Add filter to allow developers to remove modal button
* Fix iOS keyboard issues caused by using focus()
* Fix js that made it appear user's could post activity to other pages
* Fix home URL for WP installed in a directory
* Misc. fixes related to bp-legacy templates

= 2.0.7 =
* remove iframe.wp-embedded-content from activity
* adjust embed iframe width if necessary
* fix BP 2.5 messaging deprecation

= 2.0.6 =
* Fix iOS keyboard, text and textarea bugs
* Fix email bug by adding BuddyPress 2.5 email template

= 2.0.5 =
* Fix cover images by adding required BuddyPress templates
* Add msg to comment modal when BuddyPress Activity Stream is not enabled

= 2.0.4 =
* Add APv1 only logic for URLs in BuddyPress blogs

= 2.0.3 =
* Fix customizer's background image for the login screen

= 2.0.2 =
* Bug fix for Ion theme and the customizer

= 2.0.1 =
* Bug fix causing fatal error when Activity Stream is disabled in BuddyPress

= 2.0.0 =
* Add compatibility with Apppresser 2.0.0 moving cordova files to device
* Bug fix to not disable the post activity button
* Add filter to modal button and login

= 0.9.9 =
* Fix redirecting to login page and use modal instead
* Add nonce for uploading activiy image
* Update uploading avatar using new buddypress attachment api

= 0.9.8 =
* Bug fix: Don't disable the activity submit button

= 0.9.7 =
* Login form hooks to support new Facebook Login Extension

= 0.9.6 =
* New feature: don't force login on first screen by adding: define( 'APPP_REMOVE_LOGIN', true ); to wp-config.php
* Security patch for add_query_arg
* Bug fixes

= 0.9.5 =
* Lots of bug fixes: fix image posting, android compatibility, upload progress indicator, and more
* Update to work with newest version of Phonegap.

= 0.9.4 =
* Updated javascript files for current version of BuddyPress
* Fixed issue when site was in sub directory and login redirected to base URL
* Fixed issue when Woocommerce activated and Activity stream failed to return results
* Updated textdomains for translating

= 0.9.3 =
* Push notifications support for private messages, friend requests, and mentions

= 0.9.2 =
* Visual enhancements

= 0.9.1 =
* Bug fixes and enhancements
