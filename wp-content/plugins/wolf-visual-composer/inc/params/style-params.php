<?php
/**
 * Style params for containers
 *
 * @author WolfThemes
 * @category Core
 * @package WolfVisualComposer/Core
 * @version 2.7.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Row custom params
 */
function wvc_style_params() {
	return array(
		array(
			'type' => 'css_editor',
			'heading' => esc_html__( 'CSS box', 'wolf-visual-composer' ),
			'param_name' => 'css',
			'group' => esc_html__( 'Custom', 'wolf-visual-composer' ),
			'weight' => -1,
		),

		// array(
		// 	'type' => 'textfield',
		// 	'heading' => esc_html__( 'Border Color', 'wolf-visual-composer' ),
		// 	'param_name' => 'border_color',
		// 	'group' => esc_html__( 'Custom', 'wolf-visual-composer' ),
		// 	'weight' => -1,
		// ),

		// array(
		// 	'type' => 'textfield',
		// 	'heading' => esc_html__( 'Border Style', 'wolf-visual-composer' ),
		// 	'param_name' => 'border_style',
		// 	'group' => esc_html__( 'Custom', 'wolf-visual-composer' ),
		// 	'weight' => -1,
		// ),
	);
}