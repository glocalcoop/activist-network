=== Polylang ===
Contributors: Chouby
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=CCWWYUUQV8F4E
Tags: multilingual, bilingual, translate, translation, language, multilanguage, international, localization
Requires at least: 4.0
Tested up to: 4.4
Stable tag: 1.8
License: GPLv2 or later

Making WordPress multilingual

== Description ==

= Features  =

Polylang allows you to create a bilingual or multilingual WordPress site. You write posts, pages and create categories and post tags as usual, and then define the language for each of them. The translation of a post, whether it is in the default language or not, is optional.

* You can use as many languages as you want. RTL language scripts are supported. WordPress languages packs are automatically downloaded and updated.
* You can translate posts, pages, media, categories, post tags, menus, widgets...
* Custom post types, custom taxonomies, sticky posts and post formats, RSS feeds and all default WordPress widgets are supported.
* The language is either set by the content or by the language code in url, or you can use one different subdomain or domain per language
* Categories, post tags as well as some other metas are automatically copied when adding a new post or page translation
* A customizable language switcher is provided as a widget or in the nav menu
* The admin interface is of course multilingual too and each user can set the WordPress admin language in its profile

If you wish to use a professional or automatic translation service, you can install [Lingotek Translation](https://wordpress.org/plugins/lingotek-translation/), as an addon of Polylang. Lingotek offers a complete translation management system which provides services such as translation memory or semi-automated translation processes (e.g. machine translation > human translation > legal review).

If you wish to migrate from WPML, you can use the plugin [WPML to Polylang](https://wordpress.org/plugins/wpml-to-polylang/)

= Credits =

Thanks a lot to all translators who [help translating Polylang](https://translate.wordpress.org/projects/wp-plugins/polylang).
Thanks a lot to [Alex Lopez](http://www.alexlopez.rocks/) for the design of the banner and the logo.
Most of the flags included with Polylang are coming from [famfamfam](http://famfamfam.com/) and are public domain.
Wherever third party code has been used, credit has been given in the code’s comments.

= Do you like Polylang? =

Don't hesitate to [give your feedback](http://wordpress.org/support/view/plugin-reviews/polylang#postform). It will help making the plugin better. Other [contributions](http://polylang.wordpress.com/documentation/contribute/) (such as new translations or helping other users on the support forum) are welcome !

== Installation ==

1. Make sure you are using WordPress 4.0 or later and that your server is running PHP 5.2.4 or later (same requirement as WordPress itself)
1. If you tried other multilingual plugins, deactivate them before activating Polylang, otherwise, you may get unexpected results !
1. Install and activate the plugin as usual from the 'Plugins' menu in WordPress.
1. Go to the languages settings page and create the languages you need
1. Add the 'language switcher' widget to let your visitors switch the language.
1. Take care that your theme must come with the corresponding .mo files (Polylang automatically downloads them when they are available for themes and plugins in this repository). If your theme is not internationalized yet, please refer to the [codex](http://codex.wordpress.org/I18n_for_WordPress_Developers#I18n_for_theme_and_plugin_developers) or ask the theme author to internationalize it.

== Frequently Asked Questions ==

= Where to find help ? =

* First time users should read [Polylang - Getting started](http://plugins.svn.wordpress.org/polylang/doc/polylang-getting-started.pdf), a user contributed PDF document which explains the basics with a lot of screenshots.
* Read the [documentation](http://polylang.wordpress.com/documentation/). It includes [guidelines to start working with Polylang](http://polylang.wordpress.com/documentation/setting-up-a-wordpress-multilingual-site-with-polylang/), a [FAQ](http://polylang.wordpress.com/documentation/frequently-asked-questions/) and the [documentation for programmers](http://polylang.wordpress.com/documentation/documentation-for-developers/).
* Search the [support forum](https://wordpress.org/search/). You will most probably find your answer here.
* Read the sticky posts in the [support forum](http://wordpress.org/support/plugin/polylang).
* If you still have a problem, open a new thread in the [support forum](http://wordpress.org/support/plugin/polylang).
* If you want to use professional or automatic translation services, install and activate the [Lingotek Translation](https://wordpress.org/plugins/lingotek-translation/) plugin.

= How to contribute? =

See http://polylang.wordpress.com/documentation/contribute/

== Screenshots ==

1. The Polylang languages admin panel
2. The Strings translations admin panel
3. Multilingual media library
4. The Edit Post screen with the Languages metabox

== Changelog ==

= 1.8 (2016-01-19) =

* Minimum WordPress version is now 4.0
* Add ary, bn_BD, en_ZA, es_AR, fr_CA and fr_BE to the predefined languages list
* Adopt WordPress coding standards
* New structure for translated posts and terms (=> several methods of PLL_Model are deprecated).
* Revamp the management of the static front page and page for posts
* Improve performance for navigation menus with a lot of pages
* The Polylang and WPML API are now loaded when 'plugins_loaded' is fired (on frontend only if at least one language has been defined)
* Add 'pll_get_post_translations()' and 'pll_get_term_translations()' to the API
* Add filter 'pll_cookie_expiration' to change the cookie expiration time
* Add support for 'wpml_get_language_information' function from the WPML API
* The default language is now managed directly from the languages list table
* Various accessibility improvements
* It is now possible to choose the languages flags from the available list (custom flags on frontend still work as previously)
* Revamp the settings page (now a list table with inline configuration)
* Add an option to remove all data when uninstalling the plugin
* Add test of subdomains and domains accessibility
* Add post state for translations of the front page and posts page
* Add better support of the customizer menus introduced in WP 4.3
* Media taxonomies (created by 3rd party plugins) are now filtered by language when editing a media
* Synchronization of taxonomies (created by 3rd party plugins) and meta are now enabled for media
* The 'hreflang' tag now refers to the locale instead of the 2-letters language code
* Workaround for WordPress locales not being W3C valid (see #33511)
* Workaround a bug in Nextgen Gallery causing redirect on album
* Add compatibility with Duplicate Post plugin to avoid duplicated post keeping the link to translations
* Add compatibility with Jetpack Related Posts
* fix: incorrect rewrite rules after changing how the language is set (need to flush rewrite rules after this)
* fix: password protected pages don't work on multiple domains
* fix: ensure that the page parent is in the correct language when using bulk edit
* fix: is_tax set on category and post tags archives when it should not
* fix: automatically added new top-level pages to menus are not filtered by language
* fix: nav menus locations are messed when changing the default language
* fix: error 404 for untranslated taxonomies pages
* fix: single posts and pages links do not include the language code when using the default permalinks and forcing the language code in url
* fix: missing trailing slash on home url when using default permalinks or a static front page
* fix: sticky visibility is copied to new translation only if the synchronization is activated
* fix: remove "» Languages » [language name]" from the feed title
* fix: spaces are not honored when searching strings translations
* fix: default language not set and terms translations not correctly imported when using WordPress Importer
* fix: the browser language detection does not differentiate 'en_US' and 'en_GB'
* fix: non alphanumeric characters query vars values lead to an infinite redirection loop on static front pages
* fix: user profile not saved for a language when the language code contains a "-"
* fix: non translated posts page always link to the static front page even when they should not
* fix: remove hreflang="x-default" when using one domain per language
* fix: deprecated function notice in WP 4.5 alpha
* fix: wrong url for attachments when media are translated and using subdomains
* fix: wrong url for unattached attachments when using subdirectories (since WP 4.4)
* fix: wrong url scheme for custom flags

See changelog.txt for older changelog
