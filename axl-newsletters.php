<?php

/**
 * @link              http://wpaxl.com
 * @since             1.0.0
 * @package           Axl_Newsletters
 *
 * Plugin Name:       Axl Newsletters
 * Plugin URI:        http://wpaxl.com/axl-newsletters
 * Description:       Create, manage and send newsletters right from your WordPress dashboard.
 * Version:           1.0.0
 * Author:            Chris Aprea
 * Author URI:        http://wpaxl.com
 * License:           GPL-2.0
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       axl-newsletters
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * @internal Nobody should be able to overrule the real version number as this can cause serious issues
 * with the options, so no if ( ! defined() )
 */
define( 'AXL_NEWSLETTERS_VERSION', '1.0.0' );

if ( ! defined( 'AXL_NEWSLETTERS_FILE' ) ) {
	define( 'AXL_NEWSLETTERS_FILE', __FILE__ );
}

if ( ! defined( 'AXL_NEWSLETTERS_PATH' ) ) {
	define( 'AXL_NEWSLETTERS_PATH', plugin_dir_path( AXL_NEWSLETTERS_FILE ) );
}

if ( ! defined( 'AXL_NEWSLETTERS_BASENAME' ) ) {
	define( 'AXL_NEWSLETTERS_BASENAME', plugin_basename( AXL_NEWSLETTERS_FILE ) );
}

if ( ! defined( 'AXL_NEWSLETTERS_CSSJS_SUFFIX' ) ) {
	define( 'AXL_NEWSLETTERS_CSSJS_SUFFIX', ( ( defined( 'SCRIPT_DEBUG' ) && true === SCRIPT_DEBUG ) ? '' : '.min' ) );
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-axl-newsletters-activator.php
 */
function activate_axl_newsletters() {
	require_once AXL_NEWSLETTERS_PATH . 'includes/class-axl-newsletters-activator.php';
	Axl_Newsletters_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-axl-newsletters-deactivator.php
 */
function deactivate_axl_newsletters() {
	require_once AXL_NEWSLETTERS_PATH . 'includes/class-axl-newsletters-deactivator.php';
	Axl_Newsletters_Deactivator::deactivate();
}

register_activation_hook(   AXL_NEWSLETTERS_FILE, 'activate_axl_newsletters'   );
register_deactivation_hook( AXL_NEWSLETTERS_FILE, 'deactivate_axl_newsletters' );

/**
 * The core plugin class that is used to define internationalization,
 * dashboard-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( AXL_NEWSLETTERS_FILE ) . 'includes/class-axl-newsletters.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since 1.0.0
 */
function run_axl_newsletters() {

	$plugin = new Axl_Newsletters();
	$plugin->run();
}

run_axl_newsletters();