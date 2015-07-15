Activist Network
=========

The Activist network is a pre-configured WordPress Multisite that allows activists & organizers to create a space in which groups and projects can have their own website within a network. Groups' news, events & user profiles are shared across the network and highlighted on the main site.


## Functionality

This section refers to the back-end/admin features needed to organize the content within the templates. We will build around WordPress core.

### News


News is the posts (or blog) section of the site. Content here can be formatted around taxonomies & post formats.


### Taxonomy

The default taxonomies in WordPress are the Categories & Tags. Categories are global across sites to provide easy organization and navigation.


## Events

This is a custom post type created using the [[http://wordpress.org/plugins/events-manager/|Events Manager]] plugin that allows for a creation of global (or shared) events table to display events from all sub-sites on the main site. There is a wide variety of options available through the admin area of the site for customizations on a site by site basis and there are some network wide settings that allows for easier management.


## User Profiles

Individuals will be able to register as users to the network to get notifications and add content based on what the site admins decide. For this front-end profile pages will be provided for users to add their bio and social links to appear below each post or event they post and create a custom author or user template. For this we will use:


*  [[http://wordpress.org/plugins/wp-biographia/|http://wordpress.org/plugins/wp-biographia/]]
  * There are a lot of settings to allow site admins ways to control where this information appears. The rest is controlled within templates.
*  [[http://wordpress.org/plugins/theme-my-login/|http://wordpress.org/plugins/theme-my-login/]]
  * Provider frontend login, logout, reset password pages and a widget that allows to show links based on user role.
* [[http://wordpress.org/plugins/join-my-multisite/|http://wordpress.org/plugins/join-my-multisite/]] (if already a user on one site, if a user tries to register again it won't work, this will allow them to register to multiple sites with the same username.)


## Templates
TBD
