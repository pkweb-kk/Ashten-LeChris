<?php
/**
 * Plugin Name: Wolf WPBakery Page Builder Extension
 * Plugin URI: http://wolfthemes.com/plugin/wolf-visual-composer
 * Description: A WordPress plugin that extends WPBakery Page Builder for Wolf Themes.
 * Version: 2.7.4
 * Author: WolfThemes
 * Author URI: http://wolfthemes.com
 * Requires at least: 4.5
 * Tested up to: 4.9.8
 *
 * Text Domain: wolf-visual-composer
 * Domain Path: /languages/
 *
 * @package WolfVisualComposer
 * @category Core
 * @author WolfThemes
 *
 * Help:
 * https://wolfthemes.ticksy.com/
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'Wolf_Visual_Composer' ) ) {
	/**
	 * Main Wolf_Visual_Composer Class
	 *
	 * Contains the main functions for Wolf_Visual_Composer
	 *
	 * @class Wolf_Visual_Composer
	 * @version 2.7.4
	 * @since 1.0.0
	 */
	class Wolf_Visual_Composer {

		/**
		 * @var string
		 */
		public $version = '2.7.4';

		/**
		 * @var Wolf WPBakery Page Builder Extension The single instance of the class
		 */
		protected static $_instance = null;

		/**
		 * @var string
		 */
		private $update_url = 'https://plugins.wolfthemes.com/update';

		/**
		 * @var the support forum URL
		 */
		private $support_url = 'https://help.wolfthemes.com/';

		/**
		 * @var string
		 */
		public $template_url;

		/**
		 * Main Wolf WPBakery Page Builder Extension Instance
		 *
		 * Ensures only one instance of Wolf WPBakery Page Builder Extension is loaded or can be loaded.
		 *
		 * @static
		 * @see WVC()
		 * @return Wolf WPBakery Page Builder Extension - Main instance
		 */
		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		/**
		 * Wolf WPBakery Page Builder Extension Constructor.
		 */
		public function __construct() {

			// if ( ! $this->is_activated() ) {

			// 	define( 'WVC_OK', false );
			// 	include_once( 'inc/admin/admin-theme-activation.php' );
			// 	add_action( 'admin_notices', array( $this, 'activation_notice' ) );
			// 	return;
			// }

			if ( $this->not_ok_bro() ) {

				define( 'WVC_OK', false );
				add_action( 'admin_notices', array( $this, 'show_not_ok_bro_notice' ) );
				return;
			}

			if ( ! defined( 'WPB_VC_VERSION' ) ) {
				define( 'WVC_OK', false );
				add_action( 'admin_notices', array( $this, 'show_vc_missing_notice' ) );
				return;
			}

			$this->define_constants();
			$this->includes();
			$this->init_hooks();

			do_action( 'wolf_vc_loaded' );
		}

		/**
		 * Show notice if your plugin is activated but WPBakery Page Builder is not
		 */
		public function show_vc_missing_notice() {
			$plugin_data = get_plugin_data( __FILE__ );
			echo '<div class="notice notice-warning">
				<p>' . sprintf(
					wp_kses_post( __('<strong>%s</strong> requires <strong><a href="%s" target="_blank">%s</a></strong> plugin to be installed and activated.', 'wolf-visual-composer' ) ),
						$plugin_data['Name'],
						'https://codecanyon.net/item/visual-composer-page-builder-for-wordpress/242431?ref=wolf-themes',
						'WPBakery Page Builder'
					) . '</p>
			</div>';
		}

		/**
		 * Show notice if your plugin is activated but WPBakery Page Builder is not
		 */
		public function activation_notice() {

			$theme_slug = apply_filters( 'wolftheme_theme_slug', esc_attr( sanitize_title_with_dashes( get_template() ) ) );

			if ( isset( $_GET['page'] ) && $_GET['page'] === $theme_slug . '-about' ) {
				return;
			}

			$plugin_data = get_plugin_data( __FILE__ );
			echo '<div class="notice notice-error">
				<p>' . sprintf(
					wp_kses_post( __( '<strong>%s</strong> only works for verified customers who purchased a theme from the <a href="%s" target="_blank">%s</a> team. Please enter your theme <a href="%s" target="_blank" title="Find your purchase code">purchase code</a> in the plugin settings to unlock all features.', 'wolf-visual-composer' ) ),
						$plugin_data['Name'],
						'https://themeforest.net/user/wolf-themes/portfolio?ref=wolf-themes',
						'WolfThemes',
						'https://help.market.envato.com/hc/en-us/articles/202822600-Where-Can-I-Find-my-Purchase-Code-'
					) . '</p>
				<p>
					<a class="button button-primary" href="' . esc_url( admin_url( 'themes.php?page=' . $theme_slug . '-about#license' ) ) . '">' . esc_html( 'Activate', 'wolf-visual-composer' ) . '</a>
				</p>
				</div>';
		}

		/**
		 * Show notice if your plugin is activated but WPBakery Page Builder is not
		 */
		public function show_not_ok_bro_notice() {
			$plugin_data = get_plugin_data( __FILE__ );
			echo '<div class="notice notice-warning">
				<p>' . sprintf(
					wp_kses_post( __( 'Sorry, but <strong>%s</strong> only works with compatible <a target="_blank" href="%s">%s themes</a>.<br><strong>Be sure that you didn\'t change the theme\'s name in the %s file or the theme\'s folder name</strong>.<br>If you want to customize the theme\'s name, you can use a <a target="_blank" href="%s">child theme</a>.', 'wolf-visual-composer' ) ),
						$plugin_data['Name'],
						'https://themeforest.net/user/wolf-themes/portfolio?ref=wolf-themes',
						'WolfThemes',
						'style.css',
						'https://wolfthemes.ticksy.com/article/11659/'
					) . '</p>
			</div>';
		}

		/**
		 * Hook into actions and filters
		 */
		private function init_hooks() {
			register_activation_hook( __FILE__, array( $this, 'activate' ) );

			add_action( 'after_setup_theme', array( $this, 'include_template_functions' ), 11 );
			add_action( 'init', array( $this, 'init' ), 0 );

			// Includes element after init hook to allow filtering by theme
			add_action( 'init', array( $this, 'include_elements' ) );
		}

		/**
		 * Activation function
		 */
		public function activate() {
			//do_action( 'vc_restore_default_settings_preset', null, 'vc_section' );
		}

		/**
		 * Define WR Constants
		 */
		private function define_constants() {

			$constants = array(
				'WVC_DEV' => false,
				'WVC_OK' => true,
				'WVC_DIR' => $this->plugin_path(),
				'WVC_URI' => $this->plugin_url(),
				'WVC_CSS' => $this->plugin_url() . '/assets/css',
				'WVC_JS' => $this->plugin_url() . '/assets/js',
				'WVC_SLUG' => plugin_basename( dirname( __FILE__ ) ),
				'WVC_PATH' => plugin_basename( __FILE__ ),
				'WVC_VERSION' => $this->version,
				'WVC_UPDATE_URL' => $this->update_url,
				'WVC_SUPPORT_URL' => $this->support_url,
				'WVC_DOC_URI' => 'https://docs.wolfthemes.com/documentation/plugins/' . plugin_basename( dirname( __FILE__ ) ),
				'WVC_WOLF_DOMAIN' => 'wolfthemes.com',
			);

			foreach ( $constants as $name => $value ) {
				$this->define( $name, $value );
			}
		}

		/**
		 * Define constant if not already set
		 * @param  string $name
		 * @param  string|bool $value
		 */
		private function define( $name, $value ) {
			if ( ! defined( $name ) ) {
				define( $name, $value );
			}
		}

		/**
		 * What type of request is this?
		 * string $type ajax, frontend or admin
		 * @return bool
		 */
		private function is_request( $type ) {
			switch ( $type ) {
				case 'admin' :
					return is_admin();
				case 'ajax' :
					return defined( 'DOING_AJAX' );
				case 'cron' :
					return defined( 'DOING_CRON' );
				case 'frontend' :
					return ( ! is_admin() || defined( 'DOING_AJAX' ) ) && ! defined( 'DOING_CRON' );
			}
		}

		/**
		 * Include required core files used in admin and on the frontend.
		 */
		public function includes() {

			// Functions used in frontend and admin
			include_once( 'inc/core-functions.php' );
			include_once( 'inc/utility-functions.php' );
			include_once( 'inc/google-fonts.php' );
			include_once( 'inc/vc-editor-scripts.php' );
			include_once( 'inc/vc-presets.php' );
			include_once( 'inc/vc-extend.php' );
			include_once( 'inc/vc-custom-fields.php' );
			include_once( 'inc/vc-additional-params.php' );
			include_once( 'inc/theme-functions.php' );
			include_once( 'inc/conditional-functions.php' );

			// Containers background
			include_once( 'inc/params/background-params.php' );
			include_once( 'inc/params/style-params.php' );

			// Section
			include_once( 'inc/params/section-params.php' );

			// Big Text
			include_once( 'inc/params/bigtext-params.php' );
			include_once( 'inc/bigtext.php' );

			// Row
			include_once( 'inc/params/row-params.php' );
			include_once( 'inc/params/row-inner-params.php' );

			// Column
			include_once( 'inc/params/column-params.php' );
			include_once( 'inc/params/column-inner-params.php' );

			// Icon functions
			include_once( 'inc/icon-styles.php' );
			include_once( 'inc/icon-libraries.php' );
			include_once( 'inc/params/icon-params.php' );

			// Button functions
			include_once( 'inc/params/button-params.php' );
			include_once( 'inc/button.php' );

			// Social icons
			include_once( 'inc/social-icons.php' );
			include_once( 'inc/class-widget-socials.php' );

			// Heading
			include_once( 'inc/heading.php' );

			// MailChimp
			include_once( 'inc/class-mailchimp.php' );
			include_once( 'inc/class-widget-mailchimp.php' );
			include_once( 'inc/mailchimp.php' );

			// Background functions
			include_once( 'inc/background-functions.php' );

			// Login Form
			include_once( 'inc/login-form.php' );

			// Modal Window
			include_once( 'inc/modal-window.php' );

			// Privacy Policy Message
			include_once( 'inc/privacy-policy-message.php' );

			if ( $this->is_request( 'admin' ) ) {
				include_once( 'inc/admin/class-admin.php' );
			}

			if ( $this->is_request( 'ajax' ) ) {
				$this->ajax_includes();
			}

			if ( $this->is_request( 'frontend' ) ) {
				$this->frontend_includes();
			}
		}

		/**
		 * Include required ajax files.
		 */
		public function ajax_includes() {
			include_once( 'inc/ajax/ajax-functions.php' );
		}

		/**
		 * Include required frontend files.
		 */
		public function frontend_includes() {

			include_once( 'inc/frontend/frontend-functions.php' );
			include_once( 'inc/frontend/template-hooks.php' );
			include_once( 'inc/frontend/theme-frontend-functions.php' );
			include_once( 'inc/frontend/styles.php' );
			include_once( 'inc/frontend/colors.php' );
			include_once( 'inc/frontend/scripts.php' );
		}

		/**
		 * Include element files
		 */
		public function include_elements() {
			// Includes all shortcode files

			// Get elements list
			$elements_slugs = wvc_get_element_list();

			foreach ( $elements_slugs as $slug ) {

				include_once( 'inc/elements/' . sanitize_title_with_dashes( $slug ) . '.php' );

				if ( is_file( WVC_DIR . '/inc/shortcodes/' . sanitize_title_with_dashes( $slug ) . '.php' ) ) {
					include_once( 'inc/shortcodes/' . sanitize_title_with_dashes( $slug ) . '.php' );
				}
			}
		}

		/**
		 * Function used to Init Wolf WPBakery Page Builder Extension Template Functions - This makes them pluggable by plugins and themes.
		 */
		public function include_template_functions() {
			include_once( 'inc/frontend/template-functions.php' );
		}

		/**
		 * Init Wolf WPBakery Page Builder Extension when WordPress Initialises.
		 */
		public function init() {

			// Set up localisation
			$this->load_plugin_textdomain();

			// Variables
			$this->template_url = apply_filters( 'wolf_vc_url', 'views/' );

			// Init action
			do_action( 'wolf_vc_init' );
		}

		/**
		 * Loads the plugin text domain for translation
		 */
		public function load_plugin_textdomain() {

			$domain = 'wolf-visual-composer';
			$locale = apply_filters( 'wolf-visual-composer', get_locale(), $domain );
			load_textdomain( $domain, WP_LANG_DIR . '/' . $domain . '/' . $domain . '-' . $locale . '.mo' );
			load_plugin_textdomain( $domain, FALSE, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
		}

		/**
		 * Get the plugin url.
		 * @return string
		 */
		public function plugin_url() {
			return untrailingslashit( plugins_url( '/', __FILE__ ) );
		}

		/**
		 * Get the plugin path.
		 * @return string
		 */
		public function plugin_path() {
			return untrailingslashit( plugin_dir_path( __FILE__ ) );
		}

		/**
		 * Get the template path.
		 * @return string
		 */
		public function template_path() {
			return apply_filters( 'wvc_template_path', 'views/' );
		}

		/**
		 * Get the template path.
		 * @return string
		 */
		public function shortcode_template_path() {
			return apply_filters( 'wvc_shortcode_template_path', 'templates/' );
		}

		/**
		 * Get Ajax URL.
		 * @return string
		 */
		public function ajax_url() {
			return admin_url( 'admin-ajax.php', 'relative' );
		}

		/**
		 * Is the plugin activated? 
		 *
		 * @return bool
		 */
		private function is_activated() {

			//delete_transient( 'wvc_activated' );
			//delete_option( 'wvc_code' );
			//delete_option( 'wvc_key' );

			if ( is_admin() ) {

				if ( ! get_transient( 'wvc_activated' ) ) {
					
					$remote_url = 'https://api.wolfthemes.com/envato/';
					$response = wp_remote_post( $remote_url, array(
						'method' => 'POST',
						'body' => array(
							'action' => 'verification',
							'code' => get_option( 'wvc_code' ),
							'key' => get_option( 'wvc_key' ),
						),
					) );

					if ( ! is_wp_error( $response ) && is_array( $response ) ) {

						$body = wp_remote_retrieve_body( $response );

						if ( '' === $body ) {
							delete_option( 'wvc_code' );
							delete_option( 'wvc_key' );
						}

					} else {
						delete_option( 'wvc_code' );
						delete_option( 'wvc_key' );
					}
				}
			}
			
			return get_option( 'wvc_key' );
		}

		/**
		 * Not OK bro
		 * @return bool
		 */
		private function not_ok_bro() {
			$ok = array( 'wolf-2018', 'protheme', 'iyo', 'loud', 'tune', 'retine', 'racks', 'andre', 'hares', 'glytch', 'superflick', 'phase', 'zample', 'prequelle', 'slikk', 'vonzot', 'deadlift', 'hyperbent' );

			return ( ! in_array( esc_attr( sanitize_title_with_dashes( get_template() ) ), $ok ) );
		}
	} // end class
} // end class check

/**
 * Returns the main instance of Wolf_Visual_Composer to prevent the need to use globals.
 *
 * @return Wolf_Visual_Composer
 */
function WVC() {
	return Wolf_Visual_Composer::instance();
}

WVC(); // Go