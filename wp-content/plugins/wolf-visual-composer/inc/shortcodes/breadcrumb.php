<?php
/**
 * Breadcrump shortcode
 *
 * @author WolfThemes
 * @category Core
 * @package WolfVisualComposer/Shortcodes
 * @version 2.7.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// if ( ! function_exists( 'wvc_shortcode_breadcrumb' ) ) {
// 	/**
// 	 * Breadcrump shortcode
// 	 *
// 	 * @param array $atts
// 	 * @return string
// 	 */
// 	function wvc_shortcode_breadcrumb( $atts ) {

// 		extract( shortcode_atts( array(
// 			'align' => '',
// 		), $atts ) );

// 		$output = '';

// 		$output .= '<div class="wvc-breadcrumb wvc-text-' . esc_attr( $align ) . '">';

// 		$output .= wvc_breadcrumb();

// 		$output .= '</div><!--.wvc-breadcrumb-->';

// 		return $output;
// 	}
// 	add_shortcode( 'wvc_breadcrumb', 'wvc_shortcode_breadcrumb' );
// }