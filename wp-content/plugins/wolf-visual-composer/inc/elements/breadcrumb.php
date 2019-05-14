<?php
/**
 * Breadcrumb
 *
 * @author WolfThemes
 * @category Core
 * @package WolfVisualComposer/Elements
 * @version 2.7.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Breadcrumb
vc_map(
	array(
		'name' => esc_html__( 'Breadcrumb', 'wolf-visual-composer' ),
		'description' => esc_html__( 'Navigation Aid', 'wolf-visual-composer' ),
		'base' => 'wvc_breadcrumb',
		'category' => esc_html__( 'Content' , 'wolf-visual-composer' ),
		'icon' => 'fa fa-link',
		'params' => array(

			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Alignment', 'wolf-visual-composer' ),
				'param_name' => 'align',
				'value' => array(
					esc_html__( 'Left', 'wolf-visual-composer' ) => 'left',
					esc_html__( 'Center', 'wolf-visual-composer' ) => 'center',
					esc_html__( 'Right', 'wolf-visual-composer' ) => 'right',
				),
				'admin_label' => true,
			),

			array(
				'type' => 'wvc_textfield',
				'heading' => esc_html__( 'Font Size', 'wolf-visual-composer' ),
				'param_name' => 'font_size',
				'value' => apply_filters( 'wvc_default_breadcrumb_font_size', 14 ),
			),
		),
	)
);

class WPBakeryShortCode_Wvc_Breadcrumb extends WPBakeryShortCode {}