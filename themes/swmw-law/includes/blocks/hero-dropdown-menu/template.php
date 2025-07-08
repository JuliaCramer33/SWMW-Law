<?php
/**
 * Dropdown Menu Block Template
 *
 * @package SWMW_Law
 */

$menu_title = get_field('menu_title') ?: 'Menu';
$menu_items = get_field('menu_items');

$class_name = 'wp-block hero-dropdown-menu-block';
if ( ! empty( $block['className'] ) ) {
    $class_name .= ' ' . $block['className'];
}
?>
<div class="hero-dropdown-menu <?php echo esc_attr( $class_name ); ?>">
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
      <ul>
        <?php if ( $menu_items ) : ?>
          <?php foreach ( $menu_items as $item ) : ?>
            <?php if ( isset( $item['link']['url'] ) && $item['link']['url'] ) : ?>
              <li><a href="<?php echo esc_url( $item['link']['url'] ); ?>"<?php if ( ! empty( $item['link']['target'] ) ) echo ' target="' . esc_attr( $item['link']['target'] ) . '"'; ?>><?php echo esc_html( $item['link']['title'] ); ?></a></li>
            <?php endif; ?>
          <?php endforeach; ?>
        <?php endif; ?>
      </ul>
    </nav>
  </div>
</div> 
