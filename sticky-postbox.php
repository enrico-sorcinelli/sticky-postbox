<?php
/**
 * Sticky Postbox plugin for WordPress.
 *
 * @package sticky-postbox
 *
 * Plugin Name: Sticky Postbox
 * Plugin URI:  https://github.com/enrico-sorcinelli/sticky-postbox
 * Description: A WordPress plugin that allow to sticky administration postboxes.
 * Author:      Enrico Sorcinelli
 * Author URI:  https://github.com/enrico-sorcinelli/sticky-postbox/graphs/contributors
 * Text Domain: sticky-postbox
 * Domain Path: /languages/
 * Version:     1.3.0
 * License:     GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

// Check running WordPress instance.
if ( ! defined( 'ABSPATH' ) ) {
	header( 'HTTP/1.1 404 Not Found' );
	exit();
}

// Plugins constants.
define( 'STICKY_POSTBOX_VERSION', '1.3.0' );
define( 'STICKY_POSTBOX_BASEDIR', dirname( __FILE__ ) );
define( 'STICKY_POSTBOX_BASEURL', plugin_dir_url( __FILE__ ) );

// Default values.
foreach( array( 'STICKY_POSTBOX_DEBUG', 'STICKY_POSTBOX_GLOBAL_OPTIONS' ) as $key ) {
	if ( ! defined( $key ) ) {
		define( $key, false );
	}
}

if ( ! class_exists( 'Sticky_Postbox' ) ) {

	require_once STICKY_POSTBOX_BASEDIR . '/php/class-sticky-postbox.php';

	/**
	 * Init the plugin.
	 *
	 * Define STICKY_POSTBOX_AUTOENABLE to `false` in your wp-config.php to disable.
	 */
	function sticky_postbox_init() {

		if ( defined( 'STICKY_POSTBOX_AUTOENABLE' ) && false === STICKY_POSTBOX_AUTOENABLE ) {
			return;
		}

		// Instantiate our plugin class and add it to the set of globals.
		// Create plugin instance object only under administration interface.
		if ( is_admin() || is_network_admin() ) {
			$GLOBALS['sticky_postbox'] = \Sticky_Postbox::get_instance( 
				array( 
					'debug'          => STICKY_POSTBOX_DEBUG && WP_DEBUG,
					'global_options' => STICKY_POSTBOX_GLOBAL_OPTIONS,
				)
			);
		}
	}

	// Activate the plugin once all plugin have been loaded.
	add_action( 'plugins_loaded', 'sticky_postbox_init' );

	// Activation/Deactivation hooks.
	register_uninstall_hook( __FILE__, array( 'Sticky_Postbox', 'plugin_uninstall' ) );
}
