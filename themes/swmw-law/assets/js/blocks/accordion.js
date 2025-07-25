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
      const accordion = block.querySelector('.jobsites-accordion');
      if (!accordion || accordion.classList.contains('js-jobsites-initialized')) return;

      accordion.classList.add('js-jobsites-initialized');

      // After the columns are built, re-use the generic setup logic to make the panels clickable.
      // This is more robust than duplicating the click-handling code.
      const genericAccordionManager = new AccordionBlock();
      genericAccordionManager.setupAccordion(accordion);

      const letterButtons = block.querySelectorAll('.filter-letter.has-cities');
      const skipSelect = block.querySelector('.jobsites-skip-to select');
      const allPanels = accordion.querySelectorAll('.accordion-panel');

      letterButtons.forEach((button) => {
        button.addEventListener('click', () => {
          const letter = button.dataset.letter;
          const targetPanel = accordion.querySelector(`.accordion-panel[data-letter="${letter}"]`);
          if (!targetPanel) return;

          targetPanel.scrollIntoView({ behavior: 'smooth', block: 'start' });

          // This logic now correctly handles closing other panels.
          allPanels.forEach((p) => {
            if (p !== targetPanel) {
              p.classList.remove('is-open');
              const h = p.querySelector('.accordion-panel-header');
              const c = p.querySelector('.accordion-panel-content');
              if (h) h.setAttribute('aria-expanded', 'false');
              if (c) c.style.maxHeight = null;
            }
          });

          const header = targetPanel.querySelector('.accordion-panel-header');
          const content = targetPanel.querySelector('.accordion-panel-content');
          if (!targetPanel.classList.contains('is-open')) {
            targetPanel.classList.add('is-open');
            if (header) header.setAttribute('aria-expanded', 'true');
            if (content) content.style.maxHeight = content.scrollHeight + 'px';
          }

          letterButtons.forEach((btn) => btn.classList.remove('active'));
          button.classList.add('active');
        });
      });

      if (skipSelect) {
        skipSelect.addEventListener('change', (e) => {
          const letter = e.target.value;
          const targetPanel = accordion.querySelector(`.accordion-panel[data-letter="${letter}"]`);
          if (!targetPanel) return;

          targetPanel.scrollIntoView({ behavior: 'smooth' });

          allPanels.forEach((p) => {
            if (p !== targetPanel) {
              p.classList.remove('is-open');
              const h = p.querySelector('.accordion-panel-header');
              const c = p.querySelector('.accordion-panel-content');
              if (h) h.setAttribute('aria-expanded', 'false');
              if (c) c.style.maxHeight = null;
            }
          });

          const header = targetPanel.querySelector('.accordion-panel-header');
          const content = targetPanel.querySelector('.accordion-panel-content');
          if (!targetPanel.classList.contains('is-open')) {
            targetPanel.classList.add('is-open');
            if (header) header.setAttribute('aria-expanded', 'true');
            if (content) content.style.maxHeight = content.scrollHeight + 'px';
          }
        });
      }
    });
  }
}
