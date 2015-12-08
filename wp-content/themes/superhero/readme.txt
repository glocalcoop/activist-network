Superhero
===

Tags: responsive-layout, gray, two-columns, custom-background, custom-header, custom-menu, featured-images, flexible-header, full-width-template, post-formats, sticky-post, translation-ready, rtl-language-support

A responsive theme with a clean look with bright pops of color. Superhero features full-bleed featured posts and featured images, a fixed header, and subtle CSS3 transitions.

* Full-bleed flex slider
* Featured Image support
* Full-width page template
* Responsive layout
* Custom Background
* Custom Header
* Jetpack.me compatibility for infinite scroll
* Keyboard navigation for image attachment templates.
* CSS3 transition effects
* The GPL license in license.txt. :) Use it to make something cool.

Special Instructions
---------------

Superhero includes an optional full-width featured-post slider, which requires the [Jetpack] (​http://wordpress.org/plugins/jetpack/) plugin's [Featured Content] module (​http://jetpack.me/support/featured-content/) to set up.
1. Navigate to Appearance → Customize → Featured Content.
2. Enter the name of a tag.
3. Click the "Save" button at the bottom.
4. Create a post with a featured image that's at least 960px wide. The featured image will look best at a 2.88:1 width-to-height ratio, and at least 500px tall.
5. Give the post the tag you declared under Appearance → Customize → Featured Content.
6. Repeat steps 4 & 5 for as many posts as you'd like in the slider.

== Changelog ==

= 1.4 - Jun 15 2015 =
* More exhaustive escaping throughout

= 1.3 - Nov 27 2014 =
* Add support for the Eventbrite API plugin.

= 1.2.1 - Nov 2 2014 =
* Update Site Logo template tags for Jetpack.

= 1.2 - October 2 2014 =
* Add templates for proper support of the Portfolio Custom Post Type in Jetpack.

= 1.1.7 - August 18 2014 =
* Update readme file
* Update copyright in stylesheet

= 1.1.6 - April 8 2014 =
* Don't check image sizes before printing featured images; this causes conflicts with Jetpack and Photon.
* Update POT file, bump version number to be in synch with WP.org.
* Ensure featured slider navigation arrows point in the correct direction in RTL
* Remove height: 100% from HTML and BODY tags, as they were causing a conflict with the Carousel where the cursor would return to the top after clicking on a Carousel image in Chrome and Safari.

= 1.1.1 - September 17 2013 =
* Load JS function after a page load completely and not when just document is ready to avoid header overlaping blog content

= 1.1 - May 28 2013 =
* Update license.
* Added forward compat with 3.6.
* Comment style clean-up.
* Adds padding to the comments container instead of margin to prevent a bleeding edge. This was apparent when a blog has custom background image/color.
* Adds a ".displaying-header-text" class to the header h1 to allow the "display text with your image" toggle button to work in the preview.
* Prevents the featured slider's js and css from loading when there is no featured content.
* Applies the CSS 3 transition affects only to links inside the .site container, to prevent the affects from applying to the admin bar.
* Ensures post format archive links only appear for formatted posts.
* Aligned comment links with the associated avatars in the recent comments widget.
* Moved flexslider init to jquery.flexslider-min.js, to be conditionlly included with flexslider.
* Switched custom header to respect admin settings.
* Added Custom Background support. 
* Added RTL support.
* Edited media query to match small-menu.js
* Minor bug fixes.

= 1.0.2 - Mar 04 2013 =
* Added support for Featured Content.
* Removed image size check in slider.
* Extended theme documentation.

= 1.0.1 - Mar 01 2013 =
* Fixed a CSS selector.

= 1.0 - Feb 06 2013 =
* Initial release.
