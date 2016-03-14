<?php

/*----------------------------------------------
    Plugin Settings
    -----------------------------------------------*/

// Exit if accessed directly
    if( !defined( 'ABSPATH' ) ) {
     exit;
   }

   ?>

   <div class="wrap">
    <div class="saboxplugin">
      <div id="poststuff">
        <div id="postbox-container" class="postbox-container">
          <div class="meta-box-sortables ui-sortable" id="normal-sortables">
           <h2 class="saboxplugin-icon"><?php echo esc_html( get_admin_page_title() ); ?></h2>

           <div class="welcome-panel">
            <div class="welcome-panel-content">

             <form method="POST" action="options.php">
               <?php settings_fields( 'sabox_plugin' );
               do_settings_sections( 'saboxplugin_options' );
               $options = get_option( 'saboxplugin_options' );
               ?>

               <div class="postbox" id="test1">
                <div title="Click to toggle" class="handlediv"><br></div><h3 class="hndle"><span><?php _e( 'General options', 'saboxplugin' ); ?></span></h3>
                <div class="inside">
                 <div class="saboxplugin-question">
                  <label for="saboxplugin_options[sab_autoinsert]"><?php _e( 'Manually insert the Simple Author Box:', 'saboxplugin' ); ?></label>
                </div>

                <div class="saboxplugin-switch">
                  <input id="sab-toggle-1" class="sab-toggle sab-toggle-yes-no" type="checkbox" name="saboxplugin_options[sab_autoinsert]" value="1" <?php checked( 1, isset( $options['sab_autoinsert'] ) ); ?> />
                  <label for="sab-toggle-1" data-on=<?php _e( 'Yes', 'saboxplugin' ); ?> data-off=<?php _e( 'No', 'saboxplugin' ); ?>></label>
                </div>

                <div id="saboxplugin-hide">

                  <p class="description">
                    <?php _e( 'If you want to manually insert the Simple Author Box in your template file (single post view), you can use the following code snippet: ', 'saboxplugin' ); ?>
                  </p>
                  <textarea name="saboxplugin" rows="1" cols="100" onclick="this.focus();this.select()" readonly="readonly">&lt;?php if ( function_exists( 'wpsabox_author_box' ) ) echo wpsabox_author_box(); ?&gt;</textarea>

                  <?php
                  if ( get_option( 'sab_box_margin_top' ) ) {
                    $sabox_top_margin = get_option( 'sab_box_margin_top' );
                  } else {
                    $sabox_top_margin = 0;
                  }
                  ?>

                  <script type='text/javascript'>
                  var saboxTopmargin = '<?php echo $sabox_top_margin; ?>';
                  </script>

                  <div class="saboxplugin-border"></div>

                  <div class="sabox-inline-slide">

                    <div class="saboxplugin-question">
                      <label for="saboxplugin_options[sab_box_margin_top]"><?php _e( 'Top margin of author box:', 'saboxplugin' ); ?><input type="text" class="sabox-amount" id="sabox-amount" /></label>
                    </div>

                    <div class="sabox-slider" id="sabox-slider"><p></p></div>
                    <input type="hidden" name="sab_box_margin_top" id="sab_box_margin_top" value="<?php echo $sabox_top_margin; ?>" />

                  </div>
                  <div class="saboxplugin-border"></div>

                  <?php
                  if ( get_option( 'sab_box_margin_bottom' ) ) {
                    $sabox_bottom_margin = get_option( 'sab_box_margin_bottom' );
                  } else {
                    $sabox_bottom_margin = 0;
                  }
                  ?>

                  <script type='text/javascript'>
                  var saboxBottommargin = '<?php echo $sabox_bottom_margin; ?>';
                  </script>


                  <div class="sabox-inline-slide">

                    <div class="saboxplugin-question">
                      <label for="saboxplugin_options[sab_box_margin_bottom]"><?php _e( 'Bottom margin of author box:', 'saboxplugin' ); ?><input type="text" class="sabox-amount2" id="sabox-amount2" /></label>
                    </div>

                    <div class="sabox-slider2" id="sabox-slider2"><p></p></div>
                    <input type="hidden" name="sab_box_margin_bottom" id="sab_box_margin_bottom" value="<?php echo $sabox_bottom_margin; ?>" />

                  </div>
                  <div class="saboxplugin-border"></div>

                </div><!-- end of saboxplugin-hide -->

                <div class="saboxplugin-question">
                  <label for="saboxplugin_options[sab_no_description]"><?php _e( 'Hide the author box if author description is empty:', 'saboxplugin' ); ?></label>
                </div>

                <div class="saboxplugin-switch">
                  <input id="sab-toggle-4" class="sab-toggle sab-toggle-yes-no" type="checkbox" name="saboxplugin_options[sab_no_description]" value="1" <?php checked( 1, isset( $options['sab_no_description'] ) ); ?> />
                  <label for="sab-toggle-4" data-on=<?php _e( 'Yes', 'saboxplugin' ); ?> data-off=<?php _e( 'No', 'saboxplugin' ); ?>></label>
                </div>

                <div class="saboxplugin-question">
                  <label for="saboxplugin_options[sab_avatar_style]"><?php _e( 'Author avatar image style:', 'saboxplugin' ); ?></label>
                </div>

                <div class="saboxplugin-switch">
                  <input id="sab-toggle-8" class="sab-toggle sab-toggle-yes-no" type="checkbox" name="saboxplugin_options[sab_avatar_style]" value="1" <?php checked( 1, isset( $options['sab_avatar_style'] ) ); ?> />
                  <label for="sab-toggle-8" data-on=<?php _e( 'Circle', 'saboxplugin' ); ?> data-off=<?php _e( 'Square', 'saboxplugin' ); ?>></label>
                </div>

                <div class="saboxplugin-question">
                  <label for="saboxplugin_options[sab_avatar_hover]"><?php _e( 'Rotate effect on author avatar hover:', 'saboxplugin' ); ?></label>
                </div>

                <div class="saboxplugin-switch">
                  <input id="sab-toggle-12" class="sab-toggle sab-toggle-yes-no" type="checkbox" name="saboxplugin_options[sab_avatar_hover]" value="1" <?php checked( 1, isset( $options['sab_avatar_hover'] ) ); ?> />
                  <label for="sab-toggle-12" data-on=<?php _e( 'Yes', 'saboxplugin' ); ?> data-off=<?php _e( 'No', 'saboxplugin' ); ?>></label>
                </div>

                <div class="saboxplugin-border"></div>


              <div class="saboxplugin-question">
                  <label for="saboxplugin_options[sab_web]"><?php _e( 'Show author website:', 'saboxplugin' ); ?></label>
                </div>

                <div class="saboxplugin-switch">
                  <input id="sab-toggle-15" class="sab-toggle sab-toggle-yes-no" type="checkbox" name="saboxplugin_options[sab_web]" value="1" <?php checked( 1, isset( $options['sab_web'] ) ); ?> />
                  <label for="sab-toggle-15" data-on=<?php _e( 'Yes', 'saboxplugin' ); ?> data-off=<?php _e( 'No', 'saboxplugin' ); ?>></label>
                </div>


              <div id="saboxplugin-hide-three">

              <div class="saboxplugin-question">
                  <label for="saboxplugin_options[sab_web_position]"><?php _e( 'Author website position:', 'saboxplugin' ); ?></label>
                </div>

                <div class="saboxplugin-switch">
                  <input id="sab-toggle-16" class="sab-toggle sab-toggle-yes-no" type="checkbox" name="saboxplugin_options[sab_web_position]" value="1" <?php checked( 1, isset( $options['sab_web_position'] ) ); ?> />
                  <label for="sab-toggle-16" data-on=<?php _e( 'Right', 'saboxplugin' ); ?> data-off=<?php _e( 'Left', 'saboxplugin' ); ?>></label>
                </div>


              <div class="saboxplugin-question">
                  <label for="saboxplugin_options[sab_web_target]"><?php _e( 'Open author website link in a new tab:', 'saboxplugin' ); ?></label>
                </div>

                <div class="saboxplugin-switch">
                  <input id="sab-toggle-17" class="sab-toggle sab-toggle-yes-no" type="checkbox" name="saboxplugin_options[sab_web_target]" value="1" <?php checked( 1, isset( $options['sab_web_target'] ) ); ?> />
                  <label for="sab-toggle-17" data-on=<?php _e( 'Yes', 'saboxplugin' ); ?> data-off=<?php _e( 'No', 'saboxplugin' ); ?>></label>
                </div>


              <div class="saboxplugin-question">
                  <label for="saboxplugin_options[sab_web_rel]"><?php _e( 'Add "nofollow" attribute on author website link:', 'saboxplugin' ); ?></label>
                </div>

                <div class="saboxplugin-switch">
                  <input id="sab-toggle-18" class="sab-toggle sab-toggle-yes-no" type="checkbox" name="saboxplugin_options[sab_web_rel]" value="1" <?php checked( 1, isset( $options['sab_web_rel'] ) ); ?> />
                  <label for="sab-toggle-18" data-on=<?php _e( 'Yes', 'saboxplugin' ); ?> data-off=<?php _e( 'No', 'saboxplugin' ); ?>></label>
                </div>

                <div class="saboxplugin-border"></div>
                </div> <!-- end hide three -->

                <div class="saboxplugin-question">
                  <label for="saboxplugin_options[sab_colored]"><?php _e( 'Social icons type (colored background or symbols only):', 'saboxplugin' ); ?></label>
                </div>


                <div class="saboxplugin-switch">
                  <input id="sab-toggle-3" class="sab-toggle sab-toggle-yes-no" type="checkbox" name="saboxplugin_options[sab_colored]" value="1" <?php checked( 1, isset( $options['sab_colored'] ) ); ?> />
                  <label for="sab-toggle-3" data-on=<?php _e( 'Colored', 'saboxplugin' ); ?> data-off=<?php _e( 'Symbols', 'saboxplugin' ); ?>></label>
                </div>


                <div id="saboxplugin-hide-two">

                  <div class="saboxplugin-question">
                    <label for="saboxplugin_options[sab_icons_style]"><?php _e( 'Social icons style:', 'saboxplugin' ); ?></label>
                  </div>

                  <div class="saboxplugin-switch">
                    <input id="sab-toggle-9" class="sab-toggle sab-toggle-yes-no" type="checkbox" name="saboxplugin_options[sab_icons_style]" value="1" <?php checked( 1, isset( $options['sab_icons_style'] ) ); ?> />
                    <label for="sab-toggle-9" data-on=<?php _e( 'Circle', 'saboxplugin' ); ?> data-off=<?php _e( 'Squares', 'saboxplugin' ); ?>></label>
                  </div>

                <div class="saboxplugin-question">
                  <label for="saboxplugin_options[sab_social_hover]"><?php _e( 'Rotate effect on social icons hover:', 'saboxplugin' ); ?></label>
                </div>

                <div class="saboxplugin-switch">
                  <input id="sab-toggle-13" class="sab-toggle sab-toggle-yes-no" type="checkbox" name="saboxplugin_options[sab_social_hover]" value="1" <?php checked( 1, isset( $options['sab_social_hover'] ) ); ?> />
                  <label for="sab-toggle-13" data-on=<?php _e( 'Yes', 'saboxplugin' ); ?> data-off=<?php _e( 'No', 'saboxplugin' ); ?>></label>
                </div>

                  <div class="saboxplugin-question">
                  <label for="saboxplugin_options[sab_box_long_shadow]"><?php _e( 'Use flat long shadow effect:', 'saboxplugin' ); ?></label>
                </div>

                <div class="saboxplugin-switch">
                  <input id="sab-toggle-10" class="sab-toggle sab-toggle-yes-no" type="checkbox" name="saboxplugin_options[sab_box_long_shadow]" value="1" <?php checked( 1, isset( $options['sab_box_long_shadow'] ) ); ?> />
                  <label for="sab-toggle-10" data-on=<?php _e( 'Yes', 'saboxplugin' ); ?> data-off=<?php _e( 'No', 'saboxplugin' ); ?>></label>
                </div>

                <div class="saboxplugin-question">
                  <label for="saboxplugin_options[sab_box_thin_border]"><?php _e( 'Show a thin border on colored social icons:', 'saboxplugin' ); ?></label>
                </div>

                <div class="saboxplugin-switch">
                  <input id="sab-toggle-11" class="sab-toggle sab-toggle-yes-no" type="checkbox" name="saboxplugin_options[sab_box_thin_border]" value="1" <?php checked( 1, isset( $options['sab_box_thin_border'] ) ); ?> />
                  <label for="sab-toggle-11" data-on=<?php _e( 'Yes', 'saboxplugin' ); ?> data-off=<?php _e( 'No', 'saboxplugin' ); ?>></label>
                </div>


                </div><!-- end of saboxplugin-hide -->


                <div class="saboxplugin-question">
                  <label for="saboxplugin_options[sab_link_target]"><?php _e( 'Open social icon links in a new tab:', 'saboxplugin' ); ?></label>
                </div>

                <div class="saboxplugin-switch">
                  <input id="sab-toggle-2" class="sab-toggle sab-toggle-yes-no" type="checkbox" name="saboxplugin_options[sab_link_target]" value="1" <?php checked( 1, isset( $options['sab_link_target'] ) ); ?> />
                  <label for="sab-toggle-2" data-on=<?php _e( 'Yes', 'saboxplugin' ); ?> data-off=<?php _e( 'No', 'saboxplugin' ); ?>></label>
                </div>


                <div class="saboxplugin-question">
                  <label for="saboxplugin_options[sab_hide_socials]"><?php _e( 'Hide the social icons on author box:', 'saboxplugin' ); ?></label>
                </div>

                <div class="saboxplugin-switch">
                  <input id="sab-toggle-7" class="sab-toggle sab-toggle-yes-no" type="checkbox" name="saboxplugin_options[sab_hide_socials]" value="1" <?php checked( 1, isset( $options['sab_hide_socials'] ) ); ?> />
                  <label for="sab-toggle-7" data-on=<?php _e( 'Yes', 'saboxplugin' ); ?> data-off=<?php _e( 'No', 'saboxplugin' ); ?>></label>
                </div>
              </div>
            </div>

            <div style="display: block;" class="postbox closed" id="test1">
              <div title="Click to toggle" class="handlediv"><br></div><h3 class="hndle"><span><?php _e( 'Color options', 'saboxplugin' ); ?></span></h3>
              <div class="inside">

                <div class="saboxplugin-question">
                  <label for="saboxplugin_options[sab_box_author_color]"><?php _e( 'Author name color:', 'saboxplugin' ); ?></label>
                </div>

                <div class="saboxplugin-switch">
                  <?php $sab_author_color = ( isset( $options['sab_box_author_color'] ) ) ? $options['sab_box_author_color'] : ''; ?>
                  <input type="text" name="saboxplugin_options[sab_box_author_color]" id="saboxplugin_options[sab_box_author_color]" class="saboxplugin-color-picker" value="<?php echo $sab_author_color; ?>" />
                </div>

                <div class="saboxplugin-question">
                  <label for="saboxplugin_options[sab_box_web_color]"><?php _e( 'Author website link color:', 'saboxplugin' ); ?></label>
                </div>
                <div class="saboxplugin-switch">
                  <?php $sab_web_color = ( isset( $options['sab_box_web_color'] ) ) ? $options['sab_box_web_color'] : ''; ?>
                  <input type="text" name="saboxplugin_options[sab_box_web_color]" id="saboxplugin_options[sab_box_web_color]" class="saboxplugin-color-picker" value="<?php echo $sab_web_color; ?>" />
                </div>


                <div class="saboxplugin-question">
                  <label for="saboxplugin_options[sab_box_border]"><?php _e( 'Border color of Simple Author Box:', 'saboxplugin' ); ?></label>
                </div>
                <div class="saboxplugin-switch">
                  <?php $sab_border_color = ( isset( $options['sab_box_border'] ) ) ? $options['sab_box_border'] : ''; ?>
                  <input type="text" name="saboxplugin_options[sab_box_border]" id="saboxplugin_options[sab_box_border]" class="saboxplugin-color-picker" value="<?php echo $sab_border_color; ?>" />
                </div>

                <div class="saboxplugin-question">
                  <label for="saboxplugin_options[sab_box_icons_back]"><?php _e( 'Background color of social icons bar:', 'saboxplugin' ); ?></label>
                </div>

                <div class="saboxplugin-switch">
                  <?php $sab_icons_back = ( isset( $options['sab_box_icons_back'] ) ) ? $options['sab_box_icons_back'] : ''; ?>
                  <input type="text" name="saboxplugin_options[sab_box_icons_back]" id="saboxplugin_options[sab_box_icons_back]" class="saboxplugin-color-picker" value="<?php echo $sab_icons_back; ?>" />
                </div>

                <div class="saboxplugin-question">
                  <label for="saboxplugin_options[sab_box_icons_color]"><?php _e( 'Social icons color (for symbols only):', 'saboxplugin' ); ?></label>
                </div>

                <div class="saboxplugin-switch">
                  <?php $sab_icons_color = ( isset( $options['sab_box_icons_color'] ) ) ? $options['sab_box_icons_color'] : ''; ?>
                  <input type="text" name="saboxplugin_options[sab_box_icons_color]" id="saboxplugin_options[sab_box_icons_color]" class="saboxplugin-color-picker" value="<?php echo $sab_icons_color; ?>" />
                </div>

              </div>
            </div>


            <div style="display: block;" class="postbox closed" id="test3">
              <div title="Click to toggle" class="handlediv"><br></div><h3 class="hndle"><span><?php _e( 'Typography options', 'saboxplugin' ); ?></span></h3>
              <div class="inside">


                <div class="saboxplugin-question">
                  <label for="sab_box_subset"><?php _e( 'Google font characters subset:', 'saboxplugin' ); ?></label>
                  <p class="description"><?php _e( 'Please note that some Google fonts does not support particular subsets!', 'saboxplugin' ); ?></p>
                </div>
                <div class="saboxplugin-switch">
                  <select name="sab_box_subset" id="sab_box_subset" class="sab_box_subset">
                    <?php

                      // Listing Google fonts subsets from the array.
                    foreach ($sabox_google_subset as $sabox_subset) {
                      echo '<option value="' . $sabox_subset . '"' . selected ($sabox_subset, get_option('sab_box_subset')) . '>' . $sabox_subset . '</option>' . "\n";
                    }

                    ?>
                  </select>
                </div>

              <div class="saboxplugin-border"></div>

                <div class="saboxplugin-question">
                  <label for="sab_box_name_font"><?php _e( 'Author name font family:', 'saboxplugin' ); ?></label>
                </div>


                <div class="saboxplugin-switch">
                  <select name="sab_box_name_font" id="sab_box_name_font" class="sab_box_name_font">
                    <option value="none"><?php _e('None', 'saboxplugin'); ?></option>
                    <?php

                      // Listing Google fonts from the array.
                    foreach ($sabox_google_fonts as $sabox_font) {
                      echo '<option value="' . $sabox_font . '"' . selected ($sabox_font, get_option('sab_box_name_font')) . '>' . $sabox_font . '</option>' . "\n";
                    }

                    ?>
                  </select>

                </div>


                <div class="saboxplugin-border"></div>

                <div class="saboxplugin-question">
                  <label for="sab_box_web_font"><?php _e( 'Author website font family:', 'saboxplugin' ); ?></label>
                </div>

                <div class="saboxplugin-switch">
                  <select name="sab_box_web_font" id="sab_box_web_font" class="sab_box_web_font">
                    <option value="none"><?php _e('None', 'saboxplugin'); ?></option>
                    <?php

                      // Listing Google fonts from the array.
                    foreach ($sabox_google_fonts as $sabox_font) {
                      echo '<option value="' . $sabox_font . '"' . selected ($sabox_font, get_option('sab_box_web_font')) . '>' . $sabox_font . '</option>' . "\n";
                    }

                    ?>
                  </select>

                </div>

              <div class="saboxplugin-border"></div>



               <div class="saboxplugin-question">
                  <label for="sab_box_desc_font"><?php _e( 'Author description font family:', 'saboxplugin' ); ?></label>
                </div>


                <div class="saboxplugin-switch">
                  <select name="sab_box_desc_font" id="sab_box_name_font" class="sab_box_desc_font">
                    <option value="none"><?php _e('None', 'saboxplugin'); ?></option>
                    <?php

                      // Listing Google fonts from the array.
                    foreach ($sabox_google_fonts as $sabox_font) {
                      echo '<option value="' . $sabox_font . '"' . selected ($sabox_font, get_option('sab_box_desc_font')) . '>' . $sabox_font . '</option>' . "\n";
                    }

                    ?>
                  </select>

                </div>


              <div class="saboxplugin-border"></div>


               <?php
               if ( get_option( 'sab_box_name_size' ) ) {
                $sabox_name_size = get_option( 'sab_box_name_size' );
              } else {
                $sabox_name_size = 18;
              }
              ?>

              <script type='text/javascript'>
              var saboxNamesize = '<?php echo $sabox_name_size; ?>';
              </script>


              <div class="sabox-inline-slide">

                <div class="saboxplugin-question">
                  <label for="saboxplugin_options[sab_box_name_size]"><?php _e( 'Author name font size:', 'saboxplugin' ); ?><input type="text" class="sabox-amount4" id="sabox-amount4" /></label>
                </div>

                <div class="sabox-slider4" id="sabox-slider4"><p></p></div>
                <input type="hidden" name="sab_box_name_size" id="sab_box_name_size" value="<?php echo $sabox_name_size; ?>" />
                <p class="description"><?php _e( 'Default font size of author name is 18px.', 'saboxplugin' ); ?></p>
              </div>





         <div class="saboxplugin-border"></div>


               <?php
               if ( get_option( 'sab_box_web_size' ) ) {
                $sabox_web_size = get_option( 'sab_box_web_size' );
              } else {
                $sabox_web_size = 14;
              }
              ?>

              <script type='text/javascript'>
              var saboxWebsize = '<?php echo $sabox_web_size; ?>';
              </script>


              <div class="sabox-inline-slide">

                <div class="saboxplugin-question">
                  <label for="saboxplugin_options[sab_box_web_size]"><?php _e( 'Author website font size:', 'saboxplugin' ); ?><input type="text" class="sabox-amount6" id="sabox-amount6" /></label>
                </div>

                <div class="sabox-slider6" id="sabox-slider6"><p></p></div>
                <input type="hidden" name="sab_box_web_size" id="sab_box_web_size" value="<?php echo $sabox_web_size; ?>" />
                <p class="description"><?php _e( 'Default font size of author website is 14px.', 'saboxplugin' ); ?></p>
              </div>

              <div class="saboxplugin-border"></div>






              <?php
               if ( get_option( 'sab_box_desc_size' ) ) {
                $sabox_desc_size = get_option( 'sab_box_desc_size' );
              } else {
                $sabox_desc_size = 14;
              }
              ?>

              <script type='text/javascript'>
              var saboxDescsize = '<?php echo $sabox_desc_size; ?>';
              </script>


              <div class="sabox-inline-slide">

                <div class="saboxplugin-question">
                  <label for="saboxplugin_options[sab_box_desc_size]"><?php _e( 'Author description font size:', 'saboxplugin' ); ?><input type="text" class="sabox-amount5" id="sabox-amount5" /></label>
                </div>

                <div class="sabox-slider5" id="sabox-slider5"><p></p></div>
                <input type="hidden" name="sab_box_desc_size" id="sab_box_desc_size" value="<?php echo $sabox_desc_size; ?>" />
                <p class="description"><?php _e( 'Default font size of author description is 14px.', 'saboxplugin' ); ?></p>
              </div>

              <div class="saboxplugin-border"></div>

              <?php
              if ( get_option( 'sab_box_icon_size' ) ) {
                $sabox_icons_size = get_option( 'sab_box_icon_size' );
              } else {
                $sabox_icons_size = 18;
              }
              ?>

              <script type='text/javascript'>
              var saboxIconsize = '<?php echo $sabox_icons_size; ?>';
              </script>


              <div class="sabox-inline-slide">

                <div class="saboxplugin-question">
                  <label for="saboxplugin_options[sab_box_icon_size]"><?php _e( 'Size of social icons:', 'saboxplugin' ); ?><input type="text" class="sabox-amount3" id="sabox-amount3" /></label>
                </div>

                <div class="sabox-slider3" id="sabox-slider3"><p></p></div>

                <input type="hidden" name="sab_box_icon_size" id="sab_box_icon_size" value="<?php echo $sabox_icons_size; ?>" />

                <p class="description"><?php _e( 'Default font size of social icons is 18px.', 'saboxplugin' ); ?></p>

              </div>


              <div class="saboxplugin-border"></div>

              <div class="saboxplugin-question">
                <label for="saboxplugin_options[sab_desc_style]"><?php _e( 'Author description font style:', 'saboxplugin' ); ?></label>
                </div>

                    <div class="saboxplugin-switch">
                <input id="sab-toggle-14" class="sab-toggle sab-toggle-yes-no" type="checkbox" name="saboxplugin_options[sab_desc_style]" value="1" <?php checked( 1, isset( $options['sab_desc_style'] ) ); ?> />
                <label for="sab-toggle-14" data-on=<?php _e( 'Italic', 'saboxplugin' ); ?> data-off=<?php _e( 'Normal', 'saboxplugin' ); ?>></label>
              </div>


            </div>
          </div>

          <div style="display: block;" class="postbox closed" id="test4">
            <div title="Click to toggle" class="handlediv"><br></div><h3 class="hndle"><span><?php _e( 'Miscellaneous options', 'saboxplugin' ); ?></span></h3>
            <div class="inside">
              <div class="saboxplugin-question">
                <label for="saboxplugin_options[sab_load_fa]"><?php _e( 'Disable Font Awesome stylesheet:', 'saboxplugin' ); ?></label>
                <p class="description"><?php _e( 'Switch to "Yes" to prevent Font Awesome from loading its stylesheet, ONLY if your theme or another plugin already does.', 'saboxplugin' ); ?></p>
              </div>

              <div class="saboxplugin-switch">
                <input id="sab-toggle-6" class="sab-toggle sab-toggle-yes-no" type="checkbox" name="saboxplugin_options[sab_load_fa]" value="1" <?php checked( 1, isset( $options['sab_load_fa'] ) ); ?> />
                <label for="sab-toggle-6" data-on=<?php _e( 'Yes', 'saboxplugin' ); ?> data-off=<?php _e( 'No', 'saboxplugin' ); ?>></label>
              </div>

              <div class="saboxplugin-border"></div>

              <div class="saboxplugin-question">
                <label for="saboxplugin_options[sab_footer_inline_style]"><?php _e( 'Load generated inline style to footer:', 'saboxplugin' ); ?></label>
                <p class="description"><?php _e( 'This option is useful ONLY if you run a plugin that optimizes your CSS delivery or moves your stylesheets to the footer, to get a higher score on speed testing services. However, the plugin style is loaded only on single post and single page.', 'saboxplugin' ); ?></p>
              </div>

              <div class="saboxplugin-switch">
                <input id="sab-toggle-5" class="sab-toggle sab-toggle-yes-no" type="checkbox" name="saboxplugin_options[sab_footer_inline_style]" value="1" <?php checked( 1, isset( $options['sab_footer_inline_style'] ) ); ?> />
                <label for="sab-toggle-5" data-on=<?php _e( 'Yes', 'saboxplugin' ); ?> data-off=<?php _e( 'No', 'saboxplugin' ); ?>></label>
              </div>

            </div>
          </div>




          <!-- <p class="submit"> -->
           <?php submit_button(); ?>
           <!-- <input type="submit" name="submit" id="sabox-submit" class="button button-primary" value="Save Changes"  /> -->
           <!-- <input type="submit" name="submit" id="sabox-reset" class="button button-secondary" value="Reset to Default"  /> -->
        <!--  </p> -->

       </form>
     </div>
   </div>
 </div>
</div>
</div>

<div class="clearfix"></div>
<div class="sab-box"><!-- start sab-box div-->

<div class="sab-box-version">
<i class="sab-icon-version"></i>
</div>

<div class="sab-infos">
<?php _e( 'Installed Version:', 'saboxplugin' ); ?>
<span>
<?php echo SIMPLE_AUTHOR_BOX_VERSION; ?>
</span>
</div>

<div class="sab-infos">
<?php _e( 'Last Update:', 'saboxplugin' ); ?>
<span>
<?php echo SIMPLE_AUTHOR_BOX_LAST_UPDATE; ?>
</span>
</div>

</div> <!-- end sab-box div -->


</div>
</div>