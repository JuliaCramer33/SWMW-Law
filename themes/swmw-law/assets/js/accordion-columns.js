/**
 * Accordion Columnizer
 *
 * This script takes a flat list of accordion panels and distributes them
 * into a specified number of columns, creating a true multi-column layout.
 */

function initAccordionColumns() {
  const accordionContainers = document.querySelectorAll('.js-accordion-columns');

  if (!accordionContainers.length) {
    return;
  }

  const handleResize = () => {
    accordionContainers.forEach((container) => {
      // Get all the original panels
      const panels = Array.from(
        container.querySelectorAll(':scope > .accordion-panel')
      );
      if (!panels.length) {
        return;
      }

      // Determine the number of columns based on window width
      let numColumns = 3;
      if (window.innerWidth <= 1024) {
        numColumns = 2;
      }
      if (window.innerWidth <= 768) {
        numColumns = 1;
      }

      // Clear the container
      container.innerHTML = '';

      // Create and append new column wrappers
      const columns = [];
      for (let i = 0; i < numColumns; i++) {
        const column = document.createElement('div');
        column.className = 'accordion-column';
        container.appendChild(column);
        columns.push(column);
      }

      // Distribute the original panels into the new columns
      panels.forEach((panel, index) => {
        const columnIndex = index % numColumns;
        columns[columnIndex].appendChild(panel);
      });
    });
  };

  // Run on initial load
  handleResize();

  // And run on window resize, with a debounce to prevent excessive firing
  let resizeTimer;
  window.addEventListener('resize', () => {
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(handleResize, 250);
  });
}

// Ensure the function is exported for use in main.js
export { initAccordionColumns }; 
