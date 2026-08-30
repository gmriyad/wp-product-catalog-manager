<?php
/**
 * Product catalog shortcode.
 *
 * @package WP_Product_Catalog_Manager
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registers and renders the product catalog shortcode.
 */
class WPCM_Shortcode {
	/**
	 * Product catalog shortcode tag.
	 *
	 * @var string
	 */
	const TAG = 'wpcm_catalog';

	/**
	 * Registers the product catalog shortcode.
	 *
	 * @return void
	 */
	public function register() {
		add_shortcode( self::TAG, array( $this, 'render' ) );
	}

	/**
	 * Returns the rendered product catalog.
	 *
	 * @return string
	 */
	public function render() {
		$query_args = array(
			'post_type'           => WPCM_Post_Type::POST_TYPE,
			'post_status'         => 'publish',
			'posts_per_page'      => 12,
			'no_found_rows'       => true,
			'ignore_sticky_posts' => true,
		);

		$wpcm_query = new WP_Query( $query_args );

		ob_start();
		include WPCM_PLUGIN_DIR . 'templates/catalog-grid.php';
		$output = ob_get_clean();

		wp_reset_postdata();

		return (string) $output;
	}
}
