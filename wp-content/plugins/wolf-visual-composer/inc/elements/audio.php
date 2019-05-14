<?php
/**
 * Audio Embed
 *
 * @author WolfThemes
 * @category Core
 * @package WolfVisualComposer/Elements
 * @version 2.7.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Audio
vc_map(
	array(
		'name' => esc_html__( 'Single Audio Player', 'wolf-visual-composer' ),
		'base' => 'wvc_audio',
		'description' => esc_html__( 'WordPress Audio Shortcode', 'wolf-visual-composer' ),
		'icon' => 'fa fa-music',
		'category' => esc_html__( 'Music' , 'wolf-visual-composer' ),
		'params' => array(

			array(
				'type' => 'wvc_audio_url',
				'heading' => esc_html__( 'Mp3 URL', 'wolf-visual-composer' ),
				'param_name' => 'mp3',
				'admin_label' => true,
			),

			array(
				'type' => 'attach_image',
				'heading' => esc_html__( 'Image', 'wolf-visual-composer' ),
				'param_name' => 'image',
				'admin_label' => true,
			),

			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Preload', 'wolf-visual-composer' ),
				'param_name' => 'preload',
				'value' => array(
					esc_html__( 'None', 'wolf-visual-composer' ) => '',
					esc_html__( 'Auto', 'wolf-visual-composer' ) => 'auto',
					esc_html__( 'Metadata', 'wolf-visual-composer' ) => 'metadata',
				),
			),

			array(
				'type' => 'checkbox',
				'heading' => esc_html__( 'Autoplay', 'wolf-visual-composer' ),
				'param_name' => 'autoplay',
				'value' => array(
					'' => 'true'
				),
			),

			array(
				'type' => 'checkbox',
				'heading' => esc_html__( 'Loop', 'wolf-visual-composer' ),
				'param_name' => 'loop',
				'value' => array(
					'' => 'true'
				),
			),

			array(
				'type' => 'wvc_audio_url',
				'heading' => esc_html__( 'Ogg URL', 'wolf-visual-composer' ),
				'param_name' => 'ogg',
				'description' => esc_html__( 'Add alternate sources for maximum HTML5 playback.', 'wolf-visual-composer' ),
			),

			// Max Width
			array(
				'type' => 'wvc_textfield',
				'heading' => esc_html__( 'Maximum width', 'wolf-visual-composer' ),
				'param_name' => 'max_width',
				'description' => sprintf( esc_html__( 'Set a value in %s or %s if you want to constrain the image width.', 'wolf-visual-composer' ), 'px', '%' ),
				'placeholder' => '100%',
			),

			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Alignment', 'wolf-visual-composer' ),
				'param_name' => 'alignment',
				'std' => 'left',
				'value' => array(
					esc_html__( 'Left', 'wolf-visual-composer' ) => 'left',
					esc_html__( 'Center', 'wolf-visual-composer' ) => 'center',
					esc_html__( 'Right', 'wolf-visual-composer' ) => 'right',
				),
			),
		),
	)
);

class WPBakeryShortCode_Wvc_Audio extends WPBakeryShortCode {}