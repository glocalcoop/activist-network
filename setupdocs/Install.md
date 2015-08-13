#Install ANP

##On MFPL 
1. Set name serversfor the domain to be used to a.ns.mayfirst.org and b.ns.mayfirst.org
1. Create a [hosting order for the domain name at](https://members.mayfirst.org/cp/)
1. Create email address for main admin user when WP gets installed follow [MFPL FAQ](https://support.mayfirst.org/wiki/faq/email/add-email-address)
    1. our notes?
1. Create Lists DNS to be able to use [mailing lists MFPL FAQ](https://support.mayfirst.org/wiki/set\_mailman\_domain)

###WordPress
1. Getting repo on Mayfirst hosting order
    1. SSH or SFTP into webroot and delete index.html file that gets automatically generated on each new hosting order
    1. Clone repo using: `git clone https://gitorious.org/glocal/activist-network.git .` 
    1. Add WP Core files with the following: 
            wget http://wordpress.org/latest.tar.gz
            tar zxf latest.tar.gz
            cd wordpress
            cp -rpf * ../
            cd ../
            rm -rf wordpress/
            rm -f latest.tar.gz
1. Create database for WP
    1. Go to hosting order for domain name >> mysql database >> add new item >> use PROJECT\_prod\_wp
    1. Then go to mysql database user >> add new item >> use PROJECT\_prod\_wp, remember password, grant full access
    1. Go back and set up wordpress
        1. localhost and table prefix remains the same
1. Configure WP Multisite
    1. Edit wp-config.php and add
              /* Multisite */
              define( 'WP\_ALLOW\_MULTISITE', true );
    1. From wp-admin > Tools > Network Setup
        1. Select domain preferece, select subdirectories
        1. take out site from network title
        1. click install
        1. Add the code snippets to wp-config.php and .htaccess files
        1. Logout and login again
1. Create a child theme for your network site
    1. Link to sample child theme

###CiviCRM
1. get existing hosting order (MFPL same as referenced above)
1. create database for Civi
    1. Go to hosting order for domain name >> mysql database >> add new item >> use PROJECT\_prod\_civi
    1. Then go to mysql database user >> add new item >> use PROJECT\_prod\_civi, remember password, grant full access
    1. Go back and set up WP Dashboard
1. Activate CiviCRM
    1. From the main site of the Multisite go to Settings > CiviCRM Installer
    1. enter password you created for civi database
    1. click recheck requirements
    1. click check requirements and install civi
1. Setup Civi Cron
    1. Setup cron email account on hosting order
    1. Login to MFPL server 
           crontab -e
    1. Add this to bottom of file updating everything in CAPS
           */15 * * * * /usr/bin/php /home/members/MEMBER/sites/ANP.DOMAIN.URL/web/wp-content/plugins/civicrm/civicrm/bin/cli.php -u USERNAME -p PASSWORD -e Job -a execute