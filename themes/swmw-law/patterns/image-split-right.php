<?php
/**
 * Title: Image Split – Image Right
 * Slug: swmw-law/image-split-right
 * Categories: image-split
 * Description: Image split block positioned on the right with content on the left.
 */

add_action( 'init', function () {
	if ( function_exists( 'register_block_pattern_category' ) ) {
		register_block_pattern_category( 'image-split', [ 'label' => __( 'Image Split', 'swmw-law' ) ] );
	}

	if ( function_exists( 'register_block_pattern' ) ) {
		register_block_pattern( 'swmw-law/image-split-right', [
			'title'       => __( 'Image Split – Image Right', 'swmw-law' ),
			'description' => __( 'Image split block positioned on the right with content on the left.', 'swmw-law' ),
			'categories'  => [ 'image-split' ],
			'content'     => <<<EOT
<!-- wp:columns {"metadata":{"categories":["image-split"],"patternName":"swmw-law/image-split-right","name":"Image Split – Image Right"},"align":"full","style":{"spacing":{"blockGap":{"left":"var:preset|spacing|16"}}}} -->
<div class="wp-block-columns alignfull"><!-- wp:column {"width":"50%"} -->
<div class="wp-block-column" style="flex-basis:50%"><!-- wp:group {"align":"wide"} -->
<div class="wp-block-group alignwide"><!-- wp:heading -->
<h2 class="wp-block-heading">Expert Legal Solutions</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Our experienced team of attorneys provides comprehensive legal services tailored to your specific needs. With decades of combined experience, we deliver results that matter for our clients and their families.</p>
<!-- /wp:paragraph -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="#">Learn More</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group --></div>
<!-- /wp:column -->

<!-- wp:column {"width":"50%"} -->
<div class="wp-block-column" style="flex-basis:50%"><!-- wp:acf/image-split {"name":"acf/image-split","data":{"overlap_position":"left","_overlap_position":"field_image_split_overlap_position"},"mode":"preview","className":"is-style-animate-slide-in-right"} /--></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->
EOT
		] );
	}
} ); 
