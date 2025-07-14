<?php
/**
 * Template part for displaying posts
 *
 * @package SWMW_Law
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <header class="entry-header container-lg">
        <?php
        if ( is_singular() ) :
            the_title( '<h1 class="entry-title">', '</h1>' );
        else :
            the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
        endif;

        if ( 'post' === get_post_type() ) :
            ?>
            <div class="entry-meta">
                <?php
                \SWMW_Law\posted_on();
                \SWMW_Law\posted_by();
                ?>
            </div><!-- .entry-meta -->
        <?php endif; ?>
    </header><!-- .entry-header -->

    <?php \SWMW_Law\post_thumbnail(); ?>

    <div class="entry-content">
        <?php
        if ( is_singular() ) :
            the_content(
                sprintf(
                    wp_kses(
                        /* translators: %s: Name of current post. Only visible to screen readers */
                        __( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'swmw-law' ),
                        [
                            'span' => [
                                'class' => [],
                            ],
                        ]
                    ),
                    wp_kses_post( get_the_title() )
                )
            );

            wp_link_pages(
                [
                    'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'swmw-law' ),
                    'after'  => '</div>',
                ]
            );
        else :
            the_excerpt();
            ?>
            <a href="<?php echo esc_url( get_permalink() ); ?>" class="read-more">
                <?php esc_html_e( 'Read More', 'swmw-law' ); ?>
            </a>
        <?php endif; ?>
    </div><!-- .entry-content -->
</article><!-- #post-<?php the_ID(); ?> --> 
