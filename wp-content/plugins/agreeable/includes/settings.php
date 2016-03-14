<?php 
	    if(isset($_POST['ag_hidden']) && $_POST['ag_hidden'] == 'Y') {
	    
          $this->update_options();
          
?> 
		
			<div class="updated"><p><strong><?php _e('Options saved.' ); ?></strong></p></div>  
			
			<?php
		}
		
	?>

<?php $pages = get_pages('status=publish&numberposts=1000&posts_per_page=-1'); ?>

<div class="wrap agreeable-settings">
			<div class="ag-plugin-banner">
				<img src="<?php echo esc_url( plugins_url('../images/banner.png', __FILE__) ); ?>" alt="Agreeable" />
			</div>
			<div class="kp-cross-promote-area">
				<?php $this->cross_promotions('agreeable'); ?>
			</div>
			
			
			<form id="ag-form" name="ag_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
				
				<input type="hidden" name="ag_hidden" value="Y">
				
				<?php wp_nonce_field( 'ag_settings_page' ); ?>
				
				<h3><?php _e( 'General Settings', 'agreeable' ); ?></h3>
				
				<p><label for="ag_fail"><?php _e("Failed to agree error message: ", 'agreeable' ); ?></label><input type="text" name="ag_fail" value="<?php echo esc_attr($this->options['fail_text']); ?>" size="20"></br><span class='mes'><?php _e("This is what shows up if they don't check the box", 'agreeable' ); ?></span></p>
				
				<p>
					<label for="ag_url"><?php _e("Select your terms page", 'agreeable'); ?></label>
					<select name="ag_url">
						<?php foreach ($pages as $p) { ?>
							<option value="<?php echo esc_attr( $p->ID ); ?>" <?php echo $this->options['terms_page'] == $p->ID ? 'selected="selected"' : ''; ?>><?php echo esc_attr($p->post_title); ?></option>
						<?php } ?>
					</select>
					<br><span class='mes'><?php _e("Create a page for your terms and conditions and select it here.", 'agreeable' ); ?></span>
				</p>
				
				<p><label for="ag_termm"><?php _e("Message: ", 'agreeable' ); ?></label><input type="text"  name="ag_termm" size="40" value="<?php echo esc_attr($this->options['message']); ?>"><br><span class='mes'><?php _e("This is the text that goes right after the checkbox", 'agreeable' ); ?></span></p>
				
				
				<p class="ag-checkboxes">
					<input type="checkbox" id="ag_remember" name="ag_remember" value="1" <?php if($this->options['remember_me'] == 1) {echo 'checked';} ?> />
					<label for="ag_remember"><?php _e("Remember agreement for 30 days", 'agreeable'); ?></label>
				</p>
				
				<div class="ag-color-options ag-checkboxes">
					<h4><?php _e("Lightbox Options", 'agreeable'); ?></h4>
					<p class="ag-checkboxes">
						<input type="checkbox" id="ag_lightbox" name="ag_lightbox" value="1" <?php if($this->options['lightbox'] == 1) {echo 'checked';} ?> />
						<label for="ag_lightbox"><?php _e("Active?", 'agreeable'); ?></label>
						
						<br><span class='mes'><?php _e("If checked, the terms will pop up in a responsive lightbox.  If unchecked the message will link to your terms page.", 'agreeable' ); ?></span></p>
					</p>
					
					<input type="color" name="ag_text_color" id="ag_text_color" value="<?php echo esc_attr($this->options['colors']['text-color']); ?>"/>
					<label for="ag_text_color"><?php _e("Text color", 'agreeable'); ?></label>
					<br><br>
					
					<input type="color" name="ag_bg_color" id="ag_bg_color" value="<?php echo esc_attr($this->options['colors']['bg-color']); ?>" />
					<label for="ag_bg_color"><?php _e("Background color", 'agreeable'); ?></label>
				</div>	

				<div class="ag-checkboxes">
								
				<h4><?php _e("Where should it be displayed? ", 'agreeable' ); ?></h4>
					<p>
						<input type="checkbox" id="ag_login" name="ag_login" value="1" <?php if($this->options['login'] == 1) {echo 'checked';} ?> /> <label for="ag_login"> <?php _e("Login form", 'agreeable'); ?></label><br>
						<input type="checkbox" id="ag_register" name="ag_register" value="1" <?php if($this->options['register'] == 1) {echo 'checked';} ?> /> <label for="ag_register"><?php _e("Registration form", 'agreeable'); ?></label>
						<br>
						<input type="checkbox" id="ag_comments" name="ag_comments" value="1" <?php if($this->options['comments'] == 1) {echo 'checked';} ?> /> <label for="ag_comments"><?php _e("Comment form", 'agreeable'); ?></label>
					</p>
				</div>	
				
				<?php do_action('agreeable_settings'); ?>
							
			
				<p class="submit">
				<input type="submit" class="button button-large button-primary" name="Submit" value="<?php _e('Update Options', 'agreeable' ) ?>" />
				</p>
			</form>

		</div>
	