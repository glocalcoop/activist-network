<?php
/*
The comments page for Bones
*/

// Do not delete these lines
	if ( ! empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename( $_SERVER['SCRIPT_FILENAME'] ) )
		die ('Please do not load this page directly. Thanks!');

	if ( post_password_required() ) { ?>
		<div class="alert alert-help">
			<p class="nocomments"><?php _e('This post is password protected. Enter the password to view comments.', 'glocal-theme' ); ?></p>
		</div>
	<?php
		return;
	}
?>

<?php // You can start editing here. ?>

<?php if ( have_comments() ) : ?>
	<h3 id="comments"><?php comments_number( __( '<span>No</span> Responses', 'glocal-theme' ), __( '<span>One</span> Response', 'glocal-theme' ), _n( '<span>%</span> Response', '<span>%</span> Responses', get_comments_number(), 'glocal-theme' ) );?> to &#8220;<?php the_title(); ?>&#8221;</h3>

	<nav id="comment-nav">
		<ul class="clearfix">
				<li><?php previous_comments_link() ?></li>
				<li><?php next_comments_link() ?></li>
		</ul>
	</nav>

	<ol class="commentlist">
		<?php wp_list_comments( 'type=comment&callback=bones_comments' ); ?>
	</ol>

	<nav id="comment-nav">
		<ul class="clearfix">
				<li><?php previous_comments_link() ?></li>
				<li><?php next_comments_link() ?></li>
		</ul>
	</nav>

	<?php else : // this is displayed if there are no comments so far ?>

	<?php if ( comments_open() ) : ?>
			<?php // If comments are open, but there are no comments. ?>

	<?php else : // comments are closed ?>

	<?php // If comments are closed. ?>
	<!--p class="nocomments"><?php _e( 'Comments are closed.', 'glocal-theme' ); ?></p-->

	<?php endif; ?>

<?php endif; ?>


<?php if ( comments_open() ) : ?>

<section id="respond" class="respond-form">

	<h3 id="comment-form-title"><?php comment_form_title( __( 'Leave a Reply', 'glocal-theme' ), __( 'Leave a Reply to %s', 'glocal-theme' )); ?></h3>

	<div id="cancel-comment-reply">
		<p class="small"><?php cancel_comment_reply_link(); ?></p>
	</div>

	<?php if ( get_option('comment_registration') && !is_user_logged_in() ) : ?>
		<div class="alert alert-help">
			<p><?php printf( __( 'You must be %1$slogged in%2$s to post a comment.', 'glocal-theme' ), '<a href="<?php echo wp_login_url( get_permalink() ); ?>">', '</a>' ); ?></p>
		</div>
	<?php else : ?>

	<form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform">

	<?php if ( is_user_logged_in() ) : ?>

	<p class="comments-logged-in-as"><?php _e( 'Logged in as', 'glocal-theme' ); ?> <a href="<?php echo get_option( 'siteurl' ); ?>/wp-admin/profile.php"><?php echo $user_identity; ?></a>. <a href="<?php echo wp_logout_url( get_permalink() ); ?>" title="<?php _e( 'Log out of this account', 'glocal-theme' ); ?>"><?php _e( 'Log out', 'glocal-theme' ); ?> <?php _e( '&raquo;', 'glocal-theme' ); ?></a></p>

	<?php else : ?>

	<ul id="comment-form-elements" class="clearfix">

		<li>
			<label for="author"><?php _e( 'Name', 'glocal-theme' ); ?> <?php if ($req) _e( '(required)'); ?></label>
			<input type="text" name="author" id="author" value="<?php echo esc_attr($comment_author); ?>" placeholder="<?php _e( 'Your Name*', 'glocal-theme' ); ?>" tabindex="1" <?php if ($req) echo "aria-required='true'"; ?> />
		</li>

		<li>
			<label for="email"><?php _e( 'Mail', 'glocal-theme' ); ?> <?php if ($req) _e( '(required)'); ?></label>
			<input type="email" name="email" id="email" value="<?php echo esc_attr($comment_author_email); ?>" placeholder="<?php _e( 'Your E-Mail*', 'glocal-theme' ); ?>" tabindex="2" <?php if ($req) echo "aria-required='true'"; ?> />
			<small><?php _e("(will not be published)", 'glocal-theme' ); ?></small>
		</li>

		<li>
			<label for="url"><?php _e( 'Website', 'glocal-theme' ); ?></label>
			<input type="url" name="url" id="url" value="<?php echo esc_attr($comment_author_url); ?>" placeholder="<?php _e( 'Got a website?', 'glocal-theme' ); ?>" tabindex="3" />
		</li>

	</ul>

	<?php endif; ?>

	<p><textarea name="comment" id="comment" placeholder="<?php _e( 'Your Comment here...', 'glocal-theme' ); ?>" tabindex="4"></textarea></p>

	<p>
		<input name="submit" type="submit" id="submit" class="button" tabindex="5" value="<?php _e( 'Submit', 'glocal-theme' ); ?>" />
		<?php comment_id_fields(); ?>
	</p>

	<div class="alert alert-info">
		<p id="allowed_tags" class="small"><strong>XHTML:</strong> <?php _e( 'You can use these tags', 'glocal-theme' ); ?>: <code><?php echo allowed_tags(); ?></code></p>
	</div>

	<?php do_action( 'comment_form', $post->ID ); ?>

	</form>

	<?php endif; // If registration required and not logged in ?>
</section>

<?php endif; // if you delete this the sky will fall on your head ?>
