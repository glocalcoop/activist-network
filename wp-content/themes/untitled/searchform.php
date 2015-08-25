<?php
/**
 * The template for displaying search forms in untitled
 *
 * @package untitled
 */
?>
<form method="get" id="searchform" class="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>" role="search">
	<label for="s" class="screen-reader-text"><?php _ex( 'Search', 'assistive text', 'untitled' ); ?></label>
	<input type="search" class="field" name="s" value="<?php the_search_query(); ?>" id="s" placeholder="<?php echo esc_attr_x( 'Search &hellip;', 'placeholder', 'untitled' ); ?>" />
	<input type="submit" class="submit" id="searchsubmit" value="<?php echo esc_attr_x( 'Search', 'submit button', 'untitled' ); ?>" />
</form>
