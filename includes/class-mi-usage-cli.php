<?php
/**
 * The class responsible for handling all CLI commands.
 *
 * @link       https://about.me/itsmeameer
 * @since      1.0.0
 *
 * @package    MonsterInsights_Usage
 * @subpackage MonsterInsights_Usage/includes
 */

if ( ! class_exists( 'MI_Usage_CLI' ) ) {

	/**
	 * Handles all shortcodes.
	 *
	 * @since      1.0.0
	 * @package    MonsterInsights_Usage
	 * @subpackage MonsterInsights_Usage/includes
	 * @author     Ameer Humza <itsmeameer@gmail.com>
	 */
	class MI_Usage_CLI {

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

			$this->hooks();

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
		 * Register hooks.
		 *
		 * @since 1.0.0
		 * @return void
		 */
		private function hooks() {

			add_action( 'cli_init', array( $this, 'register_commands' ) );

		}

		/**
		 * Register commands.
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function register_commands() {

			WP_CLI::add_command( 'mi-usage', array( $this, 'mi_usage_command' ) );

		}

		/**
		 * CLI commands to generate usage report.
		 *
		 * @param array $args
		 * @return void
		 */
		public function mi_usage_command( $args ) {

			$api = new MI_Usage_API();

			if ( isset( $args[0] ) ) {
				switch ( $args[0] ) {
					case 'last-updated-on':
						$last_updated_on = $api->last_updated_on( 'default' );
						$message = $last_updated_on ? sprintf( __( 'Data was last updated on %s', MI_USAGE_TEXT_DOMAIN ), $last_updated_on ) : __( 'Data has not been fetched yet!', MI_USAGE_TEXT_DOMAIN );
						WP_CLI::line( $message );
						break;

					case 'fetch-data':
						$update_data = $api->try_to_update_data();

						if ( $update_data ) {
							WP_CLI::success( __( 'New data has been fetched and stored.', MI_USAGE_TEXT_DOMAIN ) );
						} else {
							WP_CLI::error( __( 'Something went wrong and could not get the new data.', MI_USAGE_TEXT_DOMAIN ) );
						}
						break;

					default:
						WP_CLI::warning( __( 'This is not a valid command.', MI_USAGE_TEXT_DOMAIN ) );
						break;
				}
				return;
			}

			WP_CLI::line( sprintf( __( 'Please use the command "%1$s" to see the last update date and "%2$s" to fetch the data again.', MI_USAGE_TEXT_DOMAIN ), 'last-updated-on', 'fetch-data' ) );

		}

	}
}

MI_Usage_CLI::get_instance();
