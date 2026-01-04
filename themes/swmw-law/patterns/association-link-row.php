<?php
/**
 * Title: Association Link Row
 * Slug: swmw-law/association-link-row
 * Categories: content
 * Description: A horizontal row for showcasing associations, organizations, or certifications with a link button.
 */

/**
 * Register the pattern and its category at init to avoid early translation loading.
 */
add_action( 'init', function () {
	// Register category if it does not exist.
	if ( function_exists( 'register_block_pattern_category' ) ) {
		register_block_pattern_category( 'content', [ 'label' => __( 'Content', 'swmw-law' ) ] );
	}

	// Register the pattern.
	if ( function_exists( 'register_block_pattern' ) ) {
		register_block_pattern( 'swmw-law/association-link-row', [
			'title'       => __( 'Association Link Row', 'swmw-law' ),
			'description' => __( 'A horizontal row for showcasing associations, organizations, or certifications with a link button.', 'swmw-law' ),
			'categories'  => [ 'content' ],
			'content'     => <<<'EOT'
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"0.75rem","bottom":"0.75rem","left":"1.25rem","right":"1.25rem"}},"border":{"radius":"6px"},"color":{"background":"#f1f3f6"}},"layout":{"type":"flex","flexWrap":"wrap","justifyContent":"space-between","verticalAlignment":"center"}} -->
<div class="wp-block-group alignfull has-background" style="background-color:#f1f3f6;border-radius:6px;padding-top:0.75rem;padding-right:1.25rem;padding-bottom:0.75rem;padding-left:1.25rem"><!-- wp:paragraph {"style":{"typography":{"fontWeight":"600"}},"textColor":"primary","fontSize":"medium"} -->
<p class="has-primary-color has-text-color has-medium-font-size" style="font-weight:600">The American Association for Justice</p>
<!-- /wp:paragraph -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button {"style":{"border":{"width":"1px","radius":"20px"},"color":{"text":"#003366","background":"transparent"}},"className":"is-style-outline","iconUrl":"/wp-content/themes/swmw-law/assets/images/arrow-external.svg","iconAlt":"External link"} -->
<div class="wp-block-button is-style-outline"><a class="wp-block-button__link has-text-color has-background wp-element-button" href="#" style="border-width:1px;border-radius:20px;color:#003366;background-color:transparent">View</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group -->
EOT,
		] );
	}
} ); 
