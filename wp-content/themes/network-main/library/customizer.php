<?php
/**
 * Glocal Network Theme Customizer
 *
 * @package Glocal Network
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
// function glocal_network_customize_register( $wp_customize ) {
// 	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
// 	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
// 	$wp_customize->get_setting( 'nav_menu_locations' )->transport = 'postMessage';
// 	$wp_customize->get_setting( 'glo_options_home' )->transport = 'postMessage';
// }
// add_action( 'customize_register', 'glocal_network_customize_register' );

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
// function glocal_network_customize_preview_js() {
// 	wp_enqueue_script( 'glocal_network_customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), '20130508', true );
// }
// add_action( 'customize_preview_init', 'glocal_network_customize_preview_js' );



// Check that WP_Customize_Control exists

if ( class_exists( 'WP_Customize_Control' ) ) {


	/**
	 * Multiple select customize control class.
	 */
	class WP_Customize_Multiple_Select_Control extends WP_Customize_Control {

		/**
		* The type of customize control being rendered.
		*/
	    public $type = 'multiple-select';

	    /**
	     * Displays the multiple select on the customize screen.
	     */
	    public function render_content() {

	    if ( empty( $this->choices ) )
	        return;
	    ?>
	        <label>
	            <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
	            <select <?php $this->link(); ?> multiple="multiple" style="height: 100%;">
					<option value="">None</option>
	                <?php
	                    foreach ( $this->choices as $value => $label ) {
	                        $selected = ( in_array( $value, $this->value() ) ) ? selected( 1, 1, false ) : '';
	                        echo '<option value="' . esc_attr( $value ) . '"' . $selected . '>' . $label . '</option>';
	                    }
	                ?>
	            </select>
	        </label>
	    <?php }
	}


	/**
	 * NARGA Category Drop Down List Class
	 *
	 * modified dropdown-pages from wp-includes/class-wp-customize-control.php
	 *
	 * @since NARGA v1.0
	 */

	class WP_Customize_Dropdown_Categories_Control extends WP_Customize_Control {

		/**
		* The type of customize control being rendered.
		*/
	    public $type = 'dropdown-categories';	
	 
	    public function render_content() {
	        $dropdown = wp_dropdown_categories( 
	            array( 
	                'name'             => '_customize-dropdown-categories-' . $this->id,
	                'echo'             => 0,
	                'hide_empty'       => true, // Only show categories that have posts associated
	                'show_option_none' => '&mdash; ' . __('Select', 'glocal-network') . ' &mdash;',
	                'hide_if_empty'    => false,
	                'selected'         => $this->value(),
	            )
	        );
	 
	        $dropdown = str_replace('<select', '<select ' . $this->get_link(), $dropdown );
	 
	        printf( 
	            '<label class="customize-control-select"><span class="customize-control-title">%s</span> %s</label>',
	            $this->label,
	            $dropdown
	        );
	    }
	}
}


// Customization Functions

function glocal_customize_register( $wp_customize ) {

	if(function_exists('glocal_home_category')) {
		$postcategory = glocal_home_category();
	}

	/**
	* Drop-downs
	*/

	// Post Category Drop-down
	$categories = get_categories();
	$cats = array();
	$i = 0;
	foreach($categories as $category){
		if($i==0){
			$defaultcat = $category->slug;
			$i++;
		}
		$cats[$category->slug] = $category->name;
	}

	// Sites List Drop-down
	$sites = wp_get_sites();
	$siteslist = array();
	$i = 0;
	foreach ($sites as $site) {
		$siteid = $site['blog_id'];
		$sitedetails = get_blog_details( $siteid );
		if($i==0) {
			$defaultsite = $siteid;
			$i++;
		}
		$siteslist[$siteid] = $sitedetails->blogname;
	}

	/**
	* Panels & Sections
	*/

	// Panel
	$wp_customize->add_panel( 'home_panel', array(
		// 'priority'       => 10,
		'capability'     => 'edit_theme_options',
		'theme_supports' => '',
		'title'          => __( 'Front Page', 'glocal-network' ),
		'description'    => __( 'Customize the display of the homepage', 'glocal-network' ),
	) );

	// Section - Select Modules
	$wp_customize->add_section( 'home_general' , array(
		'title'      => __( 'General', 'glocal-network' ),
		'description'    => __( 'Select the content to display.', 'glocal-network' ),
		'priority'   => 10,
		'panel'  => 'home_panel',
	) );

	// Section - Updates
	$wp_customize->add_section( 'home_updates' , array(
		'title'      => __( 'Updates', 'glocal-network' ),
		'priority'   => 20,
		'panel'  => 'home_panel',
	) );

	// Section - Posts
	$wp_customize->add_section( 'home_posts' , array(
		'title'      => __( 'Posts', 'glocal-network' ),
		'priority'   => 30,
		'panel'  => 'home_panel',
	) );

	// Section - Events
	$wp_customize->add_section( 'home_events' , array(
		'title'      => __( 'Events', 'glocal-network' ),
		'priority'   => 40,
		'panel'  => 'home_panel',
	) );

	// Section - Sites
	$wp_customize->add_section( 'home_sites' , array(
		'title'      => __( 'Sites', 'glocal-network' ),
		'priority'   => 50,
		'panel'  => 'home_panel',
	) );


	/**
	* Settings & Controls
	*/

	// Homepage Modules
	$wp_customize->add_setting( 'glo_options_home[modules]', array(
		'default' => array(),
		// 'capability'     => 'manage_options',
		'type'           => 'option',
	) );

	$wp_customize->add_control(
		new WP_Customize_Multiple_Select_Control(
			$wp_customize,
			'glocal_home_modules',
			array(
				'settings' => 'glo_options_home[modules]',
				'label'    => __( 'Modules - Select the modules to display', 'glocal-network' ),
				'section'  => 'home_general',
				'type'     => 'multiple-select', // The $type in our class
				'choices'  => array(
					'updates' => __('Updates', 'glocal-network'),
					'posts' => __('Posts', 'glocal-network'),
					'events' => __('Events', 'glocal-network'),
					'sites' => __('Sites', 'glocal-network'),
				),
				'priority' => 10,
			)
		)
	);

	// Homepage Site Exclude
	$wp_customize->add_setting( 'glo_options_home[exclude_sites]', array(
		'default' => array(),
		// 'capability'     => 'manage_options',
		'type'           => 'option',
	) );

	$wp_customize->add_control(
		new WP_Customize_Multiple_Select_Control(
			$wp_customize,
			'glocal_home_exclude_sites',
			array(
				'settings' => 'glo_options_home[exclude_sites]',
				'label'    => __( 'Exclude Sites - Select sites from which to exclude content', 'glocal-network' ),
				'section'  => 'home_general',
				'type'     => 'multiple-select', // The $type in our class
				'choices'  => $siteslist,
				'priority' => 20,
			)
		)
	);

	/**
	* Posts
	*/
 
	// Posts - Categories
	// Setting
	$wp_customize->add_setting('glo_options_home[posts][featured_category]', array(
		'default' 		=> array(),
		// 'default'        => $default,
		'type'			=> 'option',
	));

	// Control
	$wp_customize->add_control( 
		new WP_Customize_Multiple_Select_Control(
			$wp_customize,
			'glocal_posts_category', array(
			'settings' => 'glo_options_home[posts][featured_category]',
			'label'   => __('Categorie(s)', 'glocal-network'),
			'section'  => 'home_posts',
			'type'    => 'multiple-select',
			'choices' => $cats,
			'priority' => 10,
			)
		)
	);

	// Posts - Heading
    $wp_customize->add_setting('glo_options_home[posts][posts_heading]', array(
        'default'        => __('Posts', 'glocal-network'),
        // 'capability'     => 'manage_options',
        'type'           => 'option',
 
    ));
 
    $wp_customize->add_control('glocal_post_heading', array(
        'label'      => __('Heading', 'glocal-network'),
        'section'    => 'home_posts',
        'settings'   => 'glo_options_home[posts][posts_heading]',
        'type' => 'text',
        'priority' => 20,
		'input_attrs' => array(
			'placeholder'   => __('Enter a heading for the posts list', 'glocal-network'),
		)
    ));

	// Posts - Heading Link
	$wp_customize->add_setting('glo_options_home[posts][posts_heading_link]', array(
		'default'        => '',
		// 'capability'     => 'manage_options',
		'type'           => 'option',
		)
	);

	$wp_customize->add_control('glocal_posts_heading_link', array(
		'label'      => __('Heading Link', 'glocal-network'),
		'section'    => 'home_posts',
		'settings'   => 'glo_options_home[posts][posts_heading_link]',
		'type' => 'url',
		'priority' => 21,
		'input_attrs' => array(
			'placeholder'   => __('Enter a URL or path', 'glocal-network'),
			)
		)
	);

	// Posts - Number
    $wp_customize->add_setting('glo_options_home[posts][number_posts]', array(
        'default'        => '10',
        // 'capability'     => 'manage_options',
        'type'           => 'option',
 
    ));
 
    $wp_customize->add_control('glocal_post_number', array(
        'label'      => __('Number of Posts', 'glocal-network'),
        'section'    => 'home_posts',
        'settings'   => 'glo_options_home[posts][number_posts]',
        'type' => 'number',
        'priority' => 22,
		'input_attrs' => array(
			'min'   => 1,
			'max'   => 20,
			'placeholder'   => __('Min: 1, Max: 20', 'glocal-network'),
		)
    ));

	/**
	* Updates
	*/

    // Updates - Categories
	// Setting
	$wp_customize->add_setting('glo_options_home[updates][featured_category]', array(
		'default'		=> array(),
		// 'default'        => $default,
		'type'			=> 'option',
	));

	// Control
	$wp_customize->add_control( 
		new WP_Customize_Multiple_Select_Control(
			$wp_customize,
			'glocal_updates_category', array(
			'settings' => 'glo_options_home[updates][featured_category]',
			'label'   => __('Categorie(s)', 'glocal-network'),
			'section'  => 'home_updates',
			'type'    => 'multiple-select',
			'choices' => $cats,
			'priority' => 10,
			)
		)
	);

	// Updates - Heading
    $wp_customize->add_setting('glo_options_home[updates][updates_heading]', array(
        'default'        => __('Updates', 'glocal-network'),
        // 'capability'     => 'manage_options',
        'type'           => 'option',
 
    ));
 
    $wp_customize->add_control('glocal_update_heading', array(
        'label'      => __('Heading', 'glocal-network'),
        'section'    => 'home_updates',
        'settings'   => 'glo_options_home[updates][updates_heading]',
        'type' => 'text',
        'priority' => 20,
		'input_attrs' => array(
			'placeholder'   => __('Enter a heading for the updates list', 'glocal-network'),
		)
    ));
    
    // Updates - Background Image
    $wp_customize->add_setting('glo_options_home[updates][updates_heading_image]', array(
            'default' => '',
            'type' => 'option',
        )
    );
    
    $wp_customize->add_control(
        new WP_Customize_Image_Control(
            $wp_customize,
            'glocal_updates_heading_image', array(
                'label'     => __( 'Upload an image', 'glocal-network' ),
                'section'   => 'home_updates',
                'settings'  => 'glo_options_home[updates][updates_heading_image]',
            )
        )
    );

	// Updates - Heading Link
	$wp_customize->add_setting('glo_options_home[updates][updates_heading_link]', array(
		'default'        => '',
		// 'capability'     => 'manage_options',
		'type'           => 'option',
		)
	);

	$wp_customize->add_control('glocal_updates_heading_link', array(
		'label'      => __('Heading Link', 'glocal-network'),
		'section'    => 'home_updates',
		'settings'   => 'glo_options_home[updates][updates_heading_link]',
		'type' => 'url',
		'priority' => 21,
		'input_attrs' => array(
			'placeholder'   => __('Enter a URL or path', 'glocal-network'),
			)
		)
	);

	// Updates - Number
    $wp_customize->add_setting('glo_options_home[updates][number_updates]', array(
        'default'        => '10',
        // 'capability'     => 'manage_options',
        'type'           => 'option',
 
    ));
 
    $wp_customize->add_control('glocal_update_number', array(
        'label'      => __('Number of Updates', 'glocal-network'),
        'section'    => 'home_updates',
        'settings'   => 'glo_options_home[updates][number_updates]',
        'type' => 'number',
        'priority' => 22,
		'input_attrs' => array(
			'min'   => 1,
			'max'   => 20,
			'placeholder'   => __('Min: 1, Max: 20', 'glocal-network'),
		)
    ));

	/**
	* Events
	*/

	// Events - Heading
    $wp_customize->add_setting('glo_options_home[events][events_heading]', array(
        'default'        => __('Events', 'glocal-network'),
        // 'capability'     => 'manage_options',
        'type'           => 'option',
 
    ));
 
    $wp_customize->add_control('glocal_events_heading', array(
        'label'      => __('Heading', 'glocal-network'),
        'section'    => 'home_events',
        'settings'   => 'glo_options_home[events][events_heading]',
        'type' => 'text',
        'priority' => 20,
		'input_attrs' => array(
			'placeholder'   => __('Enter a heading for the events list', 'glocal-network'),
		)
     ));

	// Events - Heading Link
	$wp_customize->add_setting('glo_options_home[events][events_heading_link]', array(
		'default'        => '',
		// 'capability'     => 'manage_options',
		'type'           => 'option',
		)
	);

	$wp_customize->add_control('glocal_events_heading_link', array(
		'label'      => __('Heading Link', 'glocal-network'),
		'section'    => 'home_events',
		'settings'   => 'glo_options_home[events][events_heading_link]',
		'type' => 'url',
		'priority' => 21,
		'input_attrs' => array(
			'placeholder'   => __('Enter a URL or path', 'glocal-network'),
			)
		)
	);

	// Events - Number
    $wp_customize->add_setting('glo_options_home[events][number_events]', array(
        'default'        => '10',
        // 'capability'     => 'manage_options',
        'type'           => 'option',
 
    ));
 
    $wp_customize->add_control('glocal_events_number', array(
        'label'      => __('Number of Events', 'glocal-network'),
        'section'    => 'home_events',
        'settings'   => 'glo_options_home[events][number_events]',
        'type' => 'number',
        'priority' => 22,
		'input_attrs' => array(
			'min'   => 1,
			'max'   => 20,
			'placeholder'   => __('Min: 1, Max: 20', 'glocal-network'),
		)
    ));

	/**
	* Sites
	*/

	// Sites - Heading
    $wp_customize->add_setting('glo_options_home[sites][sites_heading]', array(
        'default'        => __('Sites', 'glocal-network'),
        // 'capability'     => 'manage_options',
        'type'           => 'option',
 
    ));
 
    $wp_customize->add_control('glocal_sites_heading', array(
        'label'      => __('Heading', 'glocal-network'),
        'section'    => 'home_sites',
        'settings'   => 'glo_options_home[sites][sites_heading]',
        'type' => 'text',
        'priority' => 20,
		'input_attrs' => array(
			'placeholder'   => __('Enter a heading for the sites list', 'glocal-network'),
		)
     ));

	// Sites - Heading Link
	$wp_customize->add_setting('glo_options_home[sites][sites_heading_link]', array(
		'default'        => '',
		// 'capability'     => 'manage_options',
		'type'           => 'option',
		)
	);

	$wp_customize->add_control('glocal_sites_heading_link', array(
		'label'      => __('Heading Link', 'glocal-network'),
		'section'    => 'home_sites',
		'settings'   => 'glo_options_home[sites][sites_heading_link]',
		'type' => 'url',
		'priority' => 21,
		'input_attrs' => array(
			'placeholder'   => __('Enter a URL or path', 'glocal-network'),
			)
		)
	);

	// Sites - Number
    $wp_customize->add_setting('glo_options_home[sites][number_sites]', array(
        'default'        => '8',
        // 'capability'     => 'manage_options',
        'type'           => 'option',
 
    ));
 
    $wp_customize->add_control('glocal_sites_number', array(
        'label'      => __('Number of Sites', 'glocal-network'),
        'section'    => 'home_sites',
        'settings'   => 'glo_options_home[sites][number_sites]',
        'type' => 'number',
        'priority' => 22,
		'input_attrs' => array(
			'min'   => 1,
			'max'   => 20,
			'placeholder'   => __('Min: 1, Max: 20', 'glocal-network'),
		)
    ));

	// Look & Feel - To be implemented later

}
add_action( 'customize_register', 'glocal_customize_register' );

// Return nice array of customization options

function glocal_customization_settings() {
	$glocal_home_settings = get_option('glo_options_home');

	if (!empty($glocal_home_settings)) {
		foreach ($glocal_home_settings as $key => $option)
			$home_options[$key] = $option;
		return $home_options;
	} else {
		return null;
	}
	
}

