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

if ( ! isset( $wpcm_categories ) || ! is_array( $wpcm_categories ) ) {
	$wpcm_categories = array();
}

if ( ! isset( $wpcm_selected_category ) || ! is_string( $wpcm_selected_category ) ) {
	$wpcm_selected_category = '';
}

if ( ! isset( $wpcm_search ) || ! is_string( $wpcm_search ) ) {
	$wpcm_search = '';
}

if ( ! isset( $wpcm_form_action ) || ! is_string( $wpcm_form_action ) ) {
	$wpcm_form_action = '';
}

if ( ! isset( $wpcm_pagination_links ) || ! is_array( $wpcm_pagination_links ) ) {
	$wpcm_pagination_links = array();
}

$wpcm_pagination_label = __( 'Product catalog pagination', 'wp-product-catalog-manager' );

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
	<form
		class="wpcm-catalog__filters"
		method="get"
		action="<?php echo esc_url( $wpcm_form_action ); ?>"
	>
		<div class="wpcm-catalog__filter-field">
			<label for="wpcm-category-filter">
				<?php echo esc_html__( 'Product Category', 'wp-product-catalog-manager' ); ?>
			</label>
			<select id="wpcm-category-filter" name="wpcm_category">
				<option value="">
					<?php echo esc_html__( 'All Categories', 'wp-product-catalog-manager' ); ?>
				</option>
				<?php foreach ( $wpcm_categories as $wpcm_category ) : ?>
					<?php
					if (
						! is_object( $wpcm_category ) ||
						! isset( $wpcm_category->slug, $wpcm_category->name ) ||
						! is_scalar( $wpcm_category->slug ) ||
						! is_scalar( $wpcm_category->name )
					) {
						continue;
					}

					$wpcm_category_slug = (string) $wpcm_category->slug;
					$wpcm_category_name = (string) $wpcm_category->name;
					?>
					<option
						value="<?php echo esc_attr( $wpcm_category_slug ); ?>"
						<?php selected( $wpcm_selected_category, $wpcm_category_slug ); ?>
					>
						<?php echo esc_html( $wpcm_category_name ); ?>
					</option>
				<?php endforeach; ?>
			</select>
		</div>

		<div class="wpcm-catalog__filter-field">
			<label for="wpcm-search-filter">
				<?php echo esc_html__( 'Search Products', 'wp-product-catalog-manager' ); ?>
			</label>
			<input
				type="search"
				id="wpcm-search-filter"
				name="wpcm_search"
				value="<?php echo esc_attr( $wpcm_search ); ?>"
			/>
		</div>

		<button class="wpcm-catalog__filter-submit" type="submit">
			<?php echo esc_html__( 'Filter Products', 'wp-product-catalog-manager' ); ?>
		</button>
	</form>

	<?php if ( $wpcm_query->have_posts() ) : ?>
		<div class="wpcm-catalog__grid" role="list">
			<?php
			while ( $wpcm_query->have_posts() ) :
				$wpcm_query->the_post();

				$wpcm_product_id    = get_the_ID();
				$wpcm_product_title = get_the_title( $wpcm_product_id );
				$wpcm_product_url   = get_permalink( $wpcm_product_id );

				if ( ! is_scalar( $wpcm_product_title ) ) {
					$wpcm_product_title = '';
				}

				if ( ! is_string( $wpcm_product_url ) ) {
					$wpcm_product_url = '';
				}

				$wpcm_product_title = (string) $wpcm_product_title;
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
						<?php
						$wpcm_thumbnail = get_the_post_thumbnail(
							$wpcm_product_id,
							'medium',
							array(
								'class'   => 'wpcm-product-card__image',
								'loading' => 'lazy',
							)
						);
						?>
						<a class="wpcm-product-card__image-link" href="<?php echo esc_url( $wpcm_product_url ); ?>">
							<?php if ( is_string( $wpcm_thumbnail ) ) : ?>
								<?php echo wp_kses_post( $wpcm_thumbnail ); ?>
							<?php endif; ?>
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

	<?php if ( $wpcm_pagination_links ) : ?>
		<nav
			class="wpcm-catalog__pagination"
			aria-label="<?php echo esc_attr( $wpcm_pagination_label ); ?>"
		>
			<ul>
				<?php foreach ( $wpcm_pagination_links as $wpcm_pagination_link ) : ?>
					<?php if ( is_string( $wpcm_pagination_link ) ) : ?>
						<li><?php echo wp_kses_post( $wpcm_pagination_link ); ?></li>
					<?php endif; ?>
				<?php endforeach; ?>
			</ul>
		</nav>
	<?php endif; ?>
</section>
