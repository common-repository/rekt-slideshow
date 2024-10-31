=== Rekt Slideshow ===
Contributors: rektproductions
Donate link: http://www.rektproductions.com/
Tags: slideshow, slider, photos, pictures, jquery
Requires at least: 2.9
Tested up to: 3.1
Stable tag: 1.0.5

Rekt Slideshow is a Wordpress plugin that will make it simple to insert a fading slideshow of images anywhere on a wordpress site.

== Description ==

Rekt Slideshow is a Wordpress plugin that will make it simple to insert a jQuery based fading slideshow of images anywhere on a wordpress site. 
The plugin gives an options page where the user can choose the slideshow size, number of pictures, and slide transition duration as well
as choose the images by using the Wordpress built in media upload dialog.

For more information visit [Rekt Productions](http://www.rektproductions.com/rektslideshow/ "Rekt Productions").

== Installation ==

This section describes how to install the plugin and get it working.

e.g.

1. Upload `rekt-slideshow` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Use the Rekt Slideshow options panel to configure the slideshow
4. Place `[rektslideshow]` in your templates, pages or posts as desired

== Frequently Asked Questions ==

= How many pictures can I use? =

I don't know. I didn't set a limit in the plugin.

= What jQuery script did you use? =

CrossSlide, which I found at http://tobia.github.com/CrossSlide/

== Screenshots ==

1. Screen shot of options panel.

== Changelog ==

= 1.0 =
* Initial release

= 1.0.1 =
* Fixed instructions included on bottom of options panel to include shortcode

= 1.0.2 =
* Repaired the link to the javascript file in the header

= 1.0.3 =
* Increased memory cap for picture resizing script to handle larger images
* Increased dimension cap for images (original was 1000 x 1000, now is 5000 x 5000) 

= 1.0.4 = 
* Moved script to footer for faster page loads
* Opened external images and encrypted url to avoid script injections

= 1.0.5 = 
* Fixed output causing IE to not show slideshow
* Removed unused option for external domains

== Future Improvements ==

1. Multiple slideshows with one plugin
2. Implement other options that CrossSlide offers (Ken Burns etc.)
