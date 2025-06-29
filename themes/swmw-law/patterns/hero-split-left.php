<?php
/**
 * Title: Hero – Split Left
 * Slug: swmw-law/hero-split-left
 * Categories: hero
 * Description: Split layout hero with content on the left and image overlap effect.
 */

add_action( 'init', function () {
    if ( function_exists( 'register_block_pattern_category' ) ) {
        register_block_pattern_category( 'hero', [ 'label' => __( 'Hero', 'swmw-law' ) ] );
    }

    if ( function_exists( 'register_block_pattern' ) ) {
        register_block_pattern( 'swmw-law/hero-split-left', [
            'title'       => __( 'Hero – Split Left', 'swmw-law' ),
            'description' => __( 'Split layout hero with content on the left and image overlap effect.', 'swmw-law' ),
            'categories'  => [ 'hero' ],
            'content'     => <<<'EOT'
<!-- wp:cover {"url":"https://via.placeholder.com/1600x600","overlayColor":"primary","dimRatio":30,"minHeight":500,"isUserOverlayColor":true,"align":"full","className":"hero-split-overlap","layout":{"type":"constrained"}} -->
<div class="wp-block-cover alignfull has-background-dim-30" style="min-height:500px">
  <span aria-hidden="true" class="wp-block-cover__background has-primary-background-color has-background-dim-30 has-background-dim"></span>
  <img class="wp-block-cover__image-background" alt="" src="https://via.placeholder.com/1600x600" data-object-fit="cover"/>
  <div class="wp-block-cover__inner-container">

    <!-- wp:columns {"align":"wide"} -->
    <div class="wp-block-columns alignwide">
    
      <!-- wp:column -->
      <div class="wp-block-column">
        <!-- wp:heading {"level":1} -->
        <h1>Your Hero Title Here</h1>
        <!-- /wp:heading -->

        <!-- wp:paragraph -->
        <p>This is a short supporting description about your services, mission, or call to action.</p>
        <!-- /wp:paragraph -->

        <!-- wp:buttons -->
        <div class="wp-block-buttons">
          <!-- wp:button {"backgroundColor":"secondary","textColor":"white"} -->
          <div class="wp-block-button">
            <a class="wp-block-button__link has-white-color has-secondary-background-color has-text-color has-background" href="#">Learn More</a>
          </div>
          <!-- /wp:button -->
        </div>
        <!-- /wp:buttons -->
      </div>
      <!-- /wp:column -->

      <!-- wp:column -->
      <div class="wp-block-column">
        <!-- wp:image {"sizeSlug":"large"} -->
        <figure class="wp-block-image size-large">
          <img src="https://via.placeholder.com/640x480" alt="Placeholder image"/>
        </figure>
        <!-- /wp:image -->
      </div>
      <!-- /wp:column -->

    </div>
    <!-- /wp:columns -->

  </div>
</div>
<!-- /wp:cover -->
EOT,
        ] );
    }
} );
