/**
 * @file This file contains Sticky Postnox JavaScript
 * @author Enrico Sorcinelli
 * @version 1.0.0
 * @title Sticky Postbox
 */

// Make all in a closure.
;( function( $ ) {

	$( document ).ready( function() {

		/**
		 * Handle sticky postboxes.
		 *
		 */
		window.stickyPostbox = function( args ) {

			// Set defaults.
			args = $.extend( true, {
				postbox: ''
			}, args );

			var toggle_sticky = function( element ) {
				$( element ).closest( '.postbox' ).toggleClass( 'sticky-postbox-sticky' );

				// Turn on/off sortable.
				$( element ).siblings( 'h2' ).toggleClass( 'hndle' );
				$( element ).parent().siblings( 'h2' ).toggleClass( 'hndle' );

				if ( 'dashboard' === pagenow ) {
					$( element ).siblings( 'h2' ).toggleClass( 'sticky-postbox-hndle-compat' );
					$( element ).parent().siblings( 'h2' ).toggleClass( 'sticky-postbox-hndle-compat' );
				}

				// Toogle order buttons visibility.
				$( element ).siblings( 'button.handle-order-higher, button.handle-order-lower' ).toggle();
			};

			// Add sticky icon.
			$( args.postbox + '.postbox button.handlediv' ).after( '<span class="dashicons sticky-postbox-icon-sticky"></span>' );

			// Add click handler.
			$( args.postbox + '.postbox .sticky-postbox-icon-sticky' ).on( 'click', function() {

				var sticky_postboxes = [];

				// Only one sticky box at once.
				if ( ! $( this ).closest( '.postbox' ).hasClass( 'sticky-postbox-sticky' ) ) {
					toggle_sticky( $( '.postbox.sticky-postbox-sticky .sticky-postbox-icon-sticky' ) );
				}

				toggle_sticky( this );

				// Get sticky postbox.
				$( '.postbox.sticky-postbox-sticky .sticky-postbox-icon-sticky' ).closest( '.postbox' ).each( function() {
					sticky_postboxes.push( $( this ).attr( 'id' ) );
				});

				// Save status doing AJAX query.
				$.ajax({
					url: ajaxurl,
					type: 'post',
					timeout: 1000 * args.timeout,
					dataType: 'json',
					data: {
						action: 'sticky_postbox_sticky_postboxes',
						page: typenow || pagenow,
						_ajax_nonce_sticky_postbox_sticky_postboxes: sticky_postbox_i18n.nonces.sticky_postboxes,
						sticky: sticky_postboxes
					}
				});

			});

			// Restore sticky postboxes.
			if ( 'undefined' !== typeof sticky_postbox_i18n.sticky_postboxes ) {
				sticky_postbox_i18n.sticky_postboxes.forEach(
					function( el ) {
						$( '#' + el  +  ' .sticky-postbox-icon-sticky' ).trigger( 'click' );
					}
				);
			}

		};

		stickyPostbox();

	});

})( jQuery );
