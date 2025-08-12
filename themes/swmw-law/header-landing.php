<?php
/**
 * Landing header based on main header, stripped of menus.
 * Shows logo, "Free Consultations 24/7" and site phone number.
 *
 * @package SWMW_Law
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
  <head>
    <meta charset="<?php bloginfo( 'charset' ); ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <?php wp_head(); ?>
  </head>
  <body <?php body_class('landing'); ?>>
    <?php wp_body_open(); ?>

    <a href="#main" class="skip-link sr-only"><?php esc_html_e( 'Skip to main content', 'swmw-law' ); ?></a>

    <div class="site">
      <header class="site-header" role="banner">
        <div class="inner container-lg">
          <div class="site-branding">
            <?php if ( has_custom_logo() ) : ?>
              <div class="site-logo">
                <?php the_custom_logo(); ?>
              </div>
            <?php endif; ?>
          </div>

          <div class="landing-header-meta">
            <div class="landing-header-title"><?php echo esc_html__( 'Free Consultations 24/7', 'swmw-law' ); ?></div>
            <?php
            $phone_number = get_theme_mod( 'swmw_law_phone' );
            if ( ! empty( $phone_number ) ) :
            ?>
              <div class="header-phone">
                <a class="button button-text" href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $phone_number ) ); ?>">
                  <svg class="phone-icon" viewBox="0 0 24 24" fill="currentColor" width="16" height="16" aria-hidden="true">
                    <path d="M0 0h24v24H0V0z" fill="none"/>
                    <path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/>
                  </svg>
                  <span><?php echo esc_html( $phone_number ); ?></span>
                </a>
              </div>
            <?php endif; ?>
          </div>
        </div>
      </header>
    </div>

