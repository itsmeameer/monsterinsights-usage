<?php
/**
 * Static methods to execute during activation and deactivation of the plugin.
 *
 * @link       https://about.me/itsmeameer
 * @since      1.0.0
 *
 * @package    MonsterInsights_Usage
 * @subpackage MonsterInsights_Usage/includes
 */

if ( ! class_exists( 'MI_Usage_Setup' ) ) {

	/**
	 * Static methods to execute during activation and deactivation of the plugin.
	 *
	 * @since      1.0.0
	 *
	 * @package    MonsterInsights_Usage
	 * @subpackage MonsterInsights_Usage/includes
	 * @author     Ameer Humza <itsmeameer@gmail.com>
	 */
	class MI_Usage_Setup {

		/**
		 * Fires when the plugin is activated.
		 *
		 * @since    1.0.0
		 * @access   private
		 */
		public static function activate() {
			// Do something when the plugin is activated.
		}

		/**
		 * Fires when the plugin is deactivated.
		 *
		 * @since    1.0.0
		 * @access   private
		 */
		public static function deactivate() {
			// Do something when the plugin is deactivated.
		}

	}
}
