<?php
/**
 * Title: Hero with Dropdown Nav
 * Slug: swmw/hero-with-dropdown-nav
 * Categories: hero
 * Description: A hero with a headline and a dropdown-style navigation menu block.
 */

add_action( 'init', function () {
	if ( function_exists( 'register_block_pattern_category' ) ) {
		register_block_pattern_category( 'hero', [ 'label' => __( 'Hero', 'swmw-law' ) ] );
	}

	if ( function_exists( 'register_block_pattern' ) ) {
		$image_url = get_template_directory_uri() . '/assets/images/placeholder-hero.png';
		register_block_pattern( 'swmw/hero-with-dropdown-nav', [
			'title'       => __( 'Hero with Dropdown Nav', 'swmw-law' ),
			'description' => __( 'A hero with a headline and a dropdown-style navigation menu block.', 'swmw-law' ),
			'categories'  => [ 'hero' ],
			'content'     => <<<EOT
<!-- wp:cover {"url":"$image_url","dimRatio":0,"customOverlayColor":"#1b514e","isUserOverlayColor":false,"minHeight":400,"sizeSlug":"large","metadata":{"categories":["hero"],"patternName":"swmw/hero-with-dropdown-nav","name":"Hero with Dropdown Nav"},"align":"full"} -->
<div class="wp-block-cover alignfull" style="min-height:400px">
	<img class="wp-block-cover__image-background size-large" alt="" src="$image_url" data-object-fit="cover"/>
	<span aria-hidden="true" class="wp-block-cover__background has-background-dim-0 has-background-dim" style="background-color:#1b514e"></span>
	<div class="wp-block-cover__inner-container">
		<!-- wp:columns {"verticalAlignment":"center"} -->
		<div class="wp-block-columns are-vertically-aligned-center">
			<!-- wp:column {"width":"70%"} -->
			<div class="wp-block-column" style="flex-basis:70%">
				<!-- wp:heading {"level":1,"className":"hero-heading"} -->
				<h1 class="wp-block-heading hero-heading">Title <strong>Here</strong></h1>
				<!-- /wp:heading -->
			</div>
			<!-- /wp:column -->

			<!-- wp:column {"width":"30%"} -->
			<div class="wp-block-column" style="flex-basis:30%">
				<!-- wp:acf/hero-dropdown-menu {"name":"acf/hero-dropdown-menu","mode":"preview"} /-->
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
