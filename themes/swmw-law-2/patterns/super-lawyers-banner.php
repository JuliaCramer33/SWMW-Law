<?php
/**
 * Title: Super Lawyers Banner
 * Slug: swmw-law/super-lawyers-banner
 * Categories: featured
 * Description: A banner showcasing Super Lawyers rating with badge and background.
 */

/**
 * Register the pattern and its category at init to avoid early translation loading.
 */
add_action( 'init', function () {
	// Register category if it does not exist.
	if ( function_exists( 'register_block_pattern_category' ) ) {
		register_block_pattern_category( 'featured', [ 'label' => __( 'Featured', 'swmw-law' ) ] );
	}

	// Register the pattern.
	if ( function_exists( 'register_block_pattern' ) ) {
		register_block_pattern( 'swmw-law/super-lawyers-banner', [
			'title'       => __( 'Super Lawyers Banner', 'swmw-law' ),
			'description' => __( 'A banner showcasing Super Lawyers rating with badge and background.', 'swmw-law' ),
			'categories'  => [ 'featured' ],
			'content'     => <<<'EOT'
<!-- wp:cover {"url":"https://picsum.photos/1920/600","dimRatio":80,"minHeight":200,"align":"full","className":"super-lawyers-banner","style":{"color":{"duotone":["#194C3A","#003366"]}}} -->
<div class="wp-block-cover alignfull super-lawyers-banner" style="min-height:200px"><span aria-hidden="true" class="wp-block-cover__background has-background-dim-80 has-background-dim"></span><img class="wp-block-cover__image-background" alt="" src="https://picsum.photos/1920/600" data-object-fit="cover"/><div class="wp-block-cover__inner-container"><!-- wp:columns {"align":"wide","style":{"spacing":{"blockGap":{"top":"2rem","left":"2rem"}}}} -->
<div class="wp-block-columns alignwide"><!-- wp:column {"verticalAlignment":"center","width":"66.66%"} -->
<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:66.66%"><!-- wp:paragraph {"style":{"typography":{"fontSize":"1.125rem","fontWeight":"600"}}} -->
<p style="font-size:1.125rem;font-weight:600">Rated by</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2,"style":{"typography":{"fontSize":"2.25rem","fontWeight":"300"}}} -->
<h2 style="font-size:2.25rem;font-weight:300">Super Lawyers</h2>
<!-- /wp:heading --></div>
<!-- /wp:column -->

<!-- wp:column {"verticalAlignment":"center","width":"33.33%"} -->
<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:33.33%"><!-- wp:image {"width":"150px","sizeSlug":"large","linkDestination":"none","className":"super-lawyers-badge"} -->
<figure class="wp-block-image size-large is-resized super-lawyers-badge"><img src="https://via.placeholder.com/150x200/417E4B/FFFFFF?text=Super+Lawyers+Badge" alt="Rated by Super Lawyers - Benjamin Robert Schmickle" style="width:150px"/></figure>
<!-- /wp:image --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div></div>
<!-- /wp:cover -->
EOT,
		] );
	}
} ); 
