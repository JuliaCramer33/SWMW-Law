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
  let maxInnerHeight = 0;
  const megaMenuTriggers = document.querySelectorAll(
    '.main-navigation .menu-item-has-mega-menu'
  );

  megaMenuTriggers.forEach((topItem) => {
    const topLink = topItem.querySelector(':scope > a');
    if (!topLink || !topLink.id) return;

    const panelId = topLink.id;
    const panel = document.querySelector(
      `.mega-menu-panel[aria-labelledby="${panelId}"]`
    );
    if (!panel) return;

    const panelInner = panel.querySelector('.mega-menu-panel-inner');
    const leftColumn = panel.querySelector('.mega-menu-column-left');
    const rightColumn = panel.querySelector(
      '.mega-menu-column-right-content'
    );

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

    const secondLevelItems = leftColumn.querySelectorAll(
      '.mega-menu-child-list > li.menu-item'
    );
    secondLevelItems.forEach((secondItem) => {
      const secondLevelLink = secondItem.querySelector(':scope > a');
      const thirdLevelSourceSubMenu = secondItem.querySelector(
        ':scope > .sub-menu.sub-menu-level-2'
      );
      if (thirdLevelSourceSubMenu) {
        const thirdLevelListItems =
          thirdLevelSourceSubMenu.querySelectorAll(':scope > li');
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
          thirdLevelListItems.forEach((li) =>
            newUl.appendChild(li.cloneNode(true))
          );
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
    document
      .querySelectorAll('.mega-menu-panel .mega-menu-panel-inner')
      .forEach((inner) => {
        inner.style.minHeight = maxInnerHeight + 'px';
      });
  }
}
