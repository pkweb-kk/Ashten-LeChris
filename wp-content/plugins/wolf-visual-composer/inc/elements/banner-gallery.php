<?php
/**
 * Banner Gallery
 *
 * @author WolfThemes
 * @category Core
 * @package WolfVisualComposer/Elements
 * @version 2.7.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$banner_params = vc_map_integrate_shortcode(
	'wvc_banner',
	'',
	'',
	array(
		'exclude' => array(
			'tagline',
			'link',
		),
	)
);

vc_map(
	array(
		'name' => esc_html__( 'Gallery Banner', 'wolf-visual-composer' ),
		'description' => esc_html__( 'Open a lightbox gallery from a banner', 'wolf-visual-composer' ),
		'base' => 'wvc_banner_gallery',
		'category' => esc_html__( 'Media' , 'wolf-visual-composer' ),
		'icon' => 'fa fa-bookmark-o',
		'params' => array_merge(
			array(
				array(
					'type' => 'attach_images',
					'heading' => esc_html__( 'Images', 'wolf-visual-composer' ),
					'param_name' => 'images',
				),
			),
			$banner_params
		)
	)
);

class WPBakeryShortCode_Wvc_Banner_Gallery extends WPBakeryShortCode {}