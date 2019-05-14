<?php
/**
 * Wolf WPBakery Page Builder Extension admin theme activation
 *
 * Functions available on admin
 *
 * @author WolfThemes
 * @category Core
 * @package WolfVisualComposer/Admin
 * @version 2.7.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get theme name
 */
function wvc_get_theme_name() {
	$wp_theme = wp_get_theme( get_template() );
	return $wp_theme->Name;
}

/**
 * Get the theme slug
 *
 * @return string
 */
function wvc_get_theme_slug() {

	return apply_filters( 'wolftheme_theme_slug', esc_attr( sanitize_title_with_dashes( get_template() ) ) );
}

/**
 * Theme license tab
 */
function wvc_output_license_tab() {
	?>
	<a href="#license" class="nav-tab"><?php esc_html_e( 'License', 'wolf-visual-composer' ); ?></a>
	<?php
}
add_action( 'wvc_license_tab', 'wvc_output_license_tab' );

/**
 * Theme license tab
 */
function wvc_output_license_tab_content() {
	?>
	<div id="license" class="wvc-options-panel">
	<?php
		$activated = wvc_activate_theme();
		$theme_name = wvc_get_theme_name();
		$theme_slug = wvc_get_theme_slug();
			
		if ( ! $activated ) :
		?>
		<p>
			<ul class="wvc-license-info">
				<li>
				<?php
					echo sprintf(
						wp_kses_post( __( '%s theme works with <a href="%s" target="_blank">%s</a> plugin to offer all its features.', 'wolf-visual-composer' ) ),
						$theme_name,
						'https://wolfthemes.com/wolf-wpbakery-page-builder-extension/',
						'Wolf WPBakery Page Builder Extension'
					);
				?>
				</li>
				<li>
					<?php
					echo sprintf(
						wp_kses_post( __( 'This is an extension of <a href="%" target="_blank">%s</a> plugin.', 'wolf-visual-composer' ) ),
						'https://codecanyon.net/item/visual-composer-page-builder-for-wordpress/242431?ref=wolf-themes',
						'WPBakery Page Builder'
					);
				?>
				</li>
				<li>
					<?php
					echo sprintf(
						wp_kses_post( __( 'This extension is available only to users who purchased their theme from <a href="%s" target="_blank">%s</a>.', 'wolf-visual-composer' ) ),
						'https://wolfthemes.com',
						'WolfThemes'
					);
				?>
				</li>
				<li>
				<?php
					echo sprintf(
						wp_kses_post( __( 'You <strong>do not need to activate %s</strong> as the full version is already included in the theme (<a href="%s" target="_blank">more infos</a>).', 'wolf-visual-composer' ) ),
						'WPBakery Page Builder',
						'https://wolfthemes.ticksy.com/article/12629/'
					);
				?>
				</li>
			</ul>
			<p class="wvc-license-cta-text">
				<?php
					echo sprintf(
						wp_kses_post( __( 'Please enter your <strong>theme purchase code</strong> below to activate your theme license and be able to use all features.', 'wolf-visual-composer' ) ),
						'WolfThemes'
					);
				?>
			</p>
		</p>
		<form method="post" action="<?php echo esc_url( admin_url( 'themes.php?page=' . $theme_slug . '-about#license' ) ); ?>">
		<input name="theme_purchase_code" placeholder="693e0017-48d3-4bd5-be47-1c5c14e7ab9c" type="text" class="regular-text wvc-license-input"><input value="<?php esc_html_e( 'Activate', 'wolf-visual-composer' ); ?>" type="submit" class="button button-primary wvc-license-button">
		</form>
		<p>
			<a target="_blank" href="https://help.market.envato.com/hc/en-us/articles/202822600-Where-Can-I-Find-my-Purchase-Code-"><?php esc_html_e( 'How to find your purchase code', 'wolf-visual-composer' ); ?></a>
		</p
		<?php
		else :
		?>
		<br>
		<p><?php
			echo sprintf(
				wp_kses_post( __( 'The %s is activated.', 'wolf-visual-composer' ) ),
				'WPBakery Page Builder Extension'
			);
		?></p>
		<?php
		endif;

	?>
	</div><!-- #license -->
	<?php
}
add_action( 'wvc_license_tab_content', 'wvc_output_license_tab_content' );

/**
 * Output the last new feature if set in the changelog XML
 *
 */
function wvc_activate_theme() {

	$activated = get_option( 'wvc_key' );
	$is_error = false;

	if ( ! $activated && isset( $_POST['theme_purchase_code'] ) ) {
		
		/* Verifiy purchase */
		if ( isset( $_POST['theme_purchase_code'] ) && ! empty( $_POST['theme_purchase_code'] ) ) {

			$code = esc_attr( $_POST['theme_purchase_code'] );
			$remote_url = 'https://api.wolfthemes.com/envato/';
			//$remote_url = 'http://localhost/api/envato/';

			$url = $remote_url . '?code=' . $code;

			// send request
			$response = wp_remote_post( $url, array(
				'method' => 'POST',
				'body' => array(
					'action' => 'activation',
					'purchase_code' => $_POST['theme_purchase_code'],
				),
			) );

			// get result if no error
			if ( ! is_wp_error( $response ) && is_array( $response ) ) {
				
				$body = wp_remote_retrieve_body( $response ); // use the content
				
				if ( $body ) {

					$data = json_decode( $body );

					if ( $data && is_object( $data ) && isset( $data->code ) && isset( $data->key ) ) {

						set_transient( 'wvc_activated', true, 10 * YEAR_IN_SECONDS );
						add_option( 'wvc_code', $data->code );
						add_option( 'wvc_key', $data->key );
						$activated = true;

						echo '<div class="notice-success notice">';
						echo '<p>';
						esc_html_e( 'Extension activated', 'wolf-visual-composer' );
						echo '</p>';
						echo '</div>';

					} else {
						$is_error = true;
						$error = esc_html__( 'Wrong purchase code', 'wolf-visual-composer' );
					}

				} else {
					$is_error = true;
					$error = esc_html__( 'Wrong purchase code', 'wolf-visual-composer' );
				}

			} else {
				$is_error = true;
				$error = esc_html__( 'Wrong purchase code', 'wolf-visual-composer' );
			}
		} else {
			$is_error = true;
			$error = esc_html__( 'Purchase code can not be empty', 'wolf-visual-composer' );
		}

	} else if ( $activated ) {

		return true;
	}

	if ( $is_error && $error ) {

		echo '<div class="notice-error notice">';
		echo '<p>';
		echo sanitize_text_field( $error );
		echo '</p>';
		echo '</div>';
	}

	return $activated;
}