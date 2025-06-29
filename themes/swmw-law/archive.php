<?php
/**
 * The template for displaying archive pages.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package SWMW_Law
 */

get_header();

$description = get_the_archive_description();
?>

<?php get_template_part( 'template-parts/breadcrumbs' ); ?>

<main id="primary" class="site-main swmw-archive-page">

    <!-- <section class="hero-section swmw-archive-hero">
        <div class="container">
            <?php
            the_archive_title( '<h1 class="page-title hero-title">', '</h1>' );
            the_archive_description( '<div class="archive-description">', '</div>' );
            ?>
        </div>
    </section> -->

    <div class="container-lg swmw-news-grid-container">
        <?php if ( have_posts() ) : ?>
            <div class="swmw-news-grid">
                <?php
                /* Start the Loop */
                while ( have_posts() ) :
                    the_post();

                    /*
                     * Include the Post-Type-specific template for the content.
                     * If this is a CPT archive, it will look for content-{post_type}.php
                     * For regular posts, it will use content-post.php or content.php
                     * We are using content-post-card.php for a consistent card layout.
                     */
                    get_template_part( 'template-parts/content', 'post-card' );

                endwhile;
                ?>
            </div><!-- .swmw-news-grid -->
            <?php
            // Previous/next page navigation.
            the_posts_pagination(
                [
                    'prev_text'          => esc_html__( '< Prev', 'swmw-law' ),
                    'next_text'          => esc_html__( 'Next >', 'swmw-law' ),
                    'screen_reader_text' => esc_html__( 'Posts navigation', 'swmw-law' ),
                    'aria_label'         => esc_html__( 'Posts', 'swmw-law' ),
                    'class'              => 'swmw-pagination',
                ]
            );
            ?>
        <?php else : ?>
            <?php get_template_part( 'template-parts/content', 'none' ); ?>
        <?php endif; ?>
    </div><!-- .container.swmw-news-grid-container -->

</main><!-- #main -->

<?php
get_footer(); 
