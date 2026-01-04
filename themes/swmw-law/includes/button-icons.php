<?php
/**
 * Button icon helpers (rendering arrow/custom icon for core/button)
 */

namespace SWMW_Law;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Render the icon on the front-end for the core/button block.
 * Supports a built-in arrow (inherits currentColor) or a custom media icon.
 *
 * @param string $block_content The block content.
 * @param array  $block         The full block, including name and attributes.
 *
 * @return string Modified block content.
 */
function swmw_law_render_button_icon( $block_content, $block ) {
    $attrs = isset( $block['attrs'] ) ? $block['attrs'] : [];

    $icon_url = '';
    $icon_alt = '';

    if ( ! empty( $attrs['withArrow'] ) ) {
        // Inline SVG arrow that inherits text color
        $closing_tag_pos = strrpos( $block_content, '</a>' );
        if ( false !== $closing_tag_pos ) {
            $svg = '<svg class="wp-block-button__icon" aria-hidden="true" focusable="false" width="1em" height="1em" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-left: 8px;">
                <path d="M5 12h14"></path>
                <path d="M12 5l7 7-7 7"></path>
            </svg>';
            $block_content = substr_replace( $block_content, $svg, $closing_tag_pos, 0 );
        }
        return $block_content;
    } elseif ( ! empty( $attrs['iconUrl'] ) ) {
        $icon_url = esc_url( $attrs['iconUrl'] );
        $icon_alt = isset( $attrs['iconAlt'] ) ? esc_attr( $attrs['iconAlt'] ) : '';
    }

    if ( $icon_url ) {
        $closing_tag_pos = strrpos( $block_content, '</a>' );
        if ( false !== $closing_tag_pos ) {
            $icon_html = sprintf(
                '<img src="%s" alt="%s" class="wp-block-button__icon" style="margin-left: 8px; height: 1em; width: auto;" />',
                esc_url( $icon_url ),
                esc_attr( $icon_alt )
            );
            $block_content = substr_replace( $block_content, $icon_html, $closing_tag_pos, 0 );
        }
    }

    return $block_content;
}

add_filter( 'render_block_core/button', __NAMESPACE__ . '\swmw_law_render_button_icon', 10, 2 );


