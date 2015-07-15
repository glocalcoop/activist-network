<?php
/*
 * Default Events List Template
 * This page displays a list of events, called during the em_content() if this is an events list page.
 * You can override the default display settings pages by copying this file to yourthemefolder/plugins/events-manager/templates/ and modifying it however you need.
 * You can display events however you wish, there are a few variables made available to you:
 * 
 * $args - the args passed onto EM_Events::output()
 * 
 */
$args = apply_filters('em_content_events_args', $args);

$args['format_header'] = '
<a href="/events/add/?action=edit" class="button">Add Event</a>
<header class="event-list-header">
	<h6 class="event-list-date">Date</h6>
	<h6 class="event-list-description">Description</h6>
</header>
';

$args['format'] = '
<article id="event-#_EVENTID" class="post event">
	<header class="post-header event-header">
		<h4 class="meta">
			<span class="event-day">#l</span>
			<span class="event-date">#F #j</span>
			<span class="event-time">#g:#i#a</span>
		</h4>
	</header>
	<section class="post-body event-content">
		{has_image}<div class="post-image event-image">#_EVENTIMAGE</div>{/has_image}
		<h3 class="post-title event-title">#_EVENTLINK</h3>
		{has_location}<h6 class="event-location">
		    <span class="event-location-name">#_LOCATIONNAME</span>
            <span class="event-location-street">#_LOCATIONADDRESS</span>
            <span class="event-location-city">#_LOCATIONTOWN #_LOCATIONSTATE</span>
		</h6>{/has_location}
		<p class="post-excerpt event-description">#_EVENTEXCERPT{25,...}</p>
	</section>
</article>
';

if( get_option('dbem_css_evlist') ) echo "<div class='css-events-list event-list'>";

echo EM_Events::output( $args );

if( get_option('dbem_css_evlist') ) echo "</div>";
