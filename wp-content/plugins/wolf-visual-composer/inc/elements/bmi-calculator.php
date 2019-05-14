<?php
/**
 * BMIC
 *
 * @author WolfThemes
 * @category Core
 * @package WolfVisualComposer/Elements
 * @version 2.7.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// BMI Calculator
vc_map(
	array(
		'name' => esc_html__( 'BMI Calculator', 'wolf-visual-composer' ),
		'description' => esc_html__( 'A BMI Calculator Form', 'wolf-visual-composer' ),
		'base' => 'wvc_bmi_calculator',
		'category' => esc_html__( 'Content' , 'wolf-visual-composer' ),
		'icon' => 'fa dripicons-lifting',
		'params' => array(
			array(
				'type' => 'wvc_textfield',
				'heading' => esc_html__( 'Calculator Form Title', 'wolf-visual-composer' ),
				'param_name' => 'title',
				'value' => esc_html__( 'Calculate Your BMI', 'wolf-visual-composer' ),
				'admin_label' => true,
			),

			array(
				'type' => 'textarea_html',
				'heading' => esc_html__( 'Calculator Form Subtitle', 'wolf-visual-composer' ),
				'param_name' => 'content',
			),
		),
	)
);

class WPBakeryShortCode_Wvc_BMI_Calculator extends WPBakeryShortCode {}