<?php
/**
 * Content Block shortcode template
 *
 * @author WolfThemes
 * @category Core
 * @package WolfVisualComposer/Templates
 * @version 2.7.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$atts = vc_map_get_attributes( $this->getShortcode(), $atts );

extract( shortcode_atts( array(
	'id' => '',
) , $atts) );

$id = apply_filters( 'wpml_object_id', $id, 'post' ); // WPML compatibility

$content = get_post_field( 'post_content', $id );

//echo wpb_js_remove_wpautop( $content );