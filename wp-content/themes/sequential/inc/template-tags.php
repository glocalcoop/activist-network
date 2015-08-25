<?php
/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Sequential
 */

if ( ! function_exists( 'sequential_paging_nav' ) ) :
/**
 * Display navigation to next/previous set of posts when applicable.
 */
function sequential_paging_nav() {
	// Don't print empty markup if there's only one page.
	if ( $GLOBALS['wp_query']->max_num_pages < 2 ) {
		return;
	}
	?>
	<nav class="navigation paging-navigation" role="navigation">
		<h1 class="screen-reader-text"><?php _e( 'Posts navigation', 'sequential' ); ?></h1>
		<div class="nav-links">

			<?php if ( get_next_posts_link() ) : ?>
				<div class="nav-previous">
					<?php
						if ( is_post_type_archive( 'jetpack-testimonial' ) ) {
							next_posts_link( __( '<span class="meta-nav">&larr;</span> Older testimonials', 'sequential' ) );
						} else {
							next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'sequential' ) );
						}
					?>
				</div>
			<?php endif; ?>

			<?php if ( get_previous_posts_link() ) : ?>
				<div class="nav-next">
					<?php
						if ( is_post_type_archive( 'jetpack-testimonial' ) ) {
							previous_posts_link( __( 'Newer testimonials <span class="meta-nav">&rarr;</span>', 'sequential' ) );
						} else {
							previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'sequential' ) );
						}
					?>
				</div>
			<?php endif; ?>

		</div><!-- .nav-links -->
	</nav><!-- .navigation -->
	<?php
}
endif;

if ( ! function_exists( 'sequential_post_nav' ) ) :
/**
 * Display navigation to next/previous post when applicable.
 */
function sequential_post_nav() {
	// Don't print empty markup if there's nowhere to navigate.
	$previous = ( is_attachment() ) ? get_post( get_post()->post_parent ) : get_adjacent_post( false, '', true );
	$next     = get_adjacent_post( false, '', false );

	if ( ! $next && ! $previous ) {
		return;
	}
	?>
	<nav class="navigation post-navigation" role="navigation">
		<h1 class="screen-reader-text"><?php _e( 'Post navigation', 'sequential' ); ?></h1>
		<div class="nav-links">
			<?php
				if ( is_attachment() ) {
					previous_post_link( '%link', __( '<span class="meta-nav">Published In</span>%title', 'sequential' ) );
				} else {
					if ( is_singular( 'jetpack-testimonial' ) ) {
						previous_post_link( '%link', __( '<span class="meta-nav">Previous Testimonial</span>%title', 'sequential' ) );
						next_post_link( '%link', __( '<span class="meta-nav">Next Testimonial</span>%title', 'sequential' ) );
					} else {
						previous_post_link( '%link', __( '<span class="meta-nav">Previous Post</span>%title', 'sequential' ) );
						next_post_link( '%link', __( '<span class="meta-nav">Next Post</span>%title', 'sequential' ) );
					}
				}
			?>
		</div><!-- .nav-links -->
	</nav><!-- .navigation -->
	<?php
}
endif;

/**
 * Returns true if a blog has more than 1 category.
 *
 * @return bool
 */
function sequential_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'sequential_categories' ) ) ) {
		// Create an array of all the categories that are attached to posts.
		$all_the_cool_cats = get_categories( array(
			'fields'     => 'ids',
			'hide_empty' => 1,

			// We only need to know if there is more than one category.
			'number'     => 2,
		) );

		// Count the number of categories that are attached to the posts.
		$all_the_cool_cats = count( $all_the_cool_cats );

		set_transient( 'sequential_categories', $all_the_cool_cats );
	}

	if ( $all_the_cool_cats > 1 ) {
		// This blog has more than 1 category so sequential_categorized_blog should return true.
		return true;
	} else {
		// This blog has only 1 category so sequential_categorized_blog should return false.
		return false;
	}
}

/**
 * Flush out the transients used in sequential_categorized_blog.
 */
function sequential_category_transient_flusher() {
	// Like, beat it. Dig?
	delete_transient( 'sequential_categories' );
}
add_action( 'edit_category', 'sequential_category_transient_flusher' );
add_action( 'save_post',     'sequential_category_transient_flusher' );

if ( ! function_exists( 'sequential_entry_meta' ) ) :
/**
 * Prints HTML with meta information for the post format, date and edit link.
 */
function sequential_entry_meta() {
	$format = get_post_format();
	$formats = get_theme_support( 'post-formats' );

	if ( $format && in_array( $format, $formats[0] ) ) : // If has post format
?>

		<span class="format-badge"><a href="<?php echo esc_url( get_post_format_link( $format ) ); ?>" title="<?php echo esc_attr( sprintf( __( 'All %s posts', 'sequential' ), get_post_format_string( $format ) ) ); ?>"><?php echo get_post_format_string( $format ); ?></a></span>

	<?php
		endif;

		if ( ! is_sticky() || is_single() ) { // If is not sticky or is single display date

			$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
			if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
				$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
			}

			$time_string = sprintf( $time_string,
				esc_attr( get_the_date( 'c' ) ),
				esc_html( get_the_date() ),
				esc_attr( get_the_modified_date( 'c' ) ),
				esc_html( get_the_modified_date() )
			);

			$posted_on = '<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>';
			echo '<span class="posted-on">' . $posted_on . '</span> ';

		}

		if ( is_sticky() && ! is_single() ) {

			$featured = '<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . __( 'Featured', 'sequential' ) . '</a>';
			echo '<span class="featured">' . $featured . '</span> ';

		}

		$byline = '<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>';
		echo '<span class="byline">' . $byline . '</span> ';

		edit_post_link( __( 'Edit', 'sequential' ), '<span class="edit-link">', '</span>' );
}
endif;

if ( ! function_exists( 'sequential_entry_footer' ) ) :
/**
 * Prints HTML with meta information for the categories, tags and comments.
 */
function sequential_entry_footer() {
	/* Hide category and tag text for pages */
	if ( 'post' == get_post_type() ) {
		/* translators: used between list items, there is a space after the comma */
		$categories_list = get_the_category_list( __( ', ', 'sequential' ) );
		if ( $categories_list && sequential_categorized_blog() ) {
			printf( '<span class="cat-links">' . __( 'Posted in %1$s', 'sequential' ) . '</span>', $categories_list );
		}

		/* translators: used between list items, there is a space after the comma */
		$tags_list = get_the_tag_list( '', __( ', ', 'sequential' ) );
		if ( $tags_list ) {
			printf( '<span class="tags-links">' . __( 'Tagged %1$s', 'sequential' ) . '</span>', $tags_list );
		}
	}

	if ( ! post_password_required() && ( comments_open() || '0' != get_comments_number() ) ) {
		echo '<span class="comments-link">';
		comments_popup_link( __( 'Leave a comment', 'sequential' ), __( '1 Comment', 'sequential' ), __( '% Comments', 'sequential' ) );
		echo '</span>';
	}
}
endif;

/**
 * Display an optional post thumbnail.
 *
 * Wraps the post thumbnail in an anchor element on index
 * views, or a div element when on single views.
 *
 * @return void
 */
function sequential_post_thumbnail() {
	if ( post_password_required() || is_attachment() || ! has_post_thumbnail() || has_post_format() ) {
		return;
	}
?>

	<?php if ( is_single() || is_page_template( 'page-templates/front-page.php' ) ) : ?>
		<div class="post-thumbnail">
			<?php the_post_thumbnail( 'sequential-featured-image' ); ?>
		</div>
	<?php else : ?>
		<a class="post-thumbnail" href="<?php the_permalink(); ?>">
			<?php the_post_thumbnail( 'sequential-featured-image' ); ?>
		</a>
	<?php endif; ?>

<?php
}

/**
 * Display Featured Pages on Front Page Templates.
 */
function sequential_featured_pages() {
	$featured_page_1 = esc_attr( get_theme_mod( 'sequential_featured_page_one_front_page', '0' ) );
	$featured_page_2 = esc_attr( get_theme_mod( 'sequential_featured_page_two_front_page', '0' ) );

	if ( 0 == $featured_page_1 && 0 == $featured_page_2 ) {
		return;
	}

	for ( $page_number = 1; $page_number <= 2; $page_number++ ) :
		if ( 0 != ${'featured_page_' . $page_number} ) : // Check if a featured page has been set in the customizer
?>
			<div class="front-page-block clear">
				<?php
					// Create new argument using the page ID of the page set in the customizer
					$featured_page_args = array(
						'page_id' => ${'featured_page_' . $page_number},
					);
					// Create a new WP_Query using the argument previously created
					$featured_page_query = new WP_Query( $featured_page_args );
				?>

				<?php while ( $featured_page_query->have_posts() ) : $featured_page_query->the_post(); ?>

					<?php get_template_part( 'content', 'page' ); ?>

				<?php
					endwhile;
					wp_reset_postdata();
				?>
			</div><!-- .front-page-block -->
<?php
		endif;
	endfor;
}