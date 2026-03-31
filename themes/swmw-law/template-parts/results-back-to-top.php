<?php
/**
 * Fixed "Back to top" control for long results archives.
 *
 * @package SWMW_Law
 */

defined( 'ABSPATH' ) || exit;
?>
<button id="results-back-to-top" class="button" style="position:fixed;right:16px;bottom:16px;z-index:1000;display:flex;align-items:center;gap:8px;opacity:0;transform:translateY(12px);transition:opacity 200ms ease, transform 200ms ease;pointer-events:none;">
	<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 31 31" fill="none" style="transform:rotate(90deg);display:block;">
		<path d="M26.75 15.7427L4.25 15.7427M4.25 15.7427L14.875 5.11767M4.25 15.7427L14.875 26.3677" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
	</svg>
	<?php esc_html_e( 'Top', 'swmw-law' ); ?>
</button>
<script>
(function() {
	const btn = document.getElementById('results-back-to-top');
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
	onScroll();
})();
</script>
