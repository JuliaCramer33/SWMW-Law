<?php
/**
 * Template Name: Landing Page
 * Description: Minimal landing page template with a basic header and footer. No breadcrumbs, no hero.
 *
 * @package SWMW_Law
 */

get_header( 'landing' );
?>

<main id="primary" class="site-main landing-main" role="main">
  <?php
  if ( have_posts() ) :
    while ( have_posts() ) :
      the_post();
      the_content();
    endwhile;
  endif;
  ?>
</main>

<?php get_footer( 'landing' );

