=== GoogleMapper ===
Contributors: chiggins, adamrbrown
Donate link:
Tags:google,geo
Requires at least: 2.1.2
Tested up to: 2.1.2
Stable tag:trunk

Allows creation and management of Google Maps using Google's Map API v2.

== Description ==

GoogleMapper uses the Google Map API to provide the ability to:

* Create and manage custom sets of marker icons
* Create and manage Map Groups that use a subset of your custom icons
* Create and manage Maps

Additional features allow you to:

* specify a zoom level at which the map should switch to larger icons
* specify whether or not to use the pan and zoom controls
* fill in info windows

Now with hot WordPress Widget action! (Many thanks to Adam Brown.)

The plugin is still in its early stages of development, and there's lots more features I'd like to implement (or see implemented by someone better at this than I am). Feel free to drop a comment, question, and/or feature request.

== Installation ==

1. Upload 'GoogleMapper.tar.gz' to the '/wp-content/plugins/' directory and unpack the tarball
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go to the top-level 'GoogleMapper' control panel, and click the 'Configuration' tab
4. Get a (free) Google API key at the [Google Map API Key](http://www.google.com/apis/maps/signup.html) page, and update 'Configuration'
5. Non-widget: Create a post or page, and include this token: [gmap map:1], Widget: specify the map id in the widget configuration
6. publish it and you should see your new map!

== Screenshots ==

No screenshots yet, I'll work on that...

== Frequently Asked Questions ==

= Does this plugin support PHP4? =

Unfortunately, no it doesn't. GoogleMapper uses PHP5's OO framework, and while I'd like to tell you that a PHP4-compatible version is coming soon, limited time and resources are keeping me from going after it. If there's anyone out there that would like to take a swing at making a PHP4 version, and would like some info to help get goin' on it, please contact me.

== More Documentation ==

Extended documentation is available from [my site](http://blog.chiggins.com/?p=35), which discusses the plugin in much greater detail. Please read it.

== Special Thanks ==

Adam Brown (http://AdamBrown.info) fixed several bugs, pointed me at a couple others, and thoroughly widgetized GoogleMapper (introducing me to the wonderful world of widgets in the process). Let's give him a warm round of applause!

== Things on my radar that need doing next ==

* The Icons and Marker Types control panel has detailed explanations of the fields, I need to write more of those for the other panels.
* Caching at the object level, and output level (this is a high priority, look for this one soon).
* Ability to render multiple maps on the same map, ala: [gmap map:1,4,7].
* Ability to render a map group, ala: [gmap group:2].
* Better icon uploading / editing (something perhaps like ImageManager, but icon-oriented).
* Enforce uniqueness of titles for icons, marker types, map groups, and maps.
* Assorted UI details that people suggest that make me slap my forehead and think, "yes, of course".
