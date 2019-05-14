<?php
/*
	This is the Prequelle child theme functions.php file.
	You can use this file to overwrite existing functions, filter and actions to customize the parent theme.
	https://wolfthemes.ticksy.com/article/11659/
*/

function prequelle_setup_child_config() {

	/**
	 *  Require the wolf themes framework core file
	 */
	//require_once get_stylesheet_directory_uri() . '/config/metaboxes.php';
}
prequelle_setup_child_config();

if ( ! function_exists( 'prequelle_child_enqueue_scripts' ) ) {
	/**
	 * Register theme scripts for the theme
	 */
	function prequelle_child_enqueue_scripts() {
		$version = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? time() : prequelle_get_theme_version();
		/*wp_enqueue_script( 'firebasejs-function', 'https://www.gstatic.com/firebasejs/5.7.2/firebase.js', array( 'jquery' ), $version, true );
		wp_enqueue_script( 'firebasefunc-function', get_stylesheet_directory_uri() . '/assets/js/firebasefunc.js', array( 'jquery' ), $version, true );*/
		wp_enqueue_script( 'custom-function', get_stylesheet_directory_uri() . '/assets/js/custom-function.js', array( 'jquery' ), $version, true );
	}
	add_action( 'wp_enqueue_scripts', 'prequelle_child_enqueue_scripts' );
}

function prequelle_child_set_navigation_mods($mods){
	$mods['navigation']['options']['extra_content_nav'] = array(
		'id' => 'extra_content_nav',
		'label' => esc_html__( 'Extra content', 'prequelle' ),
		'type' => 'text',
		'description' => esc_html__( 'Put extra content to show in header', 'prequelle' ),
	);
	return $mods;
}
add_filter( 'prequelle_customizer_mods', 'prequelle_child_set_navigation_mods',20 );	

add_action( 'init', 'remove_my_action');
function remove_my_action() {
	remove_action( 'prequelle_secondary_menu', 'prequelle_output_complementary_menu', 10);
}
/**
 * Secondary navigation hook
 *
 * Display cart icons, social icons or secondary menu depending on cuzstimizer option
 */
function prequelle_child_output_complementary_menu( $context = 'desktop' ) {

	$cta_content = prequelle_get_inherit_mod( 'menu_cta_content_type', 'none' );

	/**
	 * Force shop icons on woocommerce pages
	 */
	$is_wc_page_child = is_page() && wp_get_post_parent_id( get_the_ID() ) == prequelle_get_woocommerce_shop_page_id() && prequelle_get_woocommerce_shop_page_id();
	$is_wc = prequelle_is_woocommerce_page() || is_singular( 'product' ) || $is_wc_page_child;

	if ( apply_filters( 'prequelle_force_display_nav_shop_icons', $is_wc ) ) { // can be disable just in case
		$cta_content = 'shop_icons';
	}

	/**
	 * If shop icons are set on discography page, apply on all release pages
	 */
	$is_disco_page_child = is_page() && wp_get_post_parent_id( get_the_ID() ) == prequelle_get_discography_page_id() && prequelle_get_discography_page_id();
	$is_disco_page = is_page( prequelle_get_discography_page_id() ) || is_singular( 'release' ) || $is_disco_page_child;

	if ( $is_disco_page && get_post_meta( prequelle_get_discography_page_id(), '_post_menu_cta_content_type', true ) ) {
		$cta_content = get_post_meta( prequelle_get_discography_page_id(), '_post_menu_cta_content_type', true );
	}

	/**
	 * If shop icons are set on events page, apply on all event pages
	 */
	$is_events_page_child = is_page() && wp_get_post_parent_id( get_the_ID() ) == prequelle_get_events_page_id() && prequelle_get_events_page_id();
	$is_events_page = is_page( prequelle_get_events_page_id() ) || is_singular( 'event' ) || $is_events_page_child;

	if ( $is_events_page && get_post_meta( prequelle_get_events_page_id(), '_post_menu_cta_content_type', true ) ) {
		$cta_content = get_post_meta( prequelle_get_events_page_id(), '_post_menu_cta_content_type', true );
	}
	$extra_content_nav = prequelle_get_inherit_mod( 'extra_content_nav' ); 
	?>
	<?php if ( 'shop_icons' === $cta_content && 'desktop' === $context ) { ?>
			<?php if ( $extra_content_nav ) : ?>
				<div class="header-phone-container cta-item">
					<?php
						echo '<span><b>Call Us:</b> '.$extra_content_nav.'</span>';
					?>
				</div><!-- .search-container -->
			<?php endif; ?>			
			<?php if ( prequelle_display_cart_menu_item() ) : ?>
				<div class="cart-container cta-item">
					<?php
						/**
						 * Cart icon
						 */
						prequelle_cart_menu_item();

						/**
						 * Cart panel
						 */
						echo prequelle_cart_panel();
					?>
				</div><!-- .cart-container -->
			<?php endif; ?>
			<?php if ( prequelle_display_shop_search_menu_item() ) : ?>
				<div class="search-container cta-item">
					<?php
						/**
						 * Search
						 */
						prequelle_search_menu_item();
					?>
				</div><!-- .search-container -->
			<?php endif; ?>

	<?php } elseif ( 'search_icon' === $cta_content && 'desktop' === $context ) { ?>

		<div class="search-container cta-item">
			<?php
				/**
				 * Search
				 */
				prequelle_search_menu_item();
			?>
		</div><!-- .search-container -->

	<?php } elseif ( 'socials' === $cta_content ) {

		if ( prequelle_is_wvc_activated() && function_exists( 'wvc_socials' ) ) {
			echo wvc_socials( array( 'services' => prequelle_get_inherit_mod( 'menu_socials', 'facebook,twitter,instagram' ), ) );
		}

	} elseif ( 'secondary-menu' === $cta_content && 'desktop' === $context ) {

		prequelle_secondary_desktop_navigation();

	} elseif ( 'wpml' === $cta_content && 'desktop' === $context ) {

		do_action( 'wpml_add_language_selector' );

	} // end type
}
add_action( 'prequelle_secondary_menu', 'prequelle_child_output_complementary_menu', 15, 1 );


/**
 * Off mobile menu
 */
function prequelle_child_mobile_alt_menu() {
	$extra_content_nav = prequelle_get_inherit_mod( 'extra_content_nav' ); 
	?>		
	<div id="mobile-menu-panel">
		<a href="#" id="close-mobile-menu-icon" class="close-panel-button toggle-mobile-menu">X</a>
		<div id="mobile-menu-panel-inner">
			<div class="search-container cta-item">
				<?php
					/**
					 * Search
					 */
					prequelle_mob_search_form();
				?>
			</div><!-- .search-container -->	
			<?php if ( $extra_content_nav ) : ?>
			<div class="header-phone-container cta-item"><span><b>Call Us:</b> <?php echo $extra_content_nav; ?></span></div>
			<?php endif; ?>
			<?php
				/**
				 * Menu
				 */
				prequelle_primary_mobile_navigation();
			?>
		</div><!-- .mobile-menu-panel-inner -->
	</div><!-- #mobile-menu-panel -->
	<?php
}
add_action( 'prequelle_body_start', 'prequelle_child_mobile_alt_menu', 15 );

function prequelle_mob_search_form(){
	$cta_content = prequelle_get_inherit_mod( 'menu_cta_content_type', 'search_icon' );

	$type = ( 'shop_icons' === $cta_content ) ? 'shop' : 'blog';

	/**
	 * Force shop icons on woocommerce pages
	 */
	$is_wc_page_child = is_page() && wp_get_post_parent_id( get_the_ID() ) == prequelle_get_woocommerce_shop_page_id() && prequelle_is_woocommerce();
	$is_wc = prequelle_is_woocommerce_page() || is_singular( 'product' ) || $is_wc_page_child;

	if ( apply_filters( 'prequelle_force_display_nav_shop_icons', $is_wc ) ) {
		$type = 'shop';
	}

	if ( apply_filters( 'prequelle_force_nav_search_product', $is_wc ) ) {
		$type = 'shop';
	}

	if ( prequelle_get_inherit_mod( 'menu_cta_content_type', 'search_product_icon' ) ) {
		$type = 'shop';
	}

	if ( ! class_exists( 'WooCommerce' ) ) {
		$type = 'blog';
	}
	?>
	<div class="mob-search-form search-type-<?php echo prequelle_sanitize_html_classes( $type ); ?>">
		<?php
		if ( 'shop' === $type ) {
			if ( function_exists( 'get_product_search_form' ) ) {
				get_product_search_form();
			}
		} else {
			get_search_form();
		}
		?>
	</div>
<?php }



function cw_post_type_news() {
 
$supports = array(
'title', // post title
'editor', // post content
'author', // post author
'thumbnail', // featured images
'excerpt', // post excerpt
'custom-fields', // custom fields
'comments', // post comments
'revisions', // post revisions
'post-formats', // post formats
);
 
$labels = array(
'name' => _x('Events', 'plural'),
'singular_name' => _x('Event', 'singular'),
'menu_name' => _x('Events', 'admin menu'),
'name_admin_bar' => _x('Event', 'admin bar'),
'add_new' => _x('Add New', 'add new'),
'add_new_item' => __('Add New event'),
'new_item' => __('New event'),
'edit_item' => __('Edit event'),
'view_item' => __('View event'),
'all_items' => __('All event'),
'search_items' => __('Search event'),
'not_found' => __('No event found.'),
);
 
$args = array(
'supports' => $supports,
'labels' => $labels,
'public' => true,
'query_var' => true,
'rewrite' => array('slug' => 'event'),
'has_archive' => true,
'hierarchical' => false,
'menu_icon' => 'dashicons-calendar-alt'
);
register_post_type('event', $args);
}
add_action('init', 'cw_post_type_news');
 
/*Custom Post type end*/


function remove_parent_filters(){ //Have to do it after theme setup, because child theme functions are loaded first
	remove_filter( 'prequelle_product_add_to_cart_button', 'prequelle_output_product_add_to_cart_button' );
}
add_action( 'after_setup_theme', 'remove_parent_filters' );


/*====================================== PmPro customize area start =======================================*/

//add the fields to the form
function pmproan2c_pmpro_checkout_after_password()
{
	if(!empty($_REQUEST['first_name']))
		$first_name = $_REQUEST['first_name'];
	elseif(!empty($_SESSION['first_name']))
		$first_name = $_SESSION['first_name'];
	elseif(is_user_logged_in()) {
		$first_name = $current_user->first_name;
	}
	else
		$first_name = "";
	if(!empty($_REQUEST['last_name']))
		$last_name = $_REQUEST['last_name'];
	elseif(!empty($_SESSION['last_name']))
		$last_name = $_SESSION['last_name'];
	elseif(is_user_logged_in()) {
		$last_name = $current_user->last_name;
	}
	else
		$last_name = "";
	?>
	<div>
	<label for="first_name">First Name</label>
	<input id="first_name" name="first_name" type="text" class="input pmpro_required" size="30" value="<?php echo $first_name; ?>" />
	</div>
	<div>
	<label for="last_name">Last Name</label>
	<input id="last_name" name="last_name" type="text" class="input pmpro_required" size="30" value="<?php echo $last_name; ?>" />
	</div> 
	<?php
}
add_action('pmpro_checkout_after_password', 'pmproan2c_pmpro_checkout_after_password');
//require the fields
function pmproan2c_pmpro_registration_checks()
{
	global $pmpro_msg, $pmpro_msgt, $current_user;
	if(!empty($_REQUEST['first_name']))
		$first_name = $_REQUEST['first_name'];
	elseif(!empty($_SESSION['first_name']))
		$first_name = $_SESSION['first_name'];
	elseif(is_user_logged_in()) {
		$first_name = $current_user->first_name;
	}
	else
		$first_name = "";
	
	if(!empty($_REQUEST['last_name']))
		$last_name = $_REQUEST['last_name'];
	elseif(!empty($_SESSION['last_name']))
		$last_name = $_SESSION['last_name'];
	elseif(is_user_logged_in()) {
		$last_name = $current_user->last_name;
	}
	else
		$last_name = "";

	if($first_name && $last_name || $current_user->ID)
	{
		//all good
		return true;
	}
	else
	{
		$pmpro_msg = "Please complete all required fields.";
		$pmpro_msgt = "pmpro_error";
		return false;
	}
}
add_filter("pmpro_registration_checks", "pmproan2c_pmpro_registration_checks");
//update the user after checkout
function pmproan2c_update_first_and_last_name_after_checkout($user_id)
{
	global $current_user;
	if(!empty($_REQUEST['first_name']))
		$first_name = $_REQUEST['first_name'];
	elseif(!empty($_SESSION['first_name']))
		$first_name = $_SESSION['first_name'];
	elseif(is_user_logged_in()) {
		$first_name = $current_user->first_name;
	}
	else
		$first_name = "";
	
	if(!empty($_REQUEST['last_name']))
		$last_name = $_REQUEST['last_name'];
	elseif(!empty($_SESSION['last_name']))
		$last_name = $_SESSION['last_name'];
	elseif(is_user_logged_in()) {
		$last_name = $current_user->last_name;
	}
	else
		$last_name = "";
	update_user_meta($user_id, "first_name", $first_name);
	update_user_meta($user_id, "last_name", $last_name);
}
add_action('pmpro_after_checkout', 'pmproan2c_update_first_and_last_name_after_checkout');
function pmproan2c_pmpro_paypalexpress_session_vars()
{
	//save our added fields in session while the user goes off to PayPal
	$_SESSION['first_name'] = $_REQUEST['first_name'];
	$_SESSION['last_name'] = $_REQUEST['last_name'];
}
add_action("pmpro_paypalexpress_session_vars", "pmproan2c_pmpro_paypalexpress_session_vars");

add_filter("pmpro_checkout_confirm_email", "__return_false");  //Don't show confirm email fields on the checkout page.



/*
	Add the PMPro meta box to a CPT
*/
function aslechris_pmpro_meta_wrapper()
{
	//duplicate this row for each CPT
	add_meta_box('pmpro_page_meta', 'Require Membership', 'pmpro_page_meta', 'product', 'side');	
	add_meta_box('pmpro_page_meta', 'Require Membership', 'pmpro_page_meta', 'tribe_events', 'side');	
}
function aslechris_pmpro_cpt_init()
{
	if (is_admin())
	{
		add_action('admin_menu', 'aslechris_pmpro_meta_wrapper');
	}
}
add_action("init", "aslechris_pmpro_cpt_init", 20);


function aslechris_pmpro_init()
{
    //don't break if Register Helper is not loaded
    if(!function_exists("pmprorh_add_registration_field"))
    {
        return false;
    }
    //define the fields
    $fields = array();
    $fields[] = new PMProRH_Field(
        "upload_id_copy",              // input name, will also be used as meta key
        "file",                 // type of field
        array(
        	"required" => true, 
        	"showrequired" => true,
        	"accept" => "image/*",
            "label"=>"Upload ID Card",
            "hint"=>"Accept image only",
            "profile"=>true,    // show in user profile
        ));      
    //add the fields into a new checkout_boxes are of the checkout page
    foreach($fields as $field)
        pmprorh_add_registration_field(
            "checkout_boxes", // location on checkout page
            $field            // PMProRH_Field object
        );
    //that's it. see the PMPro Register Helper readme for more information and examples.
}
//add_action("init", "aslechris_pmpro_init");


function aslechris_pmpro_email_as_username()
{
  //check for level as well to make sure we're on checkout page
  if(empty($_REQUEST['level']))
    return;
  
  if(!empty($_REQUEST['bemail']))
    $_REQUEST['username'] = $_REQUEST['bemail'];
    
  if(!empty($_POST['bemail']))
    $_POST['username'] = $_POST['bemail'];
    
  if(!empty($_GET['bemail']))
    $_GET['username'] = $_GET['bemail'];
}
add_action('init', 'aslechris_pmpro_email_as_username');


//Log out the user after checkout and redirect them to a custom URL
function aslechris_pmpro_after_checkout($user_id)
{
	wp_logout();
}

add_action('pmpro_after_checkout', 'aslechris_pmpro_after_checkout');

function aslechris_pmpro_confirmation_url($rurl, $user_id, $pmpro_level)
{
	$rurl = get_permalink( get_option('woocommerce_myaccount_page_id') );	//change this URL
	return $rurl;
}

add_filter('pmpro_confirmation_url', 'aslechris_pmpro_confirmation_url', 10, 3);


function aslechris_pmpro_registration_upload_id($pmpro_continue_registration){ 

}

//add_filter( "pmpro_registration_checks",  'aslechris_pmpro_registration_upload_id',10,1);


function my_pmpro_member_links_top()
{
?>
<li><a href="<?php echo esc_url( wc_get_account_endpoint_url( 'payment-methods' ) ); ?>">Change payment details</a></li>
<li><a href="<?php echo esc_url( wc_get_account_endpoint_url( 'edit-address' ) ); ?>">Edit billing and shipping address</a></li>
<?php
}
add_action("pmpro_member_links_top", "my_pmpro_member_links_top");

/*
	Shortcode to show account navigation menu
*/
function woo_navigation_menu($atts, $content=null, $code=""){
	extract(shortcode_atts(array(		
		'sections' => 'membership,profile,invoices,links'		
	), $atts));

	$sections = array_map('trim',explode(",",$sections));	
	ob_start();

	do_action( 'woocommerce_before_account_navigation' );
	?>
	<div class="my-account">
		<div class="woocommerce-nav">
			<nav class="woocommerce-MyAccount-navigation">
				<ul>
					<?php foreach ( wc_get_account_menu_items() as $endpoint => $label ) : ?>
						<li class="<?php echo wc_get_account_menu_item_classes( $endpoint ); ?>">
							<a href="<?php echo esc_url( wc_get_account_endpoint_url( $endpoint ) ); ?>"><?php echo esc_html( $label ); ?></a>
						</li>
					<?php endforeach; ?>
				</ul>
			</nav>
		</div>
	</div>
	<?php do_action( 'woocommerce_after_account_navigation' );

	$content = ob_get_contents();
	ob_end_clean();
	
	return $content; 
}

add_shortcode('woo_account_nav', 'woo_navigation_menu');

/*====================================== PmPro customize area end =======================================*/

/*====================================== Woocommerce customize area start =======================================*/

// Register Custom Taxonomy Type, Classic & Texture
function aslechris_woo_taxonomy_item()  {

	$type_labels = array(
	    'name'                       => 'Type',
	    'singular_name'              => 'Type',
	    'menu_name'                  => 'Types',
	    'all_items'                  => 'All Types',
	    'parent_item'                => 'Parent Type',
	    'parent_item_colon'          => 'Parent Type:',
	    'new_item_name'              => 'New Type Name',
	    'add_new_item'               => 'Add New Type',
	    'edit_item'                  => 'Edit Type',
	    'update_item'                => 'Update Type',
	    'separate_items_with_commas' => 'Separate Type with commas',
	    'search_items'               => 'Search Types',
	    'add_or_remove_items'        => 'Add or remove Types',
	    'choose_from_most_used'      => 'Choose from the most used Types',
	);
	$type_args = array(
	    'labels'                     => $type_labels,
	    'hierarchical'               => true,
	    'public'                     => true,
	    'show_ui'                    => true,
	    'show_admin_column'          => false,
	    'show_in_nav_menus'          => true,
	    'show_tagcloud'              => true,
	);
	register_taxonomy( 'type', 'product', $type_args );

	$texture_labels = array(
	    'name'                       => 'Texture',
	    'singular_name'              => 'Texture',
	    'menu_name'                  => 'Texture',
	    'all_items'                  => 'All Texture',
	    'parent_item'                => 'Parent Texture',
	    'parent_item_colon'          => 'Parent Texture:',
	    'new_item_name'              => 'New Texture Name',
	    'add_new_item'               => 'Add New Texture',
	    'edit_item'                  => 'Edit Texture',
	    'update_item'                => 'Update Texture',
	    'separate_items_with_commas' => 'Separate Texture with commas',
	    'search_items'               => 'Search Texture',
	    'add_or_remove_items'        => 'Add or remove Texture',
	    'choose_from_most_used'      => 'Choose from the most used Texture',
	);
	$texture_args = array(
	    'labels'                     => $texture_labels,
	    'hierarchical'               => true,
	    'public'                     => true,
	    'show_ui'                    => true,
	    'show_admin_column'          => false,
	    'show_in_nav_menus'          => true,
	    'show_tagcloud'              => true,
	);
	register_taxonomy( 'texture', 'product', $texture_args );

	$classic_labels = array(
	    'name'                       => 'Classic',
	    'singular_name'              => 'Classic',
	    'menu_name'                  => 'Classic',
	    'all_items'                  => 'All Classic',
	    'parent_item'                => 'Parent Classic',
	    'parent_item_colon'          => 'Parent Classic:',
	    'new_item_name'              => 'New Classic Name',
	    'add_new_item'               => 'Add New Classic',
	    'edit_item'                  => 'Edit Classic',
	    'update_item'                => 'Update Classic',
	    'separate_items_with_commas' => 'Separate Classic with commas',
	    'search_items'               => 'Search Classic',
	    'add_or_remove_items'        => 'Add or remove Classic',
	    'choose_from_most_used'      => 'Choose from the most used Classic',
	);
	$classic_args = array(
	    'labels'                     => $classic_labels,
	    'hierarchical'               => true,
	    'public'                     => true,
	    'show_ui'                    => true,
	    'show_admin_column'          => false,
	    'show_in_nav_menus'          => true,
	    'show_tagcloud'              => true,
	);
	register_taxonomy( 'classic', 'product', $classic_args );

}

add_action( 'init', 'aslechris_woo_taxonomy_item', 0 );

// Make product quantity field text to dropdown
function woocommerce_quantity_input($data = null) {
    global $product;
    $defaults = array(
        'input_name'    => 'quantity',
        'input_value'   => '1',
        'max_value'     => apply_filters( 'woocommerce_quantity_input_max', '', $product ),
        'min_value'     => apply_filters( 'woocommerce_quantity_input_min', '', $product ),
        'step'      => apply_filters( 'woocommerce_quantity_input_step', '1', $product ),
        'style'     => apply_filters( 'woocommerce_quantity_style', 'float:left; margin-right:10px;', $product )
    );
    if ( ! empty( $defaults['min_value'] ) )
        $min = $defaults['min_value'];
    else $min = 1;
    if ( ! empty( $defaults['max_value'] ) )
        $max = $defaults['max_value'];
    else $max = 10;
    if ( ! empty( $defaults['step'] ) )
        $step = $defaults['step'];
    else $step = 1;
    $options = '';

    for ( $count = $min; $count <= $max; $count = $count+$step ) { 
        $selected = ($count === $data['input_value']) ? ' selected' : '';
        $options .= '<option value="' . $count . '"'.$selected.'>' . $count . ''; }

    echo '<div class="quantity_select" style="' . $defaults['style'] . '"><select name="' . esc_attr( $defaults['input_name'] ) . '" title="' . _x( 'Qty', 'Product quantity input tooltip', 'woocommerce' ) . '" class="qty">' . $options . '</select></div>';
}


/**
 * Listing Product Add to cart text change button
 *
 * @param string $string
 * @return string
 */
function prequelle_child_output_product_add_to_cart_button() {
	global $product;

	if ( $product->is_type( 'variable' ) ) {
		echo '<a class="product-quickview-button" href="' . esc_url( get_permalink() ) . '"><span class="fa quickview-product-add-to-cart-icon" title="' . esc_attr( __( 'Select option', 'prequelle' ) ). '"></span></a>';
	} elseif ( $product->is_type( 'external' ) || $product->is_type( 'grouped' ) || $product->is_type( 'simple' ) ) {
		echo '<a class="product-quickview-button" href="' . esc_url( get_permalink() ) . '"><span class="fa quickview-product-add-to-cart-icon" title="' . esc_attr( __( 'View product', 'prequelle' ) ). '"></span></a>';
	}/* else {
		echo '<a class="product-quickview-button" href="' . esc_url( get_permalink() ) . '"><span class="fa quickview-product-add-to-cart-icon" title="' . esc_attr( __( 'View product', 'prequelle' ) ). '"></span></a>';
	}*/	
}

add_filter( 'prequelle_product_add_to_cart_button', 'prequelle_child_output_product_add_to_cart_button' );


// Changing "Default Sorting" to "Recommended sorting" on shop and product settings pages
function aslechris_woo_update_sorting_name( $catalog_orderby ) {
	$catalog_orderby = str_replace("Default sorting", "Sort by", $catalog_orderby);
	$catalog_orderby = str_replace("Sort by popularity", "The most popular", $catalog_orderby);
	$catalog_orderby = str_replace("Sort by latest", "The latest style", $catalog_orderby);
	$catalog_orderby = str_replace("Sort by price: low to high", "Price low-high", $catalog_orderby);
	$catalog_orderby = str_replace("Sort by price: high to low", "Price high-low", $catalog_orderby);
	return $catalog_orderby;
}
add_filter( 'woocommerce_catalog_orderby', 'aslechris_woo_update_sorting_name' );
add_filter( 'woocommerce_default_catalog_orderby_options', 'aslechris_woo_update_sorting_name' );


//Adding Alphabetical sorting option to shop and product settings pages
function aslechris_woo_alphabetical_shop_ordering( $sort_args ) {
	$orderby_value = isset( $_GET['orderby'] ) ? woocommerce_clean( $_GET['orderby'] ) : apply_filters( 'woocommerce_default_catalog_orderby', get_option( 'woocommerce_default_catalog_orderby' ) );
	if ( 'alphabetical' == $orderby_value ) {
		$sort_args['orderby'] = 'title';
		$sort_args['order'] = 'asc';
		$sort_args['meta_key'] = '';
	}
	return $sort_args;
}
add_filter( 'woocommerce_get_catalog_ordering_args', 'aslechris_woo_alphabetical_shop_ordering' );

function aslechris_woo_catalog_orderby( $sortby ) {
	$sortby['alphabetical'] = 'Name A-Z';
	unset($sortby['rating']);
	return $sortby;
}
add_filter( 'woocommerce_default_catalog_orderby_options', 'aslechris_woo_catalog_orderby' );
add_filter( 'woocommerce_catalog_orderby', 'aslechris_woo_catalog_orderby' );


// define the woocommerce_sidebar callback 
function action_woocommerce_sidebar( $woocommerce_get_sidebar, $int ) { 
    // make action magic happen here... 
    return $woocommerce_get_sidebar;
}; 
         
// add the action 
add_action( 'woocommerce_sidebar', 'action_woocommerce_sidebar', 10, 2 ); 


/**
 * Exclude products from a particular category on the shop page
 */
function aslechris_woo_pre_get_posts_query( $q ) {
    $tax_query = (array) $q->get( 'tax_query' );
    $tax_query[] = array(
           'taxonomy' => 'product_tag',
           'field' => 'slug',
           'terms' => array( 'rent_gold' ), // Don't display products in the clothing category on the shop page.
           'operator' => 'NOT IN'
    );
    $q->set( 'tax_query', $tax_query );
}

function aslechris_woo_pre_get_posts_query_gold( $q ) {
    $tax_query = (array) $q->get( 'tax_query' );
    $tax_query[] = array(
           'taxonomy' => 'product_tag',
           'field' => 'slug',
           'terms' => array( 'rent_other' ), // Don't display products in the clothing category on the shop page.
           'operator' => 'NOT IN'
    );
    $q->set( 'tax_query', $tax_query );
}

function aslechris_woo_pre_get_posts_query_othr( $q ) {
    $tax_query = (array) $q->get( 'tax_query' );
    $tax_query[] = array(
           'taxonomy' => 'product_tag',
           'field' => 'slug',
           'terms' => array( 'rent_gold' ), // Don't display products in the clothing category on the shop page.
           'operator' => 'NOT IN'
    );
    $q->set( 'tax_query', $tax_query );
}

function aslechris_woo_product_query(){
	if(is_user_logged_in() && function_exists('pmpro_hasMembershipLevel') && pmpro_hasMembershipLevel()){	
		if (pmpro_hasMembershipLevel('3')) {
			add_action( 'woocommerce_product_query', 'aslechris_woo_pre_get_posts_query_gold' );
		}elseif (pmpro_hasMembershipLevel(array('1','2'))) {
			add_action( 'woocommerce_product_query', 'aslechris_woo_pre_get_posts_query_othr' );
		}
	}else{
		add_action( 'woocommerce_product_query', 'aslechris_woo_pre_get_posts_query' );	
	}
}
add_action( 'init', 'aslechris_woo_product_query' );


function exclude_terms_from_related_products( $query ) {
    global $wpdb;    

    if(is_user_logged_in() && function_exists('pmpro_hasMembershipLevel') && pmpro_hasMembershipLevel()){	
		if (pmpro_hasMembershipLevel('3')) {
			$exclude_term_ids[0] = ''; // term_id 
		}elseif (pmpro_hasMembershipLevel(array('1','2'))) {
			$exclude_term_ids[0] = 102; // term_id 
		}
	}else{
		$exclude_term_ids[0] = 102; // term_id 
	}

	$query['join']  .= " LEFT JOIN ( SELECT object_id FROM {$wpdb->term_relationships} WHERE term_taxonomy_id IN ( " . implode( ',', array_map( 'absint', $exclude_term_ids ) ) . ' ) ) AS exclude_join1 ON exclude_join1.object_id = p.ID';
    $query['where'] .= ' AND exclude_join1.object_id IS NULL';

    return $query;
}
add_filter( 'woocommerce_product_related_posts_query', 'exclude_terms_from_related_products' );


//Subscription product pricing change
function wc_subscriptions_custom_price_string( $pricestring ) {    
    if(is_user_logged_in() && function_exists('pmpro_hasMembershipLevel') && pmpro_hasMembershipLevel())
	{
		global $current_user;
		$current_user->membership_level = pmpro_getMembershipLevelForUser($current_user->ID);		
		if(($current_user->membership_level->ID == 1 || $current_user->membership_level->ID == 2)){
    		//$pricestring = '<span class="woocommerce-Price-amount amount">$0.00</span>';
    		$pricestring = '<span class="woocommerce-Price-amount amount">'.$pricestring.'</span>';
    	}elseif(($current_user->membership_level->ID == 3)){
    		$pricestring = '<span class="woocommerce-Price-amount amount">'.$pricestring.'</span>';
    	}
	} 
	return $pricestring;
}
add_filter( 'woocommerce_subscriptions_product_price_string', 'wc_subscriptions_custom_price_string' );
add_filter( 'woocommerce_subscription_price_string', 'wc_subscriptions_custom_price_string' );

add_filter( 'woocommerce_get_price_html', 'bbloomer_price_free_zero_empty', 100, 2 );
  
function bbloomer_price_free_zero_empty( $price, $product ){
	 if(is_user_logged_in() && function_exists('pmpro_hasMembershipLevel') && pmpro_hasMembershipLevel())
	{
		global $current_user;
		$current_user->membership_level = pmpro_getMembershipLevelForUser($current_user->ID);
		$tagids = $product->get_tag_ids();
		if(($current_user->membership_level->ID == 1 || $current_user->membership_level->ID == 2) && (in_array(101, $tagids))){
    		//$price = '<span class="woocommerce-Price-amount amount">$0.00</span>';
    		$price = '<span class="woocommerce-Price-amount amount">'.$price.'</span>';
    	}elseif(($current_user->membership_level->ID == 3) && (in_array(101, $tagids))){
    		$price = '<span class="woocommerce-Price-amount amount">'.$price.'</span>';
    	}
	} 
	return $price;
}

// If membership id is 1 and 2 then particular tagged product cost will be 0.
function add_cart_item_data( $cart_item_data, $product_id, $variation_id ) {
	 // Has our option been selected?
	 if(is_user_logged_in() && function_exists('pmpro_hasMembershipLevel') && pmpro_hasMembershipLevel())
	{
		global $current_user;
		$current_user->membership_level = pmpro_getMembershipLevelForUser($current_user->ID);
		$product = wc_get_product( $product_id );
		$tagids = $product->get_tag_ids();

		if(($current_user->membership_level->ID == 1 || $current_user->membership_level->ID == 2) && (in_array(101, $tagids))){			
			$price = $product->get_price();
			// Store the overall price for the product, including the cost of the warranty
			$cart_item_data['membership_price'] = $price - $price;
		}
	}
	 return $cart_item_data;
}
//add_filter( 'woocommerce_add_cart_item_data', 'add_cart_item_data', 10, 3 );


function before_calculate_totals( $cart_obj ) {
	if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
	 return;
	}
	 // Iterate through each cart item
	foreach( $cart_obj->get_cart() as $key=>$value ) {
		if( isset( $value['membership_price'] ) ) {
			 $price = $value['membership_price'];
			 $value['data']->set_price( ( $price ) );
		}
 	}
}
//add_action( 'woocommerce_before_calculate_totals', 'before_calculate_totals', 10, 1 );

// Add cart validation for membership user for rental product 
function aslechris_woo_add_the_user_validation( $passed, $product_id, $quantity ) { 
	global $wpdb, $current_user;
	if (!empty( $product_id )) {
		if($_POST['convert_to_sub_'.$product_id] != 0){
			$mravailabality = get_member_rental_availabality($current_user->ID);
			$tag_arr = get_product_tag_array($product_id);	
		    if (in_array('rental', $tag_arr)) {
		    	$flag = 'true';	    	
		    }
		    if ($flag === 'true' && $mravailabality == 'false') {
		    	wc_add_notice( __( 'You can rent a new product after return all.', 'woocommerce' ), 'error' );
		    	$passed = false;
		    }elseif ($flag === 'true' && $mravailabality == 'mfalse') {
		    	wc_add_notice( __( 'You are eligible for one rental a month', 'woocommerce' ), 'error' );
		    	$passed = false;
		    }	
	    }    
	}
	return $passed;
}
add_filter( 'woocommerce_add_to_cart_validation', 'aslechris_woo_add_the_user_validation', 10, 5 );


add_filter('woocommerce_is_purchasable', 'aslechris_woo_product_purchasable');
function aslechris_woo_product_purchasable($cloudways_purchasable, $product) {		
	return ($product->id == 6961 ? false : $cloudways_purchasable);	
}

// get an array of all tags of a particular product
function get_product_tag_array($product_id){	
	$terms = get_the_terms( $product_id, 'product_tag' );
	$term_array = array();
	if ( ! empty( $terms ) && ! is_wp_error( $terms ) ){
	    foreach ( $terms as $term ) {
	        $term_array[] = $term->name;
	    }
	}
	return $term_array;
}
// return true/false of a member rental status 
function get_member_rental_availabality($user_id){
	global $wpdb, $current_user;
	$mem_data_arr = $wpdb->get_row( "SELECT * FROM asl_rent_management_table WHERE user_id =".$user_id." AND return_status = 'pending'", ARRAY_A );
	if(!empty($mem_data_arr)) {
		$membership_id = $mem_data_arr['membership_id'];	
		if ($membership_id == 1) {
			return 'false';
		}elseif ($membership_id == 2) {			
			return 'false';
		}elseif ($membership_id == 3) {
			return 'false';
		}
	}else{
		$current_user->membership_level = pmpro_getMembershipLevelForUser($current_user->ID);
		if($current_user->membership_level->ID == 2){
			$member_previous_purchase = $wpdb->get_var( "SELECT count(*) FROM asl_rent_management_table WHERE`user_id` = ".$user_id." AND purchase_date BETWEEN (CURRENT_DATE() - INTERVAL 1 MONTH) AND CURRENT_DATE()"); 
			if ($member_previous_purchase > 0) {
				return 'mfalse';
			}
			return 'true';
		}		
	}
	return 'true';
}

// update rent table data after a rental product purchased
function aslechris_woocommerce_payment_complete( $order_id ) {
	global $wpdb, $current_user;

	if(is_user_logged_in() && function_exists('pmpro_hasMembershipLevel') && pmpro_hasMembershipLevel()){
		$flag = 'false';
		$order = wc_get_order( $order_id );
		$items = $order->get_items();
		foreach ( $items as $item ) {		    
		    $product_id = $item->get_product_id();
		    $tag_arr = get_product_tag_array($product_id);	
		    if (in_array('rental', $tag_arr)) {
    	    	$flag = 'true';
    	    	break;
    	    }	    
		}

		$current_user->membership_level = pmpro_getMembershipLevelForUser($current_user->ID);
		
		if($flag === 'true'){
			$wpdb->insert( 
				'asl_rent_management_table', 
				array( 
					'order_id' => $order_id, 
					'user_id' => $current_user->ID,
					'membership_id' => $current_user->membership_level->ID,
					'purchase_date' => date('Y-m-d h:i:s'),
					'invoice_status' => 'no',
					'return_status'	=> 'pending'  
				), 
				array( 
					'%d', 
					'%d',
					'%d',
					'%s',
					'%s',
					'%s' 
				) 
			);	
		}
	}	
    error_log( "Payment has been received for order $order_id" );
}
add_action( 'woocommerce_payment_complete', 'aslechris_woocommerce_payment_complete', 10, 1 );

// show/hide menu of default woocommerce account page
add_filter ( 'woocommerce_account_menu_items', 'aslechris_remove_my_account_links' );
function aslechris_remove_my_account_links( $menu_links ){
	unset( $menu_links['edit-address'] ); // Addresses
	unset( $menu_links['dashboard'] ); // Remove Dashboard
	unset( $menu_links['payment-methods'] ); // Remove Payment Methods
	//unset( $menu_links['orders'] ); // Remove Orders
	unset( $menu_links['downloads'] ); // Disable Downloads
	unset( $menu_links['subscriptions'] ); // Disable subscriptions
	//unset( $menu_links['edit-account'] ); // Remove Account details tab
	//unset( $menu_links['customer-logout'] ); // Remove Logout link
 	$menu_links['edit-account'] = 'My profile';
 	$menu_links['orders'] = 'My orders';

	return $menu_links;
 
}

// Add custom menu in woocommerce my account page menu
add_filter ( 'woocommerce_account_menu_items', 'aslechris_one_more_link' );
function aslechris_one_more_link( $menu_links ){
	global $current_user;
	$mravailabality = get_member_rental_availabality($current_user->ID);	
	$new = array( 'myevents' => 'My events', 'accountsettings' => 'Account settings');

	/*if ($mravailabality == 'false') {
		//$new = array( 'membershipinvoice' => 'Membership Invoice');
	}else{
	//	$new = array( 'membershipcancel' => 'Membership Cancel', 'membershipinvoice' => 'Membership Invoice');
	}	*/
 	 
	// array_slice() is good when you want to add an element between the other ones
	$menu_links = array_slice( $menu_links, 0, 1, true ) 
	+ $new 
	+ array_slice( $menu_links, 1, NULL, true );
 
 
	return $menu_links;
}
 
add_filter( 'woocommerce_get_endpoint_url', 'aslechris_hook_endpoint', 10, 4 );
function aslechris_hook_endpoint( $url, $endpoint, $value, $permalink ){
 	
 	if( $endpoint === 'myevents' ) {
		// ok, here is the place for your custom URL, it could be external
		$url = get_permalink(7107);
 
	}
	if( $endpoint === 'accountsettings' ) {
		// ok, here is the place for your custom URL, it could be external
		$url = get_permalink(6757);
 
	}

	if( $endpoint === 'dashboard' ) {
		// ok, here is the place for your custom URL, it could be external
		$url = get_permalink(6220);
 
	}

	/*if( $endpoint === 'membershipcancel' ) {
		// ok, here is the place for your custom URL, it could be external
		$url = get_permalink(6759);
 
	}
	if( $endpoint === 'membershipinvoice' ) {
		// ok, here is the place for your custom URL, it could be external
		$url = get_permalink(6762);
 
	}*/

	return $url;
}

//add_filter( 'init', 'aslechris_test_mail' );
function aslechris_test_mail(){
	//$to_email = 'kartick.karmakar@pkweb.in';
	$to_email = 'kartick008karmakar@gmail.com';
	$subject = 'Rental Product Penalty Invoice';
	$body = 'Rental Product Penalty Invoice body 11';
	$headers = array('Content-Type: text/html; charset=UTF-8','From: Ashten LeChris <kartick.karmakar@pkweb.in>');	 
	echo wp_mail( $to_email, $subject, $body, $headers );
}

// Remove field from woocommerce chackout
add_filter( 'woocommerce_checkout_fields' , 'aslechris_override_checkout_fields' ); 
function aslechris_override_checkout_fields( $fields ) {
	unset($fields['billing']['billing_company']);
	 
	return $fields;
}

//Add column in order table Admin section
add_filter('manage_edit-shop_order_columns', 'aslechris_membership_column', 15);
function aslechris_membership_column($columns) {
    $new_columns = (is_array($columns)) ? $columns : array();    
    //add custom column
    $new_columns['customer_membership'] = __( 'Membership', 'woocommerce' );

    return $new_columns;
}

add_action( 'manage_shop_order_posts_custom_column' , 'aslechris_orders_list_column_content', 50, 2 );
function aslechris_orders_list_column_content( $column, $post_id ) {
    if ( $column == 'customer_membership' )
    {             
        $order = wc_get_order($post_id);	    
	    $user_id = $order->get_user_id(); // or $order->get_customer_id();
		$membership_level = pmpro_getMembershipLevelForUser($user_id);

        if($membership_level->name){            
            echo '<span>' .$membership_level->name.'</span>';
        }        
    }
}

// add modal/popup for different members and non members in product details page
function single_product_add_rental_button($product_id){
	if(!is_user_logged_in())
	{
		add_action( 'woocommerce_after_add_to_cart_button', 'aslechris_woo_add_custom_button_non_members', 10, 0 );	
		//add_filter( 'woocommerce_is_purchasable', '__return_false');	
	}elseif(is_user_logged_in() && !function_exists('pmpro_hasMembershipLevel') && !pmpro_hasMembershipLevel()){		
		add_action( 'woocommerce_after_add_to_cart_button', 'aslechris_woo_add_custom_button_members', 10, 0 );	
		//add_filter( 'woocommerce_is_purchasable', '__return_false');		
	}else{
		$product_tag_array = get_product_tag_array($product_id);
		if(in_array('rental',$product_tag_array)) {		 			 	
			if (pmpro_hasMembershipLevel('3')) {
				if(!in_array('membership_gold',$product_tag_array)) {					
					add_action( 'woocommerce_after_add_to_cart_button', 'aslechris_woo_add_custom_button_members', 10, 0 );	
					//add_filter( 'woocommerce_is_purchasable', '__return_false');
				}else{
					//add_action( 'woocommerce_product_single_add_to_cart_text', 'aslechris_woo_add_custom_hide_btn', 10, 0 );
					add_action( 'woocommerce_after_add_to_cart_button', 'aslechris_woo_add_custom_hide_btn', 10, 0 );	
				}
			}elseif (pmpro_hasMembershipLevel(array('1','2'))) {
				if(!in_array('membership_black_diamond',$product_tag_array)) {
					add_action( 'woocommerce_after_add_to_cart_button', 'aslechris_woo_add_custom_button_members', 10, 0 );	
					//add_filter( 'woocommerce_is_purchasable', '__return_false');
				}else{
					//add_action( 'woocommerce_product_single_add_to_cart_text', 'aslechris_woo_add_custom_hide_btn', 10, 0 );
					add_action( 'woocommerce_after_add_to_cart_button', 'aslechris_woo_add_custom_hide_btn', 10, 0 );	
				}
			}			
		}		
	}	
}

function aslechris_woo_add_custom_hide_btn() {
	//return __('Rent Product', 'woocommerce');
	if(function_exists('pmpro_hasMembershipLevel') && pmpro_hasMembershipLevel()){
		$class="add_to_cart_subscriber";
	}else{
		$class="";
	} 

    echo '<button type="button" id="rent_popup_modal_m" class="single_rent_prod_button button alt '.$class.'">Rent Product</button>';
}
function aslechris_woo_add_custom_button_members() {  

    echo '<button type="button" id="rent_popup_modal_m" class="single_rent_prod_button button alt">Rent Product</button>';
    echo '<!-- The Modal -->
	<div id="membersModal" class="modal become-member-modal">

	  <!-- Modal content -->
	  <div class="modal-content">
	    <span class="close">&times;</span>
	    <p>You have to upgrade your membership to rent this product.</p>
	    <a href="'.home_url().'/membership-account/membership-levels/">Upgrade membership</a>
	  </div>

	</div>';
}

function aslechris_woo_add_custom_button_non_members() {     
    echo '<button type="button" id="rent_popup_modal" class="single_rent_prod_button button alt">Rent Product</button>';
    echo '<!-- The Modal -->
	<div id="memberModal" class="modal become-member-modal">

	  <!-- Modal content -->
	  <div class="modal-content">
	    <span class="close">&times;</span>
	    <p>You have to become a member to rent this product.</p>
	    <a href="'.home_url().'/membership-account/membership-levels/">Become a member</a>
	  </div>

	</div>';
}

// Add additional field into member profile page
add_action( 'woocommerce_edit_account_form', 'my_woocommerce_edit_account_form' );
add_action( 'woocommerce_save_account_details', 'my_woocommerce_save_account_details' );
 
function my_woocommerce_edit_account_form() {
 
  $user_id = get_current_user_id();
  $user = get_userdata( $user_id );
 
  if ( !$user )
    return;
 
  $description = get_user_meta( $user_id, 'description', true );
 
  ?>
 
 <fieldset>
    <legend>Profile picture</legend>
    <p class="form-row form-row-thirds">
      <label for="description">Profile picture:</label>
      <?php echo do_shortcode('[avatar_upload /]'); ?>
    </p>
  </fieldset>
  <fieldset>
    <legend>Additional Information</legend>
    <p class="form-row form-row-thirds">
      <label for="description">Bio:</label>
      <textarea name="description" class="input-text"><?php echo esc_attr( $description ); ?></textarea>      
    </p>
  </fieldset>
 
  <?php
 
}
 
function my_woocommerce_save_account_details( $user_id ) {
 
  update_user_meta( $user_id, 'description', htmlentities( $_POST[ 'description' ] ) );
 
}
/*====================================== Woocommerce customize area end =======================================*/

/*======================================= Contact form 7 RSVP =================================================*/
//Add the post id
function add_post_id_origin ( $tag, $unused ) {
    //First we test if it's our hidden field
    if ( $tag['name'] != 'your-event-id' )  
    return $tag; 

	//this way for a regulare global $post usage
	global $post;
	$tag['values'] = array($post->ID); 
	$tag['options'] = array('readonly');

	return $tag;  
}  
//Don't forget to hook the function
add_filter( 'wpcf7_form_tag', 'add_post_id_origin', 10, 2);

//Add the user id
function add_user_id_origin ( $tag, $unused ) {
    //First we test if it's our hidden field
    if ( $tag['name'] != 'your-user-id' )  
    return $tag; 

	//this way for a regulare global $post usage
	global $post;
	$tag['values'] = array(get_current_user_id()); 
	$tag['options'] = array('readonly');

	return $tag;  
}  
//Don't forget to hook the function
add_filter( 'wpcf7_form_tag', 'add_user_id_origin', 10, 2);

add_action( 'wpcf7_mail_sent', 'your_wpcf7_mail_sent_function' ); 
function your_wpcf7_mail_sent_function( $contact_form ) {
	global $wpdb;
    $title = $contact_form->title;
    $submission = WPCF7_Submission::get_instance();
  
    if ( $submission ) {
    	$posted_data = $submission->get_posted_data();
    }
      
    if ( 'Contact Form RSVP' == $title ) {     
    	$event_id = $posted_data['your-event-id'];    	    	    	
    	$user_id = $posted_data['your-user-id'];
    	$event_date = $posted_data['event-date'];

    	$formDataArray = array('your-firstname' => $posted_data['your-firstname'],'your-lastname' => $posted_data['your-lastname'],'your-email' => $posted_data['your-email'],'your-phone' => $posted_data['your-phone'],'event-selection' => $posted_data['event-selection'],'event-date' => $event_date,'your-message' => $posted_data['your-message']);

    	if(!empty($event_id) && !empty($user_id)){
    		$wpdb->insert( 
				'asl_event_rsvp', 
				array( 
					'user_id' => $user_id, 
					'event_id' => $event_id,
					'form_data' => serialize($formDataArray),					
					'timestamp' => date('Y-m-d h:i:s')
				), 
				array( 
					'%d', 
					'%d',					
					'%s',
					'%s'
				) 
			);
    	}
    }
}

/*
	Shortcode to show member events
*/
function member_events_function(){	
	global $wpdb;
	$user_id = get_current_user_id();

	ob_start();	

	$allevents = $wpdb->get_results("SELECT * FROM asl_event_rsvp WHERE user_id = $user_id");
	//echo '<pre>'; print_r($allevents); echo '</pre>';
	if ( $allevents )
	{		
	?>
	<div class="woocommerce-MyAccount-content">
	   <table class="woocommerce-orders-table woocommerce-MyAccount-orders shop_table shop_table_responsive my_account_orders account-orders-table">
	      <thead>
	         <tr>
	            <th class="woocommerce-orders-table__header woocommerce-orders-table__header-order-number"><span class="nobr">Event title</span></th>
	            <th class="woocommerce-orders-table__header woocommerce-orders-table__header-order-date"><span class="nobr">Date</span></th>
	            <th class="woocommerce-orders-table__header woocommerce-orders-table__header-order-status"><span class="nobr">Email</span></th>
	            <th class="woocommerce-orders-table__header woocommerce-orders-table__header-order-total"><span class="nobr">Phone</span></th>            
	         </tr>
	      </thead>
	      <tbody>
	      	<?php foreach ( $allevents as $event ){ 
	      		$formdata = unserialize($event->form_data);
	      	?>
	         <tr class="woocommerce-orders-table__row woocommerce-orders-table__row--status-processing order">
	            <td class="woocommerce-orders-table__cell woocommerce-orders-table__cell-order-number" data-title="Order">
	               <span><?php echo $formdata['event-selection']; ?></span>
	            </td>
	            <td class="woocommerce-orders-table__cell woocommerce-orders-table__cell-order-date" data-title="Date">
	               <time datetime="2019-01-08T13:52:06+00:00"><?php echo $formdata['event-date']; ?></time>
	            </td>
	            <td class="woocommerce-orders-table__cell woocommerce-orders-table__cell-order-status" data-title="Status">
	               <span><?php echo $formdata['your-email']; ?></span>
	            </td>
	            <td class="woocommerce-orders-table__cell woocommerce-orders-table__cell-order-total" data-title="Total">
	               <span><?php echo $formdata['your-phone']; ?></span>
	            </td>         
	         </tr> 
	         <?php } ?>        
	      </tbody>
	   </table>   
	</div>
	<?php } 
	
	$content = ob_get_contents();
	ob_end_clean();
	
	return $content; 
}

add_shortcode('member_events', 'member_events_function');