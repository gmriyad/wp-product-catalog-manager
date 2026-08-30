<?php
/**
 * Product post type registration.
 *
 * @package WP_Product_Catalog_Manager
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registers the Product custom post type.
 */
class WPCM_Post_Type {
	/**
	 * Product post type key.
	 *
	 * @var string
	 */
	const POST_TYPE = 'wpcm_product';

	/**
	 * Registers the Product custom post type.
	 *
	 * @return void
	 */
	public function register() {
		$labels = array(
			'name'                  => _x( 'Products', 'Post type general name', 'wp-product-catalog-manager' ),
			'singular_name'         => _x( 'Product', 'Post type singular name', 'wp-product-catalog-manager' ),
			'menu_name'             => _x( 'Products', 'Admin menu text', 'wp-product-catalog-manager' ),
			'name_admin_bar'        => _x( 'Product', 'Add new on toolbar', 'wp-product-catalog-manager' ),
			'add_new'               => __( 'Add New', 'wp-product-catalog-manager' ),
			'add_new_item'          => __( 'Add New Product', 'wp-product-catalog-manager' ),
			'new_item'              => __( 'New Product', 'wp-product-catalog-manager' ),
			'edit_item'             => __( 'Edit Product', 'wp-product-catalog-manager' ),
			'view_item'             => __( 'View Product', 'wp-product-catalog-manager' ),
			'all_items'             => __( 'All Products', 'wp-product-catalog-manager' ),
			'search_items'          => __( 'Search Products', 'wp-product-catalog-manager' ),
			'not_found'             => __( 'No products found.', 'wp-product-catalog-manager' ),
			'not_found_in_trash'    => __( 'No products found in Trash.', 'wp-product-catalog-manager' ),
			'featured_image'        => __( 'Product Image', 'wp-product-catalog-manager' ),
			'set_featured_image'    => __( 'Set product image', 'wp-product-catalog-manager' ),
			'remove_featured_image' => __( 'Remove product image', 'wp-product-catalog-manager' ),
			'use_featured_image'    => __( 'Use as product image', 'wp-product-catalog-manager' ),
			'archives'              => __( 'Product Archives', 'wp-product-catalog-manager' ),
			'items_list'            => __( 'Products list', 'wp-product-catalog-manager' ),
			'items_list_navigation' => __( 'Products list navigation', 'wp-product-catalog-manager' ),
		);

		$args = array(
			'labels'             => $labels,
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array(
				'slug'       => 'products',
				'with_front' => true,
			),
			'capability_type'    => 'post',
			'map_meta_cap'       => true,
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => 20,
			'menu_icon'          => 'dashicons-products',
			'supports'           => array( 'title', 'editor', 'thumbnail' ),
			'show_in_rest'       => false,
		);

		register_post_type( self::POST_TYPE, $args );
	}
}
