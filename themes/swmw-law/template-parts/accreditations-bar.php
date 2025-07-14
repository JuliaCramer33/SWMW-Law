<?php
/**
 * Template Part: Accreditations Bar
 *
 * @package SWMW_Law
 */

namespace SWMW_Law;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Get the content from the theme options page.
$accreditations_title = get_field( 'accreditations_title', 'option' );
$accreditations_logos = get_field( 'accreditations_logos', 'option' );

// Don't render the section if there are no logos.
if ( empty( $accreditations_logos ) ) {
	return;
}

// Default overlap ON unless explicitly set to false
$bar_classes = [ 'accreditations-bar' ];
$overlap = get_field( 'accreditations_bar_overlap' );
if ( $overlap !== '0' && $overlap !== 0 && $overlap !== false ) {
    $bar_classes[] = 'accreditations-bar--overlap';
}
?>
<div class="accreditations-bar__container">
	<div class="container-lg">
		<section class="<?php echo esc_attr( implode( ' ', $bar_classes ) ); ?>">
			<div class="accreditations-bar__content">
				<div class="accreditations-bar__title-area">
					<h2 class="accreditations-bar__title"><?php echo wp_kses_post( $accreditations_title ); ?></h2>
				</div>
				<div class="accreditations-bar__logos-area">
					<ul class="accreditations-bar__logos">
						<?php foreach ( $accreditations_logos as $logo ) : ?>
							<li class="accreditations-bar__logo-item">
								<img src="<?php echo esc_url( $logo['url'] ); ?>" alt="<?php echo esc_attr( $logo['alt'] ); ?>" />
							</li>
						<?php endforeach; ?>
					</ul>
				</div>
			</div>
		</section>
	</div>
</div> 
