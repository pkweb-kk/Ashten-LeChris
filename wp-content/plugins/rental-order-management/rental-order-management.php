<?php
/*
Plugin Name: Rental order management
Plugin URI:  http://weavers-web.com/
Description: Rental order management
Version:     1.0.0
Author:      Weavers Web
Author URI:  http://weavers-web.com/
*/


function rom_admin_actions() {
    add_submenu_page( 'woocommerce', 'Rental order', 'Rental order', 'manage_options', 'rom_admin', 'rom_submenu_page_callback' ); 
}

add_action('init', 'callback_for_setting_up_scripts');
function callback_for_setting_up_scripts() {
	// You need styling for the datepicker. For simplicity I've linked to Google's hosted jQuery UI CSS.    
    wp_register_style( 'jquery-ui', plugins_url('/css/jquery-ui.css', __FILE__), false, '1.0.0', 'all');
    wp_enqueue_style( 'jquery-ui' );
    wp_register_style( 'rom_style', plugins_url('/css/style.css', __FILE__), false, '1.0.0', 'all');
    wp_enqueue_style( 'rom_style' );
    
    // Load the datepicker script (pre-registered in WordPress).
    wp_enqueue_script( 'jquery-ui-datepicker' );          
    wp_enqueue_script( 'namespaceformyscript', plugins_url('/js/function.js', __FILE__), array( 'jquery' ) );
}

function rom_submenu_page_callback() {
	global $wpdb;

	if(!empty($_GET['rmid']) && (!empty($_GET['action']) && $_GET['action'] == 'edit')){ 

		if(isset($_POST['action']) && $_POST['action'] == 'editpost'){
			if(!empty($_POST['post_ID'])){				
				$shipping_date_picker = $_POST['shipping_date_picker'].' '.$_POST['shipping_date_hour'].':'.$_POST['shipping_date_minute'].':00';
				$return_date_picker = $_POST['return_date_picker'].' '.$_POST['return_date_hour'].':'.$_POST['return_date_minute'].':00';

				$wpdb->update( 
					'asl_rent_management_table', 
					array( 
						'shipping_date' => $shipping_date_picker,	// string
						'return_date' => $return_date_picker,	// string
						'delay_fine' => $_POST['order_penalty_amt'],	// string
						'invoice_status' => $_POST['order_send_invoice'],	// string
						'status' => $_POST['order_status'],	// string
						'return_status' => $_POST['return_status']	// string						
					), 
					array( 'ID' => $_POST['post_ID'] ), 
					array( 
						'%s',	// value1
						'%s',	// value2
						'%s',	// value3
						'%s',	// value4
						'%s',	// value5	
						'%s'	// value6					
					), 
					array( '%d' ) 
				);	

				if (!empty($_POST['order_send_invoice']) && $_POST['invoice_action_status'] == '' && $_POST['order_status'] == 'pending' && $_POST['return_status'] == 'pending' && $_POST['order_penalty_amt'] != ''){
					$client_id = $_POST['user_id'];
					$order_penalty_amt = $_POST['order_penalty_amt'];
					trigger_mail_to_client($client_id,$order_penalty_amt);
				}				
			}
		}

		$rent_managements_result = $wpdb->get_row( "SELECT * FROM asl_rent_management_table WHERE id ='".$_GET['rmid']."'"); 
		if ($rent_managements_result)
		{	
			$shipping_date_arr = explode(' ', $rent_managements_result->shipping_date);
			$shipping_hm_arr = explode(':', $shipping_date_arr[1]);
			$return_date_arr = explode(' ', $rent_managements_result->return_date);
			$return_hm_arr = explode(':', $return_date_arr[1]);
			
	?>
		<form name="post" action="<?php echo esc_html( admin_url( 'admin.php' ) ); ?>?page=rom_admin&action=edit&rmid=<?php echo $rent_managements_result->id; ?>" method="post" id="post">
			<input type="hidden" id="post-id" name="post_ID" value="<?php echo $rent_managements_result->id; ?>">
			<input type="hidden" id="invoice_action_status" name="invoice_action_status" value="<?php echo $rent_managements_result->invoice_status; ?>">
			<input type="hidden" id="user_id" name="user_id" value="<?php echo $rent_managements_result->user_id; ?>">
			<input type="hidden" id="subscription_id" name="subscription_id" value="<?php echo $rent_managements_result->membership_id; ?>">
			<input type="hidden" id="hiddenaction" name="action" value="editpost">
			<div id="poststuff">
				<div id="post-body" class="metabox-holder">
					<div id="postbox-container" class="postbox-container">
						<div id="normal-sortables" class="meta-box-sortables ui-sortable">
							<div id="woocommerce-order-data" class="postbox ">	
								<div class="inside">		
									<div class="panel-wrap woocommerce">		
										<div id="order_data" class="panel woocommerce-order-data">
											<h2 class="woocommerce-order-data__heading">
												Order #<?php echo $rent_managements_result->order_id; ?> details			
											</h2>				
											<div class="order_data_column_container">
												<div class="order_data_column">												
													<p class="form-field form-field-wide">
														<label for="order_date">Purchase Date: </label>
														<span><strong><?php echo date('M d, Y',strtotime($rent_managements_result->purchase_date)); ?></strong></span>
														<input type="hidden" name="order_date_second" value="<?php echo $rent_managements_result->purchase_date; ?>">
													</p>
												</div>
												<div class="order_data_column">												
													<p class="form-field form-field-wide">
														<label for="shipping_date_picker">Shipping Date:</label>
														<input type="text" class="date-picker datepicker" name="shipping_date_picker" maxlength="10" value="<?php echo $shipping_date_arr[0]; ?>" pattern="[0-9]{4}-(0[1-9]|1[012])-(0[1-9]|1[0-9]|2[0-9]|3[01])" id="shipping_date_picker" autocomplete="off">@‎
														<input type="number" class="hour" placeholder="h" name="shipping_date_hour" min="0" max="23" step="1" value="<?php echo $shipping_hm_arr[0]; ?>" pattern="([01]?[0-9]{1}|2[0-3]{1})">:
														<input type="number" class="minute" placeholder="m" name="shipping_date_minute" min="0" max="59" step="1" value="<?php echo $shipping_hm_arr[1]; ?>" pattern="[0-5]{1}[0-9]{1}">
													</p>
												</div>
												<div class="order_data_column">
													<p class="form-field form-field-wide wc-order-status">
														<label for="order_status">Status:</label>
														<select id="order_status" name="order_status" class="wc-enhanced-select enhanced" tabindex="-1" aria-hidden="true" <?php if($rent_managements_result->membership_id != 3){ ?>disabled=""<?php } ?>>
															<option value="">Choose status</option>
															<option value="pending" <?php if($rent_managements_result->status == 'pending'){ ?>selected="selected"<?php } ?>>Pending payment</option>
															<option value="complete" <?php if($rent_managements_result->status == 'complete'){ ?>selected="selected"<?php } ?>>Complete</option>														
														</select>													
													</p>
												</div>
												<div class="order_data_column">												
													<p class="form-field form-field-wide">
														<label for="return_date_picker">Return Date:</label>
														<input type="text" class="date-picker datepicker" name="return_date_picker" maxlength="10" value="<?php echo $return_date_arr[0]; ?>" pattern="[0-9]{4}-(0[1-9]|1[012])-(0[1-9]|1[0-9]|2[0-9]|3[01])" id="return_date_picker" autocomplete="off">@‎
														<input type="number" class="hour" placeholder="h" name="return_date_hour" min="0" max="23" step="1" value="<?php echo $return_hm_arr[0]; ?>" pattern="([01]?[0-9]{1}|2[0-3]{1})">:
														<input type="number" class="minute" placeholder="m" name="return_date_minute" min="0" max="59" step="1" value="<?php echo $return_hm_arr[1]; ?>" pattern="[0-5]{1}[0-9]{1}">													
													</p>
												</div>												
												<div class="order_data_column">
													<p class="form-field form-field-wide wc-order-status">
														<label for="order_status">Penalty($):</label>
														<input type="number" class="text" name="order_penalty_amt" min="0" value="<?php echo $rent_managements_result->delay_fine; ?>" <?php if($rent_managements_result->membership_id != 3){ ?>readonly=""<?php } ?> />
													</p>
												</div>
												<div class="order_data_column">
													<p class="form-field form-field-wide wc-order-status">
														<label for="order_status">Send Invoice:</label>
														<input type="checkbox" class="checkbox" name="order_send_invoice" value="yes" <?php if($rent_managements_result->invoice_status == 'yes'){ ?>checked="checked"<?php } ?> <?php if($rent_managements_result->membership_id != 3){ ?>readonly=""<?php } ?> />
													</p>
												</div>
												<div class="order_data_column">
													<p class="form-field form-field-wide wc-return-status">
														<label for="return_status">Return Status:</label>
														<select id="return_status" name="return_status" class="wc-enhanced-select enhanced" tabindex="-1" aria-hidden="true">
															<option value="">Choose status</option>
															<option value="pending" <?php if($rent_managements_result->return_status == 'pending'){ ?>selected="selected"<?php } ?>>Pending</option>
															<option value="return" <?php if($rent_managements_result->return_status == 'return'){ ?>selected="selected"<?php } ?>>Returned</option>														
														</select>													
													</p>
												</div>
												<div class="order_data_column">
													<p class="form-field form-field-wide wc-order-status">
														<button type="submit" class="button save_order button-primary" name="save_rental_setting" value="Update">Update</button>
													</p>
												</div>
												<div class="order_data_column"></div>
											</div>
											<div class="clear"></div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</form>
<?php	
		}		

	}else{
?>    
		<table class="wp-list-table widefat fixed striped posts">
			<thead>
				<tr>
					<td id="cb" class="manage-column column-cb check-column"><label class="screen-reader-text" for="cb-select-all-1">Select All</label><input id="cb-select-all-1" type="checkbox"></td>
					<th scope="col" id="order_number" class="manage-column column-order_number column-primary sortable desc"><a href=""><span>Order</span><span class="sorting-indicator"></span></a></th>
					<th scope="col" id="cust_name" class="manage-column column-cust_name"><span>Customer</span></th>
					<th scope="col" id="membership_dtls" class="manage-column column-membership_dtls">Membership</th>
					<th scope="col" id="purchase_date" class="manage-column column-purchase_date">Purchase Date</th>
					<th scope="col" id="shipping_date" class="manage-column column-shipping_date">Shipping Date</th>
					<th scope="col" id="return_date" class="manage-column column-return_date">Return Date</th>
					<th scope="col" id="penalty" class="manage-column column-penalty">Penalty</th>	
					<th scope="col" id="status" class="manage-column column-status">Invoice Status</th>
					<th scope="col" id="return_status" class="manage-column column-status">Return Status</th>	
					<th scope="col" id="action" class="manage-column column-action">Action</th>	
				</tr>
			</thead>
			<tbody id="the-list">
				<?php
				$rent_managements = $wpdb->get_results( "SELECT * FROM asl_rent_management_table"); 
				if ($rent_managements)
				{
					foreach ($rent_managements as $rent_management)
					{
						$current_user->membership_level = pmpro_getMembershipLevelForUser($rent_management->user_id);
						$user_info = get_userdata($rent_management->user_id);
				?>
					<tr id="post-<?php echo $rent_management->id; ?>" class="iedit author-self level-0 post-<?php echo $rent_management->id; ?> type-shop_order status-wc-processing post-password-required hentry pmpro-has-access entry clearfix entry-grid entry-columns-default no-post-thumbnail entry-shop_order entry-shop_order-grid">
						<th scope="row" class="check-column">
							<label class="screen-reader-text" for="cb-select-<?php echo $rent_management->id; ?>">Select Order – December 28, 2018 @ 02:48 PM</label>
							<input id="cb-select-<?php echo $rent_management->id; ?>" type="checkbox" name="post[]" value="<?php echo $rent_management->id; ?>">
							<div class="locked-indicator">
								<span class="locked-indicator-icon" aria-hidden="true"></span>
								<span class="screen-reader-text">“Order – December 28, 2018 @ 02:48 PM” is locked</span>
							</div>
						</th>
						<td class="order_number column-order_number has-row-actions column-primary" data-colname="Order"><a href="" class="order-view"><strong>#<?php echo $rent_management->order_id; ?></strong></a></td>
						<td class="order_number column-order_number has-row-actions column-primary"><strong><?php echo $user_info->first_name." ".$user_info->last_name; ?></strong></td>
						<td class="order_number column-order_number has-row-actions column-primary"><strong><?php echo $current_user->membership_level->name; ?></strong></td>
						<?php if($rent_management->purchase_date != '0000-00-00 00:00:00'){ ?>
						<td class="order_date column-order_date" data-colname="Date"><time datetime="<?php echo date('c',strtotime($rent_management->purchase_date)); ?>" title="<?php echo date('F j, Y, g:i a',strtotime($rent_management->purchase_date)); ?>"><?php echo date('M d, Y',strtotime($rent_management->purchase_date)); ?></time></td>	
						<?php }else{ ?>
							<td class="order_date column-order_date" data-colname="Date"><time></time></td>
						<?php } ?>
						<?php if($rent_management->shipping_date != '0000-00-00 00:00:00'){ ?>
						<td class="order_date column-order_date" data-colname="Date"><time datetime="<?php echo date('c',strtotime($rent_management->shipping_date)); ?>" title="<?php echo date('F j, Y, g:i a',strtotime($rent_management->shipping_date)); ?>"><?php echo date('M d, Y',strtotime($rent_management->shipping_date)); ?></time></td>
						<?php }else{ ?>
							<td class="order_date column-order_date" data-colname="Date"><time></time></td>
						<?php } ?>	
						<?php if($rent_management->return_date != '0000-00-00 00:00:00'){ ?>
						<td class="order_date column-order_date" data-colname="Date"><time datetime="<?php echo date('c',strtotime($rent_management->return_date)); ?>" title="<?php echo date('F j, Y, g:i a',strtotime($rent_management->return_date)); ?>"><?php echo date('M d, Y',strtotime($rent_management->return_date)); ?></time></td>
						<?php }else{ ?>
							<td class="order_date column-order_date" data-colname="Date"><time></time></td>
						<?php } ?>
						<td class="order_date column-order_date"><span><?php echo $rent_management->delay_fine; ?></span></td>	
						<td class="order_date column-order_date"><span><?php if($rent_management->invoice_status == 'yes'){ echo 'Invoice Sent'; } ?></span></td>
						<td class="order_date column-order_date"><span><?php echo $rent_management->return_status; ?></span></td>	<td class="wc_actions column-wc_actions" data-colname="Actions">
							<p><a class="button wc-action-button wc-action-button-complete complete" href="<?php echo esc_html( admin_url( 'admin.php' ) ); ?>?page=rom_admin&action=edit&rmid=<?php echo $rent_management->id; ?>" aria-label="Complete">Send Invoice</a></p>
						</td>
					</tr>
				<?php
					}
				}
				?>
			</tbody>
		</table>
<?php
	}
}
 
add_action('admin_menu', 'rom_admin_actions');

function trigger_mail_to_client($client_id, $amount){ 
	$user_info = get_userdata($client_id); 
	$to_email = $user_info->user_email;	
	$subject = 'Rental Product Penalty Invoice';
	$body = <<<EOF
<table border="0" cellpadding="0" cellspacing="0" width="600" id="template_container" style="background-color: #ffffff; border: 1px solid #dedede; box-shadow: 0 1px 4px rgba(0,0,0,0.1); border-radius: 3px;">
	<tbody>
		<tr>
			<td align="center" valign="top">
				<!-- Header -->
				<table border="0" cellpadding="0" cellspacing="0" width="600" id="template_header" style="background-color: #e0a121; color: #ffffff; border-bottom: 0; font-weight: bold; line-height: 100%; vertical-align: middle; font-family: &quot;Helvetica Neue&quot;, Helvetica, Roboto, Arial, sans-serif; border-radius: 3px 3px 0 0;">
					<tbody>
						<tr>
							<td id="header_wrapper" style="padding: 36px 48px; display: block;">
								<h1 style="color: #ffffff; font-family: &quot;Helvetica Neue&quot;, Helvetica, Roboto, Arial, sans-serif; font-size: 30px; font-weight: 300; line-height: 150%; margin: 0; text-align: left; text-shadow: 0 1px 0 #ab79a1;">$subject</h1>
							</td>
						</tr>
					</tbody>
				</table>
				<!-- End Header -->
			</td>
		</tr>
		<tr>
			<td align="center" valign="top">
				<!-- Body -->
				<table border="0" cellpadding="0" cellspacing="0" width="600" id="template_body">
					<tbody>
						<tr>
							<td valign="top" id="body_content" style="background-color: #ffffff;">
								<!-- Content -->
								<table border="0" cellpadding="20" cellspacing="0" width="100%">
									<tbody>
										<tr>
											<td valign="top" style="padding: 48px 48px 0;">
												<div id="body_content_inner" style="color: #636363; font-family: &quot;Helvetica Neue&quot;, Helvetica, Roboto, Arial, sans-serif; font-size: 14px; line-height: 150%; text-align: left;">
													<p style="margin: 0 0 16px;">Your penalty amount for product is $$amount.</p>
													<p style="margin: 0 0 16px;">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed aliquet diam a facilisis eleifend. Cras ac justo felis. Mauris faucibus, orci eu blandit fermentum, lorem nibh sollicitudin mi, sit amet interdum metus urna ut lacus.</p>
												</div>
											</td>
										</tr>
									</tbody>
								</table>
								<!-- End Content -->
							</td>
						</tr>
					</tbody>
				</table>
				<!-- End Body -->
			</td>
		</tr>
		<tr>
			<td align="center" valign="top">
				<!-- Footer -->
				<table border="0" cellpadding="10" cellspacing="0" width="600" id="template_footer">
					<tbody>
						<tr>
							<td valign="top" style="padding: 0; -webkit-border-radius: 6px;">
								<table border="0" cellpadding="10" cellspacing="0" width="100%">
									<tbody>
										<tr>
											<td colspan="2" valign="middle" id="credit" style="padding: 0 48px 48px 48px; -webkit-border-radius: 6px; border: 0; color: #c09bb9; font-family: Arial; font-size: 12px; line-height: 125%; text-align: center;">
												<p>Ashten LeChris</p>
											</td>
										</tr>
									</tbody>
								</table>
							</td>
						</tr>
					</tbody>
				</table>
				<!-- End Footer -->
			</td>
		</tr>
	</tbody>
</table>
EOF;
	$headers = array('Content-Type: text/html; charset=UTF-8','From: Ashten LeChris <kartick.karmakar@pkweb.in>');
	 
	wp_mail( $to_email, $subject, $body, $headers );
}
