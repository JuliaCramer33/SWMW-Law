export function initMobileMenu() {
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
    if (window.innerWidth <= 1024) { // Corresponds to $breakpoint-lg
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

  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && navToggle.classList.contains('is-active')) {
      closeMenu();
    }
  });

  // We no longer need the resize listener, CSS handles the height.
  // window.addEventListener('resize', setPanelHeight);
}

export function initMobileSubMenus() {
  const mobileNavList = document.querySelector(
    '#mobile-navigation-panel .mobile-primary-nav-list'
  );
  if (!mobileNavList) return;

  const parentMenuItems = mobileNavList.querySelectorAll(
    '.menu-item-has-children'
  );

  const updateParentHeights = (startElement) => {
    let parent = startElement.parentElement.closest(
      '.menu-item-has-children.is-open'
    );
    while (parent) {
      const subMenu = parent.querySelector(':scope > .sub-menu');
      if (subMenu) {
        const newHeight = subMenu.scrollHeight;
        subMenu.style.maxHeight = `${newHeight}px`;
      }
      parent = parent.parentElement.closest(
        '.menu-item-has-children.is-open'
      );
    }
  };

  parentMenuItems.forEach((item) => {
    const link = item.querySelector(':scope > a');
    const subMenu = item.querySelector(':scope > .sub-menu');

    if (!subMenu || !link) return;

    // Ensure arrow exists
    if (!link.querySelector('.submenu-arrow')) {
      const arrow = document.createElement('span');
      arrow.classList.add('submenu-arrow');
      link.appendChild(arrow);
    }

    link.addEventListener('click', (e) => {
      e.preventDefault();
      const isCurrentlyOpen = item.classList.contains('is-open');

      // Find sibling items at the same level and close them
      const parentUl = item.parentElement;
      const siblingItems = parentUl.querySelectorAll(
        ':scope > .menu-item-has-children'
      );
      siblingItems.forEach((sibling) => {
        if (sibling !== item) {
          sibling.classList.remove('is-open');
          const siblingSubMenu = sibling.querySelector(
            ':scope > .sub-menu'
          );
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
  mobileNavList.querySelectorAll('.sub-menu').forEach((subMenu) => {
    subMenu.style.maxHeight = null;
    const parentLi = subMenu.closest('.menu-item-has-children');
    if (parentLi) {
      parentLi.classList.remove('is-open');
    }
  });
}
