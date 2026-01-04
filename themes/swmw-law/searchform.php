<?php
/**
 * The template for displaying search forms
 *
 * @package SWMW_Law
 */
?>

<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
    <label>
        <span class="screen-reader-text"><?php echo esc_html_x( 'Search for:', 'label', 'swmw-law' ); ?></span>
        <input type="search" class="search-field" placeholder="<?php echo esc_attr_x( 'Search &hellip;', 'placeholder', 'swmw-law' ); ?>" value="<?php echo get_search_query(); ?>" name="s" />
    </label>
    <button type="submit" class="search-submit">
        <span class="screen-reader-text"><?php echo esc_html_x( 'Search', 'submit button', 'swmw-law' ); ?></span>
        <svg class="icon icon-search" aria-hidden="true" role="img" focusable="false" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20">
            <path d="M19.7 17.3l-4.5-4.5c.8-1.1 1.3-2.5 1.3-4 0-3.6-2.9-6.5-6.5-6.5S3.5 5.2 3.5 8.8s2.9 6.5 6.5 6.5c1.5 0 2.9-.5 4-1.3l4.5 4.5c.2.2.5.3.7.3s.5-.1.7-.3c.4-.4.4-1 0-1.4zM8.5 13.8c-2.8 0-5-2.2-5-5s2.2-5 5-5 5 2.2 5 5-2.2 5-5 5z"/>
        </svg>
    </button>
</form> 
