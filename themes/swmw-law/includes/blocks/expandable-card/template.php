<?php
/**
 * Expandable Card Block Template.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 *
 * @package SWMW_Law
 */

// Create id attribute allowing for custom "anchor" value.
$block_id = 'expandable-card-' . $block['id'];
if( !empty($block['anchor']) ) {
    $block_id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$className = 'expandable-card';
if( !empty($block['className']) ) {
    $className .= ' ' . $block['className'];
}
if( !empty($block['align']) ) {
    $className .= ' align' . $block['align'];
}

// Load ACF fields
$icon = get_field('icon');
$card_title = get_field('title');
$content = get_field('content');
$word_count = get_field('word_count') ?: 25;
$button_text = get_field('button_text') ?: 'More';

$has_more_content = false;
$collapsed_content_html = '';

if ($content) {
    // Check word count against plain text version of content
    $word_array = explode(' ', wp_strip_all_tags($content));
    $total_words = count($word_array);

    if ($total_words > $word_count) {
        // We need to truncate
        $has_more_content = true;
        $truncated_words = array_slice($word_array, 0, $word_count);
        $excerpt_text = implode(' ', $truncated_words) . '...';
        // Wrap the plain text excerpt in <p> tags for consistent styling
        $collapsed_content_html = wpautop(esc_html($excerpt_text));
    } else {
        // Content is short enough, no truncation needed
        $has_more_content = false;
        $collapsed_content_html = wp_kses_post($content); // Use the original content with HTML
    }
}
?>

<div id="<?php echo esc_attr($block_id); ?>" <?php echo wp_kses_post( get_block_wrapper_attributes( [ 'class' => $className ] ) ); ?>>
    <div class="expandable-card">
        <div class="header">
            <?php if ($icon): ?>
                <div class="icon">
                    <img src="<?php echo esc_url($icon['url']); ?>" alt="<?php echo esc_attr($icon['alt']); ?>" class="icon-img">
                </div>
            <?php elseif ($is_preview): ?>
                <div class="icon">
                    <div class="icon-placeholder">📄</div>
                </div>
            <?php endif; ?>
            
            <div class="content">
                <?php if ($card_title): ?>
                    <h3 class="title"><?php echo esc_html($card_title); ?></h3>
                <?php elseif ($is_preview): ?>
                    <h3 class="title">Card Title</h3>
                <?php endif; ?>
                
                <div class="description-wrapper">
                    <div class="description-content collapsed-content">
                        <?php echo wp_kses_post($collapsed_content_html); ?>
                    </div>
                    <?php if ($has_more_content): ?>
                        <div class="description-content expanded-content">
                            <?php echo wp_kses_post($content); ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <?php if ($has_more_content): ?>
                <button class="expand-button" aria-expanded="false">
                    <span class="button-icon">+</span>
                    <span class="button-text"><?php echo esc_html($button_text); ?></span>
                </button>
            <?php elseif ($is_preview): ?>
                <button class="expand-button expand-button-placeholder" disabled>
                    <span class="button-icon">+</span>
                    <span class="button-text">More</span>
                </button>
            <?php endif; ?>
        </div>
    </div>
</div>
