<?php
/**
 * @package untitled
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php if ( '' != get_the_post_thumbnail() ) : ?>

		<div class="single-thumbnail genericon genericon-link">
			<a href="<?php the_permalink(); ?>" rel="bookmark">
				<?php the_post_thumbnail( 'content-img' ); ?>
			</a>
		</div><!-- .single-thumbnail -->
		<div class="header-wrapper">
			<?php if ( 'post' == get_post_type() ) : ?>
			<div class="entry-meta">
				<?php untitled_posted_on(); ?>
				<?php if ( ! post_password_required() && ( comments_open() || '0' != get_comments_number() ) ) : ?>
				<span class="sep"> - </span>
				<span class="comments-link"><?php comments_popup_link( __( '0 comments', 'untitled' ), __( '1 Comment', 'untitled' ), __( '% Comments', 'untitled' ) ); ?></span>
				<?php endif; ?>
			</div><!-- .entry-meta -->
			<?php endif; ?>
			<header class="entry-header">
				<h1 class="entry-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h1>
			</header><!-- .entry-header -->

			<?php edit_post_link( __( 'Edit', 'untitled' ), '<footer class="entry-meta"><span class="edit-link">', '</span></footer>' ); ?>
		</div><!-- .header-wrapper -->

	<?php else : ?>

		<header class="entry-header">
			<?php if ( 'post' == get_post_type() ) : ?>
			<div class="entry-meta">
				<?php untitled_posted_on(); ?>
				<?php if ( ! post_password_required() && ( comments_open() || '0' != get_comments_number() ) ) : ?>
				<span class="sep"> - </span>
				<span class="comments-link"><?php comments_popup_link( __( '0 comments', 'untitled' ), __( '1 Comment', 'untitled' ), __( '% Comments', 'untitled' ) ); ?></span>
				<?php endif; ?>
			</div><!-- .entry-meta -->
			<?php endif; ?>
			<h1 class="entry-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h1>
		</header><!-- .entry-header -->

		<?php if ( is_search() ) : // Only display Excerpts for Search ?>
		<div class="entry-summary">
			<?php the_excerpt(); ?>
		</div><!-- .entry-summary -->
		<?php else : ?>
		<div class="entry-content">
			<?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'untitled' ) ); ?>
			<?php wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'untitled' ), 'after' => '</div>' ) ); ?>
		</div><!-- .entry-content -->
		<?php endif; ?>

	<?php endif; ?>
</article><!-- #post-## -->
