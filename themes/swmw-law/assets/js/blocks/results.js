const arrowIconSvg =
  '<svg width="25" height="24" viewBox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M24.1304 11.8223L1.63037 11.8223M1.63037 11.8223L12.2554 1.19726M1.63037 11.8223L12.2554 22.4473" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>';

function mountResultsSplide(slider, options) {
  const splide = new Splide(slider, options);

  splide.on('mounted updated', () => {
    setTimeout(() => {
      const { Arrows } = splide.Components;
      if (Arrows.arrows.prev) Arrows.arrows.prev.innerHTML = arrowIconSvg;
      if (Arrows.arrows.next) Arrows.arrows.next.innerHTML = arrowIconSvg;
    }, 0);
  });

  splide.mount();
}

/**
 * Initializes sliders for the Results Display block.
 */
export function initResultsSlider() {
  const sliders = document.querySelectorAll('.results-block .splide');
  if (!sliders.length) return;

  sliders.forEach((slider) => {
    mountResultsSplide(slider, {
      type: 'loop',
      perPage: 3,
      perMove: 1,
      gap: '0',
      pagination: false,
      arrows: true,
      start: 0,
      autoplay: true,
      interval: 4000,
      pauseOnHover: true,
      pauseOnFocus: true,
      drag: true,
      snap: true,
      focus: 0,
      trimSpace: true,
      breakpoints: {
        991: { perPage: 2 },
        767: { perPage: 1 },
      },
    });
  });
}

/**
 * Results hero carousel (Results landing) — same Splide behavior, separate block class.
 */
export function initResultsHeroCarouselSlider() {
  const sliders = document.querySelectorAll(
    '.results-hero-carousel-block .splide'
  );
  if (!sliders.length) return;

  sliders.forEach((slider) => {
    mountResultsSplide(slider, {
      type: 'loop',
      perPage: 3,
      perMove: 1,
      gap: '0',
      pagination: false,
      arrows: true,
      start: 0,
      autoplay: false,
      drag: true,
      snap: true,
      focus: 0,
      trimSpace: true,
      breakpoints: {
        991: { perPage: 2 },
        767: { perPage: 1 },
      },
    });
  });
}
