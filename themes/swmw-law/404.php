<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @package SWMW_Law
 */

get_header();
?>

    <main id="main" class="site-main">
        <section class="error-404 not-found">
            <div class="container">
                <header class="page-header">
                    <h1 class="page-title"><?php esc_html_e( 'Oops! That page can&rsquo;t be found.', 'swmw-law' ); ?></h1>
                </header><!-- .page-header -->

                <div class="page-content">
                    <p><?php esc_html_e( 'It looks like nothing was found at this location. Maybe try one of the links below or a search?', 'swmw-law' ); ?></p>

                    <?php get_search_form(); ?>

                    <div class="widget-area">
                        <div class="widget widget_categories">
                            <h2 class="widget-title"><?php esc_html_e( 'Most Used Categories', 'swmw-law' ); ?></h2>
                            <ul>
                                <?php
                                wp_list_categories(
                                    [
                                        'orderby'    => 'count',
                                        'order'      => 'DESC',
                                        'show_count' => 1,
                                        'title_li'   => '',
                                        'number'     => 10,
                                    ]
                                );
                                ?>
                            </ul>
                        </div><!-- .widget -->

                        <?php
                        /* translators: %1$s: smiley */
                        $archive_content = '<p>' . sprintf( esc_html__( 'Try looking in the monthly archives. %1$s', 'swmw-law' ), convert_smilies( ':)' ) ) . '</p>';
                        the_widget( 'WP_Widget_Archives', 'dropdown=1', "after_title=</h2>$archive_content" );
                        ?>
                    </div><!-- .widget-area -->
                </div><!-- .page-content -->
            </div>
        </section><!-- .error-404 -->
    </main><!-- #main -->

<?php
get_footer(); 
