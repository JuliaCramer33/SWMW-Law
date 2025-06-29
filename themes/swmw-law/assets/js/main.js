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
// Import other modules like initModals if you create them

/**
 * SWMW Law Theme
 * Main JavaScript file
 */

(function ($) {
  'use strict';
  // console.log('MAIN.JS JQUERY WRAPPER EXECUTING');

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
    // console.log('MAIN.JS DOCUMENT READY');
    initMobileMenu();
    initMobileSubMenus(); // Call the new mobile submenu initializer
    console.log('MAIN.JS - ABOUT TO CALL initMegaMenus()');
    initMegaMenus();
    console.log('MAIN.JS - FINISHED CALLING initMegaMenus()');
    console.log('MAIN.JS - ABOUT TO CALL moveMegaPanels()');
    moveMegaPanels();
    console.log('MAIN.JS - ABOUT TO CALL equalizeMegaMenuHeights()');
    equalizeMegaMenuHeights();
    initModals(); // Assuming this is still initialized here
    initializeButtonHoverAnimation();
    initResultsSlider(); // Initialize the results slider
    initTestimonialsSlider(); // Initialize the testimonials slider
    initAttorneysSlider(); // Initialize the attorneys slider
    initLoadMoreAttorneys(); // Initialize the load more attorneys functionality
    new AccordionBlock();
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
})(jQuery);
