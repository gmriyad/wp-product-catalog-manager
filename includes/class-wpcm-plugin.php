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
require_once WPCM_PLUGIN_DIR . 'includes/class-wpcm-meta-boxes.php';

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
		$post_type  = new WPCM_Post_Type();
		$taxonomy   = new WPCM_Taxonomy();
		$meta_boxes = new WPCM_Meta_Boxes();

		add_action( 'init', array( $post_type, 'register' ) );
		add_action( 'init', array( $taxonomy, 'register' ) );
		add_action( 'add_meta_boxes_wpcm_product', array( $meta_boxes, 'register_meta_box' ), 10, 0 );
		add_action( 'save_post_wpcm_product', array( $meta_boxes, 'save' ) );
	}
}
