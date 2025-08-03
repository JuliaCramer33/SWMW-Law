document.addEventListener('DOMContentLoaded', function () {
  const equalizeHeights = (selector, parent = document) => {
    const elements = parent.querySelectorAll(selector);
    if (elements.length === 0) return;

    let maxHeight = 0;
    elements.forEach(el => {
      el.style.height = 'auto'; // Reset height to calculate natural height
    });

    elements.forEach(el => {
      if (el.offsetHeight > maxHeight) {
        maxHeight = el.offsetHeight;
      }
    });

    elements.forEach(el => {
      el.style.height = `${maxHeight}px`;
    });
  };

  const initExpandableCards = () => {
    const cards = document.querySelectorAll('.expandable-card');

    // Group cards by their parent wp-block-columns container
    const cardGroups = new Map();
    cards.forEach(card => {
      const group = card.closest('.wp-block-columns');
      if (group) {
        if (!cardGroups.has(group)) {
          cardGroups.set(group, []);
        }
        cardGroups.get(group).push(card);
      } else {
        // Handle cards not in a columns block
        if (!cardGroups.has(null)) {
          cardGroups.set(null, []);
        }
        cardGroups.get(null).push(card);
      }
    });

    // Equalize heights within each group
    cardGroups.forEach(groupCards => {
      const parentElement = groupCards[0].closest('.wp-block-columns') || document;
      equalizeHeights('.expandable-card .title', parentElement);
      equalizeHeights('.expandable-card .collapsed-content', parentElement);
    });

    // Set up click handlers
    cards.forEach(card => {
      const button = card.querySelector('.expand-button');
      if (button && !button.hasAttribute('data-click-handler')) {
        button.setAttribute('data-click-handler', 'true');
        button.addEventListener('click', () => {
          const isExpanded = card.classList.toggle('is-expanded');
          button.setAttribute('aria-expanded', isExpanded);
          const icon = button.querySelector('.button-icon');
          if (icon) {
            icon.textContent = isExpanded ? '−' : '+';
          }
        });
      }
    });
  };

  initExpandableCards();

  // Re-run on resize, but debounce to avoid performance issues
  let resizeTimer;
  window.addEventListener('resize', () => {
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(initExpandableCards, 150);
  });
});
