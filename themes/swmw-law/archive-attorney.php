<?php
/**
 * The template for displaying Attorney archives
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package SWMW_Law
 */

get_header();

$description = get_the_archive_description();
?>

<div id="primary" class="content-area">
	<main id="main" class="site-main attorney-archive-main">

		<?php
		get_template_part( 'template-parts/breadcrumbs' );
		get_template_part( 'template-parts/hero-archive' );
		?>

		

		<div class="container-lg attorney-archive-container">
			<?php if ( have_posts() ) : ?>
				<div class="attorney-grid">
					<?php
					while ( have_posts() ) :
						the_post();
						get_template_part( 'template-parts/content', 'attorney-card' );
					endwhile;
					?>
				</div><!-- .attorney-grid -->
				
				<!-- Back to Top button -->
				<button id="attorneys-back-to-top" class="button" style="position:fixed;right:16px;bottom:16px;z-index:1000;display:flex;align-items:center;gap:8px;opacity:0;transform:translateY(12px);transition:opacity 200ms ease, transform 200ms ease;pointer-events:none;">
					<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 31 31" fill="none" style="transform:rotate(90deg);display:block;">
						<path d="M26.75 15.7427L4.25 15.7427M4.25 15.7427L14.875 5.11767M4.25 15.7427L14.875 26.3677" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
					</svg>
					<?php esc_html_e( 'Top', 'swmw-law' ); ?>
				</button>
				<script>
				(function() {
					const btn = document.getElementById('attorneys-back-to-top');
					if (!btn) return;
					const onScroll = () => {
						if (window.scrollY > 600) {
							btn.classList.add('is-visible');
							btn.style.opacity = '1';
							btn.style.transform = 'translateY(0)';
							btn.style.pointerEvents = 'auto';
						} else {
							btn.classList.remove('is-visible');
							btn.style.opacity = '0';
							btn.style.transform = 'translateY(12px)';
							btn.style.pointerEvents = 'none';
						}
					};
					window.addEventListener('scroll', onScroll, { passive: true });
					btn.addEventListener('click', function() {
						window.scrollTo({ top: 0, behavior: 'smooth' });
					});
					// Initial state
					onScroll();
				})();
				</script>
			<?php else : ?>
				<p><?php esc_html_e( 'No attorneys found.', 'swmw-law' ); ?></p>
			<?php endif; ?>



		</div><!-- .container -->

	</main><!-- #main -->
</div><!-- #primary -->

<?php
get_footer(); 
