<?php
/**
 * Template Name: Category Listing
 * Description: Displays blog posts from a specific category. By default, the category is inferred from
 *              this page's slug. Optionally, add an ACF taxonomy field named `selected_category` to pick a specific category.
 *
 * @package SWMW_Law
 */

get_header();

// Breadcrumbs
get_template_part( 'template-parts/breadcrumbs' );

global $post;

// Determine category: try ACF taxonomy field 'selected_category' (if added later), otherwise infer from page slug.
$category_term = null;
if ( function_exists( 'get_field' ) ) {
    $selected_category = get_field( 'selected_category' );
    if ( $selected_category ) {
        // Field might return term object or ID depending on configuration
        if ( is_object( $selected_category ) && isset( $selected_category->term_id ) ) {
            $category_term = $selected_category;
        } elseif ( is_numeric( $selected_category ) ) {
            $category_term = get_term( (int) $selected_category, 'category' );
        }
    }
}

if ( ! $category_term && $post instanceof WP_Post ) {
    $page_slug    = $post->post_name;
    $category_term = get_term_by( 'slug', $page_slug, 'category' );
}

// Build hero using News Archive options but with the category name as the title
$hero_title           = $category_term ? $category_term->name : get_the_title();
$hero_content         = function_exists( 'get_field' ) ? get_field( 'news_archive_content', 'option' ) : '';
$hero_button          = function_exists( 'get_field' ) ? get_field( 'news_archive_button', 'option' ) : '';
$background_image_url = function_exists( 'get_field' ) ? get_field( 'news_archive_background_image', 'option' ) : '';

$hero_styles = '';
if ( $background_image_url ) {
    $hero_styles = 'style="background-image: url(' . esc_url( $background_image_url ) . ');"';
}
?>

<?php if ( $hero_title ) : ?>
<div class="hero-archive" <?php echo $hero_styles; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
    <div class="container-lg">
        <div class="hero-archive__grid">
            <div class="hero-archive__left">
                <h1 class="hero-archive__title"><?php echo wp_kses_post( $hero_title ); ?></h1>

                <?php if ( is_array( $hero_button ) && ! empty( $hero_button['url'] ) && ! empty( $hero_button['title'] ) ) : ?>
                    <a href="<?php echo esc_url( $hero_button['url'] ); ?>" class="button button--secondary" target="<?php echo esc_attr( ! empty( $hero_button['target'] ) ? $hero_button['target'] : '_self' ); ?>">
                        <?php echo esc_html( $hero_button['title'] ); ?>
                    </a>
                <?php endif; ?>
            </div>

            <?php if ( ! empty( $hero_content ) ) : ?>
                <div class="hero-archive__right">
                    <div class="hero-archive__content wysiwyg-content">
                        <?php echo wp_kses_post( $hero_content ); ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
  </div>
<?php endif; ?>

<main id="primary" class="site-main swmw-archive-page">
    <div class="container-lg swmw-news-grid-container">
        <?php
        // Query posts in this category
        $paged = max( 1, (int) get_query_var( 'paged' ), (int) get_query_var( 'page' ) );
        $query_args = [
            'post_type'      => 'post',
            'post_status'    => 'publish',
            'paged'          => $paged,
            'ignore_sticky_posts' => true,
        ];

        if ( $category_term ) {
            $query_args['cat'] = (int) $category_term->term_id;
        }

        $news_query = new WP_Query( $query_args );

        if ( $news_query->have_posts() ) :
            echo '<div class="swmw-news-grid">';
            while ( $news_query->have_posts() ) :
                $news_query->the_post();
                get_template_part( 'template-parts/content', 'post-card' );
            endwhile;
            echo '</div><!-- .swmw-news-grid -->';

            // Pagination for custom query
            $big = 999999999; // need an unlikely integer
            $pagination = paginate_links( [
                'base'      => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
                'format'    => '?paged=%#%',
                'current'   => $paged,
                'total'     => (int) $news_query->max_num_pages,
                'prev_text' => esc_html__( '< Prev', 'swmw-law' ),
                'next_text' => esc_html__( 'Next >', 'swmw-law' ),
                'type'      => 'list',
            ] );

            if ( $pagination ) {
                // Match class name used elsewhere
                echo '<nav class="navigation pagination swmw-pagination" role="navigation">' . $pagination . '</nav>';
            }

            wp_reset_postdata();
        else :
            get_template_part( 'template-parts/content', 'none' );
        endif;
        ?>
    </div>
</main>

<?php get_footer();


