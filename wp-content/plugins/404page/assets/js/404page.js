jQuery(document).ready(function($) {
  
  $( '.pp-404page-admin-notice' ).on( 'click', '.notice-dismiss', function ( event ) {
    event.preventDefault();
		data = {
			action: 'pp_404page_dismiss_admin_notice',
			pp_404page_dismiss_admin_notice: $( this ).parent().attr( 'id' )
		};
		$.post( ajaxurl, data );
		return false;
	});
 
});