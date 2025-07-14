<?php
/**
 * Template part for displaying single posts.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package SWMW_Law
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

    <?php if ( has_post_thumbnail() ) : ?>
      <div class="single-post-hero">
        <div class="single-post-hero--content container-lg">
          <?php the_post_thumbnail( 'full' ); ?>
        </div>
      </div>
    <?php endif; ?>

    
        <div class="entry-content-wrapper">
          <div class="post-content-container">
            <div class="container-lg">
              <div class="post-content-inner">
                <div class="entry-meta container-md">
                    <?php
                    $categories = get_the_category();
                    if ( ! empty( $categories ) ) {
                        echo '<div class="category-pills">';
                        foreach ( $categories as $category ) {
                            echo '<a href="' . esc_url( get_category_link( $category->term_id ) ) . '" class="category-pill">' . esc_html( $category->name ) . '</a>';
                        }
                        echo '</div>';
                    }
                    ?>
                    <hr class="meta-separator" />
                    
                </div><!-- .entry-meta -->
                <div class="container-sm">
                  <span class="posted-on"><?php echo get_the_date(); ?></span>
                  <header class="entry-header">
                      <?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
                  </header><!-- .entry-header -->

                  <div class="entry-content">
                      <?php
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
                              get_the_title()
                          )
                      );

                      wp_link_pages(
                          [
                              'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'swmw-law' ),
                              'after'  => '</div>',
                          ]
                      );
                      ?>
                  </div><!-- .entry-content -->
                  
                  <?php
                  // Display the post navigation.
                  the_post_navigation(
                      [
                          'prev_text' => '<span class="nav-arrow">&larr;</span><span class="nav-title"> Prev Post</span>',
                          'next_text' => '<span class="nav-title">Next Post </span><span class="nav-arrow">&rarr;</span>',
                          'screen_reader_text' => __( 'Continue Reading', 'swmw-law' ),
                      ]
                  );
                  ?>
                </div>
              </div>
            </div>
          </div>
        </div>
    </div>

    <div class="post-pagination-wrapper">
        <div class="container">
            <div class="post-pagination-inner">
                <?php
                $prev_post = get_previous_post();
                $next_post = get_next_post();
                ?>
            </div>
        </div>
    </div>
</article><!-- #post-<?php the_ID(); ?> --> 
