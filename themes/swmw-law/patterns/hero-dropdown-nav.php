<?php
/**
 * Title: Hero with Dropdown Nav
 * Slug: swmw/hero-with-dropdown-nav
 * Categories: hero
 * Description: A hero with a headline and a dropdown-style navigation menu placeholder.
 */

add_action( 'init', function () {
    // Register category if it does not exist.
    if ( function_exists( 'register_block_pattern_category' ) ) {
        register_block_pattern_category( 'hero', [ 'label' => __( 'Hero', 'swmw-law' ) ] );
    }

    // Register the pattern.
    if ( function_exists( 'register_block_pattern' ) ) {
        register_block_pattern( 'swmw/hero-with-dropdown-nav', [
            'title'       => __( 'Hero with Dropdown Nav', 'swmw-law' ),
            'description' => __( 'A hero with a headline and a dropdown-style navigation menu placeholder.', 'swmw-law' ),
            'categories'  => [ 'hero' ],
            'content'     => <<<'EOT'
<!-- wp:group {"align":"full","className":"hero-dropdown-nav-wrapper"} -->
<div class="wp-block-group alignfull hero-dropdown-nav-wrapper">
    <!-- wp:heading {"level":1,"className":"hero-heading"} -->
    <h1 class="hero-heading">We Handle Cases Nationwide</h1>
    <!-- /wp:heading -->

    <!-- wp:group {"className":"hero-dropdown-nav"} -->
    <div class="wp-block-group hero-dropdown-nav">
        <!-- wp:html -->
        <div class="hamburger-menu-toggle">
            <div class="hamburger-icon">
                <span></span>
                <span></span>
                <span></span>
            </div>
            <span class="hamburger-label">Menu</span>
        </div>
        <!-- /wp:html -->

        <!-- wp:navigation {"className":"hero-nav-list","layout":{"type":"flex","flexWrap":"nowrap","orientation":"vertical"},"overlayMenu":"never"} -->
        <!-- /wp:navigation -->
    </div>
    <!-- /wp:group -->
</div>
<!-- /wp:group -->
EOT,
        ] );
    }
} ); 
