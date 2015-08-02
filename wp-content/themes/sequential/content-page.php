<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package Sequential
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php
		if ( 0 == get_theme_mod( 'sequential_title_front_page' ) ) {
			if ( ! is_page_template( 'page-templates/front-page.php' ) ) {
				the_title( '<header class="entry-header"><h1 class="entry-title">', '</h1></header>' );
			}
		} else {
			the_title( '<header class="entry-header"><h1 class="entry-title">', '</h1></header>' );
		}
	?>

	<div class="entry-content">
		<?php the_content(); ?>
		<?php
			wp_link_pages( array(
				'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'sequential' ) . '</span>',
				'after'       => '</div>',
				'link_before' => '<span>',
				'link_after'  => '</span>',
			) );
		?>
	</div><!-- .entry-content -->

	<?php edit_post_link( __( 'Edit', 'sequential' ), '<footer class="entry-footer"><span class="edit-link">', '</span></footer>' ); ?>
</article><!-- #post-## -->
