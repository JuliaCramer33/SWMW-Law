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
$excerpt_length = get_field('excerpt_length') ?: 150;
$button_text = get_field('button_text') ?: 'More';

// Generate excerpt and full content
$excerpt = '';
$full_content = '';
$has_more_content = false;

if ($content) {
    // Strip HTML tags for character counting
    $plain_text = wp_strip_all_tags($content);
    
    if (strlen($plain_text) > $excerpt_length) {
        // Truncate to excerpt length, trying to break at word boundary
        $excerpt = substr($plain_text, 0, $excerpt_length);
        $last_space = strrpos($excerpt, ' ');
        if ($last_space !== false) {
            $excerpt = substr($excerpt, 0, $last_space);
        }
        $excerpt .= '...';
        $full_content = $content;
        $has_more_content = true;
    } else {
        // Content is short enough, show it all
        $excerpt = $plain_text;
        $has_more_content = false;
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
                    <?php if ($has_more_content): ?>
                        <p class="description collapsed"><?php echo esc_html($excerpt); ?></p>
                        <div class="description expanded" style="display: none;">
                            <?php echo wp_kses_post($full_content); ?>
                        </div>
                    <?php elseif ($excerpt): ?>
                        <p class="description"><?php echo esc_html($excerpt); ?></p>
                    <?php elseif ($is_preview): ?>
                        <p class="description">This is a preview of the card content. Add your content in the block settings to see it here.</p>
                    <?php endif; ?>
                </div>
            </div>
            
            <?php if ($has_more_content): ?>
                <button class="button" onclick="toggleExpandableCard(this)" aria-expanded="false">
                    <span class="button-icon">+</span>
                    <span class="button-text"><?php echo esc_html($button_text); ?></span>
                </button>
            <?php elseif ($is_preview): ?>
                <button class="button button-placeholder" disabled>
                    <span class="button-icon">+</span>
                    <span class="button-text">More</span>
                </button>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
function toggleExpandableCard(button) {
    const card = button.closest('.expandable-card');
    const collapsedContent = card.querySelector('.description.collapsed');
    const expandedContent = card.querySelector('.description.expanded');
    const icon = button.querySelector('.button-icon');
    const isExpanded = button.getAttribute('aria-expanded') === 'true';
    
    if (isExpanded) {
        // Collapse: show excerpt, hide full content
        collapsedContent.style.display = 'block';
        expandedContent.style.display = 'none';
        icon.textContent = '+';
        button.setAttribute('aria-expanded', 'false');
    } else {
        // Expand: hide excerpt, show full content
        collapsedContent.style.display = 'none';
        expandedContent.style.display = 'block';
        icon.textContent = '−';
        button.setAttribute('aria-expanded', 'true');
    }
}
</script> 
