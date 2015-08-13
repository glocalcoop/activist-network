The plugins for version 1.0 of the Multisite are listed below. Custom styling is done in the main "Community" theme. Each plugin has its own Sass partial, so you can easily edit the associated styling or remove the plugin altogether. Those styles are in `/wp-content/themes/nycprepared/library/sass/plugins`

## jQuery

[Isotope](http://isotope.metafizzy.co/index.html) — Filters and sorts the directory


## WordPress

### Events
[Events Manager](http://wp-events-plugin.com/) — Highly flexible calendar that can aggregate sub-blog events onto the main site.

Events Manager is allows the main website on a WordPress multisite to display all events, as well as each site have it's own event listing. However, for this to work there are a few settings that need to be configured on the Network Admin level initially.

#### Network Settings

1. Needs to be network activated
1. Go to new Events Manager menu in the Network Admin
1. Configure the Global multisite settings as follows:
   *  Enable global tables mode? Yes
   *  Display global events on main blog? Yes
   *  Link sub-site events directly to sub-site? Yes
   *  Locations on main blog? No
   *  Display global locations on main blog? Yes
   *  Link sub-site locations directly to sub-site? Yes
   *  Global location slug: location
   *  Apply global capabilities? Yes

See screenshots for reference:
![EM Multisite Options](http://sync.glocal.coop/docs/_media/undefined/em-multisite-options.png)

#### Main Website Configuration

On the Main site of the multisite, which lives at http://mydomain.urg/wp-admin/ there are some Event Manager settings that can be managed from there for the whole network, after the network settings have been configured as noted above.

_**General Event Settings**_
**General Options**

* _Enable recurrence?_ - This allows events to be added that happen on a regular basis, like weekly meetings or monthly gatherings. If you say no to this option, sub-sites will not be able to use this option either.
* _Enable bookings?_ - This refers to registration for events. If you would like to allow folks to be able to user registration forms for events, you would say yes to this option.
* _Enable tags?_ - Similar to posts, this is a event specific tag that can be entered on any site. It allows sub-sites to create a personalized taxonomy for events that isn't controlled from the network.
* _Enable categories?_ - Saying yes to this allows you to set a broad set of categories that sub-sites can choose from so events appear within that category on the main site category listing. Example categories could be:
   * Meeting
   * Call
   * Workshop
   * Lecture
   * etc…
Go to Events > Categories to add the categories for events to be used by the network of sites.
* _Enable event attributes?_ - Attributes is a way to add custom fields to front-end publishing event form. However, this feature is limited to text areas and select dropdowns. More detail provided here…to come.
* _Enable event custom fields?_ - This is a back end option which is not used in WordPress in this way anymore, so there we do no recommend you enable this option for usage.
* _Enable locations?_ - This allows folks to add a location to the event that can then be seen as a map on the event itself and can be shown on a global map page as well.

### Newsletter
[ALO EasyMail](http://www.eventualo.net/blog/wp-alo-easymail-newsletter/) — This plugin allows each site to have a way to collect email addressses & send newsletters. It creates a Newsletter menu in each site Admin dashboard and provides a widget for adding to the sidebar. 

### User Profile
[Theme My Login](http://www.jfarthing.com/development/theme-my-login/) - Themed user Profile & login settings
 After installing plugin, Activate it. For Multsite Activate it network wide. There are no settings at the Network level, however there will be a new dashboard main menu for each site called TML.

There are a few initial settings that need to be saved. The ones we recommend at a minimum are:

Leave the first two options checked as they are
* Enable “theme-my-login.css”
* Enable e-mail address login
Also check off & save:
* Enable Custom Passwords
* Enable Themed Profiles

Once saved a new menu item appears, you can restrict access to dashboard & which profiles are themed.

This plugin also create 6 pages for front-end user access:
* Log In
* Log Out
* Lost Password
* Register
* Reset Password
* Your Profile


[WP Biographia](http://wordpress.org/plugins/wp-biographia/) - Manage user profile social link fields and author box settings

This plugin extends the WP User Profile to include:

* Author box at the end of posts/pages
  * With some color control
* More Social Networks for user profile

There are no network level settings for this, Once the default settings have been configured on the main site, they can be copied onto all new sites by the configurations in the Network-wide Options 

[WP User Avatar](http://wordpress.org/plugins/wp-user-avatar/) - Allows site managed avatars in profiles, as opposed to Gravatar

This plugins gives the site control of the default avatar image and gives users a way to manage their own avatar within the WP User profile.

Each site can control the settings this from Settings > WP User Avatar.

One the default settings have been configured on the main site, they can be copied onto all new sites by the configurations in the Network-wide Options 

[Join My Multisite](http://halfelf.org/plugins/join-my-multisite/) - Allows users to subscribe on a multisite on a site by site basis, with admin approval

By default, sub-sites on a multisite are not able to allow visitors to register for that specific site. By activating this plugin, you give your Site Admins the following options:

* Auto-add users
* Have a 'Join This Site' button in a widget
* Keep things exactly as they are
* Create a per site registration page
* Use a shortcode to put a 'join this site' button on any page/post.

Each site will have settings under Users > Join my Multisite along with a widget & shortcode that can be used based on the settings selected. 

[User Role Editor](http://wordpress.org/plugins/user-role-editor/) - Manage user permissions for the network or on a site by site bases. Can add custom user roles too.

User Role Editor is a plugin that provides some settings at the Network Level. Activate the Plugin network-wide and a new menu will appear in the Network under Settings > User Role Editor.

We recommend the following settings:

* In the General tab check off & save
  * Show Administrator role at User Role Editor
  * Show capabilities in the human readable form
* On the multisite tab check off & save:
  * Allow non super administrators to create, edit, and delete users

After that each site has a menu item under Roles > User Role Editor where they can edit capabilities of existing Roles & add new ones. 

### Extras

[Display Widgets](http://wordpress.org/plugins/display-widgets) - This plugin gives website admins the ability to control what pages, posts or sections a widget appears in, as well as, logged in and logged out user only info. 

There are no network or site specific settings. 

[Exclude Pages From Navigation](http://wordpress.org/plugins/exclude-pages/) -  This plugin adds a checkbox on pages in order to hide/exclude it from the sitemap. This is helpful for pages that are not intended to be part of the site map, like thank you pages after someone registers for a newsletter or event.

There are no network or site specific settings. 

### Global Category Terms

[Global Terms](https://wordpress.org/plugins/mu-global-terms/) — Makes category terms global to the entire network. It uses the main site's table to store all the terms.

### Syndication
TBD