/**
 * Tabs Block Script
 *
 * This script handles the tab functionality for the ACF Tabs block.
 * It works on both the front-end and in the block editor.
 */
(function () {
  /**
   * Initializes a single tabs block.
   * @param {HTMLElement} tabsBlock The tabs block element.
   */
  const initializeTabsBlock = (tabsBlock) => {
    // Prevent re-initialization
    if (tabsBlock.dataset.tabsInitialized) {
      return;
    }

    const tabsNav = tabsBlock.querySelector('.tabs-nav');
    const tabContent = tabsBlock.querySelector('.tab-content');

    if (!tabsNav || !tabContent) {
      return; // Exit if essential elements are missing
    }

    // Find panels, accounting for editor's extra wrappers
    const tabPanels = Array.from(tabContent.querySelectorAll('.wp-block-acf-tab-panel'));

    // Build the nav on the client-side
    tabsNav.innerHTML = ''; // Clear any server-side rendered nav items
    tabPanels.forEach((panel, index) => {
      const title = panel.dataset.tabTitle || `Tab ${index + 1}`;
      const navItem = document.createElement('div');
      navItem.classList.add('tab-nav-item');
      navItem.textContent = title;
      tabsNav.appendChild(navItem);
    });

    const tabNavItems = tabsNav.querySelectorAll('.tab-nav-item');

    const switchTab = (activeIndex) => {
      tabNavItems.forEach((item, index) => {
        item.classList.toggle('active', index === activeIndex);
      });

      tabPanels.forEach((panel, index) => {
        panel.classList.toggle('active', index === activeIndex);
      });
    };

    tabNavItems.forEach((item, index) => {
      item.addEventListener('click', () => {
        switchTab(index);
      });
    });

    // Set the first tab as active by default
    if (tabNavItems.length > 0) {
      switchTab(0);
    }

    // Mark as initialized
    tabsBlock.dataset.tabsInitialized = 'true';
  };

  /**
   * Handles initialization for both front-end and editor.
   */
  const onReady = () => {
    // Initialize all tabs blocks on the page
    const allTabsBlocks = document.querySelectorAll('.wp-block-acf-tabs:not([data-tabs-initialized])');
    allTabsBlocks.forEach(initializeTabsBlock);
  };

  // -----[ EDITOR-SPECIFIC LOGIC ]-----
  // In the editor, blocks can be added dynamically. We need to watch for them.
  if (window.wp && window.wp.data && typeof window.wp.data.subscribe === 'function') {
    const observer = new MutationObserver((mutationsList) => {
      for (const mutation of mutationsList) {
        if (mutation.type === 'childList' && mutation.addedNodes.length > 0) {
          mutation.addedNodes.forEach((node) => {
            if (node.nodeType === 1) { // Check if it's an element
              if (node.matches('.wp-block-acf-tabs')) {
                initializeTabsBlock(node);
              }
              // Also check for tabs blocks within the added node
              node.querySelectorAll('.wp-block-acf-tabs').forEach(initializeTabsBlock);
            }
          });
        }
      }
    });

    // We need a slight delay to ensure the editor's main content area is available.
    setTimeout(() => {
      const editorCanvas = document.querySelector('.block-editor-writing-flow');
      if (editorCanvas) {
        observer.observe(editorCanvas, { childList: true, subtree: true });
        // Also run once on load for already-present blocks
        onReady();
      }
    }, 500);
  }

  // -----[ FRONT-END LOGIC ]-----
  // On the front end, DOMContentLoaded is the most efficient way to run.
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', onReady);
  } else {
    // Handle cases where the script is loaded after the document is already complete
    onReady();
  }
})(); 
