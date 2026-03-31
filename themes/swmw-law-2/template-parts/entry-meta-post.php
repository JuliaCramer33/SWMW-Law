<?php
/**
 * Template part for displaying a single post's meta, like categories.
 *
 * @package SWMW_Law
 */

$categories = get_the_category();
if ( ! empty( $categories ) ) :
?>
<div class="entry-meta">
    <div class="entry-meta__categories">
        <?php
        foreach ( $categories as $category ) {
            printf(
                '<a href="%1$s" class="category-pill">%2$s</a>',
                esc_url( get_category_link( $category->term_id ) ),
                esc_html( $category->name )
            );
        }
        ?>
    </div>
</div>
<?php
endif; 
