<?php
/**
 * Title: Hero – Homepage
 * Slug: swmw-law/hero-homepage
 * Categories: hero
 * Description: A hero section designed specifically for the homepage, with a two-column layout.
 */

add_action( 'init', function () {
	if ( function_exists( 'register_block_pattern_category' ) ) {
		register_block_pattern_category( 'hero', [ 'label' => __( 'Hero', 'swmw-law' ) ] );
	}

	if ( function_exists( 'register_block_pattern' ) ) {
		register_block_pattern( 'swmw-law/hero-homepage', [
			'title'       => __( 'Hero – Homepage', 'swmw-law' ),
			'description' => __( 'A hero section designed specifically for the homepage, with a two-column layout.', 'swmw-law' ),
			'categories'  => [ 'hero' ],
			'content'     => '<!-- wp:cover {"dimRatio":0,"customOverlayColor":"#1b4f4d","align":"full","className":"swmw-hero-homepage","style":{"spacing":{"padding":{"top":"0","bottom":"0"}}}} -->
<div class="wp-block-cover alignfull swmw-hero-homepage" style="padding-top:0;padding-bottom:0"><span aria-hidden="true" class="wp-block-cover__background has-background-dim-0 has-background-dim" style="background-color:#1b4f4d"></span><div class="wp-block-cover__inner-container"><!-- wp:group {"layout":{"type":"constrained"}} -->
<div class="wp-block-group"><!-- wp:columns {"style":{"spacing":{"padding":{"top":"var:preset|spacing|8","bottom":"0"}}}} -->
<div class="wp-block-columns" style="padding-top:var(--wp--preset--spacing--8);padding-bottom:0"><!-- wp:column {"verticalAlignment":"center","width":"50%"} -->
<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:50%"><!-- wp:heading {"level":1} -->
<h1 class="wp-block-heading">Mesothelioma &amp; Asbestos Exposure Lawyers</h1>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p><strong>Over $750 Million Recovered for Victims of Asbestos-Related Diseases Nationwide.</strong> For over a decade, our firm has stood beside union workers, veterans, and families devastated by asbestos-related diseases. We’ve secured life-changing verdicts and settlements, including individual awards of $10 million or more, through tireless investigation, national reach, and deep commitment to our clients. You won’t pay unless we win.</p>
<!-- /wp:paragraph -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button {"className":"is-style-outline","style":{"border":{"width":"1px"}},"borderColor":"white"} -->
<div class="wp-block-button is-style-outline"><a class="wp-block-button__link has-border-color has-white-border-color wp-element-button" style="border-width:1px">About Us</a></div>
<!-- /wp:button -->

<!-- wp:button {"backgroundColor":"white","textColor":"secondary","style":{"elements":{"link":{"color":{"text":"var:preset|color|secondary"}}},"border":{"width":"1px"}},"borderColor":"white"} -->
<div class="wp-block-button"><a class="wp-block-button__link has-secondary-color has-white-background-color has-text-color has-background has-link-color has-border-color has-white-border-color wp-element-button" style="border-width:1px">Free Consultation</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:column -->

<!-- wp:column {"width":"50%"} -->
<div class="wp-block-column" style="flex-basis:50%"><!-- wp:image {"sizeSlug":"full","linkDestination":"none","style":{"spacing":{"margin":{"top":"0","bottom":"0"}}}} -->
<figure class="wp-block-image size-full" style="margin-top:0;margin-bottom:0"><img alt=""/></figure>
<!-- /wp:image --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:group --></div></div>
<!-- /wp:cover -->',
		] );
	}
} );
