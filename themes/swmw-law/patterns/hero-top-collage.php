<?php
/**
 * Title: Hero – Centered
 * Slug: swmw-law/hero-centered
 * Categories: hero
 * Description: A centered hero with a background image, heading, paragraph, and call-to-action button.
 */

add_action( 'init', function () {
	if ( function_exists( 'register_block_pattern_category' ) ) {
		register_block_pattern_category( 'hero', [ 'label' => __( 'Hero', 'swmw-law' ) ] );
	}

	if ( function_exists( 'register_block_pattern' ) ) {
		$image_url = get_template_directory_uri() . '/assets/images/placeholder-hero.png';

		register_block_pattern( 'swmw-law/hero-centered', [
			'title'       => __( 'Hero – Centered', 'swmw-law' ),
			'description' => __( 'A centered hero with a background image, heading, paragraph, and call-to-action button.', 'swmw-law' ),
			'categories'  => [ 'hero' ],
			'content'     => <<<EOT
<!-- wp:cover {"url":"$image_url","dimRatio":0,"isUserOverlayColor":true,"minHeight":775,"sizeSlug":"full","align":"full","className":"has-background-dim-30"} -->
<div class="wp-block-cover alignfull has-background-dim-30" style="min-height:775px">
  <img class="wp-block-cover__image-background size-full" alt="" src="$image_url" data-object-fit="cover"/>
  <span aria-hidden="true" class="wp-block-cover__background has-background-dim-0 has-background-dim"></span>
  <div class="wp-block-cover__inner-container">
    <!-- wp:group {"className":"is-style-animate-slide-up","style":{"spacing":{"padding":{"top":"var:preset|spacing|20"}}},"layout":{"type":"constrained"}} -->
    <div class="wp-block-group is-style-animate-slide-up" style="padding-top:var(--wp--preset--spacing--20)">
    
      <!-- wp:heading {"textAlign":"center","level":1} -->
      <h1 class="wp-block-heading has-text-align-center">Title Here</h1>
      <!-- /wp:heading -->

      <!-- wp:paragraph {"align":"center"} -->
      <p class="has-text-align-center">Text can go here.</p>
      <!-- /wp:paragraph -->

      <!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
      <div class="wp-block-buttons">
        <!-- wp:button {"textColor":"white","style":{"elements":{"link":{"color":{"text":"var:preset|color|white"}}},"border":{"width":"2px"},"color":{"background":"#ffffff00"}},"borderColor":"white"} -->
        <div class="wp-block-button">
          <a class="wp-block-button__link has-white-color has-text-color has-background has-link-color has-border-color has-white-border-color wp-element-button" href="#" style="border-width:2px;background-color:#ffffff00">Contact Us</a>
        </div>
        <!-- /wp:button -->
      </div>
      <!-- /wp:buttons -->

    </div>
    <!-- /wp:group -->
  </div>
</div>
<!-- /wp:cover -->
EOT
		] );
	}
} );
