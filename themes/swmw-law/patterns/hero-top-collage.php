<?php
/**
 * Title: Hero – Top Collage
 * Slug: swmw-law/hero-top-collage
 * Categories: hero
 * Description: Hero with a collage background image and editable content constrained to site width.
 */
/**
 * Register the pattern and its category at init to avoid early translation loading.
 */
add_action( 'init', function () {
    // Register category if it does not exist.
    if ( function_exists( 'register_block_pattern_category' ) ) {
        register_block_pattern_category( 'hero', [ 'label' => __( 'Hero', 'swmw-law' ) ] );
    }

    // Register the pattern.
    if ( function_exists( 'register_block_pattern' ) ) {
        register_block_pattern( 'swmw-law/hero-top-collage', [
            'title'       => __( 'Hero – Top Collage', 'swmw-law' ),
            'description' => __( 'Hero with a collage background image and editable content constrained to site width.', 'swmw-law' ),
            'categories'  => [ 'hero' ],
            'content'     => <<<'EOT'
<!-- wp:cover {"url":"https://example.com/path-to/testimonials-collage.jpg","dimRatio":50,"minHeight":500,"align":"full"} -->
<div class="wp-block-cover alignfull" style="min-height:500px"><span aria-hidden="true" class="wp-block-cover__background has-background-dim"></span><img class="wp-block-cover__image-background" alt="" src="https://example.com/path-to/testimonials-collage.jpg" data-object-fit="cover"/><div class="wp-block-cover__inner-container"><!-- wp:group {"layout":{"type":"constrained"}} -->
<div class="wp-block-group"><!-- wp:heading {"textAlign":"center","level":1} -->
<h1 class="has-text-align-center">Our Testimonials</h1>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center">Hear what our clients have to say about working with us.</p>
<!-- /wp:paragraph -->

<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons"><!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="#">Contact Us</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group --></div></div>
<!-- /wp:cover -->
EOT,
        ] );
    }
} ); 
