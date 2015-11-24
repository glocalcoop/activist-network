=== Tagregator ===
Contributors:      wordpressdotorg, iandunn, shaunandrews, ryelle, melchoyce
Donate link:       http://wordpressfoundation.org
Tags:              hashtag, social media, aggregation, stream
Requires at least: 3.9
Tested up to:      4.0
Stable tag:        0.6
License:           GPLv2 or Later

Aggregates hashtagged content from multiple social media sites into a single stream.


== Description ==

Tagregator lets you add a shortcode to a post or page on your site, and pull in content from various social media networks onto that page. For example, if you add `[tagregator hashtag="#WordPress"]` into a page, then you'll see posts that mention the #WordPress hashtag.

= Included Social Media Sources: =
* Twitter
* Instagram
* Flickr
* Google+


== Installation ==

For help installing this (or any other) WordPress plugin, please read the [Managing Plugins](http://codex.wordpress.org/Managing_Plugins) article on the Codex.

**Step 1)** After installing the plugin, go to the Tagregator > Settings screen and enter the credentials for the services you want to use.

When <a href="https://dev.twitter.com/apps/new">creating a Twitter application</a>, you should enter the URL of your website in the "Website" field (e.g., `http://www.example.org`), and then leave the "Callback URL" field empty. Once the application is created, copy the Consumer Key and Consumer Secret into Tagregator's settings.

**Step 2)** [Add the [tagregator] shortcode to a post or page](http://codex.wordpress.org/Shortcode), and include the hashtag(s) you want to aggregate:

Examples:

`[tagregator hashtag="#WordPress"]`

`[tagregator hashtag="#wcsf, #wcsf14"]`


You can also enter keywords or search queries, like this:

`[tagregator hashtag="cooking"]`

`[tagregator hashtag="ice cream"]`


You can specify the number of columns you want with the `layout` attribute:

`[tagregator hashtag="#WordCamp" layout="one-column"]`

`[tagregator hashtag="#WordCamp" layout="two-column"]`

`[tagregator hashtag="#WordCamp" layout="three-column"]`

The default is `three-column`. On mobile devices, it will automatically reduce to one or two-columns in order to fit on the screen.

**Step 3)** Wait 30-60 seconds for the plugin to pull new content in.


== Frequently Asked Questions ==

= I added my API keys and setup the shortcode, but no posts have been imported =
There could be something wrong with your API credentials or network that is causing the API requests to fail. Tagregator logs the raw responses to assist in debugging.

To view the logs, add this line to a [functionality plugin](http://wpcandy.com/teaches/how-to-create-a-functionality-plugin/), and then visit the Tagregator > Log page.

`add_filter( 'tggr_show_log', '__return_true' );`

**Warning:** The logs will contain your private API keys, so don't post them on public forums, etc.

= Why do posts show up with the wrong time? =
This is probably because you haven't configured your timezone in WordPress's General Settings. After updating the timezone, you may need to wait up to 23 hours for new posts to appear ahead of the ones that were saved with the old timezone.

= Why are some Tweets missing? =
Twitter's API doesn't guarantee that every tweet will be available in the results it returns.

= I liked the single-column look of versions 0.4 and 0.5 better, how can I get that back? =
You can achieve a similar look by specifying `one-column` in the shortcode's `layout` parameter:

`[tagregator hashtag="#WordCamp" layout="one-column"]`

= How should I disclose security vulnerabilities? =
If you find a security issue, please disclose it to us privately via [Automattic's HackerOne bounty program](https://hackerone.com/automattic), so that we can release a fix for it before you publish your findings.

= Can I create my own media sources for services that aren't included (e.g, Facebook, Vine, etc) =
Yes, Tagregator allows you to add custom modules that you develop for other services by hooking into the `tggr_media_sources` filter and adding an instance of your class.

The best way to get started is by [downloading the example plugin](http://plugins.svn.wordpress.org/tagregator/assets/tagregator-custom-media-source-example.zip) and customizing it to fit your needs.

Once you're done, please consider sharing it with others by [submitting it to the WordPress.org repository](http://wordpress.org/plugins/about/).

== Screenshots ==

1. An example of how the social media stream looks with a dark background.
1. An example of how it looks with a light background; also shows how highlighted posts can look.
1. The settings panel.


== Changelog ==

= v0.6 (2014-11-10) =
* [NEW] Switch back to Masonry layout, with several improvements (props [ryelle](https://profiles.wordpress.org/ryelle), [melchoyce](https://profiles.wordpress.org/melchoyce)).
* [NEW] Added settings field for highlighted accounts, which get an extra CSS class so they can be styled differently than normal posts (props [ryelle](https://profiles.wordpress.org/ryelle)).
* [NEW] Only retrieve new posts when the user is viewing the top of the shortcode output (props [ryelle](https://profiles.wordpress.org/ryelle)).
* [NEW] Show a spinner when loading new posts.
* [NEW] Multiple hashtags in a single shortcode are now supported.
* [NEW] Add a basic logger to assist with troubleshooting API calls.
* [UPDATE] Imported posts will no longer appear in front-end search results.
* [UPDATE] Show an excerpt instead of the full post if the content is longer than 200 characters.
* [FIX] Using a semaphore to prevent race conditions which resulted in duplicated posts.

= v0.5 (2014-07-23) =
* [NEW] Add Google+ media source (props [fahmiadib](https://profiles.wordpress.org/fahmiadib)).
* [NEW] Add media source icons to items view (props [digne](https://profiles.wordpress.org/digne)).
* [UPDATE] Retrieve new content immediately when the page loads.

= v0.4 (2013-12-04) =
* [FIX] Fixed a fatal PHP error on new site activation in Multisite networks.
* [FIX] Fixed a PHP notice when assigning hashtags to posts
* [NEW] Added support for Flickr.

= v0.3 (2013-10-14) =
* [FIX] Fixed "tggrData is not defined" bug.
* [NEW] New single-column design (props [shaunandrews](https://profiles.wordpress.org/shaunandrews)).
* [NEW] Instagram support added.
* [NEW] Pre-fetch media items when the shortcode is setup so they'll be available immediately.
* [NEW] Hashtags and usernames inside Tweets are automatically converted to links.
* [UPDATE] Replaced `global $post` statements with calls to `get_post()`.

= v0.2 (2013-10-09) =
* [FIX] No longer assuming that term slug matches sanitized version of term name. Fixes bug where Tagregator term would be created with "-2" and would never get posts.
* [NEW] Images attached to Tweets are now displayed.
* [NEW] Retweets are no longer imported.
* [NEW] URLs inside posts are now converted to hyperlinks.
* [UPDATE] Tweet content sanitized with wp_kses() instead of sanitize_text_field().
* [UPDATE] Moved all includes to bootstrapper.

= v0.1 (2013-09-17) =
* [NEW] Initial release


== Upgrade Notice ==

= 0.6 =
Version 0.6 returns to a Masonry layout.

= 0.5 =
Version 0.5 adds support for Google+.

= 0.4 = 
Version 0.4 adds support for Flickr and fixes a few bugs.

= 0.3 = 
Version 0.3 has a new single-column design and support for Instagram.

= 0.2 =
Version 0.2 displayed images attached to tweets and ignores retweets.

= 0.1 =
Initial release.