/**
 * Hero Dropdown Navigation - Auto-generated from H2/H3 headings
 * Creates anchor navigation from page headings with smooth scrolling
 */

export function initHeroDropdownNav() {
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
      const contentSelectors = [
        'main',
        '.entry-content',
        '.content-area',
        '#main',
        '.container',
        'article',
        '.post-content',
        '.page-content'
      ];
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
      return text
        .toLowerCase()
        .replace(/[^a-z0-9\s-]/g, '')
        .replace(/\s+/g, '-')
        .replace(/-+/g, '-')
        .trim('-');
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
        // (portal behavior removed)
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

      // (portal cleanup removed)
    }

    /**
     * Ensure parent containers allow overflow for dropdown visibility
     */
    function ensureOverflowVisible() {
      // Find common parent containers that might clip the dropdown
      const parentSelectors = [
        '.hero-dropdown-menu',
        '.hero-dropdown-container',
        '.wp-block',
        '.container',
        '.content-area',
        'main',
        'article',
        '.entry-content',
        '.post-content',
        '.page-content',
        '.wp-block-hero-dropdown-menu-block',
        '.hero-dropdown-menu-block'
      ];

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
        top: Math.max(0, finalScrollPosition), // Ensure we don't scroll to negative position
        behavior: 'smooth'
      });
    }
  });
} 
