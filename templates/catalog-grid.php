<?php
/**
 * Product catalog grid template.
 *
 * @package WP_Product_Catalog_Manager
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! isset( $wpcm_query ) || ! ( $wpcm_query instanceof WP_Query ) ) {
	return;
}

$wpcm_get_meta_value = static function ( $post_id, $meta_key ) {
	$value = get_post_meta( $post_id, $meta_key, true );

	if ( ! is_scalar( $value ) ) {
		return '';
	}

	return trim( (string) $value );
};
?>
<section
	class="wpcm-catalog"
	aria-label="<?php echo esc_attr__( 'Product catalog', 'wp-product-catalog-manager' ); ?>"
>
	<?php if ( $wpcm_query->have_posts() ) : ?>
		<div class="wpcm-catalog__grid" role="list">
			<?php
			while ( $wpcm_query->have_posts() ) :
				$wpcm_query->the_post();

				$wpcm_product_id    = get_the_ID();
				$wpcm_product_title = get_the_title( $wpcm_product_id );
				$wpcm_product_url   = get_permalink( $wpcm_product_id );
				$wpcm_display_price = $wpcm_get_meta_value(
					$wpcm_product_id,
					WPCM_Meta_Boxes::META_DISPLAY_PRICE
				);
				$wpcm_price         = $wpcm_get_meta_value( $wpcm_product_id, WPCM_Meta_Boxes::META_PRICE );
				$wpcm_price         = '' !== $wpcm_display_price ? $wpcm_display_price : $wpcm_price;
				$wpcm_metadata      = array(
					__( 'SKU', 'wp-product-catalog-manager' )        => $wpcm_get_meta_value(
						$wpcm_product_id,
						WPCM_Meta_Boxes::META_SKU
					),
					__( 'Material', 'wp-product-catalog-manager' )   => $wpcm_get_meta_value(
						$wpcm_product_id,
						WPCM_Meta_Boxes::META_MATERIAL
					),
					__( 'Dimensions', 'wp-product-catalog-manager' ) => $wpcm_get_meta_value(
						$wpcm_product_id,
						WPCM_Meta_Boxes::META_DIMENSIONS
					),
					__( 'Price', 'wp-product-catalog-manager' )      => $wpcm_price,
				);
				?>
				<article class="wpcm-product-card" role="listitem">
					<?php if ( has_post_thumbnail( $wpcm_product_id ) ) : ?>
						<a class="wpcm-product-card__image-link" href="<?php echo esc_url( $wpcm_product_url ); ?>">
							<?php
							echo wp_kses_post(
								get_the_post_thumbnail(
									$wpcm_product_id,
									'medium',
									array(
										'class'   => 'wpcm-product-card__image',
										'loading' => 'lazy',
									)
								)
							);
							?>
						</a>
					<?php endif; ?>

					<div class="wpcm-product-card__content">
						<h2 class="wpcm-product-card__title">
							<a href="<?php echo esc_url( $wpcm_product_url ); ?>">
								<?php echo esc_html( $wpcm_product_title ); ?>
							</a>
						</h2>

						<?php if ( array_filter( $wpcm_metadata, 'strlen' ) ) : ?>
							<dl class="wpcm-product-card__meta">
								<?php foreach ( $wpcm_metadata as $wpcm_label => $wpcm_value ) : ?>
									<?php if ( '' !== $wpcm_value ) : ?>
										<div class="wpcm-product-card__meta-row">
											<dt><?php echo esc_html( $wpcm_label ); ?></dt>
											<dd><?php echo esc_html( $wpcm_value ); ?></dd>
										</div>
									<?php endif; ?>
								<?php endforeach; ?>
							</dl>
						<?php endif; ?>
					</div>
				</article>
			<?php endwhile; ?>
		</div>
	<?php else : ?>
		<p class="wpcm-catalog__empty">
			<?php echo esc_html__( 'No products found.', 'wp-product-catalog-manager' ); ?>
		</p>
	<?php endif; ?>
</section>
