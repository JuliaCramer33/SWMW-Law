<?php
/**
 * Title: Speaking Engagement Row
 * Slug: swmw-law/speaking-engagement-row
 * Categories: content
 * Description: A row for showcasing speaking engagements, presentations, and conference appearances.
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
		register_block_pattern( 'swmw-law/speaking-engagement-row', [
			'title'       => __( 'Speaking Engagement Row', 'swmw-law' ),
			'description' => __( 'A row for showcasing speaking engagements, presentations, and conference appearances.', 'swmw-law' ),
			'categories'  => [ 'content' ],
			'content'     => <<<'EOT'
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"1rem","bottom":"1rem","left":"1.25rem","right":"1.25rem"},"blockGap":"0"},"border":{"radius":"6px"},"color":{"background":"#f8f9fa"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull has-background" style="background-color:#f8f9fa;border-radius:6px;padding-top:1rem;padding-right:1.25rem;padding-bottom:1rem;padding-left:1.25rem"><!-- wp:heading {"level":4,"style":{"typography":{"fontWeight":"600"}},"textColor":"charcoal","fontSize":"medium"} -->
<h4 class="has-charcoal-color has-text-color has-medium-font-size" style="font-weight:600">"Daimler: Where Do We Belong and Where Are We Going?"</h4>
<!-- /wp:heading -->

<!-- wp:group {"style":{"spacing":{"blockGap":"0.25rem"}},"layout":{"type":"flex","flexWrap":"wrap","verticalAlignment":"center"}} -->
<div class="wp-block-group"><!-- wp:paragraph {"style":{"typography":{"fontWeight":"500"}},"textColor":"text","fontSize":"small"} -->
<p class="has-text-color has-small-font-size" style="font-weight:500;color:#5f5f61">Presenter, Perrin Cutting Edge in Asbestos Litigation Conference</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"style":{"typography":{"fontWeight":"400"}},"textColor":"text-light","fontSize":"small"} -->
<p class="has-text-light-color has-text-color has-small-font-size" style="font-weight:400">/</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"style":{"typography":{"fontWeight":"500"}},"textColor":"secondary","fontSize":"small"} -->
<p class="has-secondary-color has-text-color has-small-font-size" style="font-weight:500">2016</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->
EOT,
		] );
	}
} ); 
