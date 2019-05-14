/*!
 * Mailchimp
 *
 * Wolf WPBakery Page Builder Extension 2.7.4
 */
/* jshint -W062 */
/* global WVCMailchimpParams */

var WVCMailchimp = function( $ ) {

	'use strict';

	return {

		/**
		 * Init UI
		 */
		init : function () {

			$( '.wvc-mailchimp-submit' ).on( 'click', function( event ) {
				event.preventDefault();

				var message = '',
					$submit = $( this ),
					$form = $submit.parents( '.wvc-mailchimp-form' ),
					$result = $form.find( '.wvc-mailchimp-result' ),
					list_id = $form.find( '.wvc-mailchimp-list' ).val(),
					email = $form.find( '.wvc-mailchimp-email' ).val(),
					data = {

						action : 'wvc_mailchimp_ajax',
						list_id : list_id,
						email : email
					};

				$result.animate( { 'opacity' : 0 } );

				$.post( WVCMailchimpParams.ajaxUrl, data, function( response ) {
					if ( response ) {

						message = response;

					} else {
						message = WVCMailchimpParams.unknownError;
					}

					$result.html( response ).animate( { 'opacity' : 1 } );
					setTimeout( function() {
						$result.animate( { 'opacity' : 0 } );
					}, 3000 );
				} );
			} );
		}
	};

}( jQuery );

( function( $ ) {

	'use strict';

	$( document ).ready( function() {
		WVCMailchimp.init();
	} );

} )( jQuery );