<?php
/**
 * Focused footer for Landing Page template.
 * Keeps trust (accreditations) and legal (disclaimer/copyright).
 *
 * @package SWMW_Law
 */
?>

<?php
// Conditionally display the accreditations bar before the footer (same logic as primary footer).
$display_bar = false;
$logos       = function_exists('get_field') ? get_field( 'accreditations_logos', 'option' ) : [];

if ( ! empty( $logos ) ) {
    if ( is_singular( 'post' ) ) { // Single blog posts default to ON unless explicitly disabled.
        $show_setting = get_field( 'show_accreditations_bar' );
        $display_bar  = ( false !== $show_setting );
    } elseif ( is_home() ) { // Blog posts index.
        $display_bar = get_field( 'show_on_posts_page', 'option' );
    } elseif ( is_post_type_archive( 'attorney' ) ) { // Attorney archive.
        $display_bar = get_field( 'show_on_attorney_archive', 'option' );
    } elseif ( is_post_type_archive( 'swmw_result' ) ) { // Results archive.
        $display_bar = get_field( 'show_on_results_archive', 'option' );
    } elseif ( is_singular() || is_front_page() ) { // Any single post/page or static front page.
        $show_setting = get_field( 'show_accreditations_bar' );
        // 'allow_null' is on, so null means default behavior. Default is ON.
        if ( null === $show_setting || true === $show_setting ) {
            $display_bar = true;
        }
    }
}

if ( $display_bar ) {
    get_template_part( 'template-parts/accreditations-bar' );
}
?>

<footer id="colophon" class="site-footer site-footer--landing" role="contentinfo">
  <div class="container-lg">
    <div class="footer-main">
      <div class="footer-column footer-brand">
        <?php
        if ( function_exists( 'the_custom_logo' ) && has_custom_logo() ) {
            the_custom_logo();
        } elseif ( get_bloginfo( 'name' ) ) {
            echo '<h1 class="site-title"><a href="' . esc_url( home_url( '/' ) ) . '" rel="home">' . esc_html( get_bloginfo( 'name' ) ) . '</a></h1>';
        }
        ?>
        <div class="footer-tagline">
          <?php
          if ( function_exists('get_field') ) {
            $footer_tagline_content = get_field( 'footer_display_tagline', 'option' );
            if ( $footer_tagline_content ) {
              echo wp_kses_post( $footer_tagline_content );
            }
          }
          ?>
        </div>
      </div>
    </div>

    <div class="footer-bottom">
      <div class="footer-bottom-inner">
        <div class="footer-copyright">
          <p class="footer-disclaimer">
            <?php
            $disclaimer_text = get_theme_mod(
              'swmw_law_footer_disclaimer_text',
              __( 'The choice of a lawyer is an important decision and should not be based solely upon advertisements. Results obtained depend upon the facts of each case. Past results afford no guarantee of future results or similar outcomes. Every case is different and must be judged on its own merits. The information on this website is for general information purposes only. Nothing on this site should be taken as legal advice for any individual case or situation. This information is not intended to create, and receipt or viewing does not constitute, an attorney-client relationship.', 'swmw-law' )
            );
            echo wp_kses_post( $disclaimer_text );
            ?>
          </p>
          <p>
            <?php
            $copyright_text = get_theme_mod(
              'swmw_law_footer_copyright_text',
              sprintf(
                /* translators: 1: Site Map link, 2: Privacy Policy link */
                __( '&copy; [current_year] All Rights Reserved. %1$s | %2$s', 'swmw-law' ),
                '<a href="' . esc_url( home_url( '/site-map/' ) ) . '">' . __( 'Site Map', 'swmw-law' ) . '</a>',
                '<a href="' . esc_url( home_url( '/privacy-policy/' ) ) . '">' . __( 'Privacy Policy', 'swmw-law' ) . '</a>'
              )
            );
            $copyright_text = str_replace( '[current_year]', date_i18n( 'Y' ), $copyright_text );
            echo wp_kses_post( $copyright_text );
            ?>
          </p>
        </div>
      </div>
    </div>
  </div>
</footer>

</div><!-- .site -->

<?php wp_footer(); ?>
</body>
</html>

