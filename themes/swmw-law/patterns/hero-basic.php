<?php
/**
 * Title: Hero – Basic
 * Slug: swmw-law/hero-basic
 * Categories: hero
 * Description: A basic hero layout with a background image, overlay, title, button, and paragraph.
 */

add_action( 'init', function () {
	if ( function_exists( 'register_block_pattern_category' ) ) {
		register_block_pattern_category( 'hero', [ 'label' => __( 'Hero', 'swmw-law' ) ] );
	}

	if ( function_exists( 'register_block_pattern' ) ) {
		$image_url = get_template_directory_uri() . '/assets/images/placeholder-hero.png';
		register_block_pattern( 'swmw-law/hero-basic', [
			'title'       => __( 'Hero – Basic', 'swmw-law' ),
			'description' => __( 'A basic hero layout with a background image, overlay, title, button, and paragraph.', 'swmw-law' ),
			'categories'  => [ 'hero' ],
			'content'     => <<<EOT
<!-- wp:cover {"url":"$image_url","dimRatio":0,"customOverlayColor":"#194a49","isUserOverlayColor":false,"focalPoint":{"x":0.46,"y":0.5},"minHeight":277,"minHeightUnit":"px","contentPosition":"center center","sizeSlug":"full","metadata":{"categories":["hero"],"patternName":"swmw-law/hero-basic","name":"Hero – Basic"},"style":{"spacing":{"padding":{"top":"var:preset|spacing|20","bottom":"var:preset|spacing|20","left":"var:preset|spacing|10","right":"var:preset|spacing|10"}},"dimensions":{"aspectRatio":"auto"}},"layout":{"type":"default"}} -->
<div class="wp-block-cover" style="padding-top:var(--wp--preset--spacing--20);padding-right:var(--wp--preset--spacing--10);padding-bottom:var(--wp--preset--spacing--20);padding-left:var(--wp--preset--spacing--10);min-height:277px">
  <img class="wp-block-cover__image-background size-full" alt="" src="$image_url" style="object-position:46% 50%" data-object-fit="cover" data-object-position="46% 50%"/>
  <span aria-hidden="true" class="wp-block-cover__background has-background-dim-0 has-background-dim" style="background-color:#194a49"></span>
  <div class="wp-block-cover__inner-container">
    <!-- wp:columns {"align":"wide","className":"container-xxl"} -->
    <div class="wp-block-columns alignwide container-xxl">
      <!-- wp:column {"verticalAlignment":"center"} -->
      <div class="wp-block-column is-vertically-aligned-center">
        <!-- wp:heading {"level":1,"className":"is-style-animate-fade-in"} -->
        <h1 class="wp-block-heading is-style-animate-fade-in"><strong>Lorem</strong> ipsum</h1>
        <!-- /wp:heading -->

        <!-- wp:buttons {"className":"is-style-animate-slide-up"} -->
        <div class="wp-block-buttons is-style-animate-slide-up">
          <!-- wp:button {"textColor":"white","className":"is-style-fill","style":{"color":{"background":"#ffffff00"},"elements":{"link":{"color":{"text":"var:preset|color|white"}}},"border":{"width":"1px"}},"borderColor":"white"} -->
          <div class="wp-block-button is-style-fill">
            <a class="wp-block-button__link has-white-color has-text-color has-background has-link-color has-border-color has-white-border-color wp-element-button" href="#" style="border-width:1px;background-color:#ffffff00">Button Text</a>
          </div>
          <!-- /wp:button -->
        </div>
        <!-- /wp:buttons -->
      </div>
      <!-- /wp:column -->

      <!-- wp:column {"verticalAlignment":"center"} -->
      <div class="wp-block-column is-vertically-aligned-center">
        <!-- wp:paragraph -->
        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean nec magna sed turpis sagittis maximus eu a arcu. Vivamus in ornare sem, in convallis arcu...</p>
        <!-- /wp:paragraph -->
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
