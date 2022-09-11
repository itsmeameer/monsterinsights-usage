<?php
/**
 * The class responsible for handling all shortcodes
 *
 * @link       https://about.me/itsmeameer
 * @since      1.0.0
 *
 * @package    MonsterInsights_Usage
 * @subpackage MonsterInsights_Usage/includes
 */

if ( ! class_exists( 'MI_Usage_Shortcodes' ) ) {

	/**
	 * Handles all shortcodes.
	 *
	 * @since      1.0.0
	 * @package    MonsterInsights_Usage
	 * @subpackage MonsterInsights_Usage/includes
	 * @author     Ameer Humza <itsmeameer@gmail.com>
	 */
	class MI_Usage_Shortcodes {

		/**
		 * Holds instance of the main class MI_Usage.
		 *
		 * @var $MI_Usage
		 */
		private $mi_usage = null;

		/**
		 * Holds instance of the class.
		 *
		 * @var $instance
		 */
		private static $instance = null;

		/**
		 * Constructor
		 */
		private function __construct() {

			$this->mi_usage = MI_Usage::get_instance();

			$this->hooks();

		}

		/**
		 * Get singleton instance.
		 *
		 * @since 1.0.0
		 * @return MI_Usage_Shortcodes Singleton object of MI_Usage_Shortcodes
		 */
		public static function get_instance() {

			// Check if instance is already exists.
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Cloning is forbidden.
		 *
		 * @since 1.0.0
		 */
		private function __clone() {
			_doing_it_wrong( __FUNCTION__, esc_html__( 'This action is forbidden!', 'monsterinsights-usage' ), '1.0.0' );
		}

		/**
		 * Call all required hooks.
		 *
		 * @since 1.0.0
		 * @return void
		 */
		private function hooks() {

			add_action( 'wp_enqueue_scripts', array( $this, 'load_assets' ) );
			add_shortcode( 'mi-usage-table', array( $this, 'mi_usage_table_callback' ) );

		}

		/**
		 * Register / Enqueue scripts and styles.
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function load_assets() {

			$plugin_slug   = $this->mi_usage->get_plugin_slug();
			$plugin_object = $this->mi_usage->get_plugin_slug( true );

			wp_register_script(
				$plugin_slug . '-shortcode',
				plugins_url( 'assets/js/shortcode.js', dirname( __FILE__ ) ),
				array( 'wp-i18n' ),
				$this->mi_usage->get_version(),
				true,
			);

			wp_localize_script(
				$plugin_slug . '-shortcode',
				$plugin_object . '_shortcode_object',
				array(
					'ajax_url' => admin_url( 'admin-ajax.php' ),
					'nonce'    => wp_create_nonce( 'basic' ),
				),
			);

		}

		/**
		 * Callback for 'mi-usage-table' shortcode.
		 *
		 * @param array $atts Shortcode attributes.
		 *
		 * @since 1.0.0
		 * @return string
		 */
		public function mi_usage_table_callback( $atts ) {

			$shortcode_atts = shortcode_atts(
				array(
					'show_title'          => 'true',
					'show_col_id'         => 'false',
					'show_col_first_name' => 'true',
					'show_col_last_name'  => 'true',
					'show_col_email'      => 'true',
					'show_col_date'       => 'true',
				),
				$atts,
			);

			$plugin_slug = $this->mi_usage->get_plugin_slug();
			wp_enqueue_script( $plugin_slug . '-shortcode' );

			$shortcode_atts['show_title']          = 'true' === $shortcode_atts['show_title'] ? 'true' : 'false';
			$shortcode_atts['show_col_id']         = 'true' === $shortcode_atts['show_col_id'] ? 'true' : 'false';
			$shortcode_atts['show_col_first_name'] = 'true' === $shortcode_atts['show_col_first_name'] ? 'true' : 'false';
			$shortcode_atts['show_col_last_name']  = 'true' === $shortcode_atts['show_col_last_name'] ? 'true' : 'false';
			$shortcode_atts['show_col_email']      = 'true' === $shortcode_atts['show_col_email'] ? 'true' : 'false';
			$shortcode_atts['show_col_date']       = 'true' === $shortcode_atts['show_col_date'] ? 'true' : 'false';

			return '<div class="mi-usage-table-wrapper mi-usage-table-shortcode" data-show-title="' . $shortcode_atts['show_title'] . '" data-show-col-id="' . $shortcode_atts['show_col_id'] . '" data-show-col-first-name="' . $shortcode_atts['show_col_first_name'] . '" data-show-col-last-name="' . $shortcode_atts['show_col_last_name'] . '" data-show-col-email="' . $shortcode_atts['show_col_email'] . '" data-show-col-date="' . $shortcode_atts['show_col_date'] . '">' . __( 'loading...', MI_USAGE_TEXT_DOMAIN ) . '</div>';

		}

	}
}

MI_Usage_Shortcodes::get_instance();
