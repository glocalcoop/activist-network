=== Sequential ===

Contributors: automattic
Tags: light, purple, white, two-columns, right-sidebar, responsive-layout, custom-colors, custom-header, custom-menu, featured-images, flexible-header, full-width-template, post-formats, rtl-language-support, sticky-post, theme-options, translation-ready

Requires at least: 4.0
Tested up to: 4.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A contemporary, clean, and multi-purpose business theme.

== Description ==

Sequential is a contemporary, clean, and multi-purpose theme that helps you to create a strong -- yet beautiful -- online presence for your business.

* Responsive layout.
* Front Page Template.
* Full Width Page Template
* Grid Page Template
* Jetpack.me compatibility for Infinite Scroll, Testimonial Custom Post Type, Responsive Videos, Site Logo.
* The GPL v2.0 or later license. :) Use it to make something cool.

== Installation ==

1. In your admin panel, go to Appearance > Themes and click the Add New button.
2. Click Upload and Choose File, then select the theme's .zip file. Click Install Now.
3. Click Activate to use your new theme right away.

== Frequently Asked Questions ==

= I don't see the Testimonial menu in my admin, where can I find it? =

To make the Testimonial menu appear in your admin, you need to install the [Jetpack plugin](http://jetpack.me) because it has the required code needed to make [custom post types](http://codex.wordpress.org/Post_Types#Custom_Post_Types) work for the Edin theme.

Once Jetpack is active, the Testimonial menu will appear in your admin, in addition to standard blog posts. No special Jetpack module is needed and a WordPress.com connection is not required for the Testimonial feature to function. Testimonial will work on a localhost installation of WordPress if you add this line to `wp-config.php`:

`define( 'JETPACK_DEV_DEBUG', TRUE );`

= How to setup the front page like the demo site? =

The demo site URL: http://sequentialdemo.wordpress.com/?demo

When you first activate Sequential, you’ll see your posts in a traditional blog format. If you’d like to use this template as the front page of your site, follow these instructions:

1. Create or edit a page, and then assign it to the Front Page Template from the Page Attributes module.
2. Add an introduction to your site. For best results, we recommend a few paragraphs.
3. Set your front page image — behind the text — as a Featured Image.
4. Go to Settings → Reading and set “Front page displays” to “A static page.”
5. Select the page to which you just assigned the Front Page Template as “Front page,” and then choose another page as “Posts page” to display your blog posts.

= What are the theme options? =

Sequential comes packed with multiple Theme Options available via the Customizer:

* Show Tagline: display the site description underneath the site title.
* Top Area Content: display some content above the header — perfect for a phone number or an email address. You can include basic HTML like links here.
* Front Page: Featured Page One: select a page to feature on the Front Page Template. (1)
* Front Page: Featured Page Two: select a page to feature on the Front Page Template. (1)
* Front Page: Show Page Titles: display the page titles on the Front Page Template.
(1) For any of the featured pages selected for the front page, keep in mind that if you choose your posts page, the front page will display excerpts and featured images of your latest blog posts.

= How to add the social links? =

Sequential allows you to display links to your social media profiles as icons using a Custom Menu. Icons for Twitter, Facebook, LinkedIn and most other popular networks are included, and Sequential will automatically display an icon for each service if it’s available.

- Set up the menu -

To automatically apply icons to your links, simply create a new Custom Menu and give it a name that starts with “Social” (e.g. “Social Menu,” “Social Links”). This specific name is important and must match exactly. Next, add each of your social links to this menu. Each menu item should be added as a custom link.

Once your menu is created and your social links are added, you can display it in your Footer Menu or you can also create a new Custom Menu Widget to display it in any of Sequential‘s widget areas.

- Available icons -

Linking to any of the following sites will automatically display its icon in your menu:

* Codepen
* Digg
* Dribbble
* Dropbox
* Facebook
* Foursquare
* Flickr
* GitHub
* Google+
* Instagram
* LinkedIn
* Email (mailto: links)
* Pinterest
* Pocket
* PollDaddy
* Reddit
* RSS Feed (urls with /feed/)
* Spotify
* StumbleUpon
* Tumblr
* Twitter
* Vimeo
* WordPress
* YouTube

= Where are located the widget areas? =

Sequential offers two widget areas, which can be configured in Appearance → Widgets:

* An optional sidebar widget area, which appears on the right.
* An optional footer widget areas.

= What are the extra CSS classes? =

Sequential comes with a few special CSS styles.

- Buttons -

button and button-minimal

You can add these classes to your links in the Text Editor, to create “call to action” buttons.

For example:

<a href="http://sequentialdemo.wordpress.com/" class="button">Button</a>

<a href="http://sequentialdemo.wordpress.com/" class="button-minimal">Button Minimal</a>

- Columns (recommended for advanced users only) -

Two special CSS classes are available to create a two-column or three-column area within a post or page: column-1-2 and column-1-3

You will need to use the Text Editor to create your columns. We recommend not switching back and forth between the Visual and Text Editor once you start creating columns.

For example, this code creates a two-column layout:

<div class="column-1-2"><p>Cras justo odio, dapibus ac facilisis in, egestas eget quam. Maecenas faucibus mollis interdum. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.</p></div>

<div class="column-1-2"><p>Cras justo odio, dapibus ac facilisis in, egestas eget quam. Maecenas faucibus mollis interdum. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.</p></div>

This variation creates a three-column layout:

<div class="column-1-3"><p>Maecenas sed diam eget risus varius blandit sit amet non magna. Nulla vitae elit libero, a pharetra augue.</p></div>

<div class="column-1-3"><p>Maecenas sed diam eget risus varius blandit sit amet non magna. Nulla vitae elit libero, a pharetra augue.</p></div>

<div class="column-1-3"><p>Maecenas sed diam eget risus varius blandit sit amet non magna. Nulla vitae elit libero, a pharetra augue.</p></div>

The column-1-3 class can only be used on pages with a full-width layout like the Front Page Template, the Grid Page Template and the Full-Width Template.

See live examples on Sequential‘s demo site:

* Full-Width Page Template: http://sequentialdemo.wordpress.com/columns-css-full-width-page-template/
* Default Page Template: http://sequentialdemo.wordpress.com/columns-css-default-page-template/


== Quick Specs (all measurements in pixels) ==

1. The main column width is 700 except when using the Front Page Template, Grid Page Template or Full-Width Page Templage where it’s 1086.
2. A widget is 314.
3. Featured Images are 772 wide by unlimited high.

== Changelog ==

= 26 January 2016 =
* Correcting link used to enqueue Genericons.

= 20 January 2016 =
* Remove meta-nav
* Temporary remove esc_html from template-tags.php -- creating issues -- will look into it
* Add proper escaping

= 19 January 2016 =
* Need to be more specific when targeting .sd-content
* Make sure sd-content list has correct margin-bottom
* Add sharedaddy script back so that users can use Sharing setting and send emails.

= 24 December 2015 =
* Adjust wording of tagline option and display the option contextually

= 26 November 2015 =
* Make sure inputs have a white background when in the sidebar.

= 23 November 2015 =
* Change "Theme" to "Theme Options" in Customizer.

= 16 November 2015 =
* Add missing icons for sharing button that got previously removed.

= 12 November 2015 =
* Fix bug in Jetpack sharing buttons

= 6 November 2015 =
* Add support for missing Genericons and update to 3.4.1.

= 27 October 2015 =
* Disable Infinite Scroll for the Testimonial CPT -- Is conflicting with theme style and loads new testimonials outside of wrapper

= 12 August 2015 =
* updating readme to reflect recent change.
* adding isset check to testimonial page content from customizer.

= 31 July 2015 =
* Remove .`screen-reader-text:hover` and `.screen-reader-text:active` style rules.

= 15 July 2015 =
* Always use https when loading Google Fonts.

= 6 May 2015 =
* Update po file and copyright
* Add support for Jetpack Testimonial CPT

= 9 April 2015 =
* Make sure sharing buttons in the hero area are not showing the text.

= 23 March 2015 =
* Add override in CSS for official sharing buttons so theme styles do not break them.

= 4 March 2015 =
* Use margins rather than padding on entry meta to prevent overlapping the entry title;

= 3 March 2015 =
* Comment out theme-specific PollDaddy styles that were breaking the look of non-plain polls.

= 1 March 2015 =
* Add missing styles from dequeued sharing script, that was causing screen reader text to be shown on the home page template.

= 26 January 2015 =
* Add background-color to select when in the footer-widget-area.
* Fix right and left padding on small devices for template pages with a full-width layout
* Add count number to share icons.

= 22 December 2014 =
* Prefix and clean variables

= 17 December 2014 =
* updated credits.

= 13 November 2014 =
* Load sequential_menu() when document is ready instead of when page is loaded.

= 6 November 2014 =
* Update site logo link class.
* Add readme
* Fix menu positioning when branding + menu > wrapper
* Update description
* Re: Fix tagline in Customizer: Revert JS changes made and instead fix the CSS
* Fix tagline in Customizer
* Center primary menu when toggled on screen >= 768px
* Add RTL stylesheet

= 5 November 2014 =
* Make sure $top_area_content is sanitize with wp_kses_post()

= 4 November 2014 =
* Add missing tag
* Update descripton and tags
* New screenshot to match demo site

= 3 November 2014 =
* Update columns css: add a margin-bottom to it and make sure that last item it in doesn't have a margin-bottom
* Fix typo and front page padding bottom
* Use rgba instead of hexa color so it's still highlighted when content is in a #f7f7f7 section like on the front page template or the grid page template
* Improve columns extra css classes --3 columns are only available when using a full-width layout (front page template, grid page template or full-width page template)
* Update flickr widget styles
* Display author name even if post is sticky
* Move "cancel reply" link to the right
* Remove extra spacing in sequential_entry_meta()

= 2 November 2014 =
* Hyphen added to: "Full-Width Page"
* remove unnecessary variables defined in the header, $format and $formats
* Improve 404 page
* Add missing @package information in inc/custom-header.php
* Add Jetpack prefixing to Site Logo template tags.

= 31 October 2014 =
* Early version of the Columns Extra CSS. Careful, still in beta and using jQuery! Handle with care! (^(I)^)

= 30 October 2014 =
* Remove reply link if empty and fix no-comment's padding
* Fix hentry margin bottom on large screens
* Fix margins on larger screens
* New style for post-thumbnail in the grid-area
* Fix widget-area paddings and clearings
* Rename Customizer Panel "Theme Options" to "Theme" -- props @kathrynwp

= 28 October 2014 =
* Reorganise CSS a bit
* Update responsive styles to overwritte color annotations

= 27 October 2014 =
* Add max-with to site-logo to avoid crazy big logos to be uploaded
* Add site-logo -- was registred but wasn't called or styled
* Move custom-header in the DOM and improve its styling
* Fix gravatar like -- copied from jetpack-likes.css
* Switch .breadcrumb-area background color to black rather than dark purple -- looks better :)
* Add support for Jetpack's Breadcrumbs
* Improve entry-footer styles: Add a :before pseudo-element to distinguish it from the content and/or sharedaddy
* Add custom styles for Sharedaddy

= 26 October 2014 =
* Fix menu alignement issue when it's supposed to be left -- Widths were not recalculated after screen resize

= 24 October 2014 =
* Fix missing comma (>_<)
* Customize Shardaddy share links (remove old css and dequeue default styles)
* Fix wrong link color for entry-meta in the Hero Area
* Add styles for WPCOM widgets

= 23 October 2014 =
* Fix hero hentry margin on large screens (>= 1020px)
* Define a width for site-branding on devices smaller than 1020px to make sure menu is always on the top right corner. Tweak positioning of the menu when toggled.
* Add styles for WPCOM Reblogger
* Center content-area when screen size < 1020px
* Add styles for PollDaddy
* Update wpcom comment form with correct colors/spacings
* Remove extra margin-botttom from the content-area when using the Front Page Template
* Remove extra margin-top for page-header and post-thumbnail when x >= 1020px

= 22 October 2014 =
* Add style for tags/categories cloud widget
* Add style for standard posts with featured image after IS scroll
* Update .button-minmal colors against dark
* Fix extra padding on front page blocks
* Add class "no-sidebar" to body when sidebar-1 isn't active and center the content
* Update sequential_entry_meta() to fix typo and and spacings
* Remove content-area with JS if it's empty
* Update wpcom.php file and add WP.com stylesheet
* Remove post-thumbnail's background for the Hero Area -- People might want to upload a png, like on the demo site :)
* Fix show tagline theme option

= 21 October 2014 =
* Initial import of the .org version of the Sequential theme
