<?php
/*
Plugin Name: Activist Network Custom Shortcodes
Description: Selection of custom shortcodes that can be used in pages, posts, widgets. 
Author: Glocal
Author URI: http://glocal.coop
Version: 0.1
License: GPL
*/

/************* CREATE SHORTCODE FOR DISPLAYING RECENT POSTS *****************/

// Usage
// [recent-posts type="" category="" orderby="" order="" numberposts="" excerpt=false]
// All parameters are optional
    // numberpost default = 10
    // excerpt default = true // Acceptable false values are 0, '0', false, 'false', no and 'no'
    // All other parameters default to WP default values.

add_shortcode( 'recent-posts', 'glocal_recent_posts_shortcode' );

function glocal_recent_posts_shortcode( $atts ) {
    ob_start();
 
    // define attributes and their defaults
    extract( shortcode_atts( array (
        'type' => '',
        'orderby' => '',
        'order' => '', 
        'numberposts' => null,
        'category' => '',
        'excerpt' => true, // default
    ), $atts ) );
 
    // define query parameters based on attributes
    $options = array(
        'post_type' => $type,
        'orderby' => $orderby,
        'order' => $order,
        'posts_per_page' => $numberposts,
        'category_name' => $category,
    );
    
    // Convert strings to booleans
    $excerpt = (filter_var($excerpt, FILTER_VALIDATE_BOOLEAN));

    $recentposts = new WP_Query( $options );
    //$recentposts = get_posts( $options ); ?>
    
    <?php
    if ( $recentposts->have_posts() ) { ?>
        <ul class="recent-posts">
            <?php while ($recentposts->have_posts()) : $recentposts->the_post(); ?>
            <li id="post-<?php the_ID(); ?>" <?php post_class(); ?> role="article">
                <h3 class="post-title"><a href="<?php echo get_permalink(); ?>"><?php echo get_the_title(); ?></a></h3>
                <div class="meta byline vcard">
                    <time class="updated" datetime="%1$s" pubdate><?php echo get_the_date(); ?></time>
                </div>
                <?php
                if($excerpt) { ?>
                <div class="post-body" itemprop="articleBody">
                    <div class="post-image"><?php the_post_thumbnail('medium'); ?></div>
                    <p class="post-excerpt"><?php echo get_the_excerpt(); ?></p>
                </div>
                <?php } ?>
            </li>
            <?php endwhile; ?>
        </ul>
    <?php
        $myvariable = ob_get_clean();
        return $myvariable;
    }
    wp_reset_query();
}

/************* ENABLE SHORTCODES IN WIDGETS, COMMENTS and EXCERPT *****************/

add_filter( 'widget_text', 'do_shortcode');
//add_filter( 'comment_text', 'do_shortcode' ); // Uncomment to enable
//add_filter('the_excerpt', 'do_shortcode'); // Uncomment to enable

?>