<input
	type="text"
	id="<?php echo esc_attr( $class::SETTINGS_PREFIX . $setting ); ?>"
	name="<?php echo esc_attr( Tagregator::PREFIX ); ?>settings[<?php echo esc_attr( $class ); ?>][<?php echo esc_attr( $setting ); ?>] ); ?>"
	class="regular-text"
	value="<?php echo esc_attr( TGGRSettings::get_instance()->settings[ $class ][ $setting ] ); ?>"
/>