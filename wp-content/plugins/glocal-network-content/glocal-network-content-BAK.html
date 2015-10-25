<?php
/*
Activist Network Posts Widget
Description: Display the posts in your network in a widget.
Author: Pea, Glocal
Author URI: http://glocal.coop
Version: 0.1
License: GPL
*/

/**
 * Add function to widgets_init that'll load our widget.
 * @since 0.1
 */
add_action( 'widgets_init', 'glocal_load_widgets' );

/**
 * Register our widget.
 * 'ANP_Network_Posts_Widget' is the widget class used below.
 *
 * @since 0.1
 */
function glocal_load_widgets() {
	register_widget( 'ANP_Network_Posts_Widget' );
	register_widget( 'ANP_Network_Post_Highlights_Widget' );
	register_widget( 'ANP_Network_Sites_Widget' );
}


// queue up the necessary js
function glocal_enqueue_media_js() {
	wp_enqueue_style('thickbox');
	wp_enqueue_script('media-upload');
	wp_enqueue_script('thickbox');
	// moved the js to an external file, you may want to change the path
	wp_enqueue_script('upload_media_widget', plugin_dir_url(__FILE__) . 'js/upload-media.js', array('jquery'));
}
add_action('admin_enqueue_scripts', 'glocal_enqueue_media_js');


/**
 * Adds ANP_Network_Sites_Widget widget.
 */
class ANP_Network_Sites_Widget extends WP_Widget {
    // all of our widget code will go here
    
    /** Constructor **/
    function ANP_Network_Sites_Widget() {
        // parent::WP_Widget(false, $name = 'Network Sites Widget');
        //_e('Display list of sites in your network.')
        $widget_ops = array(
            'class' => 'network-sites-list', 
            'description' => __( 'Display list of sites in your network.','glocal-network-content'),
        );
		
        $this->WP_Widget('glocal_network_sites_widget', 'Network Sites', $widget_ops);
		
    }
	

    /** Form **/
    /** @see WP_Widget::form */
    function form($instance) {	

        $title = esc_attr($instance['title']);
        
        $number_sites = esc_attr($instance['number_sites']);
        $exclude_sites = esc_attr($instance['exclude_sites']);
        $sort_by = esc_attr($instance['sort_by']);
        $default_image = esc_attr($instance['default_image']);
        $id = esc_attr($instance['id']);
        $class = esc_attr($instance['class']);
        $show_meta = esc_attr($instance['show_meta']);
        $show_image = esc_attr($instance['show_image']);

        ?>

        <!-- Title -->
         <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:','glocal-network-content'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
        </p>


        <!-- Number of Sites-->
         <p>
            <label for="<?php echo $this->get_field_id('number_sites'); ?>"><?php _e('Number of sites:','glocal-network-content'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('number_sites','glocal-network-content'); ?>" name="<?php echo $this->get_field_name('number_sites'); ?>" type="number" value="<?php echo $number_sites; ?>"  min="0" max="100" />
        </p>

        <!-- Exclude sites -->
		<p>         
			<label for="<?php echo $this->get_field_name( 'exclude_sites' ); ?>"><?php _e('Select sites to exclude:', 'glocal-network-content'); ?></label>
			<select id="<?php echo $this->get_field_id( 'exclude_sites' ); ?>" name="<?php echo $this->get_field_name( 'exclude_sites' ) . '[]'; ?>" multiple="multiple" style="width:100%;">
				
				<?php echo '<option id="" value=""', empty( $instance['exclude_sites'] ) ? ' selected="selected"' : '','>None</option>'; ?>
				
				<?php 
				$siteargs = array(
					'archived'   => 0,
					'spam'       => 0,
					'deleted'    => 0,
				);
		
				$sites = wp_get_sites($siteargs);
				?>
				<?php foreach( $sites as $site ) { 
					$site_id = $site['blog_id'];
					$site_name = get_blog_details( $site_id )->blogname;
					//$site_name = $site_details->blogname;
					echo '<option id="' . $site_id . '" value="' . $site_id . '"', in_array( $site_id,  $instance['exclude_sites']) ? '  selected="selected"' : '','>' . $site_name . '</option>';
					//var_dump($instance['exclude_sites']);
				} ?>
				
			</select>

		</p>

        <!-- Sort by -->
        <!-- registered, last_updated, post_count, blogname-->
        <p>
            <label for="<?php echo $this->get_field_id('sort_by'); ?>"><?php _e('Order by:','glocal-network-content'); ?></label>
            <select name="<?php echo $this->get_field_name('sort_by'); ?>" id="<?php echo $this->get_field_id('sort_by'); ?>" class="widefat">
                <?php
                $sortoptions = array(
                    'Alphabetical' => 'blogname', 
                    'Recently Active' => 'last_updated', 
                    'Most Active' =>'post_count', 
                    'Date Created' => 'registered'
                );
                foreach ($sortoptions as $key => $value) {
                    echo '<option value="' . $value . '" id="' . $value . '"', $sort_by == $value ? ' selected="selected"' : '', '>', $key, '</option>';
                }
                ?>
            </select>
        </p>

        <!-- Default image -->
        <p>
            <label for="<?php echo $this->get_field_id( 'default_image' ); ?>"><?php _e('Default site image:','glocal-network-content'); ?></label>
            <input name="<?php echo $this->get_field_name( 'default_image' ); ?>" id="<?php echo $this->get_field_id( 'default_image' ); ?>" class="widefat" type="text" size="36"  value="<?php echo esc_url( $default_image ); ?>" />
            <input class="upload_image_button" type="button" value="Upload Image" />
        </p>

        <!-- Hide meta -->
        <p>
            <input id="<?php echo $this->get_field_id('show_meta'); ?>" name="<?php echo $this->get_field_name('show_meta'); ?>" type="checkbox" value="1" <?php checked( '1', $show_meta ); ?> />
            <label for="<?php echo $this->get_field_id('show_meta'); ?>"><?php _e('Show meta info (update date and recent post)', 'glocal-network-content'); ?></label>
        </p>

        <!-- Hide image -->
        <p>
            <input id="<?php echo $this->get_field_id('show_image'); ?>" name="<?php echo $this->get_field_name('show_image'); ?>" type="checkbox" value="1" <?php checked( '1', $show_image ); ?> />
            <label for="<?php echo $this->get_field_id('show_image'); ?>"><?php _e('Show site image', 'glocal-network-content'); ?></label>
        </p>

        <!-- Instances ID -->
         <p>
            <label for="<?php echo $this->get_field_id('id'); ?>"><?php _e('List ID:','glocal-network-content'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('id'); ?>" name="<?php echo $this->get_field_name('id'); ?>" type="text" value="<?php echo $id; ?>" />
        </p>

        <!-- Class name -->
         <p>
            <label for="<?php echo $this->get_field_id('class'); ?>"><?php _e('List class:','glocal-network-content'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('class'); ?>" name="<?php echo $this->get_field_name('class'); ?>" type="text" value="<?php echo $class; ?>" />
        </p>


        <?php
    }
    

    /** Update **/
    /** @see WP_Widget::update */
    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);

        $instance['number_sites'] = strip_tags(intval($new_instance['number_sites']));
        $instance['exclude_sites'] = strip_tags($new_instance['exclude_sites']);
        $instance['sort_by'] = strip_tags($new_instance['sort_by']);
        $instance['default_image'] = strip_tags($new_instance['default_image']);
        $instance['id'] = strip_tags($new_instance['id']);
        $instance['class'] = strip_tags($new_instance['class']);
        $instance['show_meta'] = strip_tags($new_instance['show_meta']);
        $instance['show_image'] = strip_tags($new_instance['show_image']);
        
        return $instance;
    }

  
    /** Display **/
    /** @see WP_Widget::widget */
	function widget($args, $instance) {
	    extract( $args );

        // these are our widget options
	    $title = apply_filters('widget_title', $instance['title']);
		
		if(is_array($instance['exclude_sites']) && (!empty($instance['exclude_sites'][0])) ) {
			$instance['exclude_sites'] = implode(',', $instance['exclude_sites'] );
		} else {
			unset( $instance['exclude_sites'] );
		}
		
		//Remove all parameters set that are empty, blank, null or 0
		
		foreach($instance as $key => $value){
			if( empty($instance[$key]) ) {
				unset( $instance[$key] );
			}
		}

        echo $before_widget;
        
        // if the title is set
        if ( $title ) {
            echo $before_title . $title . $after_title;
        }
        
        // Use glocal_networkwide_sites function to display sites
        if(function_exists('glocal_networkwide_sites_module')) {
            echo glocal_networkwide_sites_module( $instance );
        }
		        
        echo $after_widget;
    }

}

/**
 * Adds glocal_network_sites_widget widget.
 */
class ANP_Network_Posts_Widget extends WP_Widget {
    // all of our widget code will go here
    
    /** Constructor **/
    function ANP_Network_Posts_Widget() {
        // parent::WP_Widget(false, $name = 'Network Sites Widget');
        //_e('Display list of sites in your network.')
        $widget_ops = array(
            'class' => 'network-posts-list', 
            'description' => __( 'Display list of posts in your network.','glocal-network-content'),
        );
        $this->WP_Widget('glocal_network_posts_widget', 'Network Posts', $widget_ops);
    }
    
    
    /** Form **/
    /** @see WP_Widget::form */
    function form($instance) {
		
		$defaults = array( 
			'show_meta' => true, 
			'show_thumbnail' => false, 
			'show_excerpt' => true,
			'show_site_name' => true,
			'excerpt_length' => 20
		);
		$instance = wp_parse_args( (array) $instance, $defaults );

        $title = esc_attr($instance['title']);
		
        $number_posts = esc_attr($instance['number_posts']);
        $exclude_sites = esc_attr($instance['exclude_sites']);
		$include_categories = esc_attr($instance['include_categories']);
		$style = esc_attr($instance['style']);
		$posts_per_site = esc_attr($instance['posts_per_site']);
        $id = esc_attr($instance['id']);
        $class = esc_attr($instance['class']);
        $show_meta = esc_attr($instance['show_meta']);
        $show_thumbnail = esc_attr($instance['show_thumbnail']);
		$show_excerpt = esc_attr($instance['show_excerpt']);
		$excerpt_length = esc_attr($instance['excerpt_length']);
		$show_site_name = esc_attr($instance['show_site_name']);

        ?>

        <!-- Title -->
         <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:','glocal-network-content'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
        </p>

        <!-- Number of Sites -->
         <p>
            <label for="<?php echo $this->get_field_id('number_posts'); ?>"><?php _e('Number of posts:','glocal-network-content'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('number_posts','glocal-network-content'); ?>" name="<?php echo $this->get_field_name('number_posts'); ?>" type="number" value="<?php echo $number_posts; ?>"  min="0" max="100" />
        </p>

        <!-- Posts per Site -->
         <p>
            <label for="<?php echo $this->get_field_id('posts_per_site'); ?>"><?php _e('Posts per site:','glocal-network-content'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('posts_per_site','glocal-network-content'); ?>" name="<?php echo $this->get_field_name('posts_per_site'); ?>" type="number" value="<?php echo $posts_per_site; ?>"  min="0" max="100" />
        </p>

		<!-- Exclude sites -->
		<p>         
			<label for="<?php echo $this->get_field_name( 'exclude_sites' ); ?>"><?php _e('Select sites to exclude:', 'glocal-network-content'); ?></label>
			<select id="<?php echo $this->get_field_id( 'exclude_sites' ); ?>" name="<?php echo $this->get_field_name( 'exclude_sites' ) . '[]'; ?>" multiple="multiple" style="width:100%;">
				
				<?php echo '<option id="" value=""', empty( $instance['exclude_sites'] ) ? ' selected="selected"' : '','>None</option>'; ?>
				
				<?php 
				$siteargs = array(
					'archived'   => 0,
					'spam'       => 0,
					'deleted'    => 0,
				);
		
				$sites = wp_get_sites($siteargs);
				?>
				<?php foreach( $sites as $site ) { 
					$site_id = $site['blog_id'];
					$site_name = get_blog_details( $site_id )->blogname;
					//$site_name = $site_details->blogname;
					echo '<option id="' . $site_id . '" value="' . $site_id . '"', in_array( $site_id,  $instance['exclude_sites']) ? '  selected="selected"' : '','>' . $site_name . '</option>';
					var_dump($instance['exclude_sites']);
				} ?>
				
			</select>

		</p>

		<!-- Include Categories -->
		<p>         
			<label for="<?php echo $this->get_field_name( 'include_categories' ); ?>"><?php _e('Select categories to display:', 'glocal-network-content'); ?></label>
			<select id="<?php echo $this->get_field_id( 'include_categories' ); ?>" name="<?php echo $this->get_field_name( 'include_categories' ) . '[]'; ?>" multiple="multiple" style="width:100%;">
				
				<?php echo '<option id="" value=""', empty( $instance['include_categories'] ) ? ' selected="selected"' : '','>None</option>'; ?>
				
				<?php $categories = get_categories(); ?>
				<?php foreach( $categories as $cat ) { 
					echo '<option id="' . $cat->slug . '" value="' . $cat->slug . '"', in_array( $cat->slug,  $instance['include_categories']) ? '  selected="selected"' : '','>' . $cat->name . '</option>';
				} ?>
			</select>

		</p>

        <!-- Sort by -->
        <!-- registered, last_updated, post_count, blogname-->
        <p>
            <label for="<?php echo $this->get_field_id('style'); ?>"><?php _e('Display Style:','glocal-network-content'); ?></label>
            <select name="<?php echo $this->get_field_name('style'); ?>" id="<?php echo $this->get_field_id('style'); ?>" class="widefat">
                <?php
                $sortoptions = array(
                    'List (Default)' => '', 
                    'Block' => 'block', 
                );
                foreach ($sortoptions as $key => $value) {
                    echo '<option value="' . $value . '" id="' . $value . '"', $style == $value ? ' selected="selected"' : '', '>', $key, '</option>';
                }
                ?>
            </select>
        </p>

        <!-- Show meta -->
        <p>
            <input id="<?php echo $this->get_field_id('show_meta'); ?>" name="<?php echo $this->get_field_name('show_meta'); ?>" type="checkbox" value="1" <?php checked( '1', $show_meta ); ?> />
            <label for="<?php echo $this->get_field_id('show_meta'); ?>"><?php _e('Show meta info', 'glocal-network-content'); ?></label>
        </p>

        <!-- Show thumbnails -->
        <p>
            <input id="<?php echo $this->get_field_id('show_thumbnail'); ?>" name="<?php echo $this->get_field_name('show_thumbnail'); ?>" type="checkbox" value="1" <?php checked( '1', $show_thumbnail ); ?> />
            <label for="<?php echo $this->get_field_id('show_thumbnail'); ?>"><?php _e('Show thumbnails (ignored if Show Meta is not selected):', 'glocal-network-content'); ?></label>
        </p>

        <!-- Show excerpt -->
        <p>
            <input id="<?php echo $this->get_field_id('show_excerpt'); ?>" name="<?php echo $this->get_field_name('show_excerpt'); ?>" type="checkbox" value="1" <?php checked( '1', $show_excerpt ); ?> />
            <label for="<?php echo $this->get_field_id('show_excerpt'); ?>"><?php _e('Show excerpt (ignored if Show Meta is not selected):', 'glocal-network-content'); ?></label>
        </p>

        <!-- Excerpt Length -->
         <p>
            <label for="<?php echo $this->get_field_id('excerpt_length'); ?>"><?php _e('Excerpt length (chars):','glocal-network-content'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('excerpt_length','glocal-network-content'); ?>" name="<?php echo $this->get_field_name('excerpt_length'); ?>" type="number" value="<?php echo $excerpt_length; ?>"  min="0" max="200" />
        </p>

        <!-- Show site name -->
        <p>
            <input id="<?php echo $this->get_field_id('show_site_name'); ?>" name="<?php echo $this->get_field_name('show_site_name'); ?>" type="checkbox" value="1" <?php checked( '1', $show_site_name ); ?> />
            <label for="<?php echo $this->get_field_id('show_site_name'); ?>"><?php _e('Show site name (ignored if Show Meta is not selected):', 'glocal-network-content'); ?></label>
        </p>

        <!-- Instances ID -->
         <p>
            <label for="<?php echo $this->get_field_id('id'); ?>"><?php _e('Markup ID:','glocal-network-content'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('id'); ?>" name="<?php echo $this->get_field_name('id'); ?>" type="text" value="<?php echo $id; ?>" />
        </p>

        <!-- Class name -->
         <p>
            <label for="<?php echo $this->get_field_id('class'); ?>"><?php _e('Markup class:','glocal-network-content'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('class'); ?>" name="<?php echo $this->get_field_name('class'); ?>" type="text" value="<?php echo $class; ?>" />
        </p>


        <?php
    }
    

    /** Update **/
    /** @see WP_Widget::update */
    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
		
		$instance['number_posts'] = (int)$new_instance['number_posts'];
		$instance['posts_per_site'] = (int)$new_instance['posts_per_site'];
        $instance['exclude_sites'] = $new_instance['exclude_sites'];
        $instance['include_categories'] = $new_instance['include_categories'];
		$instance['style'] = strip_tags($new_instance['style']);
        $instance['id'] = strip_tags($new_instance['id']);
        $instance['class'] = strip_tags($new_instance['class']);
        $instance['show_meta'] = strip_tags($new_instance['show_meta']);
        $instance['show_thumbnail'] = strip_tags($new_instance['show_thumbnail']);
		$instance['show_excerpt'] = strip_tags($new_instance['show_excerpt']);
		$instance['excerpt_length'] = strip_tags($new_instance['excerpt_length']);
		$instance['show_site_name'] = strip_tags($new_instance['show_site_name']);
        
        return $instance;
    }

  
    /** Display **/
    /** @see WP_Widget::widget */
	function widget($args, $instance) {
	    extract( $args );

        // these are our widget options
	    $title = apply_filters('widget_title', $instance['title']);
		
		if( is_array($instance['include_categories']) && (!empty($instance['include_categories'][0])) ) {
			$instance['include_categories'] = implode(',', $instance['include_categories'] );
		} else {
			unset( $instance['include_categories'] );
		}
		if(is_array($instance['exclude_sites']) && (!empty($instance['exclude_sites'][0])) ) {
			$instance['exclude_sites'] = implode(',', $instance['exclude_sites'] );
		} else {
			unset( $instance['exclude_sites'] );
		}
		
		if( empty($instance['number_posts']) ) {
			unset($instance['number_posts']);
		}
		
		if( empty($instance['posts_per_site']) ) {
			unset($instance['posts_per_site']);
		}
		
		if( empty($instance['excerpt_length']) ) {
			unset($instance['excerpt_length']);
		}
		        
        echo $before_widget;
        
        // if the title is set
        if ( $title ) {
            echo $before_title . $title . $after_title;
        }
        
        // Use glocal_networkwide_sites function to display sites
        if(function_exists('glocal_networkwide_posts_module')) {
			
            echo glocal_networkwide_posts_module( $instance );
        }
		        
        echo $after_widget;
    }

}

/**
 * Adds ANP_Network_Post_Highlights_Widget.
 */
class ANP_Network_Post_Highlights_Widget extends WP_Widget {
    // all of our widget code will go here
    
    /** Constructor **/
    function ANP_Network_Post_Highlights_Widget() {
        // parent::WP_Widget(false, $name = 'Network Sites Widget');
        //_e('Display list of sites in your network.')
        $widget_ops = array(
            'class' => 'highlights', 
            'description' => __( 'Display list of posts in your network.','glocal-network-content'),
        );
        $this->WP_Widget('glocal_network_post_highlights_widget', 'Network Post Highlights Module', $widget_ops);
    }
    
    
    /** Form **/
    /** @see WP_Widget::form */
    function form($instance) {
		
		$defaults = array( 
			'show_meta' => true, 
			'show_thumbnail' => false, 
			'show_excerpt' => true,
			'show_site_name' => true,
			'excerpt_length' => 20
		);
		$instance = wp_parse_args( (array) $instance, $defaults );

        $title = esc_attr($instance['title']);
		$title_image = esc_attr($instance['title_image']);
		
        $number_posts = esc_attr($instance['number_posts']);
        $exclude_sites = esc_attr($instance['exclude_sites']);
		$include_categories = esc_attr($instance['include_categories']);
		$posts_per_site = esc_attr($instance['posts_per_site']);
        $id = esc_attr($instance['id']);
        $class = esc_attr($instance['class']);
        $show_meta = esc_attr($instance['show_meta']);
        $show_thumbnail = esc_attr($instance['show_thumbnail']);
		$show_excerpt = esc_attr($instance['show_excerpt']);
		$excerpt_length = esc_attr($instance['excerpt_length']);
		$show_site_name = esc_attr($instance['show_site_name']);

        ?>

        <!-- Title -->
         <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:','glocal-network-content'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
        </p>

        <!-- Title Image -->
        <p>
            <label for="<?php echo $this->get_field_id( 'title_image' ); ?>"><?php _e('Title image:','glocal-network-content'); ?></label>
            <input name="<?php echo $this->get_field_name( 'title_image' ); ?>" id="<?php echo $this->get_field_id( 'title_image' ); ?>" class="widefat" type="text" size="36"  value="<?php echo esc_url( $title_image ); ?>" />
            <input class="upload_image_button" type="button" value="Upload Image" />
        </p>

        <!-- Number of Sites -->
         <p>
            <label for="<?php echo $this->get_field_id('number_posts'); ?>"><?php _e('Number of posts:','glocal-network-content'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('number_posts','glocal-network-content'); ?>" name="<?php echo $this->get_field_name('number_posts'); ?>" type="number" value="<?php echo $number_posts; ?>"  min="" max="100" />
        </p>

        <!-- Posts per Site -->
         <p>
            <label for="<?php echo $this->get_field_id('posts_per_site'); ?>"><?php _e('Posts per site:','glocal-network-content'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('posts_per_site','glocal-network-content'); ?>" name="<?php echo $this->get_field_name('posts_per_site'); ?>" type="number" value="<?php echo $posts_per_site; ?>"  min="" max="100" />
        </p>

		<!-- Exclude sites -->
		<p>         
			<label for="<?php echo $this->get_field_name( 'exclude_sites' ); ?>"><?php _e('Select sites to exclude:', 'glocal-network-content'); ?></label>
			<select id="<?php echo $this->get_field_id( 'exclude_sites' ); ?>" name="<?php echo $this->get_field_name( 'exclude_sites' ) . '[]'; ?>" multiple="multiple" style="width:100%;">
				
				<?php echo '<option id="" value=""', empty( $instance['exclude_sites'] ) ? ' selected="selected"' : '','>None</option>'; ?>

				<?php 
				$siteargs = array(
					'archived'   => 0,
					'spam'       => 0,
					'deleted'    => 0,
				);
		
				$sites = wp_get_sites($siteargs);
				?>
				<?php foreach( $sites as $site ) { 
					$site_id = $site['blog_id'];
					$site_name = get_blog_details( $site_id )->blogname;
					//$site_name = $site_details->blogname;
					echo '<option id="' . $site_id . '" value="' . $site_id . '"', in_array( $site_id,  $instance['exclude_sites']) ? '  selected="selected"' : '','>' . $site_name . '</option>';
					
				} ?>
				
			</select>

		</p>

		<!-- Include Categories -->
		<p>         
			<label for="<?php echo $this->get_field_name( 'include_categories' ); ?>"><?php _e('Select categories to display:', 'glocal-network-content'); ?></label>
			<select id="<?php echo $this->get_field_id( 'include_categories' ); ?>" name="<?php echo $this->get_field_name( 'include_categories' ) . '[]'; ?>" multiple="multiple" style="width:100%;">
				
				<?php echo '<option id="" value=""', empty( $instance['include_categories'] ) ? ' selected="selected"' : '','>None</option>'; ?>
				
				<?php $categories = get_categories(); ?>
				<?php foreach( $categories as $cat ) { 
					echo '<option id="' . $cat->slug . '" value="' . $cat->slug . '"', in_array( $cat->slug,  $instance['include_categories']) ? '  selected="selected"' : '','>' . $cat->name . '</option>';
				} ?>
			</select>

		</p>

        <!-- Show meta -->
        <p>
            <input id="<?php echo $this->get_field_id('show_meta'); ?>" name="<?php echo $this->get_field_name('show_meta'); ?>" type="checkbox" value="1" <?php checked( '1', $show_meta ); ?> />
            <label for="<?php echo $this->get_field_id('show_meta'); ?>"><?php _e('Show meta info', 'glocal-network-content'); ?></label>
        </p>

        <!-- Show thumbnail -->
        <p>
            <input id="<?php echo $this->get_field_id('show_thumbnail'); ?>" name="<?php echo $this->get_field_name('show_thumbnail'); ?>" type="checkbox" value="1" <?php checked( '1', $show_thumbnail ); ?> />
            <label for="<?php echo $this->get_field_id('show_thumbnail'); ?>"><?php _e('Show thumbnails (ignored if Show Meta is not selected):', 'glocal-network-content'); ?></label>
        </p>

        <!-- Show excerpt -->
        <p>
            <input id="<?php echo $this->get_field_id('show_excerpt'); ?>" name="<?php echo $this->get_field_name('show_excerpt'); ?>" type="checkbox" value="1" <?php checked( '1', $show_excerpt ); ?> />
            <label for="<?php echo $this->get_field_id('show_excerpt'); ?>"><?php _e('Show excerpt (ignored if Show Meta is not selected):', 'glocal-network-content'); ?></label>
        </p>

        <!-- Excerpt Length -->
         <p>
            <label for="<?php echo $this->get_field_id('excerpt_length'); ?>"><?php _e('Excerpt length (chars):','glocal-network-content'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('excerpt_length','glocal-network-content'); ?>" name="<?php echo $this->get_field_name('excerpt_length'); ?>" type="number" value="<?php echo $excerpt_length; ?>"  min="0" max="200" />
        </p>

        <!-- Show site name -->
        <p>
            <input id="<?php echo $this->get_field_id('show_site_name'); ?>" name="<?php echo $this->get_field_name('show_site_name'); ?>" type="checkbox" value="1" <?php checked( '1', $show_site_name ); ?> />
            <label for="<?php echo $this->get_field_id('show_site_name'); ?>"><?php _e('Show site name (ignored if Show Meta is not selected):', 'glocal-network-content'); ?></label>
        </p>

        <!-- Instances ID -->
         <p>
            <label for="<?php echo $this->get_field_id('id'); ?>"><?php _e('Markup ID:','glocal-network-content'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('id'); ?>" name="<?php echo $this->get_field_name('id'); ?>" type="text" value="<?php echo $id; ?>" />
        </p>

        <!-- Class name -->
         <p>
            <label for="<?php echo $this->get_field_id('class'); ?>"><?php _e('Markup class:','glocal-network-content'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('class'); ?>" name="<?php echo $this->get_field_name('class'); ?>" type="text" value="<?php echo $class; ?>" />
        </p>


        <?php
    }
    

    /** Update **/
    /** @see WP_Widget::update */
    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
		$instance['title_image'] = strip_tags($new_instance['title_image']);
		
		$instance['number_posts'] = (int)$new_instance['number_posts'];
		$instance['posts_per_site'] = (int)$new_instance['posts_per_site'];
        $instance['exclude_sites'] = $new_instance['exclude_sites'];
        $instance['include_categories'] = $new_instance['include_categories'];
        $instance['id'] = strip_tags($new_instance['id']);
        $instance['class'] = strip_tags($new_instance['class']);
        $instance['show_meta'] = strip_tags($new_instance['show_meta']);
        $instance['show_thumbnail'] = strip_tags($new_instance['show_thumbnail']);
		$instance['show_excerpt'] = strip_tags($new_instance['show_excerpt']);
		$instance['excerpt_length'] = strip_tags($new_instance['excerpt_length']);
		$instance['show_site_name'] = strip_tags($new_instance['show_site_name']);
        
        return $instance;
    }

  
    /** Display **/
    /** @see WP_Widget::widget */
	function widget($args, $instance) {
	    extract( $args );
		
		//print_r( $instance );

        // these are our widget options
	    $title = apply_filters('widget_title', $instance['title']);
				
		if( is_array($instance['include_categories']) && (!empty($instance['include_categories'][0])) ) {
			$instance['include_categories'] = implode(',', $instance['include_categories'] );
		} else {
			unset( $instance['include_categories'] );
		}
		if(is_array($instance['exclude_sites']) && (!empty($instance['exclude_sites'][0])) ) {
			$instance['exclude_sites'] = implode(',', $instance['exclude_sites'] );
		} else {
			unset( $instance['exclude_sites'] );
		}
		
		if( empty($instance['number_posts']) ) {
			unset($instance['number_posts']);
		}
		
		if( empty($instance['posts_per_site']) ) {
			unset($instance['posts_per_site']);
		}
		
		if( empty($instance['excerpt_length']) ) {
			unset($instance['excerpt_length']);
		}
		
		$instance['style'] = 'highlights';
        
        echo $before_widget;
        
        // if the title is set
        if ( $title ) {
            echo $before_title . $title . $after_title;
        }
        
        // Use glocal_networkwide_sites function to display sites
        if(function_exists('glocal_networkwide_posts_module')) {
			
            echo glocal_networkwide_posts_module( $instance );
        }
		        
        echo $after_widget;
    }

}
