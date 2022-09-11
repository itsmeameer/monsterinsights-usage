<?php
/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://about.me/itsmeameer
 * @since      1.0.0
 *
 * @package    MonsterInsights_Usage
 * @subpackage MonsterInsights_Usage/includes
 */

if ( ! class_exists( 'MI_Usage_i18n' ) ) {

	/**
	 * Define the internationalization functionality.
	 *
	 * Loads and defines the internationalization files for this plugin
	 * so that it is ready for translation.
	 *
	 * @since      1.0.0
	 * @package    MonsterInsights_Usage
	 * @subpackage MonsterInsights_Usage/includes
	 * @author     Ameer Humza <itsmeameer@gmail.com>
	 */
	class MI_Usage_i18n {

		/**
		 * Text Domain
		 *
		 * @var $text_domain
		 */
		private $text_domain;

		/**
		 * Constructor
		 */
		public function __construct( $text_domain ) {
			$this->text_domain = $text_domain;
		}

		/**
		 * Load the plugin text domain for translation.
		 *
		 * @since    1.0.0
		 */
		public function load_plugin_textdomain() {

			load_plugin_textdomain(
				$this->text_domain,
				false,
				dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
			);

		}

	}
}
