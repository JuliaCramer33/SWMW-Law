<?php
/**
 * Title: Hero with Dropdown Nav
 * Slug: swmw/hero-with-dropdown-nav
 * Categories: hero
 * Description: A hero with a headline and a dropdown-style navigation menu block.
 */
/**
 * Register the pattern and its category at init to avoid early translation loading.
 */
// Use the same random placeholder logic as hero-content-flex.php
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
        register_block_pattern( 'swmw/hero-with-dropdown-nav', [
            'title'       => __( 'Hero with Dropdown Nav', 'swmw-law' ),
            'description' => __( 'A hero with a headline and a dropdown-style navigation menu block.', 'swmw-law' ),
            'categories'  => [ 'hero' ],
            'content'     => str_replace(
                'https://picsum.photos/1920/1080',
                $rand_placeholder,
                <<<'EOT'
<!-- wp:cover {"url":"https://picsum.photos/1920/1080","dimRatio":50,"minHeight":400,"align":"full"} -->
<div class="wp-block-cover alignfull" style="min-height:400px"><span aria-hidden="true" class="wp-block-cover__background has-background-dim"></span><img class="wp-block-cover__image-background" alt="" src="https://picsum.photos/1920/1080" data-object-fit="cover"/><div class="wp-block-cover__inner-container"><!-- wp:columns {"verticalAlignment":"center"} -->
<div class="wp-block-columns are-vertically-aligned-center"><!-- wp:column -->
<div class="wp-block-column"><!-- wp:heading {"level":1,"className":"hero-heading"} -->
<h1 class="hero-heading">Companies That <strong>Used Asbestos</strong></h1>
<!-- /wp:heading --></div>
<!-- /wp:column -->
<!-- wp:column -->
<div class="wp-block-column"><!-- wp:acf/hero-dropdown-menu /--></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div></div>
<!-- /wp:cover -->
EOT
            ),
        ] );
    }
} ); 
