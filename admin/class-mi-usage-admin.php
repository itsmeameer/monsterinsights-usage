<?php
/**
 * The class responsible for handling all admin related functionality.
 *
 * @link       https://about.me/itsmeameer
 * @since      1.0.0
 *
 * @package    MonsterInsights_Usage
 * @subpackage MonsterInsights_Usage/admin
 */

if ( ! class_exists( 'MI_Usage_Admin' ) ) {

	/**
	 * Handles all shortcodes.
	 *
	 * @since      1.0.0
	 * @package    MonsterInsights_Usage
	 * @subpackage MonsterInsights_Usage/admin
	 * @author     Ameer Humza <itsmeameer@gmail.com>
	 */
	class MI_Usage_Admin {

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
		 *
		 * @return MI_Usage_Admin Singleton object of MI_Usage_Admin
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
		 *
		 * @return void
		 */
		private function hooks() {

			add_action( 'admin_enqueue_scripts', array( $this, 'load_assets' ) );
			add_action( 'admin_menu', array( $this, 'add_menu_page' ) );

		}

		/**
		 * Enqueue assets.
		 *
		 * @since 1.0.0
		 *
		 * @return void
		 */
		public function load_assets( $hook ) {

			if ( 'tools_page_monsterinsights_usage' === $hook ) {

				$plugin_slug   = $this->mi_usage->get_plugin_slug();
				$plugin_object = $this->mi_usage->get_plugin_slug( true );

				// Main CSS file.
				wp_enqueue_style(
					$plugin_slug . '-admin',
					plugins_url( 'assets/css/admin.css', dirname( __FILE__ ) ),
					array(),
					$this->mi_usage->get_version(),
				);

				// Main JS file.
				wp_enqueue_script(
					$plugin_slug . '-admin',
					plugins_url( 'assets/js/admin.js', dirname( __FILE__ ) ),
					array( 'wp-i18n' ),
					$this->mi_usage->get_version(),
					true,
				);

				wp_localize_script(
					$plugin_slug . '-admin',
					$plugin_object . '_admin_object',
					array(
						'ajax_url' => admin_url( 'admin-ajax.php' ),
						'nonce'    => wp_create_nonce( 'admin' ),
					),
				);

			}
		}

		/**
		 * Add sub menu page.
		 *
		 * @since 1.0.0
		 *
		 * @return void
		 */
		public function add_menu_page() {

			/**
			 * Will add a submenu under "Tools"
			 */
			add_submenu_page(
				'tools.php',
				$this->mi_usage->get_plugin_name(),
				$this->mi_usage->get_plugin_name(),
				'manage_options',
				$this->mi_usage->get_plugin_slug( true ),
				array( $this, 'page_contents' ),
				null
			);

		}

		/**
		 * Loads the view template.
		 *
		 * @since 1.0.0
		 *
		 * @return void
		 */
		public function page_contents() {

			$mi_usage = $this->mi_usage;
			$api      = new MI_Usage_API();

			require plugin_dir_path( dirname( __FILE__ ) ) . 'admin/mi-usage-admin-display.php';

		}

	}
}

MI_Usage_Admin::get_instance();
