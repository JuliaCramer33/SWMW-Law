<?php
/**
 * Title: Hero – Split Left
 * Slug: swmw-law/hero-split-left
 * Categories: hero
 * Description: Hero block with left-aligned placeholder content and a background image.
 */

add_action( 'init', function () {
	if ( function_exists( 'register_block_pattern_category' ) ) {
		register_block_pattern_category( 'hero', [ 'label' => __( 'Hero', 'swmw-law' ) ] );
	}

	if ( function_exists( 'register_block_pattern' ) ) {
		$image_url = get_template_directory_uri() . '/assets/images/placeholder-hero.png';

		register_block_pattern( 'swmw-law/hero-split-left', [
			'title'       => __( 'Hero – Split Left', 'swmw-law' ),
			'description' => __( 'Hero block with left-aligned placeholder content and a background image.', 'swmw-law' ),
			'categories'  => [ 'hero' ],
			'content'     => <<<EOT
<!-- wp:cover {"url":"$image_url","dimRatio":0,"overlayColor":"primary","isUserOverlayColor":true,"focalPoint":{"x":0.5,"y":0.02},"minHeight":750,"sizeSlug":"full","metadata":{"categories":["hero"],"patternName":"swmw-law/hero-split-left","name":"Hero – Split Left"},"align":"full","className":"has-background-dim-30 is-style-animate-fade-in","layout":{"type":"constrained"}} -->
<div class="wp-block-cover alignfull has-background-dim-30 is-style-animate-fade-in" style="min-height:750px">
  <img class="wp-block-cover__image-background size-full" alt="" src="$image_url" style="object-position:50% 2%" data-object-fit="cover" data-object-position="50% 2%" />
  <span aria-hidden="true" class="wp-block-cover__background has-primary-background-color has-background-dim-0 has-background-dim"></span>
  <div class="wp-block-cover__inner-container">
    <!-- wp:columns {"verticalAlignment":"center","align":"wide"} -->
    <div class="wp-block-columns alignwide are-vertically-aligned-center">
      <!-- wp:column {"verticalAlignment":"center"} -->
      <div class="wp-block-column is-vertically-aligned-center">
        <!-- wp:heading {"level":1} -->
        <h1 class="wp-block-heading"><strong>Headline</strong> Goes Here</h1>
        <!-- /wp:heading -->

        <!-- wp:heading -->
        <h2 class="wp-block-heading">Subheading with <strong>Emphasis</strong></h2>
        <!-- /wp:heading -->

        <!-- wp:paragraph -->
        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer sit amet semper leo...</p>
        <!-- /wp:paragraph -->

        <!-- wp:buttons -->
        <div class="wp-block-buttons">
          <!-- wp:button {"textColor":"white","style":{"color":{"background":"#ffffff00"},"border":{"width":"1px"}},"borderColor":"white"} -->
          <div class="wp-block-button">
            <a class="wp-block-button__link has-white-color has-text-color has-background has-border-color has-white-border-color wp-element-button" href="#" style="border-width:1px;background-color:#ffffff00">Call to Action</a>
          </div>
          <!-- /wp:button -->
        </div>
        <!-- /wp:buttons -->
      </div>
      <!-- /wp:column -->

      <!-- wp:column {"verticalAlignment":"center"} -->
      <div class="wp-block-column is-vertically-aligned-center">
        <!-- wp:image -->
        <figure class="wp-block-image"><img alt=""/></figure>
        <!-- /wp:image -->
      </div>
      <!-- /wp:column -->
    </div>
    <!-- /wp:columns -->
  </div>
</div>
<!-- /wp:cover -->
EOT
		] );
	}
} );
