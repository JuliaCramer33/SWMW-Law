/**
 * Initializes sliders for the Attorneys Block.
 */
export function initAttorneysSlider() {
  const sliders = document.querySelectorAll('.attorneys-block .splide');

  if (sliders.length === 0) {
    return; // No sliders found on this page
  }

  sliders.forEach((slider) => {
    new Splide(slider, {
      type: 'loop',
      perPage: 4,
      perMove: 1,
      gap: '1rem',
      padding: '1rem',
      arrows: true,
      pagination: false,
      autoHeight: true,
      breakpoints: {
        991: { perPage: 2 },
        767: { perPage: 1 },
      },
    }).mount();
  });
}
