<?php
/**
 * Front-end asset handling.
 *
 * @package WP_Product_Catalog_Manager
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Loads product catalog front-end assets when needed.
 */
class WPCM_Assets {
	/**
	 * Catalog stylesheet handle.
	 *
	 * @var string
	 */
	const STYLE_HANDLE = 'wpcm-catalog';

	/**
	 * Enqueues the catalog stylesheet when the current post uses the shortcode.
	 *
	 * @return void
	 */
	public function enqueue_catalog_style() {
		if ( ! is_singular() ) {
			return;
		}

		$post = get_post();

		if ( ! $post || ! isset( $post->post_content ) || ! is_string( $post->post_content ) ) {
			return;
		}

		if ( ! has_shortcode( $post->post_content, WPCM_Shortcode::TAG ) ) {
			return;
		}

		wp_enqueue_style(
			self::STYLE_HANDLE,
			WPCM_PLUGIN_URL . 'assets/css/frontend.css',
			array(),
			WPCM_VERSION
		);
	}
}
