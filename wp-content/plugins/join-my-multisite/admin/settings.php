<?php
/*
    This file is part of Join My Multisite, a plugin for WordPress.

    Join My Multisite is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 2 of the License, or
    (at your option) any later version.

    Join My Multisite is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with WordPress.  If not, see <http://www.gnu.org/licenses/>.

*/

if (!defined('ABSPATH')) {
    die();
}
 
// In lieu of options.php....
if ( ! empty( $_POST ) && check_admin_referer( 'update', 'helfjmm_update_options' ) ) {
    $new_options = get_option( 'helfjmm_options' );

    if ( !empty($_POST['jmm_type']) ) $new_options['type'] = absint($_POST['jmm_type']);
    
    if ( !empty($_POST['jmm_role']) && array_key_exists( sanitize_text_field( $_POST['jmm_role'] ) , get_editable_roles() ) ) $new_options['role'] = $_POST['jmm_role'];
    
    if ( !empty($_POST['jmm_persite']) && absint($_POST['jmm_persite']) == '1' ) { 
		$new_options['persite'] = '1' ;
	} else {
		$new_options['persite'] = '0';
	}
        if (isset($_POST['jmm_perpage'])) $new_options['perpage'] = $_POST['jmm_perpage'];

    update_option('helfjmm_options', $new_options);
    update_option( 'default_role', $new_options['role']);

    // Echo 
    ?><div id='message' class='updated fade'><p><strong><?php _e('Options Updated!', 'join-my-multisite'); ?></strong></p></div><?php
}

?>
    <div class="wrap">
        <div id="icon-users" class="icon32"><br></div>
        <h2><?php _e("Join My Multisite Settings", 'join-my-multisite'); ?></h2>
        
        <?php 
        $jmm_options = get_option( 'helfjmm_options' );
        ?>
    
        <form method="post" action="">
            <input type="hidden" name="page_options" value="helfjmm_options" />
            <?php wp_nonce_field('update', 'helfjmm_update_options'); ?>

            <p><?php _e('Select a membership type and a default role.', 'join-my-multisite'); ?></p>
            
            <table class="form-table">
                <tbody>
                    <tr valign="top">
                        <th scope="row"><?php _e('Membership:', 'join-my-multisite'); ?></th>
                        <td><p>
                            <input type="radio" name="jmm_type" value="1" <?php if ($jmm_options['type'] == 1) echo 'checked="checked"'; ?>> <label for="jmm-type"><strong><?php _e('Automatic', 'join-my-multisite'); ?></strong> </label><br />
                            <input type="radio" name="jmm_type" value="2" <?php if ($jmm_options['type'] == 2) echo 'checked="checked"'; ?>> <label for="jmm-type"><strong><?php _e('Manual', 'join-my-multisite'); ?></strong> </label><br />
                            <input type="radio" name="jmm_type" value="3" <?php if ($jmm_options['type'] == 3) echo 'checked="checked"'; ?>> <label for="jmm-type"><strong><?php _e('None', 'join-my-multisite'); ?></strong></label>
                        </p></td>
                        <td><p class="description">
                        <?php _e('Auto-Add signed in users to this site when they visit.', 'join-my-multisite'); ?><br />
                        <?php _e('Allow signed in users to join via a widget or the shortcode <code>[join-this-site]</code>.', 'join-my-multisite'); ?><br />            
                        <?php _e('Don\'t allow new users to add themselves this site, add them manually.', 'join-my-multisite'); ?>
                        </p>
                        <?php if ( get_option('users_can_register') == 1 && $jmm_options['type'] == 2 ) {
	                        ?><p><?php _e('To allow users to register via a button on any page or post on your site, use the shortcode <code>[join-this-site]</code> - Simply insert it and you\'re good!', 'join-my-multisite'); ?></p><?php
                        } ?>
                        </td>
                    </tr>
                    
                    <?php
                    // Registration Options:
                    
                    if ( get_option('users_can_register') == 1 ):
                    ?>
                    
                    <tr valign="top">
                        <th scope="row"><?php _e('Registration:', 'join-my-multisite'); ?></th>
                        <td><p>
                            <input type="checkbox" name="jmm_persite" value="1" <?php if ($jmm_options['persite'] == 1) echo 'checked="checked"'; ?>> <label for="jmm-persite"><?php _e('Per-Site', 'join-my-multisite'); ?></label>
                        </p></td>
                        <td><p class="description"><?php _e('Check this box if you want to use a shortcode to customize per-site registration. If unchecked, registrations will be sent to the network registration page.', 'join-my-multisite'); ?></p></td>
                    </tr>
                    <?php if ($jmm_options['persite'] == 1) { 
                            $all_pages = get_pages();
                    ?>   
                    <tr valign="top">
                        <th scope="row"></th>
                        <td>
                        
                        <?php if ( !isset($jmm_options['perpage'] ) || $jmm_options['perpage'] == 0 ) { ?> 
                        	<div id="message" class="error"><p><strong><?php _e('Join My Multisite needs your attention:', 'join-my-multisite'); ?></strong> <?php _e('You\'ve selected custom registration but have not selected a page to use. No one will be able to register for your site until you fix this.', 'join-my-multisite'); ?></p></div>
                        <?php } ?>
                        
                        <p><select name="jmm_perpage" id='jmm_options[perpage]'>
                            <option value="0"><?php _e( '&mdash; Select &mdash;' ); ?></option>
                            <?php echo walk_page_dropdown_tree( $all_pages, 0, array( 'depth' => 1,'selected' => $jmm_options['perpage'] ) ); ?>
                        </select></p>
                        </td>
                        
                        <td><p class="description"><?php _e('Users who are not logged in will be redirected to the perpage you select from the dropdowns. Only top-level pages may be used. Use the following shortcode to display the login form:', 'join-my-multisite'); ?><br />
                            <code>[join-my-multisite]</code>
                        </td>
                    </tr>
                    <?php } 
                    
                    endif; // End check for if registration is on for the network.
                    ?>
                    <tr> 
                        <th scope="row"><?php _e('New User Default Role:', 'join-my-multisite'); ?></th>
                        <td>
                        <select name="jmm_role" id="<?php echo $jmm_options['role']; ?>">
                        <?php wp_dropdown_roles( get_option( 'default_role' ) ); ?>
                        </select>
                        </td>
                    </tr>
    
            </tbody>
            </table>
            
            <p class="submit"><input class='button-primary' type='Submit' name='update' value='<?php _e("Update Options", 'join-my-multisite'); ?>' id='submitbutton' /></p>
    
        </form>