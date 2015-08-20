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

= 1.1 - May 6 2015 =
* Add support for Jetpack Testimonial CPT

= 1.0.2 - Dec 22 2014 =
* Prefix and clean variables

= 1.0.1 - Nov 19 2014 =
* Fix responsive navigation

= 1.0 - Nov 6 2014 =
* Initial release