<?php
/*
Template Name: Network Sites
*/
?>

<?php get_header(); ?>

<div class="content">

	<div class="wrap">

		<main role="main">

			<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

			<article id="post-<?php the_ID(); ?>" <?php post_class( 'clearfix' ); ?> itemscope itemtype="http://schema.org/BlogPosting">

				<header class="article-header">

					<h1 class="page-title" itemprop="headline"><?php the_title(); ?></h1>

					<section class="entry-content">
						<?php the_content(); ?>
					</section>

				</header>

				<section class="directory-content" itemprop="articleBody" rel="main">


					<ul class="sort js-menu">
						<li data-sort="id" class="is-on">Most Recently Added</li>
						<li data-sort="slug">Alphabetically</li>
						<li data-sort="posts">Most Active</li>
					</ul>

					<ul class="toggle js-menu">
						<li data-view="masonry" class="view-grid is-on">Grid</li>
						<li data-view="vertical" class="view-list">List</li>
					</ul>


					<ul class="sites-list view-grid" id="isotope">
						<?php
						$sites = wp_get_sites('offset=1&archived=0&deleted=0');

						foreach ($sites as $site) {
							$site_id = $site['blog_id'];
							$site_details = get_blog_details($site_id);
							$site_options = get_blog_option($site_id, 'theme_mods_glocal-group');
							$site_image = $site_options['glocal_site_image'];
							$site_path = $site_details->path;
							$site_slug = str_replace('/','',$site_path);

							// Get post count for each site
							$site_details = get_blog_details($site_id);

							// Find Network pages that are associated with this site
							$args = array (
								'post_type'         => 'network',
								'meta_query'        => array(
									array(
										'key'       => 'glocal_network_sites',
										'value'     => $site_id,
										'compare'   => '=',
									),
								),
							);
							$network_query = new WP_Query( $args );

							?>
							<?php
							if(function_exists('glocal_get_site_image')) {
								$header = glocal_get_site_image($site_id);
							} ?>

							<li class="isomote id-<?php echo $site_id; ?> site-<?php echo $site_slug; ?> network-<?php foreach($network_query as $post){ echo $post->post_name;} ?>" data-id="<?php echo $site_id ?>" data-slug="<?php echo $site_slug ?>" data-posts="<?php echo $site_details->post_count; ?>">
								<a href="<?php echo $site_details->siteurl; ?>" class="item-image <?php if(!$header) { echo 'no-image'; } ?>" style="background-image: url('<?php if($header) { echo $header; } ?>');"></a>
								<h3 class="item-title"><a href="<?php echo $site_details->siteurl; ?>"><?php echo $site_details->blogname; ?></a></h3>
								<h6 class="meta item-network"><?php foreach($network_query as $post){ echo $post->post_title;} ?></h6>
								<h6 class="meta item-posts">
								<?php
									if($site_details->post_count) {
										echo $site_details->post_count . ' posts';
									}
								?>
								</h6>
								<h6 class="meta item-topic"></h6>
							</li>

						<?php } ?>

        					<li class="isomote directory-promo" id="promo-builder">
        						<a href="/sites/get-a-website" title="Get a website">
            						<h3 class="post-title">Join the Network. Get a Site.</h3>
            						<div class="promo-icons"><i class="icon"></i></div>
        						</a>
        					</li>

					</ul>
					
				</section>

				<?php comments_template(); ?>

			</article>


			<?php endwhile; else : ?>

					<article id="post-not-found" class="hentry clearfix">
						<header class="article-header">
							<h1><?php _e( 'Oops, Post Not Found!', 'glocal-theme' ); ?></h1>
						</header>
						<section class="directory-content">
							<p><?php _e( 'Uh Oh. Something is missing. Try double checking things.', 'glocal-theme' ); ?></p>
						</section>
						<footer class="article-footer">
                            <p><?php _e( 'This is the error message in the page.php template.', 'glocal-theme' ); ?></p>
						</footer>
					</article>

			<?php endif; ?>

		</main>

			

	</div>

</div>

<script>
$(document).ready(function() {

  var $container = $('#isotope');
  
  // init Isotope
  $container.isotope({
    itemSelector: '.isomote',
    layoutMode: 'masonry',
    masonry: {
        columnWidth: 285, 
        gutter: 20
    },
    getSortData: {
        id: '[data-id]', 
        slug: '[data-slug]', 
        posts: '[data-posts]'
    },
    sortAscending: {
        id: false,
        slug: true,
        posts: false
    },
    sortBy: 'id'
  });

  // filter
  $('.filter').on( 'click', 'li', function() {
    var filterValue = $(this).attr('data-filter');
    $container.isotope({ filter: filterValue });
  });

  // sort
  $('.sort').on( 'click', 'li', function() {
    var sortValue = $(this).attr('data-sort');
    $container.isotope({ 
        sortBy: sortValue
    });
  });
  

  // change view
  $('.toggle').on('click', 'li', function() {
    if ($(this).hasClass('view-grid')) {
        $('#isotope').removeClass('view-list').addClass('view-grid');
    }
    else if($(this).hasClass('view-list')) {
        $('#isotope').removeClass('view-grid').addClass('view-list');
    }
    var viewValue = $(this).attr('data-view');
    $container.isotope({ layoutMode: viewValue });
  });

  // change is-on class
  $('.js-menu').each(function(i, focus) {
    var $focus = $(focus);
    $focus.on('click', 'li', function() {
      $focus.find('.is-on').removeClass('is-on');
      $(this).addClass('is-on');
    });
  });


});

</script>




<?php get_footer(); ?>
