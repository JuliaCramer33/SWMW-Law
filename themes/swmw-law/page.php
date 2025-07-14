<?php
/**
 * Template Name: Default Page
 * Template Post Type: page
 *
 * @package SWMW_Law
 */

get_header();
?>

<div class="page-content container-lg">
  <?php
  while ( have_posts() ) :
    the_post();
    get_template_part( 'template-parts/content', 'page' );
  endwhile;
  ?>
</div>

<?php
get_footer(); 
