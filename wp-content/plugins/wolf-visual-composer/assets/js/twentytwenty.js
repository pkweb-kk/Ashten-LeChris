/*!
 * Comparison Images Slider
 *
 * Wolf WPBakery Page Builder Extension 2.7.4
 */
/* jshint -W062 */
var WVCTwentyTwenty = function( $ ) {

	'use strict';

	return {

		/**
		 * Init UI
		 */
		init : function () {
			$( '.wvc-twentytwenty' ).twentytwenty();
		}
	};

}( jQuery );

( function( $ ) {

	'use strict';

	$( document ).ready( function() {
		WVCTwentyTwenty.init();
	} );

} )( jQuery );