<?php
/**
 * Dropdown Menu Block Template
 *
 * @package SWMW_Law
 */

$menu_title = get_field('menu_title') ?: 'Page Navigation';
$include_h3s = get_field('include_h3s') ?: true;
$scroll_offset = get_field('scroll_offset') ?: 100;
$manual_items = get_field('manual_items');

$class_name = 'wp-block hero-dropdown-menu-block';
if ( ! empty( $block['className'] ) ) {
    $class_name .= ' ' . $block['className'];
}

// Data attributes for JavaScript
$data_attributes = sprintf(
    'data-include-h3s="%s" data-scroll-offset="%d"',
    $include_h3s ? 'true' : 'false',
    $scroll_offset
);
?>
<div class="hero-dropdown-menu <?php echo esc_attr( $class_name ); ?>" <?php echo esc_attr( $data_attributes ); ?>>
  <div class="hero-dropdown-container">
    <button class="hamburger-menu-toggle" aria-haspopup="true" aria-expanded="false" aria-controls="hero-nav-list">
      <span class="hamburger-icon" aria-hidden="true">
        <span></span>
        <span></span>
        <span></span>
      </span>
      <span class="hamburger-label"><?php echo esc_html( $menu_title ); ?></span>
    </button>
    <nav class="hero-nav-list" id="hero-nav-list" aria-label="<?php echo esc_attr( $menu_title ); ?>">
      <div class="hero-nav-header">
        <h3 class="hero-nav-title"><?php echo esc_html( $menu_title ); ?></h3>
      </div>
      <ul class="hero-nav-items">
        <?php if ( $manual_items && ! empty( $manual_items ) ) : ?>
          <!-- Manual navigation items -->
          <?php foreach ( $manual_items as $item ) : ?>
            <?php if ( isset( $item['link']['url'] ) && $item['link']['url'] ) : ?>
              <li class="<?php echo ! empty( $item['is_submenu'] ) ? 'hero-nav-submenu-item' : ''; ?>">
                <a href="<?php echo esc_url( $item['link']['url'] ); ?>"<?php if ( ! empty( $item['link']['target'] ) ) echo ' target="' . esc_attr( $item['link']['target'] ) . '"'; ?>>
                  <?php echo esc_html( $item['title'] ?: $item['link']['title'] ); ?>
                </a>
              </li>
            <?php endif; ?>
          <?php endforeach; ?>
        <?php else : ?>
          <!-- Auto-generated navigation will be populated by JavaScript -->
          <li class="hero-nav-loading">
            <span>Loading navigation...</span>
          </li>
        <?php endif; ?>
      </ul>
    </nav>
  </div>
</div> 
