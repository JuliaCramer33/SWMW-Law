<?php
/**
 * Title: Credential & Achievement Row
 * Slug: swmw-law/credential-achievement-row
 * Categories: content
 * Description: A flexible row for showcasing credentials, achievements, education, speaking engagements, awards, or certifications. Delete fields you don't need.
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
		register_block_pattern( 'swmw-law/credential-achievement-row', [
			'title'       => __( 'Credential & Achievement Row', 'swmw-law' ),
			'description' => __( 'A flexible row for showcasing credentials, achievements, education, speaking engagements, awards, or certifications. Delete fields you don\'t need.', 'swmw-law' ),
			'categories'  => [ 'content' ],
			'content'     => <<<'EOT'
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"0.625rem","bottom":"0.625rem","left":"1.25rem","right":"1.25rem"}},"border":{"radius":"6px"},"color":{"background":"#f8f9fa"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull has-background" style="background-color:#f8f9fa;border-radius:6px;padding-top:0.625rem;padding-right:1.25rem;padding-bottom:0.625rem;padding-left:1.25rem"><!-- wp:heading {"level":4,"style":{"typography":{"fontWeight":"600"}},"textColor":"charcoal","fontSize":"medium","placeholder":"Title, Degree, or Achievement"} -->
<h4 class="has-charcoal-color has-text-color has-medium-font-size" style="font-weight:600">Title, Degree, or Achievement</h4>
<!-- /wp:heading -->

<!-- wp:paragraph {"style":{"typography":{"fontWeight":"500"}},"textColor":"text","fontSize":"small","placeholder":"Institution, Organization, or Conference Name"} -->
<p class="has-text-color has-small-font-size" style="font-weight:500;color:#5f5f61">Institution, Organization, or Conference Name</p>
<!-- /wp:paragraph -->

<!-- wp:group {"style":{"spacing":{"blockGap":"0.25rem"}},"layout":{"type":"flex","flexWrap":"wrap","verticalAlignment":"center"}} -->
<div class="wp-block-group"><!-- wp:paragraph {"style":{"typography":{"fontWeight":"500"}},"textColor":"text","fontSize":"small","placeholder":"Role, Level, or Type"} -->
<p class="has-text-color has-small-font-size" style="font-weight:500;color:#5f5f61">Role, Level, or Type</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"style":{"typography":{"fontWeight":"400"}},"textColor":"text-light","fontSize":"small"} -->
<p class="has-text-light-color has-text-color has-small-font-size" style="font-weight:400">/</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"style":{"typography":{"fontWeight":"500"}},"textColor":"secondary","fontSize":"small","placeholder":"Year"} -->
<p class="has-secondary-color has-text-color has-small-font-size" style="font-weight:500">Year</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->
EOT,
		] );
	}
} ); 
