/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./assets/js/accordion-columns.js":
/*!****************************************!*\
  !*** ./assets/js/accordion-columns.js ***!
  \****************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   initAccordionColumns: () => (/* binding */ initAccordionColumns)
/* harmony export */ });
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
    accordionContainers.forEach(container => {
      // Get all the original panels
      const panels = Array.from(container.querySelectorAll(':scope > .accordion-panel'));
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


/***/ }),

/***/ "./assets/js/blocks/accordion.js":
/*!***************************************!*\
  !*** ./assets/js/blocks/accordion.js ***!
  \***************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   AccordionBlock: () => (/* binding */ AccordionBlock),
/* harmony export */   JobsitesAccordion: () => (/* binding */ JobsitesAccordion)
/* harmony export */ });
/**
 * Accordion Block JS
 */

class AccordionBlock {
  constructor() {
    this.init();
    if (window.acf) {
      window.acf.addAction('render_block_preview', el => this.init(el[0]));
    }
  }
  init(context = document) {
    // Update selector to ignore accordions that will be handled by other scripts
    const accordions = context.querySelectorAll('.accordion-block:not(.jobsites-accordion)');
    accordions.forEach(accordion => this.setupAccordion(accordion));
  }
  setupAccordion(accordion) {
    const panels = accordion.querySelectorAll('.accordion-panel');
    panels.forEach((panel, index) => {
      const header = panel.querySelector('.accordion-panel-header');
      const content = panel.querySelector('.accordion-panel-content');
      if (!header || !content || header.classList.contains('js-accordion-initialized')) {
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
        panels.forEach(p => {
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
      openPanels.forEach(panel => {
        const content = panel.querySelector('.accordion-panel-content');
        if (content) {
          content.style.maxHeight = content.scrollHeight + 'px';
        }
      });
    });
  }
}
class JobsitesAccordion {
  constructor() {
    this.initJobsitesFeatures();
  }
  initJobsitesFeatures(context = document) {
    const jobsitesBlocks = context.querySelectorAll('.jobsites-by-city-block');
    jobsitesBlocks.forEach(block => {
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
      letterButtons.forEach(button => {
        button.addEventListener('click', () => {
          const letter = button.dataset.letter;
          const targetPanel = accordion.querySelector(`.accordion-panel[data-letter="${letter}"]`);
          if (!targetPanel) return;
          targetPanel.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
          });

          // This logic now correctly handles closing other panels.
          allPanels.forEach(p => {
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
          letterButtons.forEach(btn => btn.classList.remove('active'));
          button.classList.add('active');
        });
      });
      if (skipSelect) {
        skipSelect.addEventListener('change', e => {
          const letter = e.target.value;
          const targetPanel = accordion.querySelector(`.accordion-panel[data-letter="${letter}"]`);
          if (!targetPanel) return;
          targetPanel.scrollIntoView({
            behavior: 'smooth'
          });
          allPanels.forEach(p => {
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

/***/ }),

/***/ "./assets/js/blocks/animations.js":
/*!****************************************!*\
  !*** ./assets/js/blocks/animations.js ***!
  \****************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   initBlockAnimations: () => (/* binding */ initBlockAnimations),
/* harmony export */   toggleAnimations: () => (/* binding */ toggleAnimations)
/* harmony export */ });
/**
 * Block Animations Module
 * Handles scroll-triggered animations for Gutenberg blocks
 */

function initBlockAnimations() {
  // Check if we're in the admin
  if (document.body.classList.contains('wp-admin')) {
    return;
  }

  // Check if Intersection Observer is supported
  if (!('IntersectionObserver' in window)) {
    // Fallback: animate all blocks immediately
    const blocks = document.querySelectorAll('[data-animate]');
    if (blocks.length > 0) {
      blocks.forEach(block => {
        block.classList.add('animated');
      });
    }
    return;
  }

  // Create intersection observer with error handling
  let observer;
  try {
    observer = new IntersectionObserver(entries => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('animated');
          // Once animated, stop observing
          observer.unobserve(entry.target);
        }
      });
    }, {
      threshold: 0.1,
      // Trigger when 10% of the element is visible
      rootMargin: '0px 0px -50px 0px' // Start animation slightly before element is fully in view
    });
  } catch (error) {
    console.warn('SWMW: Could not create IntersectionObserver:', error);
    return;
  }

  // Observe all blocks with data-animate attribute
  const animatedBlocksToObserve = document.querySelectorAll('[data-animate]');
  if (animatedBlocksToObserve.length > 0) {
    animatedBlocksToObserve.forEach(block => {
      try {
        observer.observe(block);
      } catch (error) {
        console.warn('SWMW: Could not observe block:', error);
      }
    });
  }

  // Optional: Re-initialize animations when content is loaded via AJAX
  // This is useful for "Load More" functionality
  document.addEventListener('swmw:contentLoaded', () => {
    const newBlocks = document.querySelectorAll('[data-animate]:not(.animated)');
    newBlocks.forEach(block => {
      try {
        observer.observe(block);
      } catch (error) {
        console.warn('SWMW: Could not observe new block:', error);
      }
    });
  });

  // Optional: Add a method to manually trigger animations
  window.swmwTriggerAnimations = () => {
    const blocks = document.querySelectorAll('[data-animate]:not(.animated)');
    blocks.forEach(block => {
      block.classList.add('animated');
    });
  };
}

// Optional: Add a toggle for animations
function toggleAnimations() {
  const body = document.body;
  const isAnimationsDisabled = body.classList.contains('no-animations');
  if (isAnimationsDisabled) {
    body.classList.remove('no-animations');
    // Re-initialize animations
    initBlockAnimations();
  } else {
    body.classList.add('no-animations');
  }
}

/***/ }),

/***/ "./assets/js/blocks/attorneys.js":
/*!***************************************!*\
  !*** ./assets/js/blocks/attorneys.js ***!
  \***************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   initAttorneysSlider: () => (/* binding */ initAttorneysSlider)
/* harmony export */ });
/**
 * Initializes sliders for the Attorneys Block.
 */
function initAttorneysSlider() {
  const sliders = document.querySelectorAll('.attorneys-block .splide');
  if (sliders.length === 0) {
    return; // No sliders found on this page
  }
  sliders.forEach(slider => {
    new Splide(slider, {
      type: 'loop',
      perPage: 4,
      perMove: 1,
      gap: '1rem',
      padding: '1rem',
      arrows: true,
      pagination: false,
      autoHeight: true,
      breakpoints: {
        991: {
          perPage: 2
        },
        767: {
          perPage: 1
        }
      }
    }).mount();
  });
}

/***/ }),

/***/ "./assets/js/blocks/results.js":
/*!*************************************!*\
  !*** ./assets/js/blocks/results.js ***!
  \*************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   initResultsSlider: () => (/* binding */ initResultsSlider)
/* harmony export */ });
/**
 * Initializes sliders for the Results Block.
 */
function initResultsSlider() {
  const sliders = document.querySelectorAll('.results-block .splide');
  if (!sliders.length) {
    return; // No sliders found on this page
  }
  sliders.forEach(slider => {
    const splide = new Splide(slider, {
      type: 'loop',
      perPage: 3,
      gap: '1rem',
      pagination: false,
      arrows: true,
      drag: true,
      snap: true,
      focus: 'center',
      trimSpace: true,
      breakpoints: {
        991: {
          perPage: 2
        },
        767: {
          perPage: 1
        }
      }
    });
    splide.on('mounted updated', () => {
      setTimeout(() => {
        const {
          Arrows
        } = splide.Components;
        const arrowIcon = `<svg width="25" height="24" viewBox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M24.1304 11.8223L1.63037 11.8223M1.63037 11.8223L12.2554 1.19726M1.63037 11.8223L12.2554 22.4473" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>`;
        if (Arrows.arrows.prev) Arrows.arrows.prev.innerHTML = arrowIcon;
        if (Arrows.arrows.next) Arrows.arrows.next.innerHTML = arrowIcon;
      }, 0);
    });
    splide.mount();
  });
}

/***/ }),

/***/ "./assets/js/blocks/testimonials.js":
/*!******************************************!*\
  !*** ./assets/js/blocks/testimonials.js ***!
  \******************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   initTestimonialsSlider: () => (/* binding */ initTestimonialsSlider)
/* harmony export */ });
/**
 * Initializes sliders for the Testimonials Block.
 */
function initTestimonialsSlider() {
  const sliders = document.querySelectorAll('.testimonials-block .splide');
  if (!sliders.length) {
    return;
  }
  sliders.forEach(slider => {
    const slides = slider.querySelectorAll('.splide__slide');
    if (slides.length <= 1) {
      // If only one testimonial, do NOT initialize Splide
      const arrows = slider.querySelector('.splide__arrows');
      if (arrows) arrows.style.display = 'none';
      return;
    }
    const splide = new Splide(slider, {
      type: 'loop',
      perPage: 1,
      pagination: false,
      arrows: false,
      drag: true,
      snap: true,
      focus: 'center',
      trimSpace: true
    });
    const frame = slider.closest('.testimonial-frame');
    if (frame) {
      const prevArrow = frame.querySelector('.splide__arrow--prev');
      const nextArrow = frame.querySelector('.splide__arrow--next');
      const arrowIcon = `<svg width="25" height="24" viewBox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M24.1304 11.8223L1.63037 11.8223M1.63037 11.8223L12.2554 1.19726M1.63037 11.8223L12.2554 22.4473" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>`;
      if (prevArrow && nextArrow) {
        prevArrow.innerHTML = arrowIcon;
        nextArrow.innerHTML = arrowIcon;
        prevArrow.addEventListener('click', () => splide.go('<'));
        nextArrow.addEventListener('click', () => splide.go('>'));
      }
    }
    splide.mount();
  });
}

/***/ }),

/***/ "./assets/js/button-hover-animation.js":
/*!*********************************************!*\
  !*** ./assets/js/button-hover-animation.js ***!
  \*********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
function initializeButtonHoverAnimation() {
  const buttons = document.querySelectorAll('.wp-block-button:not(.is-style-outline) .wp-block-button__link, ' + '.wp-block-button.is-style-outline .wp-block-button__link');
  buttons.forEach(button => {
    let originalInlineTextColor = button.style.color; // Store only current inline style for restoration
    const computedInitialTextColor = window.getComputedStyle(button).color;
    const computedButtonStyle = window.getComputedStyle(button);
    const initialButtonBackgroundColor = computedButtonStyle.backgroundColor;

    // Set initial border color based on button type
    if (button.closest('.wp-block-button.is-style-outline')) {
      button.style.borderColor = computedInitialTextColor; // Outline border matches its text color
    } else {
      button.style.borderColor = initialButtonBackgroundColor; // Fill button border matches its background
    }
    button.addEventListener('mouseenter', function () {
      // Recapture original inline text color before changing it
      originalInlineTextColor = button.style.color;
      let hoverTextTargetColor = initialButtonBackgroundColor; // Default: for fill buttons, hover text matches their initial background

      if (button.closest('.wp-block-button.is-style-outline')) {
        // For outline buttons, hover text matches their initial text color
        hoverTextTargetColor = computedInitialTextColor;
        // Fallback if outline's initial text color was transparent (highly unlikely but safe)
        if (hoverTextTargetColor === 'rgba(0, 0, 0, 0)' || hoverTextTargetColor === 'transparent') {
          hoverTextTargetColor = '#000000';
        }
      }
      button.style.setProperty('color', hoverTextTargetColor, 'important');
    });
    button.addEventListener('mouseleave', function () {
      // Restore to original inline text color if one existed, otherwise remove the style to revert to CSS
      if (originalInlineTextColor) {
        button.style.color = originalInlineTextColor;
      } else {
        button.style.removeProperty('color');
      }
    });
  });
}

// Check if the script is being run in a browser environment before trying to add the event listener.
// This also makes the function testable in non-browser environments if needed.
if (typeof window !== 'undefined' && typeof document !== 'undefined') {
  document.addEventListener('DOMContentLoaded', initializeButtonHoverAnimation);
}
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (initializeButtonHoverAnimation);

/***/ }),

/***/ "./assets/js/hero-dropdown-nav.js":
/*!****************************************!*\
  !*** ./assets/js/hero-dropdown-nav.js ***!
  \****************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   initHeroDropdownNav: () => (/* binding */ initHeroDropdownNav)
/* harmony export */ });
/**
 * Hero Dropdown Navigation - Auto-generated from H2/H3 headings
 * Creates anchor navigation from page headings with smooth scrolling
 */

function initHeroDropdownNav() {
  // Find all hero dropdown menus
  const heroMenus = document.querySelectorAll('.hero-dropdown-menu');
  heroMenus.forEach(function (heroMenu, index) {
    const toggle = heroMenu.querySelector('.hamburger-menu-toggle');
    const navList = heroMenu.querySelector('.hero-nav-list');
    const navItems = heroMenu.querySelector('.hero-nav-items');
    if (!toggle || !navList || !navItems) {
      return;
    }

    // Get configuration from data attributes
    const scrollOffset = parseInt(heroMenu.dataset.scrollOffset) || 100;

    // Get menu title
    const label = toggle.querySelector('.hamburger-label');
    const menuTitle = label ? label.textContent : 'Page Navigation';

    // Initialize navigation
    initNavigation();

    // Toggle functionality
    toggle.addEventListener('click', function (event) {
      event.preventDefault();
      toggleMenu();
    });

    // Click outside to close
    document.addEventListener('click', function (event) {
      if (heroMenu.classList.contains('is-open') && !heroMenu.contains(event.target)) {
        closeMenu();
      }
    });

    // Handle navigation clicks
    navList.addEventListener('click', function (event) {
      const link = event.target.closest('a[href^="#"]');
      if (link) {
        event.preventDefault();
        const targetId = link.getAttribute('href').substring(1);
        scrollToSection(targetId);
        closeMenu();
      }
    });

    /**
     * Initialize navigation by scanning page for headings
     */
    function initNavigation() {
      // Check if manual items already exist
      const existingItems = navItems.querySelectorAll('li:not(.hero-nav-loading)');
      if (existingItems.length > 0) {
        // Remove loading state
        const loadingItem = navItems.querySelector('.hero-nav-loading');
        if (loadingItem) {
          loadingItem.remove();
        }
        return;
      }

      // Find all H2 and H3 headings - try multiple selectors for better coverage
      let headings = [];
      // Try to find headings in main content areas first
      const contentSelectors = ['main', '.entry-content', '.content-area', '#main', '.container', 'article', '.post-content', '.page-content'];
      for (const selector of contentSelectors) {
        const content = document.querySelector(selector);
        if (content) {
          const foundHeadings = content.querySelectorAll('h2');
          if (foundHeadings.length > 0) {
            headings = Array.from(foundHeadings);
            break;
          }
        }
      }
      // If no headings found in content areas, search the entire document
      if (headings.length === 0) {
        headings = Array.from(document.querySelectorAll('h2'));
      }
      if (headings.length === 0) {
        navItems.innerHTML = '<li class="hero-nav-empty"><span>No headings found on this page</span></li>';
        return;
      }

      // Build navigation structure
      const navStructure = buildNavigationStructure(headings);

      // Generate HTML
      navItems.innerHTML = generateNavigationHTML(navStructure);
    }

    /**
     * Build navigation structure from headings
     */
    function buildNavigationStructure(headings) {
      const structure = [];
      headings.forEach(function (heading) {
        const text = heading.textContent.trim();
        // Skip empty headings
        if (!text) return;
        // Generate ID if not present
        if (!heading.id) {
          heading.id = generateHeadingId(text);
        }
        structure.push({
          id: heading.id,
          text: text
        });
      });
      return structure;
    }

    /**
     * Generate navigation HTML
     */
    function generateNavigationHTML(structure) {
      if (structure.length === 0) {
        return '<li class="hero-nav-empty"><span>No headings found on this page</span></li>';
      }
      return structure.map(function (item) {
        return `<li><a href="#${item.id}">${escapeHtml(item.text)}</a></li>`;
      }).join('');
    }

    /**
     * Generate heading ID from text
     */
    function generateHeadingId(text) {
      return text.toLowerCase().replace(/[^a-z0-9\s-]/g, '').replace(/\s+/g, '-').replace(/-+/g, '-').trim('-');
    }

    /**
     * Escape HTML to prevent XSS
     */
    function escapeHtml(text) {
      const div = document.createElement('div');
      div.textContent = text;
      return div.innerHTML;
    }

    /**
     * Toggle menu open/closed
     */
    function toggleMenu() {
      const isOpen = heroMenu.classList.contains('is-open');
      if (!isOpen) {
        // Ensure parent containers allow overflow
        ensureOverflowVisible();
      }
      heroMenu.classList.toggle('is-open');
      const hamburgerIcon = toggle.querySelector('.hamburger-icon');
      if (hamburgerIcon) {
        hamburgerIcon.classList.toggle('is-active');
      }
      if (label) {
        const isOpen = heroMenu.classList.contains('is-open');
        label.textContent = isOpen ? 'Close' : menuTitle;
      }

      // Update ARIA attributes
      const isExpanded = heroMenu.classList.contains('is-open');
      toggle.setAttribute('aria-expanded', isExpanded);
    }

    /**
     * Ensure parent containers allow overflow for dropdown visibility
     */
    function ensureOverflowVisible() {
      // Find common parent containers that might clip the dropdown
      const parentSelectors = ['.hero-dropdown-menu', '.hero-dropdown-container', '.wp-block', '.container', '.content-area', 'main', 'article', '.entry-content', '.post-content', '.page-content', '.wp-block-hero-dropdown-menu-block', '.hero-dropdown-menu-block'];
      parentSelectors.forEach(selector => {
        const parent = heroMenu.closest(selector);
        if (parent) {
          parent.style.overflow = 'visible';
          // Also ensure any immediate children that might clip
          const children = parent.children;
          for (let i = 0; i < children.length; i++) {
            const child = children[i];
            if (child !== heroMenu && child !== heroMenu.parentElement) {
              const computedStyle = window.getComputedStyle(child);
              if (computedStyle.overflow === 'hidden' || computedStyle.overflow === 'clip') {
                child.style.overflow = 'visible';
              }
            }
          }
        }
      });

      // Also check for any containers that might be clipping the dropdown
      const allParents = [];
      let currentParent = heroMenu.parentElement;
      while (currentParent && currentParent !== document.body) {
        allParents.push(currentParent);
        currentParent = currentParent.parentElement;
      }
      allParents.forEach(parent => {
        const computedStyle = window.getComputedStyle(parent);
        if (computedStyle.overflow === 'hidden' || computedStyle.overflow === 'clip') {
          parent.style.overflow = 'visible';
        }
      });
    }

    /**
     * Close menu
     */
    function closeMenu() {
      heroMenu.classList.remove('is-open');
      const hamburgerIcon = toggle.querySelector('.hamburger-icon');
      if (hamburgerIcon) {
        hamburgerIcon.classList.remove('is-active');
      }
      if (label) {
        label.textContent = menuTitle;
      }
      toggle.setAttribute('aria-expanded', 'false');
    }

    /**
     * Smooth scroll to section
     */
    function scrollToSection(targetId) {
      const targetElement = document.getElementById(targetId);
      if (!targetElement) {
        return;
      }

      // Use getBoundingClientRect for more accurate positioning
      const rect = targetElement.getBoundingClientRect();
      const currentScrollY = window.scrollY;
      const targetPosition = currentScrollY + rect.top;

      // Calculate the correct scroll position
      let finalScrollPosition = targetPosition;

      // Check if header is fixed (mobile) and adjust accordingly
      const header = document.querySelector('.site-header');
      if (header && window.getComputedStyle(header).position === 'fixed') {
        // On mobile with fixed header, account for header height
        const headerHeight = header.offsetHeight;
        finalScrollPosition = finalScrollPosition - headerHeight - 20; // 20px extra buffer
      } else {
        // On desktop, use the configured scroll offset
        finalScrollPosition = finalScrollPosition - scrollOffset;
      }
      window.scrollTo({
        top: Math.max(0, finalScrollPosition),
        // Ensure we don't scroll to negative position
        behavior: 'smooth'
      });
    }
  });
}

/***/ }),

/***/ "./assets/js/mega-menu.js":
/*!********************************!*\
  !*** ./assets/js/mega-menu.js ***!
  \********************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   equalizeMegaMenuHeights: () => (/* binding */ equalizeMegaMenuHeights),
/* harmony export */   initMegaMenus: () => (/* binding */ initMegaMenus),
/* harmony export */   moveMegaPanels: () => (/* binding */ moveMegaPanels)
/* harmony export */ });
function initMegaMenus() {
  const megaMenuItems = document.querySelectorAll('.main-navigation .menu-item-has-mega-menu');
  const hoverDelay = 50;
  megaMenuItems.forEach(topItem => {
    const topLink = topItem.querySelector(':scope > a');
    const megaPanel = topItem.querySelector(':scope > .mega-menu-panel');
    let panelCloseTimer = null;
    let topItemCloseTimer = null;
    if (!topLink || !megaPanel) return;
    megaPanel.associatedTopLink = topLink;
    const rightColumn = megaPanel.querySelector('.mega-menu-column-right-content');
    const leftColumn = megaPanel.querySelector('.mega-menu-column-left'); // Added for completeness

    if (!leftColumn || !rightColumn) return;
    const openPanel = () => {
      clearTimeout(panelCloseTimer);
      clearTimeout(topItemCloseTimer);
      megaPanel.classList.add('is-open');
      topLink.setAttribute('aria-expanded', 'true');
      megaPanel.setAttribute('aria-hidden', 'false');
    };
    const closePanel = () => {
      megaPanel.classList.remove('is-open');
      if (megaPanel.associatedTopLink) {
        megaPanel.associatedTopLink.setAttribute('aria-expanded', 'false');
      }
      megaPanel.setAttribute('aria-hidden', 'true');
      rightColumn.innerHTML = '';
    };
    topItem.addEventListener('mouseenter', () => {
      clearTimeout(panelCloseTimer);
      openPanel();
      rightColumn.innerHTML = '';
    });
    topItem.addEventListener('mouseleave', () => {
      clearTimeout(topItemCloseTimer);
      topItemCloseTimer = setTimeout(closePanel, hoverDelay);
    });
    megaPanel.addEventListener('mouseenter', () => {
      clearTimeout(topItemCloseTimer);
      clearTimeout(panelCloseTimer);
    });
    megaPanel.addEventListener('mouseleave', () => {
      clearTimeout(panelCloseTimer);
      panelCloseTimer = setTimeout(closePanel, hoverDelay);
    });
    const allSecondLevelItems = leftColumn.querySelectorAll('.mega-menu-child-list > li.menu-item');
    allSecondLevelItems.forEach(secondItem => {
      const secondLevelLink = secondItem.querySelector(':scope > a');
      const thirdLevelSourceSubMenu = secondItem.querySelector(':scope > .sub-menu.sub-menu-level-2');
      secondItem.addEventListener('mouseenter', () => {
        rightColumn.innerHTML = '';
        if (thirdLevelSourceSubMenu) {
          const thirdLevelListItems = thirdLevelSourceSubMenu.querySelectorAll(':scope > li');
          if (thirdLevelListItems.length > 0) {
            if (secondLevelLink) {
              const titleElement = document.createElement('h3');
              titleElement.className = 'mega-menu-right-column-title';
              const textElement = document.createElement('span');
              textElement.className = 'mega-menu-title-text';
              textElement.textContent = secondLevelLink.textContent;
              titleElement.appendChild(textElement);
              const lineElement = document.createElement('span');
              lineElement.className = 'mega-menu-title-line';
              titleElement.appendChild(lineElement);
              rightColumn.appendChild(titleElement);
            }
            const newUl = document.createElement('ul');
            newUl.className = 'sub-menu third-level-list';
            if (thirdLevelListItems.length > 9) {
              newUl.classList.add('multi-column');
            }
            thirdLevelListItems.forEach(li => newUl.appendChild(li.cloneNode(true)));
            rightColumn.appendChild(newUl);
          }
        }
        allSecondLevelItems.forEach(item => item.classList.remove('is-active-child'));
        secondItem.classList.add('is-active-child');
      });
    });
  });
}
function moveMegaPanels() {
  const siteHeader = document.querySelector('.site-header');
  if (!siteHeader) {
    return;
  }
  const megaPanels = document.querySelectorAll('.mega-menu-panel');
  megaPanels.forEach(panel => {
    siteHeader.appendChild(panel);
  });
}
function equalizeMegaMenuHeights() {
  let maxInnerHeight = 0;
  const megaMenuTriggers = document.querySelectorAll('.main-navigation .menu-item-has-mega-menu');
  megaMenuTriggers.forEach(topItem => {
    const topLink = topItem.querySelector(':scope > a');
    if (!topLink || !topLink.id) return;
    const panelId = topLink.id;
    const panel = document.querySelector(`.mega-menu-panel[aria-labelledby="${panelId}"]`);
    if (!panel) return;
    const panelInner = panel.querySelector('.mega-menu-panel-inner');
    const leftColumn = panel.querySelector('.mega-menu-column-left');
    const rightColumn = panel.querySelector('.mega-menu-column-right-content');
    if (!panelInner || !leftColumn || !rightColumn) return;
    const originalPanelDisplay = panel.style.display;
    const originalPanelVisibility = panel.style.visibility;
    const originalPanelLeft = panel.style.left;
    const originalPanelTop = panel.style.top;
    panel.style.position = 'absolute';
    panel.style.left = '-9999px';
    panel.style.top = '0px';
    panel.style.visibility = 'hidden';
    panel.style.display = 'block';
    rightColumn.innerHTML = '';
    maxInnerHeight = Math.max(maxInnerHeight, panelInner.offsetHeight);
    const secondLevelItems = leftColumn.querySelectorAll('.mega-menu-child-list > li.menu-item');
    secondLevelItems.forEach(secondItem => {
      const secondLevelLink = secondItem.querySelector(':scope > a');
      const thirdLevelSourceSubMenu = secondItem.querySelector(':scope > .sub-menu.sub-menu-level-2');
      if (thirdLevelSourceSubMenu) {
        const thirdLevelListItems = thirdLevelSourceSubMenu.querySelectorAll(':scope > li');
        if (thirdLevelListItems.length > 0) {
          rightColumn.innerHTML = '';
          if (secondLevelLink) {
            const titleElement = document.createElement('h3');
            titleElement.className = 'mega-menu-right-column-title';
            const textElement = document.createElement('span');
            textElement.className = 'mega-menu-title-text';
            textElement.textContent = secondLevelLink.textContent;
            titleElement.appendChild(textElement);
            const lineElement = document.createElement('span');
            lineElement.className = 'mega-menu-title-line';
            titleElement.appendChild(lineElement);
            rightColumn.appendChild(titleElement);
          }
          const newUl = document.createElement('ul');
          newUl.className = 'sub-menu third-level-list';
          if (thirdLevelListItems.length > 9) {
            newUl.classList.add('multi-column');
          }
          thirdLevelListItems.forEach(li => newUl.appendChild(li.cloneNode(true)));
          rightColumn.appendChild(newUl);
          maxInnerHeight = Math.max(maxInnerHeight, panelInner.offsetHeight);
        }
      }
    });
    rightColumn.innerHTML = '';
    panel.style.display = originalPanelDisplay || '';
    if (panel.style.display === '') panel.style.removeProperty('display');
    panel.style.visibility = originalPanelVisibility || '';
    if (panel.style.visibility === '') panel.style.removeProperty('visibility');
    panel.style.left = originalPanelLeft || '';
    if (panel.style.left === '') panel.style.removeProperty('left');
    panel.style.top = originalPanelTop || '';
    if (panel.style.top === '') panel.style.removeProperty('top');
  });
  if (maxInnerHeight > 0) {
    document.querySelectorAll('.mega-menu-panel .mega-menu-panel-inner').forEach(inner => {
      inner.style.minHeight = maxInnerHeight + 'px';
    });
  }
}

/***/ }),

/***/ "./assets/js/mobile-menu.js":
/*!**********************************!*\
  !*** ./assets/js/mobile-menu.js ***!
  \**********************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   initMobileMenu: () => (/* binding */ initMobileMenu),
/* harmony export */   initMobileSubMenus: () => (/* binding */ initMobileSubMenus)
/* harmony export */ });
function initMobileMenu() {
  const navToggle = document.querySelector('.nav-toggle');
  const mobileNavPanel = document.getElementById('mobile-navigation-panel');
  const mobileMenuOverlay = document.querySelector('.mobile-menu-overlay');
  const siteHeader = document.querySelector('.site-header');
  const siteMain = document.querySelector('.site-main');
  let headerPlaceholder = null;
  if (!navToggle || !mobileNavPanel || !siteHeader || !siteMain || !mobileMenuOverlay) {
    return;
  }
  function createHeaderPlaceholder() {
    if (headerPlaceholder) return;
    headerPlaceholder = document.createElement('div');
    headerPlaceholder.classList.add('site-header-placeholder');
    headerPlaceholder.style.height = `${siteHeader.offsetHeight}px`;
    siteMain.before(headerPlaceholder);
  }
  function removeHeaderPlaceholder() {
    if (headerPlaceholder) {
      headerPlaceholder.remove();
      headerPlaceholder = null;
    }
  }
  function handleHeaderStickiness() {
    // Stick header on mobile, remove placeholder on desktop
    if (window.innerWidth <= 1024) {
      // Corresponds to $breakpoint-lg
      createHeaderPlaceholder();
      const adminBar = document.getElementById('wpadminbar');
      if (adminBar && getComputedStyle(adminBar).position === 'fixed') {
        siteHeader.style.top = `${adminBar.offsetHeight}px`;
      } else {
        siteHeader.style.top = '0px';
      }
    } else {
      removeHeaderPlaceholder();
      siteHeader.style.top = ''; // Reset top property on desktop
    }
  }
  const openMenu = () => {
    let topOffset = siteHeader.offsetHeight;
    const adminBar = document.getElementById('wpadminbar');
    if (adminBar && getComputedStyle(adminBar).position === 'fixed') {
      topOffset += adminBar.offsetHeight;
    }
    mobileNavPanel.style.top = `${topOffset}px`;
    mobileNavPanel.style.bottom = '';
    mobileNavPanel.style.right = '0';
    mobileNavPanel.style.left = '';
    // Set height to fill the viewport below the header/admin bar
    const availableHeight = window.innerHeight - topOffset;
    mobileNavPanel.style.height = `${availableHeight}px`;
    document.body.classList.add('mobile-menu-active');
    navToggle.classList.add('is-active');
    mobileNavPanel.classList.add('is-open');
    mobileMenuOverlay.classList.add('is-active');
    navToggle.setAttribute('aria-expanded', 'true');
    mobileNavPanel.setAttribute('aria-hidden', 'false');
    mobileMenuOverlay.setAttribute('aria-hidden', 'false');
  };
  const closeMenu = () => {
    document.body.classList.remove('mobile-menu-active');
    navToggle.classList.remove('is-active');
    mobileNavPanel.classList.remove('is-open');
    mobileMenuOverlay.classList.remove('is-active');
    navToggle.setAttribute('aria-expanded', 'false');
    mobileNavPanel.setAttribute('aria-hidden', 'true');
    mobileMenuOverlay.setAttribute('aria-hidden', 'true');
  };
  navToggle.addEventListener('click', () => {
    if (navToggle.classList.contains('is-active')) {
      closeMenu();
    } else {
      openMenu();
    }
  });

  // Close menu when overlay is clicked
  mobileMenuOverlay.addEventListener('click', closeMenu);

  // Handle header stickiness on load and resize
  handleHeaderStickiness();
  window.addEventListener('resize', handleHeaderStickiness);
  document.addEventListener('keydown', e => {
    if (e.key === 'Escape' && navToggle.classList.contains('is-active')) {
      closeMenu();
    }
  });

  // We no longer need the resize listener, CSS handles the height.
  // window.addEventListener('resize', setPanelHeight);
}
function initMobileSubMenus() {
  const mobileNavList = document.querySelector('#mobile-navigation-panel .mobile-primary-nav-list');
  if (!mobileNavList) return;
  const parentMenuItems = mobileNavList.querySelectorAll('.menu-item-has-children');
  const updateParentHeights = startElement => {
    let parent = startElement.parentElement.closest('.menu-item-has-children.is-open');
    while (parent) {
      const subMenu = parent.querySelector(':scope > .sub-menu');
      if (subMenu) {
        const newHeight = subMenu.scrollHeight;
        subMenu.style.maxHeight = `${newHeight}px`;
      }
      parent = parent.parentElement.closest('.menu-item-has-children.is-open');
    }
  };
  parentMenuItems.forEach(item => {
    const link = item.querySelector(':scope > a');
    const subMenu = item.querySelector(':scope > .sub-menu');
    if (!subMenu || !link) return;

    // Ensure arrow exists
    if (!link.querySelector('.submenu-arrow')) {
      const arrow = document.createElement('span');
      arrow.classList.add('submenu-arrow');
      link.appendChild(arrow);
    }
    link.addEventListener('click', e => {
      e.preventDefault();
      const isCurrentlyOpen = item.classList.contains('is-open');

      // Find sibling items at the same level and close them
      const parentUl = item.parentElement;
      const siblingItems = parentUl.querySelectorAll(':scope > .menu-item-has-children');
      siblingItems.forEach(sibling => {
        if (sibling !== item) {
          sibling.classList.remove('is-open');
          const siblingSubMenu = sibling.querySelector(':scope > .sub-menu');
          if (siblingSubMenu) {
            siblingSubMenu.style.maxHeight = null;
          }
        }
      });

      // Toggle the clicked item
      if (isCurrentlyOpen) {
        item.classList.remove('is-open');
        subMenu.style.maxHeight = null;
      } else {
        item.classList.add('is-open');
        subMenu.style.maxHeight = `${subMenu.scrollHeight}px`;
      }

      // After toggling, update all parent heights after a short delay
      // to allow the CSS transition to start.
      setTimeout(() => {
        updateParentHeights(item);
      }, 300); // This should match the CSS transition duration
    });
  });

  // Collapse all submenus on initialization
  mobileNavList.querySelectorAll('.sub-menu').forEach(subMenu => {
    subMenu.style.maxHeight = null;
    const parentLi = subMenu.closest('.menu-item-has-children');
    if (parentLi) {
      parentLi.classList.remove('is-open');
    }
  });
}

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/define property getters */
/******/ 	(() => {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = (exports, definition) => {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	(() => {
/******/ 		__webpack_require__.o = (obj, prop) => (Object.prototype.hasOwnProperty.call(obj, prop))
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/************************************************************************/
var __webpack_exports__ = {};
// This entry needs to be wrapped in an IIFE because it needs to be isolated against other modules in the chunk.
(() => {
/*!***************************!*\
  !*** ./assets/js/main.js ***!
  \***************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _button_hover_animation_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./button-hover-animation.js */ "./assets/js/button-hover-animation.js");
/* harmony import */ var _mobile_menu_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./mobile-menu.js */ "./assets/js/mobile-menu.js");
/* harmony import */ var _mega_menu_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./mega-menu.js */ "./assets/js/mega-menu.js");
/* harmony import */ var _blocks_results_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./blocks/results.js */ "./assets/js/blocks/results.js");
/* harmony import */ var _blocks_testimonials_js__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./blocks/testimonials.js */ "./assets/js/blocks/testimonials.js");
/* harmony import */ var _blocks_attorneys_js__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./blocks/attorneys.js */ "./assets/js/blocks/attorneys.js");
/* harmony import */ var _blocks_accordion_js__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ./blocks/accordion.js */ "./assets/js/blocks/accordion.js");
/* harmony import */ var _hero_dropdown_nav_js__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ./hero-dropdown-nav.js */ "./assets/js/hero-dropdown-nav.js");
/* harmony import */ var _blocks_animations_js__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! ./blocks/animations.js */ "./assets/js/blocks/animations.js");
/* harmony import */ var _accordion_columns_js__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! ./accordion-columns.js */ "./assets/js/accordion-columns.js");
//Import any JS here

 // Import the module










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
    (0,_mobile_menu_js__WEBPACK_IMPORTED_MODULE_1__.initMobileMenu)();
    (0,_mobile_menu_js__WEBPACK_IMPORTED_MODULE_1__.initMobileSubMenus)(); // Call the new mobile submenu initializer
    (0,_mega_menu_js__WEBPACK_IMPORTED_MODULE_2__.initMegaMenus)();
    (0,_mega_menu_js__WEBPACK_IMPORTED_MODULE_2__.moveMegaPanels)();
    try {
      (0,_mega_menu_js__WEBPACK_IMPORTED_MODULE_2__.equalizeMegaMenuHeights)();
    } catch (error) {
      console.error('MAIN.JS - ERROR in equalizeMegaMenuHeights():', error);
    }
    try {
      initModals(); // Assuming this is still initialized here
    } catch (error) {
      console.error('MAIN.JS - ERROR in initModals():', error);
    }
    try {
      (0,_button_hover_animation_js__WEBPACK_IMPORTED_MODULE_0__["default"])();
    } catch (error) {
      console.error('MAIN.JS - ERROR in initializeButtonHoverAnimation():', error);
    }
    try {
      (0,_blocks_results_js__WEBPACK_IMPORTED_MODULE_3__.initResultsSlider)(); // Initialize the results slider
    } catch (error) {
      console.error('MAIN.JS - ERROR in initResultsSlider():', error);
    }
    try {
      (0,_blocks_testimonials_js__WEBPACK_IMPORTED_MODULE_4__.initTestimonialsSlider)(); // Initialize the testimonials slider
    } catch (error) {
      console.error('MAIN.JS - ERROR in initTestimonialsSlider():', error);
    }
    try {
      (0,_blocks_attorneys_js__WEBPACK_IMPORTED_MODULE_5__.initAttorneysSlider)(); // Initialize the attorneys slider
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
      (0,_accordion_columns_js__WEBPACK_IMPORTED_MODULE_9__.initAccordionColumns)(); // Initialize the accordion columnizer FIRST
    } catch (error) {
      console.error('MAIN.JS - ERROR in initAccordionColumns():', error);
    }
    try {
      new _blocks_accordion_js__WEBPACK_IMPORTED_MODULE_6__.AccordionBlock();
    } catch (error) {
      console.error('MAIN.JS - ERROR in AccordionBlock():', error);
    }
    try {
      new _blocks_accordion_js__WEBPACK_IMPORTED_MODULE_6__.JobsitesAccordion();
    } catch (error) {
      console.error('MAIN.JS - ERROR in JobsitesAccordion():', error);
    }
    try {
      (0,_hero_dropdown_nav_js__WEBPACK_IMPORTED_MODULE_7__.initHeroDropdownNav)(); // Initialize the hero dropdown navigation
    } catch (error) {
      console.error('MAIN.JS - ERROR in initHeroDropdownNav():', error);
    }
    try {
      (0,_blocks_animations_js__WEBPACK_IMPORTED_MODULE_8__.initBlockAnimations)(); // Initialize block animations
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
          nonce: swmwLawData.load_more_attorneys_nonce // Get nonce from localized data
        },
        success(response) {
          if (response.success) {
            if (response.data.html) {
              $('.attorney-grid').append(response.data.html);
              button.text('Load More Attorneys').prop('disabled', false);
            } else {
              button.text('No More Attorneys').prop('disabled', true);
            }
            // Check if we've reached the max number of pages
            if (currentPage >= response.data.max_pages) {
              button.text('No More Attorneys').prop('disabled', true);
            }
          } else {
            console.error('Error loading attorneys:', response.data.message);
            button.text('Error - Try Again').prop('disabled', false);
          }
        },
        error(jqXHR, textStatus, errorThrown) {
          console.error('AJAX error:', textStatus, errorThrown);
          button.text('AJAX Error - Try Again').prop('disabled', false);
        }
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
          nonce: swmwLawData.load_more_results_nonce // Get nonce from localized data
        },
        success(response) {
          if (response.success) {
            if (response.data.html) {
              $('.swmw-results-grid').append(response.data.html);
              button.text('Load More Results').prop('disabled', false);
            } else {
              button.text('No More Results').prop('disabled', true);
            }
            // Check if we've reached the max number of pages
            if (currentPage >= response.data.max_pages) {
              button.text('No More Results').prop('disabled', true);
            }
          } else {
            console.error('Error loading results:', response.data.message);
            button.text('Error - Try Again').prop('disabled', false);
          }
        },
        error(jqXHR, textStatus, errorThrown) {
          console.error('AJAX error:', textStatus, errorThrown);
          button.text('AJAX Error - Try Again').prop('disabled', false);
        }
      });
    });
  }
})(jQuery);
})();

/******/ })()
;
//# sourceMappingURL=main.js.map