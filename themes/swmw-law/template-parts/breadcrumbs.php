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

// Check for the custom 'newsfeed' query var first.
if ( get_query_var( 'newsfeed' ) ) {
    $current_page_title = get_the_title( get_option( 'page_for_posts', true ) );
}
// Check for different page types to set the title correctly.
if ( is_home() ) {
    // For the blog page, get the title of the page assigned to posts.
    $current_page_title = get_the_title( get_option( 'page_for_posts', true ) );
} elseif ( is_archive() ) {
    // For category archives, keep the label as is.
    if ( is_category() ) {
        $current_page_title = single_cat_title( '', false );
    } else {
        // For all other archives, remove 'Archive:' or similar prefixes.
        $current_page_title = preg_replace( '/^.*?:\s*/', '', get_the_archive_title() );
    }
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
            $current_post_id = get_the_ID();

            // Case 1: For Pages, show their ancestors.
            if ( is_page() ) {
                $ancestors = get_post_ancestors( $current_post_id );
                if ( ! empty( $ancestors ) ) {
                    $ancestors = array_reverse( $ancestors );
                    foreach ( $ancestors as $ancestor_id ) {
                        ?>
                        <li class="breadcrumb-item breadcrumb-separator" aria-hidden="true">/</li>
                        <li class="breadcrumb-item">
                            <a href="<?php echo esc_url( get_permalink( $ancestor_id ) ); ?>"><?php echo esc_html( get_the_title( $ancestor_id ) ); ?></a>
                        </li>
                        <?php
                    }
                }
            }
            // Case 2: For single blog posts, show the dynamic "Posts" page link.
            elseif ( is_single() && 'post' === get_post_type() ) {
                $posts_page_id = get_option( 'page_for_posts' );
                if ( $posts_page_id ) {
                    $posts_page_title = get_the_title( $posts_page_id );
                    ?>
                    <li class="breadcrumb-item breadcrumb-separator" aria-hidden="true">/</li>
                    <li class="breadcrumb-item">
                        <a href="<?php echo esc_url( home_url( '/newsfeed/' ) ); ?>"><?php echo esc_html( $posts_page_title ); ?></a>
                    </li>
                    <?php
                }
            }
            // Case 3: For other CPTs, show their archive link.
            elseif ( is_singular() && ! is_page() && 'post' !== get_post_type() ) {
                $post_type_obj = get_post_type_object( get_post_type() );
                if ( $post_type_obj && $post_type_obj->has_archive ) {
                    $archive_link = get_post_type_archive_link( get_post_type() );
                    $archive_label = $post_type_obj->labels->name;
                    ?>
                    <li class="breadcrumb-item breadcrumb-separator" aria-hidden="true">/</li>
                    <li class="breadcrumb-item">
                        <a href="<?php echo esc_url( $archive_link ); ?>"><?php echo esc_html( $archive_label ); ?></a>
                    </li>
                    <?php
                }
            }
            ?>
            <li class="breadcrumb-item breadcrumb-separator" aria-hidden="true">/</li>
            <li class="breadcrumb-item breadcrumb-item--current" aria-current="page">
                <?php echo wp_kses_post( $current_page_title ); // Use wp_kses_post if title can contain HTML (e.g. search results span) ?>
            </li>
        </ol>
    </div>
</nav>
 
