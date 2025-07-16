//Import any JS here

import initializeButtonHoverAnimation from './button-hover-animation.js'; // Import the module
import { initMobileMenu, initMobileSubMenus } from './mobile-menu.js';
import {
  initMegaMenus,
  moveMegaPanels,
  equalizeMegaMenuHeights,
} from './mega-menu.js';
import { initResultsSlider } from './blocks/results.js';
import { initTestimonialsSlider } from './blocks/testimonials.js';
import { initAttorneysSlider } from './blocks/attorneys.js';
import { AccordionBlock } from './blocks/accordion.js';
import { initHeroDropdownNav } from './hero-dropdown-nav.js';
import { initBlockAnimations } from './blocks/animations.js';

// Import other modules like initModals if you create them

/**
 * SWMW Law Theme
 * Main JavaScript file
 */

(function ($) {
  'use strict';

  // Add 'js' class to body immediately to prevent layout shift
  // This ensures animations only apply when JavaScript is available
  document.documentElement.classList.add('js');


  /**
   * Initialize skip link functionality
   */
  function initSkipLink() {
    const skipLink = document.querySelector('.skip-link');
    const mainContent = document.getElementById('main');

    if (!skipLink || !mainContent) {
      return;
    }

    skipLink.addEventListener('click', function (e) {
      e.preventDefault();

      // Focus the main content area
      mainContent.focus();

      // Add a visual indicator that focus has moved (optional)
      mainContent.classList.add('skip-link-target');
      setTimeout(() => {
        mainContent.classList.remove('skip-link-target');
      }, 2000);

      // Announce to screen readers that we've skipped to main content
      const announcement = document.createElement('div');
      announcement.setAttribute('aria-live', 'polite');
      announcement.setAttribute('aria-atomic', 'true');
      announcement.className = 'sr-only';
      announcement.textContent = 'Skipped to main content';
      document.body.appendChild(announcement);

      setTimeout(() => {
        document.body.removeChild(announcement);
      }, 1000);
    });
  }

  /**
   * Initialize modal functionality - kept here for now, can be modularized later if desired
   */
  function initModals() {
    // Modal open
    $('.js-modal-open').on('click', function (e) {
      e.preventDefault();
      const target = $(this).data('modal-target');
      $(target).addClass('is-active');
    });

    // Modal close
    $('.js-modal-close').on('click', function (e) {
      e.preventDefault();
      $(this).closest('.js-modal').removeClass('is-active');
    });
  }

  // Document ready
  $(document).ready(function () {

    // Initialize skip link functionality
    try {
      initSkipLink();
    } catch (error) {
      console.error('MAIN.JS - ERROR in initSkipLink():', error);
    }

    initMobileMenu();
    initMobileSubMenus(); // Call the new mobile submenu initializer
    initMegaMenus();
    moveMegaPanels();
    try {
      equalizeMegaMenuHeights();
    } catch (error) {
      console.error('MAIN.JS - ERROR in equalizeMegaMenuHeights():', error);
    }

    try {
      initModals(); // Assuming this is still initialized here
    } catch (error) {
      console.error('MAIN.JS - ERROR in initModals():', error);
    }

    try {
      initializeButtonHoverAnimation();
    } catch (error) {
      console.error('MAIN.JS - ERROR in initializeButtonHoverAnimation():', error);
    }

    try {
      initResultsSlider(); // Initialize the results slider
    } catch (error) {
      console.error('MAIN.JS - ERROR in initResultsSlider():', error);
    }

    try {
      initTestimonialsSlider(); // Initialize the testimonials slider
    } catch (error) {
      console.error('MAIN.JS - ERROR in initTestimonialsSlider():', error);
    }

    try {
      initAttorneysSlider(); // Initialize the attorneys slider
    } catch (error) {
      console.error('MAIN.JS - ERROR in initAttorneysSlider():', error);
    }

    try {
      initLoadMoreAttorneys(); // Initialize the load more attorneys functionality
    } catch (error) {
      console.error('MAIN.JS - ERROR in initLoadMoreAttorneys():', error);
    }

    // Initialize Load More Results functionality
    try {
      initLoadMoreResults();
    } catch (error) {
      console.error('MAIN.JS - ERROR in initLoadMoreResults():', error);
    }

    try {
      new AccordionBlock();
    } catch (error) {
      console.error('MAIN.JS - ERROR in AccordionBlock():', error);
    }

    try {
      initHeroDropdownNav(); // Initialize the hero dropdown navigation
    } catch (error) {
      console.error('MAIN.JS - ERROR in initHeroDropdownNav():', error);
    }

    try {
      initBlockAnimations(); // Initialize block animations
    } catch (error) {
      console.error('MAIN.JS - ERROR in initBlockAnimations():', error);
    }

    // Add global toggle for animations (for debugging)
    window.swmwToggleAnimations = function () {
      const body = document.body;
      if (body.classList.contains('no-animations')) {
        body.classList.remove('no-animations');
      } else {
        body.classList.add('no-animations');
      }
    };
  });

  // Window load - can also be modularized if needed
  $(window).on('load', function () {
    // console.log('MAIN.JS WINDOW LOADED');
    // Initialize components that need window load
  });

  /**
   * Initialize Load More Attorneys functionality.
   */
  function initLoadMoreAttorneys() {
    const loadMoreButton = $('#load-more-attorneys');
    if (!loadMoreButton.length) {
      return; // Button not found on this page
    }

    let currentPage = 1; // The initial page is already loaded, so next page to load is 2

    loadMoreButton.on('click', function () {
      currentPage++; // Increment to load the next page
      const button = $(this);
      button.text('Loading...').prop('disabled', true);

      $.ajax({
        url: swmwLawData.ajaxUrl,
        type: 'POST',
        data: {
          action: 'load_more_attorneys',
          page: currentPage,
          nonce: swmwLawData.load_more_attorneys_nonce, // Get nonce from localized data
        },
        success(response) {
          if (response.success) {
            if (response.data.html) {
              $('.attorney-grid').append(response.data.html);
              button
                .text('Load More Attorneys')
                .prop('disabled', false);
            } else {
              button
                .text('No More Attorneys')
                .prop('disabled', true);
            }
            // Check if we've reached the max number of pages
            if (currentPage >= response.data.max_pages) {
              button
                .text('No More Attorneys')
                .prop('disabled', true);
            }
          } else {
            console.error(
              'Error loading attorneys:',
              response.data.message
            );
            button
              .text('Error - Try Again')
              .prop('disabled', false);
          }
        },
        error(jqXHR, textStatus, errorThrown) {
          console.error('AJAX error:', textStatus, errorThrown);
          button
            .text('AJAX Error - Try Again')
            .prop('disabled', false);
        },
      });
    });
  }

  /**
   * Initialize Load More Results functionality.
   */
  function initLoadMoreResults() {
    const loadMoreButton = $('#load-more-results');
    if (!loadMoreButton.length) {
      return; // Button not found on this page
    }

    let currentPage = 1; // The initial page is already loaded, so next page to load is 2

    loadMoreButton.on('click', function () {
      currentPage++; // Increment to load the next page
      const button = $(this);
      button.text('Loading...').prop('disabled', true);

      $.ajax({
        url: swmwLawData.ajaxUrl,
        type: 'POST',
        data: {
          action: 'load_more_results',
          page: currentPage,
          nonce: swmwLawData.load_more_results_nonce, // Get nonce from localized data
        },
        success(response) {
          if (response.success) {
            if (response.data.html) {
              $('.swmw-results-grid').append(response.data.html);
              button
                .text('Load More Results')
                .prop('disabled', false);
            } else {
              button
                .text('No More Results')
                .prop('disabled', true);
            }
            // Check if we've reached the max number of pages
            if (currentPage >= response.data.max_pages) {
              button
                .text('No More Results')
                .prop('disabled', true);
            }
          } else {
            console.error(
              'Error loading results:',
              response.data.message
            );
            button
              .text('Error - Try Again')
              .prop('disabled', false);
          }
        },
        error(jqXHR, textStatus, errorThrown) {
          console.error('AJAX error:', textStatus, errorThrown);
          button
            .text('AJAX Error - Try Again')
            .prop('disabled', false);
        },
      });
    });
  }
})(jQuery);
