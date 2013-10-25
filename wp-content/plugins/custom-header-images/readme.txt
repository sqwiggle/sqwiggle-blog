=== Plugin Name ===
Contributors: blackbam
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=DX9GDC5T9J9AQ
Tags: header, images, simple
Requires at least: 3.0
Tested up to: 3.5
Stable tag: 1.1.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A very simple and lightweight Plugin for managing custom header images for pages, posts, archive-pages, and all other possible.

== Description ==

This plugin provides an easy, lightweight possibility to add header images to your blog. It is simple and clean. 
It is easy to understand, maintain and extend. It does not make use of extra tables or buggy built-in uploads.

Features:

* Simple and easy management of header images
* Each possible state is covered, by using all elements of the <a href="http://upload.wikimedia.org/wikipedia/commons/3/3d/Wordpress_Template_Hierarchy.png">WordPress template hierarchy</a>
* The Media Library is used for image management, images are saved by URL copy/paste (so external images can be used, too)
* Requires no extra tables
* Support for Custom Post Types (Single Custom Post Images) and Custom Taxonomies (Taxonomy Page Images)
* NEW (1.1.0): Linked Header Images (e.g. category page, larger image version, whatever you want ...)
* NEW (1.1.0): Display Category Image by default
* NEW (1.1.0): Full output customization
* NEW (1.1.0): Exclude specified Taxonomies / Post Types from Header Image functionality
* NEW (1.1.0): Internationalization (incl. English / German translation)
* Clean install/uninstall

Please report any bugs you may encounter.

Plugin URL: <a href="http://www.blackbam.at/blackbams-blog/2012/06/25/custom-header-images-plugin-for-wordpress">http://www.blackbam.at/blackbams-blog/2012/06/25/custom-header-images-plugin-for-wordpress</a>

== Installation ==

1. Upload the Plugin to your wp-content/plugins/ folder
2. Activate the Plugin
3. Go to Settings -&gt; Header Images and insert the image URLs (get the URLs from the media library)
4. Paste the following code into your theme: <code><?php if(function_exists('chi_display_header')) { chi_display_header(); } ?></code>

Where to set the image data:

* Go to Settings -> Header Images for general settings
* Meta Boxes at the bottom of post / page / custom post type edit screen
* Category/Taxonomy add/edit screen

Note: This Plugin uses conditional tags. You can only use conditional query tags after the posts_selection action hook in WordPress (the wp action hook is the first one through which you can use these conditionals). For themes, this means the conditional tag will never work properly if you are using it in the body of functions.php, i.e. outside of a function (http://codex.wordpress.org/Conditional_Tags).

== Frequently Asked Questions ==

* Q: The Plugin is not displaying the header images correctly. What is wrong?
* 1. The function code must be pasted into one of your template files, the best place in most cases of use is the bottom of header.php
* 2. This Plugin uses conditional tags. Please make sure that your WP_Query object has been loaded correctly, before the code is executed.

== Screenshots ==

1. /assets/screenshot-1.png
2. /assets/screenshot-2.png

== Changelog ==

= 1.1.0 =
* Display Category Images by Default
* Linked Header Images
* Full Output customization
* Exclude specified Post Types / Taxonomies from Header Image functionality
* Internationalization

= 1.0.2 =
* Fixed Taxonomy editing bug

= 1.0.1 =
* Added full support for Custom Post Types and Custom Taxonomies
* Various improvements in routing, fixed category image bug.
* Fixed some language issues.

= 1.0.0 =
* Initial release.
