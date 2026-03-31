<?php
/**
 * Title: Hero – Locations
 * Slug: swmw-law/hero-locations
 * Categories: hero
 * Description: A hero section with a two-column layout where the right column is an image collage that overlaps the left.
 */

add_action( 'init', function () {
	if ( function_exists( 'register_block_pattern' ) ) {
		register_block_pattern( 'swmw-law/hero-locations', [
			'title'       => __( 'Hero – Locations', 'swmw-law' ),
			'description' => __( 'A hero section with a two-column layout where the right column is an image collage that overlaps the left.', 'swmw-law' ),
			'categories'  => [ 'hero' ],
			'content'     => '<!-- wp:cover {"dimRatio":0,"isUserOverlayColor":false,"align":"full","className":"swmw-hero-locations","style":{"spacing":{"padding":{"top":"0","bottom":"0"},"margin":{"bottom":"var:preset|spacing|24"}}}} -->
<div class="wp-block-cover alignfull swmw-hero-locations" style="margin-bottom:var(--wp--preset--spacing--24);padding-top:0;padding-bottom:0"><span aria-hidden="true" class="wp-block-cover__background has-background-dim-0 has-background-dim"></span><div class="wp-block-cover__inner-container"><!-- wp:group {"align":"wide","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignwide"><!-- wp:columns {"verticalAlignment":"center","isStackedOnMobile":false,"isStackedOnCustom":true} -->
<div class="wp-block-columns are-vertically-aligned-center is-not-stacked-on-mobile is-stacked-on-custom-992"><!-- wp:column {"verticalAlignment":"center","width":"50%","style":{"spacing":{"padding":{"top":"var:preset|spacing|10","bottom":"var:preset|spacing|10"}}}} -->
<div class="wp-block-column is-vertically-aligned-center" style="padding-top:var(--wp--preset--spacing--10);padding-bottom:var(--wp--preset--spacing--10);flex-basis:50%"><!-- wp:heading {"level":1} -->
<h1 class="wp-block-heading">St. Louis Mesothelioma Lawyer <br><strong>Your Unwavering Advocate</strong></h1>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>A mesothelioma diagnosis is devastating – physically, emotionally, and financially. From the uncertainty and mounting medical bills to questions about the future, it can feel overwhelming.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p><strong>But you don’t have to face it alone.</strong></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>At SWMW Law, we fight for mesothelioma victims and their families, helping them secure the maximum compensation they deserve. Let us handle the legal battle on your behalf. Schedule a free consultation today and start the journey to justice. There are no fees unless we win.</p>
<!-- /wp:paragraph -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button {"textColor":"white","className":"is-style-outline","style":{"elements":{"link":{"color":{"text":"var:preset|color|white"}}}}} -->
<div class="wp-block-button is-style-outline"><a class="wp-block-button__link has-white-color has-text-color has-link-color wp-element-button">Free Consultation</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:column -->

<!-- wp:column {"verticalAlignment":"center","width":"50%","className":"has-negative-overlap"} -->
<div class="wp-block-column is-vertically-aligned-center has-negative-overlap" style="flex-basis:50%"><!-- wp:group {"className":"swmw-hero-locations__collage"} -->
<div class="wp-block-group swmw-hero-locations__collage"><!-- wp:image {"sizeSlug":"full","linkDestination":"none"} -->
<figure class="wp-block-image size-full"><img src="" alt=""/></figure>
<!-- /wp:image --></div>
<!-- /wp:group --></div>
<!-- /wp:column --></div><style>
            .wp-block-columns.is-not-stacked-on-mobile.is-stacked-on-custom-992 {
                flex-wrap: wrap !important;
            }
            @media (min-width: 992px) {
                .wp-block-columns.is-not-stacked-on-mobile.is-stacked-on-custom-992 {
                    flex-wrap: nowrap !important;
                }
            }
            @media (max-width: calc(992px - 1px)) {
                .wp-block-columns.is-not-stacked-on-mobile.is-stacked-on-custom-992 > .wp-block-column {
                    flex-basis: 100% !important;
                }
            }
        </style>
<!-- /wp:columns --></div>
<!-- /wp:group --></div></div>
<!-- /wp:cover -->',
		] );
	}
} );
