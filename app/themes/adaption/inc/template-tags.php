<?php
/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Adaption
 */

if ( ! function_exists( 'adaption_paging_nav' ) ) :
/**
 * Display navigation to next/previous set of posts when applicable.
 *
 * @return void
 */
function adaption_paging_nav() {
	// Don't print empty markup if there's only one page.
	if ( $GLOBALS['wp_query']->max_num_pages < 2 ) {
		return;
	}
	?>
	<nav class="navigation paging-navigation" role="navigation">
		<h1 class="screen-reader-text"><?php _e( 'Posts navigation', 'adaption' ); ?></h1>
		<div class="nav-links">

			<?php if ( get_next_posts_link() ) : ?>
			<div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'adaption' ) ); ?></div>
			<?php endif; ?>

			<?php if ( get_previous_posts_link() ) : ?>
			<div class="nav-next"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'adaption' ) ); ?></div>
			<?php endif; ?>

		</div><!-- .nav-links -->
	</nav><!-- .navigation -->
	<?php
}
endif;

if ( ! function_exists( 'adaption_post_nav' ) ) :
/**
 * Display navigation to next/previous post when applicable.
 *
 * @return void
 */
function adaption_post_nav() {
	// Don't print empty markup if there's nowhere to navigate.
	$previous = ( is_attachment() ) ? get_post( get_post()->post_parent ) : get_adjacent_post( false, '', true );
	$next     = get_adjacent_post( false, '', false );

	if ( ! $next && ! $previous ) {
		return;
	}
	?>
	<nav class="navigation post-navigation" role="navigation">
		<h1 class="screen-reader-text"><?php _e( 'Post navigation', 'adaption' ); ?></h1>
		<div class="nav-links">
			<?php
				previous_post_link( '<div class="nav-previous">%link</div>', _x( '<span class="meta-nav">&larr;</span> %title', 'Previous post link', 'adaption' ) );
				next_post_link(     '<div class="nav-next">%link</div>',     _x( '%title <span class="meta-nav">&rarr;</span>', 'Next post link',     'adaption' ) );
			?>
		</div><!-- .nav-links -->
	</nav><!-- .navigation -->
	<?php
}
endif;

if ( ! function_exists( 'adaption_comment' ) ) :
/**
 * Template for comments and pingbacks.
 *
 * Used as a callback by wp_list_comments() for displaying the comments.
 */
function adaption_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	$args['avatar_size'] = 40;

	if ( 'pingback' == $comment->comment_type || 'trackback' == $comment->comment_type ) : ?>

	<li id="comment-<?php comment_ID(); ?>" <?php comment_class(); ?>>
		<div class="comment-body">
			<?php _e( 'Pingback:', 'adaption' ); ?> <?php comment_author_link(); ?> <?php edit_comment_link( __( 'Edit', 'adaption' ), '<span class="edit-link">', '</span>' ); ?>
		</div>

	<?php else : ?>

	<li id="comment-<?php comment_ID(); ?>" <?php comment_class( empty( $args['has_children'] ) ? '' : 'parent' ); ?>>
		<article id="div-comment-<?php comment_ID(); ?>" class="comment-body">
			<div class="comment-author vcard">
				<?php if ( 0 != $args['avatar_size'] ) { echo '<span class="avatar-wrapper">' . get_avatar( $comment, $args['avatar_size'] ) . '</span>'; } ?>
			</div><!-- .comment-author -->
			<div class="comment-wrapper">
				<div class="comment-content">
					<?php comment_text(); ?>
				</div><!-- .comment-content -->
				<footer class="comment-meta">

				<?php if ( '0' == $comment->comment_approved ) : ?>
					<p class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'adaption' ); ?></p>
				<?php endif; ?>

				<?php printf( __( '%s ', 'adaption' ), sprintf( '<cite class="fn">%s</cite>', get_comment_author_link() ) ); ?>

				<a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>">
					<time datetime="<?php comment_time( 'c' ); ?>">
						<span class="post-date"><?php printf( _x( ', %1$s at %2$s', '1: date, 2: time', 'adaption' ), get_comment_date(), get_comment_time() ); ?></span>
					</time>
				</a>
				<?php edit_comment_link( __( ' (Edit)', 'adaption' ), '<span class="edit-link">', '</span>' ); ?>
				<?php
					comment_reply_link( array_merge( $args, array(
						'add_below' => 'div-comment',
						'depth'     => $depth,
						'max_depth' => $args['max_depth'],
						'before'    => '<div class="reply">',
						'after'     => '</div>',
					) ) );
				?>

				</footer><!-- .comment-meta -->
			</div>
		</article><!-- .comment-body -->

	<?php
	endif;
}
endif; // ends check for adaption_comment()

if ( ! function_exists( 'adaption_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post-date/time and author.
 */
function adaption_posted_on() {
	$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time>';
	if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
		$time_string .= '<time class="updated" datetime="%3$s">%4$s</time>';
	}

	$time_string = sprintf( $time_string,
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() ),
		esc_attr( get_the_modified_date( 'c' ) ),
		esc_html( get_the_modified_date() )
	);

	if ( is_multi_author() ) {
		printf( __( '<span class="posted-on">Posted on %1$s</span><span class="byline"> by %2$s</span>', 'adaption' ),
			sprintf( '<a href="%1$s" rel="bookmark">%2$s</a>',
				esc_url( get_permalink() ),
				$time_string
			),
			sprintf( '<span class="author vcard"><a class="url fn n" href="%1$s">%2$s</a></span>',
				esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
				esc_html( get_the_author() )
			)
		);
	} else {
		printf( __( '<span class="posted-on">Posted on %1$s</span>', 'adaption' ),
			sprintf( '<a href="%1$s" rel="bookmark">%2$s</a>',
				esc_url( get_permalink() ),
				$time_string
			)
		);
	}
}
endif;

/**
 * Returns true if a blog has more than 1 category.
 */
function adaption_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'all_the_cool_cats' ) ) ) {
		// Create an array of all the categories that are attached to posts.
		$all_the_cool_cats = get_categories( array(
			'hide_empty' => 1,
		) );

		// Count the number of categories that are attached to the posts.
		$all_the_cool_cats = count( $all_the_cool_cats );

		set_transient( 'all_the_cool_cats', $all_the_cool_cats );
	}

	if ( '1' != $all_the_cool_cats ) {
		// This blog has more than 1 category so adaption_categorized_blog should return true.
		return true;
	} else {
		// This blog has only 1 category so adaption_categorized_blog should return false.
		return false;
	}
}

/**
 * Flush out the transients used in adaption_categorized_blog.
 */
function adaption_category_transient_flusher() {
	// Like, beat it. Dig?
	delete_transient( 'all_the_cool_cats' );
}
add_action( 'edit_category', 'adaption_category_transient_flusher' );
add_action( 'save_post',     'adaption_category_transient_flusher' );