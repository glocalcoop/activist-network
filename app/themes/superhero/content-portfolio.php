<?php
/**
 * This template part is used to display portfolio posts in archives.
 *
 * @package Superhero
 * @since Superhero 1.2
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php
	if ( has_post_thumbnail() ) :
		the_post_thumbnail( 'feat-img' );
	endif;
	?>

	<header class="entry-header">
		<?php if ( is_single() ) : ?>
		<h1 class="entry-title"><?php the_title(); ?></h1>
		<?php else : ?>
		<h1 class="entry-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h1>
		<?php endif; ?>
	</header><!-- .entry-header -->

	<div class="entry-content">
		<?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'superhero' ) ); ?>
		<?php wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'superhero' ), 'after' => '</div>' ) ); ?>
	</div><!-- .entry-content -->

	<footer class="entry-meta">
		<?php
			$project_types = get_the_term_list( get_the_ID(), 'jetpack-portfolio-type', '', __( ', ', 'superhero' ) );
			$project_tags = get_the_term_list( get_the_ID(), 'jetpack-portfolio-tag', '', __( ', ', 'superhero' ) );
		?>

		<?php if ( $project_types ) : ?>
		<span class="project-types">
			<?php printf( __( 'Posted in %1$s', 'superhero' ), $project_types ); ?>
		</span>
		<?php endif; ?>

		<?php if ( $project_tags ) : ?>
		<span class="sep"> | </span>
		<span class="tags-links">
			<?php printf( __( 'Tagged %1$s', 'superhero' ), $project_tags ); ?>
		</span>
		<?php endif; ?>

		<?php if ( ! post_password_required() && ( comments_open() || '0' != get_comments_number() ) ) : ?>
		<span class="sep"> | </span>
		<span class="comments-link"><?php comments_popup_link( __( 'Leave a comment', 'superhero' ), __( '1 Comment', 'superhero' ), __( '% Comments', 'superhero' ) ); ?></span>
		<?php endif; ?>

		<?php edit_post_link( __( 'Edit', 'superhero' ), '<span class="sep"> | </span><span class="edit-link">', '</span>' ); ?>
	</footer><!-- .entry-meta -->
</article><!-- #post-## -->
