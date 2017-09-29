=== AppPush ===
Contributors: apppresser, webdevstudios, williamsba1, scottopolis, jtsternberg, Messenlehner, LisaSabinWilson, modemlooper, stillatmylinux
Author URI: http://apppresser.com
Requires at least: 3.5
Tested up to: 4.7.5
Stable tag: 3.2.0
License: General Public License
License URI: http://www.gnu.org/licenses/gpl-2.0.html

== Description ==
Stay in touch with your app users by sending push notifications! Push notifications are kind of like text messages that you send to anyone with your app.

== Installation ==

1. Upload AppPush to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. You will now find a new "Notifications" tab in your AppPresser settings.

Please visit there to configure the AppPush extension settings.

== Changelog ==

= 3.2.0 =
* Add segments to selected post types
* Add v2 only settings tab

= 3.1.2 =
* Fix error with old PHP versions

= 3.1.1 =
* Check meta option for sending notifications when saving posts
* Fix error with old PHP versions

= 3.1.0 =
* Add segments
* Fix sending notifications from other post types
* Use target _self if the URL from AppPush matches the WordPress URL

= 3.0.2 =
* Fix Custom URLs (deeplinking) in notifications from AppPresser admin
* Fix adding device ids on user login (fix for messaging in AppBuddy)

= 3.0.1 =
* Fix URL for page menu items by not using esc_url on page names

= 3.0.0 =
* AppPresser 3.0 settings and compatibility.

= 2.4.2 =
* Fix nonce issue with apppush custom post type

= 2.4.1 =
* Add a db version check

= 2.4.0 =
* Add notification segments
* Default disable custom URLs
* Add admin setting for free PushWoosh accounts

= 2.3.1 =
* Fix post type sending notifications

= 2.3.0 =
* Notifications with Ajax URLs

= 2.2.0 =
* Save multiple device IDs per user
* Fix misc errors

= 2.1.1 =
* Add the ability to push notifications when a post/page/cpt transitions from scheduled to publish.

= 2.1.0 =
* Add custom URL link for push content

= 2.0.0 =
* Localize JavaScript in preparation for apppresser 2.0.0 compatibility

= 2.0.0 =
* Localize JavaScript in preparation for apppresser 2.0.0 compatibility

= 0.9.6 =
* Added hook to send custom push notifications: apppush_send_notification()
* Added filter for overriding push content: send_push_post_content
* Fix notification display title when app is open
* Added admin setting to override push notification titles
* Fixed bug when sending pushes to specific devices
* See docs for new hook/filter usage: <a href="http://apppresser.com/docs/extensions/apppush/#mcb_toc_head11" target="_blank">http://appprecom/docs/extensions/apppush/#mcb_toc_head11</a></li>

= 0.9.5 =
* Security patch for add_query_arg

= 0.9.4 =
* Bug fixes
* Update to work with newest version of Phonegap.

= 0.9.3 =
* Support for AppBuddy push notifications - private messages, friend requests, and mentions.

= 0.9.2 =
* Fix for devices that are not registering

= 0.9.1 =
* Support for cordova script optimization.


