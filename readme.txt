=== MyStem EDD ===
Contributors: lobov
Donate link: https://dayes.co/
Tags: mystem, EDD, easydigitaldownloads, Easy Digital Downloads
Requires at least: 4.5
Tested up to: 5.2
Requires PHP: 5.3
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html


This plugin helps you to create a store with Easy Digital Downloads and WordPress theme MyStem. 

== Description ==
MyStem EDD add extra features to the WordPress theme 'MyStem' like shortcodes, downloads category templates, single download templates and more, if you use the plugin Easy Digital Downloads
This plugin requires the [MyStem](https://wordpress.org/themes/mystem/) theme to be installed.


You can see the [Site](https://dayes.co/) with this plugin and WordPress theme MyStem


= Features =
* Select a template for each Downloads category
* Select a template for Single Downloads;
* Choose Category Icon and Icon color;
* Hide title and description of category;
* Set the number of posts for each category;



= Shortcodes =
* Popular downloads
* Featured downloads
* Latest downloads
* Account informations
* Count of all sales
* Count of all users
* Count of all downloads


== Installation ==
* Installation option 1: Find and install this plugin in the `Plugins` -> `Add new` section of your `wp-admin`
* Installation option 2: Download the zip file, then upload the plugin via the wp-admin in the `Plugins` -> `Add new` section. Or unzip the archive and upload the folder to the plugins directory `/wp-content/plugins/` via ftp
* Press `Activate` when you have installed the plugin via dashboard or press `Activate` in the in the `Plugins` list 


== Frequently Asked Questions ==
= Add cart to menu =

You can add the cart to the menu and be setting it from Customizer. Just add class 'mystem-edd-cart' to the menu item 

= Shortcodes for Downloads =

You can easily output a list or grid of downloadable products using the [downloads] short code. This short code accepts a variety of parameters that allow you to specify what downloads are displayed, and how they are displayed.

To show downloads with the default configuration, use this: [mystem_downloads]

List of the parameters:

* products - Which downloads will appear (featured, popular, latest, cat)
* type - How to display downloads. Can be 'slider' or 'grid'
* columns - How many columns appear for 'grid' (2,3,4)
* number - Number of downloads
* exclude - exclude downloading with ID
* title - title for slider
* background - background  for slider
* color - color for slider
* imgheight height of thumbnails in px, default 'auto'
* category - for products = cat, which category appear

For Example, [mystem_downloads products="featured" type="grid" columns="4" number="8" exclude="32"]

Output the selected list of 8 downloads in four columns excluding download with ID = 32

= Shortcodes for Account =
You can easily output information about login user, use this; [mystem_account]

List of the parameters:

* meta - What information should be displayed: avatar, nicename, login, pass, email, url, registered, firstname, lastname
* size - use for meta = avatar, default 96. The size of the avatar

For Example, [mystem_account meta="email"]

Output of current user email

= Shortcodes for Statistic =
You can easily output statistic information, use this; [mystem_edd_count]

List of the parameters:

*type - What shall we deduce. Can be: products, users, sales

For Example, [mystem_edd_count meta="sales"]

Displays the total number of sales

== Changelog ==

= 1.1 = 
* Fixed: minor bugs

= 1.0 = 
* Initial release