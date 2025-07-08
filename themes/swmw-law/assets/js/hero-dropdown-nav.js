/**
 * Hero Dropdown Navigation Toggle
 * Adds toggle functionality for dropdown navigation in hero sections
 */

export function initHeroDropdownNav() {
  // Find all hamburger menu toggles
  const toggles = document.querySelectorAll('.hamburger-menu-toggle');

  toggles.forEach(function (toggle) {
    // Get the menu title from the navigation block
    const heroNav = toggle.closest('.hero-dropdown-menu');
    const navBlock = heroNav ? heroNav.querySelector('.hero-nav-list') : null;

    let menuTitle = 'Menu'; // Default fallback

    if (navBlock) {
      // Try multiple ways to get the navigation title
      const navTitle = navBlock.getAttribute('data-menu-title') ||  // Custom data attribute
        navBlock.getAttribute('aria-label') ||         // WordPress navigation label
        navBlock.getAttribute('data-title') ||         // Alternative title attribute
        navBlock.querySelector('.wp-block-navigation__container')?.getAttribute('aria-label') ||
        navBlock.querySelector('.wp-block-navigation__container')?.getAttribute('data-title');

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
