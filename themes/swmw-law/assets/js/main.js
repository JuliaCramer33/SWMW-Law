//Import any JS here

import initializeButtonHoverAnimation from './button-hover-animation.js'; // Import the module
import { initMobileMenu, initMobileSubMenus } from './mobile-menu.js';
import { initMegaMenus, moveMegaPanels, equalizeMegaMenuHeights } from './mega-menu.js';
import { initResultsSlider } from './blocks/results.js';
import { initTestimonialsSlider } from './blocks/testimonials.js';
import { initAttorneysSlider } from './blocks/attorneys.js';
import { AccordionBlock, JobsitesAccordion } from './blocks/accordion.js';
import { initHeroDropdownNav } from './hero-dropdown-nav.js';
import { initBlockAnimations } from './blocks/animations.js';
import { initAccordionColumns } from './accordion-columns.js';

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
    const defaultScrollOffset = 100; // Default offset for smooth scroll

    if (!skipLink) {
      return;
    }

    skipLink.addEventListener('click', function (e) {
      e.preventDefault();

      const targetId = this.getAttribute('href').substring(1);
      const targetElement = document.getElementById(targetId);

      if (targetElement) {
        const rect = targetElement.getBoundingClientRect();
        const currentScrollY = window.scrollY;
        let finalScrollPosition = currentScrollY + rect.top; // Initial position

        // Check if header is fixed and adjust scroll position
        const header = document.querySelector('.site-header');
        if (header && window.getComputedStyle(header).position === 'fixed') {
          const headerHeight = header.offsetHeight;
          finalScrollPosition = finalScrollPosition - headerHeight - 20; // 20px extra buffer
        } else {
          // Apply default scroll offset if header is not fixed
          finalScrollPosition = finalScrollPosition - defaultScrollOffset;
        }

        window.scrollTo({
          top: Math.max(0, finalScrollPosition), // Ensure not scrolling to negative position
          behavior: 'smooth'
        });

        // After scrolling, focus the target element for accessibility
        setTimeout(() => {
          targetElement.focus();
          // Add a visual indicator that focus has moved (optional)
          targetElement.classList.add('skip-link-target');
          setTimeout(() => {
            targetElement.classList.remove('skip-link-target');
          }, 2000);
        }, 600); // Match or exceed CSS scroll-behavior transition time
      }

      // Announce to screen readers that we've skipped to content
      const announcement = document.createElement('div');
      announcement.setAttribute('aria-live', 'polite');
      announcement.setAttribute('aria-atomic', 'true');
      announcement.className = 'sr-only';
      announcement.textContent = 'Skipped to content'; // More generic message
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
    // Equalize a baseline min-height so adjacent panels align, while open height still animates
    try {
      equalizeMegaMenuHeights();
    } catch (error) {
      console.error('MAIN.JS - ERROR in equalizeMegaMenuHeights():', error);
    }

    // Recompute on resize for layout changes
    window.addEventListener('resize', () => {
      try {
        equalizeMegaMenuHeights();
      } catch (error) {
        // no-op
      }
    });

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
      initAccordionColumns(); // Initialize the accordion columnizer FIRST
    } catch (error) {
      console.error('MAIN.JS - ERROR in initAccordionColumns():', error);
    }

    try {
      new AccordionBlock();
    } catch (error) {
      console.error('MAIN.JS - ERROR in AccordionBlock():', error);
    }

    try {
      new JobsitesAccordion();
    } catch (error) {
      console.error('MAIN.JS - ERROR in JobsitesAccordion():', error);
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
    if (!loadMoreButton.length) return;

    let currentPage = 1; // next page to load will be 2 on first click

    loadMoreButton.on('click', function () {
      currentPage++;
      const button = $(this);
      button.text('Loading...').prop('disabled', true);

      $.ajax({
        url: swmwLawData.ajaxUrl,
        type: 'POST',
        data: {
          action: 'load_more_attorneys',
          page: currentPage,
          nonce: swmwLawData.load_more_attorneys_nonce,
        },
        success(response) {
          if (response.success) {
            if (response.data.html) {
              // Append only posts that are not already present (defensive against server/page repeats)
              const $grid = $('.attorney-grid');
              const temp = document.createElement('div');
              temp.innerHTML = response.data.html;
              const newItems = Array.from(temp.querySelectorAll('.attorney-card-item'));
              let appended = 0;
              newItems.forEach((el) => {
                const idMatch = el.id && el.id.match(/^post-(\d+)$/);
                const postId = idMatch ? idMatch[1] : null;
                if (postId) {
                  if (document.getElementById(`post-${postId}`)) {
                    return; // already on page, skip
                  }
                }
                $grid.append(el);
                appended += 1;
              });
              if (appended > 0) {
                document.dispatchEvent(new CustomEvent('swmw:contentLoaded'));
                button.text('Load More Attorneys').prop('disabled', false);
              } else {
                // Nothing new appended; likely end of results
                button.text('No More Attorneys').prop('disabled', true);
              }
            } else {
              button.text('No More Attorneys').prop('disabled', true);
            }
            if (currentPage >= response.data.max_pages) {
              button.text('No More Attorneys').prop('disabled', true);
            }
          } else {
            button.text('Error - Try Again').prop('disabled', false);
          }
        },
        error() {
          button.text('AJAX Error - Try Again').prop('disabled', false);
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

