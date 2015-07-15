<?php 
if(function_exists('glocal_customization_settings')) {
	$glocal_home_settings = glocal_customization_settings();
	$eventnumber = $glocal_home_settings['events']['number_events'];
}

?>

<?php // Check to see if Events Manager is active. If not don't display this module.
if ( is_plugin_active('events-manager/events-manager.php') ) { ?>

	<article id="events-module" class="module row events">
		<h2 class="module-heading">
		<?php if(!empty($glocal_home_settings['events']['events_heading_link'])) { ?>
			<a href="<?php echo $glocal_home_settings['events']['events_heading_link']; ?>">
				<?php echo $glocal_home_settings['events']['events_heading']; ?>
			</a>
		<?php } else { ?>
			<?php echo $glocal_home_settings['events']['events_heading']; ?>
		<?php } ?>	
		</h2>
		
		<ul class="events-list">
			<?php
			$parameters = array(
				'format'=>'<li>
					<h6 class="event-start">
						<time class="event-month" datetime="#M">#M</time>
						<time class="event-date" datetime="#j">#j</time>
						<time class="event-day" datetime="#D">#D</time>
					</h6>
					<h3 class="post-title event-title">#_EVENTLINK</h3>
					</li>',
			);

			// TODO: Add exclude site customization?

			if(!empty($eventnumber)) {
				$parameters['limit'] = $eventnumber;
			}

			$events = EM_Events::output($parameters); ?>
			<?php echo $events; ?>

			<li class="event-promo"><h3 class="promo-title"><a href="/events" title="Calendar">View all events</a></h3></li>
		</ul>
	</article>

<?php } else { ?>

	<?php get_template_part( 'partials/error', 'plugin' ); ?>

<?php } ?>