=== Form Manager ===
Contributors: hoffcamp
Donate link: http://www.campbellhoffman.com/
Tags: form, forms, form manager
Requires at least: 3.0.0
Tested up to: 4.0
Stable tag: 1.7.0

Put custom forms into posts and pages using shortcodes. Download submissions in .csv format.

== Description ==

Form Manager is a tool for creating forms to collect and download data from visitors to your WordPress site, and keeps track of time/date and registered users as well.  Forms are added to posts or pages using a simple shortcode format, or can be added to your theme with a simple API. 

= Features =
* validation
* required fields
* custom acknowledgments
* e-mail notifications.   
* form display templates

= Supported field types =

* text field
* text area
* dropdown
* radio buttons
* checkbox / checkbox list
* multiline select
* file upload
* reCAPTCHA

Subtitles and notes can also be added to the form in any location.

= Publishing a Form =
Forms are placed within posts or pages.  Look for the Form Manager button in your post editor towards the right (Thanks to [Andrea Bersi](http://www.andreabersi.com)).  

You can also type in shortcodes yourself.  For example, if your form's slug is 'form-1', put the following within a post or page: 

`[form form-1]`  
  
  
<br />
= Languages =

* Espa&ntilde;ol (es_ES) - [Eduardo Aranda](http://sinetiks.com)
* Italiano (it_IT) - [Andrea Bersi](http://www.andreabersi.com)
* Nederlands (nl_NL) - Dani&euml;l Karssen, [Sander Kolthof](http://www.fullcirclemedia.nl)
* Portugu&ecirc;s (Brazil) (pt_BR) - [Samuel Martins](http://www.samuelmartins.com.br)
* &#1056;&#1091;&#1089;&#1089;&#1082;&#1080;&#1081; (ru_RU) - [Ellena Murawski](http://www.artmind.com.ua)
* Fran&ccedil;ais (fr_FR) - [Serge Rauber](http://kalyx.fr)
* &#1662;&#1575;&#1585;&#1587;&#1740; (fa_IR) - Salman
* &#27491;&#39636;&#20013;&#25991; (zh_TW) - [&#39321;&#33144;](http://sofree.cc)
* Chinese Simplified (zh_CN)

== Changelog ==
= 1.7.0 =
* Fixed 'housekeeping suicide' multisite bug
* Tested up to 4.0
* Added Chinese Simplified translation

= 1.6.49 =
* Fixed data table not showing multiple pages
* Fixed formdata shortcode not using default template

= 1.6.48 =
* Fixed data table sorting.

= 1.6.47 =
* Fixed DB access to use $wpdb.
* Fixed 'hidden fields' to use their default values.
 
= 1.6.46 =
* Added DONOTCACHEPAGE to forms (optional). Thanks to Eduardo Aranda for the help!
* DONOTCACHEPAGE disabled by default for plugins installed at 1.6.45 and prior, enabled by default otherwise.
* reCAPTCHA uses current protocol.

= 1.6.45 =
* Added Chinese traditional translation, thanks to &#39321;&#33144;.

= 1.6.44 =
* Added option to include HTML tags as literal text, instead of stripping the tags.

= 1.6.43 =
* Previous version would not activate.

= 1.6.42 =
* Added checks to detect if the form manager tables are still valid (some security plugins rename tables).
* Fixed an unescaped character bug in the conditions editor, which would cause the conditions to not save.

= 1.6.41 =
* Added 'Media' option for file uploads (can render as images)
* Added non-US date format for text input
* Fixed a bug causing settings saves to fail= 1.6.40 =
* 'Checked by default' works 

= 1.6.39 =
* CSV files show URL instead of file name for file uploads
* 'Insert Saved Form' bug fixed
* Added option to disable autocomplete for forms

= 1.6.38 =
* Fixed a bad array index bug
* Empty conditions no longer break scripts
* Added 'autocomplete' to the form tag

= 1.6.37 =
* Removed IE placeholder simulation scripts

= 1.6.36 =
* Fixed IE placeholder bug
* Added option in Form > Advanced to specify form action manually
* Added html and body tags to the default e-mails
* Added option to turn of default CSS

= 1.6.35 =
* Fixed data table CSS class names

= 1.6.34 =
* Added Persian language
* Added RTL language support for reCAPTCHA
* Added option to include scripts with form render instead of in footer (for AJAX loaded posts)

= 1.6.33 =
* Fixed bug for checkbox lists (not escaping special characters)

= 1.6.32 =
* Removed debug output from formdata summary output

= 1.6.31 =
* Fixed data table bug for new forms
* Added 'required' option for file uploads

= 1.6.30 =
* Added French translation, thanks to Serge Rauber. Updated Dutch, thanks to Dani&euml;l Karssen
* Data table formatting fix, thanks to Eduardo Aranda
* 'Show' and 'hide' options work with non-table data lists
* Added 'showprivate' option to formdata shortcode to show private fields
* Option to hide empty fields in summary
* Include the form's parent post ID in submission data

= 1.6.29 =
* Fixed summary list template bug
* Fixed form action for non-pages / non-posts

= 1.6.28 =
* Fixed data table output formatting

= 1.6.27 =
* Fixed file upload URL bug
* Fixed radio button condition bug
* Fixed conditions compatibility with Chrome
* Fixed IE8 placeholder bug
* Added defalut value option for list items
* Fixed activation error due to blank charset / collation values

= 1.6.26 =
* Minor changes

= 1.6.25 =
* Fixed required condition bug
* Fixed greater than / less than condition bug
* Added option to disable nonce check on form submission

= 1.6.24 =
* Fixed file upload link bug

= 1.6.23 =
* Fixed conflict with portfolio slideshow
* Fixed multiple submission bug
* Added missing headers from default e-mail notifications (encoding)

= 1.6.22 =
* Fixed conflict with Facebook Simple Connect

= 1.6.21 =
* Fixed conflict with CKEditor plugin
* Fixed conditions in IE8 & IE9

= 1.6.20 =
* Fixed file upload type bug
* Added shortcodes to acknowledgement message
* Added 'post_url' shortcode for published posts

= 1.6.19 =
* Changed CSV download method to avoid writing files.

= 1.6.18 =
* Added option to allow certain HTML tags
* Added Russian language translation

= 1.6.17 =
* Admin no longer receives two e-mails if both 'send to admin' and 'send to user' are checked
* Added a pluggable action for form submission

= 1.6.16 =
* Fixed bugs on the form data page
* Fixed multisite error
* Added option to use PHP mail() instead of WP wp_mail()
* Fixed a MySQL default value error on some systems
* Added shortcodes to E-Mail 'Subject' and 'To' fields

= 1.6.15 =
* Added form id, submission id to e-mail shortcodes

= 1.6.14 =
* Fixed MySQL warnings about default values for text fields

= 1.6.13 =
* Added zip, U.S. state, and dimensions validators
* Option to change the timestamp format for uploaded files

= 1.6.12 =
* Data top level link removed, since it was broken. Will be replaced later.

= 1.6.11 =
* Added post status option for publishing submissions

= 1.6.10 =
* Added an 'all' option for CSV download
* Fixed summary view bug

= 1.6.9 =
* Minor interface changes
* Fixed file creation bug on some systems

= 1.6.8 =
* Added top level links to form data
* Submission data 'edit capability' option now applies to summary view editing
* Added members capabilities for .CSV file download
* Fixed CSV data bug, (missing timestamp, user, user IP)

= 1.6.7 =
* Fixed show/hide column bug

= 1.6.6 =
* Added capabilities for viewing data columns

= 1.6.5 =
* Added nicknames to private fields
* Fixed show/hide for editing private fields
* Fixed checkbox list bug

= 1.6.4 =
* Minor changes

= 1.6.3 =
* Fixed nickname update bug
* Updated Spanish translation
* Minor interface improvements

= 1.6.2 =
* Added option to change the 'registered users only' message
* Added option to select filesystem method
* Fixed data edit bug for blank text boxes
* Updated Italian, Portuguese

= 1.6.1 =
* Added friendly upgrade notices

= 1.6.0 =
* New 'Submission Data' section, with search and date range
* Download CSV of search results
* More permissions for the 'Members' plugin
* New ID and Tracking Number fields
* Fixed radio button condition bug

= 1.5.29 =
* Fixed default form value bug

= 1.5.28 =
* Fixed data summary bug
* Fixed data table shortcode bug

= 1.5.27 =
* Fixed reCAPTCHA bug

= 1.5.26 =
* Updated internationalization

= 1.5.25 =
* Fixed date validator bug

= 1.5.24 =
* Fixed conflict with Gantry Framework
* Fixed checkbox bug
* Added %wp_uploads% code for file uploads

= 1.5.23 =
* Textarea uses placeholder rather than default value

= 1.5.22 =
* Added warning about invalid regular expressions for custom validators
* Empty conditions no longer cause the validator script to break
* Updated Italian translation

= 1.5.21 =
* Fixed validation bug

= 1.5.20 =
* Fixed submission data problem with large forms

= 1.5.19 =
* Fixed checkbox list condition bug

= 1.5.18 =
* File creation now uses wp_filesystem

= 1.5.17 =
* Fixed submit button alignment

= 1.5.16 =
* Fixed bug in form template functions

= 1.5.15 =
* Added shortcodes for published post titles

= 1.5.14 =
* Fixed a bug when repopulating a form after a failed submission

= 1.5.13 =
* Fixed multiple submission bug
* Scripts appear within CDATA sections

= 1.5.12 =
* Added API for the form user's JavaScript environment

= 1.5.11 =
* Fixed internationalization for certain parts of the plugin

= 1.5.10 =
* Added a 'table' view for the form data display shortcode
* Fixed the timezone for timestamps
* Added options to change data table column types

= 1.5.9 =
* Added links to published submissions in the data page

= 1.5.8 =
* Improved conditions editor
* Fixed bug when uploading files with Unicode file names
* Added some missing internationalization handles
* Conditions can apply to 'file' inputs
* Added submission information to the main page

= 1.5.7 =
* Fixed a bug when updating a form element's nickname

= 1.5.6 =
* Fixed permissions bug
* Fixed CSV download bug
* Added separators, notes, and recaptchas to the items you can show/hide with conditions.

= 1.5.4 =
* Fixed install issues on certain platforms.  Thanks to Metin Kale. 

= 1.5.3 =
* Added an option to disable the TinyMCE button in the 'Advanced' settings page

= 1.5.2 =
* Files can be uploaded to a directory of your choosing
* Links in summaries / e-mails to uploaded files, if they are in a directory

= 1.5.1 =
* Fixed script loading bug in certain environments

= 1.5.0 =
* Added conditional behavior, e.g., only show certain items based on the values of other items
* Dutch language support (Thanks to [Sander Kolthof](www.fullcirclemedia.nl))
* Fixed '0 kB' summary bug
* Fixed checkbox default value bug

= 1.4.23 =
* Editor/Data/Advanced for forms now uses a 'tabbed' interface
* Added database check for troubleshooting
* Added checkbox positioning option
* Added more specific capabilities for Members plugin

= 1.4.22 =
* Notes can display HTML

= 1.4.21 =
* Added 'maximum length' attribute for text inputs
* Added tinyMCE button. (Many thanks to [Andrea Bersi](http://www.andreabersi.com))

= 1.4.20 =
* Fixed install error

= 1.4.19 =
* Added auto-redirect option

= 1.4.18 =
* Added fm_getFormID() to API, returns a form's ID number from a slug
* Fixed bug in formdata shortcode 'orderby' attribute
* Fixed reCAPTCHA bug
* Added support for placeholders in non HTML 5 browsers

= 1.4.17 =
* Italian language support (Thanks to [Andrea Bersi](http://www.andreabersi.com))
* Specify custom theme for reCAPTCHA
* Fixed problems when trying to edit submission data
* Added more capabilites to the Members plugin

= 1.4.16 =
* Publish submitted data to posts
* Show a table of all submissions within a post
* Fixed IE download issues
* Fixed Unicode issues with CSV / ZIP downloads
* Integration with WP-SlimStat

= 1.4.15 =
* Fixed 'show summary' error
* Fixed CSV download with international characters
* Admins can edit posted data
* Minor interface changes
* Compatibility for internationalization added
* CSS class names for each form item
* Custom capabilities, integration with the Members plugin

= 1.4.14 =
* Fixed install error

= 1.4.13 =
* Minor bug fixes

= 1.4.12 =
* Added 'template reset' in advanced settings

= 1.4.11 =
* Minor bug fixes

= 1.4.10 =
* Minor bug fixes

= 1.4.9 =
* Added e-mail notification customization to 'Advanced' form settings

= 1.4.8 =
* Fixed install error for 1.4.7

= 1.4.7 =
* Fixed e-mail list

= 1.4.6 =
* Added text entry for list options
* Moved 'Templates' and 'Behavior' to a new 'Advanced' settings page for forms

= 1.4.5 =
* Fixed summary template formatting

= 1.4.4 =
* Added file upload form element
* Save script bug fixes

= 1.4.3 =
* Added IP address to submission data
* Fixed the summary template timestamp label

= 1.4.2 = 
* Fixed e-mail list bug

= 1.4.1 =
* Fixed saved bug

= 1.4.0 =
* Templates for e-mail notifications and form display, similar to WordPress theme functionality
* HTML 5 placeholders in supported browsers
* E-mail notification conflict with certain hosts
* Fixed 'list' option bug when creating a new list

= 1.3.15 =
* Fixed asterisks appearing below labels
* Fixed include bug with XAMPP

= 1.3.14 =
* Added reCAPTCHA color scheme option in settings
* Fixed conflict with other plugins using Google RECAPTCHA

= 1.3.13 =
* Changed upgrade mechanism

= 1.3.12 =
* Added 'required item message' to form editor
* Fixed upgrade from 1.3.3 and older

= 1.3.11 =
* Full Unicode support
* Added date validator for text fields

= 1.3.10 =
* Added API stable fm_doFormBySlug($formSlug) to show forms within templates
* Admin can change plugin's shortcode in 'Advanced Settings'

= 1.3.9 =
* Fixed form behavior selection bug

= 1.3.8 =
* Fixed possible style conflict with Kubric (Default) theme

= 1.3.7 =
* Fixed 'fm_settiings' table install error

= 1.3.6 =
* Advanced settings page
* Custom text validators using regular expressions

= 1.3.5 =
* E-mail notifications for registered users
* Admin and registered user e-mail notifications are now a global rather than per form setting.

= 1.3.4 =
* Added e-mail notification for user input (acknowledgment e-mail)
* Changed editor interface

= 1.3.3 =
* Adjusted for register_activation_hook() change
* Fixed some CSS style names likely to have conflicts

= 1.3.2 =
* Added reCAPTCHA field
* Added Settings page
* Multiple forms per page
* Fixed CSV data double quote bug
* Improved acknowledgement formatting

= 1.3.1 =
* Fixed 'Single submission' behavior bug
* Items in form editor update when 'done' is clicked
* Fixed list option editor bug

= 1.3.0 =
* Added form behaviors for registered users
* Cleaned up data page
* Added data summary to data page

= 1.2.10 =
* Rearranged editor sections
* Fixed checkbox list 'required' test
* Added single checkbox 'requried' test

= 1.2.9 = 
* Fixed .csv download bug

= 1.2.8 =
* Added e-mail notifications. 

= 1.2.5 =
* Fixes multisite edit/data page bug. 

= 1.2.4 =
* Fixes an installation error when starting with a fresh plugin install.


*** I am starting work on version 2.  If you have suggestions or requests, please let me know! ***

== Installation ==

Method 1: Activate the 'WordPress Form Manager' plugin through the 'Plugins' menu in WordPress.  

Method 2: Download the source code for the plugin, and upload the 'wordpress-form-manager' directory to the '/wp-content/plugins/' directory.

== Frequently Asked Questions ==

Please visit [www.campbellhoffman.com/form-manager-faq/](http://www.campbellhoffman.com/form-manager-faq/) for FAQ and tutorials.