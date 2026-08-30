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
	 * Product Category request parameter.
	 *
	 * @var string
	 */
	const CATEGORY_PARAMETER = 'wpcm_category';

	/**
	 * Keyword search request parameter.
	 *
	 * @var string
	 */
	const SEARCH_PARAMETER = 'wpcm_search';

	/**
	 * Catalog page request parameter.
	 *
	 * @var string
	 */
	const PAGE_PARAMETER = 'wpcm_page';

	/**
	 * Maximum catalog page accepted from a request.
	 *
	 * @var int
	 */
	const MAX_PAGE = 10000;

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
		$wpcm_selected_category = $this->get_selected_category();
		$wpcm_search            = sanitize_text_field(
			$this->get_request_string( self::SEARCH_PARAMETER )
		);
		$wpcm_page              = $this->get_catalog_page();

		$query_args = array(
			'post_type'           => WPCM_Post_Type::POST_TYPE,
			'post_status'         => 'publish',
			'posts_per_page'      => 12,
			'ignore_sticky_posts' => true,
			'paged'               => $wpcm_page,
			'no_found_rows'       => false,
		);

		if ( '' !== $wpcm_search ) {
			$query_args['s'] = $wpcm_search;
		}

		if ( '' !== $wpcm_selected_category ) {
			$query_args['tax_query'] = array(
				array(
					'taxonomy' => WPCM_Taxonomy::TAXONOMY,
					'field'    => 'slug',
					'terms'    => $wpcm_selected_category,
				),
			);
		}

		$wpcm_categories       = $this->get_categories();
		$wpcm_form_action      = $this->get_form_action();
		$wpcm_query            = new WP_Query( $query_args );
		$wpcm_pagination_links = $this->get_pagination_links(
			$wpcm_form_action,
			$wpcm_selected_category,
			$wpcm_search,
			$wpcm_page,
			(int) $wpcm_query->max_num_pages
		);

		ob_start();
		include WPCM_PLUGIN_DIR . 'templates/catalog-grid.php';
		$output = ob_get_clean();

		wp_reset_postdata();

		return (string) $output;
	}

	/**
	 * Returns an unslashed scalar request value.
	 *
	 * @param string $parameter Request parameter name.
	 * @return string
	 */
	private function get_request_string( $parameter ) {
		// This is a read-only catalog filter, so a nonce is not required.
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( ! isset( $_GET[ $parameter ] ) || ! is_string( $_GET[ $parameter ] ) ) {
			return '';
		}

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		return wp_unslash( $_GET[ $parameter ] );
	}

	/**
	 * Returns a controlled positive catalog page number.
	 *
	 * @return int
	 */
	private function get_catalog_page() {
		$page = $this->get_request_string( self::PAGE_PARAMETER );

		if ( '' === $page || 1 !== preg_match( '/\A\d+\z/', $page ) ) {
			return 1;
		}

		$page = absint( $page );

		if ( $page < 1 ) {
			return 1;
		}

		return min( $page, self::MAX_PAGE );
	}

	/**
	 * Returns a validated Product Category slug.
	 *
	 * @return string
	 */
	private function get_selected_category() {
		$category_slug = sanitize_title( $this->get_request_string( self::CATEGORY_PARAMETER ) );

		if ( '' === $category_slug ) {
			return '';
		}

		$term = get_term_by( 'slug', $category_slug, WPCM_Taxonomy::TAXONOMY );

		if (
			! $term ||
			is_wp_error( $term ) ||
			! is_object( $term ) ||
			! isset( $term->taxonomy ) ||
			WPCM_Taxonomy::TAXONOMY !== $term->taxonomy
		) {
			return '';
		}

		return $category_slug;
	}

	/**
	 * Returns available Product Categories for the filter form.
	 *
	 * @return array
	 */
	private function get_categories() {
		$categories = get_terms(
			array(
				'taxonomy'   => WPCM_Taxonomy::TAXONOMY,
				'hide_empty' => false,
				'orderby'    => 'name',
				'order'      => 'ASC',
			)
		);

		if ( is_wp_error( $categories ) || ! is_array( $categories ) ) {
			return array();
		}

		return $categories;
	}

	/**
	 * Returns a clean form action for the current catalog page.
	 *
	 * @return string
	 */
	private function get_form_action() {
		$object_id = get_queried_object_id();
		$permalink = $object_id ? get_permalink( $object_id ) : '';

		if ( ! is_string( $permalink ) || '' === $permalink ) {
			return home_url( '/' );
		}

		return $permalink;
	}

	/**
	 * Builds pagination links from approved catalog state only.
	 *
	 * @param string $form_action      Clean catalog page URL.
	 * @param string $category_slug    Validated Product Category slug.
	 * @param string $search           Sanitized keyword search.
	 * @param int    $current_page     Current catalog page.
	 * @param int    $total_pages      Total catalog pages.
	 * @return array
	 */
	private function get_pagination_links(
		$form_action,
		$category_slug,
		$search,
		$current_page,
		$total_pages
	) {
		if ( $total_pages <= 1 ) {
			return array();
		}

		$state = array();

		if ( '' !== $category_slug ) {
			$state[ self::CATEGORY_PARAMETER ] = $category_slug;
		}

		if ( '' !== $search ) {
			$state[ self::SEARCH_PARAMETER ] = $search;
		}

		$base_url  = add_query_arg( $state, $form_action );
		$separator = false === strpos( $base_url, '?' ) ? '?' : '&';
		$links     = paginate_links(
			array(
				'base'      => $base_url . '%_%',
				'format'    => $separator . self::PAGE_PARAMETER . '=%#%',
				'current'   => $current_page,
				'total'     => $total_pages,
				'prev_text' => __( 'Previous', 'wp-product-catalog-manager' ),
				'next_text' => __( 'Next', 'wp-product-catalog-manager' ),
				'type'      => 'array',
				'add_args'  => false,
			)
		);

		return is_array( $links ) ? $links : array();
	}
}
