#CiviCRM
CiviCRM is web-based, open source, Constituent Relationship Management (CRM) software geared toward meeting the needs of non-profit and other civic-sector organizations. You can read more about this software at http://civicrm.org. 

##Install
CiviCRM can be installed following these instructions on how to install on WordPress. Since a copy of CiviCRM is included in this repository, there will be no need to download, follow the [instructions from here forward](http://wiki.civicrm.org/confluence/display/CRMDOC/WordPress+Installation+Guide+for+CiviCRM+4.5#WordPressInstallationGuideforCiviCRM4.5-5.EnableCiviCRMpluginandruninstaller)

##Upgrades
**Always backup the database before doing any updates or upgrades.**

When pulling in Updates from the repo, CiviCRM code base will also be updated. Follow the [instructions noted here](http://wiki.civicrm.org/confluence/display/CRMDOC/WordPress+Installation+Guide+for+CiviCRM+4.5#WordPressInstallationGuideforCiviCRM4.5-10.UpgradeCiviCRM), specifically updating the database after updating the code base run the Upgrade script at: `http://www.YOURSITE.org/wp-admin/admin.php?page=CiviCRM&q=civicrm/upgrade&reset=1`

##Extensions
Extension in CiviCRM are like plugins in WordPress. For this to be used within the Activist Network we have a created directories in wp-content/civicrm-custom/extensions. These settings will have to be added into the following two places: 
 
 *  Directories: `http://yourdomain.org/wp-admin/admin.php?page=CiviCRM&q=civicrm/admin/setting/path&reset=1`
 *  Resource URLs: `http:yourdomain.org/wp-admin/admin.php?page=CiviCRM&q=civicrm/admin/setting/url&reset=1`

##Styles
To allow the theme to control the styles that CiviCRM brings forward, we have added a plugin. 
