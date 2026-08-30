<?php
/**
 * Core plugin bootstrap.
 *
 * @package WP_Product_Catalog_Manager
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once WPCM_PLUGIN_DIR . 'includes/class-wpcm-post-type.php';
require_once WPCM_PLUGIN_DIR . 'includes/class-wpcm-taxonomy.php';

/**
 * Coordinates plugin initialization.
 */
class WPCM_Plugin {
	/**
	 * Runs the plugin.
	 *
	 * @return void
	 */
	public function run() {
		$post_type = new WPCM_Post_Type();
		$taxonomy  = new WPCM_Taxonomy();

		add_action( 'init', array( $post_type, 'register' ) );
		add_action( 'init', array( $taxonomy, 'register' ) );
	}
}
