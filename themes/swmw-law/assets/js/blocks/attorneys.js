/**
 * Initializes sliders for the Attorneys Block.
 */
document.addEventListener('DOMContentLoaded', () => {
  const sliders = document.querySelectorAll('.attorneys-block .splide');

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
});
