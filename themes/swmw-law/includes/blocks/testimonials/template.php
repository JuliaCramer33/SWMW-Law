<?php
/**
 * Testimonials Block Template.
 *
 * @package SWMW_Law
 */

// Get Selected Testimonials from ACF Relationship Field
$selected_testimonials_ids = get_field('selected_testimonials');

// Query Arguments
$num_posts = apply_filters('swmw_testimonials_block_num_posts', 6);

if (!empty($selected_testimonials_ids) && is_array($selected_testimonials_ids)) {
    $args = array(
        'post_type'      => 'swmw_testimonial',
        'post__in'       => $selected_testimonials_ids,
        'posts_per_page' => -1,
        'orderby'        => 'post__in',
    );
} else {
    $args = array(
        'post_type'      => 'swmw_testimonial',
        'posts_per_page' => $num_posts,
        'orderby'        => 'date',
        'order'          => 'DESC',
    );
}

$testimonials_query = new WP_Query($args);
$testimonial_count = $testimonials_query->found_posts;
?>

<div <?php echo wp_kses_post( get_block_wrapper_attributes( [ 'class' => 'wp-block testimonials-block' ] ) ); ?>>
    <?php if ($testimonials_query->have_posts()) : ?>
     <div class="testimonial-frame">
            <div class="testimonial-quote-icon" aria-hidden="true"><svg width="51" height="51" viewBox="0 0 51 51" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M20.1388 10.2081C12.2944 15.2458 7.93653 21.1094 7.06511 27.7986C5.70849 38.2123 15.0212 43.3095 19.7398 38.7297C24.4585 34.1499 21.6299 28.3369 18.2132 26.7479C14.7964 25.1588 12.7069 25.7123 13.0714 23.5888C13.4359 21.4655 18.2969 15.5782 22.5675 12.8364C22.8509 12.5955 22.9587 12.128 22.6859 11.7733C22.5064 11.5401 22.1544 11.0826 21.6299 10.4009C21.1713 9.80483 20.7319 9.82714 20.1388 10.2081Z" fill="#417E4B"/><path fill-rule="evenodd" clip-rule="evenodd" d="M40.7905 10.2081C32.9462 15.2458 28.5883 21.1094 27.7168 27.7986C26.3603 38.2123 35.673 43.3095 40.3916 38.7297C45.1103 34.1499 42.2817 28.3369 38.865 26.7479C35.4482 25.1588 33.3586 25.7123 33.7232 23.5888C34.0877 21.4655 38.9487 15.5782 43.2193 12.8364C43.5027 12.5955 43.6105 12.128 43.3376 11.7733C43.1582 11.5401 42.8062 11.0826 42.2817 10.4009C41.8231 9.80483 41.3837 9.82714 40.7905 10.2081Z" fill="#417E4B"/></svg></div>
            <?php if ($testimonial_count > 1) : ?>
            <div class="splide__arrows">
                <button class="splide__arrow splide__arrow--prev"></button>
                <button class="splide__arrow splide__arrow--next"></button>
            </div>
            <div class="testimonials-slider splide">
             <div class="splide__track">
                 <ul class="splide__list">
                        <?php while ($testimonials_query->have_posts()) : $testimonials_query->the_post();
                            $current_id = get_the_ID();
                        ?>
                         <li class="splide__slide">
                                <?php // This is the content that will slide ?>
                                <div class="testimonial-slide-content">
                                    <?php
                                    $testimonial_content = get_field('testimonial_content', $current_id);
                                    if ($testimonial_content) : ?>
                                        <div class="quote-content">
                                            <?php echo wp_kses_post($testimonial_content); ?>
                                        </div>
                                    <?php endif; ?>
                                    <footer class="testimonial-author">
                                        <?php
                                        $author_image = get_field('testimonial_featured_image', $current_id);
                                        if ($author_image) : ?>
                                            <div class="author-image">
                                                <?php
                                                echo wp_get_attachment_image($author_image, 'thumbnail', false, array(
                                                    'class' => 'testimonial-author-image',
                                                    'alt' => esc_attr(get_the_title())
                                                ));
                                                ?>
                                            </div>
                                        <?php endif; ?>
                                        <cite class="author-name"><?php echo esc_html(get_the_title()); ?></cite>
                                    </footer>
                             </div>
                         </li>
                        <?php endwhile; ?>
                 </ul>
             </div>
         </div>
         <?php else : // Only one testimonial, output directly ?>
            <?php $testimonials_query->the_post(); $current_id = get_the_ID(); ?>
            <div class="testimonial-slide-content">
                <?php
                $testimonial_content = get_field('testimonial_content', $current_id);
                if ($testimonial_content) : ?>
                    <div class="quote-content">
                        <?php echo wp_kses_post($testimonial_content); ?>
                    </div>
                <?php endif; ?>
                <footer class="testimonial-author">
                    <?php
                    $author_image = get_field('testimonial_featured_image', $current_id);
                    if ($author_image) : ?>
                        <div class="author-image">
                            <?php
                            echo wp_get_attachment_image($author_image, 'thumbnail', false, array(
                                'class' => 'testimonial-author-image',
                                'alt' => esc_attr(get_the_title())
                            ));
                            ?>
                        </div>
                    <?php endif; ?>
                    <cite class="author-name"><?php echo esc_html(get_the_title()); ?></cite>
                </footer>
            </div>
         <?php endif; ?>
     </div>
        <?php wp_reset_postdata(); ?>
    <?php elseif ($is_preview) : ?>
        <p><em>No testimonials found. Please check your Testimonials CPT or block settings.</em></p>
    <?php endif; ?>
</div>
