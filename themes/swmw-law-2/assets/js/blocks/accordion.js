/**
 * Accordion Block JS
 */

export class AccordionBlock {
  constructor() {
    this.init();
    if (window.acf) {
      window.acf.addAction('render_block_preview', (el) =>
        this.init(el[0])
      );
    }
  }

  init(context = document) {
    // Update selector to ignore accordions that will be handled by other scripts
    const accordions = context.querySelectorAll(
      '.accordion-block:not(.jobsites-accordion)'
    );
    accordions.forEach((accordion) => this.setupAccordion(accordion));
  }

  setupAccordion(accordion) {
    const panels = accordion.querySelectorAll('.accordion-panel');

    panels.forEach((panel, index) => {
      const header = panel.querySelector('.accordion-panel-header');
      const content = panel.querySelector('.accordion-panel-content');

      if (
        !header ||
        !content ||
        header.classList.contains('js-accordion-initialized')
      ) {
        return;
      }
      header.classList.add('js-accordion-initialized');

      const isOpen = panel.classList.contains('is-open');
      header.setAttribute('aria-expanded', isOpen);
      header.setAttribute('aria-controls', `panel-${accordion.id}-${index}`);
      content.setAttribute('id', `panel-${accordion.id}-${index}`);

      if (isOpen) {
        content.style.maxHeight = content.scrollHeight + 'px';
      }

      header.addEventListener('click', () => {
        const currentlyOpen = panel.classList.contains('is-open');

        // This logic makes it a "one at a time" accordion.
        // If you want multiple open, this loop should be removed.
        panels.forEach((p) => {
          if (p !== panel) {
            const h = p.querySelector('.accordion-panel-header');
            const c = p.querySelector('.accordion-panel-content');
            p.classList.remove('is-open');
            if (h && c) {
              h.setAttribute('aria-expanded', 'false');
              c.style.maxHeight = null;
            }
          }
        });

        if (currentlyOpen) {
          panel.classList.remove('is-open');
          header.setAttribute('aria-expanded', 'false');
          content.style.maxHeight = null;
        } else {
          panel.classList.add('is-open');
          header.setAttribute('aria-expanded', 'true');
          content.style.maxHeight = content.scrollHeight + 'px';
        }
      });
    });

    window.addEventListener('load', () => {
      const openPanels = accordion.querySelectorAll('.accordion-panel.is-open');
      openPanels.forEach((panel) => {
        const content = panel.querySelector('.accordion-panel-content');
        if (content) {
          content.style.maxHeight = content.scrollHeight + 'px';
        }
      });
    });
  }
}

export class JobsitesAccordion {
  constructor() {
    this.initJobsitesFeatures();
  }

  initJobsitesFeatures(context = document) {
    const jobsitesBlocks = context.querySelectorAll('.jobsites-by-city-block');

    jobsitesBlocks.forEach((block) => {
      if (block.classList.contains('js-jobsites-initialized')) return;
      block.classList.add('js-jobsites-initialized');

      const scrollContainer = block.querySelector('.jobsites-directory-scroll');
      const searchInput = block.querySelector('.jobsites-search-input');
      const clearButton = block.querySelector('.jobsites-search-clear');
      const letterButtons = block.querySelectorAll('.filter-letter.has-cities');
      const skipSelect = block.querySelector('.jobsites-skip-to select');
      const noResults = block.querySelector('.jobsites-no-results');
      const letterSections = block.querySelectorAll('.letter-section');
      const cityGroups = block.querySelectorAll('.city-group');

      // Search handler
      if (searchInput) {
        searchInput.addEventListener('input', () => {
          const query = searchInput.value.trim().toLowerCase();

          // Toggle clear button visibility
          if (clearButton) {
            clearButton.hidden = !query;
          }

          // Filter city groups
          cityGroups.forEach((group) => {
            const cityName = (group.dataset.city || '').toLowerCase();
            const match = !query || cityName.includes(query);
            group.classList.toggle('is-hidden', !match);
          });

          // Hide letter sections with zero visible cities
          letterSections.forEach((section) => {
            const visibleCities = section.querySelectorAll('.city-group:not(.is-hidden)');
            section.classList.toggle('is-hidden', visibleCities.length === 0);
          });

          // Show/hide no results message
          if (noResults) {
            const anyVisible = block.querySelector('.letter-section:not(.is-hidden)');
            noResults.hidden = !!anyVisible;
          }
        });
      }

      // Clear button handler
      if (clearButton) {
        clearButton.addEventListener('click', () => {
          if (searchInput) {
            searchInput.value = '';
            searchInput.dispatchEvent(new Event('input'));
            searchInput.focus();
          }
        });
      }

      // Letter button click — scroll within the container
      letterButtons.forEach((button) => {
        button.addEventListener('click', () => {
          const letter = button.dataset.letter;
          const target = scrollContainer
            ? scrollContainer.querySelector(`#letter-${letter}`)
            : null;

          if (target && scrollContainer) {
            const containerTop = scrollContainer.getBoundingClientRect().top;
            const targetTop = target.getBoundingClientRect().top;
            scrollContainer.scrollTo({
              top: scrollContainer.scrollTop + (targetTop - containerTop),
              behavior: 'smooth',
            });
          }

          letterButtons.forEach((btn) => btn.classList.remove('active'));
          button.classList.add('active');
        });
      });

      // Mobile skip-to dropdown
      if (skipSelect && scrollContainer) {
        skipSelect.addEventListener('change', (e) => {
          const letter = e.target.value;
          if (!letter) return;

          const target = scrollContainer.querySelector(`#letter-${letter}`);
          if (target) {
            const containerTop = scrollContainer.getBoundingClientRect().top;
            const targetTop = target.getBoundingClientRect().top;
            scrollContainer.scrollTo({
              top: scrollContainer.scrollTop + (targetTop - containerTop),
              behavior: 'smooth',
            });
          }
        });
      }
    });
  }
}
