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
<!-- wp:cover {"url":"$image_url","dimRatio":0,"customOverlayColor":"#194a49","isUserOverlayColor":false,"focalPoint":{"x":0.46,"y":0.5},"minHeight":277,"minHeightUnit":"px","contentPosition":"center center","sizeSlug":"full","metadata":{"categories":["hero"],"patternName":"swmw-law/hero-basic","name":"Hero – Basic"},"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|20","bottom":"var:preset|spacing|20","left":"var:preset|spacing|10","right":"var:preset|spacing|10"}},"dimensions":{"aspectRatio":"auto"}},"layout":{"type":"default"}} -->
<div class="wp-block-cover alignfull" style="padding-top:var(--wp--preset--spacing--20);padding-right:var(--wp--preset--spacing--10);padding-bottom:var(--wp--preset--spacing--20);padding-left:var(--wp--preset--spacing--10);min-height:277px">
  <img class="wp-block-cover__image-background size-full" alt="" src="$image_url" style="object-position:46% 50%" data-object-fit="cover" data-object-position="46% 50%"/>
  <span aria-hidden="true" class="wp-block-cover__background has-background-dim-0 has-background-dim" style="background-color:#194a49"></span>
  <div class="wp-block-cover__inner-container">
    <!-- wp:columns {"className":"container-xxl"} -->
    <div class="wp-block-columns container-xxl">
      <!-- wp:column {"verticalAlignment":"center","className":"is-style-animate-slide-in-left"} -->
      <div class="wp-block-column is-vertically-aligned-center is-style-animate-slide-in-left">
        <!-- wp:heading {"level":1,"className":"is-style-default"} -->
        <h1 class="wp-block-heading is-style-default"><strong>Lorem</strong> ipsum</h1>
        <!-- /wp:heading -->

        <!-- wp:buttons {"className":"is-style-default"} -->
        <div class="wp-block-buttons is-style-default">
          <!-- wp:button {"textColor":"white","className":"is-style-fill","style":{"color":{"background":"#ffffff00"},"elements":{"link":{"color":{"text":"var:preset|color|white"}}},"border":{"width":"1px"}},"borderColor":"white"} -->
          <div class="wp-block-button is-style-fill">
            <a class="wp-block-button__link has-white-color has-text-color has-background has-link-color has-border-color has-white-border-color wp-element-button" href="#" style="border-width:1px;background-color:#ffffff00">Button Text</a>
          </div>
          <!-- /wp:button -->
        </div>
        <!-- /wp:buttons -->
      </div>
      <!-- /wp:column -->

      <!-- wp:column {"verticalAlignment":"center","className":"is-style-animate-slide-in-right"} -->
      <div class="wp-block-column is-vertically-aligned-center is-style-animate-slide-in-right">
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
