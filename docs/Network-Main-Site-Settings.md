#Main Site Settings Only
1. Categories for posts
1. Remove 'Manage Categories' capability for Admins and Editors, so only Network Admins can
   1. Go to Users > User Role Editor
   1. Select Administrator role and deselect Manage Categories, make sure Apply to All Sites is selected and Save
   1. Select Editor role and deselect Manage Categories, make sure Apply to All Sites is selected and Save
1. go to main site dashboard
1. go to plugins on main site
   1. activate:network posts (custom)
   1. tadpole civicrm css
   1. tagregator (could not find this to install on the main site)
   1. wp biographia
   1. activist network site
1. Events
   1. Settings
      1. General Options
          1. Enable Bookings = NO
          1. Show some love = NO
          1. User Capabilities
               1. default is fine but this is where you can edit what roles can do for events.
          1. Event Submission Form
               1. if you want non registered users to submit events:
                  1. click Allow Anonymous event submissions
                  1. Select Default user
                  1. You can post  *[event\_form] *on whatever page you want
      1. Pages
           1. Event List/Archives
               1. Select Events under Events Pages
           1. Location List/Archives
               1. Select locations under locations page
           1. Event Categories
               1. Select categories under categories page
           1. Event tags
               1. select tags under tags page
           1. Other Pages
               1. Events Page 
               1. Locations Page
               1. If you will use this create new pages for each of these
      1. Formatting
           1. Events
               1. FOR US SITES ONLY 
                   1. List events by date title, change #j #M #y to #M #j #y
           1. Search Form
               1. change settings according to what you want
           1. Date/Time
               1. change to US format
           1. Maps
               1. default map width to 100%
               1. default map height to 500px (you can adjust this as necessary)
               1. Create new page and add these shortcodes
                   1. [locations\_map scope="future"]
                   1. [events\_list]
      1. Emails
           1. in all text ares find and delete `------------------------------- Powered by Events Manager - http://wp-events-plugin.com`
           1. Event Categories
               1. Add cats as necessary only network admin people can edit this. Keep them broad. (e.g. meetings, actions, calls)
1. Newsletter
       1. settings
           1. General Tab
               1. remove show credit banner
           1. Newsletter Tab
               1. make sure sender's email is correct
               1. Use themes section - set it to "no"
           1. Texts
               1. default is english only for multilibgual site you can add laguages in the box and click update
           1. Batch Sending
               1. Dana TO DO
           1. Permissions
               1. Default is fine, can change as needed
           1. Mailing List
               1. theres 3 types of mailing lists (1 way lists), choose which one suits your purposes.
           1. Bounces
               1. for subsites they have to set up an email account in civi for bounce processing
       1. Subscribers
           1. import/export subscribers
           1. click Import from WP registered users
       1. Add New
           1. to create newsletters
           1. Green button on the WYSIWYG toolbar, lets you add latest posts on the site
           1. check appropriate boxes for recipients
           1. hit publish
1. Forms
   1. you can use this if you don't want contacts going into the civi. can also use this for blog posts submitted.
1. Taggregator
   1. Settings
       1. enter all IDs and Keys for social media assets
       1. Then create a page or event and use a shortcode like this" [tagregator hashtag="#Ferguson, #FreewayRick, #EricGarner" layout="three-column"] to display the tweets, etc. 
1. Dashboard > Settings 
   1. General
       1. remove tagline
       1. select time zone
       1. select time format
       1. set week starts on to sunday
       1. save
1. Permalinks
   1. use post name
       1. Email
           1. create email on mayfirst 
           1. smtp host mail.mayfirst.org
           1. smtp port 25
           1. use smtp authentication
               1. What information is supposed to go here?
           1. send test email
1. WP Biographia
   1. remove checkmarks from everything except from display on individual posts
