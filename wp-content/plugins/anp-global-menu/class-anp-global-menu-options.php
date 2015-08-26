<?php

/**
 * The file that defines the network settings options class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://example.com
 * @since      0.0.1-dev
 *
 * @package    ANP_Global_Menu
 * @subpackage ANP_Global_Menu/classes
 */

class ANP_Global_Menu_Options {
 
    private static $instance;
    /**
     * Get active object instance
     *
     * @since 1.0
     *
     * @access public
     * @static
     * @return object
     */
    public static function get_instance() {
 
        if ( ! self::$instance )
            self::$instance = new ANP_Global_Menu_Options();
 
        return self::$instance;
    }
 
    /**
     * Class constructor.  Includes constants, includes and init method.
     *
     * @since 1.0
     *
     * @access public
     * @return void
     */
    public function __construct() {
        $this->init();
    }
 
    /**
     * Run action and filter hooks.
     *
     * @since 1.0
     *
     * @access private
     * @return void
     */
    private function init() {
 
        //Adds settings to Network Settings
        add_filter( 'wpmu_options'       , array( $this, 'show_network_settings' ) );
        add_action( 'update_wpmu_options', array( $this, 'save_network_settings' ) );
 
    }
 
    public static function save_network_settings() {
        $posted_settings  = array_map( 'sanitize_text_field', $_POST['anp-global-menu'] );
 
        foreach ( $posted_settings as $name => $value ) {
            update_site_option( $name, $value );
        }
    }
 
    public static function show_network_settings() {
        $settings = self::get_network_settings();
    ?>
        <h3><?php _e( 'Activist Network Global Menu' ); ?></h3>
        <table id="menu" class="form-table">

        <?php
            foreach ( $settings as $setting ) :
                ?>
     
                <tr valign="top">
                    <th scope="row"><?php echo $setting['name']; ?></th>
                    <td>

                        <?php if( 'select' == $setting['type'] ) { ?>

                            <?php $selected_menu = get_site_option( $setting['id'] ); ?>

                            <select name="anp-global-menu[<?php echo $setting['id']; ?>]" id="anp-global-menu">

                                <option value='' <?php selected( $selected_menu, '' ); ?>>--Select a Menu--</option>

                                <?php $options = $setting['options'];

                                foreach( $options as $option ) { ?>

                                    <option value="<?php echo $option->slug; ?>"  <?php selected( $selected_menu, $option->slug ); ?>><?php echo $option->name; ?></option>

                                <?php } ?>

                            </select>    

                        <?php } elseif ( 'text' == $setting['type'] ) { ?>
                            
                            <input type="<?php echo $setting['type'];?>" name="anp-global-menu[<?php echo $setting['id']; ?>]" value="<?php echo esc_attr( get_site_option( $setting['id'] ) ); ?>" />
                            <br /><?php echo $setting['desc']; ?>

                        <?php } ?>

                    </td>
                </tr>

                <?php

            endforeach;

        echo '</table>';

    }
 
    public static function get_network_settings() {
 
        $settings[] = array(
                    'id'            => 'anp-global-nav-menu',
                    'name'          => __( 'Select a Menu' ),
                    'desc'          => __( 'Please select a menu to use as the global network menu.' ),
                    'options'       => get_terms( 'nav_menu', array( 'hide_empty' => true ) ), // Must be an array
                    'type'          => 'select'
        );
 
        return apply_filters( 'plugin_settings', $settings );
    }

}
 
