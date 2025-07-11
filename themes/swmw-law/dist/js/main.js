/******/ (() => { // webpackBootstrap
/******/ 	var __webpack_modules__ = ({

/***/ "./assets/js/blocks/accordion.js":
/*!***************************************!*\
  !*** ./assets/js/blocks/accordion.js ***!
  \***************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   AccordionBlock: () => (/* binding */ AccordionBlock)
/* harmony export */ });
/**
 * Accordion Block JS
 *
 * @package
 */

class AccordionBlock {
  constructor() {
    this.init();
    // Use a more robust listener for editor updates
    if (window.acf) {
      window.acf.addAction('render_block_preview', el => this.init(el[0]));
    }
  }
  init(context = document) {
    const accordions = context.querySelectorAll('.accordion-block');
    accordions.forEach(accordion => this.setupAccordion(accordion));
  }
  setupAccordion(accordion) {
    const panels = accordion.querySelectorAll('.accordion-panel');
    panels.forEach((panel, index) => {
      const header = panel.querySelector('.accordion-panel-header');
      const content = panel.querySelector('.accordion-panel-content');
      if (!header || !content || header.classList.contains('js-accordion-initialized')) {
        return; // Exit if parts are missing or already initialized
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

        // A simple accordion logic: close others if you want only one open at a time.
        // This example is a simple toggle.
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

    // Recalculate height on window load for open-by-default panels
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
document.addEventListener('DOMContentLoaded', () => {
  new AccordionBlock();
});

/***/ }),

/***/ "./assets/js/blocks/animations.js":
/*!****************************************!*\
  !*** ./assets/js/blocks/animations.js ***!
  \****************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
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
  console.log('SWMW: Initializing block animations...');

  // Check if we're in the admin
  if (document.body.classList.contains('wp-admin')) {
    console.log('SWMW: In admin, skipping animations');
    return;
  }

  // Test: Check if any blocks have the data attribute
  const allBlocks = document.querySelectorAll('.wp-block');
  console.log('SWMW: Total blocks found:', allBlocks.length);
  const animatedBlocks = document.querySelectorAll('[data-animate]');
  console.log('SWMW: Blocks with data-animate attribute:', animatedBlocks.length);

  // Log the first few blocks to see their structure
  allBlocks.forEach((block, index) => {
    if (index < 5) {
      console.log('SWMW: Block', index, 'classes:', block.className, 'data-animate:', block.getAttribute('data-animate'));
    }
  });

  // Check if Intersection Observer is supported
  if (!('IntersectionObserver' in window)) {
    console.log('SWMW: IntersectionObserver not supported, using fallback');
    // Fallback: animate all blocks immediately
    const blocks = document.querySelectorAll('[data-animate]');
    console.log('SWMW: Found', blocks.length, 'blocks to animate');
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
          console.log('SWMW: Animating block:', entry.target, 'with animation:', entry.target.getAttribute('data-animate'));
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
  console.log('SWMW: Found', animatedBlocksToObserve.length, 'blocks with data-animate attribute');
  if (animatedBlocksToObserve.length > 0) {
    animatedBlocksToObserve.forEach(block => {
      try {
        observer.observe(block);
        console.log('SWMW: Observing block:', block, 'with animation:', block.getAttribute('data-animate'));
      } catch (error) {
        console.warn('SWMW: Could not observe block:', error);
      }
    });
  } else {
    console.log('SWMW: No blocks found with data-animate attribute');
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
/***/ (() => {

/**
 * Initializes sliders for the Attorneys Block.
 */
document.addEventListener('DOMContentLoaded', () => {
  const sliders = document.querySelectorAll('.attorneys-block .splide');
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
});

/***/ }),

/***/ "./assets/js/blocks/results.js":
/*!*************************************!*\
  !*** ./assets/js/blocks/results.js ***!
  \*************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
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

"use strict";
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

"use strict";
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

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   initHeroDropdownNav: () => (/* binding */ initHeroDropdownNav)
/* harmony export */ });
/**
 * Hero Dropdown Navigation Toggle
 * Adds toggle functionality for dropdown navigation in hero sections
 */

function initHeroDropdownNav() {
  // Find all hamburger menu toggles
  const toggles = document.querySelectorAll('.hamburger-menu-toggle');
  toggles.forEach(function (toggle) {
    // Get the menu title from the navigation block
    const heroNav = toggle.closest('.hero-dropdown-menu');
    const navBlock = heroNav ? heroNav.querySelector('.hero-nav-list') : null;
    let menuTitle = 'Menu'; // Default fallback

    if (navBlock) {
      // Try multiple ways to get the navigation title
      const navTitle = navBlock.getAttribute('data-menu-title') ||
      // Custom data attribute
      navBlock.getAttribute('aria-label') ||
      // WordPress navigation label
      navBlock.getAttribute('data-title') ||
      // Alternative title attribute
      navBlock.querySelector('.wp-block-navigation__container')?.getAttribute('aria-label') || navBlock.querySelector('.wp-block-navigation__container')?.getAttribute('data-title');
      if (navTitle && navTitle.trim() !== '') {
        menuTitle = navTitle.trim();
      }
    }

    // Set the initial label text
    const label = toggle.querySelector('.hamburger-label');
    if (label) {
      label.textContent = menuTitle;
    }
    toggle.addEventListener('click', function (event) {
      // Prevent default behavior
      event.preventDefault();
      if (heroNav) {
        // Toggle the is-open class
        heroNav.classList.toggle('is-open');

        // Update hamburger icon animation
        const hamburgerIcon = toggle.querySelector('.hamburger-icon');
        if (hamburgerIcon) {
          hamburgerIcon.classList.toggle('is-active');
        }

        // Update label text
        if (label) {
          const isOpen = heroNav.classList.contains('is-open');
          label.textContent = isOpen ? 'Close' : menuTitle;
        }
      }
    });

    // Add close button functionality
    const closeButton = heroNav ? heroNav.querySelector('.hero-nav-close') : null;
    if (closeButton) {
      closeButton.addEventListener('click', function (event) {
        event.preventDefault();
        if (heroNav) {
          // Remove the is-open class
          heroNav.classList.remove('is-open');

          // Update hamburger icon animation
          const hamburgerIcon = toggle.querySelector('.hamburger-icon');
          if (hamburgerIcon) {
            hamburgerIcon.classList.remove('is-active');
          }

          // Update label text back to original
          if (label) {
            label.textContent = menuTitle;
          }
        }
      });
    }

    // Add click outside handler to close menu
    document.addEventListener('click', function (event) {
      if (heroNav && heroNav.classList.contains('is-open')) {
        // Check if click is outside the menu
        if (!heroNav.contains(event.target)) {
          // Remove the is-open class
          heroNav.classList.remove('is-open');

          // Update hamburger icon animation
          const hamburgerIcon = toggle.querySelector('.hamburger-icon');
          if (hamburgerIcon) {
            hamburgerIcon.classList.remove('is-active');
          }

          // Update label text back to original
          if (label) {
            label.textContent = menuTitle;
          }
        }
      }
    });
  });
}

/***/ }),

/***/ "./assets/js/mega-menu.js":
/*!********************************!*\
  !*** ./assets/js/mega-menu.js ***!
  \********************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
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

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   initMobileMenu: () => (/* binding */ initMobileMenu),
/* harmony export */   initMobileSubMenus: () => (/* binding */ initMobileSubMenus)
/* harmony export */ });
function initMobileMenu() {
  const navToggle = document.querySelector('.nav-toggle');
  const mobileNavPanel = document.getElementById('mobile-navigation-panel');
  const siteHeader = document.querySelector('.site-header');
  const siteMain = document.querySelector('.site-main');
  let headerPlaceholder = null;
  if (!navToggle || !mobileNavPanel || !siteHeader || !siteMain) {
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
    document.body.classList.add('mobile-menu-active');
    navToggle.classList.add('is-active');
    mobileNavPanel.classList.add('is-open');
    navToggle.setAttribute('aria-expanded', 'true');
    mobileNavPanel.setAttribute('aria-hidden', 'false');
  };
  const closeMenu = () => {
    document.body.classList.remove('mobile-menu-active');
    navToggle.classList.remove('is-active');
    mobileNavPanel.classList.remove('is-open');
    navToggle.setAttribute('aria-expanded', 'false');
    mobileNavPanel.setAttribute('aria-hidden', 'true');
  };
  navToggle.addEventListener('click', () => {
    if (navToggle.classList.contains('is-active')) {
      closeMenu();
    } else {
      openMenu();
    }
  });

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
/******/ 	/* webpack/runtime/compat get default export */
/******/ 	(() => {
/******/ 		// getDefaultExport function for compatibility with non-harmony modules
/******/ 		__webpack_require__.n = (module) => {
/******/ 			var getter = module && module.__esModule ?
/******/ 				() => (module['default']) :
/******/ 				() => (module);
/******/ 			__webpack_require__.d(getter, { a: getter });
/******/ 			return getter;
/******/ 		};
/******/ 	})();
/******/ 	
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
// This entry needs to be wrapped in an IIFE because it needs to be in strict mode.
(() => {
"use strict";
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
/* harmony import */ var _blocks_attorneys_js__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(_blocks_attorneys_js__WEBPACK_IMPORTED_MODULE_5__);
/* harmony import */ var _blocks_accordion_js__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ./blocks/accordion.js */ "./assets/js/blocks/accordion.js");
/* harmony import */ var _hero_dropdown_nav_js__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ./hero-dropdown-nav.js */ "./assets/js/hero-dropdown-nav.js");
/* harmony import */ var _blocks_animations_js__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! ./blocks/animations.js */ "./assets/js/blocks/animations.js");
//Import any JS here

 // Import the module








console.log('MAIN.JS - Imported initHeroDropdownNav:', typeof _hero_dropdown_nav_js__WEBPACK_IMPORTED_MODULE_7__.initHeroDropdownNav);
// Import other modules like initModals if you create them

/**
 * SWMW Law Theme
 * Main JavaScript file
 */

(function ($) {
  'use strict';

  console.log('MAIN.JS JQUERY WRAPPER EXECUTING');

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
    console.log('MAIN.JS DOCUMENT READY');
    (0,_mobile_menu_js__WEBPACK_IMPORTED_MODULE_1__.initMobileMenu)();
    (0,_mobile_menu_js__WEBPACK_IMPORTED_MODULE_1__.initMobileSubMenus)(); // Call the new mobile submenu initializer
    console.log('MAIN.JS - ABOUT TO CALL initMegaMenus()');
    (0,_mega_menu_js__WEBPACK_IMPORTED_MODULE_2__.initMegaMenus)();
    console.log('MAIN.JS - FINISHED CALLING initMegaMenus()');
    console.log('MAIN.JS - ABOUT TO CALL moveMegaPanels()');
    (0,_mega_menu_js__WEBPACK_IMPORTED_MODULE_2__.moveMegaPanels)();
    console.log('MAIN.JS - ABOUT TO CALL equalizeMegaMenuHeights()');
    try {
      (0,_mega_menu_js__WEBPACK_IMPORTED_MODULE_2__.equalizeMegaMenuHeights)();
      console.log('MAIN.JS - FINISHED CALLING equalizeMegaMenuHeights()');
    } catch (error) {
      console.error('MAIN.JS - ERROR in equalizeMegaMenuHeights():', error);
    }
    try {
      initModals(); // Assuming this is still initialized here
      console.log('MAIN.JS - FINISHED CALLING initModals()');
    } catch (error) {
      console.error('MAIN.JS - ERROR in initModals():', error);
    }
    try {
      (0,_button_hover_animation_js__WEBPACK_IMPORTED_MODULE_0__["default"])();
      console.log('MAIN.JS - FINISHED CALLING initializeButtonHoverAnimation()');
    } catch (error) {
      console.error('MAIN.JS - ERROR in initializeButtonHoverAnimation():', error);
    }
    try {
      (0,_blocks_results_js__WEBPACK_IMPORTED_MODULE_3__.initResultsSlider)(); // Initialize the results slider
      console.log('MAIN.JS - FINISHED CALLING initResultsSlider()');
    } catch (error) {
      console.error('MAIN.JS - ERROR in initResultsSlider():', error);
    }
    try {
      (0,_blocks_testimonials_js__WEBPACK_IMPORTED_MODULE_4__.initTestimonialsSlider)(); // Initialize the testimonials slider
      console.log('MAIN.JS - FINISHED CALLING initTestimonialsSlider()');
    } catch (error) {
      console.error('MAIN.JS - ERROR in initTestimonialsSlider():', error);
    }
    try {
      (0,_blocks_attorneys_js__WEBPACK_IMPORTED_MODULE_5__.initAttorneysSlider)(); // Initialize the attorneys slider
      console.log('MAIN.JS - FINISHED CALLING initAttorneysSlider()');
    } catch (error) {
      console.error('MAIN.JS - ERROR in initAttorneysSlider():', error);
    }
    try {
      initLoadMoreAttorneys(); // Initialize the load more attorneys functionality
      console.log('MAIN.JS - FINISHED CALLING initLoadMoreAttorneys()');
    } catch (error) {
      console.error('MAIN.JS - ERROR in initLoadMoreAttorneys():', error);
    }

    // Initialize Load More Results functionality
    try {
      initLoadMoreResults();
      console.log('MAIN.JS - FINISHED CALLING initLoadMoreResults()');
    } catch (error) {
      console.error('MAIN.JS - ERROR in initLoadMoreResults():', error);
    }
    try {
      new _blocks_accordion_js__WEBPACK_IMPORTED_MODULE_6__.AccordionBlock();
      console.log('MAIN.JS - FINISHED CALLING AccordionBlock()');
    } catch (error) {
      console.error('MAIN.JS - ERROR in AccordionBlock():', error);
    }
    console.log('MAIN.JS - ABOUT TO CALL initHeroDropdownNav()');
    try {
      (0,_hero_dropdown_nav_js__WEBPACK_IMPORTED_MODULE_7__.initHeroDropdownNav)(); // Initialize the hero dropdown navigation
      console.log('MAIN.JS - FINISHED CALLING initHeroDropdownNav()');
    } catch (error) {
      console.error('MAIN.JS - ERROR in initHeroDropdownNav():', error);
    }
    try {
      (0,_blocks_animations_js__WEBPACK_IMPORTED_MODULE_8__.initBlockAnimations)(); // Initialize block animations
      console.log('MAIN.JS - FINISHED CALLING initBlockAnimations()');
    } catch (error) {
      console.error('MAIN.JS - ERROR in initBlockAnimations():', error);
    }

    // Add global toggle for animations (for debugging)
    window.swmwToggleAnimations = function () {
      const body = document.body;
      if (body.classList.contains('no-animations')) {
        body.classList.remove('no-animations');
        console.log('SWMW: Animations enabled');
      } else {
        body.classList.add('no-animations');
        console.log('SWMW: Animations disabled');
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