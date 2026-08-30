<?php
/**
 * Plugin uninstall entry point.
 *
 * The plugin currently owns no WordPress options, so uninstall intentionally
 * performs no data deletion. Future plugin-owned options must be individually
 * allowlisted here before they may be deleted with WordPress option APIs.
 * Product content, terms, post meta, media, user data, unrelated options, and
 * database tables are outside the uninstall policy and must remain intact.
 *
 * @package WP_Product_Catalog_Manager
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}
