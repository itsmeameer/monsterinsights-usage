<?php
/**
 * The class responsible for handling all AJAX requests.
 *
 * @link       https://about.me/itsmeameer
 * @since      1.0.0
 *
 * @package    MonsterInsights_Usage
 * @subpackage MonsterInsights_Usage/includes
 */

if ( ! class_exists( 'MI_Usage_AJAX' ) ) {

	/**
	 * Handles all shortcodes.
	 *
	 * @since      1.0.0
	 * @package    MonsterInsights_Usage
	 * @subpackage MonsterInsights_Usage/includes
	 * @author     Ameer Humza <itsmeameer@gmail.com>
	 */
	class MI_Usage_AJAX {

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

			$this->register_requests();

		}

		/**
		 * Get singleton instance.
		 *
		 * @since 1.0.0
		 * @return MI_Usage_AJAX Singleton object of MI_Usage_AJAX
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
		 * Register all AJAX requests.
		 *
		 * @since 1.0.0
		 * @return void
		 */
		private function register_requests() {

			// Front-end AJAX requests.
			add_action( 'wp_ajax_monsterinsights_usage_api_data', array( $this, 'api_table_front_end' ) );
			add_action( 'wp_ajax_nopriv_monsterinsights_usage_api_data', array( $this, 'api_table_front_end' ) );

			// Admin AJAX requests.
			add_action( 'wp_ajax_monsterinsights_usage_api_data_admin', array( $this, 'api_table_front_end_admin' ) );

		}

		/**
		 * Callback for 'mi-usage-table' shortcode.
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function api_table_front_end() {

			check_ajax_referer( 'basic', 'nonce' );

			$api = new MI_Usage_API();

			// Get formatted data.
			$data = $api->get_data( true, 'default' );

			echo wp_json_encode( $data );

			wp_die();

		}

		/**
		 * Callback for 'mi-usage-table' admin page.
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function api_table_front_end_admin() {

			check_ajax_referer( 'admin', 'nonce' );

			if ( ! current_user_can( 'manage_options' ) ) {
				// User is not allowed to access this page.
				wp_send_json_error( esc_html__( 'This user is forbidden!', 'monsterinsights-usage' ) );
				wp_die();
			}

			$api = new MI_Usage_API();

			// Get formatted data.
			$data = $api->get_data( false, 'default' );

			if ( ! $data || ! is_array( $data ) || empty( $data ) ) {
				wp_send_json_error( esc_html__( 'No data found.', 'monsterinsights-usage' ) );
			}

			$last_updated_on = $api->last_updated_on( 'default' );

			$data['last_update_text'] = sprintf( __( 'Data was last updated on %s', MI_USAGE_TEXT_DOMAIN ), $last_updated_on );

			echo wp_json_encode( $data );

			wp_die();

		}

	}
}

MI_Usage_AJAX::get_instance();
