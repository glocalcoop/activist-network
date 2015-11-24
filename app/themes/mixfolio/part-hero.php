<?php
	global $mixfolio_options;
	if ( isset( $mixfolio_options[ 'mixfolio_display_welcome_area' ] ) ) :
?>

	<div class="row">
		<div class="hero clearfix">
			<?php if ( ! isset( $mixfolio_options[ 'mixfolio_display_contact_information' ] ) && ! isset( $mixfolio_options[ 'mixfolio_twitter_id' ] ) ) { ?>
				<div class="columns twelve">
			<?php } else { ?>
				<div class="columns eight">
			<?php } ?>
				<?php if ( isset( $mixfolio_options[ 'mixfolio_welcome_area_title' ] ) ) : ?>
					<h2>
						<?php echo esc_html( $mixfolio_options[ 'mixfolio_welcome_area_title' ] ); ?>
					</h2>
				<?php endif; ?>
				<?php if ( isset( $mixfolio_options[ 'mixfolio_welcome_area_message' ] ) ) : ?>
					<div class="subheader">
						<?php echo $mixfolio_options[ 'mixfolio_welcome_area_message' ]; // HTML Allowed ?>
					</div>
				<?php endif; ?>
			</div><!-- .eight -->

			<?php if ( isset( $mixfolio_options[ 'mixfolio_display_contact_information' ] ) || isset( $mixfolio_options[ 'mixfolio_twitter_id' ] ) ) : ?>
				<div class="columns four">
					<?php if ( isset( $mixfolio_options[ 'mixfolio_twitter_id' ] ) ) : ?>
						<h3 class="twitter">
							<a href="<?php echo esc_attr( 'http://twitter.com/' . $mixfolio_options[ 'mixfolio_twitter_id' ] ); ?>" title="<?php esc_attr_e( 'Follow me on Twitter', 'mixfolio' ); ?>">
								<?php _e( 'Follow', 'mixfolio' ); ?>
							</a>
						</h3>
						<div id="tweets"></div><!-- #tweets -->
						<hr />
					<?php endif; ?>
					<?php if ( isset( $mixfolio_options[ 'mixfolio_display_contact_information' ] ) ) : ?>
						<a href="#" class="button charcoal radius large full-width" data-reveal-id="contact"><?php _e( 'Contact', 'mixfolio' ); ?></a>
					<?php endif; ?>
				</div><!-- .four -->
			<?php endif; ?>
		</div><!-- .hero -->
	</div><!-- .row -->
<?php endif; ?>