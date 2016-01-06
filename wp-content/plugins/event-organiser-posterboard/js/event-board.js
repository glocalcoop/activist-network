/*jshint -W054 */
jQuery(document).ready(function ($) {

	//Workaround for indexOf in IE 7&8
	if (!Array.prototype.indexOf){
		Array.prototype.indexOf = function(elt /*, from*/){
			var len = this.length;

			var from = Number(arguments[1]) || 0;
			from = (from < 0) ? Math.ceil(from) : Math.floor(from);

			if ( from < 0 )
				from += len;

			for (; from < len; from++ ){
				if ( from in this && this[from] === elt )
					return from;
			}
			return -1;
		};
	}

	$('.eo-eb-filter').click(function(e){
		
		e.preventDefault();
		
		var type = $(this).data('filter-type');
		var value = $(this).data(type);
		var filter = 'eo-eb-'+type + '-' + value;
		var filterOn = $(this).data('filter-on');
		var activeFilters = $('#event-board-filters').data('filters').split(',');

		if( activeFilters.length === 1 && activeFilters[0] === "" )
			activeFilters = [];

		if( !filterOn ){
			//Add filter
			activeFilters.push( filter );
			
			$('#event-board .' + filter )
				.css({'visibility': 'hidden', 'display': 'none'})
				.removeClass("eo-eb-event-box masonry-brick masonry-brick")
				.addClass('eo-eb-event-box-hidden');
			
		}else{
			var index = activeFilters.indexOf( filter );
			if( index > -1 ){
				activeFilters.splice(index, 1);
				$.grep(activeFilters,function(n){ return(n); });
			}
			
			if( activeFilters.length > 0 ){
				
				//Remove filter
				$('#event-board .' + filter )
					.not( '.'+activeFilters.join(', .') )
					.css({'visibility': 'visible', 'display': 'block'})
					.addClass("eo-eb-event-box masonry-brick masonry-brick")
					.removeClass('eo-eb-event-box-hidden');
			}else{
				$('#event-board .' + filter )
					.css({'visibility': 'visible', 'display': 'block'})
					.addClass("eo-eb-event-box masonry-brick masonry-brick")
					.removeClass('eo-eb-event-box-hidden');
			}
			
		}
		
		//Update dom data
		$('#event-board-filters').data('filters', activeFilters.join(','));
		$(this).data('filter-on', !filterOn );
		$(this).toggleClass( 'eo-eb-filter-on', !filterOn );
			
		$('#event-board-items').masonry('reload');
	});
	
	

	//JavaScript micro-templating, similar to John Resig's implementation.
	//Underscore templating handles arbitrary delimiters, preserves whitespace,
	//and correctly escapes quotes within interpolated code.
	//This is taken from underscore.
	function eo_event_board_template_handler( text, data ){

		var escaper = /\\|'|\r|\n|\t|\u2028|\u2029/g;
		
		var settings = {
				evaluate    : /<%([\s\S]+?)%>/g,
				interpolate : /<%=([\s\S]+?)%>/g,
				escape      : /<%-([\s\S]+?)%>/g
		};
		var escapes = {
				"'":      "'",
				'\\':     '\\',
				'\r':     'r',
				'\n':     'n',
				'\t':     't',
				'\u2028': 'u2028',
				'\u2029': 'u2029'
		};
		
		var render;

		//Combine delimiters into one regular expression via alternation.
		var matcher = new RegExp([
              (settings.escape ).source,
              (settings.interpolate ).source,
              (settings.evaluate ).source
              ].join('|') + '|$', 'g');
		
		//Compile the template source, escaping string literals appropriately.
		var index = 0;
		var source = "__p+='";
		text.replace(matcher, function(match, escape, interpolate, evaluate, offset) {
			source += text.slice(index, offset).replace(escaper, function(match) { return '\\' + escapes[match]; });

			if (escape) {
				source += "'+\n((__t=(" + escape + "))==null?'':_.escape(__t))+\n'";
			}
			if (interpolate) {
				source += "'+\n((__t=(" + interpolate + "))==null?'':__t)+\n'";
			}
			if (evaluate) {
				source += "';\n" + evaluate + "\n__p+='";
			}
			index = offset + match.length;
			return match;
		});
		source += "';\n";

		//If a variable is not specified, place data values in local scope.
		if (!settings.variable) source = 'with(obj||{}){\n' + source + '}\n';
		 
		source = "var __t,__p='',__j=Array.prototype.join," +
			"print=function(){__p+=__j.call(arguments,'');};\n" +
			source + "return __p;\n";

		try {
			render = new Function(settings.variable || 'obj', '_', source);
		} catch (e) {
			e.source = source;
			throw e;
		}

		if ( data ) return render( data );

		var template = function( data ) {
			return render.call( this, data );
		};
		
		return template;
	}
	
	
	var $container = $('#event-board-items');
	var width = $container.parent().width();

	$('#event-board-more').text( eventorganiser_posterboard.loading );
	var page = 1;
	
	var template = eventorganiser_posterboard.template;
	var event_board_template = eo_event_board_template_handler( template );
	
	$.ajax({
		url: eventorganiser_posterboard.url,
		dataType: 'json',
		data:{
			action: 'eventorganiser-posterboard',
			page: page,
			query: eventorganiser_posterboard.query
		}
	}).done(function ( events ) {

		var html = '';
		for( var i=0; i< events.length; i++ ){
			var event = events[i];
			html = event_board_template( event );
			$container.append(html);
		}
		
		//If there are less than query.posts_per_page events, then we won't need this...
		if( events.length < eventorganiser_posterboard.query.posts_per_page ){
			$('#event-board-more').hide();
		}

		$container.imagesLoaded( function(){
			$container.masonry({
				isFitWidth: true,
				itemSelector : '.eo-eb-event-box',
				isAnimatedFromBottom: true,
				isAnimated: true,
				singleMode: true,
				layoutPriorities: {
					shelfOrder: 0
				}
			});
		});

		$('#event-board-more').text( eventorganiser_posterboard.load_more );
		$('#event-board-more').click( eventorganiser_fetch_events );
	});

	function eventorganiser_fetch_events(){
		page++;
		$.ajax({
			url: eventorganiser_posterboard.url,
			dataType: 'json',
			data:{
				action: 'eventorganiser-posterboard',
				page: page,
				query: eventorganiser_posterboard.query
			}
		}).done(function ( events ) {
			$('#event-board-more').text('Load more');
			var html = '';
			for( var i=0; i< events.length; i++ ){
				var event = events[i];
				html += event_board_template( event );
			}
			if( events.length < eventorganiser_posterboard.query.posts_per_page ){
				$('#event-board-more').hide();
			}

			var $box = $(html);
			var activeFilters = $('#event-board-filters').data('filters').split(',');

			if( activeFilters.length == 1 && activeFilters[0] === "" )
				activeFilters = [];

			$container.append( $box ).masonry( 'appended', $box, true );

			if( activeFilters.length > 0 ){
				var select = '#event-board .'+activeFilters.join(', #event-board .');
				$( select )
					.css({'visibility': 'hidden', 'display': 'none'})
					.removeClass("eo-eb-event-box masonry-brick masonry-brick")
					.addClass('eo-eb-event-box-hidden');
			}
			
			$container.imagesLoaded( function() {
                $container.masonry( 'reload' );
            });
			
		});
	}
});