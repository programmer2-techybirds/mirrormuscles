=== Geolocation ===
Contributors: apppresser, webdevstudios, williamsba1, scottopolis, jtsternberg, Messenlehner, LisaSabinWilson, modemlooper, stillatmylinux
Author URI: http://apppresser.com
Requires at least: 3.5
Tested up to: 4.8.0
Stable tag: 3.1.0
License: General Public License
License URI: http://www.gnu.org/licenses/gpl-2.0.html

== Description ==
The Geolocation extension allows your app users to pinpoint their location on a Google Map, and post their location data to WordPress. You can also “check in” to a Google Places business or custom address.

== Installation ==

1. Upload Geolocation (appgeo) to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. You will now find a new "Geolocation" tab in your AppPresser settings.

Please visit there to configure the AppGeolocation extension settings.

== Changelog ==

= 3.1.0 =
* Add shortcode for geolocation for user meta
* Fix places parameter for checkin shortcode

= 3.0.1 =
* Add click and message event listeners to fix the checkin shortcode button.

= 3.0.0 =
* Add compatibility with AppPresser 3.0; update the data structure for lat/long.

= 2.2.1 =
* Add Google API key to static image URLs

= 2.2.0 =
* Add appp-map shortcode to display a Google map with markers from geolocation checkins

= 2.1.0 =
* Use default position when GPS is disabled

= 2.0.0 =

* Add compatibility with Apppresser 2.0.0 moving cordova files to device
* Add compatibility with Google Maps API by adding a key field in the admin
* Minor bug fixes<

= 1.1.2 =

* Bug Fix: move is_user_logged_in function after init hook

= 1.1.1 =

* Add checkin data to user profile
* Fix missing ajaxurl variable

= 1.1 =

* New check in feature, allow app users to check in to a business or location. See <a href="http://appprecom/docs/extensions/app_geolocation/#mcb_toc_head16" target="_blank">our documentation</a> for usage</li>
* Bug fixes, improvements

= 1.0.6 =

* Bug fixes with action=new posting
* Translation updates
* Compatibility with newest version of Phonegap

= 1.0.5 =

* Load geolocation js on every page for persistent location tracking
* Lat/long coordinates now display on all user profile pages
* Name change, including function names

= 1.0.4 =

* Support for script optimization

= 1.0.3 =

* Bug Fix: 'map_preview' parameter in shortcode was <em>disabling</em> the preview map.
* Bug Fix: Preview map was not properly updating with the map image.
* Bug Fix: Ensure only one map image is added.

= 1.0.2 =

* Developer Helpers: Adds submitted geo-post form data debug output to the content if the `APPPRESSER_DEBUG` constant is defined.
* Bug Fix: In some circumstances, the geo-post form fields were being added multiple times which would prevent the data from saving properly.

= 1.0.1 =

* Bug Fix: Javascript syntax error prevented geolocation data from being stored properly

= 1.0.0 =

* First official release.

