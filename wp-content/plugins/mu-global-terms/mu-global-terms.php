<?php
/**
 * Plugin Name: Multisite Global Terms
 * Version: 1.0
 * Network: true
 * Plugin URI: http://buddydev.com/plugin/mu-global-terms/
 * Author: Brajesh Singh
 * Author URI: http://buddydev.com
 * Description: The plugin allows make all the taxonomy terms( It is not taxonomy, just terms) global. The terms can be added from any of the sub site and depending on the currently available taxonomy( registered taxonomy) of a sub site, the terms will be available there 
 */

//even before any taxonmy/terms are initialized, we reset the tables
add_action( 'init', 'buddydev_change_tax_terms_table', 0 );
//on blog switching, we need to reset it again, so it does not use current blog's tax/terms only
//it works both on switch/restore blog
add_action( 'switch_blog', 'buddydev_change_tax_terms_table', 0 );

function buddydev_change_tax_terms_table(){
    global $wpdb;
    //change terms table to use main site's
    $wpdb->terms = $wpdb->base_prefix . 'terms';
    //change taxonomy table to use main site's taxonomy table
    $wpdb->term_taxonomy = $wpdb->base_prefix . 'term_taxonomy';
    //if you want to use a different sub sites table for sharing, you can replca e$wpdb->vbase_prefix with $wpdb->get_blog_prefix( $blog_id )
}

