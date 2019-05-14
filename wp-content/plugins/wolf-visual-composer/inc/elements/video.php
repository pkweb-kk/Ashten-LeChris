<?php
/**
 * Video
 *
 * @author WolfThemes
 * @category Core
 * @package WolfVisualComposer/Elements
 * @version 2.7.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Video
vc_map(
	array(
		'name' => esc_html__( 'Video', 'wolf-visual-composer' ),
		'base' => 'vc_video',
		'description' => esc_html__( 'Embed YouTube/Vimeo player', 'wolf-visual-composer' ),
		'icon' => 'fa fa-youtube-play',
		'category' => esc_html__( 'Media', 'wolf-visual-composer' ),
		'params' => array(

			array(
				'type' => 'wvc_textfield',
				'heading' => esc_html__( 'Video URL', 'wolf-visual-composer' ),
				'param_name' => 'link',
				'placeholder' => 'https://vimeo.com/30391286',
				'description' => sprintf( wp_kses(
					__( 'Link to the video. More about supported formats at <a href="%s" target="_blank">WordPress codex page.</a>', 'wolf-visual-composer' ),
						array( 'a' => array( 'href' => array() ) )
					),
					'http://codex.wordpress.org/Embeds#Okay.2C_So_What_Sites_Can_I_Embed_From.3F'
				),
			),
		),
	)
);