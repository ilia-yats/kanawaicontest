=== Google Map Locations ===
Contributors: dswpsupport 
Tags: google maps, google map, google map location, google map short code,  google map locations, google map pointer, marker,  adderss bubble on map, easy map, custom marker icons and text, google, google map, google maps, map, map markers, map by address, maps, lat, lon, latitude, longitude, geocoder, geocoding, georss, geo
Donate link:
Stable tag: google maps, google map, google map location, google map short code,  google map locations, google map pointer, marker,  adderss bubble on map, easy map, custom marker icons and text, google, google map, google maps, map, map markers, map by address, maps, lat, lon, latitude, longitude, geocoder, geocoding, georss, geo
Requires at least: 3.0
Tested up to: 4.1
License: GPLv2 or later  

== Description ==
This plugin lets you add a google map with locations with bubble.

Admin can add multiple locations with post code, latitude, longitude, image, etc... And the information will be pointed on map with bubble.

Settings Page includes an options panel which gives you control over your form.

= General Features =
* Default Map Page.
* Create Multiple Locations with various information.
* Lightweight.
* Hide or show information in google pointer through update settings.
* Can be integrated with any theme.
* Works well with other plugins.
* Default Post Code search.

= Premium Features =
* Add links to  your Google Map markers.
* Add images to your Google Map markers.
* Add custom marker icons, or your own icons to make your map look different!
* Multiple Google Maps with individual map short code.
* Manage Map locations in categories
* Set a common zoom level for all maps.
* Set zoom level for individual map also.
* Set Map Type i.e. Satalite View or Default Map View. 
* Set height and width separately for individual map also.
* Import / Export map locations with the help of sample CSV.
* Get the [Google Map Locations Premium Version](http://stores.dotsquares.com/php/wordpress/google-map-locations.html) version for only $10.


== Installation == 

The automatic plugin installer should work for most people. Manual installation is easy and takes fewer than five minutes.

1. Download the plugin, unpack it and upload the 'google-map-locations' folder to your wp-content/plugins directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Go to Google Map Location -> Settings Page to configure the basic map options.


To use the plugin as on page use shortcode as : [gml-google-show-map].

To use the plugin in theme file, use below code:

do_shortcode("[gml-google-show-map]");

Or


if(function_exists('gml_show_map')) {
	echo gml_show_map();
}


== Frequently Asked Questions ==
How to use this in theme file?

if(function_exists('gml_show_map')) {
	echo gml_show_map();
}



== Changelog ==

= 1.1 =
* Settings Panel Issue
* Removed Validations from not required fields

= 1.0 =
* This is first release. 

 

== Screenshots == 
1. screenshot-1.jpg
2. screenshot-2.jpg
3. screenshot-3.jpg