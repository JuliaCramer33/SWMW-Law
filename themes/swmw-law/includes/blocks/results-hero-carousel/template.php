<?php
/**
 * Results hero carousel — Splide carousel of selected swmw_result posts.
 * Featured image: use a composite (person + arch/background) in the file; no CSS arch overlay.
 *
 * @param array $block Block settings.
 *
 * @package SWMW_Law
 */

$is_preview = ! empty( $is_preview );

$class_name = 'results-hero-carousel-block';
if ( ! empty( $block['className'] ) ) {
	$class_name .= ' ' . $block['className'];
}

$selected_ids = get_field( 'hero_carousel_results' );

if ( empty( $selected_ids ) || ! is_array( $selected_ids ) ) {
	if ( $is_preview ) {
		echo '<p><em>' . esc_html__( 'Select one or more results in the sidebar (Featured results).', 'swmw-law' ) . '</em></p>';
	}
	return;
}

$results_query = new WP_Query(
	array(
		'post_type'              => 'swmw_result',
		'post__in'               => array_map( 'absint', $selected_ids ),
		'posts_per_page'         => -1,
		'orderby'                => 'post__in',
		'post_status'            => 'publish',
		'no_found_rows'          => true,
		'update_post_meta_cache' => true,
		'update_post_term_cache' => true,
	)
);

if ( ! $results_query->have_posts() ) {
	if ( $is_preview ) {
		echo '<p><em>' . esc_html__( 'No matching results found.', 'swmw-law' ) . '</em></p>';
	}
	return;
}

// Wide soft size for baked-in hero art; falls back if size missing.
$thumb_size = 'swmw-result-hero-carousel';
?>
<section <?php echo wp_kses_post( get_block_wrapper_attributes( array( 'class' => $class_name ) ) ); ?>>
	<div class="results-hero-carousel__arch">
		<div class="results-hero-carousel__slider results-hero-carousel__slider--splide splide">
			<div class="splide__track">
				<ul class="splide__list">
				<?php
				while ( $results_query->have_posts() ) :
					$results_query->the_post();
					$thumb_id = get_post_thumbnail_id();
					?>
					<li class="splide__slide results-hero-carousel__slide">
						<div class="results-hero-carousel__slide-inner">
							<?php if ( $thumb_id ) : ?>
								<?php
								echo wp_get_attachment_image(
									$thumb_id,
									$thumb_size,
									false,
									array(
										'class'    => 'results-hero-carousel__figure-img',
										'loading'  => 'lazy',
										'decoding' => 'async',
									)
								);
								?>
							<?php endif; ?>
						</div>
						<div class="results-hero-carousel__slide-inner-inner">
							<div class="results-hero-carousel__card">
								<?php
								get_template_part(
									'template-parts/content',
									'result-card',
									array(
										'link_category'  => false,
										'show_secondary' => true,
									)
								);
								?>
							</div>
						</div>
					</li>
					<?php
				endwhile;
				wp_reset_postdata();
				?>
				</ul>
			</div>
		</div>
	</div>
</section>
