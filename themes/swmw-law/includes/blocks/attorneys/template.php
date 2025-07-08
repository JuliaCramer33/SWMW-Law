<?php
/**
 * Attorneys Block Template.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */

// Get Selected Attorneys
$selected_attorneys = get_field('selected_attorneys');

// Only proceed if attorneys are selected
if (!empty($selected_attorneys)) {
    $args = array(
        'post_type'      => 'attorney',
        'posts_per_page' => -1,
        'post__in'       => $selected_attorneys,
        'orderby'        => 'post__in'
    );

    $attorneys_query = new WP_Query($args);
    ?>

    <div <?php echo wp_kses_post( get_block_wrapper_attributes( [ 'class' => 'wp-block attorneys-block' ] ) ); ?>>
        <?php if ($attorneys_query->have_posts()) : ?>
          <div class="attorneys-slider-wrapper container-lg">
            <div class="splide">
                <div class="splide__track">
                    <ul class="splide__list">
                        <?php while ($attorneys_query->have_posts()) : $attorneys_query->the_post(); ?>
                            <li class="splide__slide attorney-item">
                                <div class="attorney-item-inner">
                                    <?php if (has_post_thumbnail()) : ?>
                                        <div class="attorney-image">
                                            <a href="<?php the_permalink(); ?>" tabindex="-1">
                                                <?php the_post_thumbnail('large'); ?>
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <h3 class="attorney-name">
                                        <a href="<?php the_permalink(); ?>">
                                            <?php the_title(); ?>
                                        </a>
                                    </h3>

                                    <?php if ( get_field( 'attorney_title', get_the_ID() ) ) : ?>
                                        <div class="attorney-title">
                                            <p><?php the_field( 'attorney_title', get_the_ID() ); ?></p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </li>
                        <?php endwhile; ?>
                    </ul>
                </div>
                <div class="splide__arrows">
                    <button class="splide__arrow splide__arrow--prev">
                        <svg width="10" height="18" viewBox="0 0 10 18" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M9 17L1 9L9 1" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </button>
                    <button class="splide__arrow splide__arrow--next">
                        <svg width="10" height="18" viewBox="0 0 10 18" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1 1L9 9L1 17" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </button>
                </div>
            </div>
            <?php wp_reset_postdata(); ?>
          </div>
        <?php endif; ?>
    </div>
<?php 
} else if ($is_preview) {
?>
    <div <?php echo get_block_wrapper_attributes( [ 'class' => 'attorneys-block' ] ); ?>>
        <p class="attorney-placeholder"><em>Please select attorneys to display in the block settings.</em></p>
    </div>
<?php 
}
