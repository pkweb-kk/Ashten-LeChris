<?php
/**
 * MailChimp function
 *
 * @author WolfThemes
 * @category Core
 * @package WolfVisualComposer/Shortcodes
 * @version 2.7.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Return a mailchimp Subscription form
 *
 * @param string $list
 * @param string $size
 * @param string $label
 * @param string $submit
 * @return string $output
 */
function wvc_mailchimp( $atts = array() ) {

	$atts = wp_parse_args( $atts, array(
		'list' => wolf_vc_get_option( 'mailchimp', 'default_mailchimp_list_id' ),
		'size' => 'normal',
		'label' => wolf_vc_get_option( 'mailchimp', 'label' ),
		'submit_type' => 'text',
		'submit_text' => wolf_vc_get_option( 'mailchimp', 'subscribe_text', esc_html__( 'Subscribe', 'wolf-visual-composer' ) ),
		'si_type' => '',
		'icon' => '',
		'bottom_line' => wolf_vc_get_option( 'mailchimp', 'bottom_line' ),
		'image_id' => wolf_vc_get_option( 'mailchimp', 'background' ),
		'show_bg' => true,
		'show_label' => true,
		'placeholder' =>  wolf_vc_get_option( 'mailchimp', 'placeholder', esc_html__( 'enter your email address', 'wolf-visual-composer' ) ),
		'button_style' => '',
		'alignment' => 'center',
		'text_alignment' => 'center',
		'css_animation' => '',
		'css_animation_delay' => '',
		'enqueue_script' => true,
		'css' => '',
		'el_class' => '',
		'inline_style' => '',
	) );

	extract( $atts );

	$output = '';

	$class = $el_class;
	$inline_style = wvc_sanitize_css_field( $inline_style );
	$inline_style .= wvc_shortcode_custom_style( $css );

	/*Animate */
	if ( ! wvc_is_new_animation( $css_animation ) ) {
		$class .= wvc_get_css_animation( $css_animation );
		$inline_style .= wvc_get_css_animation_delay( $css_animation_delay );
	}

	if ( $enqueue_script && ! wp_script_is( 'wvc-mailchimp' ) ) {
		wp_enqueue_script( 'wvc-mailchimp' );
		// add JS global variables
		wp_localize_script(
			'wvc-mailchimp', 'WVCMailchimpParams', array(
				'ajaxUrl' => esc_url( wvc()->ajax_url() ),
			)
		);
	}

	$show_bg = wvc_shortcode_bool( $show_bg );
	$show_label = wvc_shortcode_bool( $show_label );

	$class .= " wvc-mailchimp-form-container wvc-mailchimp-size-$size wvc-mailchimp-align-$alignment wvc-mailchimp-text-align-$text_alignment wvc-mc-submit-type-$submit_type wvc-element";

	$image_size = ( 'large' == $size ) ? 'large' : 'medium';
	$background = wvc_get_url_from_attachment_id( $image_id, $image_size );

	if ( $background && $show_bg ) {
		$class .= ' wvc-mailchimp-has-bg';
		$inline_style .= 'background-image:url(' . $background . ')';
	}

	$output .= '<div class="' . wvc_sanitize_html_classes( $class ) . '" style="' . wvc_esc_style_attr( $inline_style ) . '"';

	$output .= wvc_element_aos_animation_data_attr( $atts );
	$output .= '>';

	$output .= '<form class="wvc-mailchimp-form"><input type="hidden" name="wvc-mailchimp-list" class="wvc-mailchimp-list" value="' . esc_attr( $list ) . '">';

	if ( $label && $show_label ) {
		$output .= '<h3 class="wvc-mailchimp-title">' . $label . '</h3>';
	}

	$output .= '<div class="wvc-mailchimp-inner">';

	$output .= '<div class="wvc-mailchimp-email-container">
		<input placeholder="' . $placeholder . '"  type="email" name="wvc-mailchimp-email" class="wvc-mailchimp-email">
		</div>';
	$output .= "<div class='wvc-mailchimp-submit-container'>";
	
	$output .= "<button class='wvc-button wvc-mailchimp-submit $button_style'>";

	if ( 'icon' === $submit_type ) {
		
		$output .= "<i class='wvc-mc-icon fa $icon'></i>";
	
	} else {
		$output .= $submit_text;
	}

	$output .= "</button>";
	
	$output .= "</div>";
	$output .= '</div>'; // inner
	$output .= '<div class="wvc-clear"></div>';
	$output .= '<span class="wvc-mailchimp-result">&nbsp;</span>';
	$output .= '</form>';
	$output .= '</div><!-- .wvc-mailchimp-form-container -->';

	if ( wolf_vc_get_option( 'mailchimp', 'mailchimp_api_key' ) && ! empty( $list ) ) {

		return $output;

	} elseif ( is_user_logged_in() ) {

		$output = '<p class="wvc-text-center">';

		if ( ! wolf_vc_get_option( 'mailchimp', 'mailchimp_api_key' ) ) {

			$output .= sprintf( wp_kses_post( __( '<p class="wvc-text-center">You must set a MailChimp API key in the <a href="%s" target="_blank">Wolf WPBakery Page Builder Extension</a>. You can get your MailChimp API <a href="%s" target="_blank">here</a>.<p>', 'wolf-visual-composer' ) ),
				esc_url( admin_url( 'admin.php?page=wvc-mailchimp' ) ),
				esc_url( 'http://kb.mailchimp.com/integrations/api-integrations/about-api-keys' )
			);
			$output .= '<br>';
		}

		if ( ! $list ) {
			$output .= esc_html__( 'You must set a list ID.', 'wolf-visual-composer' );
		}

		$output .= '</p>';
		return $output;
	} else {

		$output = '';

		$output .= '<p class="wvc-text-center">' . esc_html__( 'Subscription to our newsletter open soon.', 'wolf-visual-composer' ) . '</p>';

		return $output;
	}
}