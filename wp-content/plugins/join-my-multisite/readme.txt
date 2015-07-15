=== Join My Multisite ===
Contributors: Ipstenu
Tags: multisite, wpmu, registration, users
Requires at least: 3.7
Tested up to: 4.3
Stable tag: 1.7.7
Donate link: https://store.halfelf.org/donate/

Allow site admins to automatically add existing users to their site, or let users decide at the click of a button.

== Description ==

<em>This plugin is for Multisite instances only.</em>

When you want to add a user to every site on your network, you've got some pretty cool plugins for that as a network admin. But sometimes you want to let your site-managers have that control, and sometimes you want to make it optional.

By activating this plugin, you give your Site Admins the following options:

* Auto-add users
* Have a 'Join This Site' button in a widget
* Keep things exactly as they are
* Create a per site registration page
* Use a shortcode to put a 'join this site' button on any page/post.

It's really that simple! 

If they decide to auto-add, then any time a logged in user visits a site, they will be magically added to that site. If they decide to use a 'Join This Site' button, then they can customize the button message text for users who are logged in but not members, not logged in, or already members. Don't worry, if you have registration turned off, they won't see the 'register' button.

When you have registration turned on, each site can chose to use 'Per Site Registration,' which will allow them to create a page on their site just for registrations and signups. To display the signup code, just put <code>[join-my-multisite]</code> on the page.

* [Plugin Site](http://halfelf.org/plugins/join-my-multisite/)
* [Donate](https://store.halfelf.org/donate/)

==Changelog==

= 1.7.7 =
11 June, 2015

* Shortcode's URL filter should only run on the front end, since otherwise it breaks per-site adding new sites from My Sites (props <a href="https://wordpress.org/support/topic/create-a-new-site-links-to-perpage-custom-registration-page">Boyington</a>)
* Security Fix: Sanitizing and validating _POST calls properly to prevent evil.
* Security Fix: Implementing nonces.

= 1.7.6 =
12 February, 2015

* Renaming PLUGIN_DIR constant to JMM_PLUGIN_DIR to prevent conflicts. That was stupid of me. (props <a href="https://wordpress.org/support/topic/plugin-conflict-due-to-plugin_dir-constant?replies=2">Boyington</a>)

= 1.7.5 =
4 September, 2014

* Fixing static warning (props <a href="https://wordpress.org/support/topic/php-warnings-non-static-function?replies=1#post-5955721">Justin Fletcher</a>)
* 4.0 compat

= 1.7.4 =
15 May, 2014

* P tags around widgets

= 1.7.3 = 
10 Dec, 2013

* Fixed the broken join-this-site shortcode
* Corrected error on registration page calling blog_id without setting it
* Made reindeer and Macabee cookies

= 1.7.2 = 
27 Nov, 2013

* Removing 'None' role as an option, to prevent people from shooting themselves in the foot (props llocally)
* Fixing default_user_role to default_role (props llocally)
* Translation improvement on help screen
* Clarifying some BuddyPress things
* Gobble gobble

= 1.7.1 =
07 Nov, 2013

* Broke translations like a boss. Fixed.

= 1.7 =
06 Nov, 2013

* Fixing redirect error with signups (thanks <a href="http://wordpress.org/support/topic/error-500-on-wp-signupphp">bottapress</a>)
* Fixed Translations to be ready for Language Packs (see <a href="http://ottopress.com/2013/language-packs-101-prepwork/">Otto's post on prepwork to understand why</a>)
* Cleaned up some directions
* Resolved minor errors

= 1.6.1 =
20 Oct, 2013

* Adding translation to Nav Menu per suggestion by <a href="http://wordpress.org/support/topic/translation-again?replies=2#post-4750279">bottapress</a>
* Updated to match changes and inline docs in 3.7
* Changing page to match changes in http://core.trac.wordpress.org/ticket/18186 for WP 3.7

= 1.6 =
11 July, 2013

* Fixed shortcode output to work with text around it. Remember kids, shortcodes return, not echo. (also using ob_start etc to properly catch things in a better way).

= 1.5.4 =
5 July, 2013

* Wrong shortcode mentioned in in-line doc
* Better catching of the possible registration options (if users can register new blogs, they're asked to sign in and then join the site)

= 1.5.3 =
19 April, 2013

* Cleanup of settings and defines to show a better alert when you don't have everything set up.
* Corrected if-statement for display on admin end.

= 1.5.2 =
9 April, 2013

* New shortcode: <code>[join-this-site]</code> (was a secret, now is not!)
* Changed div content to div jmm-content, so you can style it how you want, and not break with weird themes - kudos @madri2

= 1.5.1 =
21 March, 2013

* Added before_signup_form() - kudos @madri2
* Redirect wp-signup to the page you defined. - kudos @madri2
* Check for it you're actually running Multisite (seriously, people?)

= 1.5 = 
15 March, 2013

* Translation cleanup
* Fixed empty param error

= 1.4.1 = 
6 Feb, 2013

* Minor typo on pages causing silly backend error. Bad copy/pasta on my part.

= 1.4 =
5 Feb, 2013 by Ipstenu

* More translation tweaks by dokkaebi
* Fixing issues with debug errors (nothing was broken, just ugly)

= 1.3 =

21 November, 2012 by Ipstenu

* Fixed uninstall issue

= 1.2 =

13 November, 2012 by Ipstenu

* Fixed issues as noted by [dokkaebi](http://wordpress.org/support/topic/problems-and-workarounds-using-v-11-on-wordpress-342)
* Added in option for login form

= 1.1 =
12 October, 2012 by Ipstenu

* Added in a per-site registration page option.
* Corrected bug where non-network admins couldn't make changes

=  1.0 =
07 October, 2012 by Ipstenu

* First completed version.

== Installation ==

This plugin is only network activatable. Configuration is done per-site via a page in the 'Users' section.

== Screenshots ==

1. Menu
1. Widget
1. Sample per-site registration front end

== Upgrade Notice ==

11-June 2015: Security update to sanitize post calls. This would have been tricky for people to take advantage of, but it doesn't hurt to be safe, right? Update please.

== Frequently Asked Questions ==

= This doesn't work if I'm not using Multisite =

It's not supposed to. "Join My <em>Multisite</em>", eh?

= What happens if the network doesn't allow registrations? =

If registration is turned off, the widget won't display anything for logged-out users.

The <code>[join-my-multisite]</code> shortcode will display a notice that registration is unavailable.

= How do I use the per-site registration page? =

<em>None of this will work if the Network Admin has not enabled registrations.</em>

First make a page for your registration. You can name it anything you want, however you can only use top-level pages (so domain.com/pagename/ and not domain.com/parentpage/childpage/). On that page, enter the shortcode <code>[join-my-multisite]</code> around any other content you want.

Next, go to Users > Join My Multisite and check the box to allow for Per Site Registration. Once that option is saved, a new dropdown will appear that will let you select a top-level page on your site. Select which page, and you are good to go.

= Can I put a button for signups on a page or in a post? =

Yep! Use <code>[join-this-site]</code>

= If I use the per-site registration, do I have to use the widget? =

Nope! In fact, you can even select 'none' (i.e. leave things as they are) and <em>still</em> use the per-site shortcode, because magic.

= What if the network allows registration and I don't make a site page? =

Then non-logged-in users will be redirected to the network registration page, and they may not be automatically added to your site (I'm working on that). I strongly suggest you create a page.

= How do I style the button? =

By default it will pick up whatever style your theme has, so if it styles buttons, you'll automatically match. If you want more, the css is `input#join-site.button` to play with the button.

= How do I style the per-site registration page? =

In your theme's CSS. This is basically the default WordPress signup page, just done in short-code form, so it will default to use your site's CSS anyway. The css falls under `.mu_register` of you want to override it in your theme.

= Can users sign up for a blog and an account via this plugin? =

No. That's such a massive network thing, the tinfoil hat in me didn't want to do it. You could fiddle with the signup page code, if you wanted, but I don't plan to support it.

= Is this BuddyPress compatible? =

As far as the BuddyPress basics go (one instance, network activated) it seems to, however it's not supported at this time. One user reported that with BP and JMM active, they weren't getting any emails for registration, so it's probably best to be used as a 'Join if you're logged in' thing, versus a 'Sign up on this site' one.

= Can I set global options? =

At this time, no. I don't have any interest in making per-site registration pages that are controlled by the network admin. You're welcome to fork or submit a pull request on the github repository - https://github.com/Ipstenu/join-my-multisite