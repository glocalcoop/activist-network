<?php
/**
 * The template for displaying posts in the Image Post Format on index and archive pages
 *
 * Learn more: http://codex.wordpress.org/Post_Formats
 *
 * @package Mixfolio
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<h1 class="entry-title">
			<?php the_title(); ?>
		</h1><!-- .entry-title -->
	</header><!-- .entry-header -->

	<div class="entry-content">
	<dl class="tabs">
		<dd><a class="active" href="#simple1"><?php echo get_post_format(); ?></a></dd>
		<dd><a href="#simple2"><?php echo get_post_format(); ?> <?php _e( 'Info','mixfolio' ); ?></a></dd>
		<?php if ( comments_open() ){ ?>
		<dd><a href="#simple3"><?php comments_number( __( 'Comments', 'mixfolio' ), __( '1 Comment', 'mixfolio' ), __( '% Comments', 'mixfolio' ) ); ?></a></dd>
		<?php } ?>
	</dl>
	<ul class="tabs-content">
		<li id="simple1tab" class="active">
			<?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'mixfolio' ) ); ?>
		</li>
		<li id="simple2tab">
			<?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'mixfolio' ), 'after' => '</div>' ) ); ?>
			<footer class="entry-meta">
				<?php mixfolio_posted_on(); ?>
				<?php mixfolio_posted_by(); ?>
				<?php if ( comments_open() || ( '0' != get_comments_number() && ! comments_open() ) ) : ?>
				<span class="sep"> | </span>
				<span class="comments-link"><?php comments_popup_link( __( 'Leave a comment', 'mixfolio' ), __( '1 Comment', 'mixfolio' ), __( '% Comments', 'mixfolio' ) ); ?></span>
				<?php endif; ?>
				<?php edit_post_link( __( 'Edit', 'mixfolio' ), '<span class="sep"> | </span><span class="edit-link">', '</span>' ); ?>
			</footer><!-- #entry-meta -->
		</li>
		<?php if ( comments_open() ){ ?>
		<li id="simple3tab">
			<?php comments_template( '', true ); ?>
		</li>
		<?php } ?>
	</ul>

	</div><!-- .entry-content -->
</article><!-- #post-<?php the_ID(); ?> -->