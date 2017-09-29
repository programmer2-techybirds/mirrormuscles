=== BuddyPress Activity Shortcode ===
Contributors: buddydev, sbrajesh, raviousprime
Tags: buddypress, buddypress activity, sitewide activity, activity shortcode
Requires at least: 4.0
Tested up to: 4.7.1
Stable tag: 1.0.8

BuddyPress Activity shortcode plugin allows you to insert BuddyPress activity stream on any page/post using shortcode.

== Description ==
BuddyPress Activity shortcode plugin allows you to insert BuddyPress activity stream on any page/post using shortcode. It has a lot of flexibility built in the shortcode.
You can customize almost all aspects of the activity list, what should be listed, how many and everything using the shortcode.

This plugin does not include any css and utilizes your theme's css for displaying the activity. If you need any help, please ask on BuddyDev support forums. 
We are helpful people looking forward to assist you.

Features include:

 * List all activities
 * List activities for a user
 * List activities for a group
 * Allow users to post from the page( experimental, if does not work with your theme, please let us know)
 * All options supported by bp_has_activities are available
 * For details, please see [Documentation](https://buddydev.com/plugins/bp-activity-shortcode/ "Plugin page" )
The simple way to use it is by including this shortcode

[activity-stream ]

Please make sure to check the usage instructions on the [BuddyPress Activity shortcode plugin page](https://buddydev.com/plugins/bp-activity-shortcode/ "Plugin page" )

Free & paid supports are available via [BuddyDev Support Forum](https://buddydev.com/support/forums/ "BuddyDev support forums")

== Installation ==

The plugin is simple to install:

1. Download `bp-activity-shortcode.zip`
1. Unzip
1. Upload `bp-activity-shortcode` directory to your `/wp-content/plugins` directory
1. Go to the plugin management page and enable the plugin "BuddyPress Activity Shortcode"

Otherwise, Use the Plugin browser, upload it and activate, you are done.

== Frequently Asked Questions ==

= How to Use =
Add the shortcode [activity-stream ] in your post or page. For detailed usage instruction, please visit plugin page on BuddyDev.


== Changelog ==

= Version 1.0.8 =
 * Introduce option to display the activity shortcode contents even on activities page  using hide_on_activity=0
 * Introduce container_class option to allow changing the shortcode output container class. It defaults to 'activity'.
  If you have hide_on_activity=0, we suggest you to change it to something else to avoid the filtering of the content via js.

= Version 1.0.7 =
 * Updated code
 * Tested with BuddyPress 2.7.0

= Version 1.0.5 =
 * Updated code
 * Added support for load more when no filters are used

= Version 1.0.5 =
 * Initial release on WordPress.org plugin repo

