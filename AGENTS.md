# WP Product Catalog Manager

## Authority and scope

This file is the authoritative project specification for the WP Product Catalog Manager WordPress plugin. Work must be delivered incrementally and must stay within the scope of the requested commit. If a request conflicts with this specification, stop and obtain clarification before expanding the scope.

The current milestone is commit 1 only: `chore: scaffold plugin structure and bootstrap`.

## MVP requirements

The completed MVP will include:

- A Product custom post type.
- A Product Category custom taxonomy.
- SKU, Material, Dimensions, and Price/Display Price fields.
- Featured image support.
- A secure admin meta box.
- Nonce and capability checks.
- A sanitized save flow.
- A `[wpcm_catalog]` shortcode.
- Category filtering and keyword search.
- Pagination.
- A responsive product grid.
- Escaped front-end output.
- Translation-ready strings using the `wp-product-catalog-manager` text domain.
- Uninstall cleanup for plugin-owned options only.
- `README.md`, `readme.txt`, and screenshots.
- No custom database tables.

REST API, CSV import/export, AJAX, custom single templates, and PDF uploads are stretch features only after the MVP.

## Exact required architecture

The repository must contain exactly the following project files for commit 1, excluding Git's internal `.git/` directory:

```text
wp-product-catalog-manager/
|-- .gitignore
|-- AGENTS.md
|-- README.md
|-- readme.txt
|-- uninstall.php
|-- wp-product-catalog-manager.php
|-- assets/
|   |-- css/
|   |   `-- frontend.css
|   `-- js/
|       `-- frontend.js
|-- includes/
|   |-- class-wpcm-assets.php
|   |-- class-wpcm-meta-boxes.php
|   |-- class-wpcm-plugin.php
|   |-- class-wpcm-post-type.php
|   |-- class-wpcm-shortcode.php
|   `-- class-wpcm-taxonomy.php
|-- languages/
|   `-- .gitkeep
`-- templates/
    `-- catalog-grid.php
```

Do not add unlisted placeholder directories, directory index files, test directories, admin directories, public directories, generated files, or dependencies during commit 1. A `.gitkeep` file is allowed only as a placeholder for a required empty directory and is not considered an architecture violation.

## Architecture responsibilities

- `wp-product-catalog-manager.php`: plugin header, direct-access guard, plugin constants, core class loading, and the minimal `plugins_loaded` bootstrap callback.
- `includes/class-wpcm-plugin.php`: inert commit-1 coordinator; later commits may connect approved components here.
- `includes/class-wpcm-post-type.php`: inert scaffold for future product post type registration.
- `includes/class-wpcm-taxonomy.php`: inert scaffold for future Product Category taxonomy registration.
- `includes/class-wpcm-meta-boxes.php`: inert scaffold for the future SKU, Material, Dimensions, and Price/Display Price meta fields and secure admin meta box.
- `includes/class-wpcm-shortcode.php`: inert scaffold for the future `[wpcm_catalog]` shortcode.
- `includes/class-wpcm-assets.php`: inert scaffold for future front-end asset registration and enqueueing.
- `assets/css/frontend.css`: inert placeholder for future catalog styles.
- `assets/js/frontend.js`: inert placeholder for future catalog behavior.
- `templates/catalog-grid.php`: guarded, inert placeholder for the future responsive product grid template.
- `uninstall.php`: guarded uninstall entry point; it must not remove data during commit 1 and may later remove plugin-owned options only.
- `README.md` and `readme.txt`: documentation scaffolds that accurately describe the current state without claiming later features are implemented; full documentation, screenshots, and usage examples belong to commit 8.

## Security requirements

- Guard every executable PHP file against direct access. Use `ABSPATH` for plugin runtime files and `WP_UNINSTALL_PLUGIN` for `uninstall.php`.
- Sanitize and validate every input using context-appropriate WordPress APIs. Unslash request data before sanitizing it.
- Escape all dynamic output at the point of rendering with the correct context-specific escaping function.
- Verify nonces for every state-changing request.
- Check appropriate capabilities before every privileged action.
- Use WordPress APIs where available. Prepare every SQL statement that contains variable data.
- Do not introduce custom database tables.
- Do not trust request variables, shortcode attributes, metadata, filenames, paths, or URLs.
- Do not expose secrets, credentials, environment-specific paths, debugging output, warnings, or notices.
- Do not create, migrate, modify, or delete data until the applicable later commit explicitly authorizes that behavior.
- Do not add third-party dependencies without explicit approval.
- Keep uninstall behavior conservative: verify the uninstall context and delete nothing until a later approved data policy exists.

## Coding requirements

- Follow WordPress coding conventions.
- Prefix PHP classes and constants with `WPCM_` and global functions, option names, metadata keys, and hooks with `wpcm_`.
- Use `wp-product-catalog-manager` as the text domain.
- Add appropriate file documentation and `@package WP_Product_Catalog_Manager` tags to PHP files.
- Do not describe or implement `WPCM_Plugin` as a singleton.
- Keep bootstrap code minimal, secure, inert, and extensible.
- Add or update tests when behavior is introduced. Run `php -l` on every PHP file changed in a commit and on every PHP file when the request requires full linting.

## Eight-step commit sequence

Implement the project only through these ordered, separately requested commits:

1. `chore: scaffold plugin structure and bootstrap`
2. `feat: register product post type and category taxonomy`
3. `feat: add product meta fields and secure save handling`
4. `feat: add catalog shortcode and responsive grid`
5. `feat: add category filtering, search and pagination`
6. `fix: harden sanitization, escaping and capability checks`
7. `chore: add uninstall cleanup, i18n and coding cleanup`
8. `docs: add README, screenshots and usage examples`

Do not combine steps or pre-implement code assigned to a later commit.

## Current commit-1 constraints

For commit 1:

- Create only the exact required architecture.
- Keep the entry point, coordinator, component classes, template, styles, and scripts inert.
- The entry point may define constants, require the coordinator, register a `plugins_loaded` callback, instantiate the coordinator, and call its empty run method.
- Do not register a custom post type or taxonomy.
- Do not add product meta fields or meta boxes.
- Do not register or render a shortcode.
- Do not query, filter, paginate, or render catalog products.
- Do not enqueue or execute front-end assets.
- Do not add admin screens, settings, REST routes, AJAX actions, CSV handling, templates with output, custom database tables, or data lifecycle behavior.
- Do not create, migrate, update, or delete WordPress data.
- Do not add activation or deactivation behavior.
- Do not add third-party dependencies.

## Required completion report

At the end of every requested change, report:

1. Files created.
2. Files modified.
3. Files deleted.
4. Test and lint commands run, including totals and results.

Also print the exact final directory tree when the request requires it.

## Version-control restriction

Do not commit or push unless the user explicitly requests it. The current commit-1 task explicitly forbids committing and pushing.
