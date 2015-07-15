<div class="wrap">

	<div id="icon-options-general" class="icon32"></div>
	<h2><?php esc_attr_e( 'Global Menu Options', 'glocal-global-menu' ); ?></h2>

	<div id="poststuff">

		<div id="post-body" class="metabox-holder columns-2">

			<!-- main content -->
			<div id="post-body-content">

				<div class="meta-box-sortables ui-sortable">

					<div class="postbox">

						<h3><span><?php esc_attr_e( 'Select the Menu to Use as Global Menu', 'glocal-global-menu' ); ?></span></h3>

						<div class="inside">

							<form name="anp-global-menu-form" method="post" action="">

								<input type="hidden" name="anp_global_menu_form_submitted" value="Y" />

								<?php $nav_menus = get_terms( 'nav_menu', array( 'hide_empty' => true ) ); ?>

								<select name="anp_global_menu_selected" id="anp-global-menu">

									<option value='' <?php selected( $anp_global_menu_selected, '' ); ?>>--Select a Menu--</option>

									<?php foreach( $nav_menus as $menu) { ?>

										<option value="<?php echo $menu->slug; ?>" <?php selected( $anp_global_menu_selected, $menu->slug ); ?>><?php echo $menu->name; ?></option>
									
									<?php } ?>

									<?php if( !isset( $anp_global_menu_selected ) || $anp_global_menu_selected == '' ) { 
										$button_text = 'Save';
									} else {
										$button_text = 'Update';
									} ?>
								
								</select>

								<?php submit_button( $button_text, $type = 'primary', $name = 'anp-global-menu-submit', $wrap = FALSE, $other_attributes = NULL ); ?>

							</form>

						</div>
						<!-- .inside -->

					</div>
					<!-- .postbox -->

				</div>
				<!-- .meta-box-sortables .ui-sortable -->

			</div>
			<!-- post-body-content -->

			<!-- sidebar -->
			<div id="postbox-container-1" class="postbox-container">

				<div class="meta-box-sortables">

					<div class="postbox">

						<h3><span><?php esc_attr_e(
									'Plugin Information', 'glocal-global-menu'
								); ?></span></h3>

						<div class="inside">
							<p><?php esc_attr_e(
									'This is a Wordpress plugin that adds a global menu to all sites in a multi-site network.',
									'glocal-global-menu'
								); ?></p>

							<p>
								<?php esc_attr_e(
									'Selected Menu',
									'glocal-global-menu'
								); ?>
							</p>

							<p>
								<code>
								<?php
								$options = get_option( 'anp-global-menu' );
								
								print_r( $options['anp_global_menu_selected'] ); 
								?>
								</code>

							</p>
						</div>
						<!-- .inside -->

					</div>
					<!-- .postbox -->

				</div>
				<!-- .meta-box-sortables -->

			</div>
			<!-- #postbox-container-1 .postbox-container -->

		</div>
		<!-- #post-body .metabox-holder .columns-2 -->

		<br class="clear">
	</div>
	<!-- #poststuff -->

</div> <!-- .wrap -->
