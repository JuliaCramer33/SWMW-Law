// export class JobsitesMasonry {
//   constructor() {
//     this.init();
//   }

//   init(context = document) {
//     const jobsitesBlocks = context.querySelectorAll('.jobsites-by-city-block');

//     jobsitesBlocks.forEach((block) => {
//       const masonryContainer = block.querySelector('.accordion-block');

//       if (!masonryContainer || masonryContainer.classList.contains('js-masonry-initialized')) return;
//       masonryContainer.classList.add('js-masonry-initialized');

//       this.setupMasonry(block, masonryContainer);
//     });
//   }

//   setupMasonry(block, container) {
//     // Force a reflow after a panel is opened/closed to prevent layout jumps
//     const observer = new MutationObserver(() => {
//       this.refreshLayout(container);
//     });

//     observer.observe(container, {
//       subtree: true,
//       attributes: true,
//       attributeFilter: ['class', 'style']
//     });

//     // Initial layout
//     this.refreshLayout(container);
//   }

//   refreshLayout(container) {
//     // Masonry logic with column-count is handled in CSS
//     // But we can optionally add a "visual update" class or force reflow
//     container.style.display = 'none';
//     container.offsetHeight; // trigger reflow
//     container.style.display = '';
//   }
// }
