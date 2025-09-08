export function initMegaMenus() {
  const megaMenuItems = document.querySelectorAll(
    '.main-navigation .menu-item-has-mega-menu'
  );
  const hoverDelay = 50;

  megaMenuItems.forEach((topItem) => {
    const topLink = topItem.querySelector(':scope > a');
    const megaPanel = topItem.querySelector(':scope > .mega-menu-panel');
    let panelCloseTimer = null;
    let topItemCloseTimer = null;

    if (!topLink || !megaPanel) return;

    megaPanel.associatedTopLink = topLink;
    const rightColumn = megaPanel.querySelector(
      '.mega-menu-column-right-content'
    );
    const leftColumn = megaPanel.querySelector('.mega-menu-column-left'); // Added for completeness

    if (!leftColumn || !rightColumn) return;

    const measurePanelNaturalHeight = () => {
      const panelInner = megaPanel.querySelector('.mega-menu-panel-inner');
      if (!panelInner) return 0;
      const prev = {
        position: megaPanel.style.position,
        left: megaPanel.style.left,
        top: megaPanel.style.top,
        visibility: megaPanel.style.visibility,
        display: megaPanel.style.display,
        maxHeight: megaPanel.style.maxHeight,
        opacity: megaPanel.style.opacity,
        pointerEvents: megaPanel.style.pointerEvents,
        transform: megaPanel.style.transform,
      };
      megaPanel.style.position = 'absolute';
      megaPanel.style.left = '-9999px';
      megaPanel.style.top = '0px';
      megaPanel.style.visibility = 'hidden';
      megaPanel.style.display = 'block';
      megaPanel.style.maxHeight = 'none';
      megaPanel.style.opacity = '1';
      megaPanel.style.pointerEvents = 'none';
      megaPanel.style.transform = 'translateX(-50%) translateY(0)';
      const height = panelInner.offsetHeight; // includes padding
      megaPanel.style.position = prev.position;
      megaPanel.style.left = prev.left;
      megaPanel.style.top = prev.top;
      megaPanel.style.visibility = prev.visibility;
      megaPanel.style.display = prev.display;
      megaPanel.style.maxHeight = prev.maxHeight;
      megaPanel.style.opacity = prev.opacity;
      megaPanel.style.pointerEvents = prev.pointerEvents;
      megaPanel.style.transform = prev.transform;
      return height;
    };

    const computeAndSetTargetHeight = () => {
      const targetHeight = measurePanelNaturalHeight();
      megaPanel.style.setProperty('--mega-panel-target-height', targetHeight + 'px');
      // Also update shared height baseline to account for padding differences
      requestAnimationFrame(() => {
        try {
          const allPanels = document.querySelectorAll('.mega-menu-panel');
          let maxH = 0;
          allPanels.forEach((p) => {
            const prev = {
              position: p.style.position,
              left: p.style.left,
              top: p.style.top,
              visibility: p.style.visibility,
              display: p.style.display,
              maxHeight: p.style.maxHeight,
              opacity: p.style.opacity,
              pointerEvents: p.style.pointerEvents,
              transform: p.style.transform,
            };
            p.style.position = 'absolute';
            p.style.left = '-9999px';
            p.style.top = '0px';
            p.style.visibility = 'hidden';
            p.style.display = 'block';
            p.style.maxHeight = 'none';
            p.style.opacity = '1';
            p.style.pointerEvents = 'none';
            p.style.transform = 'translateX(-50%) translateY(0)';
            const inner = p.querySelector('.mega-menu-panel-inner');
            const h = inner ? inner.offsetHeight : p.scrollHeight;
            maxH = Math.max(maxH, h);
            p.style.position = prev.position;
            p.style.left = prev.left;
            p.style.top = prev.top;
            p.style.visibility = prev.visibility;
            p.style.display = prev.display;
            p.style.maxHeight = prev.maxHeight;
            p.style.opacity = prev.opacity;
            p.style.pointerEvents = prev.pointerEvents;
            p.style.transform = prev.transform;
          });
          document.documentElement.style.setProperty('--mega-panels-shared-height', maxH + 'px');
        } catch (e) {
          // no-op
        }
      });
    };

    const openPanel = () => {
      clearTimeout(panelCloseTimer);
      clearTimeout(topItemCloseTimer);
      computeAndSetTargetHeight();
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
      megaPanel.style.removeProperty('--mega-panel-target-height');
      rightColumn.innerHTML = '';
    };

    topItem.addEventListener('mouseenter', () => {
      clearTimeout(panelCloseTimer);
      computeAndSetTargetHeight();
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

    const allSecondLevelItems = leftColumn.querySelectorAll(
      '.mega-menu-child-list > li.menu-item'
    );
    allSecondLevelItems.forEach((secondItem) => {
      const secondLevelLink = secondItem.querySelector(':scope > a');
      const thirdLevelSourceSubMenu = secondItem.querySelector(
        ':scope > .sub-menu.sub-menu-level-2'
      );
      secondItem.addEventListener('mouseenter', () => {
        rightColumn.innerHTML = '';
        if (thirdLevelSourceSubMenu) {
          const thirdLevelListItems =
            thirdLevelSourceSubMenu.querySelectorAll(':scope > li');
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
            thirdLevelListItems.forEach((li) =>
              newUl.appendChild(li.cloneNode(true))
            );
            rightColumn.appendChild(newUl);
          }
        }
        allSecondLevelItems.forEach((item) =>
          item.classList.remove('is-active-child')
        );
        secondItem.classList.add('is-active-child');
        requestAnimationFrame(() => {
          computeAndSetTargetHeight();
        });
      });
    });
  });
}

export function moveMegaPanels() {
  const siteHeader = document.querySelector('.site-header');
  if (!siteHeader) {
    return;
  }
  const megaPanels = document.querySelectorAll('.mega-menu-panel');
  megaPanels.forEach((panel) => {
    siteHeader.appendChild(panel);
  });
}

export function equalizeMegaMenuHeights() {
  // Set a per-panel minimum height variable equal to the tallest panel
  const panels = Array.from(document.querySelectorAll('.mega-menu-panel'));
  if (panels.length === 0) return;

  // Temporarily show panels off-canvas to measure natural heights
  const measurements = panels.map((panel) => {
    const prev = {
      position: panel.style.position,
      left: panel.style.left,
      top: panel.style.top,
      visibility: panel.style.visibility,
      display: panel.style.display,
      maxHeight: panel.style.maxHeight,
      opacity: panel.style.opacity,
      pointerEvents: panel.style.pointerEvents,
      transform: panel.style.transform,
    };
    panel.style.position = 'absolute';
    panel.style.left = '-9999px';
    panel.style.top = '0px';
    panel.style.visibility = 'hidden';
    panel.style.display = 'block';
    panel.style.maxHeight = 'none';
    panel.style.opacity = '1';
    panel.style.pointerEvents = 'none';
    panel.style.transform = 'translateX(-50%) translateY(0)';
    const height = panel.scrollHeight;
    return { panel, prev, height };
  });

  const maxHeight = measurements.reduce((m, { height }) => Math.max(m, height), 0);
  measurements.forEach(({ panel, prev }) => {
    panel.style.setProperty('--mega-panel-min-height', maxHeight + 'px');
    document.documentElement.style.setProperty('--mega-panels-shared-height', maxHeight + 'px');
    // restore
    panel.style.position = prev.position;
    panel.style.left = prev.left;
    panel.style.top = prev.top;
    panel.style.visibility = prev.visibility;
    panel.style.display = prev.display;
    panel.style.maxHeight = prev.maxHeight;
    panel.style.opacity = prev.opacity;
    panel.style.pointerEvents = prev.pointerEvents;
    panel.style.transform = prev.transform;
  });
}
