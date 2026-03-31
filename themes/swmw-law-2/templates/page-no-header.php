<?php
/**
 * Template Name: No Header
 * Template Post Type: page
 *
 * @package SWMW_Law
 */

// Get header but remove the automatic header template part inclusion
add_filter('swmw_law_show_page_header', '__return_false', 100);
get_header();
remove_filter('swmw_law_show_page_header', '__return_false', 100);
?>

<div class="page-content">
    <div class="container-lg">
        <?php
        while ( have_posts() ) :
            the_post();
            get_template_part( 'template-parts/content', 'page' );
        endwhile;
        ?>
    </div>
</div>

<?php
get_footer(); 
