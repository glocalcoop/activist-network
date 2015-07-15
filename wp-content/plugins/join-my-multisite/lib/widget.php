<?php
/*

    This file is part of Join My Multisite, a plugin for WordPress.

    Join My Multisite is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 2 of the License, or
    (at your option) any later version.

    Sitewide Comment Control is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with WordPress.  If not, see <http://www.gnu.org/licenses/>.
*/

if (!defined('ABSPATH')) {
    die();
}

// Basic JMM Widget
class jmm_JMM_Widget extends WP_Widget {

    function __construct() {
        $widget_ops = array( 'classname' => 'jmm_add_users', 'description' => 'Allow members of your network to join a specific site.' );
        $control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'helf-add-user-widget' );
        parent::__construct( 'helf-add-user-widget', 'Join My Site Widget', $widget_ops, $control_ops );
    }

	function widget( $args, $instance ) {
		extract( $args );

		/* User-selected settings. */
		//$title =  isset( $instance['title'] ) ? apply_filters('widget_title', $instance['title'] ) : "" ;
		$title = isset( $instance['title'] ) ? apply_filters('widget_title', $instance['title'] ) : "" ;
		$notregistered = isset( $instance['notreg'] ) ? $instance['notreg'] : "";
		$notmember = isset( $instance['notmember'] ) ? $instance['notmember'] : "";
		$member = isset( $instance['member'] ) ? $instance['member'] : "";
		$welcome = isset( $instance['welcome'] ) ? $instance['welcome'] : "";
		$show_form = isset( $instance['show_form'] ) ? $instance['show_form'] : "";
		$jmm_options = get_option( 'helfjmm_options' );
		global $current_user, $blog_id, $user_login;

		/* Before widget (defined by themes). */
		echo $before_widget;

		/* Title of widget (before and after defined by themes). */
		if ( $title )
			echo $before_title . $title . $after_title;
			
			if( isset($_POST['jmm-join-site']) || isset($_POST['join-site']) ){
                // This is the magic sauce.
                do_action('jmm_joinsite', array('JMM', 'join_site'));
                echo '<p>'.$welcome.'</p>';
            } else {
                if( !is_user_logged_in() ) {
                    if ( get_option('users_can_register') == 1 ) {
                        // If user isn't logged in but we allow for registration.... 
                        // IF we have a custom URL, use it, else send to /wp-signup.php for this site (becuase join my SITE, not network)
                        if ( !is_null($jmm_options['perpage']) && $jmm_options['perpage'] != "XXXXXX"  )
                            {$goto = get_permalink($jmm_options['perpage']); }
                        else
                            {$goto = '/wp-signup.php';}
                        
                        // Here is our form
                        echo '<form action="'.$goto.'" method="post" id="notmember">';
                        echo '<input type="hidden" name="action" value="jmm-join-site">';
                        echo '<input type="submit" value="'.$notregistered.'" name="join-site" id="join-site" class="button">';
                        echo '</form>';
                        
                        // Do we show the inline login form?
                        if ( $show_form == 'on' ) {
                            echo '<br /><h3 class="widget-title">'. __("Log in") .'</h3>';
                            wp_login_form(array( 'value_remember' => 1));                          
                        }
                        
                    }
                    // If we don't allow registration, we show nothing. On to the next one!
                } elseif( !is_user_member_of_blog() ) {
                    // If user IS logged in, then let's invite them to play.
                    echo '<form action="?jmm-join-site" method="post" id="notmember">';
                    echo '<input type="hidden" name="action" value="jmm-join-site">';
                    echo '<input type="submit" value="'.$notmember.'" name="join-site" id="join-site" class="button">';
                    echo '</form>';
                } else {
                    // Otherwise we're already a member, hello, mum!
                    echo '<p>'.$member.'</p>';
                }
        
            }        
		/* After widget (defined by themes). */
		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		/* Strip tags (if needed) and update the widget settings. */
		$instance['title'] = strip_tags( $new_instance['title'] );

		$instance['notreg'] = strip_tags( $new_instance['notreg'] );
		$instance['notmember'] = strip_tags( $new_instance['notmember'] );

		$instance['member'] = strip_tags( $new_instance['member'] );
		$instance['welcome'] = strip_tags( $new_instance['welcome'] );
        $instance['loginform'] = strip_tags( $new_instance['loginform'] );

        $instance['show_form'] = $new_instance['show_form'];      
		return $instance;
	}

	function form( $instance ) {

		/* Set up some default widget settings. */
		$defaults = array( 'title' => 'Welcome to My Site', 'notreg' => 'Register for an account', 'notmember' => 'Join this site', 'member' => 'Nice to see you again.', 'welcome' => 'Hi, new member.', 'loginform' => 'Log in', 'show_form' => 0 );
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'join-my-multisite' )?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:90%;" />
		</p>

		<hr>

		<p><strong><?php _e( 'Button Text', 'join-my-multisite' )?></strong></label>

        <?php if (get_option('users_can_register')) { ?>
		<p>
			<label for="<?php echo $this->get_field_id( 'notreg' ); ?>"><?php _e( 'Not registered on the network:', 'join-my-multisite' )?></label>
			<input id="<?php echo $this->get_field_id( 'notreg' ); ?>" name="<?php echo $this->get_field_name( 'notreg' ); ?>" value="<?php echo $instance['notreg']; ?>" style="width:90%;" />
		</p>
		<p>
            <input class="checkbox" type="checkbox" <?php checked( $instance['show_form'], 'on' ); ?> id="<?php echo $this->get_field_id( 'show_form' ); ?>" name="<?php echo $this->get_field_name( 'show_form' ); ?>" /> 
            <label for="<?php echo $this->get_field_id( 'show_form' ); ?>"><?php _e( 'Show in-line login form.', 'join-my-multisite' )?></label>
        </p>
        
        <?php } ?>
		<p>
			<label for="<?php echo $this->get_field_id( 'notmember' ); ?>"><?php _e( 'Not a member of this site:', 'join-my-multisite' )?></label>
			<input id="<?php echo $this->get_field_id( 'notmember' ); ?>" name="<?php echo $this->get_field_name( 'notmember' ); ?>" value="<?php echo $instance['notmember']; ?>" style="width:90%;" />
		</p>

		<hr>

		<p><strong><?php _e( 'Welcome Message Text', 'join-my-multisite' )?></strong></label>

		<p>
			<label for="<?php echo $this->get_field_id( 'member' ); ?>"><?php _e( 'Existing members:', 'join-my-multisite' )?></label>
			<input id="<?php echo $this->get_field_id( 'member' ); ?>" name="<?php echo $this->get_field_name( 'member' ); ?>" value="<?php echo $instance['member']; ?>" style="width:90%;" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'welcome' ); ?>"><?php _e( 'New member (shown on signup):', 'join-my-multisite' )?></label>
			<input id="<?php echo $this->get_field_id( 'welcome' ); ?>" name="<?php echo $this->get_field_name( 'welcome' ); ?>" value="<?php echo $instance['welcome']; ?>" style="width:90%;" />
		</p>

<?php 
		}
}

// Register the widget
register_widget( 'jmm_JMM_Widget' );

function jmm_front_end_login_fail( $username ) {
    $referrer = $_SERVER['HTTP_REFERER'];
     
    // if there's a valid referrer, and it's not the default log-in screen
    if ( !empty($referrer) && !strstr($referrer,'wp-login') && !strstr($referrer,'wp-admin') ) {
        wp_redirect(home_url() . '/?jmm=failed' ); 
        exit;
     }
         
        if ( !empty($referrer) && !strstr($referrer,'wp-login') && !strstr($referrer,'wp-admin') ) {
            if ( !strstr($referrer,'jmm=failed') ) { // don’t append twice
                if(!strstr($referrer, '?')){
                    wp_redirect( $referrer . '?jmm=failed' ); 
                } else {
                    wp_redirect( $referrer . '&jmm=failed' ); 
                }
            } else {
                wp_redirect( $referrer );
            }
        exit;
        }

    // Filtering wp_authenticate becuase it's an idiot and wp_login_failed doesn't think that blank fields is a fail...
    // http://wordpress.stackexchange.com/questions/28786/action-wp-login-failed-not-working-if-only-one-field-is-filled-out
    if( ! function_exists('wp_authenticate') ) {
        function wp_authenticate($username, $password) {
            $username = sanitize_user($username);
            $password = trim($password);
            $user = apply_filters('authenticate', null, $username, $password);
     
            if ( is_wp_error($user) ) {
                do_action('wp_login_failed', $username);
            }
     
            return $user;
        }
    }
}