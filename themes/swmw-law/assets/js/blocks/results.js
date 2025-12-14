/**
 * Initializes sliders for the Results Block.
 */
export function initResultsSlider() {
  const sliders = document.querySelectorAll('.results-block .splide');

  if (!sliders.length) {
    return; // No sliders found on this page
  }

  sliders.forEach((slider) => {
    const splide = new Splide(slider, {
      type: 'loop',
      perPage: 3,
      gap: '1rem',
      pagination: false,
      arrows: true,
      autoplay: true,
      interval: 4000, // 4s between slides
      pauseOnHover: true,
      pauseOnFocus: true,
      drag: true,
      snap: true,
      focus: 'center',
      trimSpace: true,
      breakpoints: {
        991: { perPage: 2 },
        767: { perPage: 1 },
      },
    });

    splide.on('mounted updated', () => {
      setTimeout(() => {
        const { Arrows } = splide.Components;
        const arrowIcon = `<svg width="25" height="24" viewBox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M24.1304 11.8223L1.63037 11.8223M1.63037 11.8223L12.2554 1.19726M1.63037 11.8223L12.2554 22.4473" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>`;
        if (Arrows.arrows.prev) Arrows.arrows.prev.innerHTML = arrowIcon;
        if (Arrows.arrows.next) Arrows.arrows.next.innerHTML = arrowIcon;
      }, 0);
    });

    splide.mount();
  });
}
