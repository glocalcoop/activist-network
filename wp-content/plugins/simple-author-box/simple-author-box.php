<?php
/**
* Plugin Name: Simple Author Box
* Plugin URI: http://wordpress.org/plugins/simple-author-box/
* Description: Adds a responsive author box with social icons on your posts.
* Version: 1.5
* Author: Tiguan
* Author URI: http://tiguandesign.com
* License: GPLv2
*/

/*  Copyright 2014 Tiguan (email : themesupport [at] tiguandesign [dot] com)

    THIS PROGRAM IS FREE SOFTWARE; YOU CAN REDISTRIBUTE IT AND/OR MODIFY
    IT UNDER THE TERMS OF THE GNU GENERAL PUBLIC LICENSE AS PUBLISHED BY
    THE FREE SOFTWARE FOUNDATION; EITHER VERSION 2 OF THE LICENSE, OR
    (AT YOUR OPTION) ANY LATER VERSION.

    THIS PROGRAM IS DISTRIBUTED IN THE HOPE THAT IT WILL BE USEFUL,
    BUT WITHOUT ANY WARRANTY; WITHOUT EVEN THE IMPLIED WARRANTY OF
    MERCHANTABILITY OR FITNESS FOR A PARTICULAR PURPOSE.  SEE THE
    GNU GENERAL PUBLIC LICENSE FOR MORE DETAILS.

    YOU SHOULD HAVE RECEIVED A COPY OF THE GNU GENERAL PUBLIC LICENSE
    ALONG WITH THIS PROGRAM; IF NOT, WRITE TO THE FREE SOFTWARE
    FOUNDATION, INC., 51 FRANKLIN ST, FIFTH FLOOR, BOSTON, MA  02110-1301  USA

*/


/*----------------------------------------------------------------------------------------------------------
    Main Plugin Class
-----------------------------------------------------------------------------------------------------------*/

if( !class_exists( 'Simple_Author_Box' ) ) {

    class Simple_Author_Box {

/*----------------------------------------------------------------------------------------------------------
    Function Construct
-----------------------------------------------------------------------------------------------------------*/

    public function __construct() {

        global $options;

        $options = get_option( 'saboxplugin_options', 'checked' );                         // retrieve the plugin settings from the options table
        define( 'SIMPLE_AUTHOR_BOX_LAST_UPDATE', date_i18n( 'F j, Y', '1409122800' ) );    // Defining plugin last update
        define( 'SIMPLE_AUTHOR_BOX_PATH', plugin_dir_path( __FILE__ ) );                   // Defining plugin dir path
        define( 'SIMPLE_AUTHOR_BOX_DIRNAME', basename( dirname( __FILE__ ) ) );            // Defining plugin dir name
        define( 'SIMPLE_AUTHOR_BOX_VERSION', 'v1.5');                                      // Defining plugin version
        define( 'SIMPLE_AUTHOR_BOX', 'Simple Author Box');                                 // Defining plugin name
        define( 'SIMPLE_AUTHOR_BOX_FOOTER', 10 );


        add_action( 'admin_init', array( $this, 'saboxplugin_init_settings' ) );
        add_action( 'plugins_loaded', array( $this, 'saboxplugin_translation' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'saboxplugin_author_box_style' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'saboxplugin_author_box_font' ) );

        if ( isset( $options['sab_footer_inline_style'] ) ) {
            add_action( 'wp_footer', array( $this, 'saboxplugin_author_box_inline_style' ), SIMPLE_AUTHOR_BOX_FOOTER+3 );
        } else {
            add_action( 'wp_head', array( $this, 'saboxplugin_author_box_inline_style' ), 15 );
        }

        add_action( 'admin_enqueue_scripts', array( $this, 'saboxplugin_admin_style' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'saboxplugin_color_picker' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'saboxplugin_scripts' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'saboxplugin_collapsible' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'saboxplugin_font_awesome_css' ), 999 );

        add_action( 'admin_menu', array( $this, 'saboxplugin_add_menu' ) );

        $this->path = plugin_basename( __FILE__ );
        add_filter( "plugin_action_links_$this->path", array( $this, 'saboxplugin_settings_link' ) );

        if ( !class_exists( 'Sabox_Social_Icons' ) ) {
            require_once( SIMPLE_AUTHOR_BOX_PATH . 'core/sabox_social_icons.php' );
            require_once( SIMPLE_AUTHOR_BOX_PATH . 'core/sabox_author_box.php' );
        }

    }


/*----------------------------------------------------------------------------------------------------------
    Activation & Deactivation Hooks
-----------------------------------------------------------------------------------------------------------*/

        public static function sab_activate() {
            // nothing to do yet
        }

        public static function sab_deactivate() {
            // nothing to do yet
        }


/*----------------------------------------------------------------------------------------------------------
    Load plugin textdomain
-----------------------------------------------------------------------------------------------------------*/

        public function saboxplugin_translation() {
            load_plugin_textdomain( 'saboxplugin', false, SIMPLE_AUTHOR_BOX_DIRNAME . '/lang/' );
        }


/*----------------------------------------------------------------------------------------------------------
    Plugin Settings
-----------------------------------------------------------------------------------------------------------*/

        public function saboxplugin_init_settings() {
            register_setting( 'sabox_plugin', 'saboxplugin_options' );
            register_setting( 'sabox_plugin', 'sab_box_margin_top' );
            register_setting( 'sabox_plugin', 'sab_box_margin_bottom' );
            register_setting( 'sabox_plugin', 'sab_box_icon_size' );
            register_setting( 'sabox_plugin', 'sab_box_name_size' );
            register_setting( 'sabox_plugin', 'sab_box_web_size' );
            register_setting( 'sabox_plugin', 'sab_box_name_font' );
            register_setting( 'sabox_plugin', 'sab_box_subset' );
            register_setting( 'sabox_plugin', 'sab_box_desc_font' );
            register_setting( 'sabox_plugin', 'sab_box_web_font' );
            register_setting( 'sabox_plugin', 'sab_box_desc_size' );
        }


/*----------------------------------------------------------------------------------------------------------
    Adding the author box main CSS
-----------------------------------------------------------------------------------------------------------*/

        public function saboxplugin_author_box_style() {

            if( !is_single() and !is_page() and !is_author() and !is_archive() ) {
                return;
            }

            if ( is_rtl() ) {
                wp_enqueue_style( 'sab-plugin', plugins_url( '/css/simple-author-box-rtl.min.css', __FILE__ ), false, SIMPLE_AUTHOR_BOX_VERSION ); // to debug rtl style change the url to /css/dev/simple-author-box-rtl.css
            } else {
                wp_enqueue_style( 'sab-plugin', plugins_url( '/css/simple-author-box.min.css', __FILE__ ), false, SIMPLE_AUTHOR_BOX_VERSION ); // to debug style change the url to /css/dev/simple-author-box.css

            }

        }


/*----------------------------------------------------------------------------------------------------------
    Enqueue Google Fonts
-----------------------------------------------------------------------------------------------------------*/

        public function saboxplugin_author_box_font() {

            if( !is_single() and !is_page() and !is_author() and !is_archive() ) {
                return;
            }

            global $options;

            $sab_protocol = is_ssl() ? 'https' : 'http';

            if ( get_option( 'sab_box_subset' ) != 'None' ) {
                $sab_box_subset = get_option( 'sab_box_subset' );
                $sab_subset = '&amp;subset='.$sab_box_subset;

            } else {
                $sab_subset = '';
            }

            $sab_author_font    = get_option( 'sab_box_name_font' );
            $sab_desc_font      = get_option( 'sab_box_desc_font' );
            $sab_web_font       = get_option( 'sab_box_web_font' );

            if ( get_option( 'sab_box_name_font' ) != 'none' ) {
                wp_enqueue_style( 'sab-author-name-font', $sab_protocol . '://fonts.googleapis.com/css?family='.str_replace(' ', '+', $sab_author_font).':400,700,400italic,700italic'.$sab_subset, array(), null );
            }

            if ( get_option( 'sab_box_desc_font' ) != 'none' ) {
                wp_enqueue_style( 'sab-author-desc-font', $sab_protocol . '://fonts.googleapis.com/css?family='.str_replace(' ', '+', $sab_desc_font).':400,700,400italic,700italic'.$sab_subset, array(), null );
            }

            if ( isset( $options['sab_web'] ) and get_option( 'sab_box_web_font' ) != 'none' ) {
                wp_enqueue_style( 'sab-author-web-font', $sab_protocol . '://fonts.googleapis.com/css?family='.str_replace(' ', '+', $sab_web_font).':400,700,400italic,700italic'.$sab_subset, array(), null );
            }
        }


/*----------------------------------------------------------------------------------------------------------
    Adding the author box dynamic stylesheet generated by plugin options
-----------------------------------------------------------------------------------------------------------*/

        public function saboxplugin_author_box_inline_style() {

            if( !is_single() and !is_page() and !is_author() and !is_archive() ) {
                return;
            }

            global $options;

            if ( get_option( 'sab_box_margin_top' ) ) {
                $sabox_top_margin = get_option( 'sab_box_margin_top' );
            } else {
                $sabox_top_margin = 0;
            }

            if ( get_option( 'sab_box_margin_bottom' ) ) {
                $sabox_bottom_margin = get_option( 'sab_box_margin_bottom' );
            } else {
                $sabox_bottom_margin = 0;
            }

             if ( get_option( 'sab_box_name_size' ) ) {
                $sabox_name_size = get_option( 'sab_box_name_size' );
            } else {
                $sabox_name_size = 18;
            }

             if ( isset( $options['sab_web'] ) and get_option( 'sab_box_web_size' ) ) {
                $sabox_web_size = get_option( 'sab_box_web_size' );
            } else {
                $sabox_web_size = 14;
            }

             if ( get_option( 'sab_box_desc_size' ) ) {
                $sabox_desc_size = get_option( 'sab_box_desc_size' );
            } else {
                $sabox_desc_size = 14;
            }


             if ( get_option( 'sab_box_icon_size' ) ) {
                $sabox_icon_size = get_option( 'sab_box_icon_size' );
            } else {
                $sabox_icon_size = 14;
            }

            $style = '<style type="text/css">';

            // Border color of Simple Author Box
            if( isset( $options['sab_box_border'] ) and !empty( $options['sab_box_border'] ) ) {
                $style .= '.saboxplugin-wrap {border-color:'. esc_html( $options['sab_box_border'] ) .';}';
                $style .= '.saboxplugin-wrap .saboxplugin-socials {-webkit-box-shadow: 0 0.05em 0 0 '. esc_html( $options['sab_box_border'] ) .' inset; -moz-box-shadow:0 0.05em 0 0 '. esc_html( $options['sab_box_border'] ) .' inset;box-shadow:0 0.05em 0 0 '. esc_html( $options['sab_box_border'] ) .' inset;}';
            }
            // Avatar image style
            if( isset( $options['sab_avatar_style'] ) ) {
                $style .= '.saboxplugin-wrap .saboxplugin-gravatar img {-webkit-border-radius:50%;-moz-border-radius:50%;-ms-border-radius:50%;-o-border-radius:50%;border-radius:50%;}';
            }
            // Social icons style
            if( isset( $options['sab_colored'] ) and isset( $options['sab_icons_style'] ) ) {
                $style .= '.saboxplugin-wrap .saboxplugin-socials .saboxplugin-icon-color {-webkit-border-radius:50%;-moz-border-radius:50%;-ms-border-radius:50%;-o-border-radius:50%;border-radius:50%;}';
            }
             // Long Shadow
            if( isset( $options['sab_colored'] ) and !isset( $options['sab_box_long_shadow'] ) ) {
                $style .= '.saboxplugin-wrap .saboxplugin-socials .saboxplugin-icon-color:before {text-shadow: none;}';
            }
            // Avatar hover effect
            if( isset( $options['sab_avatar_style'] ) and isset( $options['sab_avatar_hover'] ) ) {
                $style .= '.saboxplugin-wrap .saboxplugin-gravatar img {-webkit-transition:all .5s ease;-moz-transition:all .5s ease;-o-transition:all .5s ease;transition:all .5s ease;}';
                 $style .= '.saboxplugin-wrap .saboxplugin-gravatar img:hover {-webkit-transform:rotate(45deg);-moz-transform:rotate(45deg);-o-transform:rotate(45deg);-ms-transform:rotate(45deg);transform:rotate(45deg);}';
            }
            // Social icons hover effect
            if( isset( $options['sab_icons_style'] ) and isset( $options['sab_social_hover'] ) ) {
                $style .= '.saboxplugin-wrap .saboxplugin-socials .saboxplugin-icon-color, .saboxplugin-wrap .saboxplugin-socials .saboxplugin-icon-grey {-webkit-transition: all 0.3s ease-in-out;-moz-transition: all 0.3s ease-in-out;-o-transition: all 0.3s ease-in-out;-ms-transition: all 0.3s ease-in-out;transition: all 0.3s ease-in-out;}.saboxplugin-wrap .saboxplugin-socials .saboxplugin-icon-color:hover,.saboxplugin-wrap .saboxplugin-socials .saboxplugin-icon-grey:hover {-webkit-transform: rotate(360deg);-moz-transform: rotate(360deg);-o-transform: rotate(360deg);-ms-transform: rotate(360deg);transform: rotate(360deg);}';
            }
             // Thin border
            if( isset( $options['sab_colored'] ) and !isset( $options['sab_box_thin_border'] ) ) {
                $style .= '.saboxplugin-wrap .saboxplugin-socials .saboxplugin-icon-color {border: medium none !important;}';
            }
            // Background color of social icons bar
            if( isset( $options['sab_box_icons_back'] ) and !empty( $options['sab_box_icons_back'] ) ) {
                $style .= '.saboxplugin-wrap .saboxplugin-socials{background-color:'. esc_html( $options['sab_box_icons_back'] ) .';}';
            }
            // Color of social icons (for symbols only):
            if( isset( $options['sab_box_icons_color'] ) and !empty( $options['sab_box_icons_color'] ) ) {
                $style .= '.saboxplugin-wrap .saboxplugin-socials .saboxplugin-icon-grey {color:'. esc_html( $options['sab_box_icons_color'] ) .';}';
            }
            // Author name color
            if( isset( $options['sab_box_author_color'] ) and !empty( $options['sab_box_author_color'] ) ) {
                $style .= '.saboxplugin-wrap .saboxplugin-authorname a {color:'. esc_html( $options['sab_box_author_color'] ) .';}';
            }

            // Author web color
            if( isset( $options['sab_web'] ) and isset( $options['sab_box_web_color'] ) and !empty( $options['sab_box_web_color'] ) ) {
                $style .= '.saboxplugin-wrap .saboxplugin-web a {color:'. esc_html( $options['sab_box_web_color'] ) .';}';
            }

            // Author name font family
            if ( get_option( 'sab_box_name_font' ) != 'none' ) {
                $author_name_font = get_option( 'sab_box_name_font' );
                $style .= '.saboxplugin-wrap .saboxplugin-authorname {font-family:"'. esc_html( $author_name_font ) .'";}';
            }

            // Author description font family
            if ( get_option( 'sab_box_desc_font' ) != 'none' ) {
                $author_desc_font = get_option( 'sab_box_desc_font' );
                $style .= '.saboxplugin-wrap .saboxplugin-desc {font-family:'. esc_html( $author_desc_font ) .';}';
            }

            // Author web font family
            if ( isset( $options['sab_web'] ) and get_option( 'sab_box_web_font' ) != 'none' ) {
                $author_web_font = get_option( 'sab_box_web_font' );
                $style .= '.saboxplugin-wrap .saboxplugin-web {font-family:"'. esc_html( $author_web_font ) .'";}';
            }

            // Author description font style
            if( isset( $options['sab_desc_style'] ) ) {
            $style .= '.saboxplugin-wrap .saboxplugin-desc {font-style:italic;}';
            }
            // Margin top
            $style .= '.saboxplugin-wrap {margin-top:'. absint( $sabox_top_margin ) . 'px;}';
            // Margin bottom
            $style .= '.saboxplugin-wrap {margin-bottom:'. absint( $sabox_bottom_margin ) . 'px;}';
            // Author name text size
            $style .= '.saboxplugin-wrap .saboxplugin-authorname {font-size:'. absint( $sabox_name_size ) . 'px; line-height:'. absint( $sabox_name_size+7 ) .'px;}';
            // Author description font size
            $style .= '.saboxplugin-wrap .saboxplugin-desc {font-size:'. absint( $sabox_desc_size ) . 'px; line-height:'. absint( $sabox_desc_size+7 ) .'px;}';
            // Author website text size
            $style .= '.saboxplugin-wrap .saboxplugin-web {font-size:'. absint( $sabox_web_size ) . 'px;}';
            // Icons size
            $style .= '.saboxplugin-wrap .saboxplugin-socials .saboxplugin-icon-color {font-size:'. absint( $sabox_icon_size+3 ) . 'px;}';
            $style .= '.saboxplugin-wrap .saboxplugin-socials .saboxplugin-icon-color:before {width:'. absint( $sabox_icon_size+$sabox_icon_size ) . 'px; height:'. absint( $sabox_icon_size+$sabox_icon_size ) . 'px; line-height:'. absint( $sabox_icon_size+$sabox_icon_size+1 ) . 'px; }';
            $style .= '.saboxplugin-wrap .saboxplugin-socials .saboxplugin-icon-grey {font-size:'. absint( $sabox_icon_size ) . 'px;}';
            $style .= '</style>';

            echo $style;
        }


/*----------------------------------------------------------------------------------------------------------
    Adding admin options stylesheet
-----------------------------------------------------------------------------------------------------------*/

        public function saboxplugin_admin_style( $hook ) {

            // load stylesheet only on plugin options page
            global $saboxplugin_settings_page;
            if ( $hook != $saboxplugin_settings_page )
                return;
            if ( is_rtl() ) {
                wp_enqueue_style( 'saboxplugin-admin-style', plugin_dir_url( __FILE__ ) . 'css/sabox-admin-style-rtl.min.css' ); // to debug admin rtl style change the url to /css/dev/sabox-admin-style-rtl.css
            } else {
                wp_enqueue_style( 'saboxplugin-admin-style', plugin_dir_url( __FILE__ ) . 'css/sabox-admin-style.min.css' ); // to debug admin style change the url to /css/dev/sabox-admin-style.css
            }
            wp_enqueue_style( 'jquery-ui', plugin_dir_url( __FILE__ ) . 'css/jquery-ui.min.css' );

        }


/*----------------------------------------------------------------------------------------------------------
    Adding colorpicker to plugin options page
-----------------------------------------------------------------------------------------------------------*/

        public function saboxplugin_color_picker( $hook_color ) {
            // load color picker only on plugin options page
            global $saboxplugin_settings_page;
            if ( $hook_color != $saboxplugin_settings_page )
                return;
            wp_enqueue_style( 'wp-color-picker' );
            wp_enqueue_script( 'sabox-color-js', plugins_url('js/sabox-color-picker.js', __FILE__ ), array( 'wp-color-picker' ), false, true );
        }


/*----------------------------------------------------------------------------------------------------------
    Enqueue scripts to plugin options page
-----------------------------------------------------------------------------------------------------------*/

        public function saboxplugin_scripts( $hook_slide ) {
            // load slide only on plugin options page
            global $saboxplugin_settings_page;
            if ( $hook_slide != $saboxplugin_settings_page )
                return;
            wp_enqueue_script( 'jquery-ui-slider' );
            wp_enqueue_script( 'sabox-slide', plugins_url('js/sabox-slide.js', __FILE__ ), array( 'jquery', 'jquery-ui-slider' ), SIMPLE_AUTHOR_BOX_VERSION, true );
            wp_enqueue_script( 'sabox-hide', plugins_url('js/sabox-hide.js', __FILE__ ), array( 'jquery' ), SIMPLE_AUTHOR_BOX_VERSION, true );

        }


/*----------------------------------------------------------------------------------------------------------
    Enqueue scripts to plugin options page for collapsible options
-----------------------------------------------------------------------------------------------------------*/

        function saboxplugin_collapsible( $sab_suffix ) {
            global $saboxplugin_settings_page;
            if ( $sab_suffix != $saboxplugin_settings_page )
                return;
                wp_enqueue_script( 'postbox' );
                wp_enqueue_script( 'postbox-edit', plugins_url('js/postbox-edit.js', __FILE__ ), array( 'jquery', 'postbox' ) );
        }


/*----------------------------------------------------------------------------------------------------------
    Enqueue Font Awesome in front-end
-----------------------------------------------------------------------------------------------------------*/

        public function saboxplugin_font_awesome_css() {
            global $options;
            if ( !isset( $options['sab_load_fa'] ) ) {
                wp_enqueue_style( 'font-awesome', '//netdna.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css' );
            }

        }


/*----------------------------------------------------------------------------------------------------------
    Adding settings link on plugins page
-----------------------------------------------------------------------------------------------------------*/

        public function saboxplugin_settings_link( $links ) {

            $settings_link = '<a href="options-general.php?page=simple-author-box-options">Settings</a>';
            array_unshift( $links, $settings_link );
            return $links;

        }


/*----------------------------------------------------------------------------------------------------------
    Adding settings link on plugins page
-----------------------------------------------------------------------------------------------------------*/

        public function saboxplugin_add_menu() {

        // Add a page to manage the plugin's settings
        global $saboxplugin_settings_page;
        $saboxplugin_settings_page = add_options_page( 'Simple Author Box', 'Simple Author Box', 'manage_options', 'simple-author-box-options', array( $this, 'saboxplugin_settings_page' ) );

        }


/*----------------------------------------------------------------------------------------------------------
    Busted if user can't manage options
-----------------------------------------------------------------------------------------------------------*/

        public function saboxplugin_settings_page() {

            if( !current_user_can( 'manage_options' ) ) {
                wp_die(__( 'You do not have sufficient permissions to access this page.' ) );
            }
            include( SIMPLE_AUTHOR_BOX_PATH . 'core/sabox-fonts.php' );
            require_once( SIMPLE_AUTHOR_BOX_PATH . 'template/options.php' );
        }
    }
}


if( class_exists( 'Simple_Author_Box' ) ) {

    // Installation and uninstallation hooks
    register_activation_hook( __FILE__, array( 'Simple_Author_Box', 'sab_activate' ) );
    register_deactivation_hook( __FILE__, array( 'Simple_Author_Box', 'sab_deactivate' ) );

    // Initialise Class
    $simple_author_box_by_tiguan = new Simple_Author_Box();
}