<?php
/**
 * Social icons custom
 *
 * @author WolfThemes
 * @category Core
 * @package WolfVisualComposer/Elements
 * @version 2.7.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$social_icons_params = vc_map_integrate_shortcode(
	'wvc_social_icons',
	'',
	'',
	array(
		'exclude' => array(
			'services',
		),
	)
);

// Social icons
vc_map(
	array(
		'name' => esc_html__( 'Social Icons Custom', 'wolf-visual-composer' ),
		'base' => 'wvc_social_icons_custom',
		'icon' => 'fa fa-share-alt',
		'description' => esc_html__( 'A set of icons with custom URLs', 'wolf-visual-composer' ),
		'category' => esc_html__( 'Social', 'wolf-visual-composer' ),
		'params' => $social_icons_params
	)
);

$add_params = array();
$socials = wvc_get_socials();

foreach ( $socials as $social ) {
	
	$add_params[] = array(
		'type' => 'textfield',
		'heading' => ucfirst( $social ),
		'param_name' => $social,
		'group' => esc_html__( 'Socials', 'wolf-visual-composer' ),
		'weight' => 1000,
	);
}
vc_add_params( 'wvc_social_icons_custom', $add_params );

class WPBakeryShortCode_Wvc_Social_Icons_Custom extends WPBakeryShortCode {}