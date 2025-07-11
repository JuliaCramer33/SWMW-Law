<?php
/**
 * Template part for displaying a post card in a grid (e.g., on the blog archive).
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package SWMW_Law
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'swmw-post-card' ); ?>>
    <div class="swmw-post-card__image-wrapper">
        <?php if ( has_post_thumbnail() ) : ?>
            <a href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
                <?php the_post_thumbnail( 'medium_large', [ 'class' => 'swmw-post-card__image' ] ); ?>
            </a>
        <?php else : ?>
            <a href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true" class="swmw-post-card__image-placeholder">
                <span class="screen-reader-text"><?php esc_html_e( 'Placeholder image', 'swmw-law' ); ?></span>
            </a>
        <?php endif; ?>
    </div>

    <div class="swmw-post-card__content">
        <header class="swmw-post-card__header">
            <span class="swmw-post-card__date"><?php echo esc_html( strtoupper( get_the_date() ) ); ?></span>
            <?php
            $categories = get_the_category();
            if ( $categories ) : ?>
                <span class="swmw-post-card__categories">
                    <?php foreach ( $categories as $category ) : ?>
                        <a href="<?php echo esc_url( get_category_link( $category->term_id ) ); ?>" class="swmw-post-card__category-pill">
                            <?php echo esc_html( $category->name ); ?>
                        </a>
                    <?php endforeach; ?>
                </span>
            <?php endif; ?>
            <?php the_title( sprintf( '<h2 class="swmw-post-card__title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
        </header>

        <div class="swmw-post-card__excerpt">
            <?php
            $excerpt = get_the_excerpt();
            if ( strlen( $excerpt ) > 125 ) {
                $trimmed_excerpt = substr( $excerpt, 0, 125 );
                $last_space = strrpos( $trimmed_excerpt, ' ' );
                if ( false !== $last_space ) {
                    $trimmed_excerpt = substr( $trimmed_excerpt, 0, $last_space );
                }
                echo '<p>' . esc_html( $trimmed_excerpt ) . '...</p>';
            } else {
                the_excerpt();
            }
            ?>
        </div>

        <footer class="swmw-post-card__footer">
            <a href="<?php the_permalink(); ?>" class="button button-secondary-outline">Read More</a>
        </footer>
    </div>
</article> 
