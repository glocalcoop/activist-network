<?php $post = get_post(); ?>

<div id="<?php echo esc_attr( Tagregator::CSS_PREFIX . get_the_ID() ); ?>" class="<?php echo esc_attr( $css_classes ); ?>">

	<a href="http://instagram.com/<?php echo esc_attr( $author_username ); ?>" class="<?php echo esc_attr( Tagregator::CSS_PREFIX ); ?>author-profile clearfix">
		<?php if ( $author_image_url ) : ?>
			<img src="<?php echo esc_attr( $author_image_url ); ?>" alt="<?php echo esc_attr( $author_name ); ?>" class="<?php echo esc_attr( Tagregator::CSS_PREFIX ); ?>author-avatar">
		<?php endif; ?>
		<span class="<?php echo esc_attr( Tagregator::CSS_PREFIX ); ?>author-name"><?php echo esc_html( $author_name ); ?></span>
		<span class="<?php echo esc_attr( Tagregator::CSS_PREFIX ); ?>author-username">@<?php echo esc_html( $author_username ); ?></span>
	</a>

	<div class="<?php echo esc_attr( Tagregator::CSS_PREFIX ); ?>item-content">
		<?php if ( $media ) : ?>
			<?php foreach ( $media as $media_item ) : ?>
				<?php if ( 'image' == $media_item['type'] ) : ?>
					<img src="<?php echo esc_url( $media_item['small_url'] ); ?>" alt="" />
				<?php endif; ?>
			<?php endforeach; ?>
		<?php endif; ?>

		<?php if ( $show_excerpt ) : ?>
			<?php the_excerpt(); ?>
			<p><a href="<?php echo esc_attr( $media_permalink ); ?>">See the rest of this description on Instagram</a></p>
		<?php else : ?>
			<?php the_content(); ?>
		<?php endif; ?>
	</div>

	<a href="<?php echo esc_attr( $media_permalink ); ?>" class="<?php echo esc_attr( Tagregator::CSS_PREFIX ); ?>timestamp">
		<?php echo human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ) . ' ago'; ?>
	</a>

	<img class="tggr-source-logo" src="<?php echo esc_attr( $logo_url ); ?>" alt="Instagram" />
</div>
