<?php
/**
 * Product Category taxonomy registration.
 *
 * @package WP_Product_Catalog_Manager
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registers the Product Category custom taxonomy.
 */
class WPCM_Taxonomy {
	/**
	 * Product Category taxonomy key.
	 *
	 * @var string
	 */
	const TAXONOMY = 'wpcm_product_category';

	/**
	 * Registers the Product Category taxonomy for Products.
	 *
	 * @return void
	 */
	public function register() {
		$labels = array(
			'name'                  => _x( 'Product Categories', 'Taxonomy general name', 'wp-product-catalog-manager' ),
			'singular_name'         => _x( 'Product Category', 'Taxonomy singular name', 'wp-product-catalog-manager' ),
			'search_items'          => __( 'Search Product Categories', 'wp-product-catalog-manager' ),
			'all_items'             => __( 'All Product Categories', 'wp-product-catalog-manager' ),
			'parent_item'           => __( 'Parent Product Category', 'wp-product-catalog-manager' ),
			'parent_item_colon'     => __( 'Parent Product Category:', 'wp-product-catalog-manager' ),
			'edit_item'             => __( 'Edit Product Category', 'wp-product-catalog-manager' ),
			'view_item'             => __( 'View Product Category', 'wp-product-catalog-manager' ),
			'update_item'           => __( 'Update Product Category', 'wp-product-catalog-manager' ),
			'add_new_item'          => __( 'Add New Product Category', 'wp-product-catalog-manager' ),
			'new_item_name'         => __( 'New Product Category Name', 'wp-product-catalog-manager' ),
			'menu_name'             => __( 'Product Categories', 'wp-product-catalog-manager' ),
			'not_found'             => __( 'No product categories found.', 'wp-product-catalog-manager' ),
			'items_list'            => __( 'Product Categories list', 'wp-product-catalog-manager' ),
			'items_list_navigation' => __( 'Product Categories list navigation', 'wp-product-catalog-manager' ),
			'back_to_items'         => __( '&larr; Go to Product Categories', 'wp-product-catalog-manager' ),
		);

		$args = array(
			'labels'             => $labels,
			'public'             => true,
			'publicly_queryable' => true,
			'hierarchical'       => true,
			'show_ui'            => true,
			'show_admin_column'  => true,
			'show_in_nav_menus'  => true,
			'show_tagcloud'      => false,
			'show_in_quick_edit' => true,
			'query_var'          => true,
			'rewrite'            => array(
				'slug'         => 'product-category',
				'with_front'   => true,
				'hierarchical' => true,
			),
			'show_in_rest'       => false,
		);

		register_taxonomy(
			self::TAXONOMY,
			array( WPCM_Post_Type::POST_TYPE ),
			$args
		);
	}
}
