/**
 * @package Tagregator
 */


/**
 * Wrapper function to safely use $
 */
function tggrWrapper( $ ) {
	var tggr = {

		/**
		 * Initialization
		 */
		init : function() {
			if ( typeof tggrData === 'undefined' ) {
				return;
			}
			
			tggr.prefix                 = 'tggr_';
			tggr.cssPrefix              = 'tggr-';
			tggr.loadingNewPosts        = '#' + tggr.cssPrefix + 'loading-new-posts';
			tggr.mediaItemContainer     = '#' + tggr.cssPrefix + 'media-item-container';
			tggr.mediaItem              = '.' + tggr.cssPrefix + 'media-item';
			tggr.existingItemIDs        = tggr.getExistingItemIDs();
			tggr.retrievalInterval      = false;
			tggr.retrievingNewItems     = false;
			tggr.loadingNewPostsVisible = tggr.isScrolledIntoView( tggr.loadingNewPosts );

			/*
			 * Enable Masonry for multi-column layouts
			 *
			 * The initial HTML/CSS layout that exists before masonry is enabled is designed to match the Masonry
			 * layout as closely as possible, so that when Masonry is enabled there won't be a dramatic visual
			 * shift that would disorient the user.
			 *
			 * There are still differences between the two layouts, though, so we initialize Masonry as soon as
			 * the DOM is ready, in order to hopefully switch to Masonry before the user has scrolled down far
			 * enough to notice the differences.
			 *
			 * That happens before images have fully loaded, though, which creates a problem. As the images load,
			 * they change the height of each item container, which causes the containers to overlap. So, we refresh
			 * the layout once more after all the images have loaded.
			 */
			if ( ! $( tggr.mediaItemContainer ).hasClass( 'one-column' ) ) {
				$( tggr.mediaItemContainer ).masonry( {
					itemSelector: tggr.mediaItem
				} );

				$( tggr.mediaItemContainer ).imagesLoaded( function() {
					$( tggr.mediaItemContainer ).masonry( 'reloadItems' );
					$( tggr.mediaItemContainer ).masonry( 'layout' );
				} );

				$( tggr.mediaItemContainer ).on( 'tggr-rendered', tggr.refreshLayout );
				$( window ).on( 'scroll', tggr.toggleRetrieval );
			}

			tggr.enableRetrieval();
		},

		/**
		 * Determines if an element is visible in the viewport
		 *
		 * Based on http://stackoverflow.com/a/488073/450127
		 * Modified to detect if the entire element is visible (rather than just part)
		 *
		 * @param {string} element
		 * @returns {boolean}
		 */
		isScrolledIntoView : function( element ) {
			var docViewTop    = $( window ).scrollTop(),
			    docViewBottom = docViewTop + $( window ).height(),
			    elementTop    = $( element ).offset().top,
			    elementBottom = elementTop + $( element ).height();

			return ( ( docViewTop < elementTop ) && ( docViewBottom > elementBottom ) );
		},

		/**
		 * Retrieve new posts and schedule automatic retrieves in the future
		 *
		 * It's possible for this to get called multiple times, so we won't set an interval if we already have one.
		 * Otherwise only one of them would get cleared and the page would continue to load new pots when we don't
		 * want it to.
		 */
		enableRetrieval : function() {
			if ( ! tggr.retrievalInterval ) {
				tggr.retrieveNewItems();
				tggr.retrievalInterval = setInterval( tggr.retrieveNewItems, tggrData.refreshInterval * 1000 );	// convert to milliseconds
			}
		},

		/**
		 * Only retrieve new posts when the user is viewing the top of Tagregator output
		 *
		 * If the user has scrolled down to the point where the masonry layout fills the screen, and we then add
		 * new items and redraw the layout, we will potentially disrupt and disorient them, and probably make them
		 * lose their place.
		 *
		 * So, when they scroll past the "Loading new posts" spinner, we stop retrieving new items. When they scroll
		 * back above it, we immediately retrieve new items and re-establish the interval to automatically load them
		 * in the future.
		 *
		 * @param {object} event
		 */
		toggleRetrieval : function( event ) {
			var newState = tggr.isScrolledIntoView( tggr.loadingNewPosts );

			if ( tggr.loadingNewPostsVisible && ! newState ) {
				clearInterval( tggr.retrievalInterval );
				tggr.retrievalInterval = false;
			} else if ( ! tggr.loadingNewPostsVisible && newState ) {
				tggr.enableRetrieval();
			}

			tggr.loadingNewPostsVisible = newState;
		},

		/**
		 * Builds an array of which item IDs are already present in the DOM
		 *
		 * @return {array}
		 */
		getExistingItemIDs : function() {
			var itemIDs = [];

			$( tggr.mediaItemContainer ).children( tggr.mediaItem ).each( function() {
				itemIDs.push( parseInt( $( this ).attr( 'id' ).replace( tggr.cssPrefix, '' ) ) );
			} );

			return itemIDs;
		},

		/**
		 * Makes an AJAX call to the server to get any new items that have been imported since the last check
		 */
		retrieveNewItems : function() {
			if ( tggr.retrievingNewItems ) {
				return;
			}

			tggr.retrievingNewItems = true;
			$( tggr.loadingNewPosts ).removeClass( tggr.cssPrefix + 'transparent' );

			$.post(
				tggrData.ajaxPostURL, {
					'action'          : tggr.prefix + 'render_latest_media_items',
					'hashtag'         : tggrData.hashtag,
					'existingItemIDs' :	tggr.existingItemIDs
				},

				function( response ) {
					if ( response.hasOwnProperty( 'success' ) && true === response.success && 0 != response.data ) {
						tggr.refreshContent( response.data );
					}

					$( tggr.loadingNewPosts ).addClass( tggr.cssPrefix + 'transparent' );
					tggr.retrievingNewItems = false;
				}
			);
		},

		/**
		 * Updates the DOM with new items that were retrieved during the last check
		 *
		 * We only update it when a refresh interval is set, though, for the same reasons described in
		 * toggleRetrieval(). Normally this won't be called if the refresh interval is cleared, but it's
		 * possible that it will be if the user scrolls to the top of the page and then scrolls back down
		 * before the AJAX request returns.
		 *
		 * @param {string} new_items_markup
		 */
		refreshContent : function( new_items_markup ) {
			var $newItems;

			if ( ! tggr.retrievalInterval ) {
				return;
			}

			$newItems = $( new_items_markup ).prependTo( tggr.mediaItemContainer );
			$( tggr.mediaItemContainer ).trigger( 'tggr-rendered', { items: $newItems } );

			$( '#' + tggr.cssPrefix + 'no-posts-available' ).hide();
			tggr.existingItemIDs = tggr.getExistingItemIDs();
		},

		/**
		 * Refresh Masonry's layout after we add new elements to the DOM.
		 *
		 * @param {object} event
		 * @param {object} data
		 */
		refreshLayout: function( event, data ) {
			$( tggr.mediaItemContainer ).masonry( 'prepended', data.items.get() );

			$( tggr.mediaItemContainer ).imagesLoaded( function() {
				$( tggr.mediaItemContainer ).masonry( 'reloadItems' );
				$( tggr.mediaItemContainer ).masonry( 'layout' );
			} );
		}
	}; // end tggr

	$( document ).ready( tggr.init );

} // end tggr_wrapper()

tggrWrapper( jQuery );
