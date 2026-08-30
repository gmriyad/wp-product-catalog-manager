<?php
/**
 * Product meta boxes and save handling.
 *
 * @package WP_Product_Catalog_Manager
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Manages the Product Details meta box and its saved values.
 */
class WPCM_Meta_Boxes {
	/**
	 * SKU meta key.
	 *
	 * @var string
	 */
	const META_SKU = 'wpcm_sku';

	/**
	 * Material meta key.
	 *
	 * @var string
	 */
	const META_MATERIAL = 'wpcm_material';

	/**
	 * Dimensions meta key.
	 *
	 * @var string
	 */
	const META_DIMENSIONS = 'wpcm_dimensions';

	/**
	 * Price meta key.
	 *
	 * @var string
	 */
	const META_PRICE = 'wpcm_price';

	/**
	 * Display Price meta key.
	 *
	 * @var string
	 */
	const META_DISPLAY_PRICE = 'wpcm_display_price';

	/**
	 * Product Details nonce action.
	 *
	 * @var string
	 */
	const NONCE_ACTION = 'wpcm_save_product_details';

	/**
	 * Product Details nonce field name.
	 *
	 * @var string
	 */
	const NONCE_NAME = 'wpcm_product_details_nonce';

	/**
	 * Registers the Product Details meta box.
	 *
	 * @return void
	 */
	public function register_meta_box() {
		add_meta_box(
			'wpcm_product_details',
			__( 'Product Details', 'wp-product-catalog-manager' ),
			array( $this, 'render_meta_box' ),
			WPCM_Post_Type::POST_TYPE,
			'normal',
			'default'
		);
	}

	/**
	 * Renders the Product Details meta box.
	 *
	 * @param WP_Post $post Product post object.
	 * @return void
	 */
	public function render_meta_box( $post ) {
		$fields = array(
			self::META_SKU           => __( 'SKU', 'wp-product-catalog-manager' ),
			self::META_MATERIAL      => __( 'Material', 'wp-product-catalog-manager' ),
			self::META_DIMENSIONS    => __( 'Dimensions', 'wp-product-catalog-manager' ),
			self::META_PRICE         => __( 'Price', 'wp-product-catalog-manager' ),
			self::META_DISPLAY_PRICE => __( 'Display Price', 'wp-product-catalog-manager' ),
		);

		wp_nonce_field( self::NONCE_ACTION, self::NONCE_NAME );

		foreach ( $fields as $meta_key => $label ) {
			$value = $this->get_meta_value( $post->ID, $meta_key );
			?>
			<p>
				<label for="<?php echo esc_attr( $meta_key ); ?>">
					<?php echo esc_html( $label ); ?>
				</label>
				<input
					type="text"
					class="widefat"
					id="<?php echo esc_attr( $meta_key ); ?>"
					name="<?php echo esc_attr( $meta_key ); ?>"
					value="<?php echo esc_attr( $value ); ?>"
				/>
			</p>
			<?php
		}
	}

	/**
	 * Saves submitted Product Details values.
	 *
	 * @param int $post_id Product post ID.
	 * @return void
	 */
	public function save( $post_id ) {
		if ( WPCM_Post_Type::POST_TYPE !== get_post_type( $post_id ) ) {
			return;
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		if ( wp_is_post_revision( $post_id ) ) {
			return;
		}

		if ( ! isset( $_POST[ self::NONCE_NAME ] ) || ! is_string( $_POST[ self::NONCE_NAME ] ) ) {
			return;
		}

		$nonce = sanitize_text_field( wp_unslash( $_POST[ self::NONCE_NAME ] ) );

		if ( ! wp_verify_nonce( $nonce, self::NONCE_ACTION ) ) {
			return;
		}

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		$this->save_text_meta( $post_id, self::META_SKU );
		$this->save_text_meta( $post_id, self::META_MATERIAL );
		$this->save_text_meta( $post_id, self::META_DIMENSIONS );
		$this->save_price_meta( $post_id );
		$this->save_text_meta( $post_id, self::META_DISPLAY_PRICE );
	}

	/**
	 * Returns a scalar meta value for display.
	 *
	 * @param int    $post_id  Product post ID.
	 * @param string $meta_key Meta key.
	 * @return string
	 */
	private function get_meta_value( $post_id, $meta_key ) {
		$value = get_post_meta( $post_id, $meta_key, true );

		if ( ! is_scalar( $value ) ) {
			return '';
		}

		return (string) $value;
	}

	/**
	 * Saves or deletes a submitted text meta value.
	 *
	 * @param int    $post_id  Product post ID.
	 * @param string $meta_key Meta key.
	 * @return void
	 */
	private function save_text_meta( $post_id, $meta_key ) {
		// Nonce and capability checks are completed before this method is called.
		// phpcs:ignore WordPress.Security.NonceVerification.Missing
		if ( ! array_key_exists( $meta_key, $_POST ) ) {
			return;
		}

		// phpcs:ignore WordPress.Security.NonceVerification.Missing
		$raw_value = wp_unslash( $_POST[ $meta_key ] );

		if ( ! is_string( $raw_value ) ) {
			return;
		}

		$value = sanitize_text_field( $raw_value );

		if ( '' === $value ) {
			delete_post_meta( $post_id, $meta_key );
			return;
		}

		update_post_meta( $post_id, $meta_key, $value );
	}

	/**
	 * Saves, deletes, or rejects a submitted Price value.
	 *
	 * @param int $post_id Product post ID.
	 * @return void
	 */
	private function save_price_meta( $post_id ) {
		// Nonce and capability checks are completed before this method is called.
		// phpcs:ignore WordPress.Security.NonceVerification.Missing
		if ( ! array_key_exists( self::META_PRICE, $_POST ) ) {
			return;
		}

		// phpcs:ignore WordPress.Security.NonceVerification.Missing
		$raw_value = wp_unslash( $_POST[ self::META_PRICE ] );

		if ( ! is_string( $raw_value ) ) {
			return;
		}

		$value = sanitize_text_field( $raw_value );

		if ( '' === $value ) {
			delete_post_meta( $post_id, self::META_PRICE );
			return;
		}

		$value = $this->normalize_price( $value );

		if ( null === $value ) {
			return;
		}

		update_post_meta( $post_id, self::META_PRICE, $value );
	}

	/**
	 * Validates and normalizes a non-negative decimal string without floats.
	 *
	 * @param string $value Sanitized submitted value.
	 * @return string|null
	 */
	private function normalize_price( $value ) {
		if ( 1 !== preg_match( '/\A\d+(?:\.\d+)?\z/', $value ) ) {
			return null;
		}

		$parts         = explode( '.', $value, 2 );
		$integer_part  = ltrim( $parts[0], '0' );
		$fraction_part = isset( $parts[1] ) ? rtrim( $parts[1], '0' ) : '';

		if ( '' === $integer_part ) {
			$integer_part = '0';
		}

		if ( '' === $fraction_part ) {
			return $integer_part;
		}

		return $integer_part . '.' . $fraction_part;
	}
}
