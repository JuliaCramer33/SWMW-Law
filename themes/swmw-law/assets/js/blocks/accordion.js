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

      // Show only one letter section at a time (like live)
      function setVisibleLetter(letter) {
        letterSections.forEach((section) => {
          const isVisible = section.dataset.letter === letter;
          section.classList.toggle('is-visible', isVisible);
        });
        letterButtons.forEach((btn) => {
          btn.classList.toggle('active', btn.dataset.letter === letter);
        });
        if (skipSelect) {
          skipSelect.value = letter || '';
        }
        updateNoResultsVisibility();
      }

      function updateNoResultsVisibility() {
        if (!noResults) return;
        const isSearching = block.classList.contains('is-searching');
        const sectionsWithCities = block.querySelectorAll('.letter-section:not(.is-hidden)');
        const totalVisible = sectionsWithCities.length;
        const totalCities = block.querySelectorAll('.city-group:not(.is-hidden)').length;
        noResults.hidden = totalCities > 0;
      }

      // Default: show all letters (user can click a letter to filter to one)
      block.classList.add('show-all');

      function showAllLetters() {
        block.classList.add('show-all');
        letterSections.forEach((section) => section.classList.remove('is-visible'));
        letterButtons.forEach((btn) => btn.classList.remove('active'));
        if (skipSelect) skipSelect.value = '';
        updateNoResultsVisibility();
      }

      // Search handler: when typing, show all letter sections that have matches; when empty, restore prior state
      if (searchInput) {
        searchInput.addEventListener('input', () => {
          const query = searchInput.value.trim().toLowerCase();
          const isSearching = query.length > 0;

          block.classList.toggle('is-searching', isSearching);

          if (clearButton) {
            clearButton.hidden = !query;
          }

          cityGroups.forEach((group) => {
            if (!query) {
              group.classList.remove('is-hidden');
              return;
            }
            const cityName = (group.dataset.city || '').toLowerCase();
            const jobsiteNames = Array.from(group.querySelectorAll('.jobsite-name'))
              .map((el) => (el.textContent || '').toLowerCase())
              .join(' ');
            const match = cityName.includes(query) || jobsiteNames.includes(query);
            group.classList.toggle('is-hidden', !match);
          });

          letterSections.forEach((section) => {
            const visibleCities = section.querySelectorAll('.city-group:not(.is-hidden)');
            section.classList.toggle('is-hidden', visibleCities.length === 0);
          });

          if (!isSearching && !block.classList.contains('show-all')) {
            const activeLetter = block.querySelector('.filter-letter.active')?.dataset.letter;
            if (activeLetter) setVisibleLetter(activeLetter);
          }
          updateNoResultsVisibility();
        });
      }

      if (clearButton) {
        clearButton.addEventListener('click', () => {
          if (searchInput) {
            searchInput.value = '';
            searchInput.dispatchEvent(new Event('input'));
            searchInput.focus();
          }
        });
      }

      // Letter button click — filter to only this letter's section
      letterButtons.forEach((button) => {
        button.addEventListener('click', () => {
          block.classList.remove('show-all');
          setVisibleLetter(button.dataset.letter);
        });
      });

      // Reset "×" button — show all letters again
      const resetBtn = block.querySelector('.jobsites-reset-letter');
      if (resetBtn) {
        resetBtn.addEventListener('click', showAllLetters);
      }

      if (skipSelect) {
        skipSelect.addEventListener('change', (e) => {
          const letter = e.target.value;
          if (!letter) {
            showAllLetters();
            return;
          }
          block.classList.remove('show-all');
          setVisibleLetter(letter);
        });
      }
    });
  }
}
