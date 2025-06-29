<?php
/**
 * Template part for displaying page content in page.php
 *
 * @package SWMW_Law
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <div class="entry-content">
        <?php
        the_content();

        wp_link_pages( array(
            'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'swmw-law' ),
            'after'  => '</div>',
        ) );
        ?>
    </div>
</article> 
