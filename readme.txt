=== WP Product Catalog Manager ===
Contributors: wp-product-catalog-manager
Tags: product catalog, products, catalog, shortcode, taxonomy
Requires at least: 6.0
Requires PHP: 7.4
Stable tag: 0.1.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

A secure, WordPress-native product catalog with structured Product details, categories, search, filtering, pagination, and a responsive grid.

== Description ==

WP Product Catalog Manager provides a lightweight product catalog using WordPress core APIs and database tables.

The completed 0.1.0 feature set includes:

* A public, admin-manageable Product custom post type.
* A hierarchical Product Category taxonomy.
* Title, editor, and featured-image support.
* SKU, Material, Dimensions, Price, and Display Price fields.
* A secure Product Details meta box with nonce, capability, autosave, and revision checks.
* Sanitized text fields and string-based non-negative decimal Price normalization.
* A `[wpcm_catalog]` shortcode.
* Product Category filtering and sanitized keyword search.
* Pagination with twelve published Products per page.
* A responsive, container-aware catalog grid that preserves readable card widths.
* Escaped frontend output and translation-ready strings.
* Conditional catalog CSS loading.

The plugin creates no custom database tables. Catalog filtering reloads the page and does not use frontend JavaScript or AJAX.

== Installation ==

1. Upload or copy the `wp-product-catalog-manager` directory to `/wp-content/plugins/`.
2. Activate WP Product Catalog Manager through the WordPress Plugins screen.
3. Add categories under Products > Product Categories.
4. Add and publish Product entries, completing any Product Details fields needed.
5. Create or edit a page and add `[wpcm_catalog]`.
6. Publish the page and view the catalog.

No activation migration or custom database-table setup is required.

== Frequently Asked Questions ==

= How do I display the catalog? =

Place `[wpcm_catalog]` in a WordPress page. The shortcode has no attributes.

= Which Products are shown? =

Only published `wpcm_product` entries are queried. The catalog shows twelve matching Products per page.

= What does Display Price do? =

Display Price is optional presentation text. When it is present, the catalog displays it instead of the normalized numeric Price value.

= How are categories, search, and pagination handled? =

The catalog uses sanitized, namespaced URL parameters for Product Category, keyword search, and catalog page. Categories are validated against the Product Category taxonomy, and request data cannot replace arbitrary query arguments.

= Is filtering powered by AJAX or JavaScript? =

No. The filter form submits a standard GET request and reloads the page. The bundled JavaScript file is inert and is not enqueued.

= Does the plugin provide a custom single-Product template? =

No. Individual Product presentation is handled by the active WordPress theme.

= What happens on uninstall? =

The current plugin owns no WordPress options, so uninstall intentionally deletes nothing. Product posts, categories, post meta, featured images, users, unrelated options, and database tables remain intact.

= Is the plugin translation-ready? =

Yes. Runtime strings use the `wp-product-catalog-manager` text domain, and translations are loaded from the `/languages` directory.

= Does the plugin include REST, CSV, PDF, or settings features? =

No. REST API endpoints, CSV import/export, PDF uploads, AJAX, settings screens, sorting controls, price/material filters, and custom single templates are not implemented in this MVP.

== Screenshots ==

1. Product Details meta box with SKU, Material, Dimensions, Price, and Display Price.
2. Hierarchical Product Categories management screen.
3. Responsive frontend catalog grid with pagination.
4. Product Category and keyword filters showing narrowed catalog results.

== Changelog ==

= 0.1.0 =

* Added the WordPress-native Product custom post type and Product Category taxonomy.
* Added featured-image support and secure SKU, Material, Dimensions, Price, and Display Price fields.
* Added the `[wpcm_catalog]` shortcode with category filtering, keyword search, pagination, and a responsive grid.
* Added request hardening, output escaping, capability checks, and string-based Price normalization.
* Added translation loading through the `wp-product-catalog-manager` text domain.
* Documented conservative uninstall behavior; the plugin currently owns no options and deletes no data.
* Added completed-MVP installation, usage, architecture, security, limitations, and milestone documentation.
* Added real WordPress screenshots covering Product Details, Product Categories, the catalog grid, filtering, search, and pagination.
