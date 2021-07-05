<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              micahpress.com
 * @since             1.0.0
 * @package           Geo_Stats
 *
 * @wordpress-plugin
 * Plugin Name:       User Geo Stats
 * Plugin URI:        micahpress.com/geo-stats
 * Description:       View census for users that have provided their location
 * Version:           1.0.0
 * Author:            Micah Coffey
 * Author URI:        micahpress.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       geo-stats
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'GEO_STATS_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-geo-stats-activator.php
 */
function activate_geo_stats() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-geo-stats-activator.php';
	Geo_Stats_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-geo-stats-deactivator.php
 */
function deactivate_geo_stats() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-geo-stats-deactivator.php';
	Geo_Stats_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_geo_stats' );
register_deactivation_hook( __FILE__, 'deactivate_geo_stats' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-geo-stats.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_geo_stats() {

	$plugin = new Geo_Stats();
	$plugin->run();

}
run_geo_stats();
