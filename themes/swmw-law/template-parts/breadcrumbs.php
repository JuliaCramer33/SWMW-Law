<?php
/**
 * Template part for displaying breadcrumbs.
 *
 * @package SWMW_Law
 */

// Don't display on the front page.
if ( is_front_page() ) {
    return;
}

$current_page_title = '';

// Check for different page types to set the title correctly.
if ( is_home() ) {
    // For the blog page, get the title of the page assigned to posts.
    $current_page_title = get_the_title( get_option( 'page_for_posts', true ) );
} elseif ( is_archive() ) {
    // For any other archive (category, tag, etc.).
    $current_page_title = get_the_archive_title();
} elseif ( is_search() ) {
    // For search results.
    $current_page_title = sprintf( esc_html__( 'Search Results for: %s', 'swmw-law' ), '<span>' . get_search_query() . '</span>' );
} else {
    // For all other pages (single posts, pages, etc.).
    $current_page_title = get_the_title();
}

?>
<nav aria-label="<?php esc_attr_e( 'Breadcrumbs', 'swmw-law' ); ?>" class="breadcrumbs-nav">
    <div class="container-lg">
        <ol class="breadcrumbs-list">
            <li class="breadcrumb-item breadcrumb-item--home">
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>">
                    <svg class="breadcrumb-home-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16" fill="currentColor" aria-hidden="true"><path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8h5z"/><path d="M0 0h24v24H0z" fill="none"/></svg>
                    <span class="screen-reader-text"><?php esc_html_e( 'Home', 'swmw-law' ); ?></span>
                </a>
            </li>
            <?php
            // Add parent archive for single posts (blog/news)
            if ( is_single() && get_post_type() === 'post' && get_option('page_for_posts') ) : ?>
                <li class="breadcrumb-item breadcrumb-separator" aria-hidden="true">/</li>
                <li class="breadcrumb-item">
                    <a href="<?php echo esc_url( get_permalink( get_option('page_for_posts') ) ); ?>">
                        <?php echo esc_html( get_the_title( get_option('page_for_posts') ) ); ?>
                    </a>
                </li>
            <?php
            // Add parent archive for custom post types
            elseif ( is_singular() && ! is_page() && 'post' !== get_post_type() ) :
                $cpt_breadcrumb_type = get_post_type();
                $post_type_obj = get_post_type_object( $cpt_breadcrumb_type );
                if ( $post_type_obj && $post_type_obj->has_archive ) :
                    $archive_link = get_post_type_archive_link( $cpt_breadcrumb_type );
                    $archive_label = $post_type_obj->labels->name;
            ?>
                <li class="breadcrumb-item breadcrumb-separator" aria-hidden="true">/</li>
                <li class="breadcrumb-item">
                    <a href="<?php echo esc_url( $archive_link ); ?>"><?php echo esc_html( $archive_label ); ?></a>
                </li>
            <?php endif; endif; ?>
            <li class="breadcrumb-item breadcrumb-separator" aria-hidden="true">/</li>
            <li class="breadcrumb-item breadcrumb-item--current" aria-current="page">
                <?php echo wp_kses_post( $current_page_title ); // Use wp_kses_post if title can contain HTML (e.g. search results span) ?>
            </li>
        </ol>
    </div>
</nav>
 
