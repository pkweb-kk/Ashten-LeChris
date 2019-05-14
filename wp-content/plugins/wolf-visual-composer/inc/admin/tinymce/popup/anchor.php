<?php
/**
 * Anchor dialog box
 *
 * @class WVC_Admin
 * @author WolfThemes
 * @category Admin
 * @package WolfVisualComposer/Admin
 * @version 2.7.4
 */
$title = esc_html__( 'Anchor', 'wolf-visual-composer' );
$params = array(

	array(
		'id' => 'id',
		'label' => esc_html__( 'ID', 'wolf-visual-composer' ),
	),

);
echo wvc_generate_tinymce_popup( 'wvc_anchor', $params, $title );