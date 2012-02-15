=== Intuitive Navigation ===
Contributors: Denis Buka
Donate link: http://steamingkettle.net
Tags: navigation, previous, next, referrer, category, tag, links
Requires at least: 3.2
Tested up to: 3.2
Stable tag: 0.6

Creates navigation to next and previous posts based on the category or tag a visitor came from.

== Description ==

Creates navigation to next and previous posts specific to the category or tag a visitor came from. You can embed the navigation automatically or use a custom function `addIntNav()` in your template files. Customize the appearance with post thumbnails and labels. Swap next and previous post links mirror-wise. Bold links to the current category or tag. Optimized for use with caching plugins.

**Features overview:**

* Add next/previous navigation links to your post specific to the category or tag a visitor came from.   
* Optionally highlight referrer link occurrances on page.   
* Insert navigation automatically above or below post content or use a custom function - `addIntNav()`.   
* Choose wheather navigation should contain labels and post thumbnails.   
* Swap next and previous links mirror-wise.   
* Optionally load navigation links in a frame if you're using caching plugins on your site.   


**My other plugins:**   

* Generate Cache (http://wordpress.org/extend/plugins/generate-cache/)   
* Drop in Dropbox (http://wordpress.org/extend/plugins/drop-in-dropbox/)   

Links: [Steaming Kettle Website Design & Video Production Studio](http://steamingkettle.net)   

== Installation ==

1. Unzip the archive and put the folder into your plugins folder (/wp-content/plugins/).
2. Activate the plugin from the Plugins admin menu.
3. Go to Settings -> Intuitive Navigation to set some options or place `<?php if ( function_exists( 'addIntNav' ) ) { addIntNav(); } ?>` in your templates.

= Basic usage =

You can embed the navigation automatically or use a custom function `addIntNav()` in your template files.

== Frequently Asked Questions ==

= How do I add Intuitive Navigation to my theme? =

Simply place this snippet of code into your theme's template file `<?php if ( function_exists( 'addIntNav' ) ) { addIntNav(); } ?>`

== Upgrade Notice ==

== Screenshots ==

1. Front-end example
2. Settings admin page

== Changelog ==

= 0.6 =
* Improved compatibility

= 0.5 =
* Fixed encoding problem in some browsers
* Added option to insert styles in frame's head section

= 0.4 =
* Code improvements

= 0.3 =
* Improved header script addition
* Improved admin options page

= 0.2 =
* Minor corrections

= 0.1 =
* Initial release
