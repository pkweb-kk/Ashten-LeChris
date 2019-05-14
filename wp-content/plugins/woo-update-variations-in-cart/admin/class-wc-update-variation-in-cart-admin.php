<?php
class Wc_updateVariationInCartAdmin{

	public function __construct(){

		/*------------------------Add admin menu in setting------------------------------*/
		add_action( 'admin_menu', array($this, 'WOO_CK_WUVIC_cart_variation_plugin_menu' ));

		/*--------------------------------Variation edit ajax------------------------------*/
		add_action('wp_ajax_nopriv_cart_variation_edit', array($this, 'cart_variation_edit_callback'));
		add_action('wp_ajax_cart_variation_edit', array($this, 'cart_variation_edit_callback'));

		/*--------------------------------plugin activation link filter------------------------------*/
		add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), array($this, 'add_action_links' ));
	}

	/*------------------------function to add setting menu and its page-----------------------------------*/

	public function WOO_CK_WUVIC_cart_variation_plugin_menu() {

		add_options_page( 'Cart Variation Update', 'Cart Variation Update', 'manage_options', 'woocommerce-edit-variation', array($this, 'WOO_CK_WUVIC_cart_variation_plugin_menu_option') );

	}


	public function WOO_CK_WUVIC_cart_variation_plugin_menu_option() {

		if ( !current_user_can( 'manage_options' ) )  {

			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );

		}

		 ?>

		<h1>Woocommerce Edit Variation On cart Setting</h1>

		<div class="wrap">

			<div class="col span_12" id="WOO_CK_WUVIC_form_success" style="display: none;font-size: 21px; font-style: italic;"></div>

			<form id="update_cvar_content" method="post" action="">

				<table class="form-table">

					<tbody>

						<tr>

							<th scope="row"><label for="cvar_enable">Enable</label></th>

							<td><input name="WOO_CK_WUVIC_enable" type="checkbox" id="WOO_CK_WUVIC_enable" value="enable" checked>

							<p class="description" id="cvar_enable_descr">Uncheck this box to completely disable plugin.</p>

							</td>

						</tr><!-- Enable Field -->

						<tr>

							<th scope="row"><label for="cvar_edit_link">Edit Link Text</label></th>

							<td><input name="WOO_CK_WUVIC_edit_link" type="text" id="WOO_CK_WUVIC_edit_link" value="<?php echo get_option( 'WOO_CK_WUVIC_edit_link_text' ); ?>" class="regular-text">

							<p class="description" id="cvar_link_text_descr">Text for edit link.</p>

							</td>

						</tr><!-- Edit Link Text Field -->

						<tr>

							<th scope="row"><label for="cvar_edit_link_class">Css Class For Edit Link</label></th>

							<td><input name="WOO_CK_WUVIC_edit_link_class" type="text" id="WOO_CK_WUVIC_edit_link_class" value="<?php echo get_option( 'WOO_CK_WUVIC_edit_link_class' ); ?>" class="regular-text">

							<p class="description" id="cvar_link_class_descr">Add css classes.</p>

							</td>

						</tr><!-- Css Class For Edit Link Field -->

						<tr>

							<th scope="row"><label for="cvar_update_btn">Update Button Text</label></th>

							<td><input name="WOO_CK_WUVIC_update_btn" type="text" id="WOO_CK_WUVIC_update_btn" value="<?php echo get_option( 'WOO_CK_WUVIC_update_btn_text' ); ?>" class="regular-text">

							<p class="description" id="cvar_update_btn_descr">Text for update button.</p>

							</td>

						</tr><!-- Update Button Text Field -->

						<tr>

							<th scope="row"><label for="cvar_update_btn_class">Css Class For Update Button</label></th>

							<td><input name="WOO_CK_WUVIC_update_btn_class" type="text" id="WOO_CK_WUVIC_update_btn_class" value="<?php echo get_option( 'WOO_CK_WUVIC_update_btn_class' ); ?>" class="regular-text">

							<p class="description" id="cvar_update_btn_class_descr">Add css class for update button.</p>

							</td>

						</tr><!-- Css Class For Update Button Field -->

						<tr>

							<th scope="row"><label for="cvar_cancel_btn">Cancel Button Text</label></th>

							<td><input name="WOO_CK_WUVIC_cancel_btn" type="text" id="WOO_CK_WUVIC_cancel_btn" value="<?php echo get_option( 'WOO_CK_WUVIC_cancel_btn' ); ?>" class="regular-text">

							<p class="description" id="cvar_cancel_btn_descr">Text for cancel button.</p>

							</td>

						</tr><!-- cancel Button Field -->

						<tr>

							<th scope="row"><label for="cvar_cancel_btn_class">Css Class For Cancel Button</label></th>

							<td><input name="WOO_CK_WUVIC_cancel_btn_class" type="text" id="WOO_CK_WUVIC_cancel_btn_class" value="<?php echo get_option( 'WOO_CK_WUVIC_cancel_btn_class' ); ?>" class="regular-text">

							<p class="description" id="cvar_cancel_btn_class_descr">Text for cancel button class.</p>

							</td>

						</tr><!-- Css Class For cancel Button Field -->

					</tbody>

				</table>

				<?php $WOO_CK_WUVIC_img_loader = WUVIC_WOO_UPDATE_CART_ASSESTS_URL.'img/uploading.gif'; ?>

				<img src="<?php echo $WOO_CK_WUVIC_img_loader; ?>" alt="Smiley face" height="42" width="42" 

				id="loder_img_cvform" style="display:none;">

				<p class="submit"><input type="button" name="cvar_submit" id="cvar_submit" class="button button-primary" value="Save Changes"></p>

			</form>

		</div>

		<?php

		$this->add_script_cart_variation();

	}


	/*********************  cart variation Jquery  ********************/

	public function add_script_cart_variation(){ ?>

		<script type="text/javascript">

			jQuery(document).ready(function(){

				jQuery('#cvar_submit').click(function(e){

					e.preventDefault();

					jQuery('#WOO_CK_WUVIC_form_success').css('display', 'none');

					jQuery('#loder_img_cvform').css('display','block');

					var i="yes";

					var WOO_CK_WUVIC_edit_link_text = jQuery("#WOO_CK_WUVIC_edit_link").val();

					var WOO_CK_WUVIC_edit_link_class = jQuery("#WOO_CK_WUVIC_edit_link_class").val();

					var WOO_CK_WUVIC_update_btn = jQuery("#WOO_CK_WUVIC_update_btn").val();

					var WOO_CK_WUVIC_update_btn_class = jQuery("#WOO_CK_WUVIC_update_btn_class").val();

					var WOO_CK_WUVIC_cancel_btn = jQuery("#WOO_CK_WUVIC_cancel_btn").val();

					var WOO_CK_WUVIC_cancel_btn_class = jQuery("#WOO_CK_WUVIC_cancel_btn_class").val();


					if (jQuery('input#WOO_CK_WUVIC_enable').is(':checked')) { 

						var WOO_CK_WUVIC_enable = "true";

					}else{

						var WOO_CK_WUVIC_enable = "false";

					}

					var baseUrl = 'http://workspace3.weavers-web.com/aslechris';	

					var final_sring="action=cart_variation_edit&WOO_CK_WUVIC_edit_link="+WOO_CK_WUVIC_edit_link_text+"&WOO_CK_WUVIC_edit_link_class="+WOO_CK_WUVIC_edit_link_class+"&WOO_CK_WUVIC_update_btn="+

					WOO_CK_WUVIC_update_btn+"&WOO_CK_WUVIC_update_btn_class="+WOO_CK_WUVIC_update_btn_class+"&WOO_CK_WUVIC_enable="+WOO_CK_WUVIC_enable+"&WOO_CK_WUVIC_cancel_btn="+WOO_CK_WUVIC_cancel_btn+"&WOO_CK_WUVIC_cancel_btn_class="+WOO_CK_WUVIC_cancel_btn_class; 

					var ajaxurl =baseUrl+'/wp-admin/admin-ajax.php';

					jQuery.ajax({

						type:    "POST",

						url:     ajaxurl,

						dataType: 'json',

						data:    final_sring,

						// async : false,

						success: function(data){

							jQuery('#loder_img_cvform').css('display','none');

							jQuery('#WOO_CK_WUVIC_form_success').css('display', 'block');

							jQuery('#WOO_CK_WUVIC_form_success').text('Updated Successfully');

						}

					}); 

				});

			});

		</script>

	<?php }

	public function cart_variation_edit_callback(){

		update_option( 'WOO_CK_WUVIC_status', sanitize_text_field($_POST['WOO_CK_WUVIC_enable']) );

		update_option( 'WOO_CK_WUVIC_edit_link_text', sanitize_text_field($_POST['WOO_CK_WUVIC_edit_link']) );

		update_option( 'WOO_CK_WUVIC_edit_link_class', sanitize_text_field($_POST['WOO_CK_WUVIC_edit_link_class']) );

		update_option( 'WOO_CK_WUVIC_update_btn_text',sanitize_text_field($_POST['WOO_CK_WUVIC_update_btn']) );

		update_option( 'WOO_CK_WUVIC_update_btn_class', sanitize_text_field($_POST['WOO_CK_WUVIC_update_btn_class']) );

		update_option( 'WOO_CK_WUVIC_cancel_btn',sanitize_text_field($_POST['WOO_CK_WUVIC_cancel_btn']));

		update_option( 'WOO_CK_WUVIC_cancel_btn_class', sanitize_text_field($_POST['WOO_CK_WUVIC_cancel_btn_class']) );

		die();

	}


	public function add_action_links ( $links ) {

		 $mylinks = array(

		 '<a href="' . admin_url( 'options-general.php?page=woocommerce-edit-variation' ) . '">Settings</a>',

		 );

		return array_merge( $links, $mylinks );

	}

}
new Wc_updateVariationInCartAdmin;
?>