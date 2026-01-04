<?php
/**
 * Title: Slanted Badge
 * Slug: swmw-law/slanted-badge
 * Categories: text
 * Description: A reusable slanted badge with text.
 */

add_action( 'init', function () {
    if ( function_exists( 'register_block_pattern_category' ) ) {
        register_block_pattern_category( 'badges', [ 'label' => __( 'Badges', 'swmw-law' ) ] );
    }

    if ( function_exists( 'register_block_pattern' ) ) {
        register_block_pattern( 'swmw-law/slanted-badge', [
            'title'       => __( 'Slanted Badge', 'swmw-law' ),
            'description' => __( 'A slanted rectangle with bold text.', 'swmw-law' ),
            'categories'  => [ 'badges' ],
            'content'     => '<!-- wp:group {"className":"is-style-slanted-badge","layout":{"type":"constrained"}} -->
<div class="wp-block-group is-style-slanted-badge"><!-- wp:heading {"level":3} -->
<h3>Because People Matter.</h3>
<!-- /wp:heading --></div>
<!-- /wp:group -->',
        ] );
    }
} );

