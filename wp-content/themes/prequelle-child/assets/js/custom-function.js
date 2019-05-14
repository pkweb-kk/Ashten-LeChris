/*================================================ jQuery ============================================*/

( function( $ ) {

	'use strict';

	$( document ).ready( function() {
		$('#mobile-menu-panel .menu-item-has-children').on('click',function(){
			$(this).children('.sub-menu').toggleClass('open-menu');
		});

		$('#rent_popup_modal').on('click',function(){
			$('#memberModal').show();
		});
		$('#memberModal .close').on('click',function(){
			$('#memberModal').hide();
		});

		if($('#rent_popup_modal_m').hasClass('add_to_cart_subscriber')){
			$('.add_to_cart_subscriber').on('click',function(){
				$(".single-product .wcsatt-options-wrapper li.subscription-option input[type='radio']").trigger('click');
				$('.single-product form.cart').submit();
			});
			$('.single_add_to_cart_button').on('click',function(){
				$(".single-product .wcsatt-options-wrapper li.one-time-option input[type='radio']").trigger('click');
				$('.single-product form.cart').submit();
			});
		}else{
			$('#rent_popup_modal_m').on('click',function(){
				$('#membersModal').show();
			});
		}		

		$('#membersModal .close').on('click',function(){
			$('#membersModal').hide();
		});

		subscriptionUpdate();
		minicartupdate();

        $( document.body ).on( 'updated_cart_totals', function(){
        	subscriptionUpdate();
        	minicartupdate();
        });      

	} );

	var subscriptionUpdate = function(){
		var radioValue = $(".cart_item .wcsatt-options li input[type='radio']:checked").next().children().html();
        if(radioValue){
            $(radioValue).insertAfter(".cart_item td.product-price .wcsatt-options");
        }        
	}	

	var minicartupdate = function(){
		var cradioValue = $(".cart-container .wcsatt-options li input[type='radio']:checked").next().children().html();
        if(cradioValue){
            $(cradioValue).insertAfter(".cart-container span.cart-item-price .wcsatt-options");
        }
	}

} )( jQuery );