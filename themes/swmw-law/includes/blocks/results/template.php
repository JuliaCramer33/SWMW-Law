<?php
/**
 * Results Block Template.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 *
 * @package SWMW_Law
 */

$class_name = 'wp-block results-block';
if ( ! empty( $block['className'] ) ) {
    $class_name .= ' ' . $block['className'];
}

?>
<section <?php echo wp_kses_post( get_block_wrapper_attributes( [ 'class' => $class_name ] ) ); ?>>
    <?php
    // --- Get Selected Results from ACF Relationship Field ---
    $selected_results_ids = get_field('selected_results'); // ACF field name

    // --- Query Arguments ---
    $num_posts = apply_filters( 'swmw_results_block_num_posts', 6 ); // Default for fallback

    if ( ! empty( $selected_results_ids ) && is_array( $selected_results_ids ) ) {
        $args = array(
            'post_type'      => 'swmw_result',
            'post__in'       => $selected_results_ids,
            'posts_per_page' => -1, // Show all selected posts
            'orderby'        => 'post__in', // Order by the sequence they were selected
        );
    } else {
        // Fallback: show featured results by default
        $args = array(
            'post_type'      => 'swmw_result',
            'posts_per_page' => $num_posts,
            'tax_query'      => array(
                array(
                    'taxonomy' => 'swmw_result_status',
                    'field'    => 'slug',
                    'terms'    => 'featured',
                ),
            ),
            // Order featured by numeric amount desc, then date
            'meta_key'       => 'result_amount_num',
            'orderby'        => array(
                'meta_value_num' => 'DESC',
                'date'           => 'DESC',
            ),
        );
    }

    $results_query = new WP_Query( $args );
    $results_count = $results_query->found_posts;

    if ( $results_query->have_posts() ) :
        ?>
        <?php if ($results_count > 3) : ?>
        <!-- Start: Results Slider -->
        <div class="results-slider splide">
            <div class="splide__track">
                <ul class="splide__list">
                    <?php
                    $slide_count = 0;
                    while ( $results_query->have_posts() ) :
                        $results_query->the_post();
                        $slide_count++;
                    ?>
                        <li class="splide__slide result-item">
                            <div class="result-item-inner">
                                <div class="result-content">
                                    <?php
                                    // Get and display the result category
                                    $terms = get_the_terms(get_the_ID(), 'swmw_result_category');
                                    if (!empty($terms) && !is_wp_error($terms)) {
                                        $category = $terms[0];
                                        $term_link = get_term_link($category);
                                        if (!is_wp_error($term_link)) {
                                            echo '<a class="result-category" href="' . esc_url($term_link) . '">' . esc_html($category->name) . '</a>';
                                        } else {
                                            echo '<span class="result-category">' . esc_html($category->name) . '</span>';
                                        }
                                    }

                                    $result_amount = get_field( 'result_amount', get_the_ID() );
                                    ?>

                                    <?php $fmt_amount = \SWMW_Law\swmw_law_get_formatted_amount(); ?>
                                    <?php if ( $fmt_amount ) : ?>
                                        <h3 class="result-amount"><?php echo esc_html( $fmt_amount ); ?></h3>
                                    <?php endif; ?>
                                    <?php $heading_occ = \SWMW_Law\swmw_law_format_result_heading_occupation(); ?>
                                    <?php if ( $heading_occ ) : ?>
                                        <h4 class="result-title"><?php echo esc_html( $heading_occ ); ?></h4>
                                    <?php endif; ?>
                                    <?php $subtext = \SWMW_Law\swmw_law_format_result_subtext(); ?>
                                    <?php if ( $subtext ) : ?>
                                        <p class="result-subtext"><?php echo esc_html( $subtext ); ?></p>
                                    <?php endif; ?>
                                    <?php $secondary = get_field( 'result_secondary_description' ); ?>
                                    <?php if ( $secondary ) : ?>
                                        <p class="result-description"><?php echo esc_html( $secondary ); ?></p>
                                    <?php endif; ?>

                                    
                                </div>
                            </div>
                        </li>
                    <?php endwhile; ?>
                    <?php // Debug info ?>
                    <!-- Total Slides: <?php echo esc_html($slide_count); ?> -->
                </ul>
            </div>
        </div>
        <!-- End: Results Slider -->
        <?php else : // 3 or fewer results, output directly ?>
            <div class="results-grid">
                <?php while ( $results_query->have_posts() ) : $results_query->the_post(); ?>
                    <div class="result-item">
                        <div class="result-item-inner">
                            <div class="result-content">
                                <?php
                                // Get and display the result category
                                $terms = get_the_terms(get_the_ID(), 'swmw_result_category');
                                if (!empty($terms) && !is_wp_error($terms)) {
                                    $category = $terms[0];
                                    $term_link = get_term_link($category);
                                    if (!is_wp_error($term_link)) {
                                        echo '<a class="result-category" href="' . esc_url($term_link) . '">' . esc_html($category->name) . '</a>';
                                    } else {
                                        echo '<span class="result-category">' . esc_html($category->name) . '</span>';
                                    }
                                }

                                $result_amount = get_field( 'result_amount', get_the_ID() );
                                ?>

                                <?php $fmt_amount = \SWMW_Law\swmw_law_get_formatted_amount(); ?>
                                <?php if ( $fmt_amount ) : ?>
                                    <h3 class="result-amount"><?php echo esc_html( $fmt_amount ); ?></h3>
                                <?php endif; ?>
                                <?php $heading_occ = \SWMW_Law\swmw_law_format_result_heading_occupation(); ?>
                                <?php if ( $heading_occ ) : ?>
                                    <h4 class="result-title"><?php echo esc_html( $heading_occ ); ?></h4>
                                <?php endif; ?>
                                <?php $subtext = \SWMW_Law\swmw_law_format_result_subtext(); ?>
                                <?php if ( $subtext ) : ?>
                                    <p class="result-subtext"><?php echo esc_html( $subtext ); ?></p>
                                <?php endif; ?>
                                <?php $secondary = get_field( 'result_secondary_description' ); ?>
                                <?php if ( $secondary ) : ?>
                                    <p class="result-description"><?php echo esc_html( $secondary ); ?></p>
                                <?php endif; ?>

                                
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php endif; ?>

        <?php
        wp_reset_postdata();
    else :
        ?>
        <?php if ( $is_preview ) : ?>
            <p><em>No results found. Please check your Results CPT or block settings.</em></p>
        <?php endif; ?>
    <?php endif; ?>
</section>
