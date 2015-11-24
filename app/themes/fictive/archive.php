<?php
/**
 * The template for displaying Archive pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Fictive
 */

get_header(); ?>

	<section id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

		<?php if ( have_posts() ) : ?>

			<header class="page-header">
				<h1 class="page-title">
					<?php
						if ( is_category() ) :
							single_cat_title();

						elseif ( is_tag() ) :
							single_tag_title();

						elseif ( is_author() ) :
							printf( __( 'Author: %s', 'fictive' ), '<span class="vcard">' . get_the_author() . '</span>' );

						elseif ( is_day() ) :
							printf( __( 'Day: %s', 'fictive' ), '<span>' . get_the_date() . '</span>' );

						elseif ( is_month() ) :
							printf( __( 'Month: %s', 'fictive' ), '<span>' . get_the_date( _x( 'F Y', 'monthly archives date format', 'fictive' ) ) . '</span>' );

						elseif ( is_year() ) :
							printf( __( 'Year: %s', 'fictive' ), '<span>' . get_the_date( _x( 'Y', 'yearly archives date format', 'fictive' ) ) . '</span>' );

						elseif ( is_tax( 'post_format', 'post-format-aside' ) ) :
							_e( 'Asides', 'fictive' );

						elseif ( is_tax( 'post_format', 'post-format-gallery' ) ) :
							_e( 'Galleries', 'fictive');

						elseif ( is_tax( 'post_format', 'post-format-image' ) ) :
							_e( 'Images', 'fictive');

						elseif ( is_tax( 'post_format', 'post-format-video' ) ) :
							_e( 'Videos', 'fictive' );

						elseif ( is_tax( 'post_format', 'post-format-quote' ) ) :
							_e( 'Quotes', 'fictive' );

						elseif ( is_tax( 'post_format', 'post-format-link' ) ) :
							_e( 'Links', 'fictive' );

						elseif ( is_tax( 'post_format', 'post-format-status' ) ) :
							_e( 'Statuses', 'fictive' );

						elseif ( is_tax( 'post_format', 'post-format-audio' ) ) :
							_e( 'Audios', 'fictive' );

						elseif ( is_tax( 'post_format', 'post-format-chat' ) ) :
							_e( 'Chats', 'fictive' );

						else :
							_e( 'Archives', 'fictive' );

						endif;
					?>
				</h1>
				<?php
					// Show an optional term description.
					$term_description = term_description();
					if ( ! empty( $term_description ) ) :
						printf( '<div class="taxonomy-description">%s</div>', $term_description );
					endif;
				?>
				<?php $description = get_the_author_meta( 'user_description', get_the_author_meta( 'ID' ) ); ?>
				<?php if ( is_author() && '' != $description ) : ?>
					<div class="author-archives-header">
						<div class="author-info">
							<span class="author-archives-name"><span class="vcard"><a class="url fn n" href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( "ID" ) ) ); ?>" rel="me"><?php echo get_the_author(); ?></a></span></span>
							<span class="author-archives-url"><a href="<?php echo esc_url( get_the_author_meta( 'user_url', get_the_author_meta( 'ID' ) ) ); ?>"><?php echo get_the_author_meta( 'user_url', get_the_author_meta( 'ID' ) ); ?></a></span>
							<span class="author-archives-bio"><?php echo wp_kses_post( $description ); ?></span>
						</div>
					</div>
				<?php endif; ?>
			</header><!-- .page-header -->

			<?php /* Start the Loop */ ?>
			<?php while ( have_posts() ) : the_post(); ?>

				<?php
					/* Include the Post-Format-specific template for the content.
					 * If you want to override this in a child theme, then include a file
					 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
					 */
					get_template_part( 'content', get_post_format() );
				?>

			<?php endwhile; ?>

			<?php fictive_paging_nav(); ?>

		<?php else : ?>

			<?php get_template_part( 'content', 'none' ); ?>

		<?php endif; ?>

		</main><!-- #main -->
	</section><!-- #primary -->

<?php get_footer(); ?>