# Event Organiser CSV #
**Contributors:**      stephenharris  
**Donate link:**       http://wp-event-organiser.com/  
**Tags:** CSV, Event, import  
**Requires at least:** 3.5.1  
**Tested up to:**      4.2.2  
**Stable tag:**        0.3.2  
**License:**           GPLv2 or later  
**License URI:**       http://www.gnu.org/licenses/gpl-2.0.html  

Import & export events from/to CSV format

## Description ##

This plug-in allows to import events from a CSV file into Event Organiser. You can also export events from
Event Organiser into a CSV file.

Please note that this plug-in still in **beta**. I welcome feedback, issues and pull-requests.


### Aim ###
To allow users to export / import events in CSV format between various calendar applications, and to do this flexiably 
so as to limit the number of requirements on the CSV file before it can be read correctly. To allow users to move events 
between installations of Event Organiser while preserving data that is not suported by iCal.

**In the vein of flexibility columns do not have to be in any prescribed order:** you tell the plug-in which columns pertain to what (start date, end date etc)   
after importing the file.


### How to use this plug-in ###

Once installed, go to *Tools > Import Events*. Here you can export a CSV file or select a file to import one. To import an file:
 
* Select browse and select the file, click "Upload file and import"
* All being well you should now see a preview of the CSV file, along with a drop-down option at the base of each column. If the preview looks wrong, try 
selecting a different delimiter type (comma, tab, space) at the top.
* If the first row of the CSV file is a header, select the option indicating this. The first row will then be ignored.
* At the bottom of each column select what the column represents. The options are (not all are required):
  - Title
  - Start (formatted in Y-m-d format, and also indicating time **only** if the event is not all-day)  
  - End (formatted as above)
  - Recur until (if the event recurs, the date of its last occurrence)
**  - Recurrence Schedule (if the event recurs, how it repeats:** once|daily|weekly|monthly|yearly|custom).  
  - Recurrence Frequency (if the event recurs, an integer indicating with what frequency)
  - Schedule Meta (See documentation for [eo_insert_post()](http://codex.wp-event-organiser.com/function-eo_insert_event.html), e.g. "MO,TU,THR" (weekly), "BYDAY=2MO" or "BYMONTHDAY=16" (monthly)
  - Content (HTML post content)
  - Venue (Venue slug)
  - Categories (comma seperated list of category slugs) 
  - Tags (comma seperated list of tag slugs)
  - [Any custom event taxonomies registered] (comma seperated list of slugs)
  - Include dates (comma seperated list of Y-m-d dates to include from the event's schedule)
  - Exclude dates (as above, but added to the event's schedule)
  - Post Meta (an option will appear to provide the meta-key)
 * Click import.
 
 
### Importing new venues, categories and tags ###

By default the plug-in will only import venues, categories and tags that already exist. 
To allow the plug-in to create new venues, categories and tags you can add the following
code (to a seperate plug-in or your theme's `functions.php`).

     function my_set_import_imports( $args, $file ){
          $args['import_new_event-category'] =  true; //create category if it doesn't exist
          $args['import_new_event-venue']    =  true; //create venue if it doesn't exist.
          $args['import_new_event-tag']      =  true; //create tag if it doesn't exist. 
     
          return $args;
     }
     add_filter( 'eventorganiser_csv_import_args', 'my_set_import_imports', 10, 2 );

Please note the limitations on importing venues discussed below. 



### Limitations ###
Current limitations apply. See the examples folder for an archetypal CSV file 

* All dates are read using PHP's DateTime. While various formats are supported, Y-m-d (e.g. 2013-12-31) formats are **strongly** recommended
* Starts dates must be provided in Y-m-d (e.g. 2013-12-31) for all day events and also include a time-component (e.g. 2013-12-31 11:30pm) for non-all-day events. There is no 
prescribed format for the time but 24-hour time is recommended. You do not need to specify seconds.
* Include/exclude dates should be given as comma-seperated list of dates in Y-m-d format.
* Categories and tags must be given as comma-seperated list of names
* It does not support venue meta-data (yet)

*Please note that in theory all dates (other than the start date) can be given in any format, however, to 
ensure dates are interpreted correctly it is strongly recommended to give dates in Y-m-d (or Y-m-d H:i for non-all day events) format. The start 
date must be in that format so that the importer can differentriate between all-day and non-all-day events.*
 

### Future Features ###
* An "import preview" or "dry-run" so users can view how events will be imported.
* Support venue meta data
* Support category colours
* Add filters for developers
* Add support for UID to prevent importing an event twice (perhaps, update the event?)
* Add support 'maps' for importing from other applications (where format of exported CSV file is prescribed).
* Support generic date formatting (try to 'guess' / ask for format )


## Installation ##

1. Upload the entire `/event-organiser-csv` directory to the `/wp-content/plugins/` directory.
2. Activate Event Organiser CSV through the 'Plugins' menu in WordPress.

## Frequently Asked Questions ##


## Screenshots ##

### 1. At *Tools > Import Events* select a file to import. ###
![At *Tools > Import Events* select a file to import.](http://ps.w.org/event-organiser-csv/assets/screenshot-1.png)

### 2. Select delimiter, and identify each column. ###
![Select delimiter, and identify each column.](http://ps.w.org/event-organiser-csv/assets/screenshot-2.png)

### 3. After importing the events you'll be notified if the it was successful. ###
![After importing the events you'll be notified if the it was successful.](http://ps.w.org/event-organiser-csv/assets/screenshot-3.png)



## Changelog ##

### 0.3.2 - 25th May 2015 ###
* Fix bug with non-latin character sets & CSV preview
* Adds error message and prevents processing of CSV file if a start date column hasn't been selected.

### 0.3.1 ###
* Fix bug with using term name for importing venues/categories as opposed to slug (as documented). 

### 0.3.0 ###
* Fix bug with parsing schedule meta data of weekly events.
* Recognise custom event taxonomies in column selection.

### 0.2.0 ###
* Refactored CSV parsing routine
* Adds `eventorganiser_csv_import_columns` filter to allow additional columns to be 'registered'
* Adds `eventorganiser_csv_cell_value` filter to filter parsed value
* Adds `eventorganiser_csv_event_inserted` action after event is inserted
* Adds support for semicolon delimiters 

### 0.1.3 ###
* Fixed bugs which meant event-tags wouldn't be exported.
* Supports event-tag import (not just category/venue)
* Added filter to toggle import arguments
* Changed page/menu title to make it clearer that a CSV file can be exported there. 

### 0.1.2 ###
* Fixed spelling errors in readme

### 0.1.1 ###
* Added support for post meta
* Fixed bugt with importing Venues with "&" in the name

### 0.1.0 ###
* First release
