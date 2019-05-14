<?php
/**
 * Plugin Name: Woo Update Variations In Cart
 * Plugin URI: http://codingkart.com/
 * Description: WooCommerce Update Variations In Cart.
 * Version: 0.0.6
 * Author: Ganesh
 * Author URI: http://codingkart.com/
 * Developer: Ganesh pawar
 * Developer URI: http://codingkart.com/
 * Text Domain: woocommerce-extension
 * Domain Path: /languages
 *
 * Copyright: © 20016-2020 WooCommerce.
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */
 if ( ! defined( 'ABSPATH' ) ) { exit; } // Exit if accessed directly

//Plugin Path

if ( ! defined( 'WUVIC_WOO_UPDATE_CART_ASSESTS_URL' ) ) {

	define('WUVIC_WOO_UPDATE_CART_ASSESTS_URL', plugin_dir_url(__FILE__).'assets/');

}
require 'admin/class-wc-update-variation-in-cart-admin.php';
require 'front/class-wc_update-variation_in-cart_ck.php';