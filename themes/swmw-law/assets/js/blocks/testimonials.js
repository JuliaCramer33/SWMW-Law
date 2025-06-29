/**
 * Initializes sliders for the Testimonials Block.
 */
export function initTestimonialsSlider() {
	const sliders = document.querySelectorAll('.testimonials-block .splide');

	if (!sliders.length) {
		return;
	}

	sliders.forEach((slider) => {
		const splide = new Splide(slider, {
			type: 'loop',
			perPage: 1,
			pagination: false,
			arrows: false,
			drag: true,
			snap: true,
			focus: 'center',
			trimSpace: true,
		});

		const frame = slider.closest('.testimonial-frame');
		if (frame) {
			const prevArrow = frame.querySelector('.splide__arrow--prev');
			const nextArrow = frame.querySelector('.splide__arrow--next');
			const arrowIcon = `<svg width="25" height="24" viewBox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M24.1304 11.8223L1.63037 11.8223M1.63037 11.8223L12.2554 1.19726M1.63037 11.8223L12.2554 22.4473" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>`;

			if (prevArrow && nextArrow) {
				prevArrow.innerHTML = arrowIcon;
				nextArrow.innerHTML = arrowIcon;
				prevArrow.addEventListener('click', () => splide.go('<'));
				nextArrow.addEventListener('click', () => splide.go('>'));
			}
		}

		splide.mount();
	});
}
