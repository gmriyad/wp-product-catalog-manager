<?php
/**
 * Plugin Name: WP Product Catalog Manager
 * Description: Provides the bootstrap for the WP Product Catalog Manager plugin.
 * Version: 0.1.0
 * Text Domain: wp-product-catalog-manager
 *
 * @package WP_Product_Catalog_Manager
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'WPCM_VERSION', '0.1.0' );
define( 'WPCM_PLUGIN_FILE', __FILE__ );
define( 'WPCM_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'WPCM_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

require_once WPCM_PLUGIN_DIR . 'includes/class-wpcm-plugin.php';

/**
 * Starts the plugin after WordPress has loaded active plugins.
 *
 * @return void
 */
function wpcm_bootstrap() {
	$plugin = new WPCM_Plugin();
	$plugin->run();
}

add_action( 'plugins_loaded', 'wpcm_bootstrap' );
