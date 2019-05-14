/*!
 * ModalWindow
 *
 * Wolf WPBakery Page Builder Extension 2.7.4
 */
/* jshint -W062 */

/* global WVC, WVCParams */
var WVCModalWindow = function( $ ) {

	'use strict';

	return {

		modalContentSelector : '#wvc-modal-window-container',
		$overlay :  $( '#wvc-modal-window-overlay' ),
		closed : true,
		open : false,
		animated : false,
		cookieName : 'wvc_mc_p',

		/**
		 * Init UI
		 */
		init : function () {

			var _this = this;

			this.cookieName = WVCParams.themeSlug + '_wvc_mc_p';

			this.popUp();
			this.closeButton();

			$( window ).on( 'wvc_fullpage_changed', function() {
				WVC.resetAOS( _this.modalContentSelector );
			} );

			WVC.resetAOS( _this.modalContentSelector );

			/**
			 * remove default opt-out link if set in content
			 */
			if ( $( '#wvc-modal-window' ).find( '.wvc-modal-window-opt-out' ).length ) {
				$( '#wvc-modal-window-bottom-close' ).remove();
			}
		},

		popUp : function () {

			var _this = this;

			if ( '1' !== WVCParams.modalWindowShowOnce ) {
				Cookies.remove( _this.cookieName, { path: '' } );
			}

			if ( '1' === WVCParams.modalWindowShowOnce && 'prevent' === Cookies.get( _this.cookieName ) ) {
				return;
			}

			if ( 'opt-out' === Cookies.get( _this.cookieName ) ) {
				return;
			}

			if ( '1' === WVCParams.modalWindowNavigateAway ) {

				$( document ).mouseleave( function () {
					_this.showPopup();
				} );

			} else {
				_this.showPopup();
			}

			/**
			 * Open window on button click
			 */
			$( document ).on( 'click', '.wvc-modal-window-open', function( event ) {

				event.preventDefault();
				_this.showPopup( 1 );
			} );
		},

		showPopup : function ( delay ) {

			var _this = this;

			if ( false === _this.closed ) {
				return;
			}

			delay = delay || WVCParams.modalWindowDelay;

			if ( ! _this.animated ) {
				WVC.delayWow( this.modalContentSelector );
				WVC.resetAOS( this.modalContentSelector );
			}

			setTimeout( function() {

				if ( _this.$overlay.length ) {

					_this.$overlay.addClass( 'wvc-modal-window-overlay-visible' );
					_this.$overlay.one( WVC.transitionEventEnd(), function() {
						_this.$overlay.addClass( 'wvc-show-modal-window' );

						$( _this.modalContentSelector ).one( WVC.transitionEventEnd(), function() {
							
							if ( ! _this.animated ) {
								WVC.doWow();
								WVC.doAOS( _this.modalContentSelector );
								_this.animated = true;
								_this.closed = false;
								window.dispatchEvent( new Event( 'resize' ) );
								window.dispatchEvent( new Event( 'scroll' ) ); // Force WOW effect
							}
						} );
					} );
				}
			}, delay );
		},

		/**
		 * Close button
		 */
		closeButton : function () {

			var _this = this,
				cookiesExpire = parseInt( WVCParams.modalWindowCookieTime, 10 );

			$( document ).on( 'click', '.wvc-modal-window-close', function( event ) {
				
				event.preventDefault();

				if ( $( this ).hasClass( 'wvc-modal-window-opt-out' ) ) {
					Cookies.set( _this.cookieName, 'opt-out', { expires: cookiesExpire, path: '/' } );
				}

				if ( '1' === WVCParams.modalWindowShowOnce ) {
					Cookies.set( _this.cookieName, 'prevent', { expires: cookiesExpire, path: '/' } );
				}

				_this.closeWindow();
			} );

			$( document ).mouseup( function( event ) {

				if ( 1 !== event.which ) {
					return;
				}

				var $container = $( '#wvc-modal-window-container, #wvc-privacy-policy-message-container' );

				if ( ! $container.is( event.target ) && $container.has( event.target ).length === 0 ) {
					_this.closeWindow();
				}
			} );
		},

		closeWindow : function() {

			var _this = this;

			_this.$overlay.removeClass( 'wvc-modal-window-overlay-visible' );
			_this.$overlay.one( WVC.transitionEventEnd(), function() {
				$( this ).removeClass( 'wvc-show-modal-window' );
				setTimeout( function() {
					WVC.delayWow( _this.modalContentSelector );
					WVC.resetAOS( _this.modalContentSelector );
					_this.closed = true;
					_this.animated = false;
				}, 200 );
			} );
		}
	};

}( jQuery );

( function( $ ) {

	'use strict';

	$( window ).on( 'page_loaded', function() {
		WVCModalWindow.init();
	} );

} )( jQuery );