<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area, generate a shortcode and a block. This file also includes all of the
 * dependencies used by the plugin, registers the activation and deactivation functions,
 * and defines a function that starts the plugin.
 *
 * @link              https://about.me/itsmeameer
 * @since             1.0.0
 * @package           MonsterInsights_Usage
 *
 * @wordpress-plugin
 * Plugin Name:       MonsterInsights Usage
 * Plugin URI:        https://about.me/itsmeameer
 * Description:       Just a simple plugin to show how to use MI Usage via a shortcode and a block.
 * Version:           1.0.0
 * Author:            Ameer Humza
 * Author URI:        https://about.me/itsmeameer
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       monsterinsights-usage
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Includes class with static methods to execute during activation and
 * deactivation of the plugin.
 */
require_once plugin_dir_path( __FILE__ ) . 'includes/class-mi-usage-setup.php';

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-mi-usage-setup.php
 */
register_activation_hook( __FILE__, array( 'MI_Usage_Setup', 'activate' ) );

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-mi-usage-setup.php
 */
register_deactivation_hook( __FILE__, array( 'MI_Usage_Setup', 'deactivate' ) );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-mi-usage.php';
