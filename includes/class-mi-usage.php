<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://about.me/itsmeameer
 * @since      1.0.0
 *
 * @package    MonsterInsights_Usage
 * @subpackage MonsterInsights_Usage/includes
 */

if ( ! class_exists( 'MI_Usage' ) ) {

	/**
	 * The core plugin class.
	 *
	 * This is used to define internationalization, admin-specific hooks, and
	 * public-facing site hooks.
	 *
	 * Also maintains the unique identifier of this plugin as well as the current
	 * version of the plugin.
	 *
	 * @since      1.0.0
	 * @package    MonsterInsights_Usage
	 * @subpackage MonsterInsights_Usage/includes
	 * @author     Ameer Humza <itsmeameer@gmail.com>
	 */
	final class MI_Usage {

		/**
		 * Text Domain.
		 *
		 * @var $text_domain
		 */
		private $text_domain = 'monsterinsights-usage';

		/**
		 * Plugin Version.
		 *
		 * @var $version
		 */
		private $version = '1.0.0';

		/**
		 * Plugin slug.
		 *
		 * @var $slug
		 */
		private $slug = 'monsterinsights-usage';

		/**
		 * Plugin name.
		 *
		 * @var $plugin_name
		 */
		private $plugin_name = 'MonsterInsights Usage';

		/**
		 * API URI from which to fetch data.
		 *
		 * @var $api_uri
		 */
		private $api_uri = 'https://miusage.com/v1/challenge/1/';

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

			$this->define_constants();
			$this->load_dependencies();
			$this->set_locale();
			$this->hooks();
		}

		/**
		 * Get singleton instance.
		 *
		 * @return MI_Usage Singleton object of MI_Usage
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
		 * @return void
		 */
		private function __clone() {
			_doing_it_wrong( __FUNCTION__, esc_html__( 'This action is forbidden!', 'monsterinsights-usage' ), '1.0.0' );
		}

		/**
		 * Unserializing instances of this class is forbidden.
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function __wakeup() {
			_doing_it_wrong( __FUNCTION__, esc_html__( 'This action is forbidden!', 'monsterinsights-usage' ), '1.0.0' );
		}

		/**
		 * Undocumented function
		 *
		 * @since 1.0.0
		 * @return void
		 */
		private function define_constants() {

			// Plugin text domain.
			if ( ! defined( 'MI_USAGE_TEXT_DOMAIN' ) ) {
				define( 'MI_USAGE_TEXT_DOMAIN', $this->text_domain );
			}

		}

		/**
		 * Include files.
		 *
		 * @since 1.0.0
		 * @return void
		 */
		private function load_dependencies() {

			/**
			 * The class responsible for defining internationalization functionality
			 * of the plugin.
			 */
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-mi-usage-i18n.php';

			/**
			 * The class responsible for handling all shortcodes.
			 */
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-mi-usage-shortcodes.php';

			/**
			 * The class responsible for handling all API calls and caching.
			 */
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-mi-usage-api.php';

			/**
			 * The class responsible for handling all AJAX requests.
			 */
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-mi-usage-ajax.php';

			/**
			 * The class responsible for handling all CLI commands.
			 */
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-mi-usage-cli.php';

			// Admin only functionality.
			if ( is_admin() ) {

				/**
				 * The class responsible for handling all admin functionality.
				 * Only users with 'administrator' capabilities can access this.
				 */
				require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-mi-usage-admin.php';
			}

		}

		/**
		 * Register all hooks.
		 *
		 * @since  1.0.0
		 * @return void
		 */
		private function hooks() {

			// Enqueue blocks.
			add_action( 'enqueue_block_editor_assets', array( $this, 'enqueue_block_editor_assets' ) );

		}

		/**
		 * Enqueue blocks.
		 *
		 * @since  1.0.0
		 * @return void
		 */
		public function enqueue_block_editor_assets() {

			$plugin_slug   = $this->get_plugin_slug();
			$plugin_object = $this->get_plugin_slug( true );

			// The table block.
			wp_enqueue_script(
				$plugin_slug . '-table-block',
				esc_url( plugins_url( 'blocks/table/index.js', dirname( __FILE__ ) ) ),
				array( 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor', 'wp-components' ),
				'1.0.0', // Block version.
				true
			);

			wp_localize_script(
				$plugin_slug . '-table-block',
				$plugin_object . '_table_block_object',
				array(
					'ajax_url' => admin_url( 'admin-ajax.php' ),
					'nonce'    => wp_create_nonce( 'basic' ),
				),
			);
		}

		/**
		 * Define the locale for this plugin for internationalization.
		 *
		 * Uses the MI_Usage_i18n class in order to set the domain and to register the hook
		 * with WordPress.
		 *
		 * @since    1.0.0
		 * @return   void
		 */
		private function set_locale() {

			$plugin_i18n = new MI_Usage_i18n( $this->text_domain );

			add_action( 'plugins_loaded', array( $plugin_i18n, 'load_plugin_textdomain' ) );

		}

		/**
		 * The name of the plugin used to uniquely identify it within the context of
		 * WordPress and to define internationalization functionality.
		 *
		 * @since     1.0.0
		 * @return    string    The name of the plugin.
		 */
		public function get_plugin_name() {
			return $this->plugin_name;
		}

		/**
		 * Retrieve the version number of the plugin.
		 *
		 * @since     1.0.0
		 * @return    string    The version number of the plugin.
		 */
		public function get_version() {
			return $this->version;
		}

		/**
		 * Retrieve the slug of the plugin.
		 *
		 * @param boolean $convert_to_var Whether to convert to variable or not.
		 *
		 * @since     1.0.0
		 * @return    string    The slug of the plugin.
		 */
		public function get_plugin_slug( $convert_to_var = false ) {

			if ( $convert_to_var ) {
				return str_replace( '-', '_', $this->slug );
			}

			return $this->slug;
		}

		/**
		 * Retrieve the API URI that returns data.
		 *
		 * @since     1.0.0
		 * @return    string    The API URI that returns data.
		 */
		public function get_api_uri() {
			return $this->api_uri;
		}


		/**
		 * API cooldown period in seconds
		 *
		 * @since  1.0.0
		 * @return int
		 */
		public function get_api_cooldown_period() {
			return 60 * 60;
		}

	}
}

MI_Usage::get_instance();
