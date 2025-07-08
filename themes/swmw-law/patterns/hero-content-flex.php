<?php
/**
 * Title: Hero – Flexible Content
 * Slug: swmw-law/hero-content-flex
 * Categories: hero
 * Description: A flexible hero with an image background and editable text/button options.
 */
/**
 * Register the pattern and its category at init to avoid early translation loading.
 */
// Replace the image URL in the pattern content with a randomized WP native SVG
$placeholders = [
    'https://s.w.org/images/core/5.3/WP-Brand-1.svg',
    'https://s.w.org/images/core/5.3/WP-Brand-2.svg',
    'https://s.w.org/images/core/5.3/WP-Brand-3.svg',
    'https://s.w.org/images/core/5.3/WP-Brand-4.svg',
];
$rand_placeholder = $placeholders[ array_rand($placeholders) ];

add_action( 'init', function () use ($rand_placeholder) {
	// Register category if it does not exist.
	if ( function_exists( 'register_block_pattern_category' ) ) {
		register_block_pattern_category( 'hero', [ 'label' => __( 'Hero', 'swmw-law' ) ] );
	}

	// Register the pattern.
	if ( function_exists( 'register_block_pattern' ) ) {
		register_block_pattern( 'swmw-law/hero-content-flex', [
			'title'       => __( 'Hero – Flexible Content', 'swmw-law' ),
			'description' => __( 'A flexible hero with an image background and editable text/button options.', 'swmw-law' ),
			'categories'  => [ 'hero' ],
			'content'     => str_replace(
				'https://picsum.photos/1920/1080',
				$rand_placeholder,
				<<<'EOT'
<!-- wp:cover {"url":"https://picsum.photos/1920/1080","dimRatio":50,"minHeight":400,"align":"full"} -->
<div class="wp-block-cover alignfull" style="min-height:400px"><span aria-hidden="true" class="wp-block-cover__background has-background-dim"></span><img class="wp-block-cover__image-background" alt="" src="https://picsum.photos/1920/1080" data-object-fit="cover"/><div class="wp-block-cover__inner-container"><!-- wp:heading {"textAlign":"center","level":1,"style":{"typography":{"fontSize":"4rem"}}} -->
<h1 class="has-text-align-center" style="font-size:4rem">Our Attorneys</h1>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center">A flexible hero with an image background and editable text/button options.</p>
<!-- /wp:paragraph -->

<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons"><!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button">Call to Action</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div></div>
<!-- /wp:cover -->
EOT
			),
		] );
	}
} ); 
