/*!
 * Accordion
 *
 * Wolf WPBakery Page Builder Extension 2.7.4
 */
/* jshint -W062 */

var WVCAccordion = function( $ ) {

	'use strict';

	return {

		/**
		 * Init UI
		 */
		init : function () {
			$( '.wvc-accordion' ).each( function() {
				$( this ).accordion( {
					autoHeight: true,
					heightStyle: 'content'
				} );
			} );
		}
	};

}( jQuery );

( function( $ ) {

	'use strict';

	$( document ).ready( function() {
		WVCAccordion.init();
	} );

} )( jQuery );