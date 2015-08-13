There are 3 themes included with this distribution: 1 parent and 2 child themes. Your main multisite should use the Community theme. Your subsites will use the Community Group or Community Partner themes. The former is a fully hosted subsite, the latter is a simple profile page. Your networks (collections of groups and partners) are created as a custom post type.

## Parent
### Community (community)
Based on Bones, this theme contains templates that aggregate content from sites within a WP multisite network. 

#### Home Page (front-page.php)
* Displays sticky posts for the current site as featured posts
* Displays most recent posts from the other sites in the network
* Displays most recent posts of a specific category from the other sites in the network
* Displays most recent events from any site in the network (requires [Events Manager](http://wp-events-plugin.com/) plugin)
* Display the most recently created sites added to the network

**Dependencies:**
* [Wordpress Multisite](http://codex.wordpress.org/Create_A_Network) instance
* [Events Manager](http://wp-events-plugin.com/) plugin
* [Latest Network Posts](https://github.com/NYCPrepared/multisite/tree/master/wp-content/plugins/network-latest-posts) custom plugin

#### News (page-news.php)
Displays most recent posts from the other sites in the network.

**Dependencies:**
* [Latest Network Posts](https://github.com/NYCPrepared/multisite/tree/master/wp-content/plugins/network-latest-posts) custom plugin

#### Events (page-events.php)
Displays most recent events from the other sites in the network.

* [Events Manager](http://wp-events-plugin.com/) plugin

#### Directory (page-directory.php)
* Displays list of other sites in the network with filtering.
 * By Network
 * By Site Topic (proposed)
 * By Location (proposed)

**Dependencies:**
* [Network Landing Pages](https://github.com/NYCPrepared/multisite/tree/master/wp-content/plugins/network-landing-pages) custom plugin

#### Network Landing (single-network.php)
Displays custom header image, contact links, a description and recent posts from sites in the Network.

**Dependencies:**
* [Network Landing Pages](https://github.com/NYCPrepared/multisite/tree/master/wp-content/plugins/network-landing-pages) custom plugin

## Child Themes
### Community Group (community-group)
Intended to serve as a basic theme for organizations.

**Features**
* News
* Events
* Social Media Widgets

### Community Partner (community-partner)
Intended as a single profile-style page.

**Features**
* Contact Info
* RSS Feed Display
* Social Media Widgets